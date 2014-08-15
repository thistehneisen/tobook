<?php
/**********************************************************************\

	SHIPPING.php -  An Example shipping calculation

	NOTE: your store must be explicitly set up before doing shipping calculations

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
	$myorder["ordertype"]  = "CALCSHIPPING";
	
	# Shipping fields
	$myorder["scarrier"] = "2";
	$myorder["sweight"]  = "1.2";
	$myorder["sitems"]   = "1";
	$myorder["stotal"]   = "12.99";
	$myorder["sstate"]   = "CA";
	

  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);       # use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods

	if ($result["r_shipping"])
	{
		print "Shipping: $result[r_shipping]\n";
	}
	else
	{
		print "Error: $result[r_error]\n"; 
	}
?>
