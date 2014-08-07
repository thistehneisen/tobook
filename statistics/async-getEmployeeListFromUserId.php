<?php
	require_once("../DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $userId = mysql_escape_string($_POST['userId']);
    $userName = mysql_escape_string($_POST['userName']);
    
    $sql = "select t1.id, t2.content as name
    		  from as_employees t1, as_multi_lang t2
    		 where t2.model = 'pjEmployee'
    		   and t2.locale = 1
    		   and t2.foreign_id = t1.id
    		   and t1.owner_id = $userId";
    $employeeList = $db->queryArray( $sql );
    if( $employeeList == null )
    	$employeeList = array( );
    
    $data['employeeList'] = $employeeList;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
