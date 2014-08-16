<?php
	if (!headers_sent())
	{
		@session_start();
	}
	$username = $_GET['username'];
	$_SESSION['session_loginname'] = $username;
	header("location: index.php");
?>
