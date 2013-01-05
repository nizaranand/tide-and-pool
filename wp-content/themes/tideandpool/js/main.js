/*
	Any site-specific scripts you might have.
	Note that <html> innately gets a class of "no-js".
	This is to allow you to react to non-JS users.
	Recommend removing that and adding "js" as one of the first things your script does.
	Note that if you are using Modernizr, it already does this for you. :-)
*/


$(function() {
	// slideshow
    $('.slideshow').royalSlider({
    	autoPlay: { enabled: true, pauseOnHover: true },
    	arrowsNav: true,
    	transitionSpeed: 500,
    	delay:3000,
    	controlNavigation: 'none',
    	arrowsNavAutoHide: true,
    	fadeinLoadedSlide: true,
    	imageScaleMode: 'fill',
    	imageAlignCenter:true,
    	imageScalePadding: 0,
    	loop: false,
    	loopRewind: false,
    	slidesOrientation: 'horizontal',
    	slidesSpacing: 0,
    	sliderDrag: true,
    	keyboardNavEnabled: true,
    	autoScaleSlider: true
    });
    // fade in team content
/*
    $(".details-btn").hover(
    	function () {
	    	$('.free-shipping-flyaway').fadeIn(500);
	    },
	    function () {
		    $('.free-shipping-flyaway').delay(3000).fadeOut(300);
		});
*/
		
		$(".details-btn").mouseover(function() {
			$('.free-shipping-flyaway').fadeIn(500);
		});
		$(".details-btn").mouseleave(function() {
			$('.free-shipping-flyaway').delay(1000).fadeOut(500);
		});

//for mobiles, scroll top hide the address bar
function hideBar() {  

(function( win ){
	var doc = win.document;
	
	// If there's a hash, or addEventListener is undefined, stop here
	if( !location.hash && win.addEventListener ){
		
		//scroll to 1
		window.scrollTo( 0, 1 );
		var scrollTop = 1,
			getScrollTop = function(){
				return win.pageYOffset || doc.compatMode === "CSS1Compat" && doc.documentElement.scrollTop || doc.body.scrollTop || 0;
			},
		
			//reset to 0 on bodyready, if needed
			bodycheck = setInterval(function(){
				if( doc.body ){
					clearInterval( bodycheck );
					scrollTop = getScrollTop();
					win.scrollTo( 0, scrollTop === 1 ? 0 : 1 );
				}	
			}, 15 );
		
		win.addEventListener( "load", function(){
			setTimeout(function(){
				//at load, if user hasn't scrolled more than 20 or so...
				if( getScrollTop() < 20 ){
					//reset to hide addr bar at onload
					win.scrollTo( 0, scrollTop === 1 ? 0 : 1 );
				}
			}, 0);
		} );
	}
})( this );
}

hideBar();
	
	
	$("#toggle-nav").click(function() {
	
		//hide the address bar id needed
		hideBar();
		
		//toggle nav down / or up
		$('.main-nav').slideToggle().toggleClass('active');
	
	 });
	 
	 $(window).resize(function() {
	/*
 	var winWidth = $(window).width();
	 	
	 	if( winWidth > 600 && $('.main-nav').hasClass('active')) {
		 	$('.main-nav').toggleClass('active');
		 	$('.main-nav').css("display", "block");
	 	}
	 	if( winWidth <= 600 && $('.main-nav').hasClass('active')) {
		 	$('.main-nav').toggleClass('active');
		 	$('.main-nav').css("display", "none");
	 	}
*/
	 	
	 
	  });

});