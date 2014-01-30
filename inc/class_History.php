<?php

class FI_History {

	private $cron_name = 'subscribers_save_event';

	function __construct() {

		add_action( $this->cron_name, array( $this, 'cron_exec' ) );

		if ( ! wp_next_scheduled( $this->cron_name ) ) {
			wp_schedule_event( time(), 'daily', $this->cron_name );
		}
	}

	public function cron_exec() {
		$db = new FI_DB();
		$db->insert_site_history();
	}

	public function deactivate() {
		$timestamp = wp_next_scheduled( $this->cron_name );
		wp_unschedule_event( $timestamp, $this->cron_name );
	}

}

