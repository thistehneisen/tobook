<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                    |
// |                                                                                                            |
// +----------------------------------------------------------------------+

$curTab = 'template_manager';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

if($_POST["postback"] == "back") {
    header("location:".$_SESSION["tempcat_backurl"]);
    exit;
}


if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

if(isset($_GET["catid"]) and ($_GET["catid"]!= "")) {
    $catid =$_GET["catid"];
}else if(isset($_POST["catid"]) and ($_POST["catid"]!= "")) {
    $catid =$_POST["catid"];
}
$txtCategoryName = $_POST["txtCategoryName"];
$txtCategoryDescription = $_POST["txtCategoryDescription"];

if(isset($_POST["btnAddNewCategory"])) {
    $message = "";
    if($_FILES['thumpnailimage']['size'] <=0 ) {
        $message = MSG_UPLOAD_THUMNAIL_IMG;
        $messageClass = "errormessage";
    }else {
        list($originalwidth, $originalheight, $type, $attr) = getimagesize( $_FILES['thumpnailimage']['tmp_name'] );
        if($width>100)
            $width=100;
        if($height>100)
            $height=100;
        if($type=="1" or $type=="2" or $type=="3") {
            if($type=="1")
                $fileextension="gif";
            else if($type=="2")
                $fileextension="jpg";
            else if($type=="3")
                $fileextension="png";
            //$assignedname=stripslashes($txtCategoryName)."_".$catid.".".$fileextension;
            //move_uploaded_file($_FILES['thumpnailimage']['tmp_name'],"../categoryicons/".$assignedname);
            //chmod("../categoryicons/".$assignedname,0777);
        }else {
            $message=MSG_FILENOT_SUPPORTED;
            $messageClass = "errormessage";
        }
    }


    $sql = "SELECT ncat_id FROM tbl_template_category WHERE vcat_name = '".addslashes($txtCategoryName)."' ";
    $res = mysql_query($sql);
    if(mysql_num_rows($res) > 0 ) {
        $message = MSG_DUPLI_CATNAME;
        $messageClass = "errormessage";
    }
    if($message == "") {
        $sql   = "INSERT INTO tbl_template_category(vcat_name,vcat_desc)VALUES('".addslashes($txtCategoryName)."','".addslashes($txtCategoryDescription)."')";
        mysql_query($sql);
        $catid = mysql_insert_id();
        $assignedname=$catid.".".$fileextension;
        move_uploaded_file($_FILES['thumpnailimage']['tmp_name'],"../categoryicons/".$assignedname);
        chmod("../categoryicons/".$assignedname,0777);
        if($originalwidth<=80 and $originalheight<=80) {
            ;
        }else {
            $resizedimage=$file_name;
            if($originalwidth >=80) {
                $imagewidth=80;
            }else {
                $imagewidth=$originalwidth;
            }
            if($originalheight >=80) {
                $imageheight=80;
            }else {
                $imageheight=$originalheight;
            }
            ResizeImageTogivenWitdhAndHeight("../categoryicons/".$assignedname,$imageheight,$imagewidth,"../categoryicons/".$assignedname);
        }

        $sql   = "UPDATE tbl_template_category SET vcat_thumpnail='categoryicons/".$assignedname."'";
        $sql   .= " WHERE ncat_id = '".addslashes($catid)."' ";
        mysql_query($sql);

        $message = MSG_CAT_CREATED;
        $messageClass = "successmessage";
        $catid=0;
        $txtCategoryName="";
        $txtCategoryDescription="";
    }
}else if(isset($_POST["btnSaveChanges"])) {
    $message = "";
    $thumpfilename=$_FILES['thumpnailimage']['name'];
    $qryfileuploaded="";
    if($_FILES['thumpnailimage']['size'] >0) {
        list($originalwidth, $originalheight, $type, $attr) = getimagesize( $_FILES['thumpnailimage']['tmp_name'] );
        if($type=="1" or $type=="2" or $type=="3") { 
            if($type=="1")
                $fileextension="gif";
            else if($type=="2")
                $fileextension="jpg";
            else if($type=="3")
                $fileextension="png";
            $assignedname=$catid.".".$fileextension;
            move_uploaded_file($_FILES['thumpnailimage']['tmp_name'],"../categoryicons/".$assignedname);
            chmod("../categoryicons/".$assignedname,0777);
            //if($width>100 or $height>100)
            //Resizeimage("../categoryicons/".$assignedname, $width,$height, "../categoryicons/".$assignedname);
            if($originalwidth<=80 and $originalheight<=80) {
                ;
            }else {
                $resizedimage=$file_name;
                if($originalwidth >=80) {
                    $imagewidth=80;
                }else {
                    $imagewidth=$originalwidth;
                }
                if($originalheight >=80) {
                    $imageheight=80;
                }else {
                    $imageheight=$originalheight;
                }
                ResizeImageTogivenWitdhAndHeight("../categoryicons/".$assignedname,$imageheight,$imagewidth,"../categoryicons/".$assignedname);
            }
            $qryfileuploaded=", vcat_thumpnail='categoryicons/".$assignedname."' ";
        }else {
            $message=MSG_FILENOT_SUPPORTED;
            $messageClass = "errormessage";
        }
    }
    $sql = "SELECT ncat_id FROM tbl_template_category WHERE vcat_name = '".addslashes($txtCategoryName)."' and ncat_id <> '".addslashes($catid)."' ";
    $res = mysql_query($sql);
    if(mysql_num_rows($res) > 0 ) {
        $message = MSG_DUPLI_CATNAME;
        $messageClass = "errormessage";
    }
    if($message == "") {
        $sql   = "UPDATE tbl_template_category SET vcat_name='".addslashes($txtCategoryName)."' ,vcat_desc = '".addslashes($txtCategoryDescription)."'".$qryfileuploaded;
        $sql   .= " WHERE ncat_id = '".addslashes($catid)."' ";
        mysql_query($sql);
        $message = MSG_CHANGES_SAVED;
        $messageClass = "successmessage";
    }
}

