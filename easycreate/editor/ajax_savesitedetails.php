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
$site_id      = ($siteDetails['siteId']>0)?$siteDetails['siteId']:0;  

// Insert/Update Site Data
$site_id = saveSiteDetails($site_id);  
if($site_id>0){
    //saveSitePages($site_id);
    //getSiteDetailsBySiteWithSession($site_id);
    deleteSiteDetails($site_id); 
    saveSiteDetailsAsWholeToDb($site_id);
    assignSiteDetailsAsWholeToSession($site_id); 
}

// Publish Site
publishSite();

echo 1;

?>