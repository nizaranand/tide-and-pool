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
			<a id="<?php echo $image['id']; ?>" href="<?php echo $image['url']; ?>" rel="zoom-id:zoom;" class="selector" rev="<?php echo $image['sizes']['shop_single']; ?>">
				<img src="<?php echo $image['sizes']['thumbnail']; ?>" alt="<?php echo $image['alt']; ?>" title="<?php echo $image['title']; ?>" />
			</a>
		</article>
		
		<?php endforeach; ?>	
		<?php endif; ?>
	</section>
	<!-- end thumbnails -->
	
	<!-- main img -->
	<div class="main-img">
		<!--
<?php if ( has_post_thumbnail() ) : ?>
		<a href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>" class="MagicZoom" id="zoom" class="active" rel="selectors-class: active;">
			<?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ) ) ?>
		</a>
		<?php endif; ?>
-->
		<?php $images = get_field('product_gallery'); $image = $images[0]; ?>
		<a href="<?php echo $image['url']; ?>" class="MagicZoom" id="zoom" class="active" rel="selectors-class: active;">
			<img src="<?php echo $image['sizes']['shop_single']; ?>" alt="<?php echo $image['alt']; ?>" />
		</a>
	</div>
	<!-- end main img -->
</section>
<!-- end zoom -->

<div class="clear"></div>

<!-- view larger btn -->

<?php if ( has_post_thumbnail() ) : ?>
<a class="view-larger fancybox" href="<?php echo wp_get_attachment_url( get_post_thumbnail_id() ); ?>">
	<img src="<?php bloginfo( 'template_url' ); ?>/css/img/view-larger-btn.png">
</a>
<?php endif; ?>

<script type="text/javascript">
	$(function() {
		<?php $images = get_field('product_gallery'); if( $images ): ?>
		<?php foreach( $images as $image ): ?>
		$('a#<?php echo $image['id']; ?>').click(function() {
			$("a.view-larger").attr("href", "<?php echo $image['url']; ?>");
		});
		<?php endforeach; ?>
		<?php endif; ?>
	});	
</script>

<!-- end view larger -->

<!-- <?php do_action('woocommerce_product_thumbnails'); ?> -->