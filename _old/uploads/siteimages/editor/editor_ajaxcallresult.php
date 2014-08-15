<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              		|
// | page to return ajax results				                          |
// +----------------------------------------------------------------------+
	$type = $_GET['type'];
        $menuHtml = '';
        define("EDIT_MENU_TITLE", "Manage Menu");
	
	if($type == 'pagelist') {		// code to load the page menu items
		include "../includes/session.php";
		$pageList 				= $_SESSION['siteDetails']['pages'];
		$currentPage 			= $_SESSION['siteDetails']['currentpage'];
		if(sizeof($pageList) > 0 ){
			$pagelink = '<select name="slct_pages" class="select_stylenew1 jQPages" >';
			foreach($pageList as $pages){
				$pagelink .= '<option value="'.$pages['alias'].'" '.(($pages['alias']==$currentPage)?'selected="selected"':'').' >'.ucwords($pages['title']).'</option>';
			}
			$pagelink .= '<option value="addpage">Add New</option></select>';
		}
		echo $pagelink;
		exit();
	}
	elseif ($type == 'deleteapp') { // code to delete the external aplication from curent page
		$deleteApp = $_POST['panelId']; 
		include "../includes/session.php";
                include "../includes/config.php";
                include "../includes/sitefunctions.php";
		if($deleteApp != '') {
                        $siteId                 = $_SESSION['siteDetails']['siteId'];
			$currentPage 		= $_SESSION['siteDetails']['currentpage'];
			$panelPositions 	= $_SESSION['siteDetails'][$currentPage]['panelpositions'];
                        if($siteId > 0)
                            $pageId = getSitePageId($siteId,$currentPage);
			foreach($panelPositions as $panlePos=>$poses){
				foreach($poses as $key=>$position){
					if($position == $deleteApp){
                                                $appId = explode("_",$position);
                                                if($appId[0]=='exterbox'){
                                                    $panelId = $appId[2];
                                                    $type = 'app';
                                                }else{
                                                    $panelId = $deleteApp;
                                                    $type = 'panel';
                                                } 
                                               // $panelExists = checkSitePagePanelExists($panelId,$pageId,$type); 
                                                
                                                if($panelExists > 0)
                                                    deleteSitePagePanel($panelId,$pageId,$type);
						unset($_SESSION['siteDetails'][$currentPage]['panelpositions'][$panlePos][$key]);
						echo "success"; 
					}
				}
			}
		}	
	}
	elseif($type == 'loadmenu'){// function to load the menu after editing
		//echo "<pre>";
		//print_r($_GET);
		include "../includes/session.php";
		$menuName 		= $_GET['menuname'];
		$currentPage 	= $_SESSION['siteDetails']['currentpage'];
		$menuItems 		= $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuName]['items'];
		foreach($menuItems as $key=>$element) {
			$menuHtml .= '<li><a href="'.$element['link'].'">'.$element['title'].'</a></li>';
		}
		$menuHtml .='<div class="manageMenuStyle"><a href="#" data-param="'.$menuName.'" class="jqeditormenusettings">'.EDIT_MENU_TITLE.'</a></div>';
		echo $menuHtml;
		exit();
		
	}
	elseif($type == 'loadcontactform'){
		
		$formName = $_GET['formname'];
		if($formName != '') {
			include "../includes/session.php";
			include "../includes/config.php";
			include "../includes/cls_htmlform.php";
			$currentPage 	= $_SESSION['siteDetails']['currentpage'];	
			$objForm 		= new Htmlform();
            $formDetails 	= $_SESSION['siteDetails'][$currentPage]['apps'][$formName];
            if(sizeof($formDetails) > 0) {
            	global $formFeedBackItems;
                $feedBackItems =  $formDetails['items'];
                foreach($feedBackItems as $key=>$items) {           	
                	$itemDet = $formFeedBackItems[$items];                
                    $objFormElement				= new Formelement();
                    $objFormElement->type		= $itemDet['field'];
                    $objFormElement->name       = $items;
                    $objFormElement->id         = $items;
                    $objFormElement->label      = $itemDet['title'];
                    $objForm->addElement($objFormElement);
                }
             }
             $formOutPut	= $objForm->renderForm();
             echo $formOutPut;
             exit();
		}
	} 
	elseif($type == 'loadcustomform'){
		/*echo "loading";
		exit();*/
            include "../includes/cls_htmlform.php";
            $objForm = new Htmlform();           
            $customFormDetails = $_SESSION['siteDetails'][$currentPage]['apps'][$panlePos];    
            //session_start();
            //echo "session<pre>";print_r($_SESSION);exit;
            echo $customFormDetails['formelements']; //formelements
	}
	elseif($type == 'loadsliderimage'){		// load a slider image
		$slider = $_GET['slider'];
		include "../includes/session.php";
		$currentPage 	= $_SESSION['siteDetails']['currentpage'];	
		$sliderDetails 	= $_SESSION['siteDetails'][$currentPage]['apps'][$slider];
		//echo "<pre>";
		//print_r($sliderDetails);
		$sliderWidth = $sliderDetails['settings']['width'];
		
		
		$sliderImg = $sliderDetails['images'];
		krsort($sliderImg);
		foreach($sliderImg as $key=>$images){
			$imgDet = '<img src="'.$images['image'].'" width="'.$sliderWidth.'" >';
			//break;	
		}
		echo $imgDet; 
		exit();
	}
	elseif($type == 'loadnavmenu'){
		$menuname = $_GET['menuname'];
		include "../includes/session.php";
		$currentPage 	= $_SESSION['siteDetails']['currentpage'];	
		$menuDetails 	= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname];
                $menuClass 	= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname]['settings']['menutype'];
                
		$menuItems 		= $menuDetails['items'];
		$menuText 		= '<ul class="'.$menuClass.'">';
		foreach($menuItems as $key=>$items){
			$menuText .= '<li><a href="">'.$items['title'].'</a></li>';
		}
		$menuText .= '</ul>';
		echo $menuText;
		exit();
	}
	
		
?>