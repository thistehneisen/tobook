<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php

include "../includes/function.php";
$imgname=$_GET['imagename'];
getResizedImage("../systemgallery/".$imgname,50);
include "includes/adminheader.php";
?>

