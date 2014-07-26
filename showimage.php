<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
ini_set("memory_limit","256M");
//include files
include "includes/session.php";
include "includes/function.php";

//get data from the form
$imgname=$_GET['imagename'];
    list($width, $height, $type, $attr) = @getimagesize($imgname);
	if($type >3){
	  $Invalidimage="TRUE";
	  $invalidmessage="Image Not Supported";
	}else if($width>1300){
	   $Invalidimage="TRUE";
	  readfile($imgname);
	}else if($height>1300){
	   $Invalidimage="TRUE";
	    readfile($imgname);
	}


//chmod("./usergallery/".$_SESSION["session_userid"]."/images",0777);
$tempname="./usergallery/".$_SESSION["session_userid"]."/images/"."thumbimages";
//echo $tempname;
Resizeimage($imgname, 50, 100,$tempname);
readfile("./usergallery/".$_SESSION["session_userid"]."/images/thumbimages");

?>