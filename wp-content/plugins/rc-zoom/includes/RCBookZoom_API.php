<?php

class RCBookZoom_API {

	public function __construct() {
		add_action('rest_api_init', [$this, 'register_endpoints']);
	}

	public function register_endpoints():void {
		register_rest_route('rc-book-zoom/v1', '/availability', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_availability'],
			'permission_callback' => [$this, 'check_permission'],
		]);

		register_rest_route('rc-book-zoom/v1', '/exceptions', [
			'methods' => WP_REST_Server::READABLE,
			'callback' => [$this, 'get_time_slot_exceptions'],
			'permission_callback' => [$this, 'check_permission'],
		]);
	}

	public function get_availability(WP_REST_Request $request): WP_Error|WP_REST_Response|WP_HTTP_Response {
		global $wpdb; // Add this line to make $wpdb accessible

		$user_id = $request->get_param('user_id'); // Get the user ID from the request parameter

		// Retrieve availability data from the database using wpdb
		$availability_table = $wpdb->prefix . 'rc_zoom_default_time_slots'; // Replace 'rc_zoom_' with your table prefix
		$query = $wpdb->prepare(
			"SELECT * FROM $availability_table WHERE UserID = %d",
			$user_id
		);
		$results = $wpdb->get_results($query);

		if ($results) {
			// Process and format the retrieved availability data as needed
			$availability_data = array();
			foreach ($results as $result) {
				$availability_data[] = array(
					'day_of_week' => $result->DayOfWeek,
					'start_date' => $result->StartDate,
					'end_date' => $result->EndDate,
					'start_time' => $result->StartTime,
					'end_time' => $result->EndTime,
				);
			}

			// Return the availability data as the API response
			return rest_ensure_response(array(
				'success' => true,
				'message' => 'Availability data',
				'data' => $availability_data,
			));
		} else {
			// Return an error response if no availability data found
			return rest_ensure_response(array(
				'success' => false,
				'message' => 'No availability data found',
			));
		}
	}
	public function get_time_slot_exceptions(WP_REST_Request $request): WP_Error|WP_REST_Response|WP_HTTP_Response {
		global $wpdb;

		$user_id = $request->get_param('user_id'); // Get the user ID from the request parameter


		$exceptions_table = $wpdb->prefix . 'rc_zoom_unavailable_slots';
		// Prepare the query to retrieve the time slot exceptions
		$query = $wpdb->prepare(
			"SELECT * FROM $exceptions_table WHERE UserID = %d",
			$user_id
		);

		// Execute the query and fetch the results
		$results = $wpdb->get_results($query);

		if ($results) {
			// Process and format the retrieved availability data as needed
			$exception_data = array();
			foreach ($results as $result) {
				$exception_data[] = array(
					'day_of_week' => $result->DayOfWeek,
					'start_date' => $result->StartDate,
					'end_date' => $result->EndDate,
					'start_time' => $result->StartTime,
					'end_time' => $result->EndTime,
				);
			}

			// Return the availability data as the API response
			return rest_ensure_response(array(
				'success' => true,
				'message' => 'Exception data',
				'data' => $exception_data,
			));
		} else {
			// Return an error response if no availability data found
			return rest_ensure_response(array(
				'success' => false,
				'message' => 'No exception data found',
			));
		}
	}

	public function check_permission(): bool {

		// Implement your authentication and permission checks here
		if (is_user_logged_in() && current_user_can('edit_posts')) {

			// User is logged in and has the required capability, so they have permission
			return true;
		}

		// If the above condition fails, the user does not have permission
		return false;
	}
}
