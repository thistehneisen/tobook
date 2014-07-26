<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $userId = mysql_escape_string($_POST['userId']);
    $userName = mysql_escape_string($_POST['userName']);
    $table_prefix = str_replace("-", "", $userName)."_hey_appscheduler";
    
    $sql = "select t1.id, t2.content as name
    		  from ".$table_prefix."_employees t1, ".$table_prefix."_multi_lang t2
    		 where t2.model = 'pjEmployee'
    		   and t2.locale = 1
    		   and t2.foreign_id = t1.id";
    $employeeList = $db->queryArray( $sql );
    if( $employeeList == null )
    	$employeeList = array( );
    
    $data['employeeList'] = $employeeList;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
