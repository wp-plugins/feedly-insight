<?php

add_action( 'fi_search_page', 'fi_show_search_page' );

function fi_show_search_page() {

	?>

	<div class="wrap">

		<h2 class="page-title">
			<img src="<?php echo FI_BTN_URL . 'feedly-follow-square-flat-green_2x.png'; ?>" alt="" width="28" height="28" />
			<?php _e( 'Search RSS by Feedly', 'feedly_insight' ); ?>
		</h2>

		<div class="clear"></div>

		<form method="get" action="<?php echo esc_url( admin_url( 'admin.php' ) ); ?>">
			<p>
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
			</p>

			<label class="screen-reader-text" for="fi-search-input"><?php _e( 'Search RSS by Feedly', 'feedly_insight' ); ?></label>
			<input type="hidden" name="page" value="feedly_insight" />
			<input class="regular-text" id="fi-search-input" type="text" name="s"
				   value="<?php if ( ! empty( $_GET['s'] ) ) echo $_GET['s']; ?>"
				   placeholder="<?php _e( 'Input URL, domain and any words.', 'feedly_insight' ); ?>" />
			<input class="button" type="submit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
		</form>
		<p></p>

		<?php if ( ! empty( $_GET['s'] ) )
			fi_search_function( esc_attr( $_GET['s'] ), esc_attr( intval( $_GET['c'] ) ) ); ?>

	</div>

<?php

}
