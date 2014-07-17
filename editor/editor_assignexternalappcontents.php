<?php 
 /*
  * page to load the contents to the external app boxes after drag drop
  * 
  */
$newitem 	= $_POST['newitem'];
$dragitem 	= $_POST['dragitem'];
include "../includes/session.php";
include "../includes/config.php";
include "../includes/cls_htmlform.php";
 //echo "<pre>";
 //print_r($_POST);
$dragArr = explode('_', $dragitem);
$itemVal =  $dragArr[2];
if($newitem != '' && $dragitem != '') {
	if($newitem == 'externalapp_form'){ // html widget for forms
		$widgetContent 	= 'A sample contact form, click edit to update the form fields';
		//$widgetTitle	= 'Contact Form';
		$currentPage 	= $_SESSION['siteDetails']['currentpage'];
		// update the default contact form
		global $arrDefaultFormFields;
		$arrFormItem 				= array();
 		$arrFormItem['email'] 		= ''; 	
		foreach($arrDefaultFormFields as $fields)
			$arrFormItem['items'][$fields] 		= $fields;	
		$_SESSION['siteDetails'][$currentPage]['apps'][$dragitem] = $arrFormItem;
		
		
		// generate the basic form structure
		$objForm 		= new Htmlform();
        if(sizeof($arrFormItem) > 0) {
	    	global $formFeedBackItems;
	       	$feedBackItems =  $arrFormItem['items'];
	        foreach($feedBackItems as $key=>$items) {
		        $itemDet 		= $formFeedBackItems[$items];
		        $objFormElement				= new Formelement();
		        $objFormElement->type		= $itemDet['field'];
		        $objFormElement->name       = $items;
		        $objFormElement->id         = $items;
		        $objFormElement->label      = $itemDet['title'];
		        $objForm->addElement($objFormElement);
			}
        }
		$formOutPut		= $objForm->renderForm();
        $widgetContent 	= $formOutPut;
		
		$editLink 		= $widgetTitle.'<span class="configure"><span title="Click to edit the widget"><a href="#" onclick="loadFormEditor(\'editablebox_'.$itemVal.'\',1,\''.$dragitem.'\')" title="Edit widget" class="iframe panel_edit"> </a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> </a></span></span>';
		echo $editLink.'~<div id="editablebox_'.$itemVal.'" class="editableform widgetTitleStyle" data-param="'.$dragitem.'">'.$widgetContent.'</div>';
	}
	
	else if($newitem == 'externalapp_navmenu'){ // html widget for navigational menu

		$widgetContent 	= 'A sample navigation menu, click edit to update the menu items';
		//$widgetTitle	= 'Navigation Menu';
		$currentPage 	= $_SESSION['siteDetails']['currentpage'];
		$_SESSION['siteDetails'][$currentPage]['apps'][$dragitem]['settings']['menutype'] = 'horizontal';
		
		$editLink 		= $widgetTitle.'<span class="configure"><span title="Click to edit the widget"><a href="#" onclick="loadMenuEditor(\'editablebox_'.$itemVal.'\',1,\'exterbox_navmenu_'.$itemVal.'\')" title="Edit widget" class="iframe panel_edit"> </a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> </a></span></span>';		
		echo $editLink.'~<div id="editablebox_'.$itemVal.'" class="editablenavmenu widgetTitleStyle" data-param="'.$dragitem.'">'.$widgetContent.'</div>';
	}
	
	else if($newitem == 'externalapp_socialshare'){ // html widget for social shares
		$socialShareContents = '<img src="editor/images/icon_facebook.png" class="socialShareClass"> <img src="editor/images/icon_twitter.png" class="socialShareClass"> <img src="editor/images/icon_linkedin.png" class="socialShareClass">';
		//$socialShareTitle = 'Social share';
		$editLink = $socialShareTitle.'<span class="configure"><span title="Click to edit the widget"  ><a href="#" onclick="loadBoxEditor(\'editablebox_'.$itemVal.'\',1,\'exterbox_socialshare_'.$itemVal.'\')" title="Edit widget" class="iframe panel_edit"> </a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> </a></span></span>';
		echo $editLink.'~<div class="editablebox widgetTitleStyle"  data-param="'.$dragitem.'" id="editablebox_'.$itemVal.'">'.$socialShareContents.'</div>';
	}
	
	else if($newitem == 'externalapp_htmlbox'){ // html widget for social shares
		$widgetContent 	= 'A sample html content, click edit to enter your own text.';
		//$widgetTitle	= 'Html widget';
		$editLink = $widgetTitle.'<span class="configure"><span title="Click to edit the widget"  ><a href="#" onclick="loadHtmlboxEditor(\'editablebox_'.$itemVal.'\',1,\''.$dragitem.'\')" title="Edit widget" class="iframe panel_edit"> </a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> </a></span></span>';
		echo $editLink.'~<div class="editablebox widgetTitleStyle" data-param="'.$dragitem.'" id="editpanel_exterbox_htmlbox_'.$itemVal.'">'.$widgetContent.'</div>';
	}
	else if ($newitem == 'externalapp_slider'){	// widget for slider
		$widgetContent 		= 'A sample image slider, click edit to modify the image gallery.';
		//$widgetTitle		= 'Image Slideshow';
		
		// assign default values
		$currentPage 		= $_SESSION['siteDetails']['currentpage'];
		$settings['height'] = '200';
		$settings['width'] 	= '200';
		$settings['delay'] 	= '2000';
		$_SESSION['siteDetails'][$currentPage]['apps'][$dragitem]['settings'] = $settings;
		
		
		$editLink = $widgetTitle.'<span class="configure"><span title="Click to edit the widget"  ><a href="#" onclick="loadSliderEditor(\'editablebox_'.$itemVal.'\',1,\''.$dragitem.'\')" title="Edit widget" class="iframe panel_edit"> </a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> </a></span></span>';
		echo $editLink.'~<div class="editablebox widgetTitleStyle" data-param="'.$dragitem.'" id="editablebox_'.$itemVal.'">'.$widgetContent.'</div>';
                
	}else if($newitem == 'externalapp_googleadsense'){ // google adsense
		$widgetContent 	= 'Google Adsense, click edit to update the form fields';
		//$widgetTitle	= 'Google Adsense';
		$currentPage 	= $_SESSION['siteDetails']['currentpage'];
		// update the default contact form
		global $arrDefaultFormFields;
		$arrGoogleAdsenseItem 	 = array();
 		$arrGoogleAdsenseItem['google_ad_client'] 		= '';
        $arrGoogleAdsenseItem['google_ad_slot']   		= '';
        $arrGoogleAdsenseItem['google_ad_dimension'] 	= '';
		foreach($arrDefaultFormFields as $fields)
			$arrGoogleAdsenseItem['items'][$fields] 	= $fields;
		$_SESSION['siteDetails'][$currentPage]['apps'][$dragitem] = $arrGoogleAdsenseItem;

		$editLink = '<span class="configure"><span title="Click to edit the widget"><a href="#" onclick="loadGoogleAdsenseEditor(\'editablebox_'.$itemVal.'\',1,\''.$dragitem.'\')" title="Edit widget" class="iframe panel_edit"> </a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> </a></span></span>';
		echo $editLink.'~<div id="editablebox_'.$itemVal.'" class="editableform  widgetTitleStyle" data-param="'.$dragitem.'">'.$widgetContent.'</div>';
	}
	elseif($newitem == 'externalapp_dynamicform'){ // html widget for forms
		$widgetContent 	= 'A sample custom form, click edit to update the form fields';
		//$widgetTitle	= 'Custom Form';
		$currentPage 	= $_SESSION['siteDetails']['currentpage'];
		
		$editLink = $widgetTitle.'<span class="configure"><span title="Click to edit the widget"><a href="#" onclick="loadCustomFormEditor(\'editablebox_'.$itemVal.'\',1,\''.$dragitem.'\')" title="Edit widget" class="iframe panel_edit"> </a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> </a></span></span>';
		echo $editLink.'~<div id="editablebox_'.$itemVal.'" class="editableform widgetTitleStyle" data-param="'.$dragitem.'">'.$widgetContent.'</div>';
	}
	
	
	/*
	elseif ($newitem == 'externalapp_guestbook'){	// widget for guest book
		$widgetContent 	= 'A sample guest book, click edit to modify the guest book.';
		$widgetTitle	= 'Guest Book';
		$editLink = $widgetTitle.'<span class="configure"><span title="Click to edit the widget"  ><a href="#" onclick="loadGuestBookEditor(\'editablebox_'.$itemVal.'\',1,\''.$dragitem.'\')" title="Edit widget" class="iframe panel_edit">Edit</a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> X </a></span></span>';
		echo $editLink.'~<div class="editablebox" style="padding:5px 0px 5px 20px;" data-param="'.$dragitem.'" id="editablebox_'.$itemVal.'">'.$widgetContent.'</div>';
	}
	*/
}
?>