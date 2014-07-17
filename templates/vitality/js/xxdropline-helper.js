(function($){ // Mootools Safe Mode ON	
	window.addEvent('domready',function(){
		$$('.dj-main').each(function(djmenu){
			djmenu.addEvents({'mouseenter':function(){
				var djsub = djmenu.getElement('li.dj-up.active ul.dj-submenu');
				if(djsub) djsub.addClass('hideSub');
			},'mouseleave':function(){
				var djsub = djmenu.getElement('li.dj-up.active ul.dj-submenu');
				if(djsub) djsub.removeClass('hideSub');
			}});
		});	
	});	
})(document.id);