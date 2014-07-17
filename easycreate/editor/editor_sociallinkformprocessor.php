<?php 
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>		      		              |
// | Page to add social link datatypes			                          |
// +----------------------------------------------------------------------+
 

	include "../includes/session.php";
	$currentPage = $_SESSION['siteDetails']['currentpage'];

	// get the social link app details
        $i=0;
	$dataTypeId = $_POST['extboxname'];
		foreach ($_POST as $position => $item) {		
			$arrPos = explode('_',$position);
			if($arrPos[0] == 'txtsociallink'){		
				$paramid = $arrPos[1]; 
				/******* Modified for common footer in all pages ***/
				//$_SESSION['siteDetails'][$currentPage]['datatypes']['socialshare'][$dataTypeId][$paramid]['link'] = $item;
                                $_SESSION['siteDetails']['commonpanel']['socialshare'][$i]['link'] = $item;
                                 /******* Modified for common footer in all pages ***/
                                $i++;
			}
		}
	echo '1';
?>
 