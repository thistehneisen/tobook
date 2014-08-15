<?php
ob_start(); 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | File name : index.php                                                |
// | PHP version >= 5.2                                                   |
// +----------------------------------------------------------------------+
// | Author: Programmer@program.com>              |
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

include_once('../includes/install_config.php');
include_once('cls_serverconfig.php');
include_once('../includes/configsettings.php'); 

/*
 * get the path dynamically
 * NOTE:- need to add www via htaccess if required
*/
$s = empty($_SERVER["HTTPS"]) ? '' : ($_SERVER["HTTPS"] == "on") ? "s" : "";
$protocol = substr(strtolower($_SERVER["SERVER_PROTOCOL"]), 0, strpos(strtolower($_SERVER["SERVER_PROTOCOL"]), "/")) .$s . "://";
define('ROOT_URL', $protocol );

//Generating Root URL

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
if($root_url[strlen($root_url) - 1] <> '/') {
    $root_url .= '/';
}
define('BASE_URL_PART',$path);
define('BASE_URL', $root_url);
$secure_root_url = $root_url;
$secure_root_url = str_replace('http:', 'https:', $secure_root_url);

$perm_flag = true;
$perm_msg = '';
$error_message = '';
$error = false;
$installed = false;
$user_data = array();
$post_flag = false;

$configfile     = "../includes/install_config.php";
$settingsfile   = "../includes/configsettings.php";
$schemafile     = "sql/schema.sql";
$datafile       = "sql/data.sql";

$directories = array(
        "workarea/",
        "workarea/sites/",
        "sites/",
        "uploads/",
        "uploads/siteimages/",
        "uploads/siteimages/banners/",
        "uploads/themes/",
        "templates/",
        "samplelogos/",
        "systemgallery/",
        "usergallery/",
        "categoryicons/",
        "includes/install_config.php",
        "includes/configsettings.php");

//$serverOS       = ServerConfig::getServerOS();
$serverSettings = ServerConfig::checkServerConfiguration();
$serverCurrentSettings = ServerConfig::getServerSettings(); 

$host_name = parse_url($_SERVER['HTTP_HOST']);
foreach ($directories as $dir) {
    $permission = ServerConfig::fileWritable($dir,$dir);
    if (!$permission['status'] && $error == false) {
        $error = true;
        $serverPermission="true";
    }

}


