<?php

class FI_History {

	static $instance;
	private $cron_name = 'subscribers_save_event';

	public static function init() {
		if ( ! self::$instance )
			self::$instance = new FI_History;
		return self::$instance;
	}

	function __construct() {

		add_action( $this->cron_name, array( $this, 'cron_exec' ) );

		if ( ! wp_next_scheduled( $this->cron_name ) ) {
			wp_schedule_event( time(), 'daily', $this->cron_name );
		}
	}

	public function cron_exec() {
		$db = FI_DB::init();
		$db->insert_site_history();
	}

	public function deactivate() {
		$timestamp = wp_next_scheduled( $this->cron_name );
		wp_unschedule_event( $timestamp, $this->cron_name );
	}

}

