<?php
	require_once("../DB_Connection.php");
    require_once("common/functions.php");
    
    require_once("../gatewayPayment/onePayment.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $ownerId = mysql_escape_string($_POST['ownerId']);
    $campaignId = mysql_escape_string($_POST['campaignId']);
    $customerList = $_POST['customerList'];
    
    $sql = "select * from tbl_email_campaign where email_campaign = $campaignId";
    $dataCampaign = $db->queryArray( $sql );
    $dataCampaign = $dataCampaign[0];
    $category = $dataCampaign['category_code'];
    $message = MT_mailImage( utf8_encode( $dataCampaign['content'] ) ) ;
    $subject = $dataCampaign['subject'];
    $replyEmail = $dataCampaign['reply_email'];
    $replyName = $dataCampaign['reply_name'];
    
    
    $marketingType = 'M';
    
    $sql = "insert into tbl_marketing_message( title, content, type, created_time, updated_time)
    		value( '', '$campaignId', '$marketingType', now(), now())";
    $db->queryInsert( $sql );
    
    $marketingMessage = $db->getPrevInsertId();

    $sql = "select *
    	  	  from tbl_owner_premium
    		 where owner = $ownerId
    		   and plan_group_code = 'mt'";
    $dataResult = $db->queryArray( $sql );
    $credits = $dataResult[0]['credits'];
    $accountCode = $dataResult[0]['account_code'];
    
    $payable = "Y";
    if( $credits >= count( $customerList ) * CREDITS_EMAIL ){
    	$payable = "Y";
    	$remainCredits = $credits - count( $customerList ) * CREDITS_EMAIL;
    }else{
    	$recurlyAmount = round( ( count( $customerList ) * CREDITS_EMAIL - $credits ) / CREDITS_PRICE ) + 1;
    	
    	$paymentResult = onePay($accountCode, $recurlyAmount);
    	if($paymentResult->result == "success"){
	    	$remainCredits = $credits - count( $customerList ) * CREDITS_EMAIL + $recurlyAmount * CREDITS_PRICE;
	    	$payable = "Y";
	    }else{
	    	$payable = "N";
	    }
    }
    
    if( $payable == "Y" ){
		$recipients = array( );
	    for( $i = 0 ; $i < count( $customerList ); $i ++ ){
	    	$customerId = $customerList[$i]['customerId'];
	    	$planGroupCode = $customerList[$i]['planGroupCode'];
	    	$destination =  $customerList[$i]['destination'];
	    	$recipients[] = $destination;
	
	    	$sql = "insert into tbl_marketing_history( owner, customer, plan_group_code, destination, messageId, status, marketing_type, marketing_message, created_time, updated_time)
	    			values($ownerId, $customerId, '$planGroupCode', '$destination', '', 'success', '$marketingType', $marketingMessage, now(), now() )";
	    	$db->queryInsert( $sql );
	    	// MT_sendEmail($destination, $subject, $message, 1, $category);
	    }
	
	    MT_sendEmailBulk($recipients, $replyEmail, $replyName, $subject, $message, 1, $category);
	
	    $sql = "update tbl_email_campaign
	    		   set status = 'SENT'
	    		     , launched_time = now()
	    		 where email_campaign = '$campaignId'";
	    $db->query( $sql );
	    
	    $sql = "update tbl_owner_premium
	    		   set credits = credits - ".count( $customerList ) * CREDITS_EMAIL."
	    		 where owner = $ownerId
	    		   and plan_group_code = 'mt'";
	    $db->query( $sql );
	    
	    $data['credits'] = $remainCredits;	    
    }else{
    	$result = "failed";
    	$error = "CREDIT_NOT_ENOUGH";    	
    }    

    
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);    
?>
