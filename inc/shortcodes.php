<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * [fi_button]
 * [fi_button size="vertical"]
 * [fi_button size="small"]
 *
 * @param $atts
 *
 * @return mixed|void
 */
function fi_shortcode_button( $atts ) {
	extract( shortcode_atts( array(
		'size' => 'horizontal',
	), $atts ) );

	return fi_get_button( $size );
}

add_shortcode( 'fi_button', 'fi_shortcode_button' );

