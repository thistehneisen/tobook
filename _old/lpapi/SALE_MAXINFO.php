<?php 

/**********************************************************************\

	SALE_MININFO.php -  Minimum Required Fields for a Credit Card SALE


	Copyright 2003 LinkPoint International, Inc. All Rights Reserved.

	This software is the proprietary information of LinkPoint International, Inc.  
	Use is subject to license terms.

\**********************************************************************/


	include"lphp.php";
	$mylphp=new lphp;

	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "./YOURCERT.pem"; # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "1234567";        # Change this to your store number 

	$myorder["ordertype"]         = "SALE";
	$myorder["result"]            = "LIVE";           # For test transactions, set to GOOD, DECLINE, or DUPLICATE
	$myorder["transactionorigin"] = "MOTO";           # For credit card retail txns, set to RETAIL, for Mail order/telephone order, set to MOTO, for e-commerce, leave out or set to ECI
	$myorder["oid"]               = "12345678-A345";  # Order ID number must be unique. If not set, gateway will assign one.
	$myorder["ponumber"]          = "09876543-Q1234";
	$myorder["taxexempt"]         = "N";
	$myorder["terminaltype"]      = "UNSPECIFIED";    # Set terminaltype to POS for an electronic cash register or integrated POS system, STANDALONE for a point-of-sale credit card terminal, UNATTENDED for a self-service station, or UNSPECIFIED for e-commerce or other applications
	$myorder["ip"]                = "123.123.123.123";

	$myorder["subtotal"]    = "12.99";
	$myorder["tax"]         = "0.34";
	$myorder["shipping"]    = "1.45";
	$myorder["vattax"]      = "0.00";
	$myorder["chargetotal"] = "14.78";

	# CARD INFO
	$myorder["cardnumber"]   = "4111-1111-1111-1111";
	$myorder["cardexpmonth"] = "01";
	$myorder["cardexpyear"]  = "05";
	$myorder["cvmindicator"] = "provided";
	$myorder["cvmvalue"]     = "123";

	# BILLING INFO
	$myorder["name"]     = "Joe Customer";
	$myorder["company"]  = "Joe's Garage";
	$myorder["address1"] = "123 Broadway";
	$myorder["address2"] = "Suite 23v";
	$myorder["city"]     = "Moorpark";
	$myorder["state"]    = "CA";
	$myorder["country"]  = "US";
	$myorder["phone"]    = "8051234567";
	$myorder["fax"]      = "8059876543";
	$myorder["email"]    = "joe.customer@somewhere.com";
	$myorder["addrnum"]  = "123";   # Required for AVS. If not provided, transactions will downgrade.
	$myorder["zip"]      = "87123"; # Required for AVS. If not provided, transactions will downgrade.

	# SHIPPING INFO
	$myorder["sname"]     = "Joe Customer";
	$myorder["saddress1"] = "123 Broadway";
	$myorder["saddress2"] = "'Suite 23";
	$myorder["scity"]     = "Moorpark";
	$myorder["sstate"]    = "CA";
	$myorder["szip"]      = "12345";
	$myorder["scountry"]  = "US";

	# ITEMS AND OPTIONS
	$items = array (
		'id' 			=> '123456-A98765',
		'description' 	=> 'Logo T-Shirt',
		'quantity' 		=> '1',
		'price'			=> '12.99',
		'options' => array
				  (  
				  'name' => 'Color',
				  'value'=> 'Red'
				  ),
		'options2' => array
				(
				'name' => 'Size',
				'value' => 'XL'
				)
		);

	$myorder["items"][0] = $items; # put array of items into hash

	# you could also submit this same item w/ options like this:
/*	
	$myorder["items"][item1]["id"]          = "123456-A98765";
	$myorder["items"][item1]["description"] = "Logo T-Shirt";
	$myorder["items"][item1]["quantity"]    = "1";
	$myorder["items"][item1]["price"]       = "12.99";

		# pass options in like this:
		$myorder["items"][item1]["option1"]["name"]  = "Color";
		$myorder["items"][item1]["option1"]["value"] = "Red";
		$myorder["items"][item1]["option2"]["name"]  = "Size";
		$myorder["items"][item1]["option2"]["value"] = "XL";

		# or you could alternately pass the same options in like this:
#		$myorder["items"][item1][0]["name"]  = "Color";
#		$myorder["items"][item1][0]["value"] = "Red";
#		$myorder["items"][item1][1]["name"]  = "Size";
#		$myorder["items"][item1][1]["value"] = "XL";
*/


	# MISC
	$myorder["comments"] = "Repeat customer. Ship immediately.";
	$myorder["referred"] = "Saw ad on Web site.";
//	$myorder["debugging"] = "true";  # for development only - not intended for production use


  # Send transaction. Use one of two possible methods  #
//	$result = $mylphp->process($myorder);       # use shared library model
	$result = $mylphp->curl_process($myorder);  # use curl methods


	if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
	{
		print "Status: $result[r_approved]\n";
		print "Error: $result[r_error]\n";
	}
	else
	{					// success
		print "Status: $result[r_approved]\n";
		print "Code: $result[r_code]\n";
		print "OID: $result[r_ordernum]\n\n";
	}

/*
	# Look at returned hash & use the elements you need  #
	while (list($key, $value) = each($result))
	{
		echo "$key = $value\n"; 

	# (if you're in web space, look at response like this):
		 echo htmlspecialchars($key) . " = " . htmlspecialchars($value) . "<BR>\n";
	}
*/
?>
