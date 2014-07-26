<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
//include files
include "includes/session.php";
include "includes/config.php";

//sent back on clicking back button
if($_GET["goback"]=="true"){

   header("Location:filemanager.php");
   exit;
}


// get data of postback
$vuser_file=$_GET["filename"];
$vuser_site=$_GET["sitename"];
$vuser_fileid=$_GET["fileid"];
$vuser_location=$_GET["location"];
$vremote_dir=$_GET["remotedir"];

//handle post back of upload
$act=$_GET["act"];
$message="&nbsp;";

if($act=="post"){


                        // setup $host and $file variables for your setup before here...
                           $host=$_POST["vuser_domain"];
                           $hostip = gethostbyname($host);

                           //check if ftp domain is correct
                           if(@ftp_connect($hostip)){

                                           $conn_id = @ftp_connect($hostip);
                                           $ftp_user_name =$_POST["vuser_name"];
                                           $ftp_user_pass =$_POST["vuser_password"];

                                        // login with username and password
                                           if($login_result=@ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)){

                                                                                // IMPORTANT!!! turn passive mode on
                                                                           @ftp_pasv ( $conn_id, true );

                                                                           if ((!$conn_id) || (!$login_result)) {

                                                                                         $message.= "FTP connection has failed!";
                                                                                         $message.= "Attempted to connect to $host for user $ftp_user_name";


                                                                                   } else {

                                                                                        $message.= "Connected to $host, for user $ftp_user_name<br>";
                                                                                   // upload a file
                                                                                   $remote_location=$_POST["vremote_dir"];
                                                                                   $remote_file=$remote_location."/".$vuser_file;
                                                                                  // delete  file
                                                                                   if (@ftp_delete($conn_id, $remote_file)) {

                                                                                          $message.= "successfully deleted ".$vuser_file."<br>";
                                                                                          $vuser_file="";
                                                                                          $vuser_site="";
                                                                                          $vuser_location="";
                                                                                          $vremote_dir="";
                                                                                          $ftp_user_name="";
                                                                                          $ftp_user_pass="";
                                                                                                                                                                              //remove from database
                                                                                           $sql="delete from tbl_files where nfile_id='".$vuser_fileid."'";

                                                                                           mysql_query($sql,$con) or die(mysql_error());




                                                                                   } else {

                                                                                          $message.= "There was a problem while deleting ".$vuser_file."<br>";
                                                                                     }

                                                                                           //remove from database
                                                                                           $sql="delete from tbl_files where nfile_id='".$vuser_fileid."'";
                                                                                           mysql_query($sql,$con) or die(mysql_error());

                                                                                           // close the connection
                                                                                           @ftp_close($conn_id);


                                                                                    }

                                           }else{

                                                           $message .= "Cannot Connect to ftp server.Invalid login info! ";

                                           }


                           }else{

                                           $message.= "Cannot Connect to ftp server.Invalid domain provided! ";

                           }

}
include "includes/userheader.php";

?>





<script>
function goback(){

         document.deleteForm.action="ftpdelete.php?goback=true";
         document.deleteForm.submit();

}
function validate(){

    if(document.deleteForm.vuser_domain.value==""){

      alert("Domain Name cannot be empty");
      document.deleteForm.vuser_domain.focus();
      return false;

    }else if(document.deleteForm.vuser_name.value==""){

      alert("Name cannot be empty");
      document.deleteForm.vuser_name.focus();
      return false;

    }else if(document.deleteForm.vuser_password.value==""){

      alert("Password cannot be empty");
      document.deleteForm.vuser_password.focus();
      return false;

    }else{

    document.deleteForm.submit();

    }

}
</script>

<table width=50%><tr>
<td align=center><img src=images/deletefile.gif>
<form name="deleteForm" method=post action="ftpdelete.php?act=post&fileid=<?php echo $vuser_fileid;?>&sitename=<?php echo $vuser_site;?>&filename=<?php echo $vuser_file;?>&location=<?php echo $vuser_location;?>&remotedir=<?php echo $vremote_dir;?>">
<fieldset >
<table>

<tr>
<td align=center colspan=3 class=maintext><br><br>Please Enter your FTP Details<br><br>&nbsp;</td>
</tr>


<tr>
<td align=center colspan=3 class=maintext><font color=red><?php echo $message;?></font></td>
</tr>

<tr>
<td align=right class=maintext>File<font color=red><sup>*</sup></font></td>
<td></td>
<td align=left><input class=textbox type=textbox name="vuser_file" id="vuser_file"  readonly value="<?php echo $vuser_file;?>" maxlength="100"></td>
</tr>

<tr>
<td align=right class=maintext>Site Name<font color=red><sup>*</sup></td>
<td></td>
<td align=left>
<input type=textbox  class=textbox name="vuser_site" id="vuser_site"  readonly value="<?php echo $vuser_site;?>" maxlength="100">
</td>
</tr>

<tr>
<td align=right class=maintext>Domain Name<font color=red><sup>*</sup></font></td>
<td></td>
<td align=left><input type=text  class=textbox readonly name="vuser_domain" id="vuser_domain" maxlength="100"  value="<?php echo $vuser_location;?>"></td>
</tr>

<tr>
<td align=right class=maintext>Remote Directory<font color=red><sup>*</sup></font></td>
<td></td>
<td align=left><input type=text   class=textbox readonly name="vremote_dir" id="vremote_dir" maxlength="100"  value="<?php echo $vremote_dir;?>"></td>
</tr>

<tr>
<td align=right class=maintext>User Name<font color=red><sup>*</sup></td>
<td></td>
<td align=left><input type=text class=textbox  name="vuser_name" id="vuser_name" maxlength="20"  value="<?php echo htmlentities($ftp_user_name)?>"></td>
</tr>

<tr>
<td align=right class=maintext>Password<font color=red><sup>*</sup></td>
<td></td>
<td align=left><input type=password  class=textbox name="vuser_password" id="vuser_password" maxlength="50" value="<?php echo htmlentities($ftp_user_pass)?>"></td>
</tr>


<tr>
<td  colspan=3 align=center><br><input class=button type="button" onClick="validate();" value="Delete">&nbsp;&nbsp;<input type=button  class=button value="Back"   onClick="goback();"><br>&nbsp;</td>
</tr>

</table>

</fieldset>

</form>
</td>
</tr></table>

<?php
include "includes/userfooter.php";
?>