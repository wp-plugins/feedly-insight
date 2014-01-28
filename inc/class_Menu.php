<?php

new FI_Feedly_Menu();

class FI_Feedly_Menu {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
	}

	function register_menu_page() {
		add_menu_page(
			FI_NAME, // page_title
			FI_NAME, // menu title
			'manage_options',
			FI_TEXT_DOMAIN,
			array( $this, 'menu_page' ),
			plugins_url( 'feedly-insight/images/icon.png' ) //, position not used
		);
		add_submenu_page(
			FI_TEXT_DOMAIN, // parent slug
			__('Settings'),
			__('Settings'),
			'manage_options',
			'my-custom-submenu-page',
			array( $this, 'sub_menu_settings')
		);
	}

	function menu_page() {
		do_action( 'fi_menu' );
	}
	function sub_menu_settings() {
		do_action( 'fi_settings' );
	}

}