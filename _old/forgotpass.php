<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of easycreate sitebuilder                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
//include files
include "includes/session.php";
include "includes/config.php";
//handle post back of signup

$act=$_GET["act"];
$message="";

if($act=="post") {
    $loginname=$_POST["vuser_login"];
    $sql="SELECT * from tbl_user_mast WHERE vuser_login='".addslashes($loginname)."' and vdel_status='0'";
    $result=mysql_query($sql,$con) or die(mysql_error());
    if(mysql_num_rows($result) > 0) {
        //generate new password
        $row = mysql_fetch_array($result);
        $password = $row["vuser_password"];
        $email = $row["vuser_email"];
        $userid = $row["nuser_id"];
        $username = $row["vuser_name"];
        $seed = rand(1, 15000);
        $pass = $PHPSESSID.$seed;
        $pass = substr($pass, -8);
        $newpass = md5($pass);

        //reset old password to new

        $sql = " UPDATE tbl_user_mast SET vuser_password='$newpass' ";
        $sql .= " WHERE nuser_id = '$userid' ";
        mysql_query($sql);

        if($_SESSION["session_lookupsitename"] != "") {
            $serverroot		= $_SESSION["session_rootserver"];
            $lookupsitename = $_SESSION["session_lookupsitename"];
            $admin_email = $_SESSION["session_lookupadminemail"];

        } else {

            $sql 	= "Select vname,vvalue from tbl_lookup where vname IN('site_name','admin_mail','Logourl','rootserver','secureserver','template_dir') Order by vname ASC";
            $result	= mysql_query($sql) or die(mysql_error());

            if(mysql_num_rows($result) >0) {

                while($row = mysql_fetch_array($result)) {

                    switch($row["vname"]) {

                        case "site_name":
                            $lookupsitename = $row["vvalue"];
                            break;

                        case "admin_mail":
                            $admin_email = $row["vvalue"];
                            break;

                        case "rootserver":
                            $serverroot = $row["vvalue"];
                            break;

                    }
                }
            }
            //$serverroot		= "www.easycreate.com";
            //$lookupsitename = "www.easycreate.com";

            //$admin_email = "admin@easycreate.com";

        }

        //mail user about new passsword

        $mailbody = "";

       // $mailbody .=  "<html>Dear ". stripslashes($username).",<br><br>";

        $mailbody .=  "Somebody or you have requested a password reset for your account with $lookupsitename<br>";

        $mailbody .= "This email was sent automatically by the $lookupsitename server and is part of the secure password <br>";

        $mailbody .= "reset process. This is done for your protection -- only you, <br>";

        $mailbody .= "the recipient of this email.<br>";

        $mailbody .= "To continue, follow the link below, which will lead you to $lookupsitename login page.<br>";

        $mailbody .= " <a href=".BASE_URL."login.php>$lookupsitename User Section</a><br>";

        $mailbody .= " User name : " . $_POST["vuser_login"] . "<br>";

        $mailbody .= " Password : " . stripslashes($pass) . "<br><br>" ;

        $mailbody .= "Regards,<br>The $lookupsitename Team<br></html>";


        $sitelogo=BASE_URL.getSettingsValue('Logourl');
            $message="<table>
                <tr>
                    <td>
                        <img src=".$sitelogo."><br><br>
                    </td>
                  </tr>
                  <tr>
                       <td> 
                        Dear  ". stripslashes($username).", <br><br>
                       </td>
                  </tr>
                  <tr>
                    <td> ".$mailbody."               </td>
                 </tr>";
               







        $Headers="From: $lookupsitename <$admin_email>\n";

        $Headers.="Reply-To: $lookupsitename <$admin_email>\n";

        $Headers.="MIME-Version: 1.0\n";

        $Headers.="Content-type: text/html; charset=iso-8859-1\r\n";
 
        mail($email, "Your Account Information",  $message,$Headers);

        $message = "Your password has been successfully sent to your email address provided in your profile.!<br>&nbsp;";

        $loginname="";

    }else {
        $message="Invalid User Name!";
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
        $("#passForm").validate({
            rules: {
                vuser_login: {required: true}
            },
            messages: {
                vuser_login: {required: "Enter username"}
            }
        });
    });

    function validate(){
        if(document.passForm.vuser_login.value==""){
            alert("<?php echo FORGOT_MSG;?>");
            document.passForm.vuser_login.focus();
            return false;
        }else{
            document.passForm.submit();
        }
    }

</script>


<div class="headingstyle_new">
    <h1 ><?php echo FORGOT_PWD;?></h1>
    <h5 ><?php echo FORGOT_VAL_MSG;?> </h5>
</div>
 
<div class="common_box" align="center">

    <form name="passForm" id="passForm" method=post action=forgotpass.php?act=post>
        <table cellpadding="0" width="800" border="0" align="left" class="commntbl_style1">
            <tr>
                <td  colspan='2' style="padding:0 0 0 0px"><font color=red><?php echo $message;?></font></td>
            </tr>
            <tr>
                <td width="15%" align=left valign="middle" class=maintext><?php echo FORGOT_USERNAME;?><font color=red><sup>*</sup></font></td>

                <td align=left valign="middle"><input  class=textbox type=text name="vuser_login" id="vuser_login" maxlength="50" value="<?php echo $loginname; ?>"></td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td align="left" style="padding-left:172px; "><input class="get_pwd" type="submit"  value=""></td>
            </tr>
        </table>
    </form>
</div>

<?php

include "includes/footer.php";

?>