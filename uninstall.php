<?php

//if uninstall not called from WordPress exit
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) )
	exit();

require_once( dirname( __FILE__ ) . '/inc/class_DB.php' );
$db = FI_DB::init();
$db->plugin_uninstall();
