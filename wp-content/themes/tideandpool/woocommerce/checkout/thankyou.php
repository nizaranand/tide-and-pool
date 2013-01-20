<?php
/**
 * Thankyou page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */


global $woocommerce;
?>

<?php if ($order) : ?>

	<?php if (in_array($order->status, array('failed'))) : ?>

		<p><?php _e('Unfortunately your order cannot be processed as the originating bank/merchant has declined your transaction.', 'woocommerce'); ?></p>

		<p><?php
			if (is_user_logged_in()) :
				_e('Please attempt your purchase again or go to your account page.', 'woocommerce');
			else :
				_e('Please attempt your purchase again.', 'woocommerce');
			endif;
		?></p>

		<p>
			<a href="<?php echo esc_url( $order->get_checkout_payment_url() ); ?>" class="button pay"><?php _e('Pay', 'woocommerce') ?></a>
			<?php if (is_user_logged_in()) : ?>
			<a href="<?php echo esc_url( get_permalink(woocommerce_get_page_id('myaccount')) ); ?>" class="button pay"><?php _e('My Account', 'woocommerce'); ?></a>
			<?php endif; ?>
		</p>

	<?php else : ?>

		<h1><?php _e('Your order has been received.', 'woocommerce'); ?></h1>

		<ul class="order_details">
			<li><strong>Thank you for your purchase!</strong></li>
			<li class="order">
				<?php _e('Order:', 'woocommerce'); ?>
				<strong><?php echo $order->get_order_number(); ?></strong>
			</li>
			<li>You will receive a confirmation email with details of your order shortly.</li>
			<li><a class="continue-shopping" href="<?php echo home_url( '/' ); ?>our-towels/">Continue Shopping</a></li>
			<!--
<li class="date">
				<?php _e('Date:', 'woocommerce'); ?>
				<strong><?php echo date_i18n(get_option('date_format'), strtotime($order->order_date)); ?></strong>
			</li>
			<li class="total">
				<?php _e('Total:', 'woocommerce'); ?>
				<strong><?php echo $order->get_formatted_order_total(); ?></strong>
			</li>
			<?php if ($order->payment_method_title) : ?>
			<li class="method">
				<?php _e('Payment method:', 'woocommerce'); ?>
				<strong><?php
					echo $order->payment_method_title;
				?></strong>
			</li>
-->
			<?php endif; ?>
		</ul>
		<div class="clear"></div>
		
		<!-- order confirm img -->
		<a class="order-confirm-img" href="http://pinterest.com/TideandPool" target="_blank">
			<img src="<?php bloginfo( 'template_url' ); ?>/css/img/order-confirmation.jpg">
		</a>
		<!-- end order confirm img -->

	<?php endif; ?>

	

<?php else : ?>

	<p><?php _e('Thank you. Your order has been received.', 'woocommerce'); ?></p>

<?php endif; ?>