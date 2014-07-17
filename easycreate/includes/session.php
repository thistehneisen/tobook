<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                              |
// |                                                                                                            |
// +----------------------------------------------------------------------+
session_start();
function stripslashes_deep($value)
{
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    return $value;
}
ini_set('magic_quotes_runtime', 0);
if (get_magic_quotes_gpc()) {

    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);

}

?>
