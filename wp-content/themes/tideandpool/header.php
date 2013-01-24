<?php
/**
* The Header for our theme.
*
* Displays all of the <head> section and everything up till <div id="main">
*
* @package WordPress
* @subpackage Boilerplate
* @since Boilerplate 1.0
*/
?><!DOCTYPE html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie6 lte7 lte8 lte9"><![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie7 lte7 lte8 lte9"><![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie ie8 lte8 lte9"><![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="no-js ie ie9 lte9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="initial-scale=1, width=device-width, user-scalable=no, maximum-scale=1.0">
<meta name="author" content="info@blackandblackcreative.com">
<meta name="distribution" content="Global">
<meta name="revisit-after" content="never">
<meta name="copyright" content="Copyright TIDE & POOL 2013">
<meta name="robots" content="nofollow,noindex">
<title><?php
/*
* Print the <title> tag based on what is being viewed.
* We filter the output of wp_title() a bit -- see
* boilerplate_filter_wp_title() in functions.php.
*/
wp_title( '|', true, 'right' );
?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/responsive.css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />

<!-- css plugin files -->
<?php if ( ! in_category('boutiques')) { ?>
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/js/vendor/galleria/themes/classic/galleria.classic.css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/js/vendor/touchcarousel/touchcarousel.css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/js/vendor/touchcarousel/black-and-white-skin/black-and-white-skin.css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/js/vendor/fancybox/jquery.fancybox.css" />
<?php } ?>
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/js/vendor/royalslider/royalslider.css" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/js/vendor/royalslider/default/rs-default.css" />

<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/jquery.min-1.8.3.js"></script>

<!-- plugin files for dev -->
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/royalslider/jquery.royalslider.min.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/touchcarousel/jquery.touchcarousel-1.1.min.js"></script>
<?php if ( ! in_category('boutiques')) { ?>
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/fancybox/jquery.fancybox.pack.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/galleria/galleria-1.2.9.min.js"></script> 
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/galleria/themes/classic/galleria.classic.min.js"></script>
<!-- <script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/jquery.isotope.min.js"></script> -->
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/jquery.masonry.min.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/respond.min.js"></script>
<?php } ?>
<!-- main site files for production -->
<script src="<?php bloginfo( 'template_url' ); ?>/js/plugins.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/main.js"></script>

<style type="text/css">
	html {
		margin-top:0 !important;
	}
</style>

<?php if ( ! in_category('boutiques')) { ?>
<?php
/* We add some JavaScript to pages with the comment form
* to support sites with threaded comments (when in use).
*/
if ( is_singular() && get_option( 'thread_comments' ) )
wp_enqueue_script( 'comment-reply' );

/* Always have wp_head() just before the closing </head>
* tag of your theme, or you will break many plugins, which
* generally use this hook to add elements to <head> such
* as styles, scripts, and meta tags.
*/
wp_head();
?>
<?php } ?>

</head>
<body <?php body_class(); ?>>

<?php if ( ! in_category('boutiques')) { ?>
<header role="banner" class="main-header">

<div class="inner">
	
	<!--
<nav class="user-utility">
	<?php wp_nav_menu( array( 'menu' => 'User Utility' )); ?>
	</nav>
-->
	
	<h1 class="logo">
		<a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home">
		<?php bloginfo( 'name' ); ?>
		</a>
	</h1>
	
	<h2 class="tag-line"><?php bloginfo( 'description' ); ?></h2>
	
	<!-- swim club signup link -->
	<a class="fancybox-inline swimclub-signup" href="#swimclub-popup">Swim Club</a>
	
	<div class="clear"></div>
	
</div><!-- .inner -->
	
	
	
<nav id="access" role="navigation" class="main-nav clear">
<div class="inner">
<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
	<div class="clear"></div>
	
	<?php if ( ! is_page('shopping-bag')) { ?>
	<!-- shopping bag widget/dropdown -->
	<div class="shopping-bag">
		<div class="my-shopping-bag">
			<?php global $woocommerce; ?>
			<a class="shopping-bag-link" href="<?php echo $woocommerce->cart->get_cart_url(); ?>">Shopping Bag <span class="total">(<?php echo sprintf(_n('%d item', $woocommerce->cart->cart_contents_count, 'woothemes'), $woocommerce->cart->cart_contents_count);?>)</span></a>

		</div> <!-- // .my-shopping-bag -->
		
		<?php if ( sizeof( $woocommerce->cart->get_cart() ) > 0 ) : ?>
		<div class="dropdown">
			<div class="items">
				<ul>
					<?php foreach ( $woocommerce->cart->get_cart() as $cart_item_key => $values ) :
						$_product = $values['data'];
						if ( $_product->exists() && $values['quantity'] > 0 ) :
							$product_quantity = esc_attr( $values['quantity'] );
							$product_price = (( get_option('woocommerce_display_cart_prices_excluding_tax') == 'yes' ) ? $_product->get_price_excluding_tax() : $_product->get_price()) * $product_quantity;
							//echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key ); 					
					?>
					<li>
						<a href="<?php echo esc_url( get_permalink( apply_filters('woocommerce_in_cart_product_id', $values['product_id'] ) ) ); ?>" class="clearfix">
							<!-- thumb -->
							<span class="shop-bag-thumb"><?php echo $_product->get_image('zoom-thumb'); ?></span>
							<!-- end thumb -->
							
							<!-- details -->
							<span class="details">
								<span class="title"><?php echo $_product->get_title(); ?></span>
								<span class="price"><?php echo apply_filters('woocommerce_cart_item_price_html', woocommerce_price( $product_price ), $values, $cart_item_key ); ?></span>
							</span>
							<!-- end details -->
							
							<span class="qty">Qty:<?php echo $product_quantity; ?></span>
						</a>
						<!--
<div class="remove">
							<?php echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf('<a href="%s" title="%s">&times;</a>', esc_url( $woocommerce->cart->get_remove_url( $cart_item_key ) ), __('Remove from cart', 'woocommerce') ), $cart_item_key ); 
							?>
						</div>
-->			
					</li>
				<?php endif; endforeach; ?>
				</ul>
			</div>
			<!-- end items -->
			
			<!-- sub total -->
			<div class="subtotal">
				<span class="text">Subtotal</span><span class="total"><?php echo $woocommerce->cart->get_cart_total(); ?></span>
			</div>
			<!-- end sub total -->
			
			<a href="<?php echo home_url( '/' ); ?>checkout/" class="go-to-checkout">Checkout</a>
		</div>
		<!-- end dropdown -->
		<?php endif; ?>			
	</div> <!-- // .shopping-bag -->
	<?php } ?>
</div>
</nav><!-- #access -->
	
	<div id="toggle-nav">&#9776;</div>
	

</header>



<section class="content" role="main">

<?php if (is_front_page())  { ?>
<div class="announcement">
	<p>
		<span class="font-18pt">Free shipping</span>
		<span class="font-11pt">on orders of 2 towels or more!</span>
		<span class="details-btn italic">Details
			<span class="free-shipping-flyaway hide">
				<a href="#">Free Shipping</a>
			</span>
		</span>
	</p>
	
</div><!-- END announcement -->
<?php  } ?>

<?php } ?>
