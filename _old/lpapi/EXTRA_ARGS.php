<?php 

/**********************************************************************\

	XTRA_ARGS.php -  Simple SALE transaction demonstrating various optional
	arguments that can passed into the LPHP.PHP module

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
	$myorder["result"]       = "GOOD"; # For a test, set result to GOOD, DECLINE, or DUPLICATE
	$myorder["cardnumber"]   = "4111-1111-1111-1111";
	$myorder["cardexpmonth"] = "01";
	$myorder["cardexpyear"]  = "05";
	$myorder["chargetotal"]  = "9.99";


/* FIELD NAME:		cbin - Curl Binary
DEFAULT VALUE:		false
  EXPLANATION:		Curl binary - tells the module to use curl binary executable, as opposed to the
				    PHP built-in curl methods. This should be used only when (often older)
				    PHP version does not support curl directly so we have to shell out.  
EXAMPLE USAGE: 		*/
	$myorder["cbin"] = "true";
 
 
/* FIELD NAME:		cpath - Curl Path
DEFAULT VALUE:      /usr/bin/curl
  EXPLANATION:      Use this if curl binary executable is in a nonstandard location
					Only to be used in conjunction with "cbin" argument.
EXAMPLE USAGE: 		*/ 	
//	$myorder["cpath"] = "/usr/local/bin/curl";

 
/* FIELD NAME:		cargs -  Curl Arguments
DEFAULT VALUE:		-m 300 -s -S
  EXPLANATION:		Curl arguments - only if using binary curl ( cbin above).
					You need to enter the complete string if you over-ride the default.
					For more info: http://curl.haxx.se/docs/manpage.html.
                    Note: *don't use this field unless you have a specific reason and 
					know exactly what you're doing*
EXAMPLE USAGE: 		To decrease the timeout to 2 minutes (-m) and disable certificate
					verification (-k) use this string:*/ 	
	$myorder["cargs"] = "-m 120 -k -s -S";


/* FIELD NAME:		xml - use XML processing
DEFAULT VALUE:		none
  EXPLANATION:		If this field is present, we are passing an xml string directly
					into lphp.php and the server response will also be in xml format.
					Hash values passed in will be ignored, except for host, port and keyfile.
EXAMPLE USAGE: 		See the LinkPoint sample "PASS_XML.php" for example usage. */ 	
//	$myorder["xml"] = "<order><orderoptions><result>GOOD</result><ordertype>SALE</ordertype></orderoptions><merchantinfo><configfile>909001</configfile></merchantinfo><creditcard><cardnumber>4111111111111111</cardnumber><cardexpmonth>12</cardexpmonth><cardexpyear>08</cardexpyear></creditcard><payment><chargetotal>1.03</chargetotal></payment></order>";


/* FIELD NAME:		debugging
DEFAULT VALUE:		false
  EXPLANATION:		Prints values as they are processed by module. It also turns on 
					verbose connection output if curl methods are being used.
					Debugging is for development only - not intended for production use
EXAMPLE USAGE: 		*/ 	
	$myorder["debugging"] = "true";


/* FIELD NAME:		webspace
DEFAULT VALUE:		true
  EXPLANATION:		When viewing PHP output through a web browser, all xml tags are 
					removed. This PHP call allows you to view debugging output with XML tag
					names.  If running PHP directly from the command line (not in browser),
					set this field to false so as to see complete xml output.
EXAMPLE USAGE: 		*/ 	
//	$myorder["webspace"] = "false";


  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);		# use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods


	if ($myorder["xml"])	// we're processing xml string response
	{
		# break it into array
		preg_match_all ("/<(.*?)>(.*?)\</", $result, $outarr, PREG_SET_ORDER);

		$n = 0;
		while (isset($outarr[$n]))
		{
			$retarr[$outarr[$n][1]] = strip_tags($outarr[$n][0]);
			$n++;
		}

		# and then look at it like this
		while (list($key, $value) = each($retarr))
		{
			if($myorder["htmlspecialchars"] == "false")
			{
				echo "$key = $value\n";
			}
			else
			{
				echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<br>\n";
			}				
		}
	}
	else		// we're processing a hash response
	{
		if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
		{	
			print "Approved: $result[r_approved]\n";
			print "Error: $result[r_error]\n";
		}
		else
		{	// success
			print "Approved: $result[r_approved]\n";
			print "Code: $result[r_code]\n";
			print "OID: $result[r_ordernum]\n\n";
		}

		// Look at returned hash & use the elements you need //
/*		while (list($key, $value) = each($result))
		{
			if(!$myorder["webspace"]){
				echo "$key = $value\n";}
			else{
				echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<br>\n";}
		}
*/
	}
?>
