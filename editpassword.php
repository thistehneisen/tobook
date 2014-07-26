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
$curTab = 'profile';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/sitefunctions.php";

//sent back if back button is clicked
if ($_GET["goback"] == "true") {
    header("Location:profilemanager.php");
    exit;
}
//update the password
$message = "&nbsp;";
$act = $_GET["act"];

$userId  = $_SESSION["session_userid"];
$userDetails = getUserDetails($userId); 

// Mail send after updating the password
function sendUserPasswordUpdatedMail($userDetails,$password){

    $userName    = $userDetails['vuser_name'].' '.$userDetails['vuser_lastname'];
    $siteName    = getSettingsValue('site_name');
    $adminEmail  = getSettingsValue('admin_mail');
    $email       = $userDetails['vuser_email'];

    $mailbody = "<table>
                <tr>
                    <td>
                        <img src=".$sitelogo."><br><br>
                    </td>
                  </tr>
                  <tr>
                       <td>";
    $mailbody .=  "Dear ". stripslashes($userName).",<br><br>";
    $mailbody .=  VAL_PASSWORD_UPDATE.$password."<br><br>";
    $mailbody .= REGARDS."<br>".THE.$siteName .TEAM."<br></td></tr></table>";

    $Headers="From: $siteName <$adminEmail>\n";
    $Headers.="Reply-To: $siteName <$adminEmail>\n";
    $Headers.="MIME-Version: 1.0\n";
    $Headers.="Content-type: text/html; charset=iso-8859-1\r\n";
    $subject = VAL_PWD;
    $mailsent = @mail($email, $subject, $mailbody,$Headers);
}


If ($act == "post") {

    $sql = "UPDATE tbl_user_mast SET vuser_password='" . md5(addslashes($_POST["vuser_password1"])) . "'
    WHERE nuser_id='" . $_SESSION["session_userid"] . "'";

    mysql_query($sql, $con);

    // Mail send after updating the password
    sendUserPasswordUpdatedMail($userDetails,$_POST["vuser_password1"]);
    
    $message = "<font color='green'><b>Password Updated<br>&nbsp;</b></font>";
}
include "includes/userheader.php";
?>
<script>
    function goback(){
        
        document.editForm.action="editpassword.php?goback=true";
        document.editForm.submit();
        
    }
    function validate(){
        if(document.editForm.vuser_password1.value==""){
            
            alert("<?php echo VAL_NEW_PASSWORD;?>");
            document.editForm.vuser_password1.focus();
            return false;
            
        }else if(document.editForm.vuser_password2.value==""){
            
            alert("<?php echo VAL_CONFIRM_PASSWORD;?>");
            document.editForm.vuser_passoword2.focus();
            return false;
            
        }else if(document.editForm.vuser_password1.value!=document.editForm.vuser_password2.value){
            
            alert("<?php echo VAL_PASSWORD_MISMATCH;?>");
            document.editForm.vuser_password2.focus();
            return false;
            
        }else{
            
            document.editForm.submit();
        }
    }
</script>

<?php $linkArray = array( 
    TOP_LINKS_DASHBOARD=>'usermain.php',
    TOP_LINKS_MY_ACCOUNT =>'profilemanager.php',
    PROFILE_MANAGER_EDIT_PASSWORD_TITLE =>'editpassword.php');
echo getBreadCrumb($linkArray);?>

<h2><?php echo PROFILE_MANAGER_EDIT_PASSWORD_TITLE; ?></h2>

<form name="editForm" method=post action=editpassword.php?act=post>
    <div class="form-pnl">
    <p><?php echo MANDATORY_PART1;?> <font color=red>*</font> <?php echo MANDATORY_PART2 ;?><br></p>
    <?php if($message){ ?>
    <div class="<?php echo $messageClass;?>"><br><?php  echo $message;?></div>
    <?php } ?>
    <ul>
        <li>
            <label><?php echo USER_NEW_PASSWORD;?> <sup>*</sup></label>
            <input class=textbox type=password style="height: 30px;" name="vuser_password1" id="vuser_password1" maxlength="50"  >
        </li>
        <li>
            <label><?php echo USER_CONFIRM_PASSWORD;?> <sup>*</sup></label>
            <input  class=textbox type=password style="height: 30px;" name="vuser_password2"   id="vuser_password2" maxlength="50">
        </li>
        <li>
            <label>&nbsp;</label>
            <span class="btn-container">
                <input type="button" name="btnBack" value="Back" class="btn02"  onClick="goback();" >
                <input  class=btn01 type="button" onClick="validate();" value="Save">
            </span>
        </li>
    </ul>
</div>
<br><br><br>&nbsp;
</form>
<?php
include "includes/userfooter.php";
?>