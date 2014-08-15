<?php

# PASS_XML.pl - example of how to send and recieve an XML string
#
# In this sample, the merchant would be doing their own XML encoding/decoding
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

	
	include"lphp.php";
	$mylphp=new lphp;


/*	The formatting of the XML in this sample is only for example 
	purposes and human-readability; in real life it would typically
	be all one long unbroken line. */
	$xml ="
    <order>
        <orderoptions>
            <result>GOOD</result>
            <ordertype>SALE</ordertype>
        </orderoptions>
        <merchantinfo>
            <configfile>1234567</configfile> <!-- CHANGE THIS TO YOUR STORE NUMBER -->
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

	$myorder["host"]      = "secure.linkpt.net"; 
	$myorder["port"]      = "1129";
	$myorder["keyfile"]   = "./YOURCERT.pem"; # change this to the name and location of your certificate file
	$myorder["xml"]       = $xml;
//  $myorder["debugging"] = "true"; # for development only; not intended for production use


  # Send transaction. Use one of two possible methods #
//	$result = $mylphp->process($myorder);		# use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods

	if (strlen($result) < 2)    # no response
	{
		$result = "<r_error>Could not execute curl.</r_error>"; 
	}
    echo "Response: $result\n";

	# Process the XML from here.... 
	

	
	# Or OPTIONALLY - you could convert XML response to a readable array
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
