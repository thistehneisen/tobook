<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");
    
    require_once "../domain/config.php";
    require_once "../domain/xmlapi.php";
    require_once  "../domain/dnsimple.php";
    require_once "../domain/function.php";        

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
