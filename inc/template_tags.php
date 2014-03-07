<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * create screen-shot src by mshots
 *
 * @param     $url
 * @param int $width
 *
 * @return string
 */
function fi_get_create_mshots_src( $url, $width = 50 ) {
	$mshot_api = 'http://s0.wordpress.com/mshots/v1/';
	$src       = $mshot_api . urlencode( $url ) . '?' . http_build_query( array( 'w' => $width, 'r' => 3, ) );
	return $src;
}


/**
 * create screen-shot <img> tag by mshots
 *
 * @param        $url
 * @param int    $width
 * @param string $class
 * @param string $alt
 *
 * @return string
 */
function fi_get_create_mshots_img( $url, $width = 50, $class = '', $alt = '' ) {
	$img = sprintf( '<img class="%1$s" data-src="%2$s" alt="%3$s" width="%4$s" height="%5$s" />',
		$class, fi_get_create_mshots_src( $url, $width ), $alt, $width, floor( $width * 0.75 ) );
	return $img;
}


/**
 * @param $timestamp
 *
 * @return string
 */
function fi_convert_timestamp( $timestamp ) {
	if ( strstr( $timestamp, __( 'none' ) ) ) return $timestamp;
	$timestamp = date_i18n( __( 'M j, Y @ G:i', 'feedly_insight' ), $timestamp / 1000 + get_option( 'gmt_offset' ) * 60 * 60 );
	return $timestamp;
}


/**
 * copy from wp-admin/includes/ms.php
 *
 * @param string $code
 *
 * @return string
 */
function fi_format_code_lang( $code = '' ) {
	$code       = strtolower( substr( $code, 0, 2 ) );
	$lang_codes = array(
		'aa' => 'Afar', 'ab' => 'Abkhazian', 'af' => 'Afrikaans', 'ak' => 'Akan', 'sq' => 'Albanian', 'am' => 'Amharic', 'ar' => 'Arabic', 'an' => 'Aragonese', 'hy' => 'Armenian', 'as' => 'Assamese', 'av' => 'Avaric', 'ae' => 'Avestan', 'ay' => 'Aymara', 'az' => 'Azerbaijani', 'ba' => 'Bashkir', 'bm' => 'Bambara', 'eu' => 'Basque', 'be' => 'Belarusian', 'bn' => 'Bengali',
		'bh' => 'Bihari', 'bi' => 'Bislama', 'bs' => 'Bosnian', 'br' => 'Breton', 'bg' => 'Bulgarian', 'my' => 'Burmese', 'ca' => 'Catalan; Valencian', 'ch' => 'Chamorro', 'ce' => 'Chechen', 'zh' => 'Chinese', 'cu' => 'Church Slavic; Old Slavonic; Church Slavonic; Old Bulgarian; Old Church Slavonic', 'cv' => 'Chuvash', 'kw' => 'Cornish', 'co' => 'Corsican', 'cr' => 'Cree',
		'cs' => 'Czech', 'da' => 'Danish', 'dv' => 'Divehi; Dhivehi; Maldivian', 'nl' => 'Dutch; Flemish', 'dz' => 'Dzongkha', 'en' => 'English', 'eo' => 'Esperanto', 'et' => 'Estonian', 'ee' => 'Ewe', 'fo' => 'Faroese', 'fj' => 'Fijjian', 'fi' => 'Finnish', 'fr' => 'French', 'fy' => 'Western Frisian', 'ff' => 'Fulah', 'ka' => 'Georgian', 'de' => 'German', 'gd' => 'Gaelic; Scottish Gaelic',
		'ga' => 'Irish', 'gl' => 'Galician', 'gv' => 'Manx', 'el' => 'Greek, Modern', 'gn' => 'Guarani', 'gu' => 'Gujarati', 'ht' => 'Haitian; Haitian Creole', 'ha' => 'Hausa', 'he' => 'Hebrew', 'hz' => 'Herero', 'hi' => 'Hindi', 'ho' => 'Hiri Motu', 'hu' => 'Hungarian', 'ig' => 'Igbo', 'is' => 'Icelandic', 'io' => 'Ido', 'ii' => 'Sichuan Yi', 'iu' => 'Inuktitut', 'ie' => 'Interlingue',
		'ia' => 'Interlingua (International Auxiliary Language Association)', 'id' => 'Indonesian', 'ik' => 'Inupiaq', 'it' => 'Italian', 'jv' => 'Javanese', 'ja' => 'Japanese', 'kl' => 'Kalaallisut; Greenlandic', 'kn' => 'Kannada', 'ks' => 'Kashmiri', 'kr' => 'Kanuri', 'kk' => 'Kazakh', 'km' => 'Central Khmer', 'ki' => 'Kikuyu; Gikuyu', 'rw' => 'Kinyarwanda', 'ky' => 'Kirghiz; Kyrgyz',
		'kv' => 'Komi', 'kg' => 'Kongo', 'ko' => 'Korean', 'kj' => 'Kuanyama; Kwanyama', 'ku' => 'Kurdish', 'lo' => 'Lao', 'la' => 'Latin', 'lv' => 'Latvian', 'li' => 'Limburgan; Limburger; Limburgish', 'ln' => 'Lingala', 'lt' => 'Lithuanian', 'lb' => 'Luxembourgish; Letzeburgesch', 'lu' => 'Luba-Katanga', 'lg' => 'Ganda', 'mk' => 'Macedonian', 'mh' => 'Marshallese', 'ml' => 'Malayalam',
		'mi' => 'Maori', 'mr' => 'Marathi', 'ms' => 'Malay', 'mg' => 'Malagasy', 'mt' => 'Maltese', 'mo' => 'Moldavian', 'mn' => 'Mongolian', 'na' => 'Nauru', 'nv' => 'Navajo; Navaho', 'nr' => 'Ndebele, South; South Ndebele', 'nd' => 'Ndebele, North; North Ndebele', 'ng' => 'Ndonga', 'ne' => 'Nepali', 'nn' => 'Norwegian Nynorsk; Nynorsk, Norwegian', 'nb' => 'Bokmål, Norwegian, Norwegian Bokmål',
		'no' => 'Norwegian', 'ny' => 'Chichewa; Chewa; Nyanja', 'oc' => 'Occitan, Provençal', 'oj' => 'Ojibwa', 'or' => 'Oriya', 'om' => 'Oromo', 'os' => 'Ossetian; Ossetic', 'pa' => 'Panjabi; Punjabi', 'fa' => 'Persian', 'pi' => 'Pali', 'pl' => 'Polish', 'pt' => 'Portuguese', 'ps' => 'Pushto', 'qu' => 'Quechua', 'rm' => 'Romansh', 'ro' => 'Romanian', 'rn' => 'Rundi', 'ru' => 'Russian',
		'sg' => 'Sango', 'sa' => 'Sanskrit', 'sr' => 'Serbian', 'hr' => 'Croatian', 'si' => 'Sinhala; Sinhalese', 'sk' => 'Slovak', 'sl' => 'Slovenian', 'se' => 'Northern Sami', 'sm' => 'Samoan', 'sn' => 'Shona', 'sd' => 'Sindhi', 'so' => 'Somali', 'st' => 'Sotho, Southern', 'es' => 'Spanish; Castilian', 'sc' => 'Sardinian', 'ss' => 'Swati', 'su' => 'Sundanese', 'sw' => 'Swahili',
		'sv' => 'Swedish', 'ty' => 'Tahitian', 'ta' => 'Tamil', 'tt' => 'Tatar', 'te' => 'Telugu', 'tg' => 'Tajik', 'tl' => 'Tagalog', 'th' => 'Thai', 'bo' => 'Tibetan', 'ti' => 'Tigrinya', 'to' => 'Tonga (Tonga Islands)', 'tn' => 'Tswana', 'ts' => 'Tsonga', 'tk' => 'Turkmen', 'tr' => 'Turkish', 'tw' => 'Twi', 'ug' => 'Uighur; Uyghur', 'uk' => 'Ukrainian', 'ur' => 'Urdu', 'uz' => 'Uzbek',
		've' => 'Venda', 'vi' => 'Vietnamese', 'vo' => 'Volapük', 'cy' => 'Welsh', 'wa' => 'Walloon', 'wo' => 'Wolof', 'xh' => 'Xhosa', 'yi' => 'Yiddish', 'yo' => 'Yoruba', 'za' => 'Zhuang; Chuang', 'zu' => 'Zulu' );

	/**
	 * Filter the language codes.
	 *
	 * @since MU
	 *
	 * @param array  $lang_codes Key/value pair of language codes where key is the short version.
	 * @param string $code       A two-letter designation of the language.
	 */
	$lang_codes = apply_filters( 'lang_codes', $lang_codes, $code );
	return strtr( $code, $lang_codes );
}


