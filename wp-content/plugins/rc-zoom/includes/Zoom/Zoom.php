<?php

require_once  WP_PLUGIN_DIR . '/rc-zoom/includes/RCDataEncryption.php';

class Zoom {
	private string $zoom_auth_endpoint;
	private string $zoom_client_id;
	private string $zoom_client_secret;
	private string $zoom_account_id;
	private string $BASEURL;
	public string $user_id;
	public mixed $dataEncrypter;
	private string $zoom_access_token;
	private string $zoom_token_type;

	public function __construct() {
		$this->BASEURL = 'https://zoom.us/v2';
		// Break if no settings are configured
		if ( '' === get_option('zoom_client_id'     ) ||
		     '' === get_option('zoom_client_secrete') ||
		     '' === get_option('zoom_account_id'    ))
		{
			error_log('No Secrets/Tokens Set. Check Configuration');
			return;
		}
		// Get and Decrypt Properties from DB
		$this->dataEncrypter = new DataEncryption();
		$this->zoom_client_id = $this->dataEncrypter->decrypt(get_option('zoom_client_id'));
		$this->zoom_client_secret =  $this->dataEncrypter->decrypt(get_option('zoom_client_secret'));
		$this->zoom_account_id = $this->dataEncrypter->decrypt(get_option('zoom_account_id'));

		// Check for exising token TODO: CHECK TIME STAMP
		if ( '' === get_option('zoom_access_token'  ) ||
		     '' === get_option('zoom_token_type'    ))
		{
			$this->refreshToken();
		}
		// Get current tokens
		$this->zoom_access_token = get_option(  'zoom_access_token'   );
		$this->zoom_token_type   = get_option(  'zoom_token_type'     );


		$this->zoom_auth_endpoint = 'https://zoom.us/oauth/token';
		$this->user_id = 'me';
		$this->getMeetings();


	}
	public function getMeetings()  {

			$zoom_endpoint = $this->BASEURL."/users/$this->user_id/meetings/";

			$headers=[
				'authorization' => "Bearer $this->zoom_access_token",
			];
			$response = wp_remote_get($zoom_endpoint, [
				'headers' => $headers,
			]);

			if (is_wp_error( $response)){
				$error_message = $response->get_error_message();
				error_log($error_message);
			}else {
				$response_code = wp_remote_retrieve_response_code( $response);
				$response_body = json_decode(wp_remote_retrieve_body ($response));

				if($response_code >= 400){
					if ($response_code === 401 && $response_body->code === 124){//expired token
						error_log("Refreshing Token");
						$this->refreshToken();

						return [];
					}
					error_log($response_code);
					error_log(print_r($response_body, true));
					return null;
				}
				return $response_body;
			}
			return null;
		}

	public function refreshToken ():void{


		$zoom_endpoint = $this->zoom_auth_endpoint;
		$authorization = base64_encode( $this->zoom_client_id . ':' . $this->zoom_client_secret );
		$headers = [
			'Authorization' => 'Basic ' . $authorization,
		];
		$data = [
			'grant_type'    => 'account_credentials',
			'account_id'    => $this->zoom_account_id,
		];

		$args = array(
			'body'        => $data,
		);
		$response = wp_remote_post( $zoom_endpoint, [
			'headers' => $headers,
			'body' => $data,
		] );

		if (is_wp_error( $response)){
			$error_message = $response->get_error_message();
			error_log($error_message);
		}else {
			$response_code = wp_remote_retrieve_response_code( $response);
			$response_body = json_decode(wp_remote_retrieve_body ($response));
			if ($response_code >= 400){
				error_log($response_code);
				error_log(print_r($response_body, true));
				return;
			}

			if ($response_code >= 200){
				update_option('zoom_access_token', $response_body->access_token);
				update_option('zoom_token_type', $response_body->token_type);
				update_option('zoom_expires_in', $response_body->expires_in);
				update_option('zoom_scope', $response_body->scope);
				return;
			}

		}
	}
}