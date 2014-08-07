<?php
    require_once("../DB_Connection.php");
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();

    $userId = mysql_escape_string($_POST['userId']);
    $planGroupCode = mysql_escape_string($_POST['planGroupCode']);

    $sql = "select if(expired_time > now(), 1, 0) as status, account_code from tbl_owner_premium where plan_group_code = '$planGroupCode' and owner = '$userId'";
    $dataResult = $db->queryArray( $sql );
    if( $dataResult == null ){
        $status = 0;
        $accountCode = "";
    }else{
        $status = $dataResult[0]['status'];
        $accountCode = $dataResult[0]['account_code'];
    }

    $data['accountCode'] = $accountCode;
    $data['status'] = defined('BYPASS_PAYMENT') ? (int) BYPASS_PAYMENT : $status;
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
