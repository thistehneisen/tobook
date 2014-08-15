#!/usr/bin/php

<?php

# PASS_XML_LIB.php - example of how to send and recieve an XML string
#
# In this sample, the merchant would be doing their own XML encoding/decoding
#
# This sample demonstrates bypassing the LPHP.PHP module that is used
# by most of the LinkPoint PHP samples, and sending an XML string DIRECTLY 
# to the shared library LIBLPHP.SO which then uses LIBLPSSL.SO to communicate
# with LSGS. 
#
# An appropriate use of this code would be in a PHP installation where the 
# merchant wants to do their own XML encoding/decoding and the server's PHP
# installation does not support the built-in CURL methods.
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


# If the library is at loaded at startup in PHP.INI, we don't need this call

    if (!extension_loaded('liblphp')) {
        exit("cannot load liblphp.so, bye\n");
        //dl("liblphp.so");
    }
    
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
	
	$cert	= "./123456.pem"; # change this to the name and location of your certificate file
	$host	= "secure.linkpt.net";
	$port	= 1129;
	
	// send transaction to liblphp.so
	$retstg = send_stg($xml, $cert, $host, $port);
	
	# look at the xml that comes back
	print ("response:  $retstg\n\n");
	
	if (strlen($retstg) < 2)    # no response
	{
		$retstg = "<r_error>Could not execute curl.</r_error>"; 
	}
	
	# Process the XML from here 
	
	
	# Or OPTIONALLY - you could convert XML to a readable array
	preg_match_all ("/<(.*?)>(.*?)\</", $retstg, $outarr, PREG_SET_ORDER);
	
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
