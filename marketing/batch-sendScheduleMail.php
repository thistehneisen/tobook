<?php
	require_once("../DB_Connection.php");
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
       
    $sql = "select * from tbl_email_campaign where launched_time <= now() and status = 'SCHEDULED'";
    $dataCampaign = $db->queryArray( $sql );
    for( $i = 0 ; $i < count($dataCampaign); $i ++ ){
    	$category = $dataCampaign[$i]['category_code'];
    	$message = MT_mailImage( utf8_encode( $dataCampaign[$i]['content'] ) ) ;
    	$subject = $dataCampaign[$i]['subject'];
    	$replyEmail = $dataCampaign[$i]['reply_email'];
    	$replyName = $dataCampaign[$i]['reply_name'];
    	$campaignId = $dataCampaign[$i]['email_campaign'];
    	
    	$recipients = array( );
    	$sql = "select t2.destination from tbl_marketing_message t1, tbl_marketing_history t2 where t1.content = '$campaignId' and t1.marketing_message = t2.marketing_message";
    	logToFile("data.log", "SQL : $sql");
    	$dataCustomer = $db->queryArray( $sql );
    	for( $j = 0 ; $j < count( $dataCustomer ); $j ++ ){
    		$recipients[] = $dataCustomer[$j]['destination'];
    	}

    	
    	MT_sendEmailBulk($recipients, $replyEmail, $replyName, $subject, $message, 1, $category);
    	$sql = "update tbl_email_campaign
    			   set status = 'SENT'
    			 where email_campaign = '$campaignId'";
    	$db->query( $sql );    	
    }
    	    
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);    
?>
