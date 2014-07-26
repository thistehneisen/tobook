<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: girish<girish@armia.com>                                  |
// |                                                                                                      // |
// +----------------------------------------------------------------------+
?>
<?php
$curTab = 'settings'; 
$hid_tab=0;
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

$settingsValue = getSettingsValue('paymentsupport'); 

//make the updations
$act		= $_POST["act"];
//echopre($_POST);
$message	= "";
$messageClass	= "";
If($act=="changepass") {//update password
    $sql="UPDATE tbl_lookup SET vvalue='".md5(addslashes($_POST["password"]))."' where vname='admin_pass'";
    mysql_query($sql,$con);
    $passmessage=MSG_PSWD_UPDATED."<br>&nbsp;";
    $hid_tab =3;
}

// Update Registration form field setup
If($act=="changeform") { 
    $formfiledarray     = array();

    $formfiledarray['login'][0]     = $_POST["login_check"];
    $formfiledarray['login'][1]     = $_POST["login_mandatory"];

    $formfiledarray['password'][0]  = $_POST["password_check"];
    $formfiledarray['password'][1]  = $_POST["password_mandatory"];

      
    $formfiledarray['confirmpassword'][0]  = $_POST["confirmpassword_check"];
    $formfiledarray['confirmpassword'][1]  = $_POST["confirmpassword_mandatory"];
    
    
    $formfiledarray['firstName'][0] = $_POST["firstName_check"];
    $formfiledarray['firstName'][1] = $_POST["firstName_mandatory"];

    $formfiledarray['lastName'][0]  =  $_POST["lastName_check"];
    $formfiledarray['lastName'][1]  =  $_POST["lastName_mandatory"];

    $formfiledarray['address1'][0]  =  $_POST["address1_check"];
    $formfiledarray['address1'][1]  =  $_POST["address1_mandatory"];
/*
    $formfiledarray['address2'][0]  =  $_POST["address2_check"];
    $formfiledarray['address2'][1]  =  $_POST["address2_mandatory"];
*/
    $formfiledarray['city'][0]      =  $_POST["city_check"];
    $formfiledarray['city'][1]      =  $_POST["city_mandatory"];

    $formfiledarray['state'][0]     =  $_POST["state_check"];
    $formfiledarray['state'][1]     =  $_POST["state_mandatory"];

    $formfiledarray['zip'][0]       =  $_POST["zip_check"];
    $formfiledarray['zip'][1]       =  $_POST["zip_mandatory"];

    $formfiledarray['country'][0]   =  $_POST["country_check"];
    $formfiledarray['country'][1]   =  $_POST["country_mandatory"];

    $formfiledarray['email'][0]     =  $_POST["email_check"];
    $formfiledarray['email'][1]     =  $_POST["email_mandatory"];

    $formfiledarray['phone'][0]     =  $_POST["phone_check"];
    $formfiledarray['phone'][1]     =  $_POST["phone_mandatory"];

    $formfiledarray['fax'][0]       =  $_POST["fax_check"];
    $formfiledarray['fax'][1]       =  $_POST["fax_mandatory"];

    $formfiledarray                 = serialize($formfiledarray);
    $sql="UPDATE tbl_lookup SET vvalue='".$formfiledarray."' where vname='signupfield_disp'";
    mysql_query($sql,$con);
    $formFieldMessage="<font color='green'><b><br>&nbsp;".MSG_REGFORM_UPDATED."<br>&nbsp;</b></font>";
    $hid_tab =2;
}

if ($act=="post") { 
    if (get_magic_quotes_gpc()) {
        $txtLicenseKey			= stripslashes($_POST["txtLicenseKey"]);
        $admin_mail 			= stripslashes($_POST["admin_mail"]);
        $theme                          = stripslashes($_POST["theme"]);
        $user_max_storage 		= stripslashes($_POST["user_max_storage"]);
        $user_publish_option            = stripslashes($_POST["user_publish_option"]);
        $day_maintain_temp 		= stripslashes($_POST["day_maintain_temp"]);
        $site_name 			= stripslashes($_POST["site_name"]);
        $root_directory 		= stripslashes($_POST["root_directory"]);
        $paymentsupport			= stripslashes($_POST["paymentsupport"]);
        $site_price                     = stripslashes($_POST['site_price']);
        $adsense_code                   = stripslashes(trim($_POST["adsense_code"]));
        $google_analytics               = stripslashes($_POST['google_analytics']);
        
        $enable_ftp_preset              = stripslashes($_POST['enable_ftp_preset']);
        $ftp_location                   = stripslashes($_POST['ftp_location']);
        $ftp_host                       = stripslashes($_POST['ftp_host']);
        $ftp_username                   = stripslashes($_POST['ftp_username']);
        $ftp_password                   = stripslashes($_POST['ftp_password']);
        $ftp_root_url                   = stripslashes($_POST['ftp_root_url']);
        
        $currency                       = stripslashes($_POST['currency']);
        $facebook_api_id                = stripslashes($_POST['facebook_api_id']);
        $facebook_api_secret            = stripslashes($_POST['facebook_api_secret']);

        $enable_home_social_shares      = isset($_POST["enable_home_social_shares"])? $_POST["enable_home_social_shares"]:"N"; 
        $facebook_url                   = stripslashes($_POST['facebook_url']);
        $twitter_url                    = stripslashes($_POST['twitter_url']);
        $googleplus_url                 = stripslashes($_POST['googleplus_url']);
        
        $site_language_option           = stripslashes($_POST['site_language_option']);

        

        
    } else {
        $txtLicenseKey			 = $_POST["txtLicenseKey"];
        $admin_mail			 = $_POST["admin_mail"];
        $theme				 = $_POST["theme"];
        $user_max_storage 		 = $_POST["user_max_storage"];
        $day_maintain_temp 		 = $_POST["day_maintain_temp"];
        $user_publish_option             = $_POST["user_publish_option"];
        $site_name                       = $_POST["site_name"];
        $root_directory 		 = $_POST["root_directory"];
        $paymentsupport			 = $_POST["paymentsupport"];
        $site_price			 = $_POST['site_price'];
        $adsense_code                    = trim($_POST["adsense_code"]);
        $google_analytics                = $_POST['google_analytics'];
        $enable_ftp_preset               = isset($_POST["enable_ftp_preset"])? $_POST["enable_ftp_preset"]:"N";
        $ftp_location                    = $_POST['ftp_location'];
        $ftp_host                        = $_POST['ftp_host'];
        $ftp_username                    = $_POST['ftp_username'];
        $ftp_password                    = $_POST['ftp_password'];
        $ftp_root_url                    = $_POST['ftp_root_url'];
        $currency                        = $_POST['currency'];
        $facebook_api_id                 = $_POST['facebook_api_id'];
        $facebook_api_secret             = $_POST['facebook_api_secret'];

        $enable_home_social_shares      = isset($_POST["enable_home_social_shares"])? $_POST["enable_home_social_shares"]:"N";
        $facebook_url                   = $_POST['facebook_url'];
        $twitter_url                    = $_POST['twitter_url'];
        $googleplus_url                 = $_POST['googleplus_url'];

        $site_language_option           = $_POST['site_language_option'];
    }
    $adsense_flag   = isset($_POST["adsense_flag"])? $_POST["adsense_flag"]:"NO";

    $hid_tab = 0;
    //update individual values
    //updateSettingsValue($admin_mail,'admin_mail');
    
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($admin_mail)."' where vname='admin_mail'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($theme)."' where vname='theme'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($user_max_storage)."' where vname='user_max_storage'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($user_publish_option)."' where vname='user_publish_option'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($day_maintain_temp)."' where vname='day_maintain_temp'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($site_name)."' where vname='site_name'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($root_directory)."' where vname='root_directory'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($paymentsupport)."' where vname='paymentsupport'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($site_price)."' where vname='site_price'";
    if($site_price != '') mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($google_analytics)."' where vname='google_analytics'";
    mysql_query($sql,$con);
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($currency)."' where vname='currency'";
    mysql_query($sql,$con);
    
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($site_language_option)."' where vname='site_language_option'";
    mysql_query($sql,$con);

    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($enable_ftp_preset)."' where vname='enable_ftp_preset'";
    mysql_query($sql,$con);
    if($enable_ftp_preset=='Y'){
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($ftp_location)."' where vname='ftp_location'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($ftp_host)."' where vname='ftp_host'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($ftp_username)."' where vname='ftp_username'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($ftp_password)."' where vname='ftp_password'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($ftp_root_url)."' where vname='ftp_root_url'";
        mysql_query($sql,$con);

        
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($facebook_api_id)."' where vname='facebook_api_id'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($facebook_api_secret)."' where vname='facebook_api_secret'";
        mysql_query($sql,$con);
    }

    // Update Google Adsense
    $sql="UPDATE tbl_lookup SET vvalue='".mysql_real_escape_string($adsense_flag)."' where vname='adsense_flag'";
    mysql_query($sql,$con);
    if($adsense_flag=='YES'){
        $sql="UPDATE tbl_lookup SET vvalue='".mysql_real_escape_string($adsense_code)."' where vname='adsense_code'";
        mysql_query($sql,$con);
    }
    // Update Google Adsense

    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($enable_home_social_shares)."' where vname='enable_home_social_shares'";
    mysql_query($sql,$con);
    if($enable_home_social_shares=='Y'){
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($facebook_url)."' where vname='facebook_url'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($twitter_url)."' where vname='twitter_url'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($googleplus_url)."' where vname='googleplus_url'";
        mysql_query($sql,$con);
    }

    

    if ( $enable_ftp_preset == "Y") {
        if ( !isNotNull($ftp_location)) {
            $message .= "* ".MSG_FTP_LOC_EMP."!<br>";
            $error = true;
        }
        if ( !isNotNull($ftp_host)) {
            $message .= "* ".MSG_FTP_HOST_EMP."!<br>";
            $error = true;
        }
        if ( !isNotNull($ftp_username)) {
            $message .= "* ".MSG_FTP_UNAME_EMP."!<br>";
            $error = true;
        }
        if ( !isNotNull($ftp_password)) {
            $message .= "* ".MSG_FTP_PSWD_EMP."!<br>";
            $error = true;
        }
        if ( !isNotNull($ftp_root_url)) {
            $message .= "* ".MSG_FTP_ROOTURL_EMP."!<br>";
            $error = true;
        }

        if ( !isNotNull($facebook_api_id)) {
            $message .= "* ".MSG_FB_APPID_EMP."!<br>";
            $error = true;
        }
        if ( !isNotNull($facebook_api_secret)) {
            $message .= "* ".MSG_FB_APPSECRET_EMP."!<br>";
            $error = true;
        }

        
        // Directory permission check
        if(!empty($ftp_location) && !empty($ftp_host) && !empty($ftp_username) && !empty($ftp_password)){
            $hostip = gethostbyname($ftp_host);
             if(@ftp_connect($hostip)) {
                $conn_id = @ftp_connect($hostip) or die('could not connect');
                $login_result=@ftp_login($conn_id, $ftp_username, $ftp_password);
                if($login_result){ 
                    $mode = '0777';
                     if(ftp_chdir($conn_id, $ftp_location)) {
                        /* if (!@ftp_chmod($conn_id, eval("return({$mode});"), $ftp_location)) {
                            $message .= "* No permission has set for the given FTP Directory. Please set the permission manually !<br>";
                            $error = true;
                        } */
                    }else{
                        $message .= "* ".MSG_FB_DIR_EXIST." !<br>";
                        $error = true;
                    }
                }else{
                    $message .= '* '.MSG_FTP_CREDENTIALS.' <br/>';
                    $error = true;
                }
            }
        }
    }

    if ($adsense_flag == "YES") {
        if ( !isNotNull($adsense_code)) {
            $message .= "* ".MSG_GOOGLE_ADSENSE_EMP."!<br>";
            $error = true;
        }
    }

    

    if ($error) {
        $message =MSG_ERROR_FOUND."!<br>" . $message;
        $messageClass = 'errormessage';
    } else {
        $message	= MSG_SETTINGS_UPDATED."<br>&nbsp;";
        $messageClass = 'successmessage';

        if($_FILES['Logourl']['tmp_name']!="") {
            $type = ImageType($_FILES['Logourl']['tmp_name']);
            $type = substr($type, 0, strpos($type, ':'));

            // to prevent executable file uploading
            $flag =0;
            $filename1	=	$_FILES['Logourl']['name'];
            $blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
            foreach ($blacklist as $file) {
                if(preg_match("/\.$file\$/i", "$filename1")) {
                    $message = MSG_LOGO_FORMATS ;
                    $flag =1;
                }
            }

            if($flag == 0) {
                if (($type != "jpg") && ($type != "png") && ($type != "gif")) {
                    $message = MSG_LOGO_FORMATS ;
                } else {
                    //manage uploaded logo
                    $file_name=$_FILES['Logourl']['name'];
                    move_uploaded_file($_FILES['Logourl']['tmp_name'], "../samplelogos/" . $file_name);
                    chmod("../samplelogos/" . $file_name,0777);
                    list($originalwidth, $originalheight, $originaltype) = getimagesize("../samplelogos/".$file_name);

                    if($originalwidth<=300 and $originalheight<=80) {
                        ;
                    } else {
                        $resizedimage="../samplelogos/".$file_name;
                        if ($originalwidth >=300) {
                            $imagewidth=300;
                        } else {
                            $imagewidth=$originalwidth;
                        }
                        if($originalheight >=80) {
                            $imageheight=80;
                        } else {
                            $imageheight=$originalheight;
                        }

                        ResizeImageTogivenWitdhAndHeight("../samplelogos/".$file_name,$imageheight,$imagewidth,$resizedimage);
                    }
                    //update logo value
                    $sql="UPDATE tbl_lookup SET vvalue='samplelogos/".$file_name."' where vname='Logourl'";
                    mysql_query($sql,$con);
                    $message=MSG_LOGO_UPDATE;
                    $_SESSION["session_logourl"]="samplelogos/".$file_name;
                    $Logourl	= "samplelogos/".$file_name;
                }
            }
        }
    }
    if ($message == "")
        $message	= MSG_SETTINGS_UPDATED."<br>&nbsp;";
}


