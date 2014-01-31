<?php

// show dashboard widget
add_action( 'fi_dashboard', 'fi_show_dashboard' );
add_action( 'admin_print_footer_scripts', 'fi_dashboard_footer_js' );


function fi_show_dashboard() {

	$feeds = new FI_Feedly_Get();

	$feeds->set( get_bloginfo( 'rss2_url' ) );

	$results = $feeds->feed();

	if ( ! empty( $_GET['s'] ) ):
		fi_search_function( esc_attr( $_GET['s'] ), esc_attr( $_GET['c'] ) );

	elseif ( $results ):
		extract( $results );
		?>

		<style>
			.fi-history-placeholder {
				width: 600px;
				height: 300px;
				max-width: 100%;
			}

			.fi-info-panel dt {
				clear: both;
				color: #777;
				float: left;
				width: 33%;
			}

			.fi-info-panel dd {
				margin-left: 30%;
			}

			.fi-footer i, .fi-footer img {
				display: inline-block;
				vertical-align: top;
			}

			@media only screen and (min-width: 480px) {
				.fi-info-panel dt {
					width: 25%;
				}
			}
		</style>

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
		<h4><div class="dashicons dashicons-rss"></div>
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
			<input class="regular-text" id="fi-search-input" type="text" value="<?php if ( ! empty( $_GET ) ) echo $_GET['s']; ?>"
				   placeholder="<?php _e( 'Input URL, domain and any words.', 'feedly_insight' ); ?>"
				   name="s" />
			<input class="button" type="submit"
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
			<a href="<?php echo $AuthorURI; ?>" target="_blank">
				<i class="dashicons dashicons-admin-site"></i>
			</a>
			<a href="https://twitter.com/hayashikejinan" target="_blank">
				<i class="dashicons dashicons-twitter" style="color: #00aced"></i>
			</a>
			<a href="https://www.facebook.com/pages/HayashikeJinan/471796902840013" target="_blank">
				<i class="dashicons dashicons-facebook" style="color: #3b5998"></i>
			</a>
			<a href="https://plus.google.com/u/0/+hayashikejinantatsuo" target="_blank">
				<i class="dashicons dashicons-googleplus" style="color: #dd4b39"></i>
			</a>
			<a href="http://cloud.feedly.com/#subscription%2F<?php echo 'feed/' . $AuthorURI . 'feed/'; ?>" target="_blank">
				<img src="http://s3.feedly.com/img/follows/feedly-follow-square-flat-green_2x.png" alt="follow"
					 width="18" height="18" class="button-selectable">
			</a>
		</div>
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
		/* <![CDATA[ */
		jQuery.ajaxSetup({cache: true});
		jQuery(function ($) {
			var data = [<?php echo implode( ',' , $history ); ?>];

			$.plot("#fi-history-placeholder", [ data ], {
				grid  : {
					aboveData    : true,
					borderWidth  : 0,
					borderColor  : {
						//top:
						left : '#fff',
						//bottom:
						right: '#fff'
					},
					clickable    : true,
					hoverable    : true,
					autoHighlight: true
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
					min: 0
				}
			});
		});
		/* ]]> */
	</script>

<?php

}

