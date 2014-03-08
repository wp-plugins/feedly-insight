<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Class FI_DB
 *
 * management database
 */
class FI_DB {

	static $instance;
	var $history_table;


	public static function init() {
		if ( ! self::$instance )
			self::$instance = new FI_DB;
		return self::$instance;
	}


	function __construct() {
		global $wpdb;
		$this->history_table = $wpdb->prefix . 'feedly_insight_history';
	}


	/**
	 * for plugin activated ( called from PLUGIN_DIR/feedly-insight.php
	 */
	function activate() {
		global $wpdb, $charset_collate;
		if ( $wpdb->get_var( "SHOW TABLES LIKE '$this->history_table'" ) != $this->history_table ) {
			require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

			$sql = "
				CREATE TABLE {$this->history_table}  (
					ID INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
					save_date DATE,
					subscribers INT(11),
					UNIQUE KEY ID (ID)
				) {$charset_collate};";

			dbDelta( $sql );
			// Turn Off dbDelta Describe Errors
			global $EZSQL_ERROR;
			$EZSQL_ERROR = array();

			update_option( 'feedly_insight_db_ver', FI_DB_VER );
		}
	}


	/**
	 * insert history to SQL
	 *
	 * @param $date
	 * @param $count
	 */
	function insert_history( $date, $count ) {
		global $wpdb;
		$wpdb->insert( $this->history_table,
			array( 'save_date' => $date, 'subscribers' => (int) $count, ),
			array( '%d', '%d', ) );
	}


	function insert_site_history() {
		require_once( FI_DIR . 'admin/class_Feedly_Get.php' );
		$feeds = new FI_Feedly_Get();
		$feeds->set( FI::$option['feed_url'] );
		$results = $feeds->feed();
		$this->insert_history( date( 'Ymd' ), $results['subscribers'] );
	}


	function update() {
		/*
		$installed_ver = get_option( 'feedly_insight_db_ver' );
		if( $installed_ver != FI_DB_VER ) {
		}
		*/
	}


	static function update_db_check() {
		if ( get_option( 'feedly_insight_db_ver' ) != FI_DB_VER )
			self::update();
	}


	/**
	 * get saved subscribers history
	 *
	 * @param int $num default=30, 0=all data
	 *
	 * @return mixed
	 */
	function get_subscribers_history( $num = 30 ) {
		global $wpdb;
		$table = $this->history_table;
		if ( ! isset( $wpdb->$table ) ) $wpdb->$table = $table;
		// duplicate check
		$c_query = '*';
		if ( empty( FI::$option['duplicate'] ) ) $c_query = 'DISTINCT subscribers';

		$count = (int) $wpdb->get_var( "SELECT count({$c_query}) FROM {$wpdb->$table}" );
		if ( $num === 0 ) {
			$num = $count;
		} elseif ( $num < $count ) {
			$offset = $count - $num;
			$num    = "{$offset}, {$num}";
		}

		$duplicate_query = '';
		if ( empty( FI::$option['duplicate'] ) ) $duplicate_query = 'GROUP BY subscribers';

		$result_query = $wpdb->get_results( "
			SELECT subscribers, save_date
			FROM {$wpdb->$table}
			{$duplicate_query}
			ORDER BY save_date LIMIT {$num}
			", ARRAY_A );
		return $result_query;
	}


	/**
	 * return subscribers history array for graph drawing
	 *
	 * @param int $num
	 *
	 * @return array [i] =>  [timestamp,subscribers]
	 */
	function get_subscribers_history_for_graph( $num ) {
		$history      = array();
		$result_query = $this->get_subscribers_history( $num );
		foreach ( $result_query as $h ) {
			$history[] .= '[' . strtotime( $h['save_date'] ) * 1000 . ',' . $h['subscribers'] . ']';
		}
		return $history;
	}


	function export_history() {
		global $wpdb;
		$table = $this->history_table;
		if ( ! isset( $wpdb->$table ) ) $wpdb->$table = $table;
		$result_query = $wpdb->get_results( "
			SELECT subscribers, save_date
			FROM {$wpdb->$table}
			ORDER BY save_date
			", ARRAY_A );
		return $result_query;
	}


	/**
	 * delete table when plugin uninstalled
	 */
	function plugin_uninstall() {
		global $wpdb;
		$wpdb->query( "DROP TABLE IF EXISTS $this->history_table" );
	}


}

