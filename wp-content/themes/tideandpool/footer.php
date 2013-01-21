
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
<?php if ( ! in_category('boutiques')) { ?>
</section>
<!-- .content -->
<footer role="contentinfo" class="main-footer clear">
<div class="inner">
	<ul class="social">
		<li class="facebook"><a href="http://www.facebook.com/TideandPool" target="_blank">FACEBOOK</a></li>
		<li class="twitter"><a href="http://http://twitter.com/tideandpool">TWITTER</a></li>
		<li class="pinterest"><a href="http://http://pinterest.com/TideandPool">PNTEREST</a></li>
		<li class="tumblr"><a href="#" target="blank">TUMBLR</a></li>
	</ul>
	
	<?php wp_nav_menu( array( 'menu' => 'Footer Menu' )); ?>
	
	
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
<?php }?>
<?php
/* Always have wp_footer() just before the closing </body>
* tag of your theme, or you will break many plugins, which
* generally use this hook to reference JavaScript files.
*/
wp_footer();
?>

<!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
<script>
var _gaq=[['_setAccount','UA-XXXXX-X'],['_trackPageview']];
(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
g.src=('https:'==location.protocol?'//ssl':'//www')+'.google-analytics.com/ga.js';
s.parentNode.insertBefore(g,s)}(document,'script'));
</script>
        
</body>
</html>