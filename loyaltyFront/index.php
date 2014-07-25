<?php 
	if( isset( $_COOKIE['CUSTOMER_TOKEN'] ) ){
		header("location: home.php");
	}else{
		header("location: login.php");
	}
?>