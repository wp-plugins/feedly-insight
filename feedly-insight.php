<?php

/*
Plugin Name: Feedly Insight
Plugin URI: http://wordpress.org/plugins/feedly-insight/
Description: Add Feedly dashboard widget. Shows your site info, search website & feeds.
Version: 0.3.5
Author: hayashikejinan
Author URI: http://hayashikejinan.com/
Text Domain: feedly_insight
Domain Path: /languages/
License: GPLv2 or later
*/

/*
Copyright (C) 2014 hayashikejinan <hayashikejinan@gmail.com>

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

define( 'FI_NAME', 'Feedly Insight' );
define( 'FI_DIR', plugin_dir_path( __FILE__ ) );
define( 'FI_URL', plugin_dir_url( __FILE__ ) );
define( 'FI_FILE', __FILE__ );
define( 'FI_IMG_URL', FI_URL . 'images/' );
define( 'FI_BTN_URL', FI_IMG_URL . 'buttons/' );
define( 'FI_TEXT_DOMAIN', 'feedly_insight' );
define( 'FI_DASHBOARD_WIDGET_SLUG', FI_TEXT_DOMAIN );
define( 'FI_DB_VER', 1.0 );

new FI();

class FI {

	static $plugin_data;

	/**
	 * constructor
	 */
	function __construct() {
		$this->auto_load_admin();
		load_plugin_textdomain( FI_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_action( 'admin_init', array( $this, '_set_plugin_data' ) );
		add_action( 'admin_head-index.php', array( $this, 'admin_css' ) );

		register_activation_hook( __FILE__, array( $this, '_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, '_deactivate' ) );
		//add_action( 'plugins_loaded', array('FI_DB', 'update_db_check') );
	}

	/**
	 * auto load all php files in /inc
	 */
	function auto_load_admin() {
		if ( is_admin() ):
			foreach ( glob( dirname( __FILE__ ) . '/admin/*.php' ) as $path ) {
				require_once $path;
			}
		endif;
		foreach ( glob( dirname( __FILE__ ) . '/inc/*.php' ) as $path ) {
			require_once $path;
		}
	}

	/**
	 * set plugin data array to static $plugin_data
	 */
	function _set_plugin_data() {
		self::$plugin_data = get_plugin_data( __FILE__ );
	}

	function admin_css() {
		echo '<link rel="stylesheet" href="' . FI_URL . 'css/fi-admin.css" />';
	}

	function _activate() {
		$db = FI_DB::init();
		$db->activate();

		new FI_History();
	}

	function _deactivate() {
		$fi = FI_History::init();
		$fi->deactivate();
	}

}

