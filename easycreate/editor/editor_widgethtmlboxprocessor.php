<?php 
	/*
	 *  page to update the exteranl html widget
	 */
 
	include "../includes/session.php";
	$currentPage 	= $_SESSION['siteDetails']['currentpage'];
	$htmlBoxName 	= $_POST['params'];
	$panelboxhtml 	= $_POST['panelboxhtml'];
	if($htmlBoxName != ''){
		$_SESSION['siteDetails'][$currentPage]['apps'][$htmlBoxName] = $panelboxhtml;
		echo "success";
	}
?>