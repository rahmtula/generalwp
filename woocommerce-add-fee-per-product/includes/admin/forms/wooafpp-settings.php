<?php

/**
 * Settings Page
 * 
 * The html markup for the settings
 * 
 * @package WooCommerce Add Fee Per Product
 * @since 1.0.0
 */

// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;
?>
<div class="wrap">
	<h3 class="hndle"><?php _e( 'Admin Settings Page', WOOAFPP_TEXTDOMAIN ); ?></h3>
	<div class="inside">
		<form method="post" action="options.php" id="wooafpp-settings-form">

			<?php
			settings_fields( 'wooafpp_settings_options' );

			//Get options
			$wooafpp_options = get_option( 'wooafpp_options' );
			$product_fees = !empty( $wooafpp_options['product_fees'] ) ? $wooafpp_options['product_fees'] : array(
				0 => array(
					'product_id' => '',
					'fee' => 0,
					'fee_type' => 'fixed',
				)
			);
			?>
			<table class="form-table">
				<tbody>
					<tr>
						<th scope="row">
							<label><?php _e( 'Select Product', WOOAFPP_TEXTDOMAIN ); ?></label>
						</th>
						<td>
							<div class="wooafpp_repeater_wrap">
								<?php
								$args = array(
									'post_type' => array( 'product', 'product_variation' ),
									'post_status' => 'publish',
									'posts_per_page'	 => -1,
									'fields' => 'ids',
									'orderby' => 'title'
								);
								$all_products = get_posts( $args );

								if( !empty( $product_fees ) ) {
									foreach ( $product_fees as $key => $product_fee ) {?>
									<div data-index="<?php echo $key;?>" class="wooafpp_repeater_item">
										<?php
										if( !empty( $all_products ) ) {
										echo '<select name="wooafpp_options[product_fees]['. $key .'][product_id]" data-sample="wooafpp_options[product_fees][{key}][product_id]">';
										echo '<option>'. __( 'Choose Product', WOOAFPP_TEXTDOMAIN ) .'</option>';
											foreach ( $all_products as $product_id ) {
												$_product = wc_get_product( $product_id );
												echo '<option '. selected( $product_fee['product_id'], $product_id, false ) .' value="'. $_product->get_ID() .'">'. wp_kses_post( $_product->get_formatted_name() ) .'</option>';
											}
										echo '</select>';
										}?>
										<input name="wooafpp_options[product_fees][<?php echo $key;?>][fee]" type="number" value="<?php echo $product_fee['fee']?>" class="small-text" data-sample="wooafpp_options[product_fees][{key}][fee]">
										<select name="wooafpp_options[product_fees][<?php echo $key;?>][fee_type]" data-sample="wooafpp_options[product_fees][{key}][fee_type]">
											<option value="fixed" <?php selected( $product_fee['fee_type'], 'fixed' );?>><?php _e( 'Fixed', WOOAFPP_TEXTDOMAIN ); ?></option>
											<option value="percent" <?php selected( $product_fee['fee_type'], 'percent' );?>><?php _e( 'Percentage', WOOAFPP_TEXTDOMAIN ); ?></option>
										</select>
										<button class="button wooafpp_repeater_remove" type="button">-</button>
									</div>
								<?php
									}
								}?>
							</div>
							<button class="button wooafpp_repeater_addnew" type="button"><?php _e( 'Add More', WOOAFPP_TEXTDOMAIN ); ?></button>
						</td>
					</tr>
					<tr>
						<td colspan="2" align="right">
							<input type="submit" class="button-primary" value="<?php _e( 'Save Settings', WOOAFPP_TEXTDOMAIN ); ?>"/>
						</td>
					</tr>
			</table>
		</form>
	</div>
</div>