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

	public function get_link( $url, $text, $title, $query = '', $id = false ) {
		$klasses = array( 'share-' . $this->get_class(), 'sd-button' );

		if ( $this->button_style == 'icon' || $this->button_style == 'icon-text' )
			$klasses[] = 'share-icon';

		if ( $this->button_style == 'icon' ) {
			$text      = '';
			$klasses[] = 'no-text';
		}

		$url = apply_filters( 'sharing_display_link', $url );
		if ( ! empty( $query ) ) {
			if ( stripos( $url, '?' ) === false )
				$url .= '?' . $query;
			else
				$url .= '&amp;' . $query;
		}

		if ( $this->button_style == 'text' )
			$klasses[] = 'no-icon';

		return sprintf(
			'<a rel="nofollow" class="%s" href="%s"%s title="%s"%s><span style="background-image:url(\'' . FI_IMG_URL . 'feedly-follow-square-flat-green_16x16.jpg\');">%s</span></a>',
			implode( ' ', $klasses ),
			$url,
			( $this->open_links == 'new' ) ? ' target="_blank"' : '',
			$title,
			( $id ? ' id="' . esc_attr( $id ) . '"' : '' ),
			$text
		);
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
				$share_count = ' <span class="share-count">' . fi_get_subscribers() . '</span>';
			return $this->get_link(
				'http://cloud.feedly.com/#subscription%2F' . rawurlencode( 'feed/' . FI::$option['feed_url'] ),
				_x( 'Feedly', 'follow us', 'feedly_insight' ) . $share_count,
				__( 'Click to follow on Feedly', 'feedly_insight' ) );
		endif;

	}

	public function display_preview() {
		$text = '&nbsp;';
		if ( ! $this->smart )
			if ( $this->button_style != 'icon' )
				$text = $this->get_name();

		$klasses = array( 'share-' . $this->get_class(), 'sd-button' );

		if ( $this->button_style == 'icon' || $this->button_style == 'icon-text' )
			$klasses[] = 'share-icon';

		if ( $this->button_style == 'icon' )
			$klasses[] = 'no-text';

		if ( $this->button_style == 'text' )
			$klasses[] = 'no-icon';

		$link = sprintf(
			'<a rel="nofollow" class="%s" href="javascript:void(0);return false;" title="%s"><span>%s</span></a>',
			implode( ' ', $klasses ),
			$this->get_name(),
			$text
		);
		?>
		<style type="text/css">
			.services ul li#feedly, #available-services .preview-feedly {
				background: url("<?php echo FI_IMG_URL; ?>feedly-follow-square-flat-green_16x16.jpg") no-repeat scroll 5px 6px #fff;
				padding-right: 10px;
			}

			li.share-feedly a.sd-button > span {
				background-image: url('<?php echo FI_IMG_URL; ?>feedly-follow-square-flat-green_16x16.jpg');
			}

			.preview-feedly .option-smart-on {
				background: url('<?php echo FI_IMG_URL; ?>smart-feedly.png') no-repeat scroll left top / 110px 20px #fff;;
				height: 20px;
				width: 110px;
			}

			@media print, not all, not all, (min-resolution: 120dpi) {
				.preview-feedly .option-smart-on {
					background-image: url('<?php echo FI_IMG_URL; ?>smart-feedly@2x.png');
				}
			}

		</style>
		<div class="option option-smart-<?php echo $this->smart ? 'on' : 'off'; ?>">
			<?php echo $link; ?>
		</div>
	<?php
	}

}

