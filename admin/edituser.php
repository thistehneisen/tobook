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

$countries = getCountries(); 

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
     $message=MSG_PROFILE_UPDATE."!<br>&nbsp;";
     $messageClass = "successmessage";
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
     $messageClass = "errormessage";
}


//get values from lookup table
$sql = "Select vname,vvalue from tbl_lookup where vname='signupfield_disp'";
$result = mysql_query($sql) or die(mysql_error());

$dispRow = mysql_fetch_row($result);
$formfiledarray = unserialize($dispRow[1]);


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

/*
function validate(){

   if(document.editForm.vuser_name.value==""){
      alert("Name cannot be empty");
      document.editForm.vuser_name.focus();
      return false;

   /* }else if(document.editForm.vuser_lastname.value==""){
      alert("Lastname cannot be empty");
      document.editForm.vuser_lastname.focus();
      return false;
*/
/*
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

} */

function goback(){
     document.editForm.action="edituser.php?goback=true";
     document.editForm.submit();
}



function validate(){

        var frm = document.editForm;
        if(frm.vuser_login.value==""){

            alert("<?php echo VALMSG_LOGIN_NAME_EMP;?>");
            frm.vuser_login.focus();
            return false;

        }
        else if(frm.vuser_login.value.length >15){

            alert("<?php echo VALMSG_LOGIN_NAME_LENGTH;?>");
            frm.vuser_login.focus();
            return false;
        }

        else if(frm.vuser_name.value==""){

            alert("<?php echo VALMSG_FIRST_NAME_EMP;?>");
            frm.vuser_name.focus();
            return false;

        }
        else{
            if(checkOptionalFields())
            {
                frm.submit();
            }
            else
            {
                return false;
            }
        }

    }
    function checkOptionalFields()
    {
        var frm = document.editForm;

        if(frm.vuser_email){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'email', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vuser_email.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_EMAIL_EMP;?>");
                frm.vuser_email.focus();
                return false;
            }
            else if(checkMail(frm.vuser_email.value)==false){

                alert('<?php echo VALMSG_EMAIL_INVALID;?>');
                frm.vuser_email.focus();
                return false;

            }else if(checkMail(frm.vuser_email.value)==false){

                alert('Invalid mail format');
                frm.vuser_email.focus();
                return false;

            }
        }

        if(frm.vuser_lastname){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'lastName', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vuser_lastname.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_LAST_NAME_EMP;?>");
                frm.vuser_lastname.focus();
                return false;
            }
        }

        if(frm.vuser_address1){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'address1', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vuser_address1.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_ADDRESS_EMP;?>");
                frm.vuser_address1.focus();
                return false;
            }
        }


        if(frm.vcity){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'city', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vcity.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_CITY_EMP;?>");
                frm.vcity.focus();
                return false;
            }
        }

        if(frm.vstate){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'state', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vstate.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_STATE_EMP;?>");
                frm.vstate.focus();
                return false;
            }
        }

        if(frm.vzip){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'zip', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vzip.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_ZIP_EMP;?>");
                frm.vzip.focus();
                return false;
            }
        }

        if(frm.vuser_phone){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'phone', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vuser_phone.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_PHONE_EMP;?>");
                frm.vuser_phone.focus();
                return false;
            }
        }

        return true;
    }
</script>
<?php
$linkArray = array( USER       =>"admin/usermanager.php",
                    EDIT_USER  =>"admin/edituser.php?id=$user");
?>
<div class="admin-pnl">
    <?php echo getBreadCrumb($linkArray);?>
    <h2><?php echo EDIT_USER;?></h2>
