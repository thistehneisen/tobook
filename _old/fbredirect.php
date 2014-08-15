<?php
include "includes/session.php";
include "includes/config.php";

$signed_request = $_REQUEST["signed_request"];
list($encoded_sig, $payload) = explode('.', $signed_request, 2);
$data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

$pageId = $data['page']['id'];

// Get Site Data
$sql = "SELECT nsite_id,vsite_name FROM  tbl_site_mast WHERE fb_page_id ='".$pageId."'";
$res = mysql_query($sql) or die(mysql_error());
$siteVal = mysql_fetch_assoc($res); 

$siteId = $siteVal['nsite_id'];
$siteName = $siteVal['vsite_name'];
$siteNameModified = getAlias($siteName,'-');

$ftp_root_url = getSettingsValue('ftp_root_url');
$pageUrl = $ftp_root_url.$siteNameModified.'/index.html';

//$pageUrl = BASE_URL.'workarea/sites/'.$siteId.'/index.html';
//$pageUrl = "http://jeeva.org/Naseema/easycreate/my-site-test-1/index.html";
header("Location:".$pageUrl);
exit;
?>