function fi_get_subscribers() {
	// run below code when transient is old
	if ( false === ( $subscribers = get_transient( 'feedly_subscribers' ) ) ) :
		// encode RSS feed URL
		require_once( FI_DIR . '/admin/class_Feedly_Get.php' );
		$feed = new FI_Feedly_Get();
		$feed->set( FI::$option['feed_url'] );
		if ( $subscribers = $feed->feed() ):
			$subscribers = $subscribers['subscribers'];
			// set Transient subscribers every half a day.
			set_transient( 'feedly_subscribers', (int) $subscribers, 60 * 60 * 6 );
		endif;
	endif;
	return (int) $subscribers;
}


function fi_get_button( $size = 'horizontal', $value = null, $feed_url = null ) {
	if ( empty( $value ) )
		$value = number_format_i18n( fi_get_subscribers() );
	else
		esc_attr( $value );

	if ( empty( $feed_url ) )
		$feed_url = 'feed/' . FI::$option['feed_url'];
	$url = 'http://cloud.feedly.com/#subscription%2F' . rawurlencode( $feed_url );

	$title = esc_attr( apply_filters( 'fi_the_button_title', __( 'Syndicate this site using Feedly', 'feedly_insight' ) ) );
	$class = 'fi-';
	$img   = '<img class="%1$s" src="%2$s" alt="follow us in feedly" width="%3$d" height="%4$d">';

	if ( $size === 'vertical' ):
		$class .= $size;
		$img = sprintf( $img,
			'fi-img-feedly-follow',
			'http://s3.feedly.com/img/follows/feedly-follow-rectangle-flat-small_2x.png',
			66, 20 );
	elseif ( $size === 'small' ):
		$class .= 'horizontal';
		$img = sprintf( $img,
			'fi-img-feedly-follow fi-img-small fi-left',
			'http://s3.feedly.com/img/follows/feedly-follow-square-flat-green_2x.png',
			20, 20 );
	else:
		$class .= 'horizontal';
		$img = sprintf( $img,
			'fi-img-feedly-follow fi-left',
			'http://s3.feedly.com/img/follows/feedly-follow-rectangle-flat-small_2x.png',
			66, 20 );
	endif;
	$button = "<div class='fi-arrow'><span class='fi-count'>{$value}</span></div>{$img}";

	$button = "<div class='{$class}'><a class='fi-btn' href='{$url}' target='_blank' title='{$title}'>{$button}</a></div>\n";
	return apply_filters( 'fi_the_button', $button );
}


function fi_the_button( $size = 'horizontal', $value = null, $feed_url = null ) {
	echo( fi_get_button( $size, $value, $feed_url ) );
}

