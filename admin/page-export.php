<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fi_show_export() {

	echo '<div class="wrap fi-wrap">';

	?>

	<h2><?php _e( 'Export', 'feedly_insight' ); ?></h2>

	<div class="wrap">

		<p><?php _e( 'You can download Feedly Insight subscribers history as the CSV file.', 'feedly_insight' ); ?></p>

		<form action="" method="post">
			<p class="submit">
				<input type="submit" value="<?php esc_attr_e( 'Export CSV', 'feedly_insight' ); ?>" class="button-primary">
				<input type="hidden" name="mode" value="fi_download_csv">
			</p>
		</form>

	</div>

<?php


}


/**
 * Export subscribers history CSV
 */
function fi_export_csv() {
	if ( ! empty( $_POST['mode'] ) && $_POST['mode'] === 'fi_download_csv' ) {
		$db           = FI_DB::init();
		$result_query = $db->export_history();
		$first_date   = reset( $result_query );
		$last_date    = end( $result_query );
		$timestamp    = array(
			date( 'Ymd', strtotime( $first_date['save_date'] ) ),
			date( 'Ymd', strtotime( $last_date['save_date'] ) )
		);
		$timestamp    = implode( '_', $timestamp );

		$csv_data = '';
		foreach ( $result_query as $r ) {
			$csv_data .= $r['save_date'] . ',' . $r['subscribers'] . "\n";
		}

		$filename = "feedly_insight_subscribers_{$timestamp}.csv";

		// header info send to browser
		header( "Content-Type: application/octet-stream" );
		header( "Content-Disposition: attachment; filename=$filename" );
		echo mb_convert_encoding( $csv_data, 'SJIS-win', 'UTF-8' );

		exit();
	}
}

add_action( 'init', 'fi_export_csv' );

