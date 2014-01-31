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
	} ?>

	<div id="fi-results">
		<h3 id='fi-block-title' class='activity-block'><?php echo $title; ?></h3>

		<script type="text/javascript">
			/* <![CDATA[ */
			jQuery(function ($) {
				$("#fi-clear-results").click(function () {
					$("#fi-results").hide('slow', function () {
						$("#fi-results").remove();
						$("#wp-admin-bar-fi-clear-results-button").remove();
					});
				});
			});
			/* ]]> */
		</script>

		<?php fi_create_html_search_results( $results ); ?>
	</div>

<?php
}


function fi_create_html_search_results( $args ) {

	$counter = 0;
	foreach ( $args as $item ) {
		$counter ++;
		extract( $item );
		?>

		<style>
			.fi-img-ss {
				height: auto;
				max-width: 100%;
			}

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
			</h2>

			<div class="alignright"><?php fi_the_button( 'vertical', number_format_i18n( $subscribers ), $feedId ); ?></div>
			<p><?php echo $description; ?></p>

			<div class="clear"></div>

			<?php echo fi_get_create_mshots_img( $website, 300, 'fi-img-ss' ); ?>
			<div class="clear"></div>

			<dl class="fi-info-panel">

				<dt><i class="dashicons dashicons-clock"></i> <?php _e( 'Last update', 'feedly_insight' ); ?></dt>
				<dd><?php echo fi_convert_timestamp( $lastUpdated ) ?></dd>

				<dt><i class="dashicons dashicons-update"></i> <?php _e( 'Velocity', 'feedly_insight' ); ?></dt>
				<dd><?php echo $velocity; ?></dd>

				<dt><i class="dashicons dashicons-awards"></i> <?php _e( 'Score', 'feedly_insight' ); ?></dt>
				<dd><?php echo number_format_i18n( $score ); ?></dd>

				<dt><i class="dashicons dashicons-translation"></i> <?php _e( 'Languages', 'feedly_insight' ) ?></dt>
				<dd><?php echo fi_format_code_lang( $language ); ?></dd>

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


function fi_add_admin_remove_button( $wp_admin_bar ) {
	$title = '<span id="fi-clear-results">' . __( 'Clear results', 'feedly_insight' ) . '</span>';
	$wp_admin_bar->add_menu( array(
		'id'    => 'fi-clear-results-button',
		'meta'  => array( 'title' => __( 'Clear feedly search results when click this button.', 'feedly_insight' ) ),
		'title' => $title,
	) );
}

if ( ! empty( $_GET['search-feedly'] ) )
	add_action( 'admin_bar_menu', 'fi_add_admin_remove_button', 9999 );

