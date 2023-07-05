<?php

/*
  Plugin Name: RC Book Zoom Meeting
  Version: 1.0
  Description: Add book Zoom Call functionality.
  Author: Roberto Cannella
  Author URI: https://www.robertocannella.com/
  Text Domain: rc-book-zoom
  Domain Path: /languages
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
} // Exit if accessed directly

include_once 'includes/RCDataEncryption.php';
include_once 'includes/AdminFormConfiguration.php';
include_once 'includes/AdminFormTokenInfo.php';
include_once 'includes/AdminFormListMeetings.php';
include_once 'includes/AdminFormCalendar.php';
include_once 'includes/RCBookZoom_DB.php';
include_once 'includes/RCBookZoom_API.php';

class RCBookZoom {
	private RCBookZoom_DB $db;
	private RCBookZoom_API $api;

	public function __construct() {

		$this->db = new RCBookZoom_DB();
		$this->api = new RCBookZoom_API();
		// add_filter('admin_footer_text', [$this,'rcFooterText']);
		// add_filter('the_content',[$this,'ifWrap']);
		add_action( 'wp_enqueue_scripts', [ $this, 'rcScriptLoader' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAdminScripts' ] );
		add_action( 'init', [ $this, 'init' ] );


	}

	public function init(): void {
		//Plugin Initialization
		$this->db->create_tables();

		// Enable REST API
		add_filter( 'rest_authentication_errors', function ( $result ) {
			if ( ! empty( $result ) ) {
				return $result;
			}
			if ( ! is_user_logged_in() ) {
				return new WP_Error( 'rest_not_logged_in', __( 'You are not currently logged in.' ), array( 'status' => 401 ) );
			}
			return $result;
		} );


	}

	public function rcScriptLoader(): void {

		// FRONT END JavaScript
		//wp_die(dirname(plugin_basename(__FILE__)) . '/rc-word-count-plugin.js');
		//wp_die(plugins_url( '../rc-book-zoom-plugin.js', __FILE__ ));
		wp_enqueue_script( 'rc-book-zoom-js', plugins_url( '/rc-book-zoom-plugin.js', __FILE__ ), null, time() );
		wp_enqueue_style( 'rc-book-zoom-css', plugins_url( '/rc-book-zoom-plugin.css', __FILE__ ), null, time() );

		wp_localize_script( 'rc-book-zoom-js', 'globalSite', [
			'siteURL' => get_site_url()
		] );
	}

	public function enqueueAdminScripts( $hook ): void {
		// Enqueue scripts only on your plugin's admin page
		if ( isset( $_GET['page'] ) &&
		     ( $_GET['page'] === 'rc-book-zoom-settings-page' ||
		       $_GET['page'] === 'rc-book-zoom-token'         ||
		       $_GET['page'] === 'rc-book-zoom-calendar'      ||
		       $_GET['page'] === 'rc-book-zoom-list-meetings'
		     ) ) {

			wp_enqueue_script( 'rc-book-zoom-admin-js', plugins_url( '/rc-book-zoom-admin.js', __FILE__ ), null, time() );
			wp_enqueue_script( 'rc-book-zoom-index-js', plugins_url( '/build/index.js', __FILE__ ),  ['wp-element'], time() );
			wp_enqueue_style( 'rc-book-zoom-admin-css', plugins_url( '/rc-book-zoom-admin.css', __FILE__ ), null, time() );
			wp_enqueue_style( 'rc-book-zoom-tailwind-css', plugins_url( '/build/styles.css', __FILE__ ), null, time() );
			wp_localize_script('rc-book-zoom-index-js','globalSiteData', [
				'siteUrl' => get_site_url(),
				'nonceX' => wp_create_nonce('wp_rest')
			]);

		}
	}


}

$rc_book_zoom          = new RCBookZoom();
$rc_book_zoom_form     = new AdminFormConfiguration( new DataEncryption() );
$rc_zoom_token_info    = new AdminFormTokenInfo();
$rc_zoom_list_meetings = new AdminFormListMeetings();
$rc_zoom_calendar      = new AdminFormCalendar();
