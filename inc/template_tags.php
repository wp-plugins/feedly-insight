<?php


function fi_get_create_mshots_src( $url, $width = 50 ) {
	$mshot_api = 'http://s0.wordpress.com/mshots/v1/';
	$src       = $mshot_api . urlencode( $url ) . '?' . http_build_query( array( 'w' => $width, 'r' => 3, ) );
	return $src;
}


function fi_get_create_mshots_img( $url, $width = 50, $class = '', $alt = '' ) {
	$img = sprintf( '<img class="%1$s" src="%2$s" alt="%3$s" width="%4$s" height="%5$s" />',
		$class, fi_get_create_mshots_src( $url, $width ), $alt, $width, floor( $width * 0.75 ) );
	return $img;
}


function fi_convert_timestamp( $timestamp ) {
	if ( strstr( $timestamp, __( 'none' ) ) ) return $timestamp;
	$timestamp = date_i18n( __( 'M j, Y @ G:i', 'feedly_insight' ), $timestamp / 1000 + get_option( 'gmt_offset' ) * 60 * 60 );
	return $timestamp;
}


