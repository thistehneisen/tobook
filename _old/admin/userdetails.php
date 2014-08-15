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

include "includes/adminheader.php";
?>

<?php
$linkArray = array( PAYMENTS       =>"admin/payment.php",
                    USER_DETAILS  =>"admin/userdetails.php?id=$user");
?>
<div class="admin-pnl">
    <?php echo getBreadCrumb($linkArray);?>
    <h2><?php echo USER_DETAILS;?></h2>
<div class="content-tab-pnl">
    <form name="editForm" id="editForm" method=post action="edituser.php?id=<?php echo $user?>&act=post">
        <div class="form-pnl">
           
            
            <ul>
                <li>
                    <label><?php echo LOGIN_NAME;?></label>
                    <label><?php echo $vuser_login?></label>
                </li>
                <li>
                    <label><?php echo SIGNUP_FIRST_NAME;?></label>
                     <label><?php echo $vuser_name?></label>
                </li>
                <li>
                    <label><?php echo SIGNUP_LAST_NAME;?></label>
                    <label><?php echo $vuser_lastname?></label>
                </li>
                <li>
                    <label><?php echo SIGNUP_ADDRESS1;?></label>
                   <label><?php echo $vuser_address1?></label>
                </li>
                <!--li>
                    <label>Address 2</label>
                    <input type="text" value="<?php //echo htmlentities("$vuser_address2")?>" name="vuser_address2" id="vuser_address2" maxlength="200">
                </li-->
                <li>
                    <label><?php echo SIGNUP_CITY;?></label>
                    <label><?php echo $vcity?></label>
                </li>
                <li>
                    <label><?php echo SIGNUP_STATE;?></label>
                    <label><?php  echo $vstate?></label>
                </li>
                <!--<li>
                    <label>Country</label>
                    <select name="vcountry" id="vcountry" class="selectbox">
                        <?php foreach($countries as $country){ ?>
                        <option value="<?php echo $country['tc_code']; ?>" <?php echo ($country['tc_code']==$vcountry)?'selected':''; ?>> <?php echo $country['tc_name'] ?></option>
                        <?php } ?>
                    <?php //include "../includes/countries.php"; ?>
                    </select>
                </li>-->
                <li>
                    <label><?php echo SIGNUP_ZIP;?></label>
                    <label><?php echo $vzip?></label>
                </li>
                <li>
                    <label><?php echo SIGNUP_EMAIL;?></label>
                    <label><?php echo $vuser_email?></label>
                </li>
                <li>
                    <label><?php echo SIGNUP_PHONE;?></label>
                    <label><?php echo $vuser_phone?></label>
                </li>
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
                        <a href="payment.php" class=btn02 ><?php echo BTN_BACK;?></a>
                        <!--<input  class=btn02 type=button value="Back"  onClick="javascript:history(-1)"  >-->
                        
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