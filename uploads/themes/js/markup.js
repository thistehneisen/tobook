$(document).ready(function(){	// detects the current active menu item 	var current = $('.current a').attr('rel');		$('ul#menu').lavaLamp({		startItem: current	});		// submenu //////////////////////////////	/////////////////////////////////////////	$('#menu li').hover(		function () {			//show its submenu			$('ul', this).slideDown(200);		}, 		function () {			//hide its submenu			$('ul', this).slideUp(100);					}	);	/////////////////////////////////////////	// submenu //////////////////////////////		$("#slider").easySlider({		auto: true,		continuous: true,		numeric: true,		speed: 2000	});		Cufon.replace('h1');	Cufon.replace('h2');	Cufon.replace('h3');		$('.footerlogo').click(function(){   		 $('html, body').animate({scrollTop:0}, 'slow');   		 return false;	});		$('input[title!=""]').hint();		$('.colorbox').colorbox({		opacity: '0.6'	});		// contact form handling		$('.submit').click(function(){		var name = $('#name').val();		var email = $('#email').val();		var phone = $('#phonenumber').val();		var message = $('#message').val();		var valid = true;				if (name == '' || name == $('#name').attr('title')) {			$('.name-error').show();			valid = false;		}				if (email == '' || email == $('#email').attr('title')) {			$('.email-error').show();			valid = false;		}				if (message == '' || message == $('#message').attr('title')) {			$('.message-error').show();			valid = false;		}				// send data with ajax				var formData = 'name='+ name + '&email=' + email + '&phone=' + phone + '&message=' + message;				if (valid == true) {					$.ajax({				type : 'POST',				url : 'lib/send.php',				data : formData,				success : function () {					$('.error').hide();					$('#name').val('');					$('#email').val('');					$('#phonenumber').val('');					$('#message').val('');					$('.thx').show();				}			});		} // valid						return false;		});			});