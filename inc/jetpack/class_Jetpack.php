<?php

if ( !defined( 'ABSPATH' ) ) {
	exit;
}


/**
 * condition Jetpack share daddy is active or not.
 */

add_action( 'jetpack_modules_loaded', array( 'FI_Jetpack', 'init' ), 11 );


/**
 * Class FI_Jetpack
 *
 * register feedly button to share daddy
 */
class FI_Jetpack {

	static $instance;
	static $sharer;
	static $global;

	public static function init() {
		if ( !Jetpack::is_module_active( 'sharedaddy' ) ) {
			return null; // end
		}

		if ( !self::$instance )
			self::$instance = new FI_Jetpack;
		return self::$instance;
	}

	function __construct() {
		$option       = get_option( 'sharing-options' );
		self::$global = $option['global'];

		add_filter( 'sharing_services', array( $this, 'add_sharing_services' ) );

		add_action( 'sharing_global_options', array( $this, 'add_twitter_via_option' ) );
		add_filter( 'sharing_default_global', array( $this, 'add_sharing_default_global' ) );
		add_filter( 'jetpack_sharing_twitter_via', array( $this, 'twitter_via' ) );
	}

	function add_sharing_services( $services ) {
		require_once dirname( __FILE__ ) . '/class_Jetpack_Share.php';
		if ( !array_key_exists( 'feedly', $services ) )
			$services['feedly'] = 'Share_Feedly';
		if ( !array_key_exists( 'hatena', $services ) )
			$services['hatena'] = 'Share_Hatena';
		if ( !array_key_exists( 'rss', $services ) )
			$services['rss'] = 'Share_RSS';
		return $services;
	}

	function twitter_via( $via ) {
		return isset( self::$global['twitter_via'] ) ? esc_attr( self::$global['twitter_via'] ) : $via;
	}

	function add_twitter_via_option() {
		$global = self::$global;
		?>
		<tr valign="top">
			<th scope="row"><label>Twitter via</label></th>
			<td>
				&#64;<input type="text" value="<?php echo isset( $global['twitter_via'] ) ? esc_html( $global['twitter_via'] ) : ''; ?>" name="twitter_via">
			</td>
		</tr>
	<?php
	}

	function add_sharing_default_global( $global ) {
		$global['twitter_via'] = '';
		if ( !empty( $_POST['twitter_via'] ) ) $global['twitter_via'] = esc_html( $_POST['twitter_via'] );
		return $global;
	}

}

