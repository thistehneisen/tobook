<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                      |                                  |
// +----------------------------------------------------------------------+
?>
<?php

$curTab = 'signup';
error_reporting(0);
//include common files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "./includes/adminheader.php";

function isValidUsername($str) {
    if (trim($str) != "") {
        if (eregi("[^0-9a-zA-Z+_]", $str)) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

//handle post back of signup
$message = "";
$act = "";
if (isset($_GET["act"])) {
    $act = $_GET["act"];
}


if ($act == "post") { //if postback

    /* For setting editor path */

    $rootPath = getSettingsValue('rootpath');
    $_SESSION['ROOT_PATH'] = $rootPath;

    $serverPermission = getSettingsValue('serverPermission');
    if ($serverPermission == "0755") {
        $_SESSION['SERVER_PERMISSION'] = 0755;
    } else {
        $_SESSION['SERVER_PERMISSION'] = 0777;
    }
    //permission setting ends
    //check for duplicate user name
    $sql = "SELECT * from tbl_user_mast WHERE vuser_login='" . addslashes($_POST["vuser_login"]) . "' AND vdel_status != '1'";
    $result = mysql_query($sql, $con) or die(mysql_error());
    //check for duplicate user name
    $sqlEmailCheck = "SELECT * from tbl_user_mast WHERE vuser_email='" . addslashes($_POST["vuser_email"]) . "' AND vdel_status != '1'";
    $resultEmailCheck = mysql_query($sqlEmailCheck, $con) or die(mysql_error());
    if (!isValidUsername($_POST["vuser_login"])) {
        $message = " Enter valid login name";
    } else if (mysql_num_rows($result) > 0) {
        $message = "Login name already exists. Please try again!";
    } else if (mysql_num_rows($resultEmailCheck) > 0) {
        $message = "Email already exists. Please try again!";
    } else {
        $var_naffid = 0;
        if (isset($_SESSION["session_naffid"]) && $_SESSION["session_naffid"] != "") {
            $var_naffid = $_SESSION["session_naffid"];
        }
        //create new account
        $sql = "insert into `tbl_user_mast` (nuser_id,vuser_login,vuser_password,
                vuser_name,vuser_lastname,vuser_address1,vuser_address2,vcity,vstate,vzip,vcountry,
                        vuser_email,vuser_phone,vuser_fax,
                        duser_join,vuser_style,naff_id,vdel_status) values
          (  '', '" . addslashes($_POST["vuser_login"]) . "','" . md5(addslashes($_POST["vuser_password"])) . "','" .
                addslashes($_POST["txtFirstName"]) . "','" .
                addslashes($_POST["txtLastName"]) . "','" .
                addslashes($_POST["vuser_address1"]) . "','" .
                addslashes($_POST["vuser_address2"]) . "','" .
                addslashes($_POST["txtCity"]) . "','" .
                addslashes($_POST["txtState"]) . "','" .
                addslashes($_POST["txtZip"]) . "','" .
                addslashes($_POST["cmbCountry"]) . "','" .
                addslashes($_POST["vuser_email"]) . "','" .
                addslashes($_POST["vuser_phone"]) . "','" .
                addslashes($_POST["vuser_fax"]) . "',now(),'site.css','" . $var_naffid . "','0')";

        mysql_query($sql, $con);
        $_SESSION["session_naffid"] = "";
        //check if session alredy populated
        if ($_SESSION["session_lookupsitename"] != "") {

            $lookupsitename = getSettingsValue('site_name');
            $admin_email = getSettingsValue('admin_mail');
        }//if session not populated
        else {

            //get values from lookup table
            $sql = "Select vname,vvalue from tbl_lookup where vname IN('site_name','admin_mail','Logourl','rootserver','secureserver','template_dir','signupfield_disp') Order by vname ASC";
            $result = mysql_query($sql) or die(mysql_error());

            if (mysql_num_rows($result) > 0) {

                while ($row = mysql_fetch_array($result)) {

                    switch ($row["vname"]) {

                        case "site_name":
                            $_SESSION["session_lookupsitename"] = $row["vvalue"];
                            break;

                        case "Logourl":
                            $_SESSION["session_logourl"] = $row["vvalue"];
                            break;

                        case "admin_mail":
                            $_SESSION["session_lookupadminemail"] = $row["vvalue"];
                            break;

                        case "rootserver":
                            $_SESSION["session_rootserver"] = $row["vvalue"];
                            break;

                        case "secureserver":
                            $_SESSION["session_secureserver"] = $row["vvalue"];
                            break;

                        case "template_dir":
                            $_SESSION["session_template_dir"] = $row["vvalue"];
                            break;
                    }//end swith
                }//end while
            }//end if

            $lookupsitename = $_SESSION["session_lookupsitename"];
            $admin_email = $_SESSION["session_lookupadminemail"];
        }


        //mail the user welcome mail
        $rootDirectory = getSettingsValue('root_directory');
        $sitelogo = $rootDirectory . '/' . getSettingsValue('Logourl');
        $message = "<table>
                <tr>
                    <td>
                        <img src=" . $sitelogo . "><br><br>
                    </td>
                  </tr>
                  <tr>
                       <td> 
                        Dear " . $_POST["txtLastName"] . "  " . $_POST["txtFirstName"] . ",<br><br>
                       </td>
                  </tr>
                  <tr>
                    <td>
                        We're excited that you have chosen to signup with $lookupsitename. <br><br>
                            
                        Your login information are as follows<br>
                            
                         username :" . $_POST["vuser_login"] . "   <br>
                         password :" . $_POST["vuser_password"] . "  <br><br>
                             
                         Click <a href='" . $rootDirectory . "/login.php'>here</a> to login.
                             
                        Thank you again for your interest shown on $lookupsitename. <br><br>
                            
                            
                        Regards<br>
                        $lookupsitename Team<br>
                    </td>
                 </tr>";


        $headers = "From: $admin_email\n";
        $headers.="Reply-To: $admin_email\n";
        $headers.="MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $to = $_POST["vuser_email"];
        $subject = "Welcome to $lookupsitename";
        @mail($to, $subject, $message, $headers);



        $headers = "From: $admin_email\n";
        $headers.="Reply-To: $admin_email\n";
        $headers.="MIME-Version: 1.0\n";
        $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
        $to = $admin_email;
        $subject = "A new signup at $lookupsitename";
         
	   $subject="A new signup at $lookupsitename";
          $message="<table>
                <tr>
                    <td>
                        <img src=".$sitelogo."><br><br>
                    </td>
                  </tr>
                  <tr>
                       <td> Hello Admin,<br><br>There is a new signup at $lookupsitename<br>
          The following are the details: <br>
          First Name :".$_POST["txtFirstName"]." <br>
          Last Name :".$_POST["txtLastName"]." <br>
          Username :".$_POST["vuser_login"]."   <br>
          Email :".$_POST["vuser_email"]."   <br>
          <br>Regards<br>
      $lookupsitename<br></td></tr></table>";
        @mail($to, $subject, $message, $headers);

        //redirect to logged in area
        $_SESSION["session_userid"] = mysql_insert_id();
        $_SESSION["session_loginname"] = $_POST["vuser_login"];
        $_SESSION["session_email"] = $_POST["vuser_email"];
        $msg ="<font color='green'>User created successfully</font>";
        Header("location:usermanager.php?msg=".$msg);

        exit;
    }
}
//get values from lookup table
$sql = "Select vname,vvalue from tbl_lookup where vname='signupfield_disp'";
$result = mysql_query($sql) or die(mysql_error());

$dispRow = mysql_fetch_row($result);
$formfiledarray = unserialize($dispRow[1]);

include "includes/subpageheader.php";
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
        
        var frm = document.regForm;
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
        
        else if(frm.vuser_password.value==""){
            
            alert("<?php echo CONF_PSWD_EMP;?>");
            frm.vuser_password.focus();
            return false;
            
        }else if(frm.txtFirstName.value==""){
            
            alert("<?php echo VALMSG_FIRST_NAME_EMP;?>");
            frm.txtFirstName.focus();
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
        var frm = document.regForm;
        
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
                
                alert('<?php echo VALMSG_EMAIL_INVALID;?>');
                frm.vuser_email.focus();
                return false;
                
            }
        }
        
        if(frm.txtLastName){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'lastName', 1)) echo '1'; else echo '0'; ?>;
            if(frm.txtLastName.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_LAST_NAME_EMP;?>");
                frm.txtLastName.focus();
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
        
        
        
        
        if(frm.vuser_address2){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'address2', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vuser_address2.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_ADDRESS_EMP;?>");
                frm.vuser_address2.focus();
                return false;
            }
        }
        
        
        if(frm.txtCity){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'city', 1)) echo '1'; else echo '0'; ?>;
            if(frm.txtCity.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_CITY_EMP;?>");
                frm.txtCity.focus();
                return false;
            }
        }
        
        if(frm.txtState){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'state', 1)) echo '1'; else echo '0'; ?>;
            if(frm.txtState.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_STATE_EMP;?>");
                frm.txtState.focus();
                return false;
            }
        }
        
        if(frm.txtZip){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'zip', 1)) echo '1'; else echo '0'; ?>;
            if(frm.txtZip.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_ZIP_EMP;?>");
                frm.txtZip.focus();
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
        
        if(frm.vuser_fax){
            var proceedFlag = <?php if (getFormFieldstatus($formfiledarray, 'fax', 1)) echo '1'; else echo '0'; ?>;
            if(frm.vuser_fax.value=="" && proceedFlag==1){
                alert("<?php echo VALMSG_FAX_EMP;?>");
                frm.vuser_fax.focus();
                return false;
            }
        }
        
        return true;
    }
