<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * Override this template by copying it to yourtheme/woocommerce/content-single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
?>

<?php
	/**
	 * woocommerce_before_single_product hook
	 *
	 * @hooked woocommerce_show_messages - 10
	 */
	 do_action( 'woocommerce_before_single_product' );
?>

<!-- product nav -->
	<article class="product-nav">
		<!-- left -->
		<div class="left">
			<a class="product-back" href="<?php echo home_url( '/' ); ?>our-towels/">Back to our Towels</a>
		</div>
		<!-- end left -->
		
		<!-- right -->
		<div class="right">
			<span class="back"><?php previous_post_link('%link', 'Previous'); ?></span><span class="next"><?php next_post_link('%link', 'Next'); ?></span>
		</div>
		<!-- end right -->
	</article>
	<!-- end product nav -->
<div class="clear"></div>
<section id="product-<?php the_ID(); ?>" <?php post_class(); ?>>
	<!-- left -->
	<div class="left">
		<?php
			/**
			* woocommerce_show_product_images hook
			*
			* @hooked woocommerce_show_product_sale_flash - 10
			* @hooked woocommerce_show_product_images - 20
			*/
			do_action( 'woocommerce_before_single_product_summary' );
		?>
	</div>
	<!-- end left -->
	
	<!-- right -->
	<div class="right">
		<div class="inner">
			<!-- header -->
			<h1>
				<?php the_title(); ?><br>
				<!-- price -->
				<?php global $post, $product;?>
				<span class="price"><?php echo $product->get_price_html(); ?></span>
				<!-- end price -->
			</h1>
			<!-- end header -->
			
			
			
			<!-- description -->
			<?php the_content(); ?>
			<!-- end description -->
			
			<!-- in stock -->
			<?php if ( $product->is_in_stock() ) : ?>
			<p class="in-stock">In Stock</p>
			<?php endif; ?>
			<!-- end in stock -->
			
			<!-- add to cart -->
			<?php
				/**
				* woocommerce_single_product_summary hook
				*
				* @hooked woocommerce_template_single_title - 5
				* @hooked woocommerce_template_single_price - 10
				* @hooked woocommerce_template_single_excerpt - 20
				* @hooked woocommerce_template_single_add_to_cart - 30
				* @hooked woocommerce_template_single_meta - 40
				* @hooked woocommerce_template_single_sharing - 50
				*/
				do_action( 'woocommerce_single_product_summary' );
			?>
			<!-- end add to cart -->
			
			<!-- addthis share -->
			<div class="addthis_toolbox addthis_default_style ">
				<a class="addthis_button_facebook_like" fb:like:layout="button_count"></a>
				<a class="addthis_button_tweet"></a>
				<a class="addthis_button_pinterest_pinit"></a>
				<a class="addthis_button_email">
					<img src="<?php bloginfo( 'template_url' ); ?>/css/img/addthis-email-btn.png">
				</a>
			</div>
			<script type="text/javascript">var addthis_config = {"data_track_addressbar":true};</script>
			<script type="text/javascript" src="//s7.addthis.com/js/300/addthis_widget.js#pubid=ra-50f5ed780ea45f81"></script>
			<!-- end addthis share -->
		</div>
	</div>
	<!-- end right -->
	
	<?php
		/**
		 * woocommerce_after_single_product_summary hook
		 *
		 * @hooked woocommerce_output_product_data_tabs - 10
		 * @hooked woocommerce_output_related_products - 20
		 */
		do_action( 'woocommerce_after_single_product_summary' );
	?>

</section>

<?php do_action( 'woocommerce_after_single_product' ); ?>