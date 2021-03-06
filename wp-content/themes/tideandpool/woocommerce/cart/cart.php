<?php
/**
 * Cart Page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $woocommerce, $post, $product;
?>


<?php $woocommerce->show_messages(); ?>

<!-- header -->
<h1>Shopping Bag</h1>
<!-- end header -->

<form action="<?php echo esc_url( $woocommerce->cart->get_cart_url() ); ?>" method="post">
<?php do_action( 'woocommerce_before_cart_table' ); ?>
<table class="shop_table cart" cellspacing="0">
	<thead>
		<tr>
			<!-- <th class="product-remove">&nbsp;</th> -->
			<th class="product-thumbnail"><?php _e('Items:', 'woocommerce'); ?></th>
			<th class="product-name" width="500"></th>
			<th class="product-name" width="250"><?php _e('Availability:', 'woocommerce'); ?></th>
			<th class="product-price" width="250"><?php _e('Price:', 'woocommerce'); ?></th>
			<th class="product-quantity" width="250"><?php _e('Qty:', 'woocommerce'); ?></th>
			<th class="product-subtotal" style="text-align:right;padding-right:30px;" width="100"><?php _e('SubTotal:', 'woocommerce'); ?></th>
		</tr>
	</thead>
	<tr>
		<td>&nbsp;</td>
	</tr>
	<tbody>
		<?php do_action( 'woocommerce_before_cart_contents' ); ?>

		<?php
		if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) {
			foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) {
				$_product = $values['data'];
				if ( $_product->exists() && $values['quantity'] > 0 ) {
					?>
					<tr class = "<?php echo esc_attr( apply_filters('woocommerce_cart_table_item_class', 'cart_table_item', $values, $cart_item_key ) ); ?>">
						<!-- Remove from cart link -->
						<!--
<td class="product-remove">
							<?php
								echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'woocommerce') ), $cart_item_key );
							?>
						</td>
-->

						<!-- The thumbnail -->
						<td class="product-thumbnail">
							<?php
								$thumbnail = apply_filters( 'woocommerce_in_cart_product_thumbnail', $_product->get_image('zoom-thumb'), $values, $cart_item_key );
								printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), $thumbnail );
							?>
						</td>

						<!-- Product Name -->
						<td class="product-name">
							<?php
								if ( ! $_product->is_visible() || ( $_product instanceof WC_Product_Variation && ! $_product->parent_is_visible() ) )
									echo apply_filters( 'woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key );
								else
									printf('<a href="%s">%s</a>', esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ), apply_filters('woocommerce_in_cart_product_title', $_product->get_title(), $values, $cart_item_key ) );

								// Meta data
								echo $woocommerce->cart->get_item_data( $values );

                   				// Backorder notification
                   				if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $values['quantity'] ) )
                   					echo '<p class="backorder_notification">' . __('Available on backorder', 'woocommerce') . '</p>';
							?>
							<p class="sku">Item # [SKU NO HERE...] </p>
						
						</td>
						
						<td>
						
						[IN STOCK DATA HERE...]

						</td>

						<!-- Product price -->
						<td class="product-price">
							
							<div class="sale-price">
							<?php $product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();

								echo woocommerce_price( $_product->sale_price );
							?>
							</div>
							<div class="regular-price">
							<?php
								$product_price = get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' || $woocommerce->customer->is_vat_exempt() ? $_product->get_price_excluding_tax() : $_product->get_price();

								echo woocommerce_price( $_product->regular_price );
								
							?>	
							</div>
						</td>

						<!-- Quantity inputs -->
						<td class="product-quantity">
							<?php
								if ( $_product->is_sold_individually() ) {
									$product_quantity = '1';
								} else {
									$data_min = apply_filters( 'woocommerce_cart_item_data_min', '', $_product );
									$data_max = ( $_product->backorders_allowed() ) ? '' : $_product->get_stock_quantity();
									$data_max = apply_filters( 'woocommerce_cart_item_data_max', $data_max, $_product );

									$product_quantity = sprintf( '<div class="amount"><input name="cart[%s][qty]" data-min="%s" data-max="%s" value="%s" size="4" title="Qty" class="input-text qty text" maxlength="12" /></div>', $cart_item_key, $data_min, $data_max, esc_attr( $values['quantity'] ) );
								}

								echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key );
							?>
							<div class="update-remove">
								<input type="submit" class="button" name="update_cart" value="<?php _e('Update', 'woocommerce'); ?>" />
								<div class="remove">
									<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" class="remove" title="%s">Remove</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove this item', 'woocommerce') ), $cart_item_key ); ?>
								</div>
							</div>
						</td>
					

						<!-- Product subtotal -->
						<td class="product-subtotal">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', $woocommerce->cart->get_product_subtotal( $_product, $values['quantity'] ), $values, $cart_item_key );
							?>
						</td>
					</tr>
					<tr>
						<td>&nbsp;</td>
					</tr>
					<?php
				}
			}
		}

		do_action( 'woocommerce_cart_contents' );
		?>
		<tr>
			<td class="border" colspan="6">&nbsp;</td>
		</tr>
		<input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e('Checkout', 'woocommerce'); ?>" />
		<?php $woocommerce->nonce_field('cart') ?>
	</tbody>
</table>
<?php do_action( 'woocommerce_after_cart_table' ); ?>


<!-- cross sells -->
<div class="cross-sells">
	<div class="left">
		<h2>Dont Love It?</h2>
		<p>No problem. Returns are easy and FREE for 30 days!</p>
		<a class="cross-sell-btn fancybox-customer-service" href="<?php echo home_url( '/' ); ?>customer-service/returns/">Returns Information<span class="sm-arrow-right"><img src="<?php bloginfo( 'template_url' ); ?>/css/img/sm-arrow-right.png"></span></a>
	</div>
	
	<div class="right">
		<h2>Express Shipping</h2>
		<p>Need it fast? Select our 2-day Shipping.<br>  Find out more about shipping rates and times.</p>
		<a class="cross-sell-btn fancybox-customer-service" href="<?php echo home_url( '/' ); ?>customer-service/shipping-and-handiling/">Shipping Information<span class="sm-arrow-right"><img src="<?php bloginfo( 'template_url' ); ?>/css/img/sm-arrow-right.png"></span></a>
	</div>
</div>
<!-- end cross sells -->

<!-- cart collaterals -->
<div class="cart-collaterals">
	<!-- totals -->
	<div class="totals">
		<?php do_action('woocommerce_cart_collaterals'); ?>
		<?php woocommerce_cart_totals(); ?>
		<!-- <?php woocommerce_shipping_calculator(); ?> -->
	</div>
	<!-- end totals -->
	
	<!-- cart btns -->
	<div class="cart-btns">
		<a class="continue-shopping" href="<?php echo home_url( '/' ); ?>our-towels/">Continue Shopping</a>
		<!--
<input type="submit" class="checkout-button button alt" name="proceed" value="<?php _e('Checkout', 'woocommerce'); ?>" />
		<?php $woocommerce->nonce_field('cart') ?>
-->
	</div>
	<!-- end cart btns -->
	
</div>
<!-- end cart collaterals -->							
				<!--
<?php if ( get_option( 'woocommerce_enable_coupons' ) == 'yes' && get_option( 'woocommerce_enable_coupon_form_on_cart' ) == 'yes') { ?>
					<div class="coupon">

						<label for="coupon_code"><?php _e('Coupon', 'woocommerce'); ?>:</label> <input name="coupon_code" class="input-text" id="coupon_code" value="" /> <input type="submit" class="button" name="apply_coupon" value="<?php _e('Apply Coupon', 'woocommerce'); ?>" />

						<?php do_action('woocommerce_cart_coupon'); ?>

					</div>
				<?php } ?>
 <input type="submit" class="button" name="update_cart" value="<?php _e('Update Cart', 'woocommerce'); ?>" /> --> 
</form>
<?php do_action( 'woocommerce_after_cart_contents' ); ?>
	

