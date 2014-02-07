<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// show dashboard widget
add_action( 'fi_dashboard', 'fi_show_dashboard' );
add_action( 'admin_print_footer_scripts', 'fi_dashboard_footer_js' );


function fi_show_dashboard() {

	$feeds = new FI_Feedly_Get();

	$feeds->set( get_bloginfo( 'rss2_url' ) );

	$results = $feeds->feed();

	if ( ! empty( $_GET['search-feedly'] ) ):
		fi_search_function( esc_attr( $_GET['search-feedly'] ), esc_attr( $_GET['c'] ) );

	elseif ( $results ):
		extract( $results );
		?>

		<div id="fi-history-placeholder" class="fi-history-placeholder"></div>

		<dl class="activity-block fi-info-panel">

			<dt><i class="dashicons dashicons-groups"></i> <?php _e( 'Subscribers', 'feedly_insight' ) ?></dt>
			<dd><?php printf( __( '%s <small>subscribers</small>', 'feedly_insight' ),
					number_format_i18n( $subscribers ) ); ?></dd>

			<dt><i class="dashicons dashicons-update"></i>
				<abbr title="<?php _e( 'Maybe post/a week.', 'feedly_insight' ); ?>">
					<?php _e( 'Velocity', 'feedly_insight' ) ?></abbr></dt>
			<dd><?php echo $velocity; ?></dd>

			<dt><i class="dashicons dashicons-tag"></i>
				<abbr title="<?php _e( 'Feedly sensed keywords.', 'feedly_insight' ); ?>">
					<?php _e( 'Topics', 'feedly_insight' ) ?></abbr></dt>
			<dd><?php echo $topics; ?></dd>

		</dl>
		<div class="clear"></div>

	<?php
	else:
	endif;
	?>

	<div class="activity-block">
		<h4>
			<div class="dashicons dashicons-rss"></div>
			<?php _e( 'Search RSS by Feedly', 'feedly_insight' ); ?></h4>

		<form method="get">
			<label for="fi-select-number"><?php _e( 'How many search (default is 20).', 'feedly_insight' ); ?></label>

			<?php $values = array( 3, 5, 10, 20, 50, 100 );
			$html = '<select name="c" id="fi-select-number">';
			foreach ( $values as $value ) {
				$html .= '<option ';
				$html .= "value='{$value}'";
				if ( empty( $_GET['c'] ) && $value === 20 || ! empty( $_GET['c'] ) && $value === (int) $_GET['c'] )
					$html .= ' selected="selected"';
				$html .= ">{$value}</option>";
			}
			echo $html . '</select>';
			?>

			<label class="screen-reader-text" for="fi-search-input"><?php _e( 'Search RSS by Feedly', 'feedly_insight' ); ?></label>
			<input class="regular-text" id="fi-search-input" type="text" value="<?php if ( ! empty( $_GET ) ) echo $_GET['search-feedly']; ?>"
				   placeholder="<?php _e( 'Input URL, domain and any words.', 'feedly_insight' ); ?>"
				   name="search-feedly" />
			<input class="button button-primary" type="submit"
				   value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
		</form>
		<p></p>
	</div>

	<div class="activity-block fi-footer">

		<?php extract( FI::$plugin_data ); ?>

		<span><small><?php _e( 'Version:', 'feedly_insight' ); ?></small>
			<?php echo $Version; ?></span>

		<div class="alignright">
			<i class="dashicons dashicons-admin-users"></i>Author:
			<a href="<?php echo $AuthorURI . '?page_id=827'; ?>" target="_blank" title="Blog">
				<i class="dashicons dashicons-admin-site"></i>
			</a>
			<a href="https://twitter.com/hayashikejinan" target="_blank" title="Twitter">
				<i class="dashicons dashicons-twitter"></i>
			</a>
			<a href="https://www.facebook.com/pages/HayashikeJinan/471796902840013" target="_blank" title="Facebook page">
				<i class="dashicons dashicons-facebook"></i>
			</a>
			<a href="https://plus.google.com/u/0/+hayashikejinantatsuo" target="_blank" title="Google+">
				<i class="dashicons dashicons-googleplus"></i>
			</a>
			<a href="http://cloud.feedly.com/#subscription%2F<?php echo 'feed/' . $AuthorURI . 'feed/'; ?>"
			   target="_blank" title="follow on Feedly">
				<img src="http://s3.feedly.com/img/follows/feedly-follow-square-flat-green_2x.png" alt="follow"
					 width="18" height="18" class="button-selectable">
			</a>
		</div>
		<div class="clear"></div>

	</div>

<?php

}


