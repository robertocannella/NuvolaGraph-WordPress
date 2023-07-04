<?php

include_once 'Zoom/Zoom.php';

class AdminFormTokenInfo {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'adminPage' ],11 );
		add_action( 'admin_init', [ $this, 'settings' ] );


	}

	function adminPage(): void {
		add_submenu_page(
			parent_slug: 'rc-book-zoom-settings-page',
			page_title: esc_html__( 'Token Info', 'rc_book_zoom' ),
			menu_title: esc_html__( 'Token Info', 'rc_book_zoom' ),
			capability: 'manage_options',
			menu_slug: 'rc-book-zoom-token',
			callback: [ $this, 'zoomTokenInfoHtml' ],

		);

	}

	function zoomTokenInfoHtml(): void { ?>
            <div class="wrap">
                <h1>Zoom Token Info</h1>
                <form action="" method="post">
		            <?php
                    // If User clicked Refresh Token, process that request.
		            if (isset($_POST['btn-refresh-token'])){
			            $zoom = new Zoom();
			            $zoom->refreshToken();
		            }
		            settings_fields( 'bookzoomtoken' );
		            do_settings_sections( 'rc-book-zoom-token' );

		            //submit_button( __( 'Save Settings', 'rc-book-zoom' ) );

		            ?>
                    <input name="btn-refresh-token" class="button button-primary" type="submit" value="<?php echo __( 'Refresh Token', 'rc-book-zoom' ) ?>">
                </form>
            </div>

	<?php }

	function settings(): void {

		/******************************* SECTION TWO *********/
		// Create Section
		add_settings_section(
			id: 'rbz_second_section',  // slug-name ot identify the section
			title: 'Generated Token Details', // Formatted Title of the section, shown as the heading for the section
            callback: null,
			page: 'rc-book-zoom-token' // the slug-name of the settings page

		);

		// Register Access token
		add_settings_field(
			id: 'zoom_access_token',
			title: 'Access Token',
			callback: [ $this, 'accessTokenHtml' ],
			page: 'rc-book-zoom-token',
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
			callback: [ $this, 'tokenTypeHtml' ],
			page: 'rc-book-zoom-token',
			section: 'rbz_second_section'
		);
		register_setting(
			option_group: 'bookzoomptoken',
			option_name: 'zoom_token_type'
		);


	}

	function tokenDetailsHtml(): void {

		?>
        <!--        The WordPress docs recommend keeping entire contents inside a div with a class of 'wrap'-->
        <div class="wrap">
            <h1>Book Zoom Settings</h1>

            <form action="options.php" method="post">
				<?php
				settings_fields( 'bookzoomtoken' );
				do_settings_sections( 'rc-book-zoom-token' );
				submit_button( __( 'Regenerate Token', 'rc-book-zoom' ) );
				?>
            </form>


        </div>
		<?php
	}

	function accessTokenHtml(): void {

		?>
        <div class="zoom-access-token" name="zoom_access_token"><?php echo esc_attr( get_option( 'zoom_access_token' ) ) ?></div>
		<?php
	}

	function tokenTypeHtml(): void {

		?>
        <div class="zoom-token-type" type="text" name="zoom_token_type"><?php echo esc_attr( get_option( 'zoom_token_type' ) ) ?></div>
		<?php
	}
}