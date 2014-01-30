<?php

new FI_Menu();

class FI_Menu {

	function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
	}

	function register_menu_page() {
		add_submenu_page(
			'index.php',
			'Feedly ' . __( 'Search' ),
			'Feedly ' . __( 'Search' ),
			'manage_options',
			FI_TEXT_DOMAIN,
			array( $this, 'search_page' )
		);
		/* original menu
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
			FI_TEXT_DOMAIN . '_settings',
			array( $this, 'sub_menu_settings')
		);
		*/
	}

	function search_page() {
		do_action( 'fi_search_page' );
	}

	function sub_menu_settings() {
		do_action( 'fi_settings_page' );
	}

}