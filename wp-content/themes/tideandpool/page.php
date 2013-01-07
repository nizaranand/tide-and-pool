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
<?php } elseif (is_page('collection')) { ?>

<?php } else { ?>
				
<?php } ?>

<?php get_footer(); ?>