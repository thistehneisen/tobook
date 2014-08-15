<?php 
/* this page will call when an external app has been included in the template
 * we can use this page to recreate the exernal apps in the page
 * 
 * 
 */
$element = $_POST['newitem'];
include "../includes/config.php";
$itemid = time();
if($element == 'externalapp_twitlink') {   //TODO:deprecated, can remove later
?>
		<div id="exterbox_twitlink_<?php echo $itemid;?>" class="dragbox">
			<h4>Twitter Link</h4> 
			<div id="editablebox_<?php echo $itemid;?>" data-param="exterbox_twitlink_<?php echo $itemid;?>" class="editablebox">
				 Add your twitter link
			</div>
		</div>
				
<?php 
}
else if($element == 'externalapp_navmenu'){
	?>exterbox_navmenu_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_navmenu_<?php echo $itemid;?>" >
						<h4 id="headexterbox_navmenu_<?php echo $itemid;?>"><a href="#" class="s_share">Menu</a></h4> 
					</div>
	<?php 
}

else if($element == 'externalapp_socialshare'){
	?>exterbox_socialshare_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_socialshare_<?php echo $itemid;?>" >
						<h4 id="headexterbox_socialshare_<?php echo $itemid;?>"><a href="#" class="s_share">Social share</a></h4>
					</div>
	<?php 
}

else if($element == 'externalapp_form'){
	?>exterbox_form_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_form_<?php echo $itemid;?>" >
						<h4 id="headexterbox_form_<?php echo $itemid;?>"><a href="#" class="formicon">Contact Form</a></h4> 
							</div>
<?php }
else if($element == 'externalapp_htmlbox'){
	?>exterbox_htmlbox_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_htmlbox_<?php echo $itemid;?>" >
						<h4 id="headexterbox_htmlbox_<?php echo $itemid;?>"><a href="#" class="html">HTML</a></h4>
							</div>
<?php 
}
else if($element == 'externalapp_slider'){
	?>exterbox_slider_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_slider_<?php echo $itemid;?>" >
						<h4 id="headexterbox_slider_<?php echo $itemid;?>"><a href="#" class="slider">Slider</a></h4>
							</div>
<?php 
}else if($element == 'externalapp_googleadsense'){
	?>exterbox_googleadsense_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_googleadsense_<?php echo $itemid;?>" >
						<h4 id="headexterbox_googleadsense_<?php echo $itemid;?>"><a href="#" class="adsense">Google Adsense</a></h4>
							</div>
<?php }
else if($element == 'externalapp_dynamicform'){
	?>exterbox_dynamicform_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_dynamicform_<?php echo $itemid;?>" >
						<h4 id="headexterbox_dynamicform_<?php echo $itemid;?>"><a href="#" class="html">Form</a></h4>
							</div>
<?php 
}
/*
else if($element == 'externalapp_guestbook'){
	?>exterbox_guestbook_<?php echo $itemid;?>~<div class="dragbox" id="exterbox_guestbook_<?php echo $itemid;?>" >
		<h4 id="headexterbox_guestbook_<?php echo $itemid;?>"><a href="#" class="guestbook">Guest Book</a></h4>
	</div>
	<?php 
}
*/

?>