<?php
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add external navigational menu elements to session						                          |
// +----------------------------------------------------------------------+
include "../includes/session.php";

$appForm 	= $_POST['txtformname'];
$currentPage 	= $_SESSION['siteDetails']['currentpage'];
$type           = $_GET['type'];

if($type == 1) {
    $contactEmail		= $_POST['txtEmailAddress'];
    $contactParams		= $_POST['ddlParameters'];
    // validation
    if($contactEmail != '' && sizeof($contactParams) > 0) {
        $arrFormItem 			= array();
        $arrFormItem['email'] 		= $contactEmail;
        foreach($contactParams as $key=>$param) {
            $arrFormItem['items'][$param] = $param;
        }
        // adding new menu item
        $rowNo = time();
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm] = $arrFormItem;
        echo "success~".$appForm;
    }
}
else if($type ==2) {	// code to edit the contact form mail id
	$contactEmail		= $_POST['txtEmailAddress'];
	$_SESSION['siteDetails']['contactmailid']  = $contactEmail;
	echo "success";
}
?>