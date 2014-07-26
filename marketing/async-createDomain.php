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
    $ownerId = mysql_escape_string($_POST['ownerId']);
    $siteId = mysql_escape_string($_POST['siteId']);
    
    $result = createDomain( $domainName );
    if( $result != "success" ){
    	$error = $result;
    	$result = "failed";
    }else{
    	$sql = "insert into tbl_domain_info( owner, domain, status, siteId, created_time )
    			values( $ownerId, '$domainName', 'P', $siteId, now())";
    	$db->queryInsert( $sql );
    	// logToFile("data.log", "CreateDomain - SQL : $sql");    	
    }
    
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
