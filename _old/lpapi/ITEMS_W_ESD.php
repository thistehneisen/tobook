<?php 

/********************************************************************************\

	ITEMS_W_ESD.php -  Required Fields for an Example Credit Card SALE with ITEMS
  
    Copyright 2003 LinkPoint International, Inc. All Rights Reserved.

	This software is the proprietary information of LinkPoint International, Inc.  
	Use is subject to license terms.

\********************************************************************************/


	include"lphp.php";
	$mylphp=new lphp;

	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "./YOURCERT.pem"; # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "1234567";        # Change this to your store number 

	$myorder["ordertype"]    = "SALE";
	$myorder["subtotal"]     = "45.98";
	$myorder["tax"]          = "0.32";
	$myorder["shipping"]     = "1.02";
	$myorder["chargetotal"]  = "47.32";
	$myorder["cardnumber"]   = "4111-1111-1111-1111";
	$myorder["cardexpmonth"] = "01";
	$myorder["cardexpyear"]  = "05";
	$myorder["name"]         = "Joe Customer"; # If you use ESD items, name is required
	$myorder["addrnum"]      = "123";          # Required for AVS. If not provided, transactions will downgrade.
	$myorder["zip"]          = "87123";        # Required for AVS. If not provided, transactions will downgrade.


	# ITEMS AND OPTIONS
	$item0 = array (
		'id' 			=> '123456-A98765',
		'description' 	=> 'Logo T-Shirt',
		'quantity' 		=> '1',
		'price'			=> '12.99',
		'serial'		=> '0987654321',
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
		
	$myorder["items"][0] = $item0; # put array of items/options into hash
	
	# ITEM w/ ESD
    $item1 = array (
		'id' 			=> '123456-B98765',
		'description' 	=> 'Blast-Em Software',
		'quantity' 		=> '1',
		'price'			=> '12.99',
		'serial'		=> '0987654321',
        'esdtype'       => 'Esdtype_Softgood',
        'softfile'      => 'blastemgame.exe',
		);
		
	$myorder["items"][1] = $item1; # put item array into hash
	

	# you could also submit the same item w/options like this:
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

		# or you could alternately build arrays like this:
#		$myorder["items"][item1][0]["name"]  = "Color";
#		$myorder["items"][item1][0]["value"] = "Red";
#		$myorder["items"][item1][1]["name"]  = "Size";
#		$myorder["items"][item1][1]["value"] = "XL";
*/


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
