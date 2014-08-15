<?php
/**********************************************************************\

	TAX.php -  An Example tax calculation

	NOTE: your store must be explicitly set up before doing tax calculations

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

	$myorder["ordertype"]  = "CALCTAX";
	$myorder["subtotal"]   = "12.99";
	
	# Shipping fields
	$myorder["stotal"]     = "12.99";
	$myorder["sstate"]     = "CA";
	$myorder["szip"]       = "91367";
	

  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);       # use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods

	if ($result["r_tax"])
	{
		print "Tax: $result[r_tax]\n";
	}
	else
	{
		print "Error: $result[r_error]\n";
	}
?>
