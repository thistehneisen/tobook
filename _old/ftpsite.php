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
include "includes/sitefunctions.php";

if ($_POST['btnback'] == "Back") {
	$location= "downloadsite.php";
    header("location:$location");
    exit;
}

ini_set('max_execution_time', '150');

$siteid = $_SESSION['siteDetails']['siteId'];
$userid = $_SESSION["session_userid"];
$message="";
$conn_id="";

$userDetails = getUserDetails($userid);

//handle post back of upload
$act=$_GET["act"];

//get the root directory from look up table
$rootdir = getSettingsValue('root_directory');

//function to check if ftp entered values are correct
function check_ftp_info($domain,$user,$pass) {

    global $conn_id;
    $hostip = gethostbyname($domain);

    //check if ftp domain is correct
    if(@ftp_connect($hostip)) {
        $conn_id = @ftp_connect($hostip);

        // login with username and password
        if($login_result=@ftp_login($conn_id, $user, $pass)) {

            //turn passive mode on
            @ftp_pasv ( $conn_id, true );

            if ((!$conn_id) || (!$login_result)) {//see if ftp info is incorrect
                $message.= "<font class=redtext>FTP connection has failed!";
                $message.= "Attempted to connect to $host for user $ftp_user_name</font>";
            } else {
                //ftp info is fine
                $message="ok";
            }

        }else {
            $message= "<font class=redtext>Cannot Connect to ftp server.Invalid login info! </font>";
        }
    }else {
        $message= "<font class=redtext>Cannot Connect to ftp server.Invalid domain provided!</font> ";
    }
    return $message;
}


//function to browse the files & directories
function ftp_dir($local_dir,$remote_dir) {

    global $conn_id;

    if (is_dir($local_dir)) {
        
        if ($dh = opendir($local_dir)) {

            while (($file = readdir($dh)) !== false) {

                //avoid unwanted files and directories
                if (($file != ".") && ($file != "..") && ($file != "thumbimages") && ($file != "Thumbs.db") &&  ($file != "resource.txt") ) {
                    $local_path=$local_dir."/".$file;
                    $remote_path=$remote_dir."/".$file;

                    if(is_dir($local_dir."/".$file)=="1") {
                        $log.=do_ftp($local_path,$remote_path,$file,'dir');
                    }

                    if(is_file($local_dir."/".$file)=="1") {
                        $log.=do_ftp($local_path,$remote_path,$file,'file');
                    }
                }
            }
            closedir($dh);
        }
        return $log;
    }
}

//function to upload files/dirs
function do_ftp($local_path,$remote_path,$file,$type) {

    global $conn_id;
    if($type=="dir") {

        @ftp_mkdir($conn_id, $remote_path);
        if (is_dir($local_path)) {

            if ($dh = opendir($local_path)) {

                while (($files = readdir($dh)) !== false) {

                    if (($files != ".") && ($files != "..") && ($files != "thumbimages") && ($files != "Thumbs.db") &&  ($files != "resource.txt") ) {
                        $local_file=$local_path."/".$files;
                        $remote_file=$remote_path."/".$files;

                        if (@ftp_put($conn_id, $remote_file, $local_file, FTP_BINARY)) {
                            $log.= "<font class=greentext>successfully uploaded ".$file."/".$files."</font><br>";
                        } else {
                            $log.= "<font class=redtext>There was a problem while uploading ".$file."/".$files."</font><br>";
                        }
                    }
                }
                closedir($dh);
            }
            return $log;
        }

    }else {

        if (@ftp_put($conn_id, $remote_path, $local_path, FTP_BINARY)) {

            $log.= "<font class=greentext>successfully uploaded ".$file."</font><br>";

            //if guest book provide write permission
            if($file=="gb.txt") {
                ftp_site($conn_id, 'CHMOD 0777 '.$remote_path);
            }
        } else {
            $log.= "<font class=redtext>There was a problem while uploading ".$file."</font><br>";
        }
    }
    return $log;
}


