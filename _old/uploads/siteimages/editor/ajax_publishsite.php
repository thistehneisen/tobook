<?php 
/*
 *  Page to save site details
 */
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "../includes/cls_htmlform.php";
include "../editor/editor_addwidgetcontent.php";
include "../includes/sitefunctions.php";

$siteDetails  = $_SESSION['siteDetails'];
$siteId       = ($siteDetails['siteId']>0)?$siteDetails['siteId']:0;
$userId       = $_SESSION["session_userid"];

// Insert/Update Site Data If Not Exists
$siteId = saveSiteDetails($siteId);
if($siteId >0){
    //saveSitePages($siteId);
    //getSiteDetailsBySiteWithSession($siteId);
    deleteSiteDetails($siteId);
    saveSiteDetailsAsWholeToDb($siteId);
    assignSiteDetailsAsWholeToSession($siteId);
}

// Publish Site
publishSite();

// Get Payment Status (Whether site is paid or not)
$paymentStatus = getPaymentStatusByUser($siteId,$userId);

// Get Payment Mode (paid or free)
$paymentType = getSettingsValue('paymentsupport');

if($paymentType=='no'){
    // Save Payment Details
    savePaymentDetails('Free');
}

echo $paymentStatus.'**'.$paymentType;

?>