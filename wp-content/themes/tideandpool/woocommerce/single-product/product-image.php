<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

global $post, $woocommerce;

?>

<!-- zoom -->
<section class="zoom">
	<!-- thumbnails -->
	<section class="thumbnails">
		<?php $images = get_field('product_gallery'); if( $images ): ?>
		<?php foreach( $images as $image ): ?>
		<article>
			<a href="<?php echo $image['url']; ?>" rel="zoom-id:zoom;selectors-class: Active;" rev="<?php echo $image['sizes']['large']; ?>">
				<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" />
			</a>
		</article>
		<?php endforeach; ?>	
		<?php endif; ?>
	</section>
	<!-- end thumbnails -->
	
	<!-- main img -->
	<section class="main-img">
		<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" class="MagicZoom Selector" id="zoom" rel="">
			<?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ?>
		</a>
		<?php endif; ?>
	</section>
	<!-- end main img -->
</section>
<!-- end zoom -->

<div class="clear"></div>

<!-- view larger btn -->
<a class="view-larger fancybox" rel="gallery1" href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>">
	<img src="<?php bloginfo( 'template_url' ); ?>/css/img/view-larger-btn.png">
</a>		
<?php $images = get_field('product_gallery'); if( $images ): ?>
<?php foreach( $images as $image ): ?>
<a class="view-larger fancybox" rel="gallery1" href="<?php echo $image['sizes']['large']; ?>" title=""></a>
<?php endforeach; ?>	
<?php endif; ?>
<!-- end view larger -->

<!-- <?php do_action('woocommerce_product_thumbnails'); ?> -->