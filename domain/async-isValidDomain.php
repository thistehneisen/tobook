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
    $result = isValidDomain( $domainName );
    
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
