<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}

FI_Menu::init();

class FI_Menu {

	static $instance;

	public static function init() {
		if ( !self::$instance )
			self::$instance = new FI_Menu;
		return self::$instance;
	}

	private function __construct() {
		add_action( 'admin_menu', array( $this, 'register_menu_page' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );

		// old WP
		if ( version_compare( $GLOBALS['wp_version'], '3.8', '<' ) && !function_exists( 'mp6_register_dashicons' ) ) {
			add_action( 'admin_enqueue_scripts', array( $this, 'deprecated_enqueue' ) );
		}
	}

	function register_menu_page() {
		// root page
		add_menu_page(
			FI_NAME,
			FI_NAME,
			'manage_options',
			FI_TEXT_DOMAIN,
			'fi_main',
			FI_IMG_URL . 'feedly-follow-square-flat-green_16x16.png',
			'2.11' );

		// import sub page
		add_submenu_page(
			FI_TEXT_DOMAIN,
			FI_NAME . ' ' . __( 'Import', 'feedly_insight' ),
			__( 'Import', 'feedly_insight' ),
			'manage_options',
			FI_TEXT_DOMAIN . '_import',
			'__return_true' );
		// export sub page
		add_submenu_page(
			FI_TEXT_DOMAIN,
			FI_NAME . ' ' . __( 'Export', 'feedly_insight' ),
			__( 'Export', 'feedly_insight' ),
			'manage_options',
			FI_TEXT_DOMAIN . '_export',
			'fi_show_export' );
		// settings sub page
		add_submenu_page(
			FI_TEXT_DOMAIN,
			FI_NAME . ' ' . __( 'Settings', 'feedly_insight' ),
			__( 'Settings', 'feedly_insight' ),
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

	function enqueue( $hook ) {
		if ( 'toplevel_page_feedly_insight' != $hook )
			return;
		if ( !empty( $_GET['search-feedly'] ) ) {
			wp_enqueue_script( 'jquery-lazyload', FI_URL . '/js/jquery.unveil.min.js', array( 'jquery' ), '1.9.1', true );
			return;
		}
		wp_enqueue_script( 'jquery-flot', FI_URL . '/js/jquery.flot.js', array( 'jquery' ), '0.8.3-alpha', true );
		wp_enqueue_script( 'jquery-flot-time', FI_URL . '/js/jquery.flot.time.js', array( 'jquery-flot' ), '0.8.3-alpha', true );
	}

	function deprecated_dashicons() {
		echo '<style type="text/css" media="screen">.fi_wrap .dashicons { font-family: "dashicons" !important; }</style>';
	}

	function deprecated_enqueue( $hook ) {
		if ( 'toplevel_page_feedly_insight' != $hook )
			return;
		wp_enqueue_style( 'dash-icons', '//melchoyce.github.io/dashicons/css/dashicons.css' );
		add_action( 'admin_head', array( $this, 'deprecated_dashicons' ) );
	}

}

