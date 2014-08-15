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

ini_set('max_execution_time', '150');

$siteid = $_SESSION['siteDetails']['siteId'];
$userid = $_SESSION["session_userid"];
$message="";
$conn_id="";

$userDetails = getUserDetails($userid);

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
                $message.= "FTP Connection Failed";
            } else {
                //ftp info is fine
                $message="FTP Connection Success";
            }

        }else {
            $message= "Cannot Connect to ftp server.Invalid login info";
        }
    }else {
        $message= "Cannot Connect to ftp server.Invalid domain provided!";
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
                            $log.= "successfully uploaded ".$file."/".$files;
                        } else {
                            $log.= "There was a problem while uploading ".$file."/".$files;
                        }
                    }
                }
                closedir($dh);
            }
            return $log;
        }

    }else {
        if (@ftp_put($conn_id, $remote_path, $local_path, FTP_BINARY)) {
            $log.= "successfully uploaded ".$file;
            //if guest book provide write permission
            if($file=="gb.txt") {
                ftp_site($conn_id, 'CHMOD 0777 '.$remote_path);
            }
        } else {
            $log.= "There was a problem while uploading ".$file;
        }
    }
    return $log;
}



    // get user input here
    $ftp_user_domain = $_POST["vuser_domain"];
    $ftp_user_name = $_POST["vuser_name"];
    $ftp_user_pass = $_POST["vuser_password"];

    // check if ftp info is currect
    $result = check_ftp_info($ftp_user_domain,$ftp_user_name,$ftp_user_pass);

    //if well connected to site upload files
    if($result=="ok") {
        // upload  file locations
        
        $local_dir  = USER_SITE_UPLOAD_PATH.$siteid; 
        $remote_dir = $_POST["vuser_location"];    
        $log.= ftp_dir($local_dir, $remote_dir); 

        // close the connection
        @ftp_close($conn_id);
        $message = "success";

        // Update publish flag in  Site Master table
        updateSitePublishStatus($siteid);

         // Send site created mail
         // sendSiteCreatedMail($userDetails);
     
}else {
	$message="failed";
}
?>