<div class="content-tab-pnl">
    <form name="editForm" id="editForm" method=post action="edituser.php?id=<?php echo $user?>&act=post">
        <div class="form-pnl">
            <p><?php echo SIGNUP_CAPTION;?>.<br></p>
            <?php if($message){ ?>
            <div class="<?php echo $messageClass;?>"><?php  echo $message;?></div>
            <?php } ?>
            <ul>
                <li>
                    <label><?php echo LOGIN_NAME;?> <sup>*</sup></label>
                    <input type="text" readonly name="vuser_login" id="vuser_login" maxlength="100"  value="<?php echo htmlentities("$vuser_login")?>">
                </li>
                <li>
                    <label><?php echo SIGNUP_FIRST_NAME;?><sup>*</sup></label>
                    <input type="text" name="vuser_name" id="vuser_name" maxlength="100"  value="<?php echo htmlentities("$vuser_name")?>">
                </li>
                <?php if (getFormFieldstatus($formfiledarray, 'lastName', 0)) { ?>
                <li>
                    <label><?php echo SIGNUP_LAST_NAME;?> <?php echo (getFormFieldstatus($formfiledarray, 'lastName', 1))?'<sup>*</sup>':'';?></label>
                    <input type="text" name="vuser_lastname" id="vuser_lastname" maxlength="100"  value="<?php echo htmlentities("$vuser_lastname")?>">
                </li>
                 <?php } ?>
                <?php if (getFormFieldstatus($formfiledarray, 'address1', 0)) { ?>
                <li>
                    <label><?php echo SIGNUP_ADDRESS1;?><?php echo (getFormFieldstatus($formfiledarray, 'address1', 1))?'<sup>*</sup>':'';?></label>
                    <input type="text" name="vuser_address1"  value="<?php echo htmlentities("$vuser_address1")?>" id="vuser_address1" maxlength="200">
                </li>
                <?php } ?>
                <!--li>
                    <label>Address 2</label>
                    <input type="text" value="<?php //echo htmlentities("$vuser_address2")?>" name="vuser_address2" id="vuser_address2" maxlength="200">
                </li-->
                <?php if (getFormFieldstatus($formfiledarray, 'city', 0)) { ?>
                <li>
                    <label><?php echo SIGNUP_CITY;?> <?php echo (getFormFieldstatus($formfiledarray, 'city', 1))?'<sup>*</sup>':'';?></label>
                    <input type="text" value="<?php echo htmlentities("$vcity")?>" name="vcity" id="vcity" maxlength="100">
                </li>
                <?php } ?>
                <?php if (getFormFieldstatus($formfiledarray, 'state', 0)) { ?>
                <li>
                    <label><?php echo SIGNUP_STATE;?> <?php echo (getFormFieldstatus($formfiledarray, 'state', 1))?'<sup>*</sup>':'';?></label>
                    <input type="text"  value="<?php  echo htmlentities("$vstate")?>" name="vstate" id="vstate" maxlength="100">
                </li>
                <?php } ?>
                <?php if (getFormFieldstatus($formfiledarray, 'country', 0)) { ?>
                <li>
                    <label><?php echo SIGNUP_COUNTRY;?> <?php echo (getFormFieldstatus($formfiledarray, 'country', 1))?'<sup>*</sup>':'';?></label>
                    <select name="vcountry" id="vcountry" class="selectbox">
                        <?php foreach($countries as $country){ ?>
                        <option value="<?php echo $country['tc_code']; ?>" <?php echo ($country['tc_code']==$vcountry)?'selected':''; ?>> <?php echo $country['tc_name'] ?></option>
                        <?php } ?>
                    <?php //include "../includes/countries.php"; ?>
                    </select>
                </li>
                <?php } ?>
                <?php if (getFormFieldstatus($formfiledarray, 'zip', 0)) { ?>
                <li>
                    <label><?php echo SIGNUP_ZIP;?> <?php echo (getFormFieldstatus($formfiledarray, 'zip', 1))?'<sup>*</sup>':'';?></label>
                    <input type="text" value="<?php echo htmlentities("$vzip")?>" name="vzip" id="vzip" maxlength="100">
                </li>
                 <?php } ?>
                <li>
                    <label><?php echo SIGNUP_EMAIL;?><sup>*</sup></label>
                    <input type="text" value="<?php echo htmlentities("$vuser_email")?>" name="vuser_email" id="vuser_email" maxlength="100">
                </li>
                <?php if (getFormFieldstatus($formfiledarray, 'phone', 0)) { ?>
                <li>
                    <label><?php echo SIGNUP_PHONE;?> <?php echo (getFormFieldstatus($formfiledarray, 'phone', 1))?'<sup>*</sup>':'';?></label>
                    <input type="text" name="vuser_phone" id="vuser_phone" maxlength="30"  value="<?php echo htmlentities("$vuser_phone")?>">
                </li>
                 <?php } ?>
                <!--li>
                    <label>Fax<sup>*</sup></label>
                    <input type="text" name="vuser_fax" id="vuser_fax" maxlength="30"  value="<?php //echo htmlentities("$vuser_fax")?>">
                </li-->
                <!--li>
                    <label>Fax<sup>*</sup></label>
                    <textarea name="" cols="" rows=""></textarea>
                </li-->
                <li>
                    <label>&nbsp;</label>
                    <span class="btn-container">
                        <input  class=btn02 type=button value="<?php echo BTN_BACK;?>"  onClick="goback();"  >
                        <input class="btn01" type="button" onClick="validate();" value="<?php echo BTN_SAVE;?>">
                    </span>
                </li>
            </ul>
        </div>
        <input type=hidden id="id" name="id" value="<?php echo $user;?>">
    </form>
</div>
</div>
<?php
include "includes/adminfooter.php";
?>