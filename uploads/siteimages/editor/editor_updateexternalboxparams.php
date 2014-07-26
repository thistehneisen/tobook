<?php 
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add external box param values to session							                          |
// +----------------------------------------------------------------------+
	
	include "../includes/session.php";
	$currentPage = $_SESSION['siteDetails']['currentpage'];

	// get the external app details
	 $appId = $_POST['extboxname'];
 
		// code to iterate the external box parameters
		foreach ($_POST as $position => $item) {
 		
			$arrPos = explode('_',$position);
			if($arrPos[0] == 'txteditoredit'){		
				//unset( $_SESSION['siteDetails'][$currentPage]['apps']);
				// assign the parameter details to session
				$paramid = $arrPos[1]; 
				$_SESSION['siteDetails'][$currentPage]['apps'][$appId][$paramid] = $item;
				// parameter assigning ends
			}
		}
	echo '1';
?>
 