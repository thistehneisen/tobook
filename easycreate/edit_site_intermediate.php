<?php
include "includes/session.php";
include "includes/config.php";
include "includes/sitefunctions.php";

$action = $_REQUEST['action'];
$siteId = $_REQUEST['siteid'];

if($siteId > 0){
    assignSiteDetailsAsWholeToSession($siteId);
}
header("Location:getsitedetails.php?action=".$action."&siteid=". $siteId);
?>