if($act=="post") {

    // get user input here
    $ftp_user_domain = $_POST["vuser_domain"];
    $ftp_user_name = $_POST["vuser_name"];
    $ftp_user_pass = $_POST["vuser_password"];

    
    //check if ftp info is currect
    $result = check_ftp_info($ftp_user_domain,$ftp_user_name,$ftp_user_pass);

    //if well connected to site upload files
    if($result=="ok") {
        $log.= "<font class=greentext><b>Connected to $ftp_user_domain, for user $ftp_user_name</b><br></font>";
        // upload  file locations
        
        $local_dir  = USER_SITE_UPLOAD_PATH.$siteid; 
        $remote_dir = $_POST["vuser_location"];    
        $log.= ftp_dir($local_dir, $remote_dir); 

        // close the connection
        @ftp_close($conn_id);
        $message="FTP session completed";
        $messageClass = "msggreen";

        // Update publish flag in  Site Master table
        updateSitePublishStatus($siteid);

        // Send site created mail
         sendSiteCreatedMail($userDetails);
     
    }else {
        $message=$result;
        $messageClass = "errormessage";
    }

}

$location=htmlentities($_POST["vuser_location"]);
/*
if(empty($location)) {
    $location="$rootdir";
}
*/
include "includes/userheader.php";
?>


<script>

    function validate(){

        //alert(document.uploadForm.nsite_id.options[document.uploadForm.nsite_id.selectedIndex].value;);

        if(document.uploadForm.vuser_domain.value==""){
            alert("Domain Name cannot be empty");
            document.uploadForm.vuser_domain.focus();
            return false;

        }else if(document.uploadForm.vuser_location.value==""){

            alert("Remote Directory cannot be empty");
            document.uploadForm.vuser_location.focus();
            return false;

        }else if(document.uploadForm.vuser_name.value==""){

            alert("User Name cannot be empty");
            document.uploadForm.vuser_name.focus();
            return false;

        }else if(document.uploadForm.vuser_password.value==""){

            alert("Password cannot be empty");
            document.uploadForm.vuser_password.focus();
            return false;

        }else{
            document.uploadForm.submit();
        }

    }
</script>




<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align="center">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left">
                        <h2>FTP</h2>
                    </td>
                </tr>
                <tr>
                    <td class="<?php echo $messageClass;?>"><?php echo $message; ?></td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <?php if($act=="post" && $result=="ok") { ?>
                <tr>
                    <td>
                        FTP Status Window
                        <table width="100%"  border="0" class="customize-tbl" cellpadding="0" cellspacing="0">
                            <tr>
                                <td> <?php echo $log;?><br> </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <?php }else{ ?>
                <tr>
                    <td>
                        <!-- Main section starts here-->
                        <form name="uploadForm" method=post  action="ftpsite.php?act=post">
                            <table width="100%"  border="0" class="customize-tbl" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td colspan="4" class="maintext">
                                        To upload your site files directly to your server please provide the ftp information below
                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td class=maintext width="22%" align="left">Domain Name <span style="color:red;">*</span></td>
                                    <td colspan=5 align=left width="30%">
                                        <input type=text class=textbox name="vuser_domain" id="vuser_domain" maxlength="100"  value="<?php echo htmlentities($_POST["vuser_domain"])?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class=maintext width="22%" align="left">Remote Directory <span style="color:red;">*</span></td>
                                    <td colspan=5 align=left width="30%">
                                        <input type=text class=textbox name="vuser_location" id="vuser_location" maxlength="100"  value="<?php echo $location;?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class=maintext width="22%" align="left">User Name  <span style="color:red;">*</span></td>
                                    <td colspan=5 align=left width="30%">
                                        <input type=text  class=textbox name="vuser_name" id="vuser_name" maxlength="20"  value="<?php echo htmlentities($_POST["vuser_name"])?>">
                                    </td>
                                </tr>
                                <tr>
                                    <td class=maintext width="22%" align="left">Password  <span style="color:red;">*</span></td>
                                    <td colspan=5 align=left width="30%">
                                        <input style="height:25px;width: 685px;"  class=textbox type=password name="vuser_password" id="vuser_password" maxlength="50" value="<?php echo htmlentities($_POST["vuser_password"])?>">
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td colspan=3><br>&nbsp;
                                        <input type="submit" value="Back" name="btnback" class="grey-btn02">
                                        <input class=btn01  type="button" onClick="validate();" value="Upload">
                                        <input type=button  class="btn02" value="Site Manager" onClick="return reg_check(this);">
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


<script language="javascript">
    function reg_check(myForm) {
        window.location = '<?php echo BASE_URL;?>sitemanager.php';
        return false;
    }
</script>
<?php
include "includes/userfooter.php";
?>