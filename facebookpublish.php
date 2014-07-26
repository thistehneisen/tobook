<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
include "includes/sitefunctions.php";
include "includes/curlwrapper.php";
include "includes/ftpfunctions.php";

ini_set('max_execution_time', '150');


$apiId      = getSettingsValue('facebook_api_id');
$apiSecret  = getSettingsValue('facebook_api_secret');

$nsite_id = $_SESSION['siteDetails']['siteId'];
$siteName = $_SESSION['siteDetails']['siteInfo']['siteName'];

if($siteLanguageOption=="english"){
    $siteNameModified = getAlias($siteName,'-');
}else{
    $siteNameModified = $nsite_id;
}

$userid = $_SESSION["session_userid"];
$userDetails = getUserDetails($userid);

//get the ftp details from look up table
$ftp_directory   = getSettingsValue('ftp_location');
$ftp_user_domain = getSettingsValue('ftp_host');
$ftp_user_name   = getSettingsValue('ftp_username');
$ftp_user_pass   = getSettingsValue('ftp_password');

$sql = "select fb_page_id from  tbl_site_mast where nsite_id=$nsite_id";
$result=mysql_query($sql) or die(mysql_error());
$row = mysql_fetch_assoc($result);
$selectedPage = $row['fb_page_id'];

if($_get['flag']==1) {
    $message  = "Your site is published successfully";
}
include "facebook/base_facebook.php";
include "facebook/facebook.php";

$facebook = new Facebook(array(
                'appId' => $apiId,
                'secret' => $apiSecret,
));

if($_POST) {
    $fanpageId = $_POST['fanpageList'];
    $user = $facebook->getUser();
    
    if ($user) {
        try {
            // Proceed knowing you have a logged in user who's authenticated.
            $user_profile = $facebook->api('/me/accounts','GET');
            $returndata     =   $user_profile['data'];

            for ($pageCount=0;$pageCount<count($returndata);$pageCount++) {
                if($returndata[$pageCount]['id']==$fanpageId) {
                    $access_token = $returndata[$pageCount]['access_token'];
                }
            }

            if (!empty($user_profile )) {

                try {
                    $publishStream = $facebook->api("/$fanpageId/tabs", 'post', array(
                            'app_id' => $apiId,
                            'access_token'    => $access_token,
                            'custom_name' => '')
                    );

                    $sqlReset = "UPDATE tbl_site_mast SET fb_page_id=''
                                 WHERE fb_page_id='" . $fanpageId . "'";
                    mysql_query($sqlReset, $con) or die(mysql_error());
                    
                    $sql = "UPDATE  tbl_site_mast SET fb_page_id='" . $fanpageId . "'
                            WHERE nsite_id='" . $nsite_id . "'";
                    mysql_query($sql, $con) or die(mysql_error());

                    // Move files to ftp preset by admin
                    $result = check_ftp_info($ftp_user_domain,$ftp_user_name,$ftp_user_pass);
                    
                    //if well connected to site upload files
                    if($result=="ok") {
                        // upload  file locations
                        $local_dir = USER_SITE_UPLOAD_PATH.$nsite_id;
                        ftp_dir($local_dir, $ftp_directory,$siteNameModified);
                        // close the connection
                        @ftp_close($conn_id);

                        // Update publish flag in  Site Master table
                         updateSitePublishStatus($nsite_id);

                         // Send site created mail
                         sendSiteCreatedMail($userDetails);
                    }
                    // Move files to ftp preset by admin
                    

                } catch (FacebookApiException $e) {
                    //d($e);
                }

                header('Location:'.BASE_URL.'/facebookpublish.php?flag=1');

            }else {
                header('Location:'.BASE_URL.'/facebookpublish.php?flag=1');
            }

        } catch (FacebookApiException $e) {

            $user = null;
        }
    }else {
        # There's no active session, let's generate one

        $login_url = $facebook->getLoginUrl(array('scope' => 'manage_pages'));
        header("Location: " . $login_url);
    }

}
$user = $facebook->getUser(); 



