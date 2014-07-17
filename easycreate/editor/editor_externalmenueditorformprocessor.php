<?php
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add external navigational menu elements to session						                          |
// +----------------------------------------------------------------------+
	include "../includes/session.php";
  	$appMenu 	= $_POST['txtmenuname'];
 
  	$currentPage = $_SESSION['siteDetails']['currentpage'];
   	$type = $_GET['type'];
   	if($type == 1) {
 		$menuTitle		= $_POST['txtaddmenutitle'];
 		$menuLink		= $_POST['txtaddmenulink'];
 		$menuOpenType	= $_POST['selmenuopentype'];
	
 		// validation
    	if($appMenu != '' && $menuTitle != '' && $menuLink != '' && $menuOpenType != '') {
    		
 			$arrMenuItem 				= array();
 			$arrMenuItem['title'] 		= $menuTitle;
 			$arrMenuItem['link'] 		= $menuLink;
 			$arrMenuItem['opentype'] 	= $menuOpenType;
 		
 			// edit checking code 
 			$menuEdit	= $_POST['txtnavaction'];
 			$navid		= $_POST['txtnavid'];
 			if($menuEdit == 'edit' && $navid != '') {		// editing menu item
 				$_SESSION['siteDetails'][$currentPage]['apps'][$appMenu]['items'][$navid] = $arrMenuItem;
 				echo "editsuccess~".$menuTitle;
 			}
 			else {											// adding new menu item
 				$rowNo = time();
 				$_SESSION['siteDetails'][$currentPage]['apps'][$appMenu]['items'][$rowNo] = $arrMenuItem;
 				echo "success~".$menuTitle;
 			}
 		
   		}
   	}
   	else if($type == 2){		// menu item deletion code
   		$navid 		= $_GET['navid'];
   		$menuname 	= $_GET['menuname'];
   		if($navid != '' && $menuname != '') {
   			unset($_SESSION['siteDetails'][$currentPage]['apps'][$menuname]['items'][$navid]);
   			echo "Successfully removed the menu item";
   		}
   	}
   	else if($type == 3){		// menu settings modification
   		$menuName = $_POST['txtmenuname'];
   		$menuType = $_POST['menuType'];
   		if($menuName != '' && $menuType != ''){			// menu type modificatio
   		 	$_SESSION['siteDetails'][$currentPage]['apps'][$menuName]['settings'] = array('menutype' => $menuType);
   		 	echo "success~".$menuType;
   		}
   		//$_SESSION['siteDetails'][$currentPage]['apps'][$appMenu]
   	}
   	
?>