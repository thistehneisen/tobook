<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                      |
// |                                                                                                            |
// +----------------------------------------------------------------------+
?>
<?php

$curTab = 'users';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/admin_functions.php";
include "includes/adminheader.php";
//sent back
if($_GET["goback"]=="true"){
   header("Location:usermanager.php");
   exit;
}

//get the id from get or post
if($_GET["id"]!=""){
   $user=$_GET["id"];
}elseif($_POST["id"]!=""){
   $user=$_POST["id"];
}


$act=$_GET["act"];

If($act=="post"){

 $sql="UPDATE tbl_user_mast SET vuser_name='".addslashes($_POST["vuser_name"])."',
 vuser_lastname='".addslashes($_POST["vuser_lastname"])."',vuser_address1='".addslashes($_POST["vuser_address1"])."',
 vuser_address2='".addslashes($_POST["vuser_address2"])."',
     vuser_email='".addslashes($_POST["vuser_email"])."',
     vuser_phone='".addslashes($_POST["vuser_phone"])."',
     vcity='".addslashes($_POST["vcity"])."',
     vstate='".addslashes($_POST["vstate"])."',
     vzip='".addslashes($_POST["vzip"])."',
     vcountry='".addslashes($_POST["vcountry"])."',
     vuser_fax='".addslashes($_POST["vuser_fax"])."' WHERE nuser_id='".$user."'";

     mysql_query($sql,$con);
     $message="Profile Updated<br>&nbsp;";
}

//get the currrent values
$sql="select vuser_login,vuser_name,vuser_lastname,vuser_address1,vuser_address2,vcity,vstate,vzip,vcountry,vuser_email,vuser_phone,vuser_fax from tbl_user_mast where nuser_id='".$user."'";


$result=mysql_query($sql,$con);

if( mysql_num_rows($result)>0){

$row=mysql_fetch_array($result);

     $vuser_login=stripslashes($row["vuser_login"]);
     $vuser_name=stripslashes($row["vuser_name"]);
     $vuser_lastname=stripslashes($row["vuser_lastname"]);
     $vuser_address1=stripslashes($row["vuser_address1"]);
     $vuser_address2=stripslashes($row["vuser_address2"]);
     $vcity=stripslashes($row["vcity"]);
     $vstate=stripslashes($row["vstate"]);
     $vzip=stripslashes($row["vzip"]);
     $vcountry=stripslashes($row["vcountry"]);
     $vuser_email=stripslashes($row["vuser_email"]);
     $vuser_phone=stripslashes($row["vuser_phone"]);
     $vuser_fax=stripslashes($row["vuser_fax"]);
     
     $country = $vcountry;
}else{

     $message="Invalid Userid.Please Retry!";

}

include "includes/adminheader.php";
?>
<script>
function checkMail(email)
{
        var str1=email;
        var arr=str1.split('@');
        var eFlag=true;
        if(arr.length != 2)
        {
                eFlag = false;
        }
        else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
        {
                        eFlag = false;
        }
        else
        {
                var dot=arr[1].split('.');
                if(dot.length < 2)
                {
                        eFlag = false;
                }
                else
                {
                        if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
                        {
                                eFlag = false;
                        }

                        for(i=1;i < dot.length;i++)
                        {
                                if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1 || dot[i].length > 4)
                                {
                                        eFlag = false;
                                }
                        }
                }
        }
                return eFlag;
}

function validate(){

   if(document.editForm.vuser_name.value==""){

      alert("Name cannot be empty");
      document.editForm.vuser_name.focus();
      return false;

    }else if(document.editForm.vuser_lastname.value==""){

      alert("Lastname cannot be empty");
      document.editForm.vuser_lastname.focus();
      return false;

    }else if(document.editForm.vuser_address1.value==""){

      alert("Address cannot be empty");
      document.editForm.vuser_address1.focus();
      return false;

    }else if(document.editForm.vuser_email.value==""){

      alert("Email cannot be empty");
      document.editForm.vuser_email.focus();
      return false;

    }else if(checkMail(document.editForm.vuser_email.value)==false){

     alert('Invalid mail format');
     document.editForm.vuser_email.focus();
     return false;

    }else{

    document.editForm.submit();

    }

}

function goback(){
     document.editForm.action="edituser.php?goback=true";
     document.editForm.submit();
}
</script>
<?php
$linkArray = array( 'User'       =>"admin/usermanager.php",
                    'Edit User'  =>"admin/edituser.php?id=$user");
