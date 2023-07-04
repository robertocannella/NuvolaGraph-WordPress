<?php

class AdminFormConfiguration {

	public function __construct( public DataEncryption $data_encryption ) {
		add_action( 'admin_menu', [ $this, 'adminPage' ] );
		add_action( 'admin_init', [ $this, 'settings' ] );

		// These filters here because sanitize_text gets called twice:
		// https://core.trac.wordpress.org/ticket/21989
		// https://developer.wordpress.org/reference/functions/register_setting/

		add_filter( 'pre_update_option_zoom_account_id', [ $this, 'encryptData' ] );
		add_filter( 'pre_update_option_zoom_client_id', [ $this, 'encryptData' ] );
		add_filter( 'pre_update_option_zoom_client_secret', [ $this, 'encryptData' ] );

	}


	function settings(): void {

		/******************************* SECTION ONE *********/
		// Create Section
		add_settings_section(
			id: 'rbz_first_section',  // slug-name ot identify the section
			title: 'Zoom Server to Server configuration', // Formatted Title of the section, shown as the heading for the section
			callback: [
				$this,
				'serverToServerHtml'
			], // A function that echos out any content at the top of the section (between heading and fields)
			page: 'rc-book-zoom-settings-page', // the slug-name of the settings page
			args: [
				'before_section' => '<hr>', // HTML content to prepend to the section's HTML output.
				'after_section'  => '',     // HTML content to append to the section's HTML output.
				'section_class'  => 'class_name' // The class name for the section.
			]
		);


		// Register Zoom Account ID
		add_settings_field(
			id: 'zoom_account_id', // slug-name to identify the field
			title: 'Account ID',    // Show as the label for the field during input
			callback: [
				$this,
				'accountIdHtml'
			],   // Function that fills the field with desired inputs. Should echo its output
			page: 'rc-book-zoom-settings-page', // slug-name of the section of the settings page in which to show the box
			section: 'rbz_first_section' ); // a reference to the section to attach to

		register_setting(
			option_group: 'bookzoomplugin',
			option_name: 'zoom_account_id',
			args: [
				'sanitize_callback' => null,
				'default'           => ''
			] );


		// Register Zoom Client ID
		add_settings_field(
			id: 'zoom_client_id',
			title: 'Client ID',
			callback: [ $this, 'clientIdHtml' ],
			page: 'rc-book-zoom-settings-page',
			section: 'rbz_first_section' );

		register_setting(
			option_group: 'bookzoomplugin',
			option_name: 'zoom_client_id',
			args: [
				'sanitize_callback' => null,
				'default'           => ''
			] );

		// Register Zoom Client Secret
		add_settings_field(
			id: 'zoom_client_secret',
			title: 'Client Secret',
			callback: [ $this, 'clientSecretHtml' ],
			page: 'rc-book-zoom-settings-page',
			section: 'rbz_first_section' );

		register_setting(
			option_group: 'bookzoomplugin',
			option_name: 'zoom_client_secret',
			args: [
				'sanitize_callback' => null,
				'default'           => ''
			] );


	}

	function encryptData( $input ): string {

		$submitted_key = sanitize_text_field( $input );

		return $this->data_encryption->encrypt( $submitted_key );

	}

	function decryptData( $input ): string {

		if ( $input ) {
			return $this->data_encryption->decrypt( $input );
		}

		return 'Not Set';
	}




	function serverToServerHtml(): void { ?>
		<button class="button button-secondary" type="button" id="reveal-secrets-button">Reveal</button>
		<?php
	}


	function clientSecretHtml(): void {
		$encrypted_key = get_option( 'zoom_client_secret' );
		if ( $encrypted_key === '' ) {
			$decrypted_key = 'Not Set';
		} else {
			$decrypted_key = $this->decryptData( get_option( 'zoom_client_secret' ) );
		}

		?>
		<input type="password" class="key-field" name="zoom_client_secret"
		       value="<?php echo esc_attr( $decrypted_key ) ?>"/>

		<?php
	}

	function clientIdHtml(): void {
		$encrypted_key = get_option( 'zoom_client_id' );
		if ( $encrypted_key === '' ) {
			$decrypted_key = 'Not Set';
		} else {
			$decrypted_key = $this->decryptData( get_option( 'zoom_client_id' ) );
		}

		?>
		<input type="password" class="key-field" name="zoom_client_id"
		       value="<?php echo esc_attr( $decrypted_key ) ?>"/>

		<?php
	}

	function accountIdHtml(): void {
		$encrypted_key = get_option( 'zoom_account_id' );
		if ( $encrypted_key === '' ) {
			$decrypted_key = 'Not Set';
		} else {
			$decrypted_key = $this->decryptData( get_option( 'zoom_account_id' ) );
		}

		?>
		<input type="password" class="key-field" name="zoom_account_id"
		       value="<?php echo esc_attr( $decrypted_key ) ?>"/>

		<?php
	}

	function bookZoomHtml(): void {
		?>
		<!--        The WordPress docs recommend keeping entire contents inside a div with a class of 'wrap'-->
		<div class="wrap">
			<h1>Book Zoom Settings</h1>

			<form action="options.php" method="post">
				<?php
				settings_fields( 'bookzoomplugin' );
				do_settings_sections( 'rc-book-zoom-settings-page' );
				submit_button( __( 'Save Settings', 'rc-book-zoom' ) );
				?>
			</form>



		</div>
		<?php
	}

	function adminPage(): void {
		add_menu_page(
			page_title: 'Zoom Settings',
			menu_title: esc_html__( 'Book Zoom', 'rc_book_zoom' ),
			capability: 'manage_options',
			menu_slug: 'rc-book-zoom-settings-page',
			callback: [ $this, 'bookZoomHtml' ]

		);
	}

}