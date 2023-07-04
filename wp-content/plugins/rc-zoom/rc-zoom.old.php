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

class RCBookZoom {

	public function __construct( public DataEncryption $data_encryption ) {
		add_action( 'admin_menu', [ $this, 'adminPage' ] );
		add_action( 'admin_init', [ $this, 'settings' ] );
		// add_filter('admin_footer_text', [$this,'rcFooterText']);
		// add_filter('the_content',[$this,'ifWrap']);
		add_action( 'wp_enqueue_scripts', [ $this, 'rcScriptLoader' ] );
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueueAdminScripts' ] );


		// These filters here because sanitize_text gets called twice:
		// https://core.trac.wordpress.org/ticket/21989
		// https://developer.wordpress.org/reference/functions/register_setting/

		add_filter( 'pre_update_option_zoom_account_id', [ $this, 'encryptData' ] );
		add_filter( 'pre_update_option_zoom_client_id', [ $this, 'encryptData' ] );
		add_filter( 'pre_update_option_zoom_client_secret', [ $this, 'encryptData' ] );

	}

	function rcScriptLoader(): void {

		// FRONT END JavaScript
		//wp_die(dirname(plugin_basename(__FILE__)) . '/rc-word-count-plugin.js');
		wp_enqueue_script( 'rc-book-zoom-js', plugins_url( '/rc-book-zoom-plugin.js', __FILE__ ) );
		wp_enqueue_style( 'rc-book-zoom-css', plugins_url( '/rc-book-zoom-plugin.css', __FILE__ ) );
		wp_localize_script( 'rc-book-zoom-js', 'globalSite', [
			'siteURL' => get_site_url()
		] );
	}

