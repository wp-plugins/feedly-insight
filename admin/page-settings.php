<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function fi_show_settings() {

	?>

	<div class="wrap">

		<h2><?php echo FI_NAME . ' ' . __( 'Settings', 'feedly_insight' ); ?></h2>

		<form method="post" action="options.php">

			<?php
			settings_fields( 'fi-settings-group' );
			?>

			<table class="form-table">
				<tr valign="top">
					<th scope="row"><?php _e( 'CSS', 'feedly_insight' ); ?></th>
					<td>
						<label>
							<input name="<?php echo FI_OPTION_NAME; ?>[css_enqueue]" type="checkbox" value="1"
								<?php checked( '1', FI::$option['css_enqueue'] ); ?> />
							<?php _e( 'Output css for Feedly button.', 'feedly_insight' ); ?>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Feed URL', 'feedly_insight' ); ?></th>
					<td>
						<label>
							<input type="text" name="<?php echo FI_OPTION_NAME; ?>[feed_url]" class="regular-text"
								   value="<?php esc_html_e( FI::$option['feed_url'] ); ?>" />
							<span style="display: block;">
								<?php _e( 'If you don\'t use WordPress default feed URL ( Ex. feed burner or other ), please input the URL.', 'feedly_insight' ); ?>
							</span>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Duplicate history', 'feedly_insight' ); ?></th>
					<td>
						<label>
							<input name="<?php echo FI_OPTION_NAME; ?>[duplicate]" type="checkbox" value="1"
								<?php checked( '1', ! empty( FI::$option['duplicate'] ) ? FI::$option['duplicate'] : 0 ); ?> />
							<?php _e( 'Exclude duplicated subscribers from history graph.', 'feedly_insight' ); ?>
						</label>
					</td>
				</tr>
				<tr valign="top">
					<th scope="row"><?php _e( 'Dashboard widget', 'feedly_insight' ); ?></th>
					<td>
						<label>
							<input name="<?php echo FI_OPTION_NAME; ?>[dashboard]" type="checkbox" value="1"
								<?php checked( '1', ! empty( FI::$option['dashboard'] ) ? FI::$option['dashboard'] : 0 ); ?> />
							<?php _e( 'Enable Feedly Insight dashboard widget.', 'feedly_insight' ); ?>
						</label>
					</td>
				</tr>
				<?php do_action( 'fi_page_settings' ); ?>
			</table>
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e( 'Save Changes', 'feedly_insight' ) ?>" />
			</p>

		</form>

	</div>

<?php
}


function fi_settings_validate( $input ) {
	// Our first value is either 0 or 1
	$input['css_enqueue'] = ( $input['css_enqueue'] == 1 ? 1 : 0 );

	// Say our second option must be safe text with no HTML tags
	$input['feed_url'] = wp_filter_nohtml_kses( $input['feed_url'] );
	if ( empty( $input['feed_url'] ) ) $input['feed_url'] = get_bloginfo( 'rss2_url' );

	$input['duplicate'] = ( $input['duplicate'] == 1 ? 1 : 0 );
	$input['dashboard'] = ( $input['dashboard'] == 1 ? 1 : 0 );

	delete_transient( 'feedly_subscribers' );

	return $input;
}

