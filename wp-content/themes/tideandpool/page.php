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

<div id="galleria"></div>

<script type="text/javascript">
	$(document).ready(function(){
		
		var data = [
			<?php $my_query = new WP_Query('page_id=21&showposts=1'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
			
			<?php if(get_field('home_page_slideshow')): ?>
			<?php while(the_repeater_field('home_page_slideshow')): ?>
			<?php $image = wp_get_attachment_image_src(get_sub_field('home_slideshow_image'), 'home-slideshow'); ?>
			{ image :'<?php echo $image[0]; ?>', link: '<?php the_sub_field('home_slideshow_link'); ?>', title: '<?php the_title_attribute(); ?> '},
			<?php endwhile; ?>
			<?php endif; ?>
			<?php endwhile; ?>
		];
		
		Galleria.run('#galleria', {
			dataSource: data,
			autoplay: 4000 ,
			initialTransition: 'fade',
			touchTransition: 'fade',
			transitionSpeed: 700,
			transition: 'fade',
			imageTimeout: 1700,
			debug: false,
			showInfo: false,
			preload: 5,
			showCounter: true,
			showInfo: false,
			imageCrop: 'landscape',
			imagePosition: 'center',
			showImagenav: true,
			thumbnails: false,
			swipe:true,
			responsive: true,
			extend: function() {
				this.$('counter').appendTo('#counter');
				var gallery = $(this);
			}
		});
	});
</script>


<?php } elseif (is_page('collections')) { ?>

<!-- collections container -->
<section id="collections" class="thumb-container">
	<?php $my_query = new WP_Query('page_id=4&showposts=1'); ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<?php if(get_field('collections_gallery')): ?>
	<?php while(the_repeater_field('collections_gallery')): ?>
	<!-- thumb -->
	<article class="thumb">
		<a <?php if(! get_sub_field('link')) { ?>class="fancybox"<?php } ?> href="
			<?php if(get_sub_field('link'))
				{
				the_sub_field('link');
				}
				else {
					$image = wp_get_attachment_image_src(get_sub_field('zoom_image'), 'collections-zoom');
					echo $image[0];
				}
			?>
			">
			
			<img src="<?php $image = wp_get_attachment_image_src(get_sub_field('collections_thumbnail'), 'wpse_60890_retina_scaled'); ?><?php echo $image[0]; ?>" data-rollover="<?php $image = wp_get_attachment_image_src(get_sub_field('collections_thumbnail'), 'large'); ?><?php echo $image[0]; ?>"/>
		</a>
	</article>
	<!-- end thumb -->
	<?php endwhile; ?>
	<?php endif; ?>
	<?php endwhile; ?>
</section>
<!-- end collection container -->

<?php } elseif (is_page('about')) { ?>

<!-- about -->
<article id="about" class="body-content">
	<?php $my_query = new WP_Query('page_id=397&showposts=1'); ?>
	<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
	<!-- header -->
	<h1>About Tide & Pool</h1>
	<!-- end header -->
	
	<!-- body content -->
	<div class="body-content" style="background:url('<?php $image = wp_get_attachment_image_src(get_field('about_bg_image'), 'about-img'); ?><?php echo $image[0]; ?>');no-repeat;">
		<!-- text -->
		<?php the_field('about_text'); ?>
		<!-- end text -->
	</div>
	<!-- end body content -->
	
	<!-- instagram link -->
	<a class="instagram" href="http://instagram.com/tideandpool" target="_blank">Follow us @tideandpool Instagram</a>
	<!-- end instagram link -->
	
	<!-- instagram img -->
	<a class="instagram-img" href="http://instagram.com/tideandpool">
		<?php $image = wp_get_attachment_image_src(get_field('instagram_image'), 'imstagram-img'); ?>
		<img src="<?php echo $image[0]; ?>" alt="" />
	</a>
	<!-- end instagram img -->
	<?php endwhile; ?>
</article>
<!-- end about -->

<?php } elseif (is_page('boutiques')) { ?>

<!-- boutiques -->
<article id="boutiques" class="body-content">
	<!-- header -->
	<h1>Boutiques</h1>
	<!-- end header -->
	
	<!-- left -->
	<div class="left">
		<!-- boutiques scroll -->
		<div id="boutiques-scroll" class="scroll-pane">
			<ol id="boutiques">
			<?php $my_query = new WP_Query('cat=1&order=aesc&showposts=-1'); ?>
			<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>

			<!-- panel -->
			<li id="boutique-<?php the_ID(); ?>" class="boutique">
				<!-- city & state -->
				<div class="city-and-state">
					<span class="number"></span>
					<?php if(get_field('boutique_location')): ?>
					<?php while(the_repeater_field('boutique_location')): ?>
					<?php the_sub_field('city'); ?>, <?php the_sub_field('state'); ?>
					<?php endwhile; ?>
					<?php endif; ?>
				</div>
				<!-- end city & state -->
				
				<!-- boutique name -->
				<p><?php the_title() ?></p>
				<!-- end boutique name -->
			</li>
			<!-- end panel -->

			<?php endwhile; ?>
			</ol>
		</div>
		<!-- end boutiques scroll -->	
	</div>
	<!-- end left -->
	
	<!-- right -->
	<div class="right">
		<!-- boutique view -->
		<div class="boutique-view">
			<!-- content loaded via AJAX -->
		</div>
		<!-- end boutique view -->	
	</div>
	<!-- end right -->
</article>
<!-- end boutiques -->

<!-- AJAX load script -->
<script type="text/javascript">
	$(document).ready(function(){
		
		//stylised scroll pane
		$('#boutiques-scroll').jScrollPane({
			autoReinitialise: true,
			animateScroll: true,
			showArrows: true,
			verticalDragMinHeight: 110,
			verticalDragMaxHeight: 110
		});
		
		// add sequential numbers to each list item
		$("ol#boutiques li .city-and-state").each(function (i) {
			i = i+1;
			if(i < 10) i = "0"+i;
			$(this).prepend('<span class="number">' +i+ '.</span>');
		});
		
		// add/remove active class on list item click
		$("ol#boutiques li:first-child").addClass('active');
		$('ol#boutiques li').click(function() {
			$("li.active").removeClass('active');
			$(this).addClass('active');
		});
   
		// AJAX load event
		<?php $my_query = new WP_Query('cat=1&showposts=1&order=desc'); ?>
		<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		$('.boutique-view').delay(500).fadeIn(1000);
		$('.boutique-view').load('<?php the_permalink(); ?>');
		<?php endwhile; ?>
		
		<?php $my_query = new WP_Query('cat=1&showposts=-1'); ?>
		<?php while ($my_query->have_posts()) : $my_query->the_post(); ?>
		$('ol#boutiques li#boutique-<?php the_ID(); ?>').click(function() {
			//alert("AJAX click test");
			$('.boutique-view').fadeOut(500);
			$('.boutique-view').load('<?php the_permalink(); ?>');
			$('.boutique-view').delay(500).fadeIn(1000);
		});
		<?php endwhile; ?>
	});
</script>
<!-- end AJAX load script -->

<?php } elseif (is_page('shopping-bag')) { ?>

<!-- body content -->
<article class="body-content">
	<?php while ( have_posts() ) : the_post(); ?>
	
	<?php the_content(); ?>
	
	<?php endwhile; ?>
</article>				
<!-- end body content -->

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

<?php get_footer(); ?>