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

	<h2 id='fi-block-title' class='activity-block clear'><?php echo $title; ?></h2>
	<button id="fi-clear-results" class="button">
		<img class="fi-icon" width="16" height="14"
			 src="<?php echo FI_IMG_URL; ?>/buttons/feedly-follow-logo-green_2x.png" />
		<?php _e( 'Clear results', 'feedly_insight' ); ?></button>

	<hr />

	<div id="fi-results">

		<?php fi_create_html_search_results( $results ); ?>

	</div>
	<div class="clear"></div>

<?php
}


function fi_create_html_search_results( $args ) {

	$counter = 0;
	foreach ( $args as $item ) {
		$counter ++;
		extract( $item );
		?>

		<div class="fi-item fi-item-<?php echo $counter; ?>">

			<h2>
				<small><?php echo $counter . '. '; ?></small>
				<?php if ( strstr( site_url(), $website ) ): ?>
					<div class="dashicons dashicons-admin-home"></div>
				<?php endif; ?>
				<a href="<?php echo $website; ?>" target="_blank" title="<?php echo $description; ?>">
					<?php echo wp_trim_words( $title, 30, '&hellip;' ); ?></a>
			</h2>

			<?php fi_the_button( 'vertical', number_format_i18n( $subscribers ), $feedId ); ?>
			<p><?php echo $description; ?></p>

			<div class="clear"></div>

			<a href="<?php echo $website; ?>" target="_blank" title="<?php echo $title; ?>">
				<?php echo fi_get_create_mshots_img( $website, 480, 'fi-img-ss' ); ?>
			</a>

			<dl class="fi-info-panel">

				<dt><i class="dashicons dashicons-clock"></i> <?php _e( 'Last update', 'feedly_insight' ); ?></dt>
				<dd><?php echo fi_convert_timestamp( $lastUpdated ) ?></dd>

				<dt><i class="dashicons dashicons-update"></i>
					<abbr title="<?php _e( 'The average number of articles published weekly. This number is updated every few days.', 'feedly_insight' ); ?>">
						<?php _e( 'Velocity', 'feedly_insight' ) ?></abbr></dt>
				<dd><?php echo $velocity; ?></dd>

				<dt><i class="dashicons dashicons-awards"></i>
					<abbr title="<?php _e( 'What\'s meaning? I don\'t know...', 'feedly_insight' ); ?>">
						<?php _e( 'Score', 'feedly_insight' ); ?></abbr></dt>
				<dd><?php echo number_format_i18n( $score ); ?></dd>

				<dt><i class="dashicons dashicons-translation"></i> <?php _e( 'Languages', 'feedly_insight' ) ?>
				</dt>
				<dd><?php echo fi_format_code_lang( $language ); ?></dd>

				<dt><i class="dashicons dashicons-performance"></i>
					<abbr title="<?php _e( 'What\'s meaning? I don\'t know...', 'feedly_insight' ); ?>">
						<?php _e( 'Estimated engagement', 'feedly_insight' ); ?></abbr></dt>
				<dd><?php echo $estimatedEngagement; ?></dd>

				<dt><i class="dashicons dashicons-tag"></i> <?php _e( 'Tags', 'feedly_insight' ); ?></dt>
				<dd><?php echo implode( ', ', $deliciousTags ); ?></dd>

			</dl>

		</div>

	<?php

	}
	return;
}


function fi_search_footer_js() {
	?>
	<script type="text/javascript">
		// <![CDATA[
		(function ($) {
			var target = $("#fi-results");
			var button = $("#fi-clear-results");
			var input = $('#fi-search-input');
			button.click(function () {
				target.hide('slow', function () {
					target.remove();
					input.val('');
				});
				input.focus();
				$('html,body').animate({ scrollTop: 0 }, 'slow', 'swing');
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
}

