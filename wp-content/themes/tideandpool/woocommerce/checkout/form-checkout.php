<?php
/**
 * Checkout Form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $woocommerce; $woocommerce_checkout = $woocommerce->checkout();
?>

<?php $woocommerce->show_messages(); ?>

<?php do_action('woocommerce_before_checkout_form');

// If checkout registration is disabled and not logged in, the user cannot checkout
if (get_option('woocommerce_enable_signup_and_login_from_checkout')=="no" && get_option('woocommerce_enable_guest_checkout')=="no" && !is_user_logged_in()) :
	echo apply_filters('woocommerce_checkout_must_be_logged_in_message', __('You must be logged in to checkout.', 'woocommerce'));
	return;
endif;

// filter hook for include new pages inside the payment method
$get_checkout_url = apply_filters( 'woocommerce_get_checkout_url', $woocommerce->cart->get_checkout_url() ); ?>

<!-- checkout form -->
<form name="checkout" method="post" class="checkout" action="<?php echo esc_url( $get_checkout_url ); ?>">
	<!-- header -->
	<h1 class="checkout">Checkout</h1>
	<!-- end header -->
		<?php if ( sizeof( $woocommerce_checkout->checkout_fields ) > 0 ) : ?>
		<?php do_action( 'woocommerce_checkout_before_customer_details'); ?>

		<!-- customer details -->
		<article id="customer_details" class="checkout-panel">
			<!-- header -->
			<h2 class="checkout">1. Checkout Method (STATIC CONTENT)<span class="edit">Edit</span></h2>
			<!-- end header -->
			
			<!-- content -->
			<div class="content">
				<!-- left -->
				<div class="left">
					<!-- header -->
					<h3>New to TIDE & POOL?<br><span class="blue-green">Account Sign Up or Checkout as a Guest</span></h3>
					<!-- end header -->
					
					<!-- text -->				
					<p>Register to save time on future visits and enjoy exclusive previews of new designs. Or checkout as a guest without creating an account.</p>
					<!-- end text -->
					
					<!-- form -->
					<input type="radio" name="register" value="register">Resgister<br>
					<input type="radio" name="register" value="register">Checkout as Guest<br>
						
					<a class="continue" href="#">Continue</a>
					<!-- end form -->
				</div>
				<!-- end left -->
			
				<!-- right -->
				<div class="right">
					<!-- header -->
					<h3 class="checkout">TIDE & POOL Account Holders<br><span class="blue-green">Sign in below to access your account</span></h3>
					<!-- end header -->
					
					<!-- form -->
					<label>Email Address</label><input type="text" name="email" placeholder="email address">
					<label>Password</label><input type="password" name="email">
					<div class="clear"></div>
					<a class="forgot-password" href="#">Forgot Password</a>
						
					<a class="continue" href="#">Sign In</a>
					<!-- end form -->
					<!-- <?php wp_login_form(); ?> -->
					
					<!-- <?php do_action('woocommerce_before_customer_login_form'); ?> -->
				</div>
				<!-- end right -->
				
				<div class="clear"></div>
			</div>
			<!-- end content -->
		</article>
		<!-- end customer details -->
		
		<!-- shipping info -->
		<article id="shipping-info" class="checkout-panel">
			<!-- header -->
			<h2 class="checkout">2. Shipping Information<span class="edit">Edit</span></h2>
			<!-- end header -->
			
			<!-- content -->
			<div class="content">
				<!-- header -->
				<h3>Enter a Shipping Address<br><span class="blue-green">All fields except those indicated are required.</span></h3>
				<!-- end header -->
				
				<!-- shipping address form -->
				<div class="address-form">
					<?php do_action('woocommerce_checkout_shipping'); ?>
				</div>
				<!-- end shipping address form -->
			
				<div class="clear"></div>
				<!-- shipping method -->
				<div class="shipping-method">
					<!-- header -->
					<h3 class="checkout">Select Shipping Method</h3>
					<!-- end header -->
					
					[shipping methods from UPS API here...]
					
					<a class="continue" href="#">Continue</a>
				</div>
				<!-- end shipping method -->
			</div>
			<!-- end content -->
		</article>
		<!-- end shipping info -->
		
		<!-- gift options -->
		<article id="gift-options" class="checkout-panel">
			<!-- header -->
			<h2 class="checkout">3. Gift Options (STATIC CONTENT)<span class="edit">Edit</span></h2>
			<!-- end header -->
			
			<!-- content -->
			<div class="content">
				<!-- header -->
				<h3>Gift Options<br><span class="blue-green">All fields except those indicated are required.</span></h3>
				<!-- end header -->
				
				
				<!-- gift message form -->
				<div class="left" id="gift-message">
					<!-- text -->
				<p><strong>Is this a gift?</strong> By selecting YES, you will receive a complimentary gift receipt and card.<br><br><input type="radio" name="register" value="register">YES <input type="radio" name="register" value="register">NO</p><br>
				<!-- end text -->
					<textarea></textarea>
					
					<p>You have 175 out of 175 characters remaining.</p>
					
					<a class="continue" href="#">Continue</a>
				</div>
				<!-- end gift message form -->
			
				<!-- shipping method -->
				<div class="right" id="gift-wrap-img">
					<textarea>[dynamic gift wrap message here...]</textarea>
				</div>
				<!-- end shipping method -->
				
				<div class="clear"></div>
			</div>
			<!-- end content -->
		</article>
		<!-- end gift options -->
		
		<!-- billing address -->
		<article id="billing-address" class="checkout-panel">
			<!-- header -->
			<h2 class="checkout">4. Billing Address<span class="edit">Edit</span></h2>
			<!-- end header -->
			
			<!-- content -->
			<div class="content">
				<!-- billing address form -->
				<div class="address-form">
					<?php do_action('woocommerce_checkout_billing'); ?>
				</div>
				<!-- end billing address form -->
			</div>
			<!-- end content -->
		</article>
		<!-- end billing address -->

		<?php do_action( 'woocommerce_checkout_after_customer_details'); ?>

		<!-- <h1 id="order_review_heading"><?php _e('Your order', 'woocommerce'); ?></h1> -->

		<?php endif; ?>
		
		<!-- payment method & order review -->
		<?php do_action('woocommerce_checkout_order_review'); ?>
		<!-- end payment method & order review -->
</form>
<!-- end checkout form -->

<!-- checkout progress -->
<div class="checkout-progress">
	<!-- header -->
	<h2>Progress</h2>
	<!-- end header -->
	
	<!-- panels -->
	<div class="progress-panels">
		<article class="panel">Shipping</article>
		<article class="panel">Gift Options</article>
		<article class="panel">Billing</article>
		<article class="panel">Order Review</article>
		<article class="panel">Payment Method</article>
	<!-- end panels -->
</div>
<!-- end checkout progress -->

<?php do_action('woocommerce_after_checkout_form'); ?>