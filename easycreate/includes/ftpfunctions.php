<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: Naseema N A<naseema.n@armia.com>                            |
// |                                                                      |
// +----------------------------------------------------------------------+


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
                $message.= "Attempted to connect to $domain for user $user</font>";
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
function ftp_dir($local_dir,$remote_dir,$siteNameModified) { 

    global $conn_id;

    $remote_dir = $remote_dir.$siteNameModified.'/'; 
    if(!is_dir($remote_dir))
    @ftp_mkdir($conn_id, $remote_dir);

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

            /*
            //if guest book provide write permission
            if($file=="gb.txt") {
                ftp_site($conn_id, 'CHMOD 0777 '.$remote_path);
            } */
        } else {
            $log.= "<font class=redtext>There was a problem while uploading ".$file."</font><br>";
        }
    }
    return $log;
}



?>