// Created Site Settings
if($act=="created_site_post") {
    $enable_created_site_google_analytics   = isset($_POST["enable_created_site_google_analytics"])? $_POST["enable_created_site_google_analytics"]:"N";
    $enable_google_adsense                  = isset($_POST["enable_google_adsense"])? $_POST["enable_google_adsense"]:'0';
    $enable_menu                            = isset($_POST["enable_menu"])? $_POST["enable_menu"]:'0';
    $enable_social_share                    = isset($_POST["enable_social_share"])? $_POST["enable_social_share"]:'0';
    $enable_contact_form                    = isset($_POST["enable_contact_form"])? $_POST["enable_contact_form"]:'0';
    $enable_html                            = isset($_POST["enable_html"])? $_POST["enable_html"]:'0';
    $enable_slider                          = isset($_POST["enable_slider"])? $_POST["enable_slider"]:'0';
    $enable_form                            = isset($_POST["enable_form"])? $_POST["enable_form"]:'0';
    $enable_fb                              = isset($_POST["enable_fb"])? $_POST["enable_fb"]:'0';
    $enable_twiter                          = isset($_POST["enable_twiter"])? $_POST["enable_twiter"]:'0';
    $enable_linkedin                        = isset($_POST["enable_linkedin"])? $_POST["enable_linkedin"]:'0';

    $enable_created_site_banner             = isset($_POST["enable_created_site_banner"])? $_POST["enable_created_site_banner"]:'0';
    $created_site_banner_name               = isset($_FILES["created_site_banner_name"])? $_FILES["created_site_banner_name"]:'';
    $created_site_banner_link               = isset($_POST["created_site_banner_link"])? $_POST["created_site_banner_link"]:'';
    

    if($_FILES['created_site_banner_name']['tmp_name']!="") {
            $type = ImageType($_FILES['created_site_banner_name']['tmp_name']);
            $type = substr($type, 0, strpos($type, ':'));

            // to prevent executable file uploading
            $flag =0;
            $filename1 = $_FILES['created_site_banner_name']['name'];
            $blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
            foreach ($blacklist as $file) {
                if(preg_match("/\.$file\$/i", "$filename1")) {
                    $message = MSG_BANNER_FORMATS ;
                    $flag =1;
                }
            }

            if($flag == 0) {
                if (($type != "jpg") && ($type != "png") && ($type != "gif")) {
                    $message = MSG_BANNER_FORMATS ;
                } else {
                    //manage uploaded logo
                    $file_name      = $_FILES['created_site_banner_name']['name'];
                    $file_path_info = pathinfo($file_name); 
                    $file_name_new  = 'sb_banner_'.time().'.'.$file_path_info['extension'];
                    
                    // Create Directories if not exists
                    if (!@is_dir("../uploads"))
                    @mkdir("../uploads");

                    if (!@is_dir("../uploads/siteimages"))
                    @mkdir("../uploads/siteimages");

                    if (!@is_dir("../uploads/siteimages/banners"))
                    @mkdir("../uploads/siteimages/banners");
                    
                    $imageUploadPath = "../".EDITOR_USER_IMAGES."banners/" . $file_name_new;
                    $imageUploadPathReal = BASE_URL.EDITOR_USER_IMAGES."banners/" . $file_name_new;
                    move_uploaded_file($_FILES['created_site_banner_name']['tmp_name'], $imageUploadPath);
                    chmod($imageUploadPath,0777);
                    list($originalwidth, $originalheight, $originaltype) = getimagesize($imageUploadPath);

                    if($originalwidth<=300 and $originalheight<=80) {
                        ;
                    } else {
                        $resizedimage=$imageUploadPath;
                        if ($originalwidth >=300) {
                            $imagewidth=300;
                        } else {
                            $imagewidth=$originalwidth;
                        }
                        if($originalheight >=80) {
                            $imageheight=80;
                        } else {
                            $imageheight=$originalheight;
                        }

                        ResizeImageTogivenWitdhAndHeight($imageUploadPath,$imageheight,$imagewidth,$resizedimage);
                    }
                    //update logo value
                    $sql="UPDATE tbl_lookup SET vvalue='".mysql_real_escape_string($imageUploadPathReal)."' where vname='created_site_banner_name'";
                    mysql_query($sql,$con);
                }
            }
        }
    
    $hid_tab = 5;
    
    $sql               =   "UPDATE tbl_lookup SET vvalue='".addslashes($enable_created_site_google_analytics)."' where vname='enable_created_site_google_analytics'";
    mysql_query($sql,$con);

    $sql               =   "UPDATE tbl_lookup SET vvalue='".addslashes($enable_created_site_banner)."' where vname='enable_created_site_banner'";
    mysql_query($sql,$con);
    
    $sql               =   "UPDATE tbl_lookup SET vvalue='".addslashes($created_site_banner_link)."' where vname='created_site_banner_link'";
    mysql_query($sql,$con);


    
    
    $sqlUpdateApps     =   "UPDATE tbl_editor_apps 
                            SET app_status = CASE 
                                WHEN app_alias='navigation_menu' THEN ".addslashes($enable_menu)." 
                                WHEN app_alias='social_shares' THEN ".addslashes($enable_social_share)." 
                                WHEN app_alias='contact_form' THEN ".addslashes($enable_contact_form)."
                                WHEN app_alias='html_widget' THEN ".addslashes($enable_html)."
                                WHEN app_alias='slider' THEN ".addslashes($enable_slider)."
                                WHEN app_alias='dynamic_form' THEN ".addslashes($enable_form)."
                                WHEN app_alias='google_adsense' THEN ".addslashes($enable_google_adsense)."
                            END ";
                            
    mysql_query($sqlUpdateApps,$con);
    
    
     $sqlUpdateAppsParams     =   "UPDATE tbl_editor_apps_params  
                                    SET param_status  = CASE 
                                        WHEN param_id='1' THEN ".addslashes($enable_fb)." 
                                        WHEN param_id='2' THEN ".addslashes($enable_twiter)." 
                                        WHEN param_id='3' THEN ".addslashes($enable_linkedin)."
                                    END ";
                            
    mysql_query($sqlUpdateAppsParams,$con);
    $sitecreatedmessage	= MSG_SETTINGS_UPDATED."<br>&nbsp;";
}


