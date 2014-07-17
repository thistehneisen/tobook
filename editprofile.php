<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                            |
// |                                                                                                            |
// +----------------------------------------------------------------------+
?>
<?php

$curTab = 'profile';

//include files
include "includes/session.php";
include "includes/config.php";


//sent back if back button is clicked
if ($_GET["goback"] == "true") {
    header("Location:profilemanager.php");
    exit;
}




$act = $_GET["act"];

If ($act == "post") {

//update tables with the new value
    $sql = "UPDATE tbl_user_mast SET vuser_name='" . addslashes($_POST["vuser_name"]) . "',
    vuser_lastname='" . addslashes($_POST["vuser_lastname"]) . "',vuser_address1='" . addslashes($_POST["vuser_address1"]) . "',
    vuser_address2='" . addslashes($_POST["vuser_address2"]) . "',
     vuser_email='" . addslashes($_POST["vuser_email"]) . "',
     vuser_phone='" . addslashes($_POST["vuser_phone"]) . "',
     vcity='" . addslashes($_POST["vcity"]) . "',
     vstate='" . addslashes($_POST["vstate"]) . "',
     vzip='" . addslashes($_POST["vzip"]) . "',
     vcountry='" . addslashes($_POST["vcountry"]) . "',
     vuser_fax='" . addslashes($_POST["vuser_fax"]) . "' WHERE nuser_id='" . $_SESSION["session_userid"] . "'";

    mysql_query($sql, $con);



    $message = "<font color='green'><b>Profile Updated<br>&nbsp</b></font>";
}

//get the currrent values of the profile

$sql = "select vuser_login,vuser_name,vuser_lastname,vuser_address1,vuser_address2,vcity,vstate,vzip,vcountry,vuser_email,vuser_phone,vuser_fax from tbl_user_mast where nuser_id='" . $_SESSION["session_userid"] . "'";


$result = mysql_query($sql, $con);

if (mysql_num_rows($result) > 0) {

    $row = mysql_fetch_array($result);

    $vuser_login = stripslashes($row["vuser_login"]);
    $vuser_name = stripslashes($row["vuser_name"]);
    $vuser_lastname = stripslashes($row["vuser_lastname"]);
    $vuser_address1 = stripslashes($row["vuser_address1"]);
    $vuser_address2 = stripslashes($row["vuser_address2"]);
    $vcity = stripslashes($row["vcity"]);
    $vstate = stripslashes($row["vstate"]);
    $vzip = stripslashes($row["vzip"]);
    $vcountry = stripslashes($row["vcountry"]);
    $vuser_email = stripslashes($row["vuser_email"]);
    $vuser_phone = stripslashes($row["vuser_phone"]);
    $vuser_fax = stripslashes($row["vuser_fax"]);
} else {

    $message = "Invalid userid. Please retry!";
}
include "includes/userheader.php";
?>
<script>
    
    function goback(){
        
        document.editForm.action="editprofile.php?goback=true";
        document.editForm.submit();
        
    }
    
    function checkMail(email)
    {
        var str1=email;
        var arr=str1.split("@");
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
            
            alert("<?php echo VAL_FNAME;?>");
            document.editForm.vuser_name.focus();
            return false;
            
        }else if(document.editForm.vuser_lastname.value==""){
            
            alert("<?php echo VAL_LNAME;?>");
            document.editForm.vuser_lastname.focus();
            return false;
            
        }else if(document.editForm.vuser_address1.value==""){
            
            alert("<?php echo VAL_ADDRESS;?>");
            document.editForm.vuser_address1.focus();
            return false;
            
        }else if(document.editForm.vuser_email.value==""){
            
            alert("<?php echo VAL_EMAIL;?>");
            document.editForm.vuser_email.focus();
            return false;
            
        }else if(checkMail(document.editForm.vuser_email.value)==false){
            
            alert('<?php echo VAL_INVALID_MAIL;?>');
            document.editForm.vuser_email.focus();
            return false;
            
        }else{
            
            document.editForm.submit();
            
        }
        
    }
</script>

<?php $linkArray = array(
    TOP_LINKS_DASHBOARD=>'usermain.php',
    TOP_LINKS_MY_ACCOUNT =>'profilemanager.php',
                          PROFILE_MANAGER_EDIT_PROFILE_TITLE =>'editprofile.php');
