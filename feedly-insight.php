<?php

/*
Plugin Name: Feedly Insight
Plugin URI: http://hayashikejinan.com/feedly-insight/
Description: Add Feedly subscribers history & it's widget. Shows your site info, search website & feeds. Also supported Jetpack share.
Version: 0.9.17 beta
Author: hayashikejinan (ﾎﾎ冢次男)
Author URI: http://hayashikejinan.com/
Text Domain: feedly_insight
Domain Path: /languages/
License: GPLv2 or later
*/
define( 'FI_VER', '0.9.17 beta' );

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

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

define( 'FI_NAME', 'Feedly Insight' );
define( 'FI_DIR', plugin_dir_path( __FILE__ ) );
define( 'FI_URL', plugin_dir_url( __FILE__ ) );
define( 'FI_FILE', __FILE__ );
define( 'FI_IMG_URL', FI_URL . 'images/' );
define( 'FI_BTN_URL', FI_IMG_URL . 'buttons/' );
define( 'FI_TEXT_DOMAIN', 'feedly_insight' );
define( 'FI_DASHBOARD_WIDGET_SLUG', FI_TEXT_DOMAIN );
define( 'FI_OPTION_NAME', FI_TEXT_DOMAIN . '_settings' );
define( 'FI_DB_VER', 1.0 );

new FI();

class FI {

	static $plugin_data;
	static $option;

	/**
	 * constructor
	 */
	function __construct() {
		$this->auto_load_admin();
		self::$option = get_option( FI_OPTION_NAME );
		load_plugin_textdomain( FI_TEXT_DOMAIN, false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );
		add_action( 'admin_init', array( $this, '_set_plugin_data' ) );
		add_action( 'plugins_loaded', array( $this, 'jetpack' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'admin_css' ) );
		if ( self::$option['css_enqueue'] )
			add_action( 'wp_enqueue_scripts', array( $this, 'fi_button_css' ) );

		register_activation_hook( __FILE__, array( $this, '_activate' ) );
		register_deactivation_hook( __FILE__, array( $this, '_deactivate' ) );
		//add_action( 'plugins_loaded', array('FI_DB', 'update_db_check') );

		FI_History::init();
		if ( !empty( FI::$option['dashboard'] ) && is_admin() ) FI_Dashboard_Widget::init();
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

	function jetpack() {
		if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'sharedaddy' ) ) {
			require_once dirname( __FILE__ ) . '/inc/jetpack/class_Jetpack.php';
		}
	}

	/**
	 * set plugin data array to static $plugin_data
	 */
	function _set_plugin_data() {
		self::$plugin_data = get_plugin_data( __FILE__ );
	}

	function admin_css( $hook ) {
		if ( $hook != 'index.php' && !strstr( $hook, FI_TEXT_DOMAIN ) && 'settings_page_sharing' != $hook )
			return;
		if ( defined( 'WP_SHARING_PLUGIN_VERSION' ) && version_compare( WP_SHARING_PLUGIN_VERSION, '3.0-alpha', '>=' ) )
			wp_register_style( 'fi_admin', FI_URL . 'css/fi-admin.css', false, FI_VER );
		else
			wp_register_style( 'fi_admin', FI_URL . 'css/fi-admin-deprecated.css', false, FI_VER );
		wp_enqueue_style( 'fi_admin' );
	}

	function fi_button_css() {
		if ( defined( 'WP_SHARING_PLUGIN_VERSION' ) && version_compare( WP_SHARING_PLUGIN_VERSION, '3.0-alpha', '>=' ) )
			wp_register_style( 'fi_buttons', FI_URL . 'css/fi-buttons.css', false, FI_VER );
		else
			wp_register_style( 'fi_buttons', FI_URL . 'css/fi-buttons-deprecated.css', false, FI_VER );
		wp_enqueue_style( 'fi_buttons' );
	}

	function _activate() {
		$db = FI_DB::init();
		$db->activate();
		if ( !get_option( FI_OPTION_NAME ) ):
			add_option( FI_OPTION_NAME, array(
				'css_enqueue' => 1,
				'feed_url'    => get_bloginfo( 'rss2_url' ),
			) );
		endif;
	}

	function _deactivate() {
		$fi = FI_History::init();
		$fi->deactivate();
	}

}

