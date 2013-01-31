<?php
/**
 * Template Name: Customer Service
 *
 */

get_header(); ?>

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
				<?php $my_query = new WP_Query('cat=18&order=asc&showposts=-1'); ?>
				<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
				<li id="menu-<?php the_ID(); ?>"><a href="#"><?php the_title(); ?></a></li>
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
		<!-- contact details -->
		<article id="contact">
			<!-- header -->
			<h2>Contact</h2>
			<!-- end header -->
			
			<!-- phone number & email -->
			<span class="call-us">CALL US<br>(562) 277-1382</span><span class="email-us">EMAIL US<br><a href="mailto:customerservice@tideandpool.com">customerservice@tideandpool.com</a></span>
			<!-- end phone number & email -->
			
			<div class="clear"></div>
			
			<p>Call us or Email us between 9:30 am - 5:30 pm PST.<br>If we miss you, we’ll get back to  you as soon as possible.</p>	
		</article>
		<!-- end contact details -->
		
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
		$('nav#customer-service ul li:first-child a').addClass('active');
		
		$('nav#customer-service a').click(function() {
			$("nav#customer-service a.active").removeClass('active');
			$(this).addClass('active');
		});
		
		//stylised scroll pane
		$('#text-scroll').jScrollPane( {
			autoReinitialise: true,
			animateScroll: true
		});
		
		<?php $my_query = new WP_Query('cat=18&showposts=-1'); ?>
		<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		$("#menu-<?php the_ID(); ?> a").click(function() {
			$(".jspContainer").animate({scrollTop: $("#entry-<?php the_ID(); ?>").offset().top - 230},1500);
		});
		<?php endwhile; ?>
	});
</script>
<!-- end scroll to script -->

<?php get_footer(); ?>