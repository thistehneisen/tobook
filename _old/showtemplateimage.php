<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
//echo"helo";exit;
include "includes/session.php";
include "includes/function.php";
$templateid = isset($_GET['tmpid']) ? $_GET['tmpid'] : '';
$type       = isset($_GET['type']) ? $_GET['type'] : 'thumb';
$imgname       = isset($_GET['imgname']) ? $_GET['imgname'] : '';
if($type=="home"){
  //$imagename="homepageimage.jpg";
    $imagename  =   $imgname;
}else if($type=="sub"){
  //$imagename="subpageimage.jpg";
    $imagename  =   $imgname;
}
else if($type=="theme"){
 	$imagename = isset($_GET['image']) ? $_GET['image'] : '';
}

else if($type=="thumb"){
    
  //$imagename="thumpnail.jpg";
    $imagename  =   $imgname;
}
 
readfile("./".$_SESSION["session_template_dir"]."/".$templateid."/$imagename");

?>