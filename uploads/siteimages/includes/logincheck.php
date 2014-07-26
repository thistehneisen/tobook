<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>	        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
//if session expired redirect to login page
if(!isset($_SESSION["session_userid"]) || $_SESSION["session_userid"]==""){
        header("Location:login.php");
        exit;
}
?>