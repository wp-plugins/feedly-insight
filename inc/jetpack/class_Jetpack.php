<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * condition Jetpack share daddy is active or not.
 */
if ( class_exists( 'Jetpack' ) && Jetpack::is_module_active( 'sharedaddy' ) ) {
	FI_Jetpack::init();
} else {
	return;
}


/**
 * Class FI_Jetpack
 *
 * register feedly button to share daddy
 */
class FI_Jetpack {

	static $instance;

	public static function init() {
		if ( ! self::$instance )
			self::$instance = new FI_Jetpack;
		return self::$instance;
	}

	function __construct() {
		add_filter( 'sharing_services', array( $this, 'add_sharing_services' ) );
	}

	function add_sharing_services( $services ) {
		if ( ! array_key_exists( 'feedly', $services ) )
			$services['feedly'] = 'Share_Feedly';
		return $services;
	}

}


/**
 * Class Share_Feedly
 *
 * for share daddy
 */
class Share_Feedly extends Sharing_Source {

	var $shortname = 'feedly';

	public function __construct( $id, array $settings ) {
		parent::__construct( $id, $settings );

		if ( 'official' == $this->button_style )
			$this->smart = true;
		else
			$this->smart = false;
	}

	public function get_name() {
		return __( 'Feedly', 'jetpack' );
	}

	public function get_display( $post ) {
		if ( $this->smart ):
			return '<div class="feedly_button">' . fi_get_button() . '</div>';
		else:
			$share_count = '';
			if ( fi_get_subscribers() )
				$share_count = '<span class="share-count">' . fi_get_subscribers() . '</span>';
			return $this->get_link(
				'http://cloud.feedly.com/#subscription%2F' . rawurlencode( 'feed/' . FI::$option['feed_url'] ),
				_x( 'Feedly', 'follow us', 'feedly_insight' ) . $share_count,
				__( 'Click to follow on Feedly', 'feedly_insight' ) );
		endif;
	}

	public function display_footer() {
		$this->js_dialog( $this->shortname, array( 'height' => 600, 'width' => 968, ) );
	}

}