if ($act=="payment") {
    if (get_magic_quotes_gpc()) {
        $secureserver			= stripslashes($_POST["secureserver"]);
        $paypal_email 			= stripslashes($_POST["paypal_email"]);
       // $paypal_token 			= stripslashes($_POST["paypal_token"]);
        $auth_loginid 			= stripslashes($_POST["auth_loginid"]);
        $auth_txnkey			= stripslashes($_POST["auth_txnkey"]);
        $auth_pass			= stripslashes($_POST["auth_pass"]);
        $auth_email			= stripslashes($_POST["auth_email"]);
        $checkout_key			= stripslashes($_POST["checkout_key"]);
        $checkout_productid		= stripslashes($_POST["checkout_productid"]);
        $enable_gateways		= stripslashes($_POST["enable_gateways"]);
        $google_id 			= stripslashes($_POST["google_id"]);
        $google_key			= stripslashes($_POST["google_key"]);
        $linkpay_store			= stripslashes($_POST["linkpay_store"]);
    } else {
        $secureserver			 = $_POST["secureserver"];
        $paymentsupport			 = $_POST["paymentsupport"];
        $paypal_email 			 = $_POST["paypal_email"];
        //$paypal_token 			 = $_POST["paypal_token"];
        $auth_loginid 			 = $_POST["auth_loginid"];
        $auth_txnkey 			 = $_POST["auth_txnkey"];
        $auth_pass			 = $_POST["auth_pass"];
        $auth_email			 = $_POST["auth_email"];
        $checkout_key	 		 = $_POST["checkout_key"];
        $checkout_productid		 = $_POST["checkout_productid"];
        $enable_gateways		 = $_POST["enable_gateways"];
        $google_id 			 = $_POST["google_id"];
        $google_key			 = $_POST["google_key"];
        $linkpay_store			 = $_POST["linkpay_store"];
    }
    $hid_tab =1;
    //update individual values
    $sql="UPDATE tbl_lookup SET vvalue='".addslashes($secureserver)."' where vname='secureserver'";
    mysql_query($sql,$con);


    $enable_paypal	= isset($_POST["enable_paypal"])	?	$_POST["enable_paypal"]		:	"NO";
    $paypal_sandbox	= isset($_POST["paypal_sandbox"])	?	$_POST["paypal_sandbox"]	:	"NO";
    $auth_demo		= isset($_POST["auth_demo"])		?	$_POST["auth_demo"]		:       "NO";
    $checkout_demo	= isset($_POST["checkout_demo"])	?	$_POST["checkout_demo"]		:	"NO";
    $enable_gateways	= isset($_POST['enable_gateways'])	?	$_POST['enable_gateways']	:	"NO";
    $enable_google	= isset($_POST["enable_google"])	?	$_POST["enable_google"]		:	"NO";
    $google_demo	= isset($_POST["google_demo"])		?	$_POST["google_demo"]		:	"NO";
    $enable_linkpay	= isset($_POST["enable_linkpay"])	?	$_POST["enable_linkpay"]	:	"NO";
    $linkpay_demo	= isset($_POST["linkpay_demo"])		?	$_POST["linkpay_demo"]		:	"NO";
    $paymentsupport     = $settingsValue;

    if ($enable_paypal == "YES") {
        if ( !isNotNull($paypal_email)) {
            $message .= "* ".MSG_PP_ADDRESS_EMP."!<br>";
            $error = true;
        } else {
            if(!isValidEmail($paypal_email) ) {
                $message .= "* ".MSG_PP_EMAIL_INVALID."!<br>";
                $error = true;
            }
        }
        /*
        if (!isNotNull($paypal_token)) {
            $message .= "* Paypal Token is empty!<br>";
            $error = true;
        }*/
    } else if (isNotNull($paypal_email)) {
        if(!isValidEmail($paypal_email) ) {
            $message .= "* ".MSG_PP_EMAIL_INVALID."!<br>";
            $error = true;
        }
    }
    if ($enable_google == "YES") {
        if ( !isNotNull($google_id)) {
            $message .= "* ".MSG_GC_MERCHNT_ID_EMP."!<br>";
            $error = true;
        }
        if ( !isNotNull($google_key)) {
            $message .= "* ".MSG_GC_MERCHNT_KEY_EMP."!<br>";
            $error = true;
        }
    }

    if($enable_gateways == "AN" OR $enable_gateways == "LP") {
        ob_start();
        phpinfo(8);
        $info = ob_get_contents();
        ob_end_clean();
        $info = stristr($info, 'curl');
        preg_match('/\d/', $info, $match);
        $curl_ver = $match[0];
        if($curl_ver=="") {
            $message .= MSG_CURL_REQU. "<br>";
            $error = true;
        }
    }

    if ($paymentsupport == "yes") {
        if ($enable_gateways == "NO" AND $enable_paypal == "NO") {
            $message .= MSG_CHOOSE_PAYMNT." <br>";
            $error = true;
        }
    }

    if ($error) {
        $message =MSG_ERROR_FOUND . $message;
    } else {
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($enable_gateways)."' where vname='enable_gateways'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($enable_paypal)."' where vname='enable_paypal'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($paypal_sandbox)."' where vname='paypal_sandbox'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($paypal_email)."' where vname='paypal_email'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($paypal_token)."' where vname='paypal_token'";
        mysql_query($sql,$con);

        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($enable_google)."' where vname='enable_google'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($google_demo)."' where vname='google_demo'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($google_id)."' where vname='google_id'";
        mysql_query($sql,$con);
        $sql="UPDATE tbl_lookup SET vvalue='".addslashes($google_key)."' where vname='google_key'";
        mysql_query($sql,$con);

        if ($enable_gateways == "AN") {
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($auth_loginid)."' where vname='auth_loginid'";
            mysql_query($sql,$con);
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($auth_txnkey)."' where vname='auth_txnkey'";
            mysql_query($sql,$con);
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($auth_pass)."' where vname='auth_pass'";
            mysql_query($sql,$con);
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($auth_email)."' where vname='auth_email'";
            mysql_query($sql,$con);
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($auth_demo)."' where vname='auth_demo'";
            mysql_query($sql,$con);
        } else if ($enable_gateways == "CO") {
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($checkout_key)."' where vname='checkout_key'";
            mysql_query($sql,$con);
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($checkout_demo)."' where vname='checkout_demo'";
            mysql_query($sql,$con);
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($checkout_productid)."' where vname='checkout_productid'";
            mysql_query($sql,$con);
        } else if ($enable_gateways == "LP") {
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($linkpay_store)."' where vname='linkpay_store'";
            mysql_query($sql,$con);
            $sql="UPDATE tbl_lookup SET vvalue='".addslashes($linkpay_demo)."' where vname='linkpay_demo'";
            mysql_query($sql,$con);
        }

        $message	= MSG_SETTINGS_UPDATED."<br>&nbsp;";
    }
    if ($message == "")
        $message	= MSG_SETTINGS_UPDATED."<br>&nbsp;";
}

//get the currrent values
$sqlGetEditorApps   =   "SELECT * FROM tbl_editor_apps";
$resGetEditorApps = mysql_query($sqlGetEditorApps);
while ($rowGetEditorApps = mysql_fetch_array($resGetEditorApps)) {//echopre($rowGetEditorApps) ;
    switch($rowGetEditorApps['app_alias']){
        case 'navigation_menu':
                    if($rowGetEditorApps['app_status']=='1')
                        $enable_menu='1';
                    break;
         case 'social_shares':
                    if($rowGetEditorApps['app_status']=='1')
                        $enable_social_share='1';
                    break;
          case 'contact_form':
                    if($rowGetEditorApps['app_status']=='1')
                        $enable_contact_form='1';
                    break;
          case 'html_widget':
                    if($rowGetEditorApps['app_status']=='1')
                        $enable_html='1';
                    break;
           case 'slider':
                    if($rowGetEditorApps['app_status']=='1')
                        $enable_slider='1';
                    break;
           case 'google_adsense':
                    if($rowGetEditorApps['app_status']=='1')
                        $enable_google_adsense='1';
                    break;
    }
}
$sqlGetEditorParamApps   =   "SELECT * FROM  tbl_editor_apps_params";
$resGetEditorParamApps = mysql_query($sqlGetEditorParamApps);
while ($rowGetEditorParamApps = mysql_fetch_array($resGetEditorParamApps)) {//echopre($rowGetEditorParamApps) ;
    switch($rowGetEditorParamApps['param_id']){
        case '1':
                    if($rowGetEditorParamApps['param_status']=='1')
                        $enable_fb='1';
                    break;
         case '2':
                    if($rowGetEditorParamApps['param_status']=='1')
                        $enable_twiter='1';
                    break;
          case '3':
                    if($rowGetEditorParamApps['param_status']=='1')
                        $enable_linkedin='1';
                    break;
    }
}



