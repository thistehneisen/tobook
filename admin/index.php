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
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
if(file_exists('language/english_lng_admin.php')) {
	include 'language/english_lng_admin.php';
}
else {
	include '../language/english_lng_admin.php';
}
//handle post back of signup
$act=$_GET["act"];
$message="&nbsp;";
 if($_SESSION["session_username"] == "admin"){
    Header("location:dashboard.php"); 
 }

if($act=="post"){

   //check if login information are correct
   $sql="SELECT * from tbl_lookup WHERE vname='admin_pass' and  vvalue='".md5(mysql_real_escape_string($_POST["vuser_password"]))."'";
   $result=mysql_query($sql,$con) or die(mysql_error());

   if((mysql_num_rows($result) > 0) && (addslashes($_POST["vuser_login"])=="admin")){

          $_SESSION["session_username"] = "admin";
		  $_SESSION["session_advCatId"] = "";
		  $_SESSION["session_advTemplateDir"] = "";
		  $_SESSION["session_steps"] = "";
		  $_SESSION["session_advTemplateType"] = "";
          Header("location:dashboard.php");
   }else{
          $message=MSG_INVALID_LOGIN;
   }

}
//include files
include "./includes/adminheader.php";
?>

<script>
function passPress()
{
if(window.event.keyCode=="13"){
		validate();
	}
}

function validate(){

   if(document.loginForm.vuser_login.value==""){

      alert("<?php echo CONF_LOGIN_NAME_EMP;?>");
      document.loginForm.vuser_login.focus();
      return false;

    }else if(document.loginForm.vuser_password.value==""){

      alert("<?php echo CONF_PSWD_EMP;?>");
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
</script>

<table width="100%">
    <tr>
        <td>
            <div class="admin-pnl">
                <table width="100%" class="admin-login">
                    <tr><td><h3><?php echo ADMIN_LOGIN;?></h3></td></tr>
                    <tr>
                        <td>
                            <form name="loginForm" method=post action=index.php?act=post>

                                <table border="0" width="100%">
                                        <!--<tr>
                                        <td align=center colspan=3 class=maintext><br><br>Please enter Login Name/Password<br><br>&nbsp;</td>
									</tr>-->
                                    <tr>
                                        <td colspan=3 class=maintext><font color=red><?php echo $message;?></font></td>
                                    </tr>
                                    <tr>
                                        <td class="maintext" width="30%"><label><?php echo LOGIN_NAME;?><font color=red><sup>*</sup></font></label></td>
                                        <td align=left><input type="text" class=textbox name="vuser_login" id="vuser_login" maxlength="50" value="<?php echo htmlentities($_POST["vuser_login"]); ?>"></td>
                                    </tr>

                                    <tr>
                                        <td class=maintext><label><?php echo LOGIN_PASSWORD;?><font color=red><sup>*</sup></font></label></td>
                                        <td align=left><input type=password  class=textbox  name="vuser_password" id="vuser_password" maxlength="50"  onKeyPress="javascript:passPress();"></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td align="right" width="15%">
                                        <!-- <input  type="button"  class="btn02" value="Reset" onClick="resetFields();" >  -->
                                            <input type="submit" onClick="validate();" value="<?php echo BTN_LOGIN;?>"  class="btn01" >&nbsp;
                                        </td>
                                    </tr>
                                </table>
                            </form>
                        </td>
                    </tr>
                </table>
            </div>
        </td>
    </tr>
</table>

<?php

include "includes/adminfooter.php";
?>