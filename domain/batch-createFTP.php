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
    
	$sql = "select * from tbl_domain_info where status = 'P'";
	$domainList = $db->queryArray($sql);
	// logToFile("data.log", "CreateFTP - SQL : $sql");
	for( $i = 0; $i < count( $domainList ); $i++ ){
		$domainInfo = $domainList[$i]['domain_info'];
		$domain = $domainList[$i]['domain'];
		$siteId = $domainList[$i]['siteId'];
		$ownerId = $domainList[$i]['owner'];
		$type = $domainList[$i]['type'];
		
		// logToFile("data.log", "CreateFTP - Domain : $domain");
		// logToFile("data.log", "CreateFTP - DomainInfo : $domainInfo");
		if( isValidDomain( $domain ) ){
			$accountInfo = createFTPAccount( $domain );
			// logToFile("data.log", "CreateFTP - RESULT : ".$accountInfo->result);
			if ($accountInfo->result == "success"){
				if($accountInfo->error == null){
					$ftpServer = $accountInfo->ftp_server;
					$ftpUsername = $accountInfo->ftp_user;
					$ftpPassword = $accountInfo->ftp_pass;
					
					$sql = "update tbl_domain_info
							   set ftp_server = '$ftpServer'
								 , ftp_username = '$ftpUsername'
								 , ftp_password = '$ftpPassword'
								 , status = 'A'
							 where domain_info = $domainInfo";
					$db->query($sql);
					// logToFile("data.log", "CreateFTP - UPDATE-SQL : $sql");					
					
					$message = run_ftp_uploading( $ftpServer, $ftpUsername, $ftpPassword, "/", $siteId);
					// logToFile("data.log", "CreateFTP - MESSAGE : $message");
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
						
						if( $type == "B" ){
							$message="Congratulation! <br/><br/>
							Your Web site has been set successfully.<br/><br/>
							Please visit your site follow URL:<br/><br/>
							<a href='$siteUrl'>$siteUrl</a><br/><br/>
							Thank you";
							$subject = "Your Web Site Has been installed.";
														
						}else if( $type == "T" ){
							$message="Congratulation! <br/><br/>
							Your Web site has been transfered successfully.<br/><br/>
							Thank you";
							$subject = "Your Web Site Has been installed.";							
						}
						
						$senderName = "Klikkaaja";
						$noreply = "noreply@klikkaaja.com";
							
						$Headers="From: $senderName <$noreply>\n";
						$Headers.="Reply-To: $senderName <$noreply>\n";
						$Headers.="MIME-Version: 1.0\n";
						$Headers.="Content-type: text/html; charset=iso-8859-1\r\n";
							
						mail($email, $subject,  $message, $Headers);						

					}
				} else{
					$error = $accountInfo->error; 
				}
			} else{
				$error = "Please make sure you typed correctly.";
			}
		}
	}
    
    $data['result'] = $result;
    $data['error'] = $error;
    header('Content-Type: application/json');
    echo json_encode($data);
?>
