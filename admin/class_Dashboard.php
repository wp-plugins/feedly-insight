<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

FI_Dashboard_Widget::init();

class FI_Dashboard_Widget {

	static $instance;

	public static function init() {
		if ( ! self::$instance )
			self::$instance = new FI_Dashboard_Widget;
		return self::$instance;
	}

	private function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
	}

	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action below.
	 */
	function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			FI_DASHBOARD_WIDGET_SLUG, // Widget slug.
			FI_NAME, // Title.
			array( $this, 'dashboard_widget_function' ) // Display function.
		);
	}

	/**
	 * Create the function to output the contents of our Dashboard Widget.
	 */
	function dashboard_widget_function() {
		// Display whatever it is you want to show.
		do_action( 'fi_dashboard' );
	}

	function enqueue( $hook ) {
		if ( 'index.php' != $hook )
			return;
		wp_enqueue_script( 'jquery-flot', FI_URL . '/js/jquery.flot.js', array( 'jquery' ), '0.8.3-alpha', true );
		wp_enqueue_script( 'jquery-flot-time', FI_URL . '/js/jquery.flot.time.js', array( 'jquery-flot' ), '0.8.3-alpha', true );
		if ( version_compare( $GLOBALS['wp_version'], '3.8', '<' ) && ! function_exists( 'mp6_register_dashicons' ) )
			wp_enqueue_style( 'dash-icons', '//melchoyce.github.io/dashicons/css/dashicons.css' );
	}

}