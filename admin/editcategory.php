<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

if($_POST["postback"] == "back"){
   header("location:".$_SESSION["gbackurl"]);
   exit;
}



if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE); 
} 

if(isset($_GET["catid"]) and ($_GET["catid"]!= "")){
	$catid =$_GET["catid"]; 
}else if(isset($_POST["catid"]) and ($_POST["catid"]!= "")){
	$catid =$_POST["catid"]; 
}
$txtCategoryName = $_POST["txtCategoryName"];
$txtCategoryDescription = $_POST["txtCategoryDescription"];

if($_POST["btnAddNewCategory"] == "Add New Category"){//insert category
	$message = "";
	$sql = "SELECT ngcat_id FROM tbl_gallery_category WHERE vcat_name = '".addslashes($txtCategoryName)."' ";
	$res = mysql_query($sql);//check for duplicate category name
	if(mysql_num_rows($res) > 0 ){
		$message = "Duplicate Category Name! Please enter a unique Category Name!";
	}
	if($message == ""){
		$sql   = "INSERT INTO tbl_gallery_category(vcat_name,vcat_desc)VALUES('".addslashes($txtCategoryName)."','".addslashes($txtCategoryDescription)."')";
		mysql_query($sql);//insert the new category
		$catid = mysql_insert_id();
		$message = "Category created successfully!";
		header("Location:categorymanager.php?msg=cs");
		exit;
	}
}else if($_POST["btnSaveChanges"] == "Save Changes"){//update category
	$message = "";
	
	$sql = "SELECT ngcat_id FROM tbl_gallery_category WHERE vcat_name = '".addslashes($txtCategoryName)."' and ngcat_id <> '".addslashes($catid)."' ";
	$res = mysql_query($sql);//check for duplicate category name
	if(mysql_num_rows($res) > 0 ){
		$message = "Duplicate Category Name! Please enter a unique Category Name!";
	}
	if($message == ""){
		$sql   = "UPDATE tbl_gallery_category SET vcat_name='".addslashes($txtCategoryName)."' ,vcat_desc = '".addslashes($txtCategoryDescription)."' WHERE ngcat_id = '".addslashes($catid)."' ";
		mysql_query($sql);//update the category
		$message = "Changes saved successfully!";
	}
}

$sql = "SELECT * FROM tbl_gallery_category WHERE ngcat_id = '".addslashes($catid)."' ";
$res = mysql_query($sql);
if(mysql_num_rows($res) > 0 ){
	$row = mysql_fetch_array($res);
	$txtCategoryName = $row["vcat_name"];
	$txtCategoryDescription = $row["vcat_desc"];
}
include "includes/adminheader.php";
?>
<script>
function checkInput(){
	var frm = document.frmCategories;
	if(frm.txtCategoryName.value.length == 0){
		alert("Category Name cannot be empty!");
		return false;
	}else if(frm.txtCategoryDescription.value.length == 0){
		alert("Category Description cannot be empty!");
		return false;
	}else{
		return true;
	}
}
function clickback(){
var frm = document.frmCategories;
  frm.postback.value="back";
  document.frmCategories.submit();
}
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                	<td align="center"><img src="../images/addeditsystemgallry.gif" ><br>&nbsp;</td>
            	</tr>
</table>
<form name="frmCategories" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return checkInput();">
<input type="hidden" name="catid" value="<?php echo htmlentities($catid); ?>">
<input type="hidden" name="postback" value="">
<fieldset style="width:450px;">
<table width="80%" align="center">
	<tr><td colspan="3" align="center" class="redtext"><?php echo $message;?><br>&nbsp;</td></tr>
	<tr><td class=maintext width="30%" align="left">Category Name<font color=red><sup>*</sup></font></td><td width="2%">&nbsp;</td><td align="left"><input type="text" class="textbox" name="txtCategoryName" value="<?php echo htmlentities($txtCategoryName);?>"> </td></tr>
	<tr><td class=maintext valign="top"  align="left">Description<font color=red><sup>*</sup></font></td><td >&nbsp;</td><td  align="left"><textarea class="textbox" rows="4" cols="40" name="txtCategoryDescription"><?php echo htmlentities($txtCategoryDescription);?></textarea></td></tr>
	<tr><td class=maintext colspan="3" align="center">
		<?php
		if(isset($catid) AND $catid != "" ){?>
		<input type="submit" name="btnSaveChanges" value="Save Changes" class="button"  >
		<?php }else{?>
		<input type="submit" name="btnAddNewCategory" value="Add New Category" class="button"  >
		<?php }
		?>
		&nbsp;<input type="button" name="btnBack" value="Back to Categories" class="button"  onClick="clickback();" >
		</td>
	</tr>
	<tr><td class=maintext colspan="3">&nbsp;</td></tr>
</table>
</fieldset>
</form>
<?php

include "includes/adminfooter.php";
?>