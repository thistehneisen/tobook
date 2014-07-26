<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $siteId = mysql_escape_string($_POST['siteId']);
    
    $sql = "update tbl_domain_info
    		   set status = 'R'
    		 where siteId = $siteId";
    $db->query( $sql );

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
