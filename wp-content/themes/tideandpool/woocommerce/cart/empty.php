<?php
/**
 * Empty cart page
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
?>

<div class="site-messages">
	<p><?php _e('Your cart is currently empty.', 'woocommerce') ?>
	<?php do_action('woocommerce_cart_is_empty'); ?> <a class="button" href="<?php echo get_permalink(woocommerce_get_page_id('shop')); ?>"><?php _e('&larr; Return To Shop', 'woocommerce') ?></a></p>
</div>