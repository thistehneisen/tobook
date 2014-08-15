<?php
require_once("../DB_Connection.php");
require_once "config.php";
require_once "xmlapi.php";
require_once "dnsimple.php";
require_once "function.php";

$result = "success";
$error = "";
$data = array();

$domainName = mysql_escape_string($_POST['domainName']);
$ownerId = mysql_escape_string($_POST['ownerId']);
$siteId = mysql_escape_string($_POST['siteId']);

$result = createDomain($domainName);
if ($result != "success") {
    $error = $result;
    $result = "failed";
} else {
    $sql = "insert into tbl_domain_info( owner, domain, status, siteId, type, created_time )
            values ( $ownerId, '$domainName', 'P', $siteId, 'T', now())";
    $db->queryInsert($sql);
}
$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>