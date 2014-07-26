<?php
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add external navigational menu elements to session						                          |
// +----------------------------------------------------------------------+
include "../includes/session.php";
$appForm = $_POST['txtformname'];

$currentPage 	= $_SESSION['siteDetails']['currentpage'];
$type = $_GET['type'];
if($type == 1) {
    $google_ad_client		= $_POST['google_ad_client'];
    $google_ad_slot		= $_POST['google_ad_slot'];
    $google_ad_dimension	= $_POST['google_ad_dimension'];
    //$google_ad_height		= $_POST['google_ad_height'];

    $error_flag = 0;
    $message = "Please enter the following to continue :  <br/>";

    if($google_ad_client==""){
        $message .= "Enter Google Ad Client ID<br/>";
        $error_flag = 1;
    }
    if($google_ad_slot==""){
        $message .= "Enter Google Ad Slot";
        $error_flag = 1;
    }

    // validation
    if($error_flag==0){
    if($google_ad_client != '' && $google_ad_slot && $google_ad_dimension) {
        $arrFormItem 		= array();
        $arrFormItem['google_ad_client'] 	= $google_ad_client;
        $arrFormItem['google_ad_slot']          = $google_ad_slot;
        //$arrFormItem['google_ad_width'] 	= $google_ad_width;
        //$arrFormItem['google_ad_height'] 	= $google_ad_height;
        $arrFormItem['google_ad_dimension'] 	= $google_ad_dimension;

        // adding new menu item
        $rowNo = time();
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm] = $arrFormItem;
        echo "success~".$appForm;
    }
    }else{
        echo "failure~".$message;
    }
}
?>