<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $groupId = mysql_escape_string($_POST['groupId']);
    $ownerId = mysql_escape_string($_POST['ownerId']);
    $groupName = mysql_escape_string($_POST['groupName']);    

    if( $groupId == "" ){
	    $sql = "insert into tbl_marketing_group( owner, group_name, created_time, updated_time )
	    		value( $ownerId, '$groupName', now(), now() )";
	    $db->queryInsert( $sql );
    }else{
    	$sql = "update tbl_marketing_group
    			   set group_name = '$groupName'
    			     , updated_time = now()
    			 where marketing_group = $groupId";
    	$db->query( $sql );
    }

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>