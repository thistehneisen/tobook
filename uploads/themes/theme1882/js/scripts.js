jQuery(document).ready(function() {
	jQuery('ul li:last-child').addClass('lastItem');
	jQuery('ul li:first-child').addClass('firstItem');

	// Tips
	jQuery('*[rel=tooltip], .hasTooltip').tooltip()
	jQuery('*[rel=popover]').popover()
	jQuery('.tip-bottom').tooltip({placement: "bottom"})

	// Modal Window
	jQuery('[href="#modal"]').click(function(e){
		jQuery('#modal').modal('toggle');
		e.preventDefault();
	});

	jQuery('#modal button.modalClose').click(function(e){
		jQuery('#modal').modal('hide');
		e.preventDefault();
	});

	// Initialize the gallery touch
	/*jQuery('a.touchGalleryLink').touchTouch();*/

	jQuery('ul#isotopeContainer.hover_true').magnificPopup({
		delegate: 'a.galleryZoom',
		type: 'image',
		tLoading: 'Loading #%curr%...',
		mainClass: 'mfp-with-zoom',
		gallery: {
			enabled: false,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
		}
	});

	jQuery('ul#isotopeContainer.hover_false').magnificPopup({
		delegate: 'a.galleryZoomIcon',
		type: 'image',
		tLoading: 'Loading #%curr%...',
		mainClass: 'mfp-with-zoom',
		gallery: {
			enabled: false,
			navigateByImgClick: true,
			preload: [0,1] // Will preload 0 - before current, and 1 after the current image
		},
		image: {
			tError: '<a href="%url%">The image #%curr%</a> could not be loaded.'
		}
	});

	jQuery('.articleGalleryZoom').magnificPopup({
		type: 'image',
		mainClass: 'mfp-with-zoom'
	});

	//Dropdown icons
	jQuery('.dropdown-toggle').dropdown()
	var iconTest=/icon-/i;
	var iconReplace = "fa fa-";
	function iconSet(){
		jQuery('[class*="icon-"]').each(function(){
			iconClass = jQuery(this).attr('class');
			var newString = iconClass.replace(iconTest, iconReplace);
			jQuery(this).addClass(newString);
		})
	}
	iconSet()


	//Gallery Hover Animation
	jQuery('a.zoom').hover(function(){
		jQuery(this).addClass('active');
		jQuery(this).find('span.zoom-bg').stop(true, true).animate({opacity: 0.7}, 200);
		jQuery(this).find('span.zoom-icon').stop(true, true).animate({top:'50%'}, 200);
	},function(){
		jQuery(this).removeClass('active');
		jQuery(this).find('span.zoom-bg').stop(true, true).animate({opacity: 0}, 200);
		jQuery(this).find('span.zoom-icon').stop(true, true).animate({top:'-50%'}, 200);
	});

	// hide #back-top first
	jQuery("#back-top").hide();
	
	// fade in #back-top
	jQuery(function () {
		jQuery(window).scroll(function () {
			if (jQuery(this).scrollTop() > 100) {
				jQuery('#back-top').fadeIn();
			} else {
				jQuery('#back-top').fadeOut();
			}
		});

		// scroll body to 0px on click
		jQuery('#back-top a').click(function () {
			jQuery('body,html').animate({
				scrollTop: 0
			}, 400);
			return false;
		});
	});


	// IPad/IPhone
	  var viewportmeta = document.querySelector && document.querySelector('meta[name="viewport"]'),
	    ua = navigator.userAgent,
	 
	    gestureStart = function () {
	        viewportmeta.content = "width=device-width, minimum-scale=0.25, maximum-scale=1.6, initial-scale=1.0";
	    },
	 
	    scaleFix = function () {
	      if (viewportmeta && /iPhone|iPad/.test(ua) && !/Opera Mini/.test(ua)) {
	        viewportmeta.content = "width=device-width, minimum-scale=1.0, maximum-scale=1.0";
	        document.addEventListener("gesturestart", gestureStart, false);
	      }
	    };
	//scaleFix();

	/*Pagination Active Button*/
	jQuery('div.pagination ul li:not([class])').addClass('num');

	jQuery('.modal.loginPopup').on('shown', function (event) {
		if(jQuery(this).outerHeight()>=jQuery(window).height()){
			if((jQuery(window).scrollTop()+jQuery(this).outerHeight())>=jQuery(document).height()){
				jQuery(this).css({marginTop:jQuery(document).height()-jQuery(this).outerHeight()})
			}
			else{
				jQuery(this).css({marginTop:jQuery(window).scrollTop()})
			}
		}
		else{
			jQuery(this).css({marginTop:jQuery(window).scrollTop()-jQuery(this).outerHeight()/2+jQuery(window).height()/2})
		}
	})
	
	jQuery('.sf-menu > li > a').each(function(num){
		jQuery(this).parent().addClass('link'+(num+1));
	})
	
	jQuery('.social a').prepend('<span></span>');
	
	jQuery('.mod-newsflash-adv').each(function(){
		var th=jQuery(this).find('.row');
		th.eq(th.length-1).addClass('lastRow');
	})
	
});