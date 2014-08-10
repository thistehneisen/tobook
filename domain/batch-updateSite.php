<?php
	require_once("../DB_Connection.php");
	
    require_once("functionFTP.php");
    require_once "config.php";
    require_once "xmlapi.php";
    require_once "dnsimple.php";
    require_once "function.php";

    $result = "success";
    $error = "";
    $data = array();
    
	$sql = "select * from tbl_domain_info where status = 'R'";
	$domainList = $db->queryArray($sql);
	// logToFile("data.log", "CreateFTP - SQL : $sql");
	for( $i = 0; $i < count( $domainList ); $i++ ){
		$domainInfo = $domainList[$i]['domain_info'];
		$domain = $domainList[$i]['domain'];
		$siteId = $domainList[$i]['siteId'];
		$ownerId = $domainList[$i]['owner'];


		$ftpServer = $domainList[$i]['ftp_server'];
		$ftpUsername = $domainList[$i]['ftp_username'];
		$ftpPassword = $domainList[$i]['ftp_password'];

		$message = run_ftp_uploading( $ftpServer, $ftpUsername, $ftpPassword, "/", $siteId);

		if( $message == "success" ){
			$sql = "update tbl_domain_info
					   set status = 'S'
					     , updated_time = now()
					 where domain_info = $domainInfo";
			$db->query($sql);
			
			$sql = "select * from tbl_user_mast where nuser_id = $ownerId";
			$dataUser = $db->queryArray($sql);
			$dataUser = $dataUser[0];
			$email = $dataUser['vuser_email'];
			
			$siteUrl = "http://$domain";
			$message="Congratulation! <br/><br/>
					Your Web site has been updated successfully.<br/><br/>
					Please visit your site follow URL:<br/><br/>
					<a href='$siteUrl'>$siteUrl</a><br/><br/>
					Thank you";
			
			$senderName = "Klikkaaja";
			$noreply = "noreply@klikkaaja.com";
			
			$Headers="From: $senderName <$noreply>\n";
			$Headers.="Reply-To: $senderName <$noreply>\n";
			$Headers.="MIME-Version: 1.0\n";
			$Headers.="Content-type: text/html; charset=iso-8859-1\r\n";
			
			mail($email, "Your Web Site Has been updated.",  $message, $Headers);
		}
					
	}
    
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
