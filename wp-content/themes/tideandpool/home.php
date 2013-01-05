<?php
/**
 * Template Name: Home
 *
 * Main homepage.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

<!-- home slideshow -->
<div class="slideshow home">
	<?php $my_query = new WP_Query('page_id=21&showposts=1'); ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<?php if(get_field('home_page_slideshow')): ?>
	<?php while(the_repeater_field('home_page_slideshow')): ?>
	<a href="<?php the_sub_field('home_slideshow_link 	'); ?>">
		<?php $image = wp_get_attachment_image_src(get_sub_field('home_slideshow_image'), 'home-slideshow'); ?>
		<img src="<?php echo $image[0]; ?>" />
	</a>
	<?php endwhile; ?>
	<?php endwhile; ?>
</div>
<!-- end home slideshow -->

<?php get_footer(); ?>
