<?php

/*

	Template Name: Popup

*/

?>
<html <?php language_attributes(); ?> class="no-js" style="oveflow:hidden !important;">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<meta name="viewport" content="initial-scale=1, width=device-width, user-scalable=no, maximum-scale=1.0">
<title><?php
/*
* Print the <title> tag based on what is being viewed.
* We filter the output of wp_title() a bit -- see
* boilerplate_filter_wp_title() in functions.php.
*/
wp_title( '|', true, 'right' );
?></title>
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="stylesheet" href="<?php bloginfo( 'template_url' ); ?>/responsive.css" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/jquery.min-1.8.3.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/main.js"></script>
<style>
	html {
		overflow: hidden !important;
	}
</style>
</head>
<body style="overflow:hidden !important;">
<?php if (is_page('swim-club-signup')) { ?>

<!-- swim club signup popup  -->
<article id="swimclub" class="popup">
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
</article>
<!-- end swim club signup popup -->

<?php } elseif (is_page('swim-club-thank-you')) { ?>

<!-- swim club thank you popup  -->
<article id="swimclub" class="popup">
	<!-- content -->
	<div class="content">
		<?php $my_query = new WP_Query('page_id=597&showposts=1'); ?>
		<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		<!-- left -->
		<div class="left">
			<?php if(get_field('swim_club_thankyou')): ?>
			<?php while(the_repeater_field('swim_club_thankyou')): ?>
			<!-- logo -->
			<a class="logo-swimclub" href="<?php echo home_url( '/' ); ?>">
				<?php $image = wp_get_attachment_image_src(get_sub_field('swimclub_logo_thankyou'), 'swimclub-logo'); ?>
				<img src="<?php echo $image[0]; ?>" alt="" />	
			</a>
			<!-- end logo -->
					
			<!-- text -->
			<div class="body-text">
				<?php $image = wp_get_attachment_image_src(get_sub_field('thankyou_image'), 'swimclub-text'); ?>
				<img src="<?php echo $image[0]; ?>" alt="" />
			</div>
			<!-- end text -->
			<?php endwhile; ?>
			<?php endif; ?>	
		</div>
		<!-- end left -->
			
		<!-- right -->
		<div class="right">
			<?php if(get_field('swim_club_thankyou')): ?>
			<?php while(the_repeater_field('swim_club_thankyou')): ?>
			<?php $image = wp_get_attachment_image_src(get_sub_field('swimclub_image_thankyou'),'swimclub-body'); ?>
			<img src="<?php echo $image[0]; ?>" alt="" />
			<?php endwhile; ?>
			<?php endif; ?>
		</div>
		<!-- end right -->
		<?php endwhile; ?>
	</div>
	<!-- end content -->
</article>
<!-- end swim club thank you popup -->

<?php } else { ?>

<!-- body content -->
<article class="body-content">
	<?php while ( have_posts() ) : the_post(); ?>

	<!-- header -->
	<h1><?php the_title(); ?></h1>
	<!-- end header -->
	
	<?php the_content(); ?>
	
	<?php endwhile; ?>
</article>				
<!-- end body content -->

<?php } ?>
</body>
<html>