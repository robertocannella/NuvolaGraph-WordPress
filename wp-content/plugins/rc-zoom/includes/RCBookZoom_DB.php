<?php

class RCBookZoom_DB {
	private mixed $wpdb;
	private string $prefix;
	private string $charset;

	public function __construct() {
		global $wpdb;
		$this->wpdb = $wpdb;
		$this->prefix = 'rc_zoom_';
		$this->charset = $wpdb->get_charset_collate();
	}

	public function create_tables():void {
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		// Table names
		$usersTable = $this->wpdb->prefix . 'users';
		$defaultTimeSlotsTable = $this->wpdb->prefix . 'rc_zoom_default_time_slots';
		$unavailableSlotsTable = $this->wpdb->prefix . 'rc_zoom_unavailable_slots';

		// Check if tables exist
		$usersTableExists = $this->wpdb->get_var("SHOW TABLES LIKE '$usersTable'") === $usersTable;
		error_log("Users Table Exists: ".$usersTableExists);
		$defaultTimeSlotsTableExists = $this->wpdb->get_var("SHOW TABLES LIKE '$defaultTimeSlotsTable'") === $defaultTimeSlotsTable;
		$unavailableSlotsTableExists = $this->wpdb->get_var("SHOW TABLES LIKE '$unavailableSlotsTable'") === $unavailableSlotsTable;

		// Create tables if they don't exist
		if (!$defaultTimeSlotsTableExists) {
			dbDelta("CREATE TABLE $defaultTimeSlotsTable (
                DefaultSlotID bigint(20) NOT NULL AUTO_INCREMENT,
                UserID bigint(20) NOT NULL,
                DayOfWeek VARCHAR(20) NOT NULL,
                StartTime TIME NOT NULL,
                EndTime TIME NOT NULL,
                PRIMARY KEY (DefaultSlotID)
            ) $this->charset;");
		}

		if (!$unavailableSlotsTableExists) {
			dbDelta("CREATE TABLE $unavailableSlotsTable (
                SlotID INT(11) NOT NULL AUTO_INCREMENT,
                UserID INT(11) NOT NULL,
                StartDate DATE NOT NULL,
                EndDate DATE NOT NULL,
                DayOfWeek VARCHAR(20) NOT NULL,
                StartTime TIME NOT NULL,
                EndTime TIME NOT NULL,
                PRIMARY KEY (SlotID)
            ) $this->charset;");
		}
	}
}
