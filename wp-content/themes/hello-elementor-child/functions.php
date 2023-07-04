<?php
require_once 'includes/ZoomUser.php';
require_once 'includes/ZoomMeeting.php';
require_once 'includes/ETimeZones.php';

/* Function to enqueue stylesheet from parent theme */

function child_enqueue__parent_scripts() {

    wp_enqueue_style( 'parent', get_template_directory_uri().'/style.css' );

}

add_action( 'wp_enqueue_scripts', 'child_enqueue__parent_scripts');


//Add the shortcode
add_shortcode( 'contact-form', 'display_contact_form' );

/**
 * This function displays the validation messages, the success message, the container of the validation messages, and the
 * contact form.
 */
function display_contact_form():void {

	$validation_messages = [];
	$success_message = '';

	if ( isset( $_POST['contact_form'] ) ) {

		//Sanitize the data
		$full_name = isset( $_POST['full_name'] ) ? sanitize_text_field( $_POST['full_name'] ) : '';
		$email     = isset( $_POST['email'] ) ? sanitize_text_field( $_POST['email'] ) : '';
		$message   = isset( $_POST['message'] ) ? sanitize_textarea_field( $_POST['message'] ) : '';

		//Validate the data
		if ( strlen( $full_name ) === 0 ) {
			$validation_messages[] = esc_html__( 'Please enter a valid name.', 'twentytwentyone' );
		}

		if ( strlen( $email ) === 0 or
		     ! is_email( $email ) ) {
			$validation_messages[] = esc_html__( 'Please enter a valid email address.', 'twentytwentyone' );
		}

		if ( strlen( $message ) === 0 ) {
			$validation_messages[] = esc_html__( 'Please enter a valid message.', 'twentytwentyone' );
		}

		//Send an email to the WordPress administrator if there are no validation errors
		if ( empty( $validation_messages ) ) {

			$mail    = get_option( 'admin_email' );
			$subject = 'New message from ' . $full_name;
			$message = $message . ' - The email address of the customer is: ' . $mail;

			wp_mail( $mail, $subject, $message );

			$success_message = esc_html__( 'Your message has been successfully sent.', 'twentytwentyone' );

		}

	}

	//Display the validation errors
	if ( ! empty( $validation_messages ) ) {
		foreach ( $validation_messages as $validation_message ) {
			echo '<div class="validation-message">' . esc_html( $validation_message ) . '</div>';
		}
	}

	//Display the success message
	if ( strlen( $success_message ) > 0 ) {
		echo '<div class="success-message">' . esc_html( $success_message ) . '</div>';
	}

	?>

	<!-- Echo a container used that will be used for the JavaScript validation -->
	<div id="validation-messages-container"></div>

	<form id="contact-form" action="<?php echo esc_url( get_permalink() ); ?>"
	      method="post">

		<input type="hidden" name="contact_form">

		<div class="form-section">
			<label for="full-name"><?php echo esc_html( 'Full Name', 'twentytwentyone' ); ?></label>
			<input type="text" id="full-name" name="full_name">
		</div>

		<div class="form-section">
			<label for="email"><?php echo esc_html( 'Email', 'twentytwentyone' ); ?></label>
			<input type="text" id="email" name="email">
		</div>

		<div class="form-section">
			<label for="message"><?php echo esc_html( 'Message', 'twentytwentyone' ); ?></label>
			<textarea id="message" name="message"></textarea>
		</div>

		<input type="submit" id="contact-form-submit" value="<?php echo esc_attr( 'Submit', 'twentytwentyone' ); ?>">

	</form>

	<?php

}


//zoom_generate_token();
//$zoom_user = zoom_get_user_details('me');


//$meeting = new ZoomMeeting();
//$meeting->agenda = "test agenda";
//$meeting->duration = 60; // The meeting's scheduled duration, in minutes.
//$meeting->startTime = '2023-07-03T12:02:00Z';
//$meeting->timezone = 'America/New_York';
//$meeting->topic = "New Meeting";
//$zoom_meeting = zoom_create_meeting('me', $meeting);
//var_dump($zoom_meeting);

$meeting_id = 74104998773;

