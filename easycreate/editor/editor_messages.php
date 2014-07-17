<?php   
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2012-2013 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 2                 |
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
include "../includes/session.php";
include "../includes/config.php"; 

$messageType 	= $_GET['messageType'];
$message 	= $_GET['message'];
?>
<link rel="stylesheet" href="../style/editor_styles.css" type="text/css">
<div class="popupcontent_newwrapper">
    <div id="editor_htmleditor" class="<?php echo $messageType?>"><?php echo $message; ?></div>
</div>