$sql = "SELECT * FROM tbl_template_category WHERE ncat_id = '".addslashes($catid)."' ";
$res = mysql_query($sql);
if(mysql_num_rows($res) > 0 ) {
    $row = mysql_fetch_array($res);
    $txtCategoryName = $row["vcat_name"];
    $txtCategoryDescription = $row["vcat_desc"];
    $thumpnaail="../".$row["vcat_thumpnail"];
}

?>
<script>
    function clickback(){
        var frm = document.frmCategories;
        frm.postback.value="back";
        document.frmCategories.submit();
    }
    function checkInput(){
        var frm = document.frmCategories;
        if(frm.txtCategoryName.value.length == 0){
            alert("<?php echo VALMSG_CATNAME;?>");
            return false;
            /* }else if(frm.txtCategoryDescription.value.length == 0){
                alert("Category Description cannot be empty!");
                return false; */
        }else{
            return true;
        }
    }
</script>

<?php
$linkArray = array( TEMP_MANAGER     =>'admin/templatemanager.php',
        TEMP_CATEGORIES  =>'admin/tempcategorymanager.php',
        ADD_EDIT_TEMP_CAT  =>'admin/edittemplatecategory.php?catid='.$catid);
?>
<div class="admin-pnl">
    <?php echo getBreadCrumb($linkArray); ?>
    <h2><?php echo ADD_EDIT_TEMP_CAT;?></h2>
    <div class="content-tab-pnl">

        <form name="frmCategories" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return checkInput();">
            <input type="hidden" name="catid" value="<?php echo $catid; ?>">
            <input type="hidden" name="postback">

            <div class="form-pnl">
           <p> <?php echo PLS_FILL_DETAILS;?>.<br></p>
               
                <?php if($message) { ?>
                <div class="<?php echo $messageClass;?>"><?php  echo $message;?></div>
                    <?php } ?>
                <ul>
                    <li>
                        <label><?php echo CAT_NAME;?><sup>*</sup></label>
                        <input type="text" class="textbox" name="txtCategoryName" value="<?php echo $txtCategoryName;?>">
                    </li>
                    <li>
                        <label><?php echo DESCRIPTION;?></label>
                        <textarea class="textbox" rows="5" name="txtCategoryDescription"><?php echo $txtCategoryDescription;?></textarea>
                        <label class="help_style" style="font-style: normal;">(<?php echo DESCRIPTION_MSG;?>.)</label>
                    </li>
                    <li>
                        <label><?php echo THUMBNAIL;?> <?php echo IMAGE;?><sup>*</sup></label>
                        <input type="file" name="thumpnailimage" id="thumpnailimage">
                        <label class="help_style" style="font-style: normal;">(<?php echo UPLOAD_IMG_MSG;?>.)</label>
                    </li>
                    <?php if($thumpnaail !="") { ?>
                    <li>
                        <label>&nbsp;</label>
                        <img border=1 src="<?php echo $thumpnaail?>">
                    </li>
                        <?php } ?>
                    <li>
                        <label>&nbsp;</label>
                        <span class="btn-container">
                            <input type="button" name="btnBack" value="<?php echo BTN_BACK;?>" class="btn02"  onClick="clickback();" >
                            <?php
                            if(isset($catid) AND $catid != "" ) {?>
                            <input type="submit" name="btnSaveChanges" value="<?php echo BTN_UPDATE;?>" class="btn01">
                                <?php }else {?>
                            <input type="submit" name="btnAddNewCategory" value="<?php echo BTN_ADD;?>" class="btn01">
                                <?php } ?>
                        </span>
                    </li>
                </ul>
            </div>

        </form>
    </div>
</div>
<?php
include "includes/adminfooter.php";
?>