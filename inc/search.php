<?php

function fi_search_function( $search_words, $number ) {

	$feeds = new FI_Feedly_Get();
	$feeds->set( $search_words );
	$results = $feeds->search( $number );

	$title = sprintf( _n( '%s result', '%s results', count( $results ), 'feedly_insight' ), number_format_i18n( count( $results ) ) );
	$title = sprintf( __( 'Search results for &#8220;%s&#8221;', 'feedly_insight' ),
			urldecode( $search_words ) ) . ': <strong>' . $title . '</strong>';

	if ( ! count( $results ) ) {
		echo $title;
		echo '<p>' . __( 'No items.', 'feedly_insight' ) . '</p>';
		return;
	}

	$output = '';
	$output .= "<h3 id='fi-block-title' class='activity-block'>{$title}</h3>";

	echo $output;

	fi_create_html_search_results( $results );

	echo $output;

}


function fi_create_html_search_results( $args ) {

	$counter = 0;
	foreach ( $args as $item ) {
		$counter ++;
		extract( $item );
		?>

		<style>
			.fi-info-panel dt {
				clear: both;
				color: #777;
				float: left;
				width: 120px;
			}

			.fi-info-panel dd {
				margin-left: 130px;
			}

			.fi-footer i, .fi-footer img {
				vertical-align: middle;
			}

			@media only screen and (min-width: 480px) {
				.fi-info-panel dt {
					width: 120px;
				}
			}
		</style>

		<div class="inside fi-item-<?php echo $counter; ?>">

			<h2>
				<small><?php echo $counter . '. '; ?></small>
				<a href="<?php echo $website; ?>" target="_blank" title="<?php echo $description; ?>">
					<?php echo wp_trim_words( $title, 30, '&hellip;' ); ?></a>

				<?php if ( strstr( site_url(), $website ) ) echo '<i class="dashicons dashicons-yes"></i>'; ?>

				<a class="" href="http://cloud.feedly.com/#subscription%2F<?php echo urlencode( $feedId ); ?>"
				   target="blank" title="<?php printf( __( 'Follow %s in feedly', 'feedly_insight' ), $title ); ?>">
					<img src="http://s3.feedly.com/img/follows/feedly-follow-rectangle-flat-small_2x.png" alt="follow"
						 width="61" height="20" class="button-selectable"></a>
			</h2>

			<p><?php echo $description; ?></p>

			<div class="clear"></div>

			<?php echo fi_get_create_mshots_img( $website, 200, '' ); ?>

			<dl class="fi-info-panel">

				<dt><i class="dashicons dashicons-clock"></i> <?php _e( 'Last update', 'feedly_insight' ); ?></dt>
				<dd><?php echo fi_convert_timestamp( $lastUpdated ) ?></dd>

				<dt><i class="dashicons dashicons-update"></i> <?php _e( 'Velocity', 'feedly_insight' ); ?></dt>
				<dd><?php echo $velocity; ?></dd>

				<dt><i class="dashicons dashicons-groups"></i> <?php _e( 'Subscribers', 'feedly_insight' ); ?></dt>
				<dd><?php printf( __( '%s <small>subscribers</small>', 'feedly_insight' ),
						number_format_i18n( $subscribers ) ); ?></dd>

				<dt><i class="dashicons dashicons-awards"></i> <?php _e( 'Score', 'feedly_insight' ); ?></dt>
				<dd><?php echo number_format_i18n( $score ); ?></dd>

				<dt><i class="dashicons dashicons-translation"></i> <?php _e( 'Languages', 'feedly_insight' ) ?></dt>
				<dd><?php echo $language; ?></dd>

				<dt><i class="dashicons dashicons-performance"></i> <?php _e( 'Estimated', 'feedly_insight' ); ?></dt>
				<dd><?php echo $estimatedEngagement; ?></dd>

				<dt><i class="dashicons dashicons-tag"></i> <?php _e( 'Tags', 'feedly_insight' ); ?></dt>
				<dd><?php echo implode( ', ', $deliciousTags ); ?></dd>

			</dl>

		</div>

		<div class="clear"></div>

	<?php

	}
	return;
}