function fi_dashboard_footer_js() {
	// todo 特定の環境でビジュアルエディタのボタンが表示されないため無理矢理
	if ( get_current_screen()->id != 'dashboard' || ! empty( $_GET ) ) return;

	$db = FI_DB::init();

	$result_query = $db->get_subscribers_history();
	$history      = array();
	foreach ( $result_query as $h ) {
		$history[] .= '[' . strtotime( $h['save_date'] ) * 1000 . ',' . $h['subscribers'] . ']';
	}
	?>

	<script type="text/javascript">
		// <![CDATA[
		(function ($) {
			var label = '<?php _e('Subscribers', 'feedly_insight'); ?>';
			var data = [<?php echo implode( ',' , $history ); ?>];
			var target = '#fi-history-placeholder';

			// helper for returning the weekends in a period

			function weekendAreas(axes) {

				var markings = [],
					d = new Date(axes.xaxis.min);
				// go to the first Saturday
				d.setUTCDate(d.getUTCDate() - ((d.getUTCDay() + 1) % 7))
				d.setUTCSeconds(0);
				d.setUTCMinutes(0);
				d.setUTCHours(0);

				var i = d.getTime();

				// when we don't set yaxis, the rectangle automatically
				// extends to infinity upwards and downwards
				do {
					markings.push({ xaxis: { from: i, to: i + 2 * 24 * 60 * 60 * 1000 } });
					i += 7 * 24 * 60 * 60 * 1000;
				} while (i < axes.xaxis.max);
				return markings;
			}

			$.plot(target, [
				{
					data : data,
					label: label
				}
			], {
				grid  : {
					borderWidth  : 0,
					borderColor  : {
						//top:
						left : '#fff',
						//bottom:
						right: '#fff'
					},
					clickable    : true,
					hoverable    : true,
					autoHighlight: true,
					markings     : weekendAreas
				},
				series: {
					color : '#87c040',
					lines : { show: true },
					points: { show: true }
				},
				xaxis : {
					mode      : "time",
					timeformat: "%m/%d"
				},
				yaxis : {
					//min: 0
					minTickSize: 1,
					tickDecimals: 0
				},
				legend: {
					position: "se",
					show    : true
				}
			});

			$("<div id='fi-tooltip'></div>").css({
				position          : "absolute",
				display           : "none",
				border            : "1px solid #fdd",
				padding           : "2px",
				"background-color": "#eee",
				opacity           : 0.80
			}).appendTo("body");

			$(target).bind("plothover", function (event, pos, item) {

				if ($("#enablePosition:checked").length > 0) {
					var str = "(" + pos.x.toFixed(2) + ", " + pos.y.toFixed(2) + ")";
					$("#hoverdata").text(str);
				}

				if ($(target).length > 0) {
					if (item) {
						var x = item.datapoint[0],
							y = item.datapoint[1];
						var time = $.plot.formatDate(new Date(x), "%Y/%m/%d");

						$("#fi-tooltip").html(time + " " + item.series.label + ": " + y)
							.css({top: item.pageY + 5, left: item.pageX + 5})
							.fadeIn(200);
					} else {
						$("#fi-tooltip").hide();
					}
				}
			});

		})(jQuery);
		// ]]>
	</script>

<?php

}

