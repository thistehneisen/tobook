<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+


echo "Before user addition <br>";





//====================== Supportdesk User Addition ==================

include("useradd.php");

userAdd('login-name','password','first-name','email-address');

//====================== End Supportdesk User Addition ==============



echo "After user addition <br>";


?>