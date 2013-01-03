
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
</section><!-- .content -->
<footer role="contentinfo" class="main-footer clear">
<div class="inner">
	<ul class="social">
		<li><a href="">FACEBOOK</a></li>
		<li><a href="">TWITTER</a></li>
		<li><a href="">PNTEREST</a></li>
		<li><a href="">TUMBLR</a></li>
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
		<input type="email" placeholder="EMAIL SIGN UP">
		<input  type="submit">
	</div>
	
		<address>&copy; <?php the_time('Y'); ?> <?php bloginfo( 'title' ); ?></address>


</div>
</footer><!-- footer -->
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