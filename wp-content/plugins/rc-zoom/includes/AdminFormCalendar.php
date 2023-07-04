<?php

class AdminFormCalendar {
	public function __construct() {
		add_action( 'admin_menu', [ $this, 'adminPage' ]);
	}
	public function adminPage():void {
		add_submenu_page(
			parent_slug: 'rc-book-zoom-settings-page',
			page_title: esc_html__( 'Calendar', 'rc_book_zoom' ),
			menu_title: esc_html__( 'Calendar', 'rc_book_zoom' ),
			capability: 'manage_options',
			menu_slug: 'rc-book-zoom-calendar',
			callback: [ $this, 'zoomCalendarHtml' ],

		);

	}
	public function zoomCalendarHtml():void {?>
		<div class="wrap">
			<h1>Calendar</h1>
		</div>
		<?php
	}
}

