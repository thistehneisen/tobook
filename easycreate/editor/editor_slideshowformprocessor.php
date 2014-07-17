<?php 
/*
 *  slider image processor
 *  Authors: jinson<jinson.m@armia.com>
 */

include "../includes/session.php";
 
$sliderid 		= $_POST['sliderid'];
$newimg 		= $_POST['newimg'];
$action 		= $_POST['action'];
//echo "<pre>";
//print_r($_POST);
$currentPage 	= $_SESSION['siteDetails']['currentpage'] ;
if($action == 'add'){					// add the slide show images
	if($sliderid != '' && $newimg != ''){
		$row 		= time();
		$newimg1 = str_replace('/thumb','',$newimg);
		$arrImages 	= array('image' =>$newimg1,'title' => 'sample test' );
 		$_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderid]['images'][$row] = $arrImages;
 		echo "success";
	}
}
elseif($action == 'delete') {			// delete the image from image slide show
 	$sliderid 	= $_POST['sliderid'];
	$imgkey 	= $_POST['imgkey'];
	if($sliderid != '' && $imgkey != '') {
		unset($_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderid]['images'][$imgkey]);
		echo 'success';
	}
	exit();
}elseif($action == 'settings') {		// delete the image from image slide show
	$slideHeight 	= $_POST['height'];
	$slideWidth 	= $_POST['width'];
	$slideDelay		= $_POST['delay'];
	if($sliderid){
		$arrSettings 	= array('height' =>$slideHeight,'width' => $slideWidth,'delay'=> $slideDelay);
		$_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderid]['settings']		= $arrSettings;
		echo "success";
	}
	exit();
}
?>