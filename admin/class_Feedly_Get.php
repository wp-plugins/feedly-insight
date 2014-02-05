<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

require_once trailingslashit( dirname( __FILE__ ) ) . 'class_Feedly.php';

class FI_Feedly_Get extends FI_Feedly {

	private $id;

	const FEEDS  = 'feeds/';
	const SEARCH = 'search/feeds?';

	function set( $id ) {
		$this->id = esc_attr( $id );
	}

	/**
	 * Feedly feed API
	 *
	 * @return array
	 */
	function feed() {
		$url          = self::FEEDLY_API . self::FEEDS . rawurlencode( trailingslashit( __FUNCTION__ ) . $this->id );
		$results      = $this->curl_get_contents( $url );
		$default_args = array(
			'topics'      => array( __( 'none', 'feedly_insight' ) ),
			'subscribers' => 0,
			'title'       => __( 'none', 'feedly_insight' ),
			'website'     => '#',
			'id'          => __( 'none', 'feedly_insight' ),
			'velocity'    => __( 'none', 'feedly_insight' ),
			'language'    => __( 'Unknown', 'feedly_insight' ),
			'description' => '&mdash;', );
		//
		$results           = wp_parse_args( $results, $default_args );
		$results['topics'] = implode( ', ', $results['topics'] );
		$results           = array_map( 'esc_attr', $results );
		return apply_filters( 'fi_feed_result', $results );
	}

	/**
	 * Feedly search API
	 *
	 * @param int $count
	 *
	 * @return mixed
	 */
	function search( $count = 20 ) {
		$url          = self::FEEDLY_API . self::SEARCH;
		$query        = http_build_query( array( 'q' => $this->id, 'n' => $count ) );
		$results      = $this->curl_get_contents( $url . $query );
		$results      = $results['results'];
		$default_args = array(
			'deliciousTags'       => array( __( 'none', 'feedly_insight' ) ),
			'subscribers'         => __( 'none', 'feedly_insight' ),
			'title'               => __( 'none', 'feedly_insight' ),
			'estimatedEngagement' => __( 'none', 'feedly_insight' ),
			'website'             => '#',
			'lastUpdated'         => __( 'none', 'feedly_insight' ),
			'score'               => __( 'none', 'feedly_insight' ),
			'feedId'              => __( 'none', 'feedly_insight' ),
			'velocity'            => __( 'none', 'feedly_insight' ),
			'language'            => __( 'Unknown', 'feedly_insight' ),
			'description'         => '&mdash;', );
		// todo ここの回し方が気持ち悪い
		$count = 0;
		foreach ( $results as $r ) {
			$results[$count] = wp_parse_args( $r, $default_args );
			$count ++;
		}
		return apply_filters( 'fi_search_result', $results );
	}

	protected function curl_get_contents( $url, $timeout = 5, $decode = true ) {
		return parent::curl_get_contents( $url, $timeout, $decode );
	}

	protected function callback_curl_get_contents( $url, $timeout, $decode ) {
		return parent::callback_curl_get_contents( $url, $timeout, $decode );
	}

}

