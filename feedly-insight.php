<?php

/*
Plugin Name: Feedly Insight
Plugin URI: http://wordpress.org/plugins/feedly-insight/
Description: Add Feedly dashboard widget. Shows your site info, search website & feeds.
Version: 0.2.2
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

new FI();

class FI {

	static $plugin_data;

	/**
	 * constructor
	 */
	function __construct() {
		if ( is_admin() )
			$this->auto_load_admin();
		load_plugin_textdomain( FI_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_action( 'admin_init', array( $this, '_set_plugin_data' ) );
	}

	/**
	 * auto load all php files in /inc
	 */
	function auto_load_admin() {
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

}

