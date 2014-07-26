//  ========== 
//  = Custom JS and jQuery = 
//  ========== 

$(document).ready(function() {
						   
						   
						     $('.bxslider').bxSlider();
    
    //  ========== 
    //  = Tweet loader = 
    //  ========== 
    if ($(".tweet-container").length > 0) {
        $(".tweet-container").tweet({
            username: "envato",
            join_text: "auto",
            count: 3,
            auto_join_text_default: "we said,",
            auto_join_text_ed: "we",
            auto_join_text_ing: "we were",
            auto_join_text_reply: "we replied to",
            auto_join_text_url: "we were checking out",
            loading_text: "loading tweets...",
            template: '{avatar} <strong>@{user}</strong> {text} {time} <span class="bolded-line"></span>'
        });
    }
    
    
    //  ========== 
    //  = Smooth scroll to the top of the page = 
    //  ========== 
    $("#to-the-top").click(function(e) {
        e.preventDefault();
        $("html, body").animate({ 'scrollTop' : 0 }, 2000);
    });
    
    
    //  ========== 
    //  = Carousel = 
    //  ==========
    
	$(".carousel .slide").each(function(index, elm) {
		var $this = $(this);
		$this.css({
			width : $this.parent().parent().width()
		})
	});
    
    $(window).load(function() {
    	$(".carousel").each(function(index, elm) {
	        $(elm).carouFredSel({
	            auto : {
	                play : false,
	            },
	            prev : {
	                button : $(elm).parent().find(".nav-left"),
	            },
	            next : {
	                button : $(elm).parent().find(".nav-right"),
	            }
	        });
	    });
    });
	    
    
    //  ========== 
    //  = Revolution Slider = 
    //  ========== 
    if ($(window).width() > 767) {
    	var $mainSlider = $(".slides").revolution({    
	        delay:6000,                                                
	        
	        
	        navigationType:"none",                  
	        navigationArrows:"none",        
	        touchenabled:"on",                      
	        onHoverStop:"off",                        
	        
	        navOffsetHorizontal:0,
	        navOffsetVertical:20,
	        
	        hideCaptionAtLimit:0,
	        hideAllCaptionAtLilmit:0,
	        hideSliderAtLimit:0,
	        
	        stopAtSlide:-1,
	        stopAfterLoops:-1,
	        
	        shadow:1,
	        fullWidth:"on"    
	    });
	    
	    $("#slider-nav-left").click(function() {
	        $mainSlider.revprev();
	    });
	    $("#slider-nav-right").click(function() {
	        $mainSlider.revnext();
	    });
	    
	    $mainSlider.bind("revolution.slide.onchange", function(e, data) {
	        var slideIndex = data.slideIndex;
	        var customCaption = $(".fullwidthbanner ul li:nth-child(" + slideIndex + ") .custom-cap").text();
	        $("#custom-caption-container").html(customCaption);
	        
	    });
    } else {
    	$(".fullwidthbanner-container").css({
    		"backgroundImage" : 'url(' + $(".fullwidthbanner-container").find("li:first-child img").attr("src") + ')'
    	});
    	$(".fullwidthbanner-container .fullwidthbanner").hide();
    }
	    
    
    
    //  ========== 
    //  = Collapse / accordion = 
    //  ========== 
    
    $(".accordion-body").parent().find(".accordion-heading a").click(function(e) {
    	e.preventDefault();
    	var target = $(this).attr("href");
    	$(target).slideToggle();
    	$(this).parent().toggleClass("open");
    });
    
    
});
