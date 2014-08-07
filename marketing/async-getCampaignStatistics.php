<?php
	require_once("../DB_Connection.php");
    require_once("common/functions.php");

    $result = "success";
    $error = "";
    $data = array();
    
    $campaignId = mysql_escape_string($_POST['campaignId']);
    
    $sql = "select * from tbl_email_campaign where email_campaign = $campaignId";
    $dataCampaign = $db->queryArray( $sql );
    $dataCampaign = $dataCampaign[0];
    $categoryCode = $dataCampaign['category_code'];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://sendgrid.com/api/stats.get.json');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    
    $data = array(
    		'api_user'=>SENDGRID_USERNAME,
    		'api_key'=>SENDGRID_PASSWORD,
    		'category'=>$categoryCode,
    		'aggregate'=>1
    );
    
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $output = curl_exec($ch);
    curl_close($ch);
    // logToFile("data.log", "out : $output");
    $statistics = json_decode( $output, true );
    
    if( isset($statistics["error"]) ){
    	$result = "failed";
    }else{
    	$data['statistics'] = $statistics[0];
    }
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