	public function enqueueAdminScripts( $hook ): void {
		// Enqueue scripts only on your plugin's admin page
		if ( isset( $_GET['page'] ) && $_GET['page'] === 'rc-book-zoom-settings-page' ) {
			wp_enqueue_script( 'rc-book-zoom-admin-js', plugins_url( '/rc-book-zoom-admin.js', __FILE__ ) );
			wp_enqueue_style( 'rc-book-zoom-admin-css', plugins_url( '/rc-book-zoom-admin.css', __FILE__ ) );
		}
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

		/******************************* SECTION TWO *********/
		// Create Section
		add_settings_section(
			id: 'rbz_second_section',  // slug-name ot identify the section
			title: 'Generated Token Details', // Formatted Title of the section, shown as the heading for the section
			callback: [$this, 'tokenDetailsHtml'], // A function that echos out any content at the top of the section (between heading and fields)
			page: 'rc-book-zoom-settings-page', // the slug-name of the settings page
			args: [
				'before_section' => '<hr>', // HTML content to prepend to the section's HTML output.
				'after_section'  => '',     // HTML content to append to the section's HTML output.
				'section_class'  => 'class_name' // The class name for the section.
			]
		);

		// Register Access token
		add_settings_field(
			id: 'zoom_access_token',
            title: 'Access Token',
            callback: [$this, 'accessTokenHtml'],
            page: 'rc-book-zoom-settings-page',
            section: 'rbz_second_section'
		);
        register_setting(
                option_group: 'bookzoomtoken',
                option_name: 'zoom_access_token'
        );

		// Register Token Type
		add_settings_field(
			id: 'zoom_token_type',
			title: 'Token Type',
			callback: [$this, 'tokenTypeHtml'],
			page: 'rc-book-zoom-settings-page',
			section: 'rbz_second_section'
		);
		register_setting(
			option_group: 'bookzoomptoken',
			option_name: 'zoom_token_type'
		);



//		// Register Word Count Display Location
//		add_settings_field(
//			id: 'wcp_location',
//			title: 'Display Location',
//			callback: [
//				$this,
//				'locationHTML'
//			],
//			page: 'rc-book-zoom-settings-page',
//			section: 'rbz_second_section'
//		);

//		register_setting(
//			option_group: 'bookzoomplugin',
//			option_name: 'wcp_location',
//			args: [
//				'sanitize_callback' => [ $this, 'sanitizeLocation' ],
//				'default'           => '0'
//			]
//		);

//		// Register Word Count Headline
//		add_settings_field( 'wcp_headline', 'Headline', [
//			$this,
//			'headlineHTML'
//		], 'rc-book-zoom-settings-page', 'rbz_second_section' );
//		register_setting( 'bookzoomplugin', 'wcp_headline', [
//			'sanitize_callback' => 'sanitize_text_field',
//			'default'           => 'Post Statistics'
//		] );
//
//		// Register Show Word Count
//		add_settings_field( 'wcp_wordcount', 'Word Count', [
//			$this,
//			'checkboxHTML'
//		], 'rc-book-zoom-settings-page', 'rbz_second_section', [ 'theName' => 'wcp_wordcount' ] );
//		register_setting( 'bookzoomplugin', 'wcp_wordcount', [
//			'sanitize_callback' => 'sanitize_text_field',
//			'default'           => '1'
//		] );
//
//		// Register Show Character Count
//		add_settings_field( 'wcp_charcount', 'Character Count', [
//			$this,
//			'checkboxHTML'
//		], 'rc-book-zoom-settings-page', 'rbz_second_section', [ 'theName' => 'wcp_charcount' ] );
//		register_setting( 'bookzoomplugin', 'wcp_charcount', [
//			'sanitize_callback' => 'sanitize_text_field',
//			'default'           => '1'
//		] );
//		// Register Show Read Time
//		add_settings_field( 'wcp_readtime', 'Read Time', [
//			$this,
//			'checkboxHTML'
//		], 'rc-book-zoom-settings-page', 'rbz_second_section', [ 'theName' => 'wcp_readtime' ] );
//		register_setting( 'bookzoomplugin', 'wcp_readtime', [
//			'sanitize_callback' => 'sanitize_text_field',
//			'default'           => '1'
//		] );

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

	function sanitizeLocation( $input ) {

		if ( $input != 0 and $input != 1 ) {
			add_settings_error( 'wcp_location', 'wcp_location_error', 'Display location must be beginning or end' );

			return get_option( 'wcp_location' );
		}

		return $input;
	}



	function serverToServerHtml(): void { ?>
        <button class="button button-secondary" type="button" id="reveal-secrets-button">Reveal</button>
		<?php
	}
    function accessTokenHtml():void {

        ?>
        <div  name="zoom_access_token"><?php echo esc_attr(get_option('zoom_access_token'))?></div>
        <?php
    }
    function tokenTypeHtml():void {

        ?>
        <div type="text" name="zoom_token_type"><?php echo esc_attr(get_option('zoom_token_type'))?></div>
        <?php
    }
    function tokenDetailsHtml():void{
            if(isset($_POST['btn=gtb'])){
                global $wpdb;
                $wpdb->insert(
                        table: $wpdb->prefix.'rc_test',data: [
                                'title' => 'This is a test'
                ]

                );
                echo 'hello';
            }
            ?>
        <input type="submit" class="button button-secondary" name="btn-gtb" value="Regenerate" type="button" id="regenerate-token-button"></button>

	    <?php
        }

	function checkboxHTML( $args ): void {
		?>
        <input type="checkbox" name="<?php echo $args['theName'] ?>"
               value="1" <?php checked( get_option( esc_attr( $args['theName'] ), '1' ) ) ?> >

		<?php
	}

	function headlineHTML(): void { ?>
        <input name="wcp_headline" value="<?php echo esc_attr( get_option( 'wcp_headline', 'Post Statistics' ) ) ?>"/>
		<?php
	}


	function locationHTML(): void { ?>
        <select name="wcp_location">
            <option value="0" <?php selected( get_option( 'wcp_location', '0' ) ) ?>> Beginning of Post</option>
            <option value="1" <?php selected( get_option( 'wcp_location', '1' ) ) ?>>End of Post</option>
        </select>
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

            <form action="" method="post">
                <?php
                settings_fields('bookzoomtoken');
                submit_button( __( 'Regenerate Token', 'rc-book-zoom' ) );
                ?>

            </form>

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
		add_options_page(
			'Zoom Settings',
			esc_html__( 'Book Zoom', 'rc_book_zoom' ),
			'manage_options',
			'rc-book-zoom-settings-page',
			[ $this, 'bookZoomHtml' ]

		);

	}

}

$rc_book_zoom = new RCBookZoom( new DataEncryption() );