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

$curTab = 'login';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";


//handle post back of signup

if(sizeof($_POST)>0 AND isset($_POST['chekSelTemplate'])){
    $_SESSION['session_templateselectedfromindex']="YES";
    list($_SESSION['selected_templateid'],$_SESSION['selected_templateThemeId'])= explode('_',$_POST['chekSelTemplate']);   
}


$act=$_GET["act"];
$message="";
/* check whether the template selected*/
$req=$_GET['req'];

//if($req=="ts") {
//    $_SESSION['session_templateselectedfromindex']="YES";
//    $_SESSION['session_buildtype']=$_GET['ttype'];
//    $_SESSION['session_locationurl']="showtemplates.php?type=T&templateid=".$_GET['tid']."&";
//    if($_SESSION["session_loginname"] != "")
//        Header("location:sitemanager.php");
//}
//echo $_SESSION['session_templateselectedfromindex'];
/********************/

if($act=="post") {

    //select the matching record for entered login info
    $sql="SELECT * from tbl_user_mast WHERE vuser_login='".addslashes($_POST["vuser_login"])."' and vuser_password='".md5(addslashes($_POST["vuser_password"]))."' and vdel_status='0'";
    $result=mysql_query($sql,$con) or die(mysql_error());

    if(mysql_num_rows($result) > 0) {
        while($row=mysql_fetch_array($result)) {

            //set sessions
            $_SESSION["session_loginname"]=$_POST["vuser_login"];
            $_SESSION["session_userid"]=$row["nuser_id"];
            $_SESSION["session_email"]=$row["vuser_email"];
            $_SESSION["session_style"]=$row["vuser_style"];
            $_SESSION["session_template_dir"] = getSettingsValue('template_dir');

            /* For setting editor path */
            $rootPath = getSettingsValue('rootpath');
            $_SESSION['ROOT_PATH']=$rootPath;

            $rootserver = getSettingsValue('rootserver');
            $_SESSION['INSTALL_PATH'] = $rootserver;

            $serverPermission = getSettingsValue('serverPermission');
            if($serverPermission=="0755"){
                $_SESSION['SERVER_PERMISSION']= 0755;
            }
            else{
                $_SESSION['SERVER_PERMISSION']= 0777;
            }

//      die;

            /* For setting editor path */
            //delete the images in temporary folder for subsequesnt logins
            if (isset($_SESSION["session_userid"]) AND $_SESSION["session_userid"] <> "") {
                if(is_dir("./tmpeditimages/".$_SESSION["session_userid"]))
                    remove_dir("./tmpeditimages/".$_SESSION["session_userid"]);
            }
            if($_SESSION['session_templateselectedfromindex']=="YES") {

                // Unset session_templateselectedfromindex
                unset($_SESSION['session_templateselectedfromindex']);
                
                header("location:getsitedetails.php?tempid=".$_SESSION['selected_templateid']."&themeid=".$_SESSION['selected_templateThemeId']);
            }
            else if($_SESSION['start_type']=="sitemanager"){
                 header("location:sitemanager.php");
            }   
             else {
                header("location:usermain.php?succ=msg");
            }
        }
    }else {
        $message=USER_LOGIN;
    }
}

//include files
include "includes/subpageheader.php";
?>
<style>
    .error{
        color:red;
        display: block;
        width:350px;
    }   
</style>

<script language="javascript" type="text/javascript" src="js/jquery.js"></script>
<script language="javascript" type="text/javascript" src="js/jquery.validate.js"></script>
<script>
    $(document).ready(function(){
    $("#loginForm").validate({
        rules: {
            vuser_login: {required: true},
            vuser_password: {required: true}
        },
        messages: {
            vuser_login: {required: "Enter login name"},
             vuser_password: {required: "Enter password"}
        }
    });
    });
    
    function validate1(){

        if(document.loginForm.vuser_login.value==""){

            alert("<?php echo VAL_LOGIN_NAME_EMPTY;?>");
            document.loginForm.vuser_login.focus();
            return false;

        }else if(document.loginForm.vuser_password.value==""){

            alert("<?php echo VAL_PWD_EMPTY;?>");
            document.loginForm.vuser_password.focus();
            return false;
        }else{
            document.loginForm.submit();
        }

    }
    function resetFields()
    {
        document.loginForm.vuser_login.value = "";
        document.loginForm.vuser_password.value = "";
        document.loginForm.vuser_login.focus();
    }
    function passPress()
    {
        if(window.event.keyCode=="13"){
            validate();
        }
    }
</script>
<div class="headingstyle_new">
    <h1><?php echo LOGIN_TITLE; ?></h1>
    <h5><?php echo LOGIN_CAPTION; ?></h5>
</div>

<div class="common_box" align="center">

    <form name="loginForm" id="loginForm" method=post action='login.php?act=post' style="padding:0; margin:0; ">


        <table class="commntbl_style1" border="0" width="800"cellpadding="0" cellspacing="0" align="left">
            <tr>
                <td align=center colspan=3><font color=red><?php echo $message;?></font></td>
            </tr>

            <tr>
                <td width="25%" align=left ><?php echo LOGIN_NAME; ?><font color=red><sup>*</sup></font></td>
                <td width="5%"></td>
                <td align=left><input  type=text name="vuser_login" id="vuser_login" maxlength="50" value="<?php echo htmlentities($_POST["vuser_login"]); ?>" ></td>
            </tr>

            <tr>
                <td align=left ><?php echo LOGIN_PASSWORD; ?><font color=red><sup>*</sup></font></td>
                <td></td>
                <td align=left><input  type=password name="vuser_password" id="vuser_password" maxlength="50"  onKeyPress="javascript:passPress();"></td>
            </tr>



            <tr>
                <td colspan="2" >&nbsp;</td>
                <td  align="left" class="" style="padding:15px 0 15px 260px; ">
                    <input  type="submit"  value="<?php echo strtoupper(LOGIN_TITLE); ?>" class="btn01">
                    <!--<input  type="button"  class=button value="Reset" onClick="resetFields();" >--></td>
            </tr>

            <tr>
                <td  colspan=2 align=center ></td>

                <td align="left" >
                    <p class="login_extralinks"><?php echo LOGIN_NEW_USER; ?> <a href="signup.php"><?php echo LOGIN_SIGN_UP_HERE;?> </a>  |   <?php echo LOGIN_FORGOT_PASSWORD;?> <a href="forgotpass.php"><?php echo LOGIN_CLICK_HERE;?>.</a></p>
                </td>
            </tr>

        </table>



    </form>

</div>

<?php
include "includes/userfooter.php";
?>