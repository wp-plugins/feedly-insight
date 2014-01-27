<?php

new FI_Dashboard_Widget();

class FI_Dashboard_Widget {

	function __construct() {
		add_action( 'wp_dashboard_setup', array( $this, 'add_dashboard_widgets' ) );

		if ( version_compare( $GLOBALS['wp_version'], '3.8', '<' ) && ! function_exists( 'mp6_register_dashicons' ) ):
			add_action( 'admin_enqueue_scripts', array( $this, 'enqueue' ) );
		endif;
	}

	/**
	 * Add a widget to the dashboard.
	 *
	 * This function is hooked into the 'wp_dashboard_setup' action below.
	 */
	function add_dashboard_widgets() {
		wp_add_dashboard_widget(
			FI_TEXT_DOMAIN . '_dashboard_widget', // Widget slug.
			sprintf( __( 'Feedly information of %s', 'feedly_insight' ), esc_attr( get_bloginfo( 'name' ) ) ), // Title.
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

	function enqueue() {
		wp_enqueue_style( 'dash-icons', '//melchoyce.github.io/dashicons/css/dashicons.css' );
	}

}