<?php
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

$templateid=$_GET['id'];
$type=$_GET['type'];
$prtype=$_GET['prtype'];
if(!is_dir("../".$_SESSION["session_template_dir"]."/$templateid/")){
	  echo "Template could not found.";
	  exit;
}
/* if site is simple*/
if($type=="simple"){
  showtplpriview($templateid, "$prtype", "..");
  header("location:../".$_SESSION["session_template_dir"]."/$templateid/priview.htm");
  exit;
}else{
	if($prtype=="index"){
  			header("location:../".$_SESSION["session_template_dir"]."/$templateid/index.htm");
			  exit;
	}else{
	     header("location:../".$_SESSION["session_template_dir"]."/$templateid/sub.htm");
		   exit;
	}		
}

?>