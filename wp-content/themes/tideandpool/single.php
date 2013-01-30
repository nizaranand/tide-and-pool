<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

<div id="<?php the_ID(); ?>" class="boutique-content">			
<!-- gallery -->
<div id="boutiques-gallery" class="royalSlider rsDefault">
	<?php $images = get_field('boutique_gallery');
	if( $images ): ?>
	<?php foreach( $images as $image ): ?>
	<img src="<?php echo $image['sizes']['large']; ?>" alt="<?php echo $image['alt']; ?>" />
	<?php endforeach; ?>	
	<?php endif; ?>
</div>
<!-- end gallery -->
			
<!-- left -->
<div class="left">
	<!-- location -->
	<div class="location">
		<!-- header -->
		<h2><?php the_title(); ?></h2>
		<!-- end header -->
		
		<!-- address -->
		<div class="address">
			<?php if(get_field('boutique_location')): ?>
			<?php while(the_repeater_field('boutique_location')): ?>
			<?php the_sub_field('street_address'); ?>, <?php the_sub_field('city'); ?>, <?php the_sub_field('state'); ?>, <?php the_sub_field('zip_code'); ?> 
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
		<!-- end address -->
		
		<?php if(get_field('google_map')): ?>
		<!-- get directions -->
		<a class="google" href="http://maps.google.com?q=<?php the_field('google_map'); ?>" target="_blank">Get Directions</a>
		<!-- end get directions -->
		<?php endif; ?>
	</div>
	<!-- end locaiton -->
	
	<?php the_field('description'); ?>			
</div>
<!-- end left -->
			
<!-- right -->
<div class="right">
	<!-- opening hours -->
	<div class="opening-hours"><?php the_field('opening_hours'); ?></div>
	<!-- end opening hours -->
	
	<?php if(get_field('boutique_location')): ?>
	<?php while(the_repeater_field('boutique_location')): ?>			
	<!-- phone -->
	<div class="phone"><?php the_sub_field('phone_no'); ?></div>
	<!-- end phone -->
	
	<!-- website url -->
	<a class="website" href="<?php the_sub_field('website'); ?>"><?php the_sub_field('website'); ?></a>
	<!-- end website url -->
	
	<?php endwhile; ?>
	<?php endif; ?>
</div>
<!-- end right -->
</div>

<?php endwhile; ?>

<?php get_footer(); ?>
