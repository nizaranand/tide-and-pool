<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $product, $woocommerce_loop;

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
	$woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
	$woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibilty
if ( ! $product->is_visible() )
	return;

// Increase loop count
$woocommerce_loop['loop']++;
?>
<!-- slide -->
<li class="slide touchcarousel-item">

	<?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

	<a href="<?php the_permalink(); ?>">

		
		<?php if(get_field('product_carousel')): ?>
		<?php while(the_repeater_field('product_carousel')): ?>
		<!-- thumbnail -->
		<div class="thumbnail">
			<img src="<?php $image = wp_get_attachment_image_src(get_sub_field('carousel_main_image'), 'product-carousel'); ?><?php echo $image[0]; ?>"/>
		</div>
		<!-- end thumbnail -->
		
		<!-- overlay -->
		<div class="overlay">
			<!-- hover img -->
			<div class="hover-img">
				<?php $image = wp_get_attachment_image_src(get_sub_field('carousel_hover_image'), 'product-carousel'); ?>
				<img src="<?php echo $image[0]; ?>"/>
			</div>
			<!-- end hover img -->
			
			<!-- product details -->
			<div class="product-details">
				<h3><?php the_title(); ?></h3>
				<?php do_action( 'woocommerce_after_shop_loop_item_title' );?>
			</div>
			<!-- end product details -->
		</div>
		<!-- end overlay -->
		
		<?php endwhile; ?>
		<?php endif; ?>
	</a>

	<?php do_action( 'woocommerce_after_shop_loop_item' ); ?>

</li>
<!-- end slide -->