echo getBreadCrumb($linkArray);?>

<h2><?php echo PROFILE_MANAGER_EDIT_PROFILE_TITLE; ?></h2>
<form name="editForm" method=post action=editprofile.php?act=post>
    <div class="form-pnl">
        <p><?php echo MANDATORY_PART1 ;?> <font color=red>*</font> <?php echo MANDATORY_PART2 ;?><br></p>
        <?php if ($message) { ?>
            <div class="<?php echo $messageClass; ?>"><br><?php echo $message; ?></div>
        <?php } ?>
        <ul>
            <li>
                <label><?php echo USER_LOGIN_NAME ;?> </label>
                <input type=text size="30" readonly class=textbox name="vuser_login" id="vuser_login" maxlength="100"  value="<?php echo htmlentities("$vuser_login"); ?>">
            </li>
            <li>
                <label><?php echo USER_FIRST_NAME ;?><sup>*</sup></label>
                <input type=text size="30" class=textbox name="vuser_name" id="vuser_name" maxlength="100"  value="<?php echo htmlentities("$vuser_name"); ?>">
            </li>
            <li>
                <label><?php echo USER_LAST_NAME ;?><sup>*</sup></label>
                <input type=text size="30" class=textbox name="vuser_lastname" id="vuser_lastname" maxlength="100"  value="<?php echo htmlentities("$vuser_lastname"); ?>">
            </li>
            <li>
                <label><?php echo USER_EMAIL ;?><sup>*</sup></label>
                <input type=text size="30"   class=textbox value="<?php echo htmlentities("$vuser_email"); ?>" name="vuser_email" id="vuser_email" maxlength="100">
            </li>
            <li>
                <label><?php echo USER_ADDRESS1 ;?><sup>*</sup></label>
                <input type=text size="30"  class=textbox name="vuser_address1"  value="<?php echo htmlentities("$vuser_address1"); ?>" id="vuser_address1" maxlength="200">
            </li>
            <!--li>
                <label>Address2</label>
                <input type=text size="30"   class=textbox value="<?php //echo htmlentities("$vuser_address2"); ?>" name="vuser_address2" id="vuser_address2" maxlength="200">
            </li-->
            <li>
                <label><?php echo USER_CITY ;?></label>
                <input type=text size="30"   class=textbox value="<?php echo htmlentities("$vcity"); ?>" name="vcity" id="vcity" maxlength="100">
            </li>
            <li>
                <label><?php echo USER_STATE ;?></label>
                <input type=text size="30"   class=textbox value="<?php echo htmlentities("$vstate"); ?>" name="vstate" id="vstate" maxlength="100">
            </li>
            <li>
                <label><?php echo USER_COUNTRY ;?></label>
                <select name="vcountry" id="vcountry" class="selectbox">
                    <?php
                    $country = $vcountry;
                    include "includes/countries.php";
                    ?>
                </select>
            </li>
            <li>
                <label><?php echo USER_ZIP ;?></label>
                <input type=text size="30"   class=textbox value="<?php echo htmlentities("$vzip"); ?>" name="vzip" id="vzip" maxlength="100">
            </li>
            
            <li>
                <label><?php echo USER_PHONE ;?></label>
                <input type=text size="30" name="vuser_phone"  class=textbox  id="vuser_phone" maxlength="30"  value="<?php echo htmlentities("$vuser_phone"); ?>">
            </li>
            <!--li>
                <label>Fax</label>
                <input type=text size="30"  class=textbox name="vuser_fax" id="vuser_fax" maxlength="30"  value="<?php //echo htmlentities("$vuser_fax"); ?>">
            </li-->
                
                
                
            <li>
                <label>&nbsp;</label>
                <span class="btn-container">
                    <input type="button" name="btnBack" value="Back" class="btn02"  onClick="goback();" >
                   <!--<input  type="button" onClick="validate();" value="Save">-->
                    <input  class="button btn01" type="button" onClick="validate();" value="Save">
                        
                </span>
            </li>
        </ul>
    </div>
   <br>&nbsp;
</form>
<script>
    var country="";
    country="<?php echo $vcountry; ?>";
    for(i=0;i<editForm.vcountry.options.length;i++){
        if(editForm.vcountry.options[i].text == country){
            editForm.vcountry.options[i].selected=true;
            break;
        }
    }
</script>
<?php
include "includes/userfooter.php";
?>