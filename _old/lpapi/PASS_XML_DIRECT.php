<?php

# PASS_XML_DIRECT.php - example of how to send and recieve an XML string 
# directly to LSGS
#
# This sample demonstrates using the PHP built-in CURL methods to send an
# XML string DIRECTLY from this sample program to LSGS, bypassing lphp.php,
# liblphp.so and liblpssl.so.
#
# We assume that PHP has been compiled to support the CURL methods. 
# To see if your server's PHP supports CURL methods, enter the command 
# "php -m" to see a listing of supported modules. 
#
# In this program, the merchant would be doing their own XML encoding/decoding
#
# This sample does only a minimal SALE transaction, but it can be
# used as an example of passing in larger XML strings for more complex
# transactions. Any of the included LinkPoint XML sample files could be 
# passed in as an XML string here. 
#
# Copyright 2003 LinkPoint International, Inc. All Rights Reserved.
# 
# This software is the proprietary information of LinkPoint International, Inc.  
# Use is subject to license terms.


 
# Build a simple transaction XML string
# This XML formatting is for human readability only
# (In real life, this would be all one long unbroken string)
	$xml ="
    <order>
        <orderoptions>
            <result>GOOD</result>
            <ordertype>SALE</ordertype>
        </orderoptions>
        <merchantinfo>
            <configfile>123456</configfile> <!-- CHANGE THIS TO YOUR STORE NUMBER -->
        </merchantinfo>
        <creditcard>
            <cardnumber>4111111111111111</cardnumber>
            <cardexpmonth>12</cardexpmonth>
            <cardexpyear>08</cardexpyear> 
        </creditcard>
        <payment>
            <chargetotal>1.03</chargetotal>
        </payment>
    </order>";
	
	$host	= "secure.linkpt.net";
	$port	= 1129;
	$cert	= "./YOURCERT.pem"; # change this to the name and location of your certificate file
	
	
	$hoststring = "https://".$host.":".$port."/LSGSXML";
	
	# use PHP built-in curl functions
	$ch = curl_init ();
	curl_setopt ($ch, CURLOPT_URL,$hoststring);
	curl_setopt ($ch, CURLOPT_POST, 1); 
	curl_setopt ($ch, CURLOPT_POSTFIELDS, $xml); # the string we built above
	curl_setopt ($ch, CURLOPT_SSLCERT, $cert);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
#	curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
#	curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, 0);
	
//	curl_setopt ($ch, CURLOPT_VERBOSE, 1);	// optional - verbose debug output
											// not for production use
											
	#  send the string to LSGS
	$result = curl_exec ($ch);

	if (strlen($result) < 2)    # no response
	{
		$result = "<r_error>Could not execute curl.</r_error>"; 
	}
	
	
	# look at the  xml that comes back
	print ("response:  $result\n\n");
	
	# Process the XML from here.... 
	
	
	# Or OPTIONALLY - you could convert XML to an array
	preg_match_all ("/<(.*?)>(.*?)\</", $result, $outarr, PREG_SET_ORDER);
	
	$n = 0;
	while (isset($outarr[$n]))
	{
		$retarr[$outarr[$n][1]] = strip_tags($outarr[$n][0]);
		$n++; 
	}

	# and then look at it like this
	while (list($key, $value) = each($retarr))
		echo "$key = $value \n";
	# and use the hash elements that you need
	
?>
