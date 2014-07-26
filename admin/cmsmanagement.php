<?php

include "../includes/config.php";
include "../includes/session.php";
include "includes/adminheader.php";
if (basename($_SERVER['PHP_SELF']) <> "index.php") {
	if(($_SESSION["session_username"] == "" || $_SESSION["session_username"] != "admin") && basename($_SERVER["REQUEST_URI"]) != "index.php" ){
					header("location:index.php");
	}
}
if($_SESSION["session_username"] == "admin" && $_SERVER['HTTP_REFERER'] == "" && basename($_SERVER["REQUEST_URI"]) != "dashboard.php"){
                header("location:dashboard.php");
}

if(!empty($_POST)){
    if(isset($_POST['cmsSubmit'])){ 

      $section_name = $_REQUEST['section_name'];
      $section_title= $_REQUEST['section_title'];
      $section_help = $_REQUEST['section_help'];
      $section_content = $_REQUEST['section_content'];
      $section_price = $_REQUEST['section_price'];
      
      $updateQuery  = "UPDATE tbl_cms SET section_help='$section_help', section_title = '$section_title',section_content=  '$section_content', section_price = '$section_price', section_status=1 WHERE section_name = '$section_name'";
      mysql_query($updateQuery);
    }
    
    $_SESSION['successMsg'] =   MSG_UPDATED_CONTENTS;
    header("Location:cmslisting.php");
}
?>