<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              	  |
// |          									                          |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
include "includes/zipdirectoryclass.php";
include "includes/sitefunctions.php";


$siteid 		= $_SESSION['siteDetails']['siteId'];
$templateid             = $_SESSION['siteDetails']['siteInfo']['templateid'];
$userid 		= $_SESSION["session_userid"];
$sitename = $_SESSION['siteDetails']['siteInfo']['siteName'];

if($siteLanguageOption=="english"){
    $siteNameModified = getAlias($sitename,'-');
}else{
    $siteNameModified = $siteid;
}

$userDetails = getUserDetails($userid);

$paymentStatus = getPaymentStatusByUser($siteid,$userid);
//echopre($paymentStatus);

// added for correction while redirecting
$rootserver 	= $_SESSION["session_rootserver"];
$secureserver 	= $_SESSION["session_secureserver"];

if($paymentStatus <= 0) {
    echo("<script>alert('".DOWNLOAD_PUBLISH_WRONGSITE."'); location.href=\"usermain.php\";</script>");
    exit;
}

$errormessage = "";
/*check whether the site exist in tbl_site_mast with  the  combination of current templateid,userid 
 if site exist in database  check for the pages exist in workarea location
*/ 
$qry = "select * from tbl_site_mast where nsite_id='" . $siteid . "' and nuser_id='" . $userid . "' and ntemplate_id='" . $templateid . "'"; 
if (mysql_num_rows(mysql_query($qry)) > 0) {
    if (! is_dir(USER_SITE_UPLOAD_PATH.$siteid)) {
        $errormessage = DOWNLOAD_SITE_REMOVED;
    }
} else {
    $errormessage = DOWNLOAD_PUBLISH_AUTHORIZATION;
}

/*
if ($errormessage != "") {
    echo $errormessage;
    exit;
} */

/*
if ($_SESSION['session_paymentmode'] != "success") {
    header("location:payment.php");
    exit;
} */

/* Get the download type that are permitted by admin(ie ftp,zip or both) */

$ftpzip = getSettingsValue('user_publish_option'); 
switch($ftpzip){
    case 'FTP/ZIP/SUBFOLDER':
         $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY1;
         break;
    case 'FTP/ZIP':
         $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY2;
         break;
    case 'FTP/SUBFOLDER':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY3;
        break;
    case 'ZIP/SUBFOLDER':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY4;
        break;
    case 'FTP':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY5;
        break;
    case 'ZIP':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY6;
        break;
    case 'SUBFOLDER':
        $ftpMessage = DOWNLOAD_PUBLISH_UPLOAD_DIRECTLY7;
        break;
}

$ftp_preset = getSettingsValue('enable_ftp_preset');
 
$tempFolder = explode("/",$_SESSION['replacepath']); 
$arrayIndex=sizeof($tempFolder)-3;

//echopre($_SESSION['siteDetails']);

