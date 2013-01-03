<?php
/**
* The Header for our theme.
*
* Displays all of the <head> section and everything up till <div id="main">
*
* @package WordPress
* @subpackage Boilerplate
* @since Boilerplate 1.0
*/
?><!DOCTYPE html>
<!--[if lt IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie6 lte7 lte8 lte9"><![endif]-->
<!--[if IE 7 ]><html <?php language_attributes(); ?> class="no-js ie ie7 lte7 lte8 lte9"><![endif]-->
<!--[if IE 8 ]><html <?php language_attributes(); ?> class="no-js ie ie8 lte8 lte9"><![endif]-->
<!--[if IE 9 ]><html <?php language_attributes(); ?> class="no-js ie ie9 lte9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!--><html <?php language_attributes(); ?> class="no-js"><!--<![endif]-->
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
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php
/* We add some JavaScript to pages with the comment form
* to support sites with threaded comments (when in use).
*/
if ( is_singular() && get_option( 'thread_comments' ) )
wp_enqueue_script( 'comment-reply' );

/* Always have wp_head() just before the closing </head>
* tag of your theme, or you will break many plugins, which
* generally use this hook to add elements to <head> such
* as styles, scripts, and meta tags.
*/
wp_head();
?>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8/jquery.min.js"></script>

<!-- plugin files for dev -->
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/royalslider/jquery.royalslider.min.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/galleria-1.2.7/galleria-1.2.7.min.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/galleria-1.2.7/themes/classic/galleria.classic.min.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/vendor/jquery.masonry.min.js"></script>

<!-- main site files for production -->
<script src="<?php bloginfo( 'template_url' ); ?>/js/plugins.js"></script>
<script src="<?php bloginfo( 'template_url' ); ?>/js/main.js"></script>

</head>
<body <?php body_class(); ?>>
hello ben

<header role="banner" class="main-header">
	<div class="inner">
	
	<nav class="user-utility">
	<?php wp_nav_menu( array( 'menu' => 'User Utility' )); ?>
	</nav>
	
	<h1 class="logo"><a href="<?php echo home_url( '/' ); ?>" title="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
	<h2 class="tag-line"><?php bloginfo( 'description' ); ?></h2>
	

	
	<div class="clear"></div>
	
	</div><!-- .inner -->
	
	
	
		<nav id="access" role="navigation" class="main-nav clear">
		<div class="inner">
	<?php /* Our navigation menu.  If one isn't filled out, wp_nav_menu falls back to wp_page_menu.  The menu assiged to the primary position is the one used.  If none is assigned, the menu with the lowest ID is used.  */ ?>
	<?php wp_nav_menu( array( 'container_class' => 'menu-header', 'theme_location' => 'primary' ) ); ?>
			<div class="clear"></div>
		</div>
	</nav><!-- #access -->
	
	<div id="toggle-nav">&#9776;</div>
	

</header>



<section class="content" role="main">
<div class="inner">

<div class="announcement">
	<p>Free shippign on all order... </p>
</div>
