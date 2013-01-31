/*
	Any site-specific scripts you might have.
	Note that <html> innately gets a class of "no-js".
	This is to allow you to react to non-JS users.
	Recommend removing that and adding "js" as one of the first things your script does.
	Note that if you are using Modernizr, it already does this for you. :-)
*/


$(function() {

	// fixed nav on scroll
	$(window).scroll(function (e) { 
					
		if ( $(this).scrollTop() > 117 && !$('body').hasClass('fixed')) { 
			$('body').addClass('fixed');
		} else if ( $(this).scrollTop() <= 117 && $('body').hasClass('fixed')) {
			$('body').removeClass('fixed');
		}
		
	});
	
        
	// magic zoom active thumb class
	$(".thumbnails article a:first").addClass("first-active");
	$('.thumbnails article a').click(function() {
		$('.thumbnails article a:first').removeClass("first-active");
	});
	
	
	/*
$(".shopping-bag").hover(function(){
		$('.dropdown').stop(true,true).animate({
			height:"toggle",
		    opacity:"toggle"
		 },300);
	});
*/
	/*
$(".shopping-bag").hover(function() {
		$('.dropdown').stop(true,true).slideDown(500);
		},
		function() {
		$('.dropdown').stop(true,true).delay(2000).slideUp(500);
	});
*/
	
	$('.shopping-bag').hover(function() {
		    $('.dropdown').animate({'height':"toggle", 'opacity':1}, 300);
		    return false;
		    e.preventDefault();
		  }, function() {
		    $('.dropdown').delay(2000).animate({'height':"toggle", 'opacity':0}, 300);
		    return false;
		    e.preventDefault();
		});
	
	
	//append shop bag pointer
	$('.dropdown').append('<div class="pointer"/></div>');
	
	// products carousel
	$("#carousel").touchCarousel({
         scrollbar: false,
         loopItems: true,
         itemFallbackWidth: 500		
    });
    
    // products carousel details hover
    $(".touchcarousel-item").hover(function() { 
    	$('.product-details',this).stop(true,true).fadeIn(800);
    }, 
    function() {
		$('.product-details',this).stop(true,true).fadeOut(300); 
    });
    
    // product carousel hover image swap
    $("ul.touchcarousel-container li.slide img").hover(function() {
	var initialImage = $(this).attr('src');
	var swapImageUrl = $(this).attr('data-rollover');
	$(this).attr('src',swapImageUrl);
	$(this).attr('data-rollover',initialImage);
	$(this).delay(500).fadeIn(1000);

 	}, function() {
	var initialImage = $(this).attr('data-rollover');
	var swapImageUrl = $(this).attr('src');
	$(this).attr('src',initialImage);
	$(this).attr('data-rollover',swapImageUrl);
 	});
    
    // boutique dragable slideshow
	$('#boutiques-gallery').royalSlider({
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
    	swipe: true,
    	keyboardNavEnabled: true,
    	autoScaleSlider: true
    });
    
    //collections fancybox
    $(".fancybox").fancybox({
		padding:0,
		type:'image',
		autoScale:true
	});
	
	//swimclub fancybox
	$("a.fancybox-inline").fancybox({
		'width':'779px',
		'height': '448px',
		'overlayShow': true,
		'overlayOpacity': 1,
		'autoDimensions' : 'false',
		'autoScale': 'false',
		'padding': 0,
		'scrolling': 'no'
	});
	
	// collections isotope
	/*$('#collections').isotope({
		sortBy: 'original-order',
		itemSelector : '.thumb'
	});*/


    
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
		isFitWidth: true,
		columnWidth:  166
		/* gutterWidth: 2 */
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
        'width' : $this.width() * 1.6
    });
    },function() {
       var $this = $(this);
       $this.stop(true,true).animate({
        'width' : $this.width() / 1.6
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