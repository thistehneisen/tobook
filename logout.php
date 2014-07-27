<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php

$curTab = 'logout';

//include files
include "includes/session.php";

//remove session parameters
$_SESSION["session_username"] = "";
$_SESSION["session_affiliatename"] = "";
$_SESSION["session_affiliate"] = "";
$_SESSION['owner_id'] = "";
session_unset();
session_destroy();
header("Location:index.php");
exit;
?>
