$(window).load(function () {

	/*Fluid footer*/
	$('#footer-inner-extend').hide();
	$(window).scroll(function(event) {
		//get heights
		//window
		var wh = $(this).height();
		//container
		var ch = $('#main-container').outerHeight();
		//footer 1, shown by default
		var ft1 = $('#footer-inner').outerHeight();
		//footer 2, hidden by default
		var ft2 = $('#footer-inner-extend').outerHeight();
		//sh = shift div
		var sh = ft1 + ft2;
		//lst = last scrollTop
		var lst = ch-wh-sh+ft1;
		//cst = current scrollTop
		var cst = $(this).scrollTop();
		if ((cst) > lst) {
			//scrolldown
			$('#footer-inner-extend').show();
			$('footer').css({position:'relative',height:ft2 + ft1});
		} else {
			// scrollup
			$('#footer-inner-extend').hide();
			$('footer').css({position:'fixed', bottom:'0', height:ft1});
		}
	});
	
});

$(document).ready(function () {

	/*No jumping allowed*/
	$('a[href="#"]').click(function()
	{
	return false;
	});
	
	
	/*Nivo Slider*/
	$('#slider').nivoSlider({
			controlNavThumbs:true,
		    directionNav: false
	});
	
	/*isotope*/
	var $container = $('#isotope');
	// initialize isotope
	$container.isotope({
	  // options
	  itemSelector : 'li'
	});
	
	
	$('#filter a').click(function(){
	  var selector = $(this).data('category');
	  $container.isotope({ filter: selector });
	  return false;
	});
		
	
	/*shadowbox*/
	Shadowbox.init({skipSetup:true});
	Shadowbox.setup("#isotope a", {
		gallery: "beu - Gallery"
	});
	

});


/*contact form*/

function sendMail()
    {
        if ( window.XMLHttpRequest )
            xmlhttp = new XMLHttpRequest();
        else
        xmlhttp = new ActiveXObject( "Microsoft.XMLHTTP" );

        xmlhttp.onreadystatechange = function()
        {
            if ( xmlhttp.readyState === 4 && xmlhttp.status === 200 )
                document.getElementById( "contact-form" ).innerHTML = xmlhttp.responseText;
    }

        xmlhttp.open( "get", "send.php?name=" + document.form.name.value + "&email=" + document.form.email.value + "&message=" + document.form.message.value, true );
        xmlhttp.send();
    }