if (isset($_POST['upgraderCheck'])) {
    $post_flag = true;

    //For Getting Folder Permission Status and Proper Error messages

    foreach ($directories as $dir) {
        $permission = ServerConfig::fileWritable($dir,$dir);
        if (!$permission['status'] && $error == false) {
            $error = true;
            $serverPermission="true";
        }

    } 
    if ($error == true) { //echo '<pre>'; print_r($_POST); echo '</pre>'; exit;
        if (isset($_POST["btnContinue"]) && !isset($_POST["auto_set"])) {
            $txtFTPusername = $_POST['FTPusername'];
            $txtFTPpassword = $_POST['FTPpassword'];

            $user_data['FTPusername'] = $_POST["FTPusername"];
            $user_data['FTPpassword'] = $_POST["FTPpassword"];

            if (trim($txtFTPusername) == '') {
                $perm_msg .= '* Please enter FTP username <br/>';
            }
            if (trim($txtFTPpassword) == '') {
                $perm_msg .= '* Please enter FTP password <br/>';
            } else {
                $conn_id = @ftp_connect($host_name["path"]);
                $login_result = @ftp_login($conn_id, $txtFTPusername, $txtFTPpassword);
                if ($login_result) {
                    $mode = 777;
                    $np = '0' . $mode;

                    $user_install = str_replace('upgrade3.1', '', getcwd());

//get the path staring from public_html
                    if (strstr($user_install, 'public_html')) {
                        $path_parts = explode('/public_html', $user_install);
                        $user_install = '/public_html' . $path_parts[1];
                    } elseif (strstr($user_install, 'httpdocs')) {
                        $path_parts = explode('/httpdocs', $user_install);
                        $user_install = '/httpdocs' . $path_parts[1];
                    }

                    foreach ($directories as $directory) {
                        $edited_path = str_replace('..', '', $directory);
                        $directory = $user_install . $edited_path;
                        if ($directory[strlen($directory) - 1] == '/') {
                            $directory = substr($directory, 0, strlen($directory) - 1);
                        }
                        $directory=ltrim($directory,"/");
                        if (!@ftp_chmod($conn_id, eval("return({$np});"), $directory)) {
                            $perm_flag = false;
                        }
                    }

                    if (!$perm_flag) {
                        $perm_msg .= '* Sorry, an error occurred. Please try again or set the permissions manually <br/>';
                    } else {
                        $perm_msg = '<b>* File permissions successfuly set </b><br/>';
                        $serverPermission="false";
                        $error=false;
                    }
                } else {
                    $perm_msg .= '* Sorry, could not connect to the server. Please check the credentials <br/>';
                }
            }
        }
    } 

    foreach ($directories as $dir) {
        $permission = ServerConfig::fileWritable($dir, $dir);
        if (!$permission['status']) {
            $error = true;
        }
    } 


    //check the db connection
    $connection = @mysql_connect(MYSQL_HOST, MYSQL_USERNAME, MYSQL_PASSWORD); 
    if ($connection === false) {
        $error = true;
        $message .= " * Connection Not Successful! Please verify your database details!<br>";
    } else {
        $dbselected = @mysql_select_db(MYSQL_DB, $connection);
        if (!$dbselected) {
            $error = true;
            $message .= " * Database could not be selected! Please verify your database details!<br>";
        }
    } 

    $sqlPrefix = MYSQL_TABLE_PREFIX;


    // Get adminEmail
    $lookUpdate = mysql_query("SELECT vvalue FROM ".$sqlPrefix."lookup");
    $admin_email = '';
    $sqlsettingsData = mysql_query("SELECT vvalue, vname FROM " . $sqlPrefix . "lookup WHERE vname IN ('admin_mail', 'site_name')");
    if($sqlsettingsData) {
        while($sqlres = mysql_fetch_assoc($sqlsettingsData)) { 
            if($sqlres['vname'] == 'admin_mail') {
                $admin_email = $sqlres['vvalue'];
            }
            else {
                $site_name = $sqlres['vvalue'];
            }
        }
    } 


    //proceed with corresponding action
    $version = '3.1';
    if (!$error) {
        // Update Easycreate install_config file
        $fp = fopen($configfile, "w+");
        $configcontent = "<?php\n";
        $configcontent .= "define('INSTALLED', true); \n\n";
        $configcontent .= "define('VERSION', \"$version\"); \n\n";
        $configcontent .= "\n?>";
        fwrite($fp, $configcontent);
        fclose($fp);

        //------------------------UPDATE THE DB---------------------------------//
        $sqlquery = @fread(@fopen($schemafile, 'r'), @filesize($schemafile));
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        $tableName = 'lookup';
        $labelField = 'vname';
        $valueField = 'vvalue';

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysql_query($sqlquery[$i], $connection);
        }

        $dataquery = @fread(@fopen($datafile, 'r'), @filesize($datafile));
        $dataquery = preg_replace('/tbl_/', $sqlPrefix, $dataquery);
        $dataquery = ServerConfig::splitsqlfile($dataquery, ";");

        for ($i = 0; $i < sizeof($dataquery); $i++) {
            mysql_query($dataquery[$i], $connection);
        } 


        // To make DB collation utf8_general_ci for multi language support
        if(!$connection) {
            echo "Cannot connect to the database ";
            die();
        }
        mysql_select_db(MYSQL_DB);
        $result = mysql_query('show tables');
        while($tables = mysql_fetch_array($result)) {
            foreach ($tables as $key => $value) {
                mysql_query("ALTER TABLE $value CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
            }
        }
        // To make DB collation utf8_general_ci for multi language support


        //installation tracker
        $rootserver = BASE_URL;
        $productVersion = 'Easycreate 3.1';
        $string = "";
        $pro = urlencode($productVersion);
        $dom = urlencode($rootserver);
        $ipv = urlencode($_SERVER['REMOTE_ADDR']);
        $mai = urlencode($admin_email);
        $string = "pro=$pro&dom=$dom&ipv=$ipv&mai=$mai";
        $contents = "no";
        $file = @fopen("http://www.iscripts.com/installtracker.php?$string", 'r');
        if ($file) {
            $contents = @fread($file, 8192);
        }

        $installed = true;

//send confirmation email to admin
        $subject = "Script Upgraded at " . $site_name;

        $headers = "From: " . $site_name . "<" . $admin_email . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

        $mailcontent = "Hello , <br>";
        $mailcontent .= "Your Site is successfully upgraded.<br> <a href='" . BASE_URL . "' target='_blank'>Click Here to Access your Site</a>";
        $mailcontent .= "<br><a href='" . BASE_URL . "admin' target='_blank'>Click Here to Access your Site Administration Control Panel</a> <br><br>";
        $mailcontent .= "<br><br> Thanks and regards,<br> " . $site_name . " Team";

        $mailMsg = NULL;
        $dateVal = date("m/d/Y");
        $copyright = 'copyright '.date('Y').' '.$site_name.' All rights reserved'; 

        if(!empty($mailMsg)) {
            $mailMsg = str_replace("{SITE_LOGO}", "<img src='".BASE_URL . "install/css/ec.png'/>", $mailMsg);
            $mailMsg = str_replace("{Date}", $dateVal, $mailMsg);
            $mailMsg = str_replace("{MAIL_CONTENT}", $mailcontent, $mailMsg);
            $mailMsg = str_replace("{SITE_NAME}", $site_name, $mailMsg);
            $mailMsg = str_replace("{COPYRIGHT}", $copyright, $mailMsg);
        } else {
            $mailMsg = $mailcontent;
        } 

        $mailcontent = $mailMsg; 
        @mail(addslashes($admin_email), $subject, $mailcontent, $headers);
        
    }
}  

