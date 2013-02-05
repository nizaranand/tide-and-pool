<?php

/*

	Template Name: Swim CLub - Thank you

*/

get_header(); ?>

<!-- overlay -->
<div class="overlay"></div>
<!-- end oevrlay -->

<!-- swim club popup  -->
<div id="swimclub-popup" class="popup-content-wrapper hide">
		<!-- content -->
		<div class="content">
			<?php $my_query = new WP_Query('page_id=383&showposts=1'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<!-- left -->
			<div class="left">
				<?php if(get_field('swim_club')): ?>
				<?php while(the_repeater_field('swim_club')): ?>
				<!-- logo -->
				<a class="logo-swimclub" href="<?php echo home_url( '/' ); ?>">
					<?php $image = wp_get_attachment_image_src(get_sub_field('swimclub_logo'), 'swimclub-logo'); ?>
					<img src="<?php echo $image[0]; ?>" alt="" />	
				</a>
				<!-- end logo -->
					
				<!-- text -->
				<?php the_sub_field('swimclub_text'); ?>
				<!-- end text -->
				<?php endwhile; ?>
				<?php endif; ?>
					
				<!-- signup form -->
				<div class="signup-form"><?php the_field('swimclub_signup_code'); ?></div>
				<!-- end signup form -->
			</div>
			<!-- end left -->
			
			<!-- right -->
			<div class="right">
				<?php if(get_field('swim_club')): ?>
				<?php while(the_repeater_field('swim_club')): ?>
				<?php $image = wp_get_attachment_image_src(get_sub_field('swimclub_image'), 'swimclub'); ?>
				<img src="<?php echo $image[0]; ?>" alt="" />
				<?php endwhile; ?>
				<?php endif; ?>
			</div>
			<!-- end right -->
			<?php endwhile; ?>
		</div>
		<!-- end content -->
</div>
<!-- end swim club popup -->

<?php get_footer(); ?>