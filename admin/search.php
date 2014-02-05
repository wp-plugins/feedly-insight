<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

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
		<h2 id='fi-block-title' class='activity-block clear'>
			<?php echo $title; ?>
			<button id="fi-clear-results" class="button alignright">
				<img class="fi-icon" width="16" height="14"
					 src="<?php echo FI_IMG_URL; ?>/buttons/feedly-follow-logo-green_2x.png" />
				<?php _e( 'Clear results', 'feedly_insight' ); ?></button>
		</h2>
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

		<div class="activity-block">
			<div class="inside fi-item-<?php echo $counter; ?>">

				<h2>
					<small><?php echo $counter . '. '; ?></small>
					<?php if ( strstr( site_url(), $website ) ): ?>
						<div class="dashicons dashicons-admin-home"></div>
					<?php endif; ?>
					<a href="<?php echo $website; ?>" target="_blank" title="<?php echo $description; ?>">
						<?php echo wp_trim_words( $title, 30, '&hellip;' ); ?></a>
				</h2>

				<div class="alignright">
					<?php fi_the_button( 'vertical', number_format_i18n( $subscribers ), $feedId ); ?></div>
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

					<dt><i class="dashicons dashicons-translation"></i> <?php _e( 'Languages', 'feedly_insight' ) ?>
					</dt>
					<dd><?php echo fi_format_code_lang( $language ); ?></dd>

					<dt><i class="dashicons dashicons-performance"></i> <?php _e( 'Estimated', 'feedly_insight' ); ?>
					</dt>
					<dd><?php echo $estimatedEngagement; ?></dd>

					<dt><i class="dashicons dashicons-tag"></i> <?php _e( 'Tags', 'feedly_insight' ); ?></dt>
					<dd><?php echo implode( ', ', $deliciousTags ); ?></dd>

				</dl>

			</div>
		</div>
		<div class="clear"></div>

	<?php

	}
	return;
}


function fi_search_footer_js() {
	?>
	<script type="text/javascript">
		// <![CDATA[
		(function($){
			var target = $("#fi-results");
			var button = $("#fi-clear-results");
			var input = $('#fi-search-input');
			button.click(function () {
				target.hide('slow', function () {
					target.remove();
					input.val('');
				});
				input.focus();
				var position = $('#feedly_insight').offset().top;
				$('html,body').animate({ scrollTop: position }, 'slow', 'swing');
			});

			var top = button.offset().top;
			$(window).scroll(function (event) {
				var y = $(this).scrollTop();
				if (y >= top)
					button.addClass('fi-fixed');
				else
					button.removeClass('fi-fixed');
				//button.width(button.parent().width());
			});
		})(jQuery);
		// ]]>
	</script>
<?php
}


if ( ! empty( $_GET['search-feedly'] ) ) {
	add_action( 'admin_print_footer_scripts', 'fi_search_footer_js' );

	// remove welcome panel
	remove_action( 'welcome_panel', 'wp_welcome_panel' );
}

