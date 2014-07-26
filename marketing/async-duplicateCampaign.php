<?php
	require_once("common/DB_Connection.php");	
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $duplicateCampaignId = mysql_escape_string($_POST['duplicateCampaignId']);
    $newCampaignName = mysql_escape_string($_POST['newCampaignName']);
    $categoryCode = time()."_".MT_generateRandom(16);
    
    $sql = "insert into tbl_email_campaign( owner, reply_email, reply_name, subject, content, status, category_code, created_time, updated_time)
    		select owner, reply_email, reply_name, '$newCampaignName' as subject, content, 'DRAFT', '$categoryCode', now(), now()
      		  from tbl_email_campaign
    		 where email_campaign = $duplicateCampaignId";
    $db->query( $sql );

    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
