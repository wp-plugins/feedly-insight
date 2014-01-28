<?php

class FI_Feedly {

	protected $request;
	const FEEDLY_API = 'http://cloud.feedly.com/v3/';

	function get_fi_request() {
		return $this->request;
	}

	/**
	 * cURL で外部リソースを取得
	 *
	 * @param      $url
	 * @param int  $timeout
	 * @param bool $decode
	 *
	 * @return mixed
	 */
	protected function curl_get_contents( $url, $timeout = 5, $decode = true ) {

		$this->request = $url;

		if ( ! function_exists( 'curl_version' ) )
			return $this->callback_curl_get_contents( $url, $timeout, $decode );

		// 取得
		$ch = curl_init();
		curl_setopt( $ch, CURLOPT_URL, $url );
		curl_setopt( $ch, CURLOPT_FAILONERROR, true );
		// ヘッダ文字列
		curl_setopt( $ch, CURLOPT_HEADER, false );
		// curl_execの返り値を文字列に
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout );

		if ( curl_exec( $ch ) === false )
			return 'Curl error: ' . curl_error( $ch );

		$results = curl_exec( $ch );
		if ( empty( $results ) ) return false;

		curl_close( $ch );

		if ( $decode ) return json_decode( $results, true );
		return $results;
	}

	/**
	 * curl が使えない場合、wp_remote_get を使う
	 *
	 * @param $url
	 * @param $timeout
	 * @param $decode
	 *
	 * @return array|bool|WP_Error
	 */
	protected function callback_curl_get_contents( $url, $timeout, $decode ) {
		$results = wp_remote_get(
			$url,
			array( 'timeout' => $timeout, 'sslverify' => false, 'headers' => array( 'Accept-Encoding' => '' ) )
		);
		if ( is_wp_error( $results ) || ! $results['response']['code'] === 200 )
			return false;
		if ( empty( $results['body'] ) )
			return false;

		if ( $decode ) return json_decode( $results['body'], true );
		return $results['body'];
	}

}