if ($user) {
    try {
        // Proceed knowing you have a logged in user who's authenticated.
        $user_profile = $facebook->api('/me','GET');

        if (!empty($user_profile )) {

            $access_token = $facebook->getAccessToken();

            $fql_query_url = "https://graph.facebook.com/"
                            . "fql?q=SELECT+page_id,+name,+fan_count+FROM+page+WHERE+page_id+IN(SELECT+page_id+from+page_admin+WHERE+uid=me())&format=json-strings"
                            . "&access_token=" . $access_token;
            
            $objcurl = new Curlwrapper($fql_query_url);
            $fql_query_result = $objcurl->get();
            $pagesArray = json_decode($fql_query_result,true);
            $pagesList = $pagesArray['data'];
        }else {
            //header('Location:'.BASE_URL.'/facebookpublish.php');
        }

    } catch (FacebookApiException $e) {

        $user = null;
    }
}else {
    # There's no active session, let's generate one

    $login_url = $facebook->getLoginUrl(array('scope' => 'manage_pages'));
    ob_start("ob_gzhandler");
    header("Location: " . $login_url);
    exit;
}

include "includes/userheader.php";
?>

<script>
    $(document).ready(function() {
        $("#jqCreateFBPage").live("click",function(){

            window.open( 'http://www.facebook.com/pages/create.php', '_blank' );
        });
        $("#publishStoreBtn").live("click",function(){

            var fanpageId= $("#fanpageList").val();
            if(fanpageId==""){
                $("#jqErrorMessage").html("Please select any Facebook fanpage");
                return false;
            }
        });

    });
</script>

<div class="common_box">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0">
        <tr>
            <td align="left">
                 <?php if($_GET['flag']==1) $pageTitle = 'Publish Success'; else $pageTitle = 'Publish your site to Facebook fanpage';  ?>
                <h2><?php echo $pageTitle; ?></h2>
            </td>
        </tr>
        <?php if($_GET['flag']==1) { ?>
        <tr>
            <td>
                <p style="font-size: 18px; padding: 30px 0 20px 0;"> Your site is published successfully </p> <br/>
                <input class="grey-btn02" type="button" name=btnback value="Back" onclick="window.location='<?php echo BASE_URL; ?>'"> &nbsp;
            </td>
        </tr>
        <?php } else {?>
        <tr>
            <td >
                <form name="publishStore" id="publishStore" method="post" enctype="multipart/form-data" action="" class="" >
                        <?php if(count($pagesList)>0 ) { ?>
                    <div class="">
                        <span>Choose Facebook Fanpage</span>

                        <select name="fanpageList" class="selectbox" id="fanpageList" >
                            <option value="">Select a Facebook Page</option>
                                    <?php for($pageCount = 0; $pageCount<count($pagesList);$pageCount++) { ?>
                            <option <?php if($selectedPage==$pagesList[$pageCount]['page_id']) { ?> selected <?php } ?>value="<?php echo $pagesList[$pageCount]['page_id'];?>"><?php echo $pagesList[$pageCount]['name'];?></option>
                                        <?php } ?>
                        </select>
                        <p class="errormessage" id="jqErrorMessage"></p>
                        <br>
                        <div class="">
                            <a href="http://www.facebook.com/pages/create.php" target="_blank" class="">Click here </a> to create a Facebook Fan page. <br/>
                            <a href=""  class="link">Click here </a> to reload Fan page(s).

                        </div>
                        <div class="clear"></div>

                        <div class="clear"></div>
                    </div>
                            <?php } else { ?>
                    <div class="">
                        <div class="r" >&nbsp;</div>
                        <div class="">
                            <div class="">
                                <input type="submit" class="btn01"  value=" Create your Facebook fanpage now" name="" id="jqCreateFBPage" >
                                <div class="clear"></div>
                            </div>
                            <div class="">

                                <a href="<?php echo BASE_URL?>facebookpublish.php"  class="link">Click here </a> to reload Fan page(s).

                            </div></div> </div>
                            <?php } ?>

                        <?php if(count($pagesList)>0 ) { ?>
                    <br>
                    <div class="">
                        <div class="">
                            <input type="submit" class="btn01"  value=" Publish Your Site To Facebook Fanpage" name="publishStoreBtn" id="publishStoreBtn" >
                            <div class="clear"></div>
                        </div>
                        <div class="clear"></div>
                    </div>
                            <?php } ?>
                </form>

            </td>
        </tr>
            <?php } ?>
    </table>
</div>
<?php
include "includes/userfooter.php";
?>