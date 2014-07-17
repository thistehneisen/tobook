<?php
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
ob_start();
include_once('../includes/install_config.php');
include_once('cls_serverconfig.php');

//prevent re-installation if already installed
if (INSTALLED) {
    header("location: ../index.php");
    exit;
}

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
$path=str_replace('install/', '', $path);
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


if (isset($_POST['installerCheck']) || $_POST['cldpack']==1) {
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

                    $user_install = str_replace('install', '', getcwd());

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
    if($_POST['cldpack']==1) {
        $user_data['txtDBServerName']   =  'localhost';
        $user_data['txtDBName']         =  $_POST["db_name"];
        $user_data['txtDBUserName']     =  $_POST["db_user"];
        $user_data['txtDBPassword']     =  $_POST["db_password"];
        $user_data['txtSiteName']       =  $_POST["store_name"];
        $user_data['txtAdminName']      =  "admin";
        $user_data['txtAdminPassword']  =  "q1w2e3";
        $user_data['txtAdminEmail']     =  $_POST['contactemail'];
        $user_data['txtTablePrefix']    =  'easycreate_';
    }
    else {
//check the user data
        $user_data['txtDBServerName']   = $_POST["txtDBServerName"];
        $user_data['txtDBName']         = $_POST["txtDBName"];
        $user_data['txtDBUserName']     = $_POST["txtDBUserName"];
        $user_data['txtDBPassword']     = $_POST["txtDBPassword"];
        $user_data['txtSiteName']       = $_POST["txtSiteName"];
        $user_data['txtAdminName']      = $_POST["txtAdminName"];
        $user_data['txtAdminPassword']  = $_POST["txtAdminPassword"];
        $user_data['txtLicenseKey']     = $_POST["txtLicenseKey"];
        $user_data['txtAdminEmail']     = $_POST["txtAdminEmail"];
//$user_data['txtTablePrefix']    = $_POST["txtTablePrefix"];
        $user_data['txtTablePrefix']    = 'tbl_';
    }

    if ($_POST['cldpack'] != 1) {
        if (trim($user_data['txtDBServerName']) == '') {
            $message .= " * Database Server Name is empty!" . "<br>";
            $error = true;
        }
        if (trim($user_data['txtDBName']) == '') {
            $message .= " * Database Name is empty!" . "<br>";
            $error = true;
        }
        if (trim($user_data['txtDBUserName']) == '') {
            $message .= " * Database User Name is empty!" . "<br>";
            $error = true;
        }
        if (trim($user_data['txtSiteName']) == '') {
            $message .= " * Site Name is empty!" . "<br>";
            $error = true;
        }
        if (trim($user_data['txtLicenseKey']) == '') {
            $message .= " * License Key is empty!" . "<br>";
            $error = true;
        }
        if (trim($user_data['txtAdminEmail']) == '') {
            $message .= " * Admin Email is empty!" . "<br>";
            $error = true;
        } else {
            if (!ServerConfig::is_valid_email($user_data['txtAdminEmail'])) {
                $message .= " * Invalid Admin Email!" . "<br>";
                $error = true;
            }
        }
    }

    //check the db connection
    $connection = @mysql_connect($user_data['txtDBServerName'], $user_data['txtDBUserName'], $user_data['txtDBPassword']);
    if ($connection === false) {
        $error = true;
        $message .= " * Connection Not Successful! Please verify your database details!<br>";
    } else {
        $dbselected = @mysql_select_db($user_data['txtDBName'], $connection);
        if (!$dbselected) {
            $error = true;
            $message .= " * Database could not be selected! Please verify your database details!<br>";
        }
    }

    //proceed with corresponding action
    $version = '3.1';
    if (!$error) {
        // Update Easycreate install_config file
        $fp = fopen($configfile, "w+");
        $configcontent = "<?php\n";
        if ($_POST['cldpack'] == 1) {
            $configcontent .= "define('CLOUDINSTALLED', true); \n\n";
            $configcontent .= "define('SOURCE', 'SOURCE'); \n\n";
            $configcontent .= "define('FIELD', 'vLookUp_Value'); \n\n";
            $configcontent .= "define('CONDITION', \"vLookUp_Name = 'vLicenceKey'\"); \n\n";
            $configcontent .= "define('PID',24); \n\n";
        }
        $configcontent .= "define('INSTALLED', true); \n\n";
        $configcontent .= "define('VERSION', \"$version\"); \n\n";
        $configcontent .= "\n?>";
        fwrite($fp, $configcontent);

        //Update Easycreate configsettings file
        $handle = fopen($settingsfile, "rb");
        $contents = fread($handle, filesize($settingsfile));
        fclose($handle);

        $contents = str_replace('DB_NAME', $user_data['txtDBName'], $contents);
        $contents = str_replace('USER_NAME', $user_data['txtDBUserName'], $contents);
        $contents = str_replace('DB_PASSWORD', $user_data['txtDBPassword'], $contents);
        $contents = str_replace('HOST_NAME', $user_data['txtDBServerName'], $contents);
        $contents = str_replace('DB_PREFIX', $user_data['txtTablePrefix'], $contents);
        $contents = str_replace('CONFIG_BASE_URL_PART', BASE_URL_PART, $contents);



        $fp = fopen($settingsfile, 'w');
        fwrite($fp, $contents);
        fclose($fp);

//------------------------UPDATE THE DB---------------------------------//
        $sqlquery = @fread(@fopen($schemafile, 'r'), @filesize($schemafile));
        if ($_POST['cldpack'] != 1) {
            $sqlquery = preg_replace('/tbl_/', $user_data['txtTablePrefix'], $sqlquery);
        }
        $sqlquery = ServerConfig::splitsqlfile($sqlquery, ";");

        $tableName = 'lookup';
        $labelField = 'vname';
        $valueField = 'vvalue';

        for ($i = 0; $i < sizeof($sqlquery); $i++) {
            mysql_query($sqlquery[$i], $connection);
        }

        $dataquery = @fread(@fopen($datafile, 'r'), @filesize($datafile));
        $dataquery = preg_replace('/tbl_/', $user_data['txtTablePrefix'], $dataquery);
        $dataquery = ServerConfig::splitsqlfile($dataquery, ";");

        for ($i = 0; $i < sizeof($dataquery); $i++) {
            mysql_query($dataquery[$i], $connection);
        }


       // To make DB collation utf8_general_ci for multi language support
       if(!$connection) { echo "Cannot connect to the database ";die();}
       mysql_select_db($user_data['txtDBName']);
       $result = mysql_query('show tables');
            while($tables = mysql_fetch_array($result)) {
                foreach ($tables as $key => $value) {
                        mysql_query("ALTER TABLE $value CONVERT TO CHARACTER SET utf8 COLLATE utf8_general_ci");
                }
            }
       // To make DB collation utf8_general_ci for multi language support


        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . $tableName." SET ".$valueField." = '" . addslashes($user_data['txtLicenseKey']) . "' WHERE ".$labelField." = 'vLicenceKey'";
        mysql_query($sqlsettings1) or die(mysql_error());

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . $tableName." SET ".$valueField." = '" . addslashes($user_data['txtSiteName']) . "' WHERE ".$labelField." = 'site_name'";
        mysql_query($sqlsettings1) or die(mysql_error());

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . $tableName." SET ".$valueField." = '" . addslashes($user_data['txtAdminEmail']) . "' WHERE ".$labelField." = 'admin_mail'";
        mysql_query($sqlsettings1) or die(mysql_error());

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . $tableName." SET ".$valueField." = '" . addslashes($root_url) . "' WHERE ".$labelField." = 'rootserver'";
        mysql_query($sqlsettings1) or die(mysql_error());

        $sqlsettings1 = "UPDATE " . $user_data['txtTablePrefix'] . $tableName." SET ".$valueField." = '" . addslashes($secure_root_url) . "' WHERE ".$labelField." = 'secureserver'";
        mysql_query($sqlsettings1) or die(mysql_error());


        //installation tracker
        $rootserver = BASE_URL;
        $productVersion = 'Easycreate 3.1';
        $string = "";
        $pro = urlencode($productVersion);
        $dom = urlencode($rootserver);
        $ipv = urlencode($_SERVER['REMOTE_ADDR']);
        $mai = urlencode($user_data['txtAdminEmail']);
        $string = "pro=$pro&dom=$dom&ipv=$ipv&mai=$mai";
        $contents = "no";
        $file = @fopen("http://www.iscripts.com/installtracker.php?$string", 'r');
        if ($file) {
            $contents = @fread($file, 8192);
        }

        $installed = true;

//send confirmation email to admin
        $subject = "Script Installed at " . $user_data['txtSiteName'];

        $headers = "From: " . $user_data['txtSiteName'] . "<" . $user_data['txtAdminEmail'] . ">\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";

        $mailcontent = "Hello , <br>";
        $mailcontent .= "Your Site is successfully installed.<br> <a href='" . BASE_URL . "' target='_blank'>Click Here to Access your Site</a>";
        $mailcontent .= "<br><a href='" . BASE_URL . "admin' target='_blank'>Click Here to Access your Site Administration Control Panel</a> <br><br>";
        $mailcontent .= "Your Admin Username   :  " . $user_data['txtAdminName'];
        $mailcontent .= "<br>Your Admin Password   :  " . $user_data['txtAdminPassword'];
        $mailcontent .= "<br><br> Thanks and regards,<br> " . $user_data['txtSiteName'] . " Team";

        /* Email Template */
        /*$email_temp_qry = "SELECT mail_template_body FROM ".$user_data['txtTablePrefix']."mail_template WHERE mail_template_name='installer_mail'";
 $email_temp = mysql_query($email_temp_qry);
 $mailMsgArr = mysql_fetch_array($email_temp); */
        $mailMsg = NULL;
        $dateVal = date("m/d/Y");
        $copyright = 'copyright '.date('Y').' '.$user_data['txtSiteName'].' All rights reserved';

        /*
 if(count($mailMsgArr) > 0) {
 $mailMsg = $mailMsgArr['mail_template_body'];
 } // End If*/
        if(!empty($mailMsg)) {
            $mailMsg = str_replace("{SITE_LOGO}", "<img src='".BASE_URL . "install/css/ec.png'/>", $mailMsg);
            $mailMsg = str_replace("{Date}", $dateVal, $mailMsg);
            $mailMsg = str_replace("{MAIL_CONTENT}", $mailcontent, $mailMsg);
            $mailMsg = str_replace("{SITE_NAME}", $user_data['txtSiteName'], $mailMsg);
            $mailMsg = str_replace("{COPYRIGHT}", $copyright, $mailMsg);
        } else {
            $mailMsg = $mailcontent;
        }

        $mailcontent = $mailMsg;
        @mail(addslashes($user_data['txtAdminEmail']), $subject, $mailcontent, $headers);
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
    header("location: install_success.php");
    exit;
}
$installerTitle = 'iScripts EasyCreate Installer';
$productName = 'EasyCreate';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
        <title><?php echo $installerTitle; ?></title>
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
                            <h4><?php echo $installerTitle; ?></h4>
                            <div align="center" id="items_top_area">&nbsp;&nbsp; <a
                                    title="OnlineInstallationManual" href="#"
                                    onClick="window.open('<?php echo BASE_URL; ?>docs/easycreate.pdf','OnlineInstallationManual','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Installation
		manual</strong></a> | <a title="Readme" href="#"
                                                         onClick="window.open('<?php echo BASE_URL; ?>docs/Readme.txt','Readme','top=100,left=100,width=820,height=550,scrollbars=yes,toolbar=no,status=yrd');"><strong>Readme</strong></a>
		| <a
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
                                                        <font color="#F4700E" size="+1">Thank you for choosing <?php echo $productName;?>&nbsp;</font>
                                                        <br>
                                                        <br>
                                                        <font color="#000000" size="2">To complete this installation
						please enter the details below.</font></div>

                                                </font></b></td>
                                    </tr>
                                        <?php if ($post_flag) { ?>
                                    <tr>
                                        <td align=center class="message">
                                            <div align="left" class="text_information"><br>
                                                        <?php if($error) {?><u><b><font color="#FF0000">Please correct the
						following errors to continue:</font></b></u>
                                                <p />
                                                            <?php }?> <font color="#FF0000"><?php echo $perm_msg; ?></font><br>
                                                <font color="#FF0000"><?php echo $error_message . '<br/>' . $message; ?></font><br>
                                            </div>
                                        </td>
                                    </tr>
                                            <?php } ?>
                                    <tr>
                                        <td class=maintext align="left">Note: All Fields Are Mandatory. <br>
                                            <form name="frmInstall" method="post"
                                                  action="<?php echo $_SERVER['PHP_SELF']; ?>"><br>

                                                <FIELDSET><LEGEND class='block_class'>File Permissions</LEGEND>
                                                    <table width=85% border="0" cellpadding="2" cellspacing="2"
                                                           class=maintext>
                                                        <tr>
                                                            <td colspan="2" align="left"><b> <?php if ($serverPermission==true) { ?>
                                                                            <?php echo $productName;?> requires that some of the folders
								have write permission. You can provide an FTP login so that this
								process is done automatically.<br />
                                                                    <br />
								For security reasons, it is best to create a separate FTP user
								account with access to the <?php echo $productName;?>
								installation only and not the entire web server. Your host can
								assist you with this. If you have difficulties completing
								installation without these credentials, please click "I would
								provide permissions manually" to do it yourself.<br />
                                                                    <br />
                                                                            <?php } ?> </b></td>
                                                        </tr>
                                                            <?php if ($serverPermission==true) { ?>
                                                        <tr>
                                                            <td class=maintext align="left">FTP username</td>
                                                            <td width="61%" align=left><input name="FTPusername"
                                                                                              id="FTPusername" type="text" size="50"
                                                                                              value="<?php echo htmlentities($user_data['FTPusername']); ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td class=maintext align="left">FTP password</td>
                                                            <td width="61%" align=left><input name="FTPpassword"
                                                                                              id="FTPpassword" type="password" size="50"
                                                                                              value="<?php echo htmlentities($user_data['FTPpassword']); ?>">
                                                            </td>
                                                        </tr>
                                                                <?php if ($serverPermission==true) { ?>
                                                        <tr>
                                                            <td colspan="2" align="left"><input type="checkbox"
                                                                                                name="auto_set" id="auto_set"
                                                                                                            <?php echo ($_POST['auto_set'])?'checked':'';?>
                                                                                                onclick="divToggle(this)" /> &nbsp; I would provide permissions
								manually</td>
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
                                                        <fieldset><legend>Directories/Files List</legend> <?php echo $error_message; ?>
                                                        </fieldset>
                                                    </div>
                                                            <?php } ?></FIELDSET>
                                                <br>
                                                <br>

                                                <FIELDSET><LEGEND class='block_class'>Database Details</LEGEND>
                                                    <table width=85% border=0 cellpadding="2" cellspacing="2"
                                                           class=maintext>
                                                        <tr>
                                                            <td colspan="2" class=maintext align="left">Database Server</td>
                                                            <td width="61%" align=left><input type="text"
                                                                                              name="txtDBServerName" id="txtDBServerName"
                                                                                              value="<?php echo trim($user_data['txtDBServerName']) <> "" ? htmlentities($user_data['txtDBServerName']) : "localhost"; ?>" />
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class=maintext align="left">Database Name</td>
                                                            <td width="61%" align=left><input name="txtDBName"
                                                                                              id="txtDBName" type="text" class="textbox" maxlength="100"
                                                                                              size="50"
                                                                                              value="<?php echo htmlentities($user_data['txtDBName']); ?>"></td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class=maintext align="left">Database User Name</td>
                                                            <td width="61%" align=left><input name="txtDBUserName"
                                                                                              id="txtDBUserName" type="text" maxlength="100" size="50"
                                                                                              value="<?php echo htmlentities($user_data['txtDBUserName']); ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class=maintext align="left">Database Password</td>
                                                            <td width="61%" align=left><input name="txtDBPassword"
                                                                                              id="txtDBPassword" type="text" maxlength="100" size="50"
                                                                                              value="<?php echo htmlentities($user_data['txtDBPassword']); ?>">
                                                            </td>
                                                        </tr>
                                                        <!--tr>
                                                                <td colspan="2" class=maintext align="left">Table Prefix</td>
                                                                <td width="61%" align=left>
                                                                    <input name="txtTablePrefix"  id="txtTablePrefix" type="text"  maxlength="100" size="50" value="<?php //echo trim($user_data['txtTablePrefix']) <> "" ? htmlentities($user_data['txtTablePrefix']) : "tbl_"; ?>">
                                                                </td>
                                                            </tr-->

                                                    </table>
                                                </FIELDSET>
                                                <br>
                                                <br>
                                                <FIELDSET><LEGEND class='block_class'>Site Details</LEGEND>
                                                    <table width=85% border=0 cellpadding="2" cellspacing="2"
                                                           class=maintext>
                                                        <tr>
                                                            <td colspan="2" class=maintext align="left">Site Name</td>
                                                            <td width="61%" align=left><input name="txtSiteName"
                                                                                              id="txtSiteName" type="text" maxlength="100" size="50"
                                                                                              value="<?php echo htmlentities($user_data['txtSiteName']); ?>">
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td colspan="2" class=maintext align="left">License Key</td>
                                                            <td width="61%" align=left><input name="txtLicenseKey"
                                                                                              id="txtLicenseKey" type="text" maxlength="100" size="50"
                                                                                              value="<?php echo htmlentities($user_data['txtLicenseKey']); ?>">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </FIELDSET>
                                                <br>
                                                <br>
                                                <FIELDSET><LEGEND class="block_class">Administration Details</LEGEND>
                                                    <table width=85% border=0 cellpadding="2" cellspacing="2"
                                                           class=maintext>
                                                        <tr>
                                                            <td colspan="2" class=maintext align="left">Admin Email</td>
                                                            <td width="61%" align=left><input name="txtAdminName"
                                                                                              id="txtAdminName" type="hidden" maxlength="100" size="50"
                                                                                              value="admin"> <input name="txtAdminPassword"
                                                                                              id="txtAdminPassword" type="hidden" maxlength="100" size="50"
                                                                                              value="admin"> <input name="txtAdminEmail" id="txtAdminEmail"
                                                                                              type="text" maxlength="100" size="50"
                                                                                              value="<?php echo htmlentities($user_data['txtAdminEmail']); ?>">
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </FIELDSET>
                                                <br>
                                                <table width=85% border=0 cellpadding="2" cellspacing="2"
                                                       class=maintext>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center"><input type="submit" name="btnContinue"
                                                                                  value="Continue" class="buttn_admin"></td>
                                                    </tr>
                                                </table>

                                                <!-- DO NOT REMOVE --> <input type="hidden" name="installerCheck"
                                                                              id="installerCheck" value="1" /> <!-- ------------- --></form>
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
