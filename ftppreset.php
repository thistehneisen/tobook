<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php 
//include files
include "includes/session.php"; 
include "includes/config.php";
include "includes/ftpfunctions.php";
include "includes/sitefunctions.php";

ini_set('max_execution_time', '150');

$siteid   = $_SESSION['siteDetails']['siteId'];
$siteName = $_SESSION['siteDetails']['siteInfo']['siteName'];

if($siteLanguageOption=="english"){
    $siteNameModified = getAlias($siteName,'-');
}else{
    $siteNameModified = $siteid;
}

$userid = $_SESSION["session_userid"];
$userDetails = getUserDetails($userid);
$message="";
$conn_id="";

//get the root directory from look up table
$ftp_directory   = getSettingsValue('ftp_location');

$ftp_user_domain = getSettingsValue('ftp_host');
$ftp_user_name   = getSettingsValue('ftp_username');
$ftp_user_pass   = getSettingsValue('ftp_password');
$ftp_root_url    = getSettingsValue('ftp_root_url');

//echopre($ftp_directory); echopre($ftp_user_domain); echopre($ftp_user_name); echopre1($ftp_user_pass);

//check if ftp info is currect
$result = check_ftp_info($ftp_user_domain,$ftp_user_name,$ftp_user_pass); 

//if well connected to site upload files
if($result=="ok") {

    $log.= "<font class=greentext><b>Connected to $ftp_user_domain, for user $ftp_user_name</b><br></form>";

    // upload  file locations
    $local_dir= USER_SITE_UPLOAD_PATH.$siteid;
    /*
    if( !is_file(USER_SITE_UPLOAD_PATH.$siteid."/index.htm") ) {
        copy(USER_SITE_UPLOAD_PATH.$siteid."/home.htm",USER_SITE_UPLOAD_PATH.$siteid."/index.htm");
    } */


    $log.= ftp_dir($local_dir, $ftp_directory,$siteNameModified);

    // close the connection
    @ftp_close($conn_id);
    $message="FTP session completed";
    $messageClass = "msggreen";
    
    // Update publish flag in  Site Master table
     updateSitePublishStatus($siteid);

     // Send site created mail
     sendSiteCreatedMail($userDetails);
    
    //$message=$result;
    //header("Location:downloadsite.php?sid=".$siteid);
}

include "includes/userheader.php";
?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align="center">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left">
                        <h2>FTP Location Preset By Admin</h2>
                    </td>
                </tr>
                <tr>
                    <td class="<?php echo $messageClass;?>"><?php echo $message; ?></td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <?php if($result=="ok") { ?>
                <tr>
                    <td>
                        FTP Status Window
                        <table width="100%"  border="0" class="customize-tbl" cellpadding="0" cellspacing="0">
                            <tr>
                                <td> <?php echo $log;?><br> </td>
                            </tr>
                            <tr>
                                <td> Your Site is at : <a target="_blank" href="<?php echo $ftp_root_url.$siteNameModified; ?>"><?php echo $ftp_root_url.$siteNameModified; ?></a><br> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <?php } ?>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>
<?php
include "includes/userfooter.php";
?>