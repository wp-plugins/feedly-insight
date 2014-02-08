<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

FI_Menu::init();

class FI_Menu {

	static $instance;

	public static function init() {
		if ( ! self::$instance )
			self::$instance = new FI_Menu;
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
	}

	function register_menu_page() {
		//create new sub-level menu
		add_submenu_page(
			'index.php',
			FI_NAME . ' ' . __( 'Settings', 'feedly_insight' ),
			FI_NAME . ' ' . __( 'Settings', 'feedly_insight' ),
			'manage_options',
			FI_TEXT_DOMAIN . '_settings',
			'fi_show_settings' );

		//call register settings function
		add_action( 'admin_init', array( $this, 'register_settings' ) );
	}

	function register_settings() {
		//register settings
		register_setting( 'fi-settings-group', FI_OPTION_NAME, 'fi_settings_validate' );
	}

}