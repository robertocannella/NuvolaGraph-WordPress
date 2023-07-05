<?php
include_once 'Zoom/Zoom.php';


class AdminFormListMeetings {
	public Zoom $zoom;

	public function __construct() {
		add_action( 'admin_menu', [ $this, 'adminPage' ]);

	}
	public function adminPage():void {
			add_submenu_page(
				parent_slug: 'rc-book-zoom-settings-page',
				page_title: esc_html__( 'Zoom Meetings', 'rc_book_zoom' ),
				menu_title: esc_html__( 'Zoom Meetings', 'rc_book_zoom' ),
				capability: 'manage_options',
				menu_slug: 'rc-book-zoom-list-meetings',
				callback: [ $this, 'zoomListMeetingsHtml' ],

			);
	}

	/**
	 * @throws Exception
	 */
	public function zoomListMeetingsHtml():void{
		$this->zoom = new Zoom();
		$meetings = $this->zoom->getMeetings();

		?>

		<div class="wrap">
            <h1>Zoom Meetings</h1>
            <div>
                <h2>Summary</h2>
                Total meetings: <?php echo esc_html__($meetings->total_records,'rc-book-zoom') ?>
            </div>


            <table class="wp-list-table widefat striped">
                <thead>
                <tr>
                    <th class="manage-column">Start Time</th>
                    <th class="manage-column">Duration</th>
                    <th class="manage-column">Join URL </th>
                    <!-- Additional table headers -->
                </tr>
                </thead>
                <tbody>
                <tr>
                    <?php

                    foreach ($meetings->meetings as $meeting):
	                    $dateString = $meeting->start_time;
	                    $date = new DateTime($dateString);
	                    $formattedDate = $date->format('Y-m-d H:i:s');

                        ?>


                        <td class="column-1"><?php echo esc_html($formattedDate); ?> </td>
                        <td class="column-2"><?php echo esc_html($meeting->duration );?></td>
                        <td class="column-3"><a target="_blank" href="<?php echo esc_html($meeting->join_url)?>"><?php echo esc_html__($meeting->join_url, 'rc-book-zoom'); ?> </a></td>

                   <?php
                    endforeach;

                    ?>

                    <!-- Additional table data rows -->
                </tr>
                </tbody>
            </table>
		</div>
	<?php

	}

}