$sql = "SELECT * FROM tbl_lookup";
$res = mysql_query($sql);
while ($row = mysql_fetch_array($res)) { 
    $vname	= stripslashes($row["vname"]);
    $vvalue	= stripslashes($row["vvalue"]);

    switch($vname) {
        case  admin_mail:
            $admin_mail=$vvalue;
            break;
        case  theme:
            $theme=$vvalue;
            break;
        case  user_max_storage:
            $user_max_storage=$vvalue;
            break;

        case  user_publish_option:
            $user_publish_option=$vvalue;
            break;

        case  day_maintain_temp:
            $day_maintain_temp=$vvalue;
            break;

        case  site_name:
            $site_name=$vvalue;
            break;

        case  site_price:
            $site_price=$vvalue;
            break;

        case  root_directory:
            $root_directory=$vvalue;
            break;

        case  auth_txnkey:
            $auth_txnkey=$vvalue;
            break;

        case  auth_loginid:
            $auth_loginid=$vvalue;
            break;

        case  auth_email:
            $auth_email=$vvalue;
            break;

        case  auth_pass:
            $auth_pass=$vvalue;
            break;

        case  auth_demo:
            $auth_demo=$vvalue;
            break;

        case  secureserver:
            $secureserver=$vvalue;
            break;

        case  Logourl:
            $Logourl=$vvalue;
            break;

        case  paymentsupport:
            $paymentsupport=$vvalue;
            break;

        case  enable_paypal:
            $enable_paypal=$vvalue;
            break;

        case  paypal_sandbox:
            $paypal_sandbox=$vvalue;
            break;

        case   paypal_email:
            $paypal_email=$vvalue;
            break;

        case   checkout_demo:
            $checkout_demo=$vvalue;
            break;

        case   checkout_key:
            $checkout_key=$vvalue;
            break;

        case checkout_productid:
            $checkout_productid=$vvalue;
            break;

        case   enable_gateways:
            $enable_gateways=$vvalue;
            break;

        case   rootserver:
            $rootserver=$vvalue;
            break;

        case   paypal_token:
            $paypal_token=$vvalue;
            break;

        case enable_google:
            $enable_google	= $vvalue;
            break;

        case google_demo:
            $google_demo	= $vvalue;
            break;

        case google_id:
            $google_id	= $vvalue;
            break;

        case google_key:
            $google_key	= $vvalue;
            break;

        case linkpay_store:
            $linkpay_store	= $vvalue;
            break;

        case linkpay_demo:
            $linkpay_demo	= $vvalue;
            break;

        case adsense_flag:
            $adsense_flag       = $vvalue;
            break;

        case adsense_code:
            $adsense_code       = $vvalue;
            break;

        case vLicenceKey:
            $txtLicenseKey	= $vvalue;
            break;
        case google_analytics:
            $google_analytics = $vvalue;
            break;
        case  signupfield_disp:
            $formfiledarray = unserialize($vvalue);
            break;

        
        case  enable_ftp_preset:
            $enable_ftp_preset = $vvalue;
            break;
        case  ftp_location:
            $ftp_location = $vvalue;
            break;
        case  ftp_host:
            $ftp_host = $vvalue;
            break;
        case  ftp_username:
            $ftp_username = $vvalue;
            break;
        case  ftp_password:
            $ftp_password = $vvalue;
            break;
        case  ftp_root_url:
            $ftp_root_url = $vvalue;
            break;
        
         case  facebook_api_id:
            $facebook_api_id = $vvalue;
            break;
         case  facebook_api_secret:
            $facebook_api_secret = $vvalue;
            break;

         case  currency:
            $currency = $vvalue;
            break;
        
        case  enable_created_site_google_analytics:
            $enable_created_site_google_analytics = $vvalue;
            break;

        case  enable_created_site_banner:
            $enable_created_site_banner = $vvalue;
            break;
        case  created_site_banner_name:
            $created_site_banner_name = $vvalue;
            break;
        case  created_site_banner_link:
            $created_site_banner_link = $vvalue;
            break;

        case  enable_home_social_shares:
            $enable_home_social_shares = $vvalue;
            break;
        case  facebook_url:
            $facebook_url = $vvalue;
            break;
        case  googleplus_url:
            $googleplus_url = $vvalue;
            break;
        case  twitter_url:
            $twitter_url = $vvalue;
            break;
        
        case  site_language_option:
            $site_language_option = $vvalue;
            break;

        
    }
}


If($act=="cleanup") {
    //function to clean up
    deleteOldData($day_maintain_temp,"../");
    $cleanmessage=MSG_CLEAN_UP."<br>&nbsp;";
    $hid_tab =4;
}

// Placed the below includes for : to apply site theme just after data update

include "includes/admin_functions.php"; 

echo '<script>';
 
foreach($adminvalidation as $key=>$val){
 echo 'var '.$key.'="'.$val.'";';
}
echo '</script>';
?>
<link rel="stylesheet" href="../style/jquery-ui.css" />
<script src="../js/jquery.js"></script>
<script src="../js/jquery-1.8.3.js"></script>
<script src="../js/jquery-ui.js"></script>
<script src="../js/settings.js"></script>
<script src="../js/common.js"></script>
<link rel="stylesheet" href="../style/common.css" />
<div class="admin-pnl">
        <h2><?php echo SETTINGS; ?></h2>
        <div>
            <div class="<?php echo $messageClass;?>"><?php echo $message;?></div>
            <input type="hidden" name="hidIndex" id="hidIndex" value="<?php echo $hid_tab?>">
        </div>


<!--NEW DESIGN-->

