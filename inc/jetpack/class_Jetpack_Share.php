<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
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


/**
 * Class Share_Hatena
 */
class Share_Hatena extends Sharing_Source {

	var $shortname = 'hatena';

	public function __construct( $id, array $settings ) {
		parent::__construct( $id, $settings );

		if ( 'official' == $this->button_style )
			$this->smart = true;
		else
			$this->smart = false;
	}

	public function get_name() {
		return __( 'Bookmark', 'feedly_insight' );
	}

	public function get_display( $post ) {

		$share_url  = esc_url( $this->get_share_url( $post->ID ) );
		$post_title = esc_attr( $this->get_share_title( $post->ID ) );
		// replace white space
		$post_title = str_replace( ' ', '%20', $post_title );

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
				'http://b.hatena.ne.jp/entry/panel/?url=' . $share_url . '&amp;btitle=' . $post_title,
				_x( 'Bookmark', 'share to', 'feedly_insight' ),
				__( 'Click to share on Hatena Bookmark', 'feedly_insight' ),
				'share=hatena', 'sharing-hatena-' . $post->ID );
		endif;
	}

	public function display_footer() {
		if ( $this->smart ) {
			echo '<script type="text/javascript" src="//api.b.st-hatena.com/js/bookmark_button.js" charset="utf-8" async="async"></script>';
		} else {
			$this->js_dialog( $this->shortname, array( 'height' => 220, 'width' => 365, ) );
		}

		if ( $this->button_style != 'icon' )
			$this->hatena_enqueue();
	}

	public function hatena_enqueue() {
		wp_enqueue_script( 'hatena-sharing-js', FI_URL . 'js/hatena-sharing.min.js', array( 'sharing-js' ), FI_VER );
	}

}


/**
 * Class Share_Feedly
 *
 * for share daddy
 */
class Share_RSS extends Sharing_Source {

	var $shortname = 'rss';

	public function __construct( $id, array $settings ) {
		parent::__construct( $id, $settings );

		if ( 'official' == $this->button_style )
			$this->smart = true;
		else
			$this->smart = false;
	}

	public function get_name() {
		return __( 'RSS', 'feedly_insight' );
	}

	public function get_link( $url, $text, $title, $query = '', $id = false ) {
		$klasses = array( 'share-'.$this->get_class(), 'sd-button' );

		if ( $this->button_style == 'icon' || $this->button_style == 'icon-text' )
			$klasses[] = 'share-icon';

		if ( $this->button_style == 'icon' ) {
			$text = '';
			$klasses[] = 'no-text';
		}

		if ( $this->button_style == 'text' )
			$klasses[] = 'no-icon';

		return sprintf(
			'<a rel="nofollow" class="%s" href="%s"%s title="%s"%s><span>%s</span></a>',
			implode( ' ', $klasses ),
			FI::$option['feed_url'],
			( $this->open_links == 'new' ) ? ' target="_blank"' : '',
			$title,
			( $id ? ' id="' . esc_attr( $id ) . '"' : '' ),
			$text
		);
	}

	public function get_display( $post ) {
		return $this->get_link(
			'',
			_x( 'RSS', 'follow us', 'feedly_insight' ),
			__( 'Click to follow on RSS', 'feedly_insight' ) );
	}

}

