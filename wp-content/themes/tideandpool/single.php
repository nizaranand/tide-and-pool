<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Boilerplate
 * @since Boilerplate 1.0
 */

get_header(); ?>

<?php if (in_category('boutiques')) { ?>

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
	<div class="phone"><span class="phone">Phone:</span><?php the_sub_field('phone_no'); ?></div>
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

<?php } elseif (in_category('customer-service')) { ?>

<!-- customer service popup -->
<section id="customer-service">
	<!-- header -->
	<h1>Customer Service</h1>
	<!-- end header -->
	
	<!--left -->
	<div class="left">
		<!-- scroll nav -->
		<nav id="customer-service">
			<ul>
				<?php $my_query = new WP_Query('cat=18&order=asc&showposts=-1&exclude=743'); ?>
				<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
				<li id="menu-<?php the_ID(); ?>"><a href="#entry-<?php the_ID(); ?>"><?php the_title(); ?></a></li>
				<?php endwhile; ?>
			</ul>
		</nav>
		<!-- end scroll nav -->
		
		<!-- sidebar img -->
		<div class="sidebar-img">
			<?php $my_query = new WP_Query('page_id=399&showposts=1'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<?php $image = wp_get_attachment_image_src(get_field('customer_service_popup_-_sidebar_image'), 'customer-service-sidebar'); ?>
			<img src="<?php echo $image[0]; ?>" alt="" />
			<?php endwhile; ?>
		</div>
		<!-- end sidebar img -->
	</div>
	<!-- end left -->
	
	<!-- right -->
	<div class="right">
		<!-- text scroll -->
		<div id="text-scroll" class="scroll-pane">
			<?php $my_query = new WP_Query('cat=18&order=asc&showposts=-1'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<article id="entry-<?php the_ID(); ?>" class="text-panel">
				<!-- header -->
				<h2><?php the_title() ?></h2>
				<!-- end header -->
				
				<?php the_content(); ?>
			</article>
			<?php endwhile; ?>
		</div>
		<!-- end text scroll -->
	</div>
	<!-- end right -->
</section>
<!-- end customer service popup -->

<!-- scroll to script -->
<script type="text/javascript">
	$(document).ready(function(){
		
		// active clas on first nav item
		//$('nav#customer-service ul li:first-child a').addClass('active');
		
		<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
		$('nav#customer-service ul li#menu-<?php the_ID(); ?> a').addClass('active');
		<?php endwhile; // end of the loop. ?>
		
		$('nav#customer-service a').click(function() {
			$("nav#customer-service a.active").removeClass('active');
			$(this).addClass('active');
		});
		
		//stylised scroll pane
		$('#text-scroll').jScrollPane( {
			autoReinitialise: true,
			animateScroll: true,
			showArrows: true,
			verticalDragMinHeight: 110,
			verticalDragMaxHeight: 110
		});
		
		<?php $my_query = new WP_Query('cat=18&showposts=-1'); ?>
		<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		$("#menu-<?php the_ID(); ?> a").click(function() {
			$(".jspContainer").stop(true,true).animate({scrollTop: $("#entry-<?php the_ID(); ?>").offset().top - 230},1500);
		});
		<?php endwhile; ?>
	});
</script>
<!-- end scroll to script -->

<style type="text/css">
	html,body {
		overflow: hidden !important;
	}
</style>

<?php } else { ?>

<?php } ?>

<?php get_footer(); ?>
