<?php

/**********************************************************************\

#   MULTIPLE.php - Sample of multiple processing
#
#   This sample demonstrates running an array of PREAUTH transactions.
#
#   This could equally well be a series of POSTAUTHS or RETURNS if the 
#   OIDs match those previously approved.

   Note: if you are using the shared object LIBLPHP.SO and are getting
   PHP warings "Function registration failed..." while processing batches,
   you can add this entry to your php.ini file: 
			extension=liblphp.so
   and then comment out the lines to load the library at LIBLPHP.PHP 
   module at about line # 57.

# Copyright 2003 LinkPoint International, Inc. All Rights Reserved.
#
# This software is the proprietary information of LinkPoint International, Inc.  
# Use is subject to license terms.

\***********************************************************************/


	include"lphp.php";
	$mylphp=new lphp;

	$myorder["host"]       = "secure.linkpt.net";
	$myorder["port"]       = "1129";
	$myorder["keyfile"]    = "./YOURCERT.pem"; # Change this to the name and location of your certificate file 
	$myorder["configfile"] = "1234567";        # Change this to your store number 

	$myorder["ordertype"]    = "PREAUTH";
	$myorder["result"]       = "good"; # For a test, set result to GOOD, DECLINE, or DUPLICATE
//	$myorder["debugging"] = "true";


	# build array of individual orders
	$order0 = array ( 	
		'chargetotal'  => '1.11',
		'cardnumber'   => '4111-1111-1111-1111',
		'cardexpmonth' => '01',
		'cardexpyear'  => '04',
		'addrnum'      => '123',   # Required for AVS. If not provided, transactions will downgrade.
		'zip'          => '12345', # Required for AVS. If not provided, transactions will downgrade.
#		'oid'          => '12345678-A1'
		);

	$myorder["orders"][0] = $order0; # put array into hash

	$order1 = array ( 	
		'chargetotal'  => '2.22',
		'cardnumber'   => '4111-1111-1111-1111',
		'cardexpmonth' => '01',
		'cardexpyear'  => '02',
		'addrnum'      => '123',   # Required for AVS. If not provided, transactions will downgrade.
		'zip'          => '23456', # Required for AVS. If not provided, transactions will downgrade.
#		'oid'          => '12345678-B2'
		);

	$myorder["orders"][1] = $order1; # put array into hash

	$order2 = array ( 	
		'chargetotal'  => '3.33',
		'cardnumber'   => '4111-1111-1111-1111',
		'cardexpmonth' => '01',
		'cardexpyear'  => '04',
		'addrnum'      => '123',   # Required for AVS. If not provided, transactions will downgrade.
		'zip'          => '34567', # Required for AVS. If not provided, transactions will downgrade.
#		'oid'          => '12345678-C3'
		);

	$myorder["orders"][2] = $order2; # put array into hash


	# PROCESS ORDERS ARRAYS #
		
	while (list ($key, $val) = each ($myorder))
	{	
		if (is_array($val))
		{
			$orders_array = $val;
			
			while (list ($akey, $aval) = each ( $orders_array))
			{
				$oa2 = $aval;
				
				# CONSTANTS	
				$txn["host"]       = $myorder["host"];
				$txn["port"]       = $myorder["port"];
				$txn["keyfile"]    = $myorder["keyfile"];
				$txn["configfile"] = $myorder["configfile"];
				$txn["debugging"]  = $myorder["debugging"];                    
					
				# INDIVIDUAL ORDER ELEMENTS		
				$txn["chargetotal"]  = $oa2[chargetotal];
				$txn["cardnumber"]   = $oa2[cardnumber];
				$txn["cardexpmonth"] = $oa2[cardexpmonth];
				$txn["cardexpyear"]  = $oa2[cardexpyear];
				$txn["addrnum"]      = $oa2[addrnum];
				$txn["zip"]          = $oa2[zip];
				$txn["oid"]          = $oa2[oid];

			  # Send transaction. Use one of two possible methods  #
				$result = $mylphp->process($txn);		# use shared library model
			//	$result = $mylphp->curl_process($txn);  # use curl methods
				
				if ($result["r_approved"] != "APPROVED")	// transaction failed, print the reason
				{	
					print "Status: $result[r_approved]\n";
					print "Error: $result[r_error]\n";
				}
				else
				{	// success
					print "Status: $result[r_approved]\n";
					print "Code: $result[r_code]\n";
					print "OID: $result[r_ordernum]\n";
				}
			}
		}
	}
?>