//check current directory permissions
foreach ($directories as $dir) {
    $permission = ServerConfig::fileWritable($dir, $dir);
    if (!$permission['status'] && $error == false) {
        $error = true;
        $serverPermission="true";

    }
    if (!$permission['status'] ) {
        $error_message=$error_message.$permission['message'];
    }
}

if ($installed) {
    header("location: upgrade_success.php");
    exit;
}
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
    <body class="bodyinstaller">
        <div class="header_row">
            <div class="header_container  sitewidth">
                <table width="100%" border="0" cellspacing="0" cellpadding="0"
                       align="center">
                    <tr>
                        <td width="23%" align="left"><img src="css/ec.png" alt="Logo"></td>
                        <td width="77%" align="right">
                            <h4><?php echo $upgraderTitle; ?></h4>
                            <div align="center" id="items_top_area">&nbsp;&nbsp; <a
                                    title="OnlineInstallationManual" href="#"
                                    onClick="window.open('<?php echo BASE_URL; ?>docs/easycreate.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Installation
                                        manual</strong></a> | <a title="Readme" href="#"
                                                         onClick="window.open('<?php echo BASE_URL; ?>docs/Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Readme</strong></a> | <a
                                                         title="If you have any difficulty, submit a ticket to the support department"
                                                         href="#"
                                                         onClick="window.open('http://www.iscripts.com/support/postticketbeforeregister.php','','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd,resizable=yes');">
                                    <strong>Get Support</strong></a></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td><img src="css/spacer.gif" width="1" height="5"></td>
            </tr>
        </table>

        <table width="100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="76%" valign="top" height="400"><!-- Here's where I want my views to be displayed -->
                    <table width="80%" border="0" cellpadding="0" cellspacing="0"
                           align="center">
                        <tr>
                            <td><!--------Installer starts-------------------------> <!--items display area start -->
                                <?php
                                if ($serverSettings == "FAILURE") {
                                    ?>
                                <table width="100%" border=0 align="center">
                                        <?php
                                        foreach ($serverCurrentSettings as $settings) {
                                            $span_class = $settings['flag'] ? "install_value_ok" : "install_value_fail";
                                            ?>
                                    <tr>
                                        <td><?php echo $settings['feature']; ?> <span
                                                class="<?php echo $span_class; ?>"><?php echo $settings['setting']; ?></span>
                                        </td>
                                    </tr>
                                            <?php
                                        }
                                        ?>
                                    <tr>
                                        <td><span class="install_value_fail">Fatal errors detected. Please
						correct the above red items and reload.</span></td>
                                    </tr>
                                </table>

                                <?php
                                } else if (!$installed) {
                                    ?>
                                <table width="80%" border="0" align="center">
                                    <tr>
                                        <td align="center"><b><font size="1">
                                            <div align="justify"><br>
                                                <font color="#F4700E" size="+1">Thank you for purchasing the upgrade kit of <?php echo $productName;?>&nbsp;</font>
                                                <br>
                                                <br>
                                                <font color="#000000" size="2">To complete this upgradation process, please enter the details below.</font>
                                            </div>
                                            </font></b></td>
                                    </tr>




                                    <tr>
                                        <td class=maintext align="left" colspan="2">
                                            <FIELDSET>
                                                <LEGEND class="block_class">Upgradation Instructions</LEGEND>
                                                <br />
                                                <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                                    <tr>
                                                        <td colspan="3" class="maintext"><font class="required">*</font>&nbsp;Make sure that you have done the following things before pressing the 'Upgrade' button</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3">&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td class="maintext tpadding">
                                                            <label class="fleft"><img  src="css/arrow.png" />&nbsp;&nbsp;</label>
                                                            <label class="fleft">Take the backup of the existing EasyCreate files and folders!! </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td class="maintext tpadding">
                                                            <label class="fleft"><img  src="css/arrow.png" />&nbsp;&nbsp;</label>
                                                            <label class="fleft">Take the backup of the existing EasyCreate Database !! </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td class="maintext tpadding">
                                                            <label class="fleft"><img  src="css/arrow.png" />&nbsp;&nbsp;</label>
                                                            <label class="fleft">Restore includes/configsettings.php, folders like systemgallery,usergallery,categoryicons,samplelogos,sites,uploads,workarea <br/>from the backed up folder(old easycreate folder) to the new easycreate folder </label>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td class="maintext tpadding">
                                                            <label class="fleft"><img  src="css/arrow.png" />&nbsp;&nbsp;</label>
                                                            <label class="fleft">When the upgradation procedure is completed successfully, cross check your existing files and database with the upgraded one. </label>
                                                        </td>
                                                    </tr>
                                                    <!--tr>
                                                        <td colspan="2">&nbsp;</td>
                                                        <td class="maintext tpadding">
                                                            <label class="fleft"><b>Note:</b> &nbsp;&nbsp;</label>
                                                            <label class="fleft">Once upgraded, the already created sites will not work since we are replacing the entire templates.</label>
                                                        </td>
                                                    </tr-->
                                                </table>
                                                <br />
                                            </FIELDSET>
                                        </td>
                                    </tr>







                                    <?php if ($post_flag) { ?>
                                    <tr>
                                        <td align=center class="message">
                                            <div align="left" class="text_information"><br>
                                                <?php if($error) { ?>
                                                <u><b><font color="#FF0000">Please correct the following errors to continue:</font></b></u><br/>
                                                <?php }?>
                                                <font color="#FF0000"><?php echo $perm_msg; ?></font><br>
                                                <font color="#FF0000"><?php echo $error_message . '<br/>' . $message; ?></font><br>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php } ?>
                                    <tr>
                                        <td class=maintext align="left">Note: All Fields Are Mandatory. <br>
                                            <form name="frmInstall" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"><br>

                                                <FIELDSET><LEGEND class='block_class'>File Permissions</LEGEND>
                                                    <table width=85% border="0" cellpadding="2" cellspacing="2"
                                                           class=maintext>
                                                        <tr>
                                                            <td colspan="2" align="left">
                                                                <b> <?php if ($serverPermission==true) { ?>
                                                                <?php echo $productName;?> requires that some of the folders have write permission. You can provide an FTP login so that this process is done automatically.<br /><br />
								For security reasons, it is best to create a separate FTP user account with access to the <?php echo $productName;?> upgradation only and not the entire web server. 
                                                                Your host can assist you with this. If you have difficulties completing upgradation without these credentials, please click "I would provide permissions manually" to do it yourself.<br />
                                                                <br />
                                                                <?php } ?> </b>
                                                            </td>
                                                        </tr>
                                                        <?php if ($serverPermission==true) { ?>
                                                        <tr>
                                                            <td class=maintext align="left">FTP username</td>
                                                            <td width="61%" align=left><input name="FTPusername" id="FTPusername" type="text" size="50" value="<?php echo htmlentities($user_data['FTPusername']); ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td class=maintext align="left">FTP password</td>
                                                            <td width="61%" align=left><input name="FTPpassword" id="FTPpassword" type="password" size="50" value="<?php echo htmlentities($user_data['FTPpassword']); ?>"> </td>
                                                        </tr>
                                                        <?php if ($serverPermission==true) { ?>
                                                        <tr>
                                                            <td colspan="2" align="left"><input type="checkbox" name="auto_set" id="auto_set" <?php echo ($_POST['auto_set'])?'checked':'';?> onclick="divToggle(this)" /> &nbsp; I would provide permissions manually</td>
                                                        </tr>
                                                        <?php
                                                            }
                                                        } else {
                                                        ?>
                                                        <tr>
                                                            <td colspan="2" align="left"><b>File permissions are OK.</b></td>
                                                        </tr>
                                                                <?php } ?>
                                                    </table>
                                                    
                                                    <?php if ($serverPermission==true) { ?>
                                                    <div id="err_div" style="display: none">
                                                        <fieldset><legend>Directories/Files List</legend> <?php echo $error_message; ?> </fieldset>
                                                    </div>
                                                <?php } ?></FIELDSET>
                                                <br>
                                                <br>
                                                
                                                <table width=85% border=0 cellpadding="2" cellspacing="2" class=maintext>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="left"><input type="submit" name="btnContinue" value="Upgrade" class="buttn_admin"></td>
                                                    </tr>
                                                </table>
                                                <!-- DO NOT REMOVE -->
                                                <input type="hidden" name="upgraderCheck" id="upgraderCheck" value="1" />
                                                <!-- ------------- -->
                                            </form>
                                        </td>
                                    </tr>
                                </table>
                                    <?php
                                }
                                ?></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
        <div class="installr_footer"></div>
    </body>
</html>
