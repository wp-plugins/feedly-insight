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
		if ( ! array_key_exists( 'hatena', $services ) )
			$services['hatena'] = 'Share_Hatena';
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
		return __( 'Feedly', 'feedly_insight' );
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


class Share_Hatena extends Sharing_Source {

	var $shortname = 'hatena';

	public function __construct( $id, array $settings ) {
		parent::__construct( $id, $settings );

		if ( 'official' == $this->button_style )
			$this->smart = true;
		else
			$this->smart = false;
	}

	public function get_share_url( $post_id ) {
		return apply_filters( 'sharing_permalink', get_permalink( $post_id ), $post_id, $this->id );
	}

	public function get_share_title( $post_id ) {
		$post  = get_post( $post_id );
		$title = apply_filters( 'sharing_title', $post->post_title, $post_id, $this->id );

		return html_entity_decode( wp_kses( $title, null ) );
	}

	public function get_name() {
		return __( 'Bookmark', 'feedly_insight' );
	}

	public function get_display( $post ) {

		$share_url  = esc_attr( $this->get_share_url( $post->ID ) );
		$post_title = $this->get_share_title( $post->ID );

		if ( $this->smart ):
			$lang = 'en';
			$lang = apply_filters( 'sharing_hatena_lang', $lang );

			$button = sprintf( '<a href="http://b.hatena.ne.jp/entry/" class="hatena-bookmark-button" data-hatena-bookmark-layout="standard-balloon" data-hatena-bookmark-lang="%s" data-hatena-bookmark-url="%s"  data-hatena-bookmark-title="%s" title="%s">%s</a>',
				$lang, $share_url, $post_title, __( 'Add this entry to Hatena Bookmark', 'feedly_insight' ),
				__( 'Add this entry to Hatena Bookmark', 'feedly_insight' ),
				'<img src="http://b.st-hatena.com/images/entry-button/button-only@2x.png" alt="' . __( 'Add this entry to Hatena Bookmark', 'feedly_insight' ) . '" width="20" height="20" style="border: none;" />'
			);
			return '<div class="hatena_button">' . $button . '</div>';
		else:
			return $this->get_link(
				'http://b.hatena.ne.jp/add?mode=confirm&url=' . $share_url . '&title=' . $post_title,
				_x( 'Bookmark', 'share to', 'feedly_insight' ),
				__( 'Click to share on Hatena Bookmark', 'feedly_insight' ),
				'share=hatena', 'sharing-hatena-' . $post->ID );
		endif;
	}

	public function display_footer() {
		if ( $this->smart ) {
			echo '<script type="text/javascript" src="//api.b.st-hatena.com/js/bookmark_button_wo_al.js" charset="utf-8" async="async"></script>';
		} else {
			$this->js_dialog( $this->shortname, array( 'height' => 400, 'width' => 515, ) );
		}

		if ( $this->button_style != 'icon' )
			$this->hatena_enqueue();
	}

	public function hatena_enqueue() {
		wp_enqueue_script( 'hatena-sharing-js', FI_URL . 'js/hatena-sharing.min.js', array( 'sharing-js' ), FI::$plugin_data['Version'] );
	}

}

