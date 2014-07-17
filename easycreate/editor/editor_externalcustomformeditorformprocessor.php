<?php
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add external navigational menu elements to session						                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";

$appForm        = $_POST['txtformname'];
$currentPage 	= $_SESSION['siteDetails']['currentpage'];
$type           = $_GET['apptype'];

if($type==2) {
    $key = $_POST['type']."_".$_POST['name'];
    $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['name']= $_POST['name'];
    $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['type']= $_POST['type'];
    $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['display']= $_POST['display'];
    if($_POST['value'])
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['value']= $_POST['value'];
    if($_POST['size'])
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['size']= $_POST['size'];
    if($_POST['maxlenght'])
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['maxlenght']= $_POST['maxlenght'];
    if($_POST['rows'])
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['rows']= $_POST['rows'];

    if($_POST['columns'])
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['columns']= $_POST['columns'];

    if($_POST['maxlenght'])
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['elements'][$key]['maxlenght']= $_POST['maxlenght'];



    exit;
}
if($type== 1) {

   // $formPageHeading	= $_POST['txtPageHeading'];
    $formEmail			= $_POST['txtEmailAddress'];
    $formParams			= $_POST['formelements'];
 
    // validation
    if( $formEmail!=''  && $formParams !='') {
        $arrFormItem                    = array();
      //  $arrFormItem['heading']         = $formPageHeading;
        $arrFormItem['email']           = $formEmail;
        $arrFormItem['formelements'] 	= $formParams;


        // adding new menu item
        $rowNo = time();
     //   $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['heading']   = $formPageHeading;
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['email']   = $formEmail;
        $_SESSION['siteDetails'][$currentPage]['apps'][$appForm]['formelements']   = $formParams;
        //print_r($_SESSION['siteDetails'][$currentPage]['apps'][$appForm]);
        echo "success~".$appForm;
    }
    else
    	echo "error~Please enter all details";
}
?>