if (isset($_POST['submitdownload'])) {  
    //SaveSitePreview($userid, $templateid, $tmpsiteid, $siteid, "Yes", ".", "preview",$tempFolder[$arrayIndex]);
    
    if ($_POST['downloadformat'] == "1") {  // zip format

        if($siteLanguageOption =="english"){
            $zipfilename = getAlias($_SESSION['siteDetails']['siteInfo']['siteName']);
        }else{
            $zipfilename = $siteid;
        }
        $dirlocation = USER_SITE_UPLOAD_PATH.$siteid;
        $directoryloclength = strlen($dirlocation);
        /*  $tt is an array contains all the files under $dirlocation*/
        $tt = list_directory("$dirlocation"); 
        $zipObject = new zipfile();
        $indexpageexist = 0;
        foreach($tt as $key => $value) {
            // check to see index page exist
            if (strcasecmp(basename($value), "index.html") == 0) {
                $indexpageexist = 1;
            }

            //if (basename($value) != "resource.txt") {
                $value_tostoreinzip = substr($value, $directoryloclength + 1);
                $zipObject->add_file($value, "./" . $value_tostoreinzip);
            //}
        }
        /* if user doesnot create index.htm file,
		* create index.htm with the same content in home.htm and add to zip */

        /*
        if ($indexpageexist == "0") {
            copy($dirlocation . "/homepage.html", $dirlocation . "/index.html");
            $zipObject->add_file($dirlocation . "/index.html", "./index.html");
            unlink($dirlocation . "/index.html");
        }
         */

        header("Content-type: application/octet-stream");
        header ("Content-disposition: attachment; filename=$zipfilename.zip");
        echo $zipObject->file();
        
        // Update publish flag in  Site Master table
         updateSitePublishStatus($siteid);

         // Send site created mail
         sendSiteCreatedMail($userDetails);
         
        }

    //to upload to a sub directory
    else if($_POST['downloadformat']=="3") {
        
        $dirlocation = USER_SITE_UPLOAD_PATH.$siteid;
        $destinDir = 'sites/'.$siteNameModified;

        $old = umask(0);

        if(!@is_dir('sites')) {
            @mkdir('sites');
            chmod("sites", 0755);
        }//end if
        else{
            chmod("sites", 0755);
        }
        umask($old);
        
        if(!@is_dir($destinDir)) {
            @mkdir($destinDir);
        }//end if

        if(!@is_dir($destinDir."/images")) {
            @mkdir($destinDir."/images");
        }//end if

        if(!@is_dir($destinDir."/styles")) {
            @mkdir($destinDir."/styles");
        }//end if

        if(!@is_dir($destinDir."/js")) {
            @mkdir($destinDir."/js");
        }//end if

        $remote_dir = $destinDir;
        $mode = ($_SESSION['SERVER_PERMISSION'])?$_SESSION['SERVER_PERMISSION']:0777;
        $log1=copyfilesdirr($dirlocation,$remote_dir,$mode,false);
        $log2=copyfilesdirr($dirlocation."/images",$remote_dir."/images",$mode,false);
        $log3=copyfilesdirr($dirlocation."/styles",$remote_dir."/styles",$mode,false);
        $log3=copyfilesdirr($dirlocation."/js",$remote_dir."/js",$mode,false);
        //$log4=copy("./copy_index/index.htm",$remote_dir."/index.htm");

        // Update publish flag in  Site Master table
         updateSitePublishStatus($siteid);

         // Send site created mail
         sendSiteCreatedMail($userDetails);
         
        header("location:downloadsite.php?status=done");
        exit();
    }

    //To upload to a different directory/domain preset by Admin (FTP Preset Option)
    else if($_POST['downloadformat']=="4") {
        if($ftp_preset =='Y') {
            header("location:ftppreset.php");
            exit();
        }else{
            header("location:sitemanager.php");
            exit();
        }
    }
    
    //To publish site to Facebook fanpage
    else if($_POST['downloadformat']=="5") {
        if($ftp_preset =='Y') {
            header("location:facebookpublish.php");
            exit();
        }else{
            header("location:sitemanager.php");
            exit();
        }
    }
    else {
        header("location:ftpsite.php");
        exit();
    }//end else


}//end if 
$_SERVER['HTTP_REFERER'] = "frompayment";
include "includes/userheader.php";
?>
<SCRIPT>
    function showpreview(){ 
        var leftPos = (screen.availWidth-500) / 2;
        var topPos = (screen.availHeight-400) / 2 ;
        winurl="editor_sitepreview.php";
        insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);

    }
</SCRIPT>
<style>

.tooltip {color: #929292;
    display: inline-block;
    font-family: Arial,Helvetica,sans-serif;
    font-size: 11px;
    font-weight: normal;
    line-height: 20px;
    padding: 0 0 0 5px;
    text-align: left;font-style:italic;}