//$meeting = zoom_get_meeting($meeting_id);
//var_dump($meeting);


//var_dump($meeting);
//$registrants = zoom_get_registrants($meeting_id);

//var_dump($registrants);

function zoom_get_registrants($meeting_id){

	$zoom_endpoint = "https://api.zoom.us/v2/meetings/$meeting_id/registrants";
	$bearer_token = get_option('zoom_access_token');
	$headers=[
		'authorization' => "Bearer $bearer_token",
	];

	$response = wp_remote_get($zoom_endpoint, [
		'headers' => $headers,
	]);

	if (is_wp_error( $response)){
		$error_message = $response->get_error_message();
		log_to_screen("<span style='color: red; font-weight: bold;'>Something went wrong: $error_message</span>");
	}else {
		$response_code = wp_remote_retrieve_response_code( $response);
		$response_body = json_decode(wp_remote_retrieve_body ($response));

		var_dump($response_body);
	}
	return null;
}

function zoom_get_meeting( int $meeting_id ):ZoomMeeting|null {

	$zoom_endpoint = "https://api.zoom.us/v2/meetings/$meeting_id";
	$bearer_token = get_option('zoom_access_token');
	$headers=[
		'authorization' => "Bearer $bearer_token",
	];

	$response = wp_remote_get($zoom_endpoint, [
		'headers' => $headers,
	]);

	if (is_wp_error( $response)){
		$error_message = $response->get_error_message();
		log_to_screen("<span style='color: red; font-weight: bold;'>Something went wrong: $error_message</span>");
	}else {
		$response_code = wp_remote_retrieve_response_code( $response);
		$response_body = json_decode(wp_remote_retrieve_body ($response));

		return new ZoomMeeting($response_body);
	}
	return null;
}

function zoom_create_meeting($user_id, ZoomMeeting $meeting):ZoomMeeting|null{

	$zoom_endpoint = "https://api.zoom.us/v2/users/$user_id/meetings";
	$bearer_token = get_option('zoom_access_token');

	$headers=[
		'authorization' => "Bearer $bearer_token",
		'Content-Type' => 'application/json',
	];
	$data = [
		'agenda' => $meeting->getAgenda(),
		'duration' => $meeting->getDuration(),
		'start_time' => $meeting->getStartTime(),
		'timezone' => $meeting->getTimezone(),
		'topic'=> $meeting->getTopic()
	];

	$response = wp_remote_post($zoom_endpoint, [
		'headers' => $headers,
		'body' => json_encode($data)
	]);

	if (is_wp_error( $response)){
		$error_message = $response->get_error_message();
		log_to_screen("<span style='color: red; font-weight: bold;'>Something went wrong: $error_message</span>");
	}else {
		$response_code = wp_remote_retrieve_response_code( $response);
		$response_body = json_decode(wp_remote_retrieve_body ($response));
		if ($response_code >= 200 && $response_code < 300){
			log_to_screen("<span style='color: green; font-weight: bold;'>$response_body->id</span>");
		}
		else {
			log_to_screen("<span style='color: red; font-weight: bold;'>$response_code</span>");
		}

		return new ZoomMeeting($response_body);
	}
	return null;
}

function zoom_get_user_details($user_id):ZoomUser|null{
    $zoom_endpoint = "https://api.zoom.us/v2/users/$user_id";
    $bearer_token = get_option('zoom_access_token');

    $headers=[
        'authorization' => "Bearer $bearer_token",

    ];
    $response = wp_remote_get($zoom_endpoint, [
        'headers' => $headers,
    ]);

	if (is_wp_error( $response)){
		$error_message = $response->get_error_message();
		log_to_screen("<span style='color: red; font-weight: bold;'>Something went wrong: $error_message</span>");
	}else {
		$response_code = wp_remote_retrieve_response_code( $response);
		$response_body = json_decode(wp_remote_retrieve_body ($response));

		return new ZoomUser($response_body);
	}
	return null;
}


function log_to_screen(string $string) :void{
	echo '<pre id="zp-notification" style="width: 100%; background-color: white; position:absolute; z-index: 9999999;">';
    echo $string;
	echo '</pre>';
}