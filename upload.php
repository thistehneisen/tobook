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
include "includes/session.php";
include "includes/config.php";

//sent back if back button is pressed
if($_GET["goback"]=="true") {

    header("Location:filemanager.php");
    exit;
}

//handle post back of upload
$act=$_GET["act"];
$message="";

if($act=="post") {

    $uploadfile="./uploads/".$_SESSION["session_userid"]."#".$_FILES['vuser_file']['name'];
    // move uploaded file
    move_uploaded_file($_FILES['vuser_file']['tmp_name'], $uploadfile);


    // setup $host and $file variables for your setup before here...
    $host=$_POST["vuser_domain"];
    $hostip = gethostbyname($host);

    //check if ftp domain is correct
    if(@ftp_connect($hostip)) {


        $conn_id = @ftp_connect($hostip);
        $ftp_user_name =$_POST["vuser_name"];
        $ftp_user_pass =$_POST["vuser_password"];

        // login with username and password
        if($login_result=@ftp_login($conn_id, $ftp_user_name, $ftp_user_pass)) {

            // turn passive mode on
            @ftp_pasv ( $conn_id, true );

            if ((!$conn_id) || (!$login_result)) {


                $message.= "FTP connection has failed!";
                $message.= "Attempted to connect to $host for user $ftp_user_name";


            } else {

                $message.= "Connected to $host, for user $ftp_user_name<br>";
                $message.="Host IP is $hostip<br>";


                // upload a file
                $remote_location=$_POST["vremote_dir"];
                $remote_file=$remote_location."/".$_FILES['vuser_file']['name'];
                $file=$uploadfile;



                if (@ftp_put($conn_id, $remote_file, $file, FTP_BINARY)) {

                    // delete oldfile database
                    $sql="delete from tbl_files where vfile_name='".$_FILES['vuser_file']['name']."' and nsite_id='".$_POST['nsite_id']."' and vlocation='".$host."'";
                    mysql_query($sql,$con);

                    // add to database
                    $sql="insert into tbl_files(nfile_id,vfile_name,nsite_id,vlocation,vremote_dir,ddate) values  ('','".$_FILES['vuser_file']['name']."','".$_POST['nsite_id']."','".$host."','".$remote_location."',now())";
                    mysql_query($sql,$con);
                    $message.= "successfully uploaded ".$_FILES['vuser_file']['name']."<br>";


                } else {
                    $message.= "There was a problem while uploading ".$_FILES['vuser_file']['name']."<br>";
                }


                @unlink($uploadfile);
                // close the connection
                @ftp_close($conn_id);




            }

        }else {

            $message= "Cannot connect to FTP server. Invalid login info! ";

        }


    }else {

        $message= "Cannot connect to FTP server. Invalid domain provided! ";

    }


}
include "includes/userheader.php";


?>





<script>
    function goback(){

        document.uploadForm.action="upload.php?goback=true";
        document.uploadForm.submit();

    }

    function validate(){



        if(document.uploadForm.vuser_file.value==""){

            alert("File name cannot be empty");
            document.uploadForm.vuser_file.focus();
            return false;

        }else if(document.uploadForm.nsite_id.options.length=="0"){

            alert("No sites are currently created by you.");
            return false;
        }else if(document.uploadForm.vuser_domain.value==""){

            alert("Domain Name cannot be empty");
            document.uploadForm.vuser_domain.focus();
            return false;

        }else if(document.uploadForm.vremote_dir.value==""){

            alert("Remote Directory cannot be empty");
            document.uploadForm.vremote_dir.focus();
            return false;

        }else if(document.uploadForm.vuser_name.value==""){

            alert("Name cannot be empty");
            document.uploadForm.vuser_name.focus();
            return false;

        }else if(document.uploadForm.vuser_password.value==""){

            alert("Password cannot be empty");
            document.uploadForm.vuser_password.focus();
            return false;

        }else{

            document.uploadForm.submit();

        }

    }
</script>

<table><tr>
        <td align=center><img src=images/uploadfile.gif><br><br>
            <form name="uploadForm" method=post  enctype="multipart/form-data" action=upload.php?act=post>


                <fieldset>
                    <table>

                        <tr>
                            <td align=center colspan=3 class=maintext><br><br>Please fill in the following details<br><br>&nbsp;</td>
                        </tr>


                        <tr>
                            <td align=center colspan=3 class=maintext><font color=red><?php echo $message;?></font></td>
                        </tr>

                        <tr>
                            <td align=right class=maintext>File<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left><input  class=textbox type=file name="vuser_file" id="vuser_file" maxlength="100"></td>
                        </tr>


                        <tr>
                            <td align=right class=maintext>Site Name<font color=red><sup>*</sup></td>
                            <td></td>
                            <td align=left>
                                <select name="nsite_id" id="nsite_id">
                                    <?php
                                    $sql="select nsite_id,vsite_name,nuser_id from tbl_site_mast where nuser_id='".$_SESSION["session_userid"]."' and ndel_status='0'";
                                    $result=mysql_query($sql,$con);
                                    while($row=mysql_fetch_array($result)) {

                                        echo "<option value='".$row["nsite_id"]."'>".$row["vsite_name"]."</option>";

                                    }

                                    ?>
                                </select></td>
                        </tr>

                        <tr>
                            <td align=right class=maintext>Domain Name<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left><input type=text class=textbox name="vuser_domain" id="vuser_domain" maxlength="100"  value="<?php echo htmlentities($_POST["vuser_domain"])?>"></td>
                        </tr>

                        <tr>
                            <td align=right class=maintext>Remote Directory<font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left><input type=text class=textbox name="vremote_dir" id="vremote_dir" maxlength="100"  value="<?php echo htmlentities($_POST["vremote_dir"])?>"></td>
                        </tr>

                        <tr>
                            <td align=right class=maintext>User Name<font color=red><sup>*</sup></td>
                            <td></td>
                            <td align=left><input type=text  class=textbox name="vuser_name" id="vuser_name" maxlength="20"  value="<?php echo htmlentities($_POST["vuser_name"])?>"></td>
                        </tr>

                        <tr>
                            <td align=right class=maintext>Password<font color=red><sup>*</sup></td>
                            <td></td>
                            <td align=left><input  class=textbox type=password name="vuser_password" id="vuser_password" maxlength="50" value="<?php echo htmlentities($_POST["vuser_password"])?>"></td>
                        </tr>


                        <tr>
                            <td  colspan=3 align=center><input class=button  type="button" onClick="validate();" value="Upload">&nbsp;&nbsp;<input type=button  class=button value="Back"  onClick="goback();"></td>
                        </tr>

                    </table>


                </fieldset>
            </form><br><br>&nbsp;
        </td>
    </tr></table>

<?php
include "includes/userfooter.php";
?>