</style>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align="center">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left">
                    <?php if(isset($_GET['status']) && $_GET['status']=='done')
                    		echo ' <h3 style=" color: #0EB9AF; font-family: dosis,arial;  ; font-weight: normal; margin: 20px 0; padding: 0;">Successfully published your site</h3>';
                    	else
                    		 echo '<h2>'.DOWNLOAD_PUBLISHED.'</h2>'
                    ?>
                       
                    </td>
                </tr>
                <tr>
                    <td class=errormessage><?php echo $errormessage; ?></td>
                </tr>
                <?php if(isset($_GET['status']) && $_GET['status']=='done') {
                    
                    echo '<tr class="maintext"><td> <b>'.DOWNLOAD_SUCCESS.'<br>'. DOWNLOAD_CLICK.'</b> <br><br><a href="'.BASE_URL.'sites/'.$siteNameModified.'/" target="_blank" class="maintext"><b>'.BASE_URL.'sites/'.$siteNameModified.'/</b></a></td></tr>';
                } else {
                ?>
                <tr>
                    <td  class="maintext">
                        <?php //echo $ftpMessage; ?>
                        
                        <?php echo DOWNLOAD_OPTION;?>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                
                <tr>
                    <td>
                        <!-- Main section starts here-->
                        <form name="frmDownload" action="" method="POST">
                            <table width="100%"  border="0" class="customize-tbl" cellpadding="0" cellspacing="0">
                                <?php if($ftpzip=='ZIP' || $ftpzip=='FTP/ZIP' || $ftpzip=='ZIP/SUBFOLDER' || $ftpzip=='FTP/ZIP/SUBFOLDER' ){ ?>
                                <tr>
                                    <td class=maintext align=left>
                                        <input type=radio name=downloadformat value="1" checked><?php echo DOWNLOAD_ZIP;?> <br>
                                      <span> <?php echo DOWNLOAD_OPTION_DESCRIPTION;?></span> 
                                    </td>
                                </tr>
                                <?php }
                                if($ftpzip=='FTP' || $ftpzip=='FTP/ZIP' || $ftpzip=='FTP/SUBFOLDER' || $ftpzip=='FTP/ZIP/SUBFOLDER' ){ ?>
                                <tr>
                                    <td class=maintext align=left>
                                        <input type=radio name=downloadformat value="2" checked ><?php echo DOWNLOAD_PUBLISH_FTP;?> <br>
                                       <span>  <?php echo DOWNLOAD_PUBLISH_SERVER;?></span>
                                    </td>
                                </tr>
                                <?php }
                                if($ftpzip=='SUBFOLDER' || $ftpzip=='FTP/SUBFOLDER' || $ftpzip=='ZIP/SUBFOLDER' || $ftpzip=='FTP/ZIP/SUBFOLDER' ){ ?>
                                <tr>
                                    <td class=maintext align=left>
                                        <?php
                                        $rootPath = getSettingsValue('root_directory');
                                        ?>
                                        <input type=radio name=downloadformat value="3" checked ><?php echo DOWNLOAD_PUBLISH_SUBLOCATION;?><?php echo $rootPath?><?php echo DOWNLOAD_PUBLISH_SUBLOCATION2;?> <br>
                                      <span> <?php echo DOWNLOAD_PUBLISH_SUBLOCATION_DESC;?></span>
                                        
                                    </td>
                                </tr>
                                <?php } if($ftp_preset == 'Y') { ?>
                                <tr>
                                    <td class=maintext align=left>
                                        <?php $ftp_root_url    = getSettingsValue('ftp_root_url');?>
                                        <input type=radio name=downloadformat value="4"><?php echo DOWNLOAD_PUBLISH_UPLOAD;?> <?php echo $ftp_root_url?><?php echo DOWNLOAD_PUBLISH_UPLOAD_LOCATION;?> <br>
                                       <span><?php echo DOWNLOAD_PUBLISH_UPLOAD_LOCATION_DESC;?></span>
                                    </td>
                                </tr>
                                <tr>
                                    <td class=maintext align=left>
                                        <input type=radio name=downloadformat value="5" ><?php echo DOWNLOAD_PUBLISH_FB;?><br>
                                      <span> <?php echo DOWNLOAD_PUBLISH_FB_DESIRED;?></span>
                                    </td>
                                </tr>
                                <?php } ?>
                                <tr>
                                    <td colspan=2><br>&nbsp;
                                        <input class="grey-btn02" type="button" name=btnback value="<?php echo TEMPLATE_SAVE;?>" onclick="window.location='editor.php'"> &nbsp;
                                        <input class=btn04 type=submit name=submitdownload value="<?php echo TEMPLATE_CONTINUE;?>">
                                        <input class=grey-btn02 type=button name=btnpreviews value="<?php echo TEMP_PREVIEW;?>" onclick="showpreview();" >
                                    </td>
                                </tr>
                                
                            </table>
                        </form>
                        <!-- Main section ends here-->
                    </td>
                </tr>
               <?php } ?>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

<?php
include "includes/userfooter.php";
?>