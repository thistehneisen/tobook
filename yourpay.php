<?php
if (!$txtFirstName || !$txtLastName || !$txtAddress || !$txtCity || !$txtState || !$txtZIP || !$txtCCNumber || !
    $txtEmail || !$ddlCountry || !$txtMM || !$txtYY|| !$txtPhone) {
    $cc_flag 	= false;
    $cc_err 	= "Some necessary information were found missing.  Please enter the missing information." ;
	// exit;
} else {
	include_once "lpapi/lphp.php";
	$mylphp		= new lphp;
	
	// get link pay details
	$sql	= "select vname,vvalue from tbl_lookup where vname in('site_price', 'linkpay_store','linkpay_demo')";
	$result = mysql_query($sql,$con);
	while($row=mysql_fetch_array($result)){
		$vname	= stripslashes($row["vname"]);
		$vvalue	= stripslashes($row["vvalue"]);
			switch($vname){
				case  linkpay_store:
					  $lp_linkpay_store = urlencode($vvalue); // Tran Key
				 break;
				case  linkpay_demo:
					 $lp_linkpay_demo = urlencode($vvalue); // Replace LOGIN with your login
				 break;
				case  site_price:
					 $lp_site_price = urlencode($vvalue); // Replace LOGIN with your login
				 break;
			}
	}
	
	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "./lpapi/1001168167.pem"; # Change this to the name and location of your certificate file
	$myorder["configfile"] = urlencode($lp_linkpay_store);        # Change this to your store number




	$myorder["ordertype"]    = "SALE";
    if($lp_linkpay_demo	== "YES"){
	  $myorder["result"]       = "GOOD";# For a test, set result to GOOD, DECLINE, or DUPLICATE
	}else{
	  $myorder["result"]       = "LIVE";
	}

    $myorder["cardnumber"]   = urlencode($txtCCNumber);
	$myorder["cardexpmonth"] = $txtMM ;
	$myorder["cardexpyear"]  = $txtYY;
	$myorder["chargetotal"]  = urlencode($lp_site_price);
	$myorder["cvmindicator"] = "provided";
	$myorder["cvmvalue"]     = $txtCode;

# BILLING INFO 4111111111111111
	$myorder["name"]     = urlencode($txtFirstName)." ".urlencode($txtLastName);
	$myorder["company"]  = urlencode($txtCompany);//"-NA-";
	$myorder["address1"] = urlencode($txtAddress);
	$myorder["city"]     = urlencode($txtCity);
	$myorder["state"]    = urlencode($txtState);
	$myorder["country"]  = urlencode($ddlCountry);
	$myorder["phone"]    = urlencode($txtPhone);
	$myorder["email"]    = urlencode($txtEmail);
	$myorder["zip"]      = urlencode($txtZIP);
	//$myorder["debugging"] = "true";  # for development only - not intended for production use
	$result 			 = $mylphp->curl_process($myorder);  # use curl methods

   if(!is_array($result)) {
        $cc_flag  	= false;
        $cc_err 	= "Error";
        $cc_err 	.= "<br>" . $result;
   } else { 
		if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
		{
			$cc_flag  	= false;
			$cc_err 	= "Error";
			$err		= explode(":", $result[r_error]);
			if(is_array($err))
				$len	= strlen($err[0])+1;
			else
				$len	= 0;
			$cc_err 	.= "<br>" . substr($result[r_error], $len);
		} else {	// success
		   $cc_flag = true;
		   $cc_tran = $result[r_ordernum];
		}
	}
}
?>
