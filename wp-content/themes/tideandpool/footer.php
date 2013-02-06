
<?php
/**
* The template for displaying the footer.
*
* Contains the closing of the id=main div and all content
* after.  Calls sidebar-footer.php for bottom widgets.
*
* @package WordPress
* @subpackage Boilerplate
* @since Boilerplate 1.0
*/
?>
</section>
<!-- .content -->
<?php if ( ! is_page('customer-service') && ! in_category('customer-service') && ! is_page('terms-and-privacy') )  { ?>
<footer role="contentinfo" class="main-footer clear">
<div class="inner">
	<ul class="social">
		<li class="facebook"><a href="http://www.facebook.com/TideandPool" target="_blank">FACEBOOK</a></li>
		<li class="twitter"><a href="http://twitter.com/tideandpool" target="_blank">TWITTER</a></li>
		<li class="pinterest"><a href="http://pinterest.com/TideandPool" target="_blank">PINTEREST</a></li>
		<li class="instagram"><a href="http://instagram.com/tideandpool" target="blank">INSTAGRAM</a></li>
	</ul>
	
	<?php wp_nav_menu( array( 'menu' => 'Footer Menu' )); ?>
	<!--
<div class="menu-footer-menu-container">
		<ul id="menu-footer-menu">
			<?php $my_query = new WP_Query('cat=18&order=asc&showposts=-1'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			<li id="menu-<?php the_ID(); ?>"><a class="fancybox-iframe" href="<?php echo home_url( '/' ); ?>customer-service/#<?php the_ID(); ?>"><?php the_title(); ?></a></li>
			<?php endwhile; ?>
		</ul>
	</div>
-->
	
	
<!--
	<ul class="">
		<li><a href="">CONTACT US</a></li>
		<li><a href="">ORDERING</a></li>
		<li><a href="">SHIPPING</a></li>
		<li><a href="">RETURNS</a></li>
		<li><a href="">FAQ</a></li>
		<li><a href="">TERMS &amp; PRIVACY</a></li>
	</ul>
-->
	
	<div class="signup">
		<!-- email signup btn -->
		<div class="email-signup">
			Email Signup
			<!-- form -->
			<form class="hide">
				<input type="email" class="italic" placeholder="Your Email Address...">
				<!-- <input  type="submit"> -->
			<!-- end form -->
			</form>
		</div>
		<!-- end email signup btn -->
	</div>
	
		<address>&copy; <?php the_time('Y'); ?> <?php bloginfo( 'title' ); ?></address>


</div>
</footer><!-- footer -->

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
<?php
/* Always have wp_footer() just before the closing </body>
* tag of your theme, or you will break many plugins, which
* generally use this hook to reference JavaScript files.
*/
wp_footer();
?>
<?php } ?>

<style type="text/css">
	html {
		margin-top:0 !important;
	}
</style>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
        
</body>
</html>