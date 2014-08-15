<?php
echo"<html><head><title>PHP_FORM_MIN.php LinkPoint Sample </title></head><body><br>";
/*

<!---------------------------------------------------------------------------------
* PHP_FORM_MAX.php - A form processing example showing many of the possible fields
*
* This script processes form data passed in from PHP_FORM_MAX.html
*
*
* Copyright 2003 LinkPoint International, Inc. All Rights Reserved.
* 
* This software is the proprietary information of LinkPoint International, Inc.  
* Use is subject to license terms.
*

		This program is based on the sample SALE_MAXINFO.php
		See comments in that file for many field descriptions.
		
		Depending on your server setup, this script may need to
		be placed in the cgi-bin directory, and the path in the
		calling file PHP_FORM_MAX.html may need to be adjusted
		accordingly.

		NOTE: older versions of PHP and in cases where the PHP.INI
		entry is NOT "register_globals = Off", form data can be
		accessed simply by using the form-field name as a varaible
		name, eg. $myorder["host"] = $host, instead of using the 
		global $_POST[] array as we do here. Passing form fields 
		as demonstrated here provides a higher level of security.

------------------------------------------------------------------------------------>
*/
	include"lphp.php";
	$mylphp=new lphp;

	# constants
	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "./YOURCERT.pem"; # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "1234567";        # Change this to your store number 

	# transaction details
	$myorder["ordertype"]         = $_POST["ordertype"];
	$myorder["result"]            = $_POST["result"];
	$myorder["transactionorigin"] = $_POST["transactionorigin"];
	$myorder["oid"]               = $_POST["oid"];
	$myorder["ponumber"]          = $_POST["ponumber"];
	$myorder["taxexempt"]         = $_POST["taxexempt"];
	$myorder["terminaltype"]      = $_POST["terminaltype"];
	$myorder["ip"]                = $_POST["ip"];

	# totals
	$myorder["subtotal"]    = $_POST["subtotal"];
	$myorder["tax"]         = $_POST["tax"];
	$myorder["shipping"]    = $_POST["shipping"];
	$myorder["vattax"]      = $_POST["vattax"];
	$myorder["chargetotal"] = $_POST["chargetotal"];

	# card info
	$myorder["cardnumber"]   = $_POST["cardnumber"];
	$myorder["cardexpmonth"] = $_POST["cardexpmonth"];
	$myorder["cardexpyear"]  = $_POST["cardexpyear"];
	$myorder["cvmindicator"] = $_POST["cvmindicator"];
	$myorder["cvmvalue"]     = $_POST["cvmvalue"];

	# BILLING INFO
	$myorder["name"]     = $_POST["name"];
	$myorder["company"]  = $_POST["company"];
	$myorder["address1"] = $_POST["address1"];
	$myorder["address2"] = $_POST["address2"];
	$myorder["city"]     = $_POST["city"];
	$myorder["state"]    = $_POST["state"];
	$myorder["country"]  = $_POST["country"];
	$myorder["phone"]    = $_POST["phone"];
	$myorder["fax"]      = $_POST["fax"];
	$myorder["email"]    = $_POST["email"];
	$myorder["addrnum"]  = $_POST["addrnum"];
	$myorder["zip"]      = $_POST["zip"];

	# SHIPPING INFO
	$myorder["sname"]     = $_POST["sname"];
	$myorder["saddress1"] = $_POST["saddress1"];
	$myorder["saddress2"] = $_POST["saddress2"];
	$myorder["scity"]     = $_POST["scity"];
	$myorder["sstate"]    = $_POST["sstate"];
	$myorder["szip"]      = $_POST["szip"];
	$myorder["scountry"]  = $_POST["scountry"];

	# MISC
	$myorder["comments"] = $_POST["comments"];
	$myorder["referred"] = $_POST["referred"];

	# ITEMS AND OPTIONS
	# there are several ways to pass items and options; see sample SALE_MAXINFO.php

	$myorder["items"][item1]["id"]          = $_POST["id"];
	$myorder["items"][item1]["description"] = $_POST["description"];
	$myorder["items"][item1]["quantity"]    = $_POST["quantity"];
	$myorder["items"][item1]["price"]       = $_POST["price"];

	$myorder["items"][item1]["option1"]["name"]  = $_POST["name1"];
	$myorder["items"][item1]["option1"]["value"] = $_POST["value1"];
	$myorder["items"][item1]["option2"]["name"]  = $_POST["name2"];
	$myorder["items"][item1]["option2"]["value"] = $_POST["value2"];

	if ($_POST["debugging"])
		$myorder["debugging"]="true";


#   Send transaction. Use one of two possible methods 
//	$result = $mylphp->process($myorder);       # use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods


	if ($result["r_approved"] != "APPROVED")    // transaction failed, print the reason
	{
		print "Status:  $result[r_approved]<br>\n";
		print "Error:  $result[r_error]<br><br>\n";
	}
	else	// success
	{		
		print "Status: $result[r_approved]<br>\n";
		print "Transaction Code: $result[r_code]<br><br>\n";
	}

# if verbose output has been checked,
# print complete server response to a table
	if ($_POST["verbose"])
	{
		echo "<table border=1>";

		while (list($key, $value) = each($result))
		{
			# print the returned hash 
			echo "<tr>";
			echo "<td>" . htmlspecialchars($key) . "</td>";
			echo "<td><b>" . htmlspecialchars($value) . "</b></td>";
			echo "</tr>";
		}
		
		echo "</table><br>\n";
	}
?>

</body></html>