<div class="content-tab-pnl" id="accordion">

    <!-- General Settings -->
    <div class="content-tab-hd jDisplayIcon" val="1">
        <h5><?php echo GENERAL_SETTINGS;?></h5>
        <span>
            <?php
            if($hid_tab==0){?>
             <img id="img_1" type="open" class="jimage"  src="../style/images/accordian-arrow-open.png"></span>
            <?php
            }
            else{?>
                <img id="img_1" type="close" class="jimage"  src="../style/images/accordian-arrow-close.png"></span>
                <?php
            }?>
        <div class="clear"></div>
    </div>
    <form name="settingsForm" method="post" action="settings.php"  ENCTYPE="multipart/form-data" class="settingsform" >
        <input type="hidden" name="act" value="post">
    <div class="content-tab">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="350"><?php echo LICENSE_KEY; ?></td>
                <td width="6%">&nbsp;</td>
                <td>
                    <input style="width: 387px;" type="text" class="textbox" name="txtLicenseKey" value="<?php echo htmlentities($txtLicenseKey);?>" size="40" maxlength="40" readonly>
                    <a class="masterTooltip" title="<?php echo TOOLTIP_LICENSEKEY;?>">
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                    
                </td>
            </tr>
            <tr>
                <td ><?php echo DAY_MAINTAIN_TEMP; ?></td>
                <td>&nbsp;</td>
                <td>
                    <input style="width:355px;" type="text" name="day_maintain_temp" id="day_maintain_temp" class="textbox"  maxlength="8"  value="<?php echo htmlentities($day_maintain_temp); ?>" ><?php echo DAYS; ?>
                    <a class="masterTooltip"  title="<?php echo TOOLTIP_NO_DAYS;?>.">
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <td><?php echo USER_PUBLISH_OPTION; ?></td>
                <td>&nbsp;</td>
                <td>
                    <select name="user_publish_option"  id="user_publish_option" class="textbox">
                        <option value="FTP">FTP ONLY</option>
                        <option value="ZIP">ZIP ONLY</option>
                        <option value="SUBFOLDER">SUBFOLDER ONLY</option>
                        <option value="FTP/ZIP">FTP or ZIP</option>
                        <option value="FTP/SUBFOLDER">FTP or SUBFOLDER</option>
                        <option value="ZIP/SUBFOLDER">ZIP or SUBFOLDER</option>
                        <option value="FTP/ZIP/SUBFOLDER">FTP or ZIP or SUBFOLDER</option>
                    </select>
                     <a title="<?php echo TOOLTIP_OPTN_MODE;?>" class="masterTooltip" >
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <td><?php echo USER_MAX_STORAGE; ?></td>
                <td>&nbsp;</td>
                <td>
                    <select name="user_max_storage" id="user_max_storage" class="textbox">
                        <?php
                        for($i=5;$i<=500;$i=$i+5) {
                            echo "<option value=".$i.">".$i."</option>";
                        }
                        ?>
                    </select>
                    <a title="<?php echo TOOLTIP_MAX_SPACE_MB;?>" class="masterTooltip" >
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <td><?php echo ADMIN_MAIL; ?></td>
                <td>&nbsp;</td>
                <td>
                    <input style="width: 389px;" type="text" class="textbox" name="admin_mail" id="admin_mail" size="40" maxlength="100" value="<?php echo htmlentities($admin_mail); ?>" >
                     <a title="<?php echo TOOLTIP_EMAIL_ALERTS;?>" class="masterTooltip" >
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <td><?php echo THEME; ?></td>
                <td>&nbsp;</td>
                <td>
                    <?php
                    $option = "<select name='theme'>";
                    if ($handle = opendir('../themes')) {
                        while (false !== ($file = readdir($handle))) {
                            if ($file != "." && $file != "..") {
                                if($file == $theme) $selected = 'selected';
                                else $selected = '';
                                $option.="<option value='$file' $selected>".$file."</option>";
                            }
                        }
                        closedir($handle);
                    }
                    echo $option.'</select>';
                    ?>
                   
                    <a title=" <?php echo TOOLTIP_COLOR_SCHEME;?>" class="masterTooltip" >
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <td><?php echo CURRENCY;?></td>
                <td>&nbsp;</td>
                <td>
                    <select name="currency"  id="currency" class="textbox">
                        <?php foreach($currencyArray as $currencyData){ ?>
                        <option value="<?php echo $currencyData['code'] ?>" <?php echo ($currencyData['code']==$currency)?'selected':''; ?>><?php echo $currencyData['title'].'('.$currencyData['symbol'].')'; ?></option>
                        <?php } ?>
                    </select>
                    <a title=" Currency format" class="masterTooltip" >
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <td><?php echo PAYMENT_SUPPORT; ?></td>
                <td>&nbsp;</td>
                <td class=maintext align=left>
                    <input type=radio name="paymentsupport" id="paymentsupport" value="yes" <?php if($paymentsupport=="yes") echo "checked";?> onclick="checkpayment();"><?php echo PAID;?>
                    <input type=radio name="paymentsupport" id="paymentsupport1" value="no" <?php if($paymentsupport=="no") echo "checked";?> onclick="checkpayment1();"><?php echo FREE;?>
                    
                    <a title='<?php echo TOOLTIP_FREE;?>.' class="masterTooltip" >
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <div id="priceTr">
                        <td><?php echo SITE_PRICE; ?></td>
                        <td>&nbsp;</td>
                        <td><?php echo $currencyArray[$currency]['symbol'];?> <input style="width: 366px" type=text name="site_price" id="site_price" class="textbox04"  value="<?php echo htmlentities($site_price); ?>" >
                         <a title='<?php echo TOOLTIP_PAID;?>.' class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                        </td>
                </div>
            </tr>
            <tr>
                <td class="maintext"><?php echo SITE_NAME; ?>(<?php echo EG; ?> <?php echo($_SESSION["session_lookupsitename"]); ?>) </td>
                <td></td>
                <td>
                    <input type="text" class="textbox" name="site_name" id="site_name" maxlength="100" value="<?php echo htmlentities($site_name); ?>" >
                    <a title="<?php echo TOOLTIP_SITE_NAME;?>" class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                </td>
            </tr>
            <tr>
                <td class="maintext"><?php echo ROOT_DIRECTORY; ?> </td>
                <td>&nbsp;</td>
                <td align="left">
                    <input type="text" class="textbox" name="root_directory" id="root_directory" maxlength="100" value="<?php echo htmlentities($root_directory); ?>" >
                    <a title="<?php echo TOOLTIP_ROOT_DIR;?>" class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                </td>
            </tr>

            <tr>
                <td class="maintext"><?php echo SITE_LOGO; ?></td>
                <td>&nbsp;</td>
                <td>
                    <input type="file" class="textbox" name="Logourl" id="Logourl" >
                    <a title="<?php echo TOOLTIP_CHANGE_LOGO;?>" class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                </td>
            </tr>
            <tr>
                <td class="maintext"><?php echo CURRENT_LOGO; ?></td>
                <td>&nbsp;</td>
                <td>
                    <img src="../<?php echo $Logourl; ?>">
                </td>
            </tr>
            <!-- Analytics Code Management Area -->
            <tr>
                <td class="maintext"><?php echo ENABLE_GOOGLE_ADSENSE; ?></td>
                <td>&nbsp;</td>
                <td align="left">
                    <input type="radio" name="adsense_flag" id="adsense_flag" value="YES" <?php if($adsense_flag=="YES") echo "checked";?>><?php echo YES;?>
                    <input type="radio" name="adsense_flag" id="adsense_flag" value="NO" <?php if($adsense_flag=="NO") echo "checked";?>><?php echo NO;?>
                    
                    <a title="<?php echo TOOLTIP_GOOGLE_ADS;?>" class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                </td>
            </tr>
            <tr>
                <td  class="maintext"><?php echo GOOGLE_ADSENSE_CODE; ?></td>
                <td>&nbsp;</td>
                <td>
                    <textarea name="adsense_code" class="text_area" rows="10" cols="30"><?php echo stripcslashes($adsense_code); ?></textarea>
                    <a title=" <?php echo TOOLTIP_ADVT_CODE;?>." class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                   
                </td>
            </tr>
            <tr>
                <td class="maintext"><?php echo GOOGLE_ANALYTICS_CODE; ?></td>
                <td>&nbsp;</td>
                <td>
                    <textarea name="google_analytics" class="text_area" rows="10" cols="30">
                        <?php echo stripcslashes($google_analytics); ?>
                    </textarea>
                     <a title=" <?php echo TOOLTIP_GOOGLE_ANALYTICS;?>." class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                </td>
            </tr>
            <tr>
                <td><?php echo SITE_LANGUAGE_OPTION; ?></td>
                <td>&nbsp;</td>
                <td>
                    <select name="site_language_option"  id="site_language_option" class="textbox">
                        <option value="english" <?php echo ($site_language_option=='english')?"selected":"";?> >English</option>
                        <option value="other" <?php echo ($site_language_option=='other')?"selected":"";?>>Other</option>
                    </select>
                    <a title="<?php echo TOOLTIP_SITE_LANGUAGE_OPTION;?>" class="masterTooltip" >
                        <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>


            <!-- FTP Preset -->
            <tr>
                <td><?php echo SEND_SITE_DOMAIN;?> </td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_ftp_preset=='Y')?'checked':''; ?> name="enable_ftp_preset" class="jqToggle" value="Y" id="jqFtpPreset" >
                    
                    <a title=" <?php echo TOOLTIP_PUBLSH_OPT;?>" class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="jqFtpPreset">
                        <table width="100%">
                            <tr>
                                <td width="370" class="maintext"><?php echo FTP_DIR;?></td>
                                <td width="6%">&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="ftp_location" id="ftp_location" size="40" maxlength="40" value="<?php echo htmlentities($ftp_location); ?>" >
                                    
                                    <a title=" <?php echo TOOLTIP_FTP_DIR_PATH;?>public_html/samplesites/" class="masterTooltip" >
                            			<img height="15" border="0" src="../images/info_icon.png">
                        			</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo FTP_HOST;?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="ftp_host" id="ftp_host" maxlength="100" value="<?php echo htmlentities($ftp_host); ?>" >
                                    
                                    <a title=" <?php echo TOOLTIP_FTP_HOST_ADD;?>" class="masterTooltip" >
                            			<img height="15" border="0" src="../images/info_icon.png">
                        			</a>
                                
                                
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo FTP_UNAME;?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="ftp_username" id="ftp_username" maxlength="100" value="<?php echo htmlentities($ftp_username); ?>" >
                               
                                 <a title=" <?php echo TOOLTIP_FTP_UNAME;?>" class="masterTooltip" >
                            			<img height="15" border="0" src="../images/info_icon.png">
                        			</a>
                                
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo FTP_PASSWD;?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="password" class="textbox" name="ftp_password" id="ftp_password" maxlength="100" value="<?php echo htmlentities($ftp_password); ?>" >
                               
                               
                                 <a title=" <?php echo TOOLTIP_FTP_PSWD;?>" class="masterTooltip" >
                            			<img height="15" border="0" src="../images/info_icon.png">
                        			</a>
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo FTP_ROOT_URL;?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="ftp_root_url" id="ftp_root_url" maxlength="100" value="<?php echo htmlentities($ftp_root_url); ?>" >
                                
                                
                                 <a title="<?php echo TOOLTIP_WEBPATH_DOMAIN;?>" class="masterTooltip" >
                            			<img height="15" border="0" src="../images/info_icon.png">
                        			</a>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3" class="maintext"><?php echo FB_APP_DTLS?></td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo FB_APPLN_ID;?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="facebook_api_id" id="facebook_api_id" maxlength="100" value="<?php echo htmlentities($facebook_api_id); ?>" >
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo FB_APPLN_SECRET;?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="facebook_api_secret" id="facebook_api_secret" maxlength="100" value="<?php echo htmlentities($facebook_api_secret); ?>" >
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            


            <!-- Enable SocialShare -->
            <tr>
                <td><?php echo ENABLE_SOCIAL_SHARE?></td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_home_social_shares=='Y')?'checked':''; ?> name="enable_home_social_shares" class="jqToggle" value="Y" id="jqSocialShare" >
                        <a title="<?php echo TOOLTIP_SOCIAL_SHARE;?> " class="masterTooltip" >
                            <img height="15" border="0" src="../images/info_icon.png">
                        </a>
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="jqSocialShare">
                        <table width="100%">
                            <tr>
                                <td width="370" class="maintext"><?php echo FACEBOOK;?></td>
                                <td width="6%">&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="facebook_url" id="facebook_url" maxlength="40" value="<?php echo htmlentities($facebook_url); ?>" >
                                        <a title="<?php echo TOOLTIP_FACEBOOK;?>" class="masterTooltip" >
                                            <img height="15" border="0" src="../images/info_icon.png">
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo TWITTER;?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="twitter_url" id="twitter_url" maxlength="100" value="<?php echo htmlentities($twitter_url); ?>" >
                                        <a title="<?php echo TOOLTIP_TWITTER;?>" class="masterTooltip" >
                                            <img height="15" border="0" src="../images/info_icon.png">
                                        </a>
                                </td>
                            </tr>
                            <tr>
                                <td class="maintext"><?php echo GOOGLE_PLUS?></td>
                                <td>&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="googleplus_url" id="googleplus_url" maxlength="100" value="<?php echo htmlentities($googleplus_url); ?>" >
                                        <a title="<?php echo TOOLTIP_GOOGLE_PLUS;?>" class="masterTooltip" >
                                            <img height="15" border="0" src="../images/info_icon.png">
                                        </a>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <!-- Enable SocialShare -->
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="">
                    <div class="btn-box">
                        <input name="saveGeneral" type="button" class="btn01" onClick="validate();" value="<?php echo BTN_SAVE;?>">
                    </div>
                </td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>
    </form>
    <!-- General Settings -->


    <!-- Payment Settings -->
    <div id="paymentTab" class="content-tab-hd jDisplayIcon" val="2" style="display: <?php echo ($paymentsupport=='no')?'none':'block';?>">
        <h5><?php echo PAYMENT_SETTINGS;?></h5>
        <span>
            <?php
            if($hid_tab==1){?>
             <img id="img_2" type="open" class="jimage"  src="../style/images/accordian-arrow-open.png"></span>
            <?php
            }
            else{?>
                <img id="img_2" type="close" class="jimage"  src="../style/images/accordian-arrow-close.png"></span>
                <?php
            }?>
            
            
        <div class="clear"></div>
    </div>
    <form name="paymentSettingsForm" method="post" action="settings.php" class="settingsform">
        <input type="hidden" name="act" value="payment">
        <div class="content-tab">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td width="350"><?php echo SECURE_SERVER_URL;?></td>
                <td width="3%">&nbsp;</td>
                <td>
                    <input type="text" class="textbox" name="secureserver" id="secureserver" size="40" maxlength="100" value="<?php echo htmlentities($secureserver); ?>" >
                
                 <a title="<?php echo TOOLTIP_SECURE_URL;?> https://yoursite.com" class="masterTooltip" >
                    <img height="15" border="0" src="../images/info_icon.png">
                    </a>
                </td>
            </tr>
            <tr>
                <td><?php echo START_NEW_ACC;?></td>
                <td>&nbsp;</td>
                <td>
                    <a href="https://www.paypal.com/us/mrb/pal=NCGEZ2VF6YL62" target="_blank"><img src="../images/paypal_small.jpg" width="77" height="30" border="0" alt="<?php echo START_NEW_ACC;?>" title="<?php echo START_NEW_ACC;?>"></a>
                </td>
            </tr>
            <tr>
                <td valign="top">  <?php echo ENABLE_PAYPAL;?> ?</td>
                <td>&nbsp;</td>
                <td>
                    <input name="enable_paypal" type="checkbox" id="enable_paypal" value="YES" <?php if (isset($enable_paypal) and ($enable_paypal == "YES")) { echo " CHECKED "; } ?>> &nbsp;
                    <div style="padding-top:5px;" class="txt12">
                        <?php echo ENABLE_PAYPAL_MSG;?>.<br>
                        <?php echo GIVEN_BELOW_DTLS;?> <br>
                        <p> <?php echo SET_AUTO_RETURN;?><br>
                        <br>
                        <?php echo TO_SET_AUTO_RETURN;?>: <br>
                       &nbsp;1. <?php echo CLICK_PROFILE_TAB;?>. <br>
                        &nbsp;2. <?php echo CLICK_AUTO_RETURN;?>. <br>
                        &nbsp;3. <?php echo CLICK_RADIO_BTN;?>. <br>
                        &nbsp;4. <?php echo ENTER_RET_URL;?>.<br>
                        <br>
                        <?php echo RETURN_URL;?>
                        <br>
                        &nbsp;<?php echo SUCCESS?>:&nbsp;<b>
                        <?php echo str_replace("http", 'https', $rootserver); ?>/payment-paypalipn.php</b> <br>
                        &nbsp;<?php echo FAILURE;?>:&nbsp;&nbsp;&nbsp;<b>
                        <?php echo str_replace("http", 'https', $rootserver); ?>/publishpage.php?msg=f</b> </p>
                    </div>
                </td>
            </tr>
            <tr>
                <td><?php echo PAYPAL_SANDBOX;?></td>
                <td>&nbsp;</td>
                <td valign="top">
                    <input name="paypal_sandbox" type="checkbox" id="paypal_sandbox" value="YES" <?php if (isset($paypal_sandbox) and ($paypal_sandbox == "YES")) { echo " CHECKED "; } ?>>
                </td>
            </tr>
            <tr>
                <td><?php echo PAYPAL_EMAIL_ADDRESS;?> </td>
                <td>&nbsp;</td>
                <td>
                    <input type="text" class="textbox" value="<?php echo htmlentities($paypal_email);?>" name="paypal_email" id="paypal_email" maxlength="100">
                </td>
            </tr>
            <!-- 
            <tr>
                <td valign="top">PayPal Identity Token </td>
                <td>&nbsp;</td>
                <td class="txt12">
                    <input type="text" class="textbox" value="<?php echo htmlentities($paypal_token);?>" name="paypal_token" id="paypal_token" maxlength="100">
                    <br>   <br>
                    To get the Identity Token: <br>
                  &nbsp;1. Click the Profile tab. <br>
                  &nbsp;2. Click the Website Payment Preferences. <br>
                  &nbsp;3. Select &quot;On&quot; option of &quot;Payment Data Transfer&quot;.<br>
                  &nbsp;4. Now you will get an &quot;Identity Token&quot; there. <br>
                  &nbsp;5. Now just copy that and paste that over here..
                </td>
            </tr>
             -->
            <tr>
                <td><?php echo START_NEW_ACC;?></td>
                <td>&nbsp;</td>
                <td><a href="http://checkout.google.com/sell?promo=searmiasystemsinc" target="_blank"><img src="../images/google_small.gif" width="114" height="20" border="0" alt="<?php echo START_NEW_ACC;?>" title="<?php echo START_NEW_ACC;?>"></a></td>
            </tr>
            <tr>
                <td> <?php echo ENABLE_GOOGLE_CHKOUT;?> </td>
                <td>&nbsp;</td>
                <td class="txt12">
                    <input name="enable_google" type="checkbox" id="enable_google" value="YES" <?php if (isset($enable_google) and ($enable_google == "YES")) { echo " CHECKED "; } ?>> &nbsp;
                     <?php echo ENABLE_GOOGLE_CHKOUT_MSG; ?>
                     
                    <!--<font color="RED">**&nbsp;</font> While enabling google checkout, you need to set the &quot;API callback URL&quot; i on the seller page under Settings-&gt;Integration.<br><?php echo $rootserver?>/callback_api.php</b> <br> -->&nbsp;&nbsp;&nbsp;
                    <div>
                        <input name="google_demo" type="checkbox" id="google_demo" value="YES" <?php if (isset($google_demo) and ($google_demo == "YES")) { echo " CHECKED "; } ?>>
                        &nbsp;<?php echo GOOGLE_CHKOUT_SANDBOX;?>
                    </div>
                </td>
            </tr>
            <tr>
                <td><?php echo GC_ID;?> </td>
                <td>&nbsp;</td>
                <td>
                    <input type="text" class="textbox" value="<?php echo htmlentities($google_id);?>" name="google_id" id="google_id" maxlength="100">
                </td>
            </tr>
            <tr>
                <td valign="top"><?php echo GC_KEY;?> </td>
                <td>&nbsp;</td>
                <td>
                    <input type="text" class="textbox" value="<?php echo htmlentities($google_key);?>" name="google_key" id="google_key" maxlength="100">
                
                 <div style="padding-top:5px;" class="txt12">
                        <?php echo GC_MSG;?>.<br>
                        <?php echo GIVEN_BELOW_DTLS;?> <br>
                        <p> <strong><?php echo SET_CALLBACK_URL;?></strong> <br>
                       &nbsp;1. <?php echo LOGIN_GOOGLE_MERCHNT_PANEL;?>. <br>
                        &nbsp;2. <?php echo CLICK_SET_TAB;?>. <br>
                        &nbsp;3. <?php echo VLICK_INTERGRATION_LINK;?>. <br>
                        &nbsp;4. <?php echo ENTER_API_CALLBACK_URL;?>.<br>
                        <br>
                       <?php echo API_CALLBACK_URL;?>
                        <br>
                         <b>
                        <?php echo str_replace("http", 'https', $rootserver); ?>/payment-gcipn.php</b> <br>
                         </p>
                    </div>
                
                </td>
            </tr>
            <tr>
                <td><?php echo PAYMNT_GATEWAY;?> </td>
                <td>&nbsp;</td>
                <td>
                    <select name="enable_gateways" id="enable_gateways" class="selectbox" onChange="javascript:displayDetails(this.value);">
                        <option value='NO'><?php echo NONE;?></option>
                        <option value='AN' <?php if ($enable_gateways == "AN") { echo " SELECTED "; } ?> ><?php echo AUTH_NET;?></option>
                        <option value='CO' <?php if ($enable_gateways == "CO") { echo " SELECTED "; } ?> ><?php echo TWOCHECKOUT;?></option>
                        <option value='LP' <?php if ($enable_gateways == "LP") { echo " SELECTED "; } ?> ><?php echo LINK_POINT;?></option>
                    </select>
                    <input type="hidden" name="enable_gateways_backup" id="enable_gateways_backup" value="<?php echo $enable_gateways?>">
                </td>
            </tr>

            <!-- Authorize.net -->
            <tr>
                <td colspan="3" >
                    <div id="AN" style="width: 764px; display:<?php echo ($enable_gateways != "CC")?'none':'block'; ?>" >
                        <table>
                            <tr>
                                <td width="366"><?php echo START_NEW_ACC;?> </td>
                                <td><a href="http://www.authorize.net/" target="_blank"><img src="../images/authorize_small.jpg" width="49" height="36" border="0" alt="Start A New Account" title="<?php echo START_NEW_ACC;?>"></a></td>
                            </tr>
                            <tr>
                                <td><?php echo AUTH_NET;?><?php echo LOGIN_ID?></td>
                                <td>
                                    <input type="text" class="textbox" value="<?php echo htmlentities($auth_loginid);?>" name="auth_loginid" maxlength="100">
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo AUTH_NET;?> <?php echo LOGIN_PASSWORD?></td>
                                <td>
                                    <input type="password" class="textbox" value="<?php echo htmlentities($auth_pass);?>" name="auth_pass" maxlength="100">
                                </td>
                            </tr>
                            <tr>
                                <td ><?php echo AUTH_NET;?> <?php echo TRANSACTION_KEY;?></td>
                                <td>
                                    <input type="text" class="textbox" value="<?php echo htmlentities($auth_txnkey);?>" name="auth_txnkey" maxlength="100">
                                </td>
                            </tr>
                            <tr>
                                <td ><?php echo AUTH_NET;?> <?php echo MERCHNT_EMAIL;?></td>
                                <td>
                                    <input type="text" class="textbox" value="<?php echo htmlentities($auth_email);?>" name="auth_email" maxlength="100">
                                </td>
                            </tr>
                            <?php
                            $enablechecked="";
                            if($auth_demo=="YES" or $_POST['auth_demo']=="YES")
                                $enablechecked="checked";
                            ?>
                            <tr>
                                <td><?php echo AUTH_NET;?> <?php echo TEST_MODE;?></td>
                                <td>
                                    <input type="checkbox" class="textbox"  value="YES" name="auth_demo" <?php echo $enablechecked?>>
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <!-- Authorize.net -->

            <!-- Google Checkout -->
            <tr>
                <td colspan="3">
                    <div id="CO" style=" width: 764px; display:<?php echo ($enable_gateways != "CO")?'none':'block'; ?>" >
                        <table>
                            <tr>
                                <td width="366"><?php echo START_NEW_ACC;?> </td>
                                <td>
                                    <a href="http://www.2checkout.com" target="_blank"><img src="../images/2co_small.gif" border="0" alt="Start A New Account" title="Start A New Account"></a>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TWOCHECKOUT;echo " ".TRANSACTION_KEY;?></td>
                                <td>
                                    <input type="text" class="textbox" value="<?php echo $checkout_key?>" name="checkout_key" maxlength="100">
                                    <div class="txt12"><br>
                                        
                                       <?php echo TWOCHECKOUT_MSG;?>. <br>
                                        <br> <?php echo RET_URLS_ARE;?>:<br> <br>
                                       &nbsp;<?php echo APPR_URL;?>:&nbsp;<b><?php echo $rootserver?>/publishpage.php?msg=cs</b> <br>
                                        &nbsp;<?php echo PENDING_URL;?> :&nbsp;&nbsp;&nbsp;<b><?php echo $rootserver?>/publishpage.php?msg=cf</b>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo CHECKOUT_DEMO_MODE;?></td>
                                <td>
                                    <input name="checkout_demo" type="checkbox" id="checkout_demo" value="YES" <?php if (isset($checkout_demo) and ($checkout_demo == "YES")) { echo " CHECKED "; } ?>>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo TWOCHECKOUT?> <?php echo PRODUCT_ID;?> </td>
                                <td class="txt12"><input name="checkout_productid" type="text" class="textbox" value="<?php echo htmlentities($checkout_productid);?>" size="4" maxlength="2" onBlur="javascript:checkNumber(this);">
                                    <br><br> 
                                    <?php echo TWOCHECKOUT_MSG;?>
                                </td>
                            </tr>
                            
                        </table>
                    </div>
                </td>
            </tr>
            <!-- Google Checkout -->

            <!-- Link Point -->
            <tr>
                <td colspan="3">
                    <div id="LP" style=" width: 764px; display:<?php echo ($enable_gateways != "LP")?'none':'block'; ?>" >
                        <table>
                            <tr>
                                <td width="266"><?php echo START_NEW_ACC;?> </td>
                                <td><a href="http://www.firstdata.com/linkpoint/" target="_blank"><img src="../images/linkpoint_small.gif" border="0" alt="Start A New Account" title="Start A New Account"></a></td>
                            </tr>
                            <tr>
                                <td valign="top"><?php echo LP_STORE_NO;?> </td>
                                <td>
                                    <input type="text" class="textbox" value="<?php echo $linkpay_store?>" name="linkpay_store" maxlength="100">
                                    <div class="txt12"><br>
                                       <?php echo TO_SETUP_ACC_MSG;?> &quot;<b><?php echo $rootserver?>/lpapi/1001168167.pem</b>&quot; <?php echo WITH_DIG_CERT_MSG;?>.<br>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td><?php echo LP_DEMO_MODE;?></td>
                                <td><input name="linkpay_demo" type="checkbox" id="linkpay_demo" value="YES" <?php if (isset($linkpay_demo) and ($linkpay_demo == "YES")) { echo " CHECKED "; } ?>></td>
                            </tr>
                        </table>
                    </div>
                    <div id="NO"> &nbsp; </div>
                </td>
            </tr>
            <!-- Link Point -->
            
            
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="">
                   <!--  <input name="savePayment" type="button" class="save-btn" onClick="paymentValidate();" value=""> -->
                     <div class="btn-box">
                        <input name="savePayment" type="button" class="btn01" onClick="paymentValidate();" value="<?php echo BTN_SAVE;?>">
                    </div>
                </td>
            </tr>
            </table>
            
            <div class="clear"></div>
        </div>
    </form>
    <!-- Payment Settings -->


    <!-- Account Settings -->
    <div class="content-tab-hd jDisplayIcon" val="3">
        <h5><?php echo ACC_SETTINGS;?></h5>
        <span>
            <?php
            if($hid_tab==2){?>
             <img id="img_3" type="open" class="jimage"  src="../style/images/accordian-arrow-open.png"></span>
            <?php
            }
            else{?>
                <img id="img_3" type="close" class="jimage"  src="../style/images/accordian-arrow-close.png"></span>
                <?php
            }?>
            
        <div class="clear"></div>
    </div>
  
        <form name=passwordForm  action=settings.php  method=post class="settingsform" >
          <div class="content-tab"  >
            <input type="hidden" name="act" value="changeform">
			<h6><?php echo REG_FORM_DETAILS;?></h6>
			<?php echo ENABLE_FORM_FIELDS;?>.
				<div><font color=red><?php echo $formFieldMessage;?></font></div>
                
                <table width="100%" class="admin-table-list" cellpadding="0" cellspacing="0">
                    <tr><td><strong><?php echo FORM_FIELD;?></strong></td><td><strong><?php echo ENABLE_DISABLE;?></strong></td><td><strong><?php echo MANDATORY;?></strong></td></tr>
                    <tr>
                        <td class=maintext><?php echo LOGIN_NAME;?></td>
                        <td><input type="hidden" id="login_check" name="login_check" value="on"><?php echo ENABLED?></td>
                        <td><input type="hidden" id="login_mandatory" name="login_mandatory" value="on"><?php echo ENABLED;?></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo LOGIN_PASSWORD;?></td>
                        <td><input type="hidden" id="password_check" name="password_check" value="on"><?php echo ENABLED;?></td>
                        <td><input type="hidden" id="password_mandatory" name="password_mandatory" value="on"><?php echo ENABLED;?></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo LOGIN_PASSWORD_CONFIRM;?></td>
                        <td><input type="hidden" id="confirmpassword_check" name="confirmpassword_check" value="on"><?php echo ENABLED;?></td>
                        <td><input type="hidden" id="confirmpassword_mandatory" name="confirmpassword_mandatory" value="on"><?php echo ENABLED;?></td>
                    </tr>
                    
                    <tr>
                        <td class=maintext><?php echo SIGNUP_FIRST_NAME;?></td>
                        <td><input type="hidden" id="firstName_check" name="firstName_check" value="on"><?php echo ENABLED;?></td>
                        <td><input type="hidden" id="firstName_mandatory" name="firstName_mandatory" value="on"><?php echo ENABLED;?></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo SIGNUP_EMAIL;?></td>
                        <td><input type="hidden" id="email_check" name="email_check" value="on"><?php echo ENABLED;?></td>
                        <td><input type="hidden" id="email_mandatory" name="email_mandatory" value="on"><?php echo ENABLED;?></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo SIGNUP_LAST_NAME;?></td>
                        <td><input type="checkbox" id="lastName_check" name="lastName_check" <?php if(getFormFieldstatus($formfiledarray,'lastName',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="lastName_mandatory" name="lastName_mandatory" <?php if(getFormFieldstatus($formfiledarray,'lastName',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('lastName');"></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo SIGNUP_ADDRESS1;?></td>
                        <td><input type="checkbox" id="address1_check" name="address1_check" <?php if(getFormFieldstatus($formfiledarray,'address1',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="address1_mandatory" name="address1_mandatory" <?php if(getFormFieldstatus($formfiledarray,'address1',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('address1');"></td>
                    </tr>
                    
                    <!-- 
                    <tr>
                        <td class=maintext>Address2</td>
                        <td><input type="checkbox" id="address2_check" name="address2_check" <?php if(getFormFieldstatus($formfiledarray,'address2',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="address2_mandatory" name="address2_mandatory" <?php if(getFormFieldstatus($formfiledarray,'address2',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('address2');"></td>
                    </tr>
                     -->
                    <tr>
                        <td class=maintext><?php echo SIGNUP_CITY;?></td>
                        <td><input type="checkbox"  id="city_check" name="city_check" <?php if(getFormFieldstatus($formfiledarray,'city',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="city_mandatory" name="city_mandatory" <?php if(getFormFieldstatus($formfiledarray,'city',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('city');"></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo SIGNUP_STATE;?></td>
                        <td><input type="checkbox"  id="state_check" name="state_check" <?php if(getFormFieldstatus($formfiledarray,'state',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="state_mandatory" name="state_mandatory" <?php if(getFormFieldstatus($formfiledarray,'state',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('state');"></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo SIGNUP_ZIP;?></td>
                        <td><input type="checkbox"  id="zip_check" name="zip_check" <?php if(getFormFieldstatus($formfiledarray,'zip',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="zip_mandatory" name="zip_mandatory" <?php if(getFormFieldstatus($formfiledarray,'zip',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('zip');"></td>
                    </tr>
                    <tr>
                        <td class=maintext><?php echo SIGNUP_COUNTRY;?></td>
                        <td><input type="checkbox" id="country_check" name="country_check" <?php if(getFormFieldstatus($formfiledarray,'country',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="country_mandatory" name="country_mandatory" <?php if(getFormFieldstatus($formfiledarray,'country',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('country');"></td>
                    </tr>

                    <tr>
                        <td class=maintext><?php echo SIGNUP_PHONE?></td>
                        <td><input type="checkbox" id="phone_check" name="phone_check" <?php if(getFormFieldstatus($formfiledarray,'phone',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="phone_mandatory" name="phone_mandatory" <?php if(getFormFieldstatus($formfiledarray,'phone',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('phone');"></td>
                    </tr>
                    <!-- 
                    <tr>
                        <td class=maintext>Fax</td>
                        <td><input type="checkbox" id="fax_check" name="fax_check" <?php if(getFormFieldstatus($formfiledarray,'fax',0)) echo 'checked="checked"'; ?>></td>
                        <td><input type="checkbox" id="fax_mandatory" name="fax_mandatory" <?php if(getFormFieldstatus($formfiledarray,'fax',1)) echo 'checked="checked"'; ?> onclick="checkFieldStatus('fax');"></td>
                    </tr>
                     -->
				</table>
				<table width="100%">
                    <tr>
                        <td colspan=3 width=100% align="right"><input class="btn01" type="submit" id="buttonpass" name="buttonformfield" value="<?php echo BTN_UPDATE_FORM_FIELDS;?>"></td>
                    </tr>
                </table>
     
        <div class="clear"></div>
    </div>   </form>
    <!-- Account Settings -->


    <!-- Password Settings -->
    <div class="content-tab-hd jDisplayIcon" val="4">
        <h5><?php echo PASSWORD_SETTINGS;?></h5>
        <span>
            <?php
            if($hid_tab==3){?>
             <img id="img_4" type="open" class="jimage"  src="../style/images/accordian-arrow-open.png"></span>
            <?php
            }
            else{?>
                <img id="img_4" type="close" class="jimage"  src="../style/images/accordian-arrow-close.png"></span>
                <?php
            }?> 
           
        <div class="clear"></div>
    </div>
    <form name="passwordForm" id="passwordForm"  action=settings.php  method=post class="settingsform">
        <input type="hidden" name="act" value="changepass">
        <div class="content-tab">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan=3 ><div class="successmessage"><?php echo $passmessage;?></div></td>
                </tr>
                <tr>
                    <td width="350"><?php echo NEW_PASSWD;?></td>
                    <td width="3%">&nbsp;</td>
                    <td>
                        <input type="password" name="password" id="password" maxlength=100 class="textbox" size="40">
                    </td>
                </tr>
                <tr>
                    <td><?php echo LOGIN_PASSWORD_CONFIRM;?></td>
                    <td>&nbsp;</td>
                    <td>
                        <input type="password" name="confirmpassword" id="confirmpassword" maxlength=100 class="textbox" size="40">
                    </td>
                </tr>
                <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="">
                    <div class="btn-box">
                        <input class="btn01" type="button" id="buttonpass" name="buttonpass" value="<?php echo BTN_CHANGE_PSWD;?>" onClick="changePass();">
                    </div>
                </td>
            </tr>
            </table>
            <div class="clear"></div>
        </div>
    </form>
    <!-- Password Settings -->


    <!-- Cleanup Old Files -->
	
	<?php
	/*
    <div class="content-tab-hd jDisplayIcon" val="5">
        <h5>Cleanup Old Files</h5>
        <span>
             <?php
            if($hid_tab==4){?>
             <img id="img_5" type="open" class="jimage"  src="../style/images/accordian-arrow-open.png"></span>
            <?php
            }
            else{?>
                <img id="img_5" type="close" class="jimage"  src="../style/images/accordian-arrow-close.png"></span>
                <?php
            }?>
            
        <div class="clear"></div>
    </div>
   
        <form name=cleanupForm  action=settings.php  method=post class="settingsform">
            <input type="hidden" name="act" value="cleanup">
            <div class="content-tab">
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td colspan=3 ><div class="successmessage"><?php echo $cleanmessage;?></div></td>
                </tr>
                <tr>
                    <td colspan="3">
                        <input class="btn01" type=button id=buttonpass name=buttonclean title="Cleans temporary files" value="Cleanup old Files" onClick="cleanUp();"><br>
                        (This will delete the temporary files stored in the server.)
                    </td>
                </tr>
            </table>  <div class="clear"></div>
            </div>
        </form>
      
    
    <!-- Cleanup Old Files -->

*/ ?>



    <!-- Created Site Settings -->
   <div class="content-tab-hd jDisplayIcon" val="6">
        <h5><?php echo SITE_SETTINGS;?></h5>
        <span>
            <?php
            if($hid_tab==5){?>
             <img id="img_6" type="open" class="jimage"  src="../style/images/accordian-arrow-open.png"></span>
            <?php
            }
            else{?>
                <img id="img_6" type="close" class="jimage"  src="../style/images/accordian-arrow-close.png"></span>
                <?php
            }?>
        <div class="clear"></div>
    </div>
    <form name="createdSiteSettingsForm" method="post" action="settings.php" enctype="multipart/form-data" class="settingsform">
        <input type="hidden" name="act" value="created_site_post">
        <div class="content-tab" >
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td colspan=3 ><div class="successmessage"><?php echo $sitecreatedmessage;?></div></td>
            </tr>
            <tr>
                <td><?echo ENABLE_GOOGLE_ANALYTICS;?></td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_created_site_google_analytics=='Y')?'checked':''; ?> name="enable_created_site_google_analytics" class="jqToggle" value="Y" >
                </td>
            </tr>
            
            <tr>
                <td><?php echo ENABLE_GOOGLE_ADDSENSE?> ? </td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_google_adsense=='1')?'checked':''; ?> name="enable_google_adsense" class="jqToggle" value="1" >
                </td>
            </tr>

            <tr>
                <td><?php echo ENABLE_FORM;?></td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_form=='1')?'checked':''; ?> name="enable_form" class="jqToggle" value="1" >
                </td>
            </tr>
            
            <tr>
                <td><?php echo ENABLE_SLIDER;?> </td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_slider=='1')?'checked':''; ?> name="enable_slider" class="jqToggle" value="1" >
                </td>
            </tr>
            <tr>
                <td><?php echo ENABLE_CONTACT_FORM;?></td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_contact_form=='1')?'checked':''; ?> name="enable_contact_form" class="jqToggle" value="1" >
                </td>
            </tr>
            <tr>
                <td><?php echo ENABLE_MENU;?></td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_menu=='1')?'checked':''; ?> name="enable_menu" class="jqToggle" value="1" >
                </td>
            </tr>
            <tr>
                <td><?php echo ENABLE_SOCIAL_SHARE;?> </td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_social_share=='1')?'checked':''; ?> name="enable_social_share" id="jSocialShare" value="1" >
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <?php
                    $style=($enable_social_share =='1')?'block':'none';
                    ?>
                    <table align="center" id="jDisplaySocialShare" style="display:<?php echo $style?>">
                        <tr>
                           <td>
                            <input type="checkbox" <?php echo ($enable_fb=='1')?'checked':''; ?> name="enable_fb" class="jqToggle" value="1" ><?php echo ENABLE_FB;?>
                            </td> 
                        </tr>
                        <tr>
                           <td>
                            <input type="checkbox" <?php echo ($enable_twiter=='1')?'checked':''; ?> name="enable_twiter" class="jqToggle" value="1" ><?php echo ENABLE_TWITTER;?>
                            </td> 
                        </tr>
                        <tr>
                           <td>
                            <input type="checkbox" <?php echo ($enable_linkedin=='1')?'checked':''; ?> name="enable_linkedin" class="jqToggle" value="1" ><?php echo ENABLE_LINKEDIN;?>
                            </td> 
                        </tr>
                         
                    </table>
                    
                </td>
            </tr>
            <tr>
                <td><?php echo ENABLE_HTML_CONTENT;?> </td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_html=='1')?'checked':''; ?> name="enable_html" class="jqToggle" value="1">
                </td>
            </tr>

            <!-- Option to add banner to created site -->
            <tr>
                <td><?php echo ADD_BANNER;?></td>
                <td>&nbsp;</td>
                <td>
                    <input type="checkbox" <?php echo ($enable_created_site_banner=='1')?'checked':''; ?> name="enable_created_site_banner" class="jqToggle" value="1" id="jqEnableBanner" >
                </td>
            </tr>
            <tr>
                <td colspan="3">
                    <div class="jqEnableBanner">
                        <table>
                            <tr>
                                <td width="350" class="maintext"><?php echo UPLOAD_BANNER;?></td>
                                <td width="1%">&nbsp;</td>
                                <td>
                                    <input type="file" class="textbox" name="created_site_banner_name" id="created_site_banner_name" >
                                    <input type="hidden" name="created_site_banner_value" id="created_site_banner_value" value="<?php echo $created_site_banner_name?>" >
                                </td>
                            </tr>
                            <?php if($created_site_banner_name!=''){ ?>
                            <tr>
                                <td width="350" class="maintext"><?php echo BANNER;?></td>
                                <td width="1%">&nbsp;</td>
                                <td>
                                    <img src="<?php echo $created_site_banner_name ?>" />
                                </td>
                            </tr>
                            <?php } ?>
                            <tr>
                                <td width="350" class="maintext"><?php echo BANNER_LINK;?></td>
                                <td width="1%">&nbsp;</td>
                                <td>
                                    <input type="text" class="textbox" name="created_site_banner_link" id="created_site_banner_link" value="<?php echo $created_site_banner_link;  ?>" >
                                </td>
                            </tr>
                        </table>
                    </div>
                </td>
            </tr>
            <!-- Option to add banner to created site -->

            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="">
                    <div class="btn-box">
                        <input name="saveCreatedSiteSettings" type="button" class="btn01" onClick="validateCreatedSite();" value="<?php echo BTN_SAVE;?>">
                    </div>
                </td>
            </tr>
        </table>
        <div class="clear"></div>
    </div>
    </form>
    <!-- Created Site Settings  -->
    
</div>
</div>
<!---->

 





<script>
    var user_max_storage;
    var user_publish_option;
    user_max_storage='<?php echo $user_max_storage; ?>';
    user_publish_option='<?php echo $user_publish_option;?>';

    document.settingsForm.user_max_storage.value=user_max_storage;
    document.settingsForm.user_publish_option.value=user_publish_option;

<?php if($paymentsupport=="yes") echo "checkpayment();";?>
<?php if($paymentsupport=="no") echo "checkpayment1();";?>

</script>
<?php
include "includes/adminfooter.php";
?>