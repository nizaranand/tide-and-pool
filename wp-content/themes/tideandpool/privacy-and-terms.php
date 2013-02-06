<?php

/*

	Template Name: Terms & Privacy

*/

get_header(); ?>

<!-- customer service popup -->
<section id="terms-and-privacy">
	<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
	<!-- header -->
	<h1>Privacy Policy</h1>
	<!-- end header -->
	
	<!-- text scroll -->
	<div id="privacy-text-scroll" class="scroll-pane">
		<?php the_content(); ?>
	</div>
	<!-- end text scroll -->
	<?php endwhile; ?>
</section>

<script type="text/javascript">
	$(document).ready(function(){
		
		//stylised scroll pane
		$('#privacy-text-scroll').jScrollPane( {
			autoReinitialise: true,
			animateScroll: true,
			showArrows: true,
			verticalDragMinHeight: 110,
			verticalDragMaxHeight: 110
		});
	});
</script>

<?php get_footer(); ?>