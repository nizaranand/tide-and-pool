<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

<?php if (is_page('homepage')) { ?>
<!-- home slideshow -->
<div class="slideshow home rsDefault">
	<?php $my_query = new WP_Query('page_id=21&showposts=1'); ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<?php if(get_field('home_page_slideshow')): ?>
	<?php while(the_repeater_field('home_page_slideshow')): ?>
	<div class="rsImg">
		<a href="
			<?php if(get_sub_field('home_slideshow_link'))
				{
				the_sub_field('home_slideshow_link');
				}
				else {
					echo('#');
				}
			?>
			">
			<?php $image = wp_get_attachment_image_src(get_sub_field('home_slideshow_image'), 'home-slideshow'); ?>
			<img src="<?php echo $image[0]; ?>" />
		</a>
	</div>
	<?php endwhile; ?>
	<?php endif; ?>
	<?php endwhile; ?>
</div>
<!-- end home slideshow -->
<?php } elseif (is_page('collections')) { ?>

<!-- collections container -->
<section id="collections" class="thumb-container">
	<?php $my_query = new WP_Query('page_id=4&showposts=1'); ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<?php if(get_field('collections_gallery')): ?>
	<?php while(the_repeater_field('collections_gallery')): ?>
	<!-- thumb -->
	<article class="thumb">
		<a <?php if(! get_sub_field('link')) { ?>class="fancybox"<?php } ?> href="
			<?php if(get_sub_field('link'))
				{
				the_sub_field('link');
				}
				else {
					$image = wp_get_attachment_image_src(get_sub_field('zoom_image'), 'large');
					echo $image[0];
				}
			?>
			">
			<?php $image = wp_get_attachment_image_src(get_sub_field('collections_thumbnail'), 'large'); ?>
			<img src="<?php echo $image[0]; ?>" alt="" />
		</a>
	</article>
	<!-- end thumb -->
	<?php endwhile; ?>
	<?php endif; ?>
	<?php endwhile; ?>
</section>
<!-- end collection container -->

<?php } elseif (is_page('collections-test-2')) { ?>

<!-- collections container -->
<div id="collections" class="thumb-container">
	<?php $my_query = new WP_Query('page_id=103&showposts=1'); ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<?php $images = get_field('collection_test_gallery'); if( $images ): ?>
	<?php foreach( $images as $image ): ?>
	<div class="thumb">
		<img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
	</div>
	<?php endforeach; ?>
	<?php endif; ?>
	<?php endwhile; ?>
</div>
<!-- end collection container -->

<?php } else { ?>
				
<?php } ?>

<?php get_footer(); ?>