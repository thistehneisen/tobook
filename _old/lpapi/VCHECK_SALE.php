<?php
/**********************************************************************\

	VCHECK_SALE.php -  Minimum Required Fields for a VirtualCheck SALE

	Copyright 2003 LinkPoint International, Inc. All Rights Reserved.

	This software is the proprietary information of LinkPoint International, Inc.  
	Use is subject to license terms.

\***********************************************************************/


	include"lphp.php";
	$mylphp=new lphp;

	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "./YOURCERT.pem"; # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "1234567";        # Change this to your store number 

	$myorder["ordertype"]    = "SALE";
	$myorder["chargetotal"]  = "9.99";

	# Customer's Driver's license # and DL state
	$myorder["dl"] = "120381698";
	$myorder["dlstate"] = "CA";

	# Transit routing number for the customer's bank
	$myorder["routing"] = "123456789";

	# Customer's bank account number
	$myorder["account"] = "2139842610";

	# Is this a business or personal account? personal  = pc, business = bc
	$myorder["accounttype"] = "pc";
	
    # Bank name and 2-letter bank state
	$myorder["bankname"]    = "MyBank";
	$myorder["bankstate"]   = "CA";

	# Additional required VCHECK fields
	$myorder["name"] = "Joe Customer";
	$myorder["address1"] = "123 Broadway";
	$myorder["city"] = "Moorpark";
	$myorder["state"] = "CA";
	$myorder["zip"] = "12345";
	$myorder["phone"] = "8051234567";
	$myorder["email"] = "joe.customer@somewhere.com";	# optional field


  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);       # use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods


	if ($result["r_approved"] != "SUBMITTED")	// transaction failed, print the reason
	{
		print "Approved: $result[r_approved]\n";
		print "Error: $result[r_error]\n";
	}
	else    // success
	{
		print "Approved: $result[r_approved]\n";
		print "Code: $result[r_code]\n";
		print "OID: $result[r_ordernum]\n\n";
	}

/*
	# Look at returned hash & use the elements you need  #
	while (list($key, $value) = each($result))
	{
		echo "$key = $value\n"; 

	#if you're in web space, look at response like this:
		 echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<BR>\n";
	}
*/
?>
