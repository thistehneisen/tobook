<?php
include 'config.php';
include 'function.php';
include 'xmlapi.php';
include 'dnsimple.php';
global $domainObject;
 
$domain = $_GET["domain"];
$domainObject = new DNSimple;
$domainObject->url = SITE_URL;
$domainObject->username = USER_EMAIL;
$domainObject->password = USER_PASSWORD;
$ip = HOST_IP;

$check = isValidDomain($domain);
if ($check == "failed") {
    echo "Please check your domain name you want to register now";
} else {
    $result = createDomain($domain);
}
echo $result;
?>