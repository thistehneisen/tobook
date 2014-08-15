<?php 
	if (isset($_COOKIE['CUSTOMER_TOKEN'])) {
		header("location: main.php");
	} else {
		header("location: login.php");
	}
?>