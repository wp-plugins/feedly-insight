<?php

/*
Plugin Name: Feedly Insight
Plugin URI: http://
Description: Add Feedly dashboard widget. Shows your site info, search website & feeds.
Version: 0.1.3
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

	function __construct() {
		if ( is_admin() )
			$this->auto_load_admin();
		load_plugin_textdomain( FI_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
	}

	function auto_load_admin() {
		foreach ( glob( dirname( __FILE__ ) . '/inc/*.php' ) as $path ) {
			require_once $path;
		}
	}

}