?>
<div class="admin-pnl">
    <?php echo getBreadCrumb($linkArray);?>
    <h2>Edit User</h2>
<div class="content-tab-pnl">
    <form name="editForm" id="editForm" method=post action=edituser.php?act=post>
        
        <div class="content-tab">
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>Please Fill In The Following Details(Fields marked <font color=red>*</font> are mandatory)</td>
                </tr>
                <tr>
                    <td><font color=red><?php  echo $message;?></font></td>
                </tr>
                <tr>
                    <td>Login Name<font color=red></font></td>
                    <td></td>
                    <td><input type=text readonly class=textbox name="vuser_login" id="vuser_login" maxlength="100"  value="<?php echo htmlentities("$vuser_login")?>" size="30"></td>
                </tr>
                <tr>
                    <td>First Name<font color=red><sup>*</sup></font></td>
                    <td></td>
                    <td><input type=text class=textbox name="vuser_name" id="vuser_name" maxlength="100"  value="<?php echo htmlentities("$vuser_name")?>" size="30"></td>
                </tr>
                <tr>
                    <td >Last Name<font color=red><sup>*</sup></font></td>
                    <td></td>
                    <td ><input type=text class=textbox name="vuser_lastname" id="vuser_lastname" maxlength="100"  value="<?php echo htmlentities("$vuser_lastname")?>" size="30"></td>
                </tr>
                <tr>
                    <td >Address1<font color=red><sup>*</sup></font></td>
                    <td></td>
                    <td ><input type=text  class=textbox name="vuser_address1"  value="<?php echo htmlentities("$vuser_address1")?>" id="vuser_address1" maxlength="200" size="30"></td>
                </tr>
                <tr>
                    <td>Address2</td>
                    <td></td>
                    <td><input type=text   class=textbox value="<?php echo htmlentities("$vuser_address2")?>" name="vuser_address2" id="vuser_address2" maxlength="200" size="30"></td>
                </tr>
                <tr>
                    <td>City</td>
                    <td></td>
                    <td><input type=text   class=textbox value="<?php echo htmlentities("$vcity")?>" name="vcity" id="vcity" maxlength="100" size="30"></td>
                </tr>
                <tr>
                    <td>State</td>
                    <td></td>
                    <td><input type=text   class=textbox value="<?php  echo htmlentities("$vstate")?>" name="vstate" id="vstate" maxlength="100" size="30"></td>
                </tr>
                <tr>
                    <td>ZIP</td>
                    <td></td>
                    <td><input type=text   class=textbox value="<?php echo htmlentities("$vzip")?>" name="vzip" id="vzip" maxlength="100" size="30"></td>
                </tr>
                <tr>
                    <td>Country</td>
                    <td></td>
                    <td><select name="vcountry" id="vcountry" class="selectbox" style="width:188px; ">
                                                <?php include "../includes/countries.php"; ?>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td>EMail<font color=red><sup>*</sup></font></td>
                    <td></td>
                    <td><input type=text   class=textbox value="<?php echo htmlentities("$vuser_email")?>" name="vuser_email" id="vuser_email" maxlength="100" size="30"></td>
                </tr>

                <tr>
                    <td>Phone<font color=red></font></td>
                    <td></td>
                    <td align=left><input type=text name="vuser_phone"  class=textbox  id="vuser_phone" maxlength="30"  value="<?php echo htmlentities("$vuser_phone")?>" size="30"></td>
                </tr>
                <tr>
                    <td>Fax</td>
                    <td></td>
                    <td><input type=text  class=textbox name="vuser_fax" id="vuser_fax" maxlength="30"  value="<?php echo htmlentities("$vuser_fax")?>" size="30"></td>
                </tr>
                <tr>
                    <td><br><input class="btn01" type="button" onClick="validate();" value="Save">&nbsp;&nbsp;
                        <input  class=btn02 type=button value="Back"  onClick="goback();"  ><br>&nbsp;</td>
                </tr>

            </table>
        </div>
        <input type=hidden id="id" name="id" value="<?php echo $user;?>">

    </form>
</div>
</div>
<script>
/*var country="";
country = "<?php echo $vcountry; ?>";
 for(i = 0;i < editForm.vcountry.options.length; i++){
     
            if(editForm.vcountry.options[i].text == country){
                editForm.vcountry.options[i].selected=true;
                break;
        }
    }*/
</script>
<?php
include "includes/adminfooter.php";
?>