</script>
<div class="headingstyle_new">
    <h1><?php echo CREATE_USER_TITLE; ?></h1>
    
</div>

<div class="common_box" align="center">

    <form name="regForm" method=post action=adduser.php?act=post>

        <table cellpadding="0" cellspacing="0" border="0" class="commntbl_style1" align="left">
            <tr>
                <td align=center colspan=3  ><font color=red><?php echo $message; ?></font></td>
            </tr>

            <tr>
                <td width="25%" align=left ><?php echo LOGIN_NAME; ?><font color=red> *</font></td>
                <td width="4%"></td>
                <td width="71%" align=left><input type=text   name="vuser_login" id="vuser_login" maxlength="15" value="<?php echo $_POST["vuser_login"] ?>" size="30"></td>
            </tr>

            <tr>
                <td align=left ><?php echo LOGIN_PASSWORD; ?><font color=red> *</font></td>
                <td></td>
                <td align=left><input  type=password name="vuser_password" id="vuser_password" maxlength="50"  value="<?php echo $_POST["vuser_password"] ?>" size="30"></td>
            </tr>

            <tr>
                <td align=left ><?php echo SIGNUP_FIRST_NAME; ?><font color=red> *</font></td>
                <td></td>
                <td align=left><input type=text   name="txtFirstName" id="txtFirstName" maxlength="100"  value="<?php echo $_POST["txtFirstName"] ?>" size="30"></td>
            </tr>

            <!--commented for new design-->	
            <?php if (getFormFieldstatus($formfiledarray, 'lastName', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_LAST_NAME; ?><?php if (getFormFieldstatus($formfiledarray, 'lastName', 1)) { ?>
                            <font color=red>*</font> 
                        <?php } ?></td>
                    <td></td>
                    <td align=left><input type=text   name="txtLastName" id="txtLastName" maxlength="100"  value="<?php echo $_POST["txtLastName"] ?>" size="30"></td>
                </tr>
            <?php } ?>
            <tr>
                <td align=left ><?php echo SIGNUP_EMAIL; ?><font color=red> *</font></td>
                <td></td>
                <td align=left><input type=text    value="<?php echo $_POST["vuser_email"] ?>" name="vuser_email" id="vuser_email" maxlength="100" size="30"></td>
            </tr>
            <?php if (getFormFieldstatus($formfiledarray, 'address1', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_ADDRESS1; ?><?php if (getFormFieldstatus($formfiledarray, 'address1', 1)) { ?>
                            <font color=red>*</font> 
                        <?php } ?></td>
                    <td></td>
                    <td align=left><input type=text   name="vuser_address1"  value="<?php echo $_POST["vuser_address1"] ?>" id="vuser_address1" maxlength="200" size="30"></td>
                </tr>
            <?php } ?>
                
            <?php if (getFormFieldstatus($formfiledarray, 'address2', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_ADDRESS2; ?><?php if (getFormFieldstatus($formfiledarray, 'address2', 1)) { ?>
                            <font color=red>*</font> 
                        <?php } ?></td>
                    <td></td>
                    <td align=left><input type=text    value="<?php echo $_POST["vuser_address2"] ?>" name="vuser_address2" id="vuser_address2" maxlength="200" size="30"></td>
                </tr>
            <?php } ?>
                
            <?php if (getFormFieldstatus($formfiledarray, 'city', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_CITY; ?><?php if (getFormFieldstatus($formfiledarray, 'city', 1)) { ?>
                            <font color=red>*</font> 
                        <?php } ?></td>
                    <td></td>
                    <td align=left><input type=text    value="<?php echo $_POST["txtCity"] ?>" name="txtCity" id="txtCity" maxlength="100" size="30"></td>
                </tr>
            <?php } ?>
                
            <?php if (getFormFieldstatus($formfiledarray, 'state', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_STATE; ?><?php if (getFormFieldstatus($formfiledarray, 'state', 1)) { ?>
                            <font color=red>*</font> 
                        <?php } ?></td>
                    <td></td>
                    <td align=left><input type=text value="<?php echo $_POST["txtState"] ?>" name="txtState" id="txtState" maxlength="100" size="30"></td>
                </tr>
            <?php } ?>
            <?php if (getFormFieldstatus($formfiledarray, 'country', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_COUNTRY; ?><?php if (getFormFieldstatus($formfiledarray, 'country', 1)) { ?>
                            <font color=red>*</font>
                        <?php } ?></td>
                    <td></td>
                    <td align=left>
                        <select name="cmbCountry"  class="selectbox" style="width:368px;height: 34px;padding-top: 6px; ">
                            <?php include "../includes/countries.php"; ?>
                        </select></td>
                </tr>
            <?php } ?>
                
            <?php if (getFormFieldstatus($formfiledarray, 'zip', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_ZIP; ?><?php if (getFormFieldstatus($formfiledarray, 'zip', 1)) { ?>
                            <font color=red>*</font> 
                        <?php } ?></td>
                    <td></td>
                    <td align=left><input type=text    value="<?php echo $_POST["txtZip"] ?>" name="txtZip" id="txtZip" maxlength="20" size="30"></td>
                </tr>
            <?php } ?>
                
            <?php if (getFormFieldstatus($formfiledarray, 'phone', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_PHONE; ?><?php if (getFormFieldstatus($formfiledarray, 'phone', 1)) { ?>
                            <font color=red> *</font>
                        <?php } ?><font color=red></font></td>
                    <td></td>
                    <td align=left><input type=text   name="vuser_phone"  id="vuser_phone" maxlength="30"  value="<?php echo $_POST["vuser_phone"] ?>" size="30"></td>
                </tr>
            <?php } ?>
                
            <?php if (getFormFieldstatus($formfiledarray, 'fax', 0)) { ?>
                <tr>
                    <td align=left ><?php echo SIGNUP_FAX; ?><?php if (getFormFieldstatus($formfiledarray, 'fax', 1)) { ?>
                            <font color=red> *</font>
                        <?php } ?></td>
                    <td></td>
                    <td align=left><input type=text   name="vuser_fax" id="vuser_fax" maxlength="30"  value="<?php echo $_POST["vuser_fax"] ?>" size="30"></td>
                </tr>
            <?php } ?>
            <!--commented for new design	-->

            <tr>
                <td valign="top" align="left" colspan="2" class="extrapadding"></td>
                <td  align=center >
                </td>
            </tr>

            <tr>
                <td colspan="2">&nbsp;</td>
                <td align="left">
                    <input class="btn01" type="button" onClick="validate();" value="<?php echo CREATE_USER_TITLE; ?>">
                  <input  class=btn01 type=reset  onClick="window.location='usermanager.php'" value="<?php echo BTN_BACK;?>"></td>
            </tr>
            <tr>
                <td  colspan=2 align=center ></td>
                    
                <td align="left" >

                </td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="3">&nbsp;</td>
            </tr>
        </table>
    </form>

</div>


<script>
    if(document.regForm.cmbCountry){
        for(i=0;i<regForm.cmbCountry.options.length;i++){
            if(regForm.cmbCountry.options[i].text == "UnitedStates"){
                regForm.cmbCountry.options[i].selected=true;
                break;
            }
        }
    }
</script>
<?php
include "includes/userfooter.php";
?>