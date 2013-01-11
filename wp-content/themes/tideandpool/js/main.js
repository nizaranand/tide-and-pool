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
    
// collections masonry

$("#collections").append('<div id=loading></div>');

var $container = $('#collections');

$container.imagesLoaded(function() {
	
	$("#loading").remove();

	var lis = $('.thumb'); //tis is the image item 
			
			var i = 0; //set counter to 0
			
			//loops thru itself
			(function displayImages() {
				lis.eq(i++).fadeIn(150, displayImages);
					//adjust line height for view more

			})();
			
	$container.masonry({
		itemSelector: '.thumb',
		isAnimated: true,
		isFitWidth: false,
		columnWidth:  1
	});
	 
	$(window).resize(function() {
		$(".thumb").each(function() {
			var theHeight = Math.floor($(this).height() / 2 - 16);
		 });
	});
      
    // hover z-index class
    $(".thumb").hover(function() { 
    	$(this).addClass('img-hover');
    }, 
    function() {
		$(this).removeClass('img-hover'); 
    });

    // image hover grow
    $(".thumb img").hover(function() {
      
	  var $this = $(this);
    $this.stop(true,true).animate({
        //'height': $this.height() * 1.2,
        'width' : $this.width() * 1.5
    });
    },function() {
       var $this = $(this);
       $this.stop(true,true).animate({
        //'height': $this.height() / 1.2,
        'width' : $this.width() / 1.5
    });
});


});
    // free shipping fly away fade in/out
	$(".details-btn").hover(function() {
		$('.free-shipping-flyaway').stop(true,true).fadeIn(500);
	},
	function() {
		$('.free-shipping-flyaway').stop(true,true).delay(2000).fadeOut(500);
	});
	
	// email signup form fade in/out
	$(".email-signup").hover(function() {
		$('.email-signup form').stop(true,true).fadeIn(500);
	},
	function() {
		$('.email-signup form').stop(true,true).delay(1000).fadeOut(500);
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