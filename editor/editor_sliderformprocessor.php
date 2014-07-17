<?php 
/*
 *  slider image processor
 *  Authors: jinson<jinson.m@armia.com>
*/

include "../includes/session.php";

$sliderid 	= $_POST['sliderid'];
//$newimg 	= $_POST['newimg'];
$action 	= $_POST['action'];

$currentPage = $_SESSION['siteDetails']['currentpage'] ;
if($action == 'add') {
    $newimg 	= $_POST['newimg'];
    if($sliderid != '' && $newimg != '') {
        $row 		= time();
        $newimg1 = str_replace('/thumb','',$newimg);
        $arrImages 	= array('image' =>$newimg1,'title' => 'sample test' );
        $_SESSION['siteDetails'][$currentPage]['apps'][$sliderid]['images'][$row] = $arrImages;
        echo "success";
    }
}
elseif($action == 'delete') {		// delete the image from image gallery
    $sliderid 	= $_POST['sliderid'];
    $imgkey 	= $_POST['imgkey'];
    if($sliderid != '' && $imgkey != '') {
        unset($_SESSION['siteDetails'][$currentPage]['apps'][$sliderid]['images'][$imgkey]);
        echo 'success';
    }
    exit();
}elseif($action == 'settings') {		// delete the image from image gallery
    $slideHeight 	= $_POST['height'];
    $slideWidth 	= $_POST['width'];
    $slideDelay		= $_POST['delay'];
    if($sliderid) {
        $arrSettings 	= array('height' =>$slideHeight,'width' => $slideWidth,'delay'=> $slideDelay);
        $_SESSION['siteDetails'][$currentPage]['apps'][$sliderid]['settings']		= $arrSettings;
        echo "success";
    }
    exit();
}
?>