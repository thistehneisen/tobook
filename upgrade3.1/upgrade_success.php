<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : index.php                                                |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: BINU CHANDRAN.E<binu.chandran@armiasystems.com>              |
// +----------------------------------------------------------------------+
// | Modified: ARUN SADASIVAN (01/07/2012)								  |
// |----------------------------------------------------------------------+
// | Copyrights Armia Systems � 2010                                      |
// | All rights reserved                                                  |
// +----------------------------------------------------------------------+
// | This script may not be distributed, sold, given away for free to     |
// | third party, or used as a part of any internet services such as      |
// | webdesign etc.                                                       |
// +----------------------------------------------------------------------+
error_reporting(0);
ob_start();
include_once('../includes/install_config.php');

/*
 * get the path dynamically
 * NOTE:- need to add www via htaccess if required
 */
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) . $s . "://";
define('ROOT_URL', $protocol);

///Generating Root URL

$current_path = getcwd();
$current_path=rtrim($current_path,"/");
$current_path=$current_path."/";
$current_path=str_replace("\\","/",$current_path);

$DocumentRoot=$_SERVER["DOCUMENT_ROOT"];
$DocumentRoot=rtrim($DocumentRoot,"/");
$DocumentRoot=$DocumentRoot."/";
$DocumentRoot=str_replace("\\","/",$DocumentRoot);

$path=str_replace($DocumentRoot,"",$current_path);
$path=str_replace('upgrade3.1/', '', $path);
$root_url = ROOT_URL . $_SERVER['SERVER_NAME'] ."/".$path;

//add trailing slashes
if ($root_url[strlen($root_url) - 1] <> '/') {
    $root_url .= '/';
}

define('BASE_URL', $root_url);

$upgraderTitle = 'iScripts EasyCreate Upgrader';
$productName = 'EasyCreate';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo $upgraderTitle; ?></title>
    </head>
    <script type="text/javascript" src="../js/jquery.min.js"></script>
    <script type="text/javascript" src="../js/install.js"></script>
    <link href="css/install.css" rel="stylesheet" type="text/css" />
    <body>
        <div class="header_row">
            <div class="header_container  sitewidth">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
                    <tr>
                        <td width="23%" align="left"><img src="css/ec.png" alt="Logo"></td>
                        <td width="77%" align="right">
                            <h4><?php echo $upgraderTitle; ?></h4>
                            <div align="center" id="items_top_area">
                                &nbsp;&nbsp;
                                <a title="OnlineInstallationManual" href="#" onClick="window.open('<?php echo BASE_URL; ?>project/Docs/Installation_Manual.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Installation manual</strong></a> |
                                <a title="Readme" href="#" onClick="window.open('<?php echo BASE_URL; ?>project/Docs/Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Readme</strong></a> |
                                <a title="If you have any difficulty, submit a ticket to the support department" href="#" onClick="window.open('http://www.iscripts.com/support/postticketbeforeregister.php','','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd,resizable=yes');">
                                <strong>Get Support</strong></a>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="css/spacer.gif" width="1" height="5"></td>
            </tr>
        </table>
        <table width="80%" border="0" align="center">
            <tr>
                <td>
                    <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext align="center">
                        <br>
                        <tr>
                            <td align="center" class="maintext" >
                                <font color="#F4700E" size="+1">Congratulations! The Upgrade Process Was Completed Successfully!</font>
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="maintext" height="20" >&nbsp;</td>
                        </tr>                        
                       					
                        <tr>
                            <td align="center">
                                <br>
                                <fieldset>
                                    <legend class="block_class">Site Login Details</legend>
                                    <table cellpadding="0" cellspacing="0" width="95%" class="maintext" align="center">
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        
                                        <tr>
                                            <td width="26%"><b><font size="-1">Admin URL&nbsp;:</font></b></td>
                                            <td width="74%"><a style="cursor:pointer;text-decoration: none;" href="<?php echo BASE_URL . "admin" ?>"><img src="css/admin_login_install.jpg" border="0" height="25" align="absmiddle">&nbsp; &nbsp; <?php echo BASE_URL . "admin"; ?></a></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <!--tr>
                                            <td  valign="top"><b><font size="-1">Admin Credentials&nbsp;:</font></b></td>
                                            <td  valign="top">
                                                <div class="adm_cred">
                                                    <font size="-1">Username&nbsp;:&nbsp;admin</font><br/><br/>
                                                    <font size="-1">Password&nbsp;:&nbsp;admin</font>
                                                </div>
                                            </td>
                                        </tr-->
                                        <tr>
                                            <td colspan="2">&nbsp;</td>
                                        </tr>
                                        <tr>
                                            <td ><b><font size="-1">Home URL&nbsp;:</font></b></td>
                                            <td ><a style="cursor:pointer;text-decoration: none;" href="<?php echo BASE_URL; ?>"><img src="css/home_page.jpg" border="0" height="25" align="absmiddle">&nbsp; &nbsp;<?php echo BASE_URL; ?></a></td>
                                        </tr>
                                    </table>
                                </fieldset>	
                            </td>
                        </tr>
                        <tr>
                            <td align="center" class="maintext" height="20" >&nbsp;</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <div class="installr_footer"></div>
    </body>
</html>