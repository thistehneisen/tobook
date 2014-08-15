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

//sent back
if($_GET["goback"]=="true"){
   header("Location:templatelisting.php");
   exit;
}

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

if(isset($_GET["tempid"]) and ($_GET["tempid"]!= "")){
        $tempid =$_GET["tempid"];
}else if(isset($_POST["tempid"]) and ($_POST["tempid"]!= "")){
        $tempid =$_POST["tempid"];
}


  if(isset($_POST["btnSaveChanges"])){
  	 
	 	$txtCategory = $_POST["cmbCategory"];
		$txtTempName = $_POST["txttemplatename"];
		$txtTempId = $_POST["tempid"];
		if($txtCategory != '' && $txtTempName != '' && $txtTempId != '') {
			$sql   = "UPDATE tbl_template_mast SET ncat_id='".addslashes($txtCategory)."' ,temp_name = '".addslashes($txtTempName)."'";
			$sql   .= " WHERE ntemplate_mast = '".addslashes($txtTempId)."' ";
			mysql_query($sql);
			$message = MSG_TEMPL_UPDATE_SUCC;
            $messageClass = "successmessage";
		}
		
	 

}

$sql = "SELECT ncat_id,temp_name FROM tbl_template_mast WHERE ntemplate_mast = '".addslashes($tempid)."' ";
$res = mysql_query($sql);
if(mysql_num_rows($res) > 0 ){
        $row = mysql_fetch_array($res);
        $catId = $row['ncat_id'];
        $tempName = $row['temp_name'];
}

?>
<script>
function checkInput(){
        var frm = document.frmCategories;
        if(frm.txttemplatename.value.length == 0){
                alert("<?php echo VALMSG_TEMPL_EMP;?>!");
                return false;
         }else{
                return true;
        }
}
function goback(){
     document.frmCategories.action="<?php echo $_SERVER['HTTP_REFERER']?>";
     document.frmCategories.submit();
}
</script>

<?php
$linkArray = array( TEMP_MANAGER     =>'admin/templatemanager.php',
                    MANAGE_TEMP  =>'admin/templatelisting.php',
                    EDIT_TEMPL  =>'admin/edittemplate.php?tempid=='.$tempid);
?>
<div class="admin-pnl">
    <?php echo getBreadCrumb($linkArray); ?>
    <h2><?php echo EDIT_TEMPL;?></h2>
<div class="content-tab-pnl">

<form name="frmCategories" enctype="multipart/form-data" method="POST" action="<?php echo $_SERVER["PHP_SELF"];?>" onsubmit="return checkInput();">
<input type="hidden" name="catid" value="<?php echo $catid; ?>">
<input type="hidden" name="postback">

<div class="form-pnl">
    <p><?php echo SIGNUP_CAPTION;?>.<br></p>
    <?php if($message){ ?>
    <div class="<?php echo $messageClass;?>"><?php  echo $message;?></div>
    <?php } ?>
    <ul>
        <li>
            <label><?php echo CAT_NAME;?> <sup>*</sup></label>
            
<select name="cmbCategory" class="selectbox2" >
<?php

	$sql = "Select ncat_id,vcat_name from tbl_template_category";
	$result = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_array($result)) {
			echo("<option value=\"". $row["ncat_id"] . "\"" . (($catId == $row["ncat_id"])?"Selected":"") . ">" . $row["vcat_name"] . "</option>");
		}
	}
?>
</select> 
        </li>
      
        <li>
            <label><?php echo TEMP_NAME;?> <sup>*</sup></label>
              <input type="text" name="txttemplatename" id="txttemplatename" value="<?php echo $tempName;?>" />
        </li>
        <input type="hidden" name="tempid" id="tempid" value="<?php echo $tempid; ?>" />
        <li>
            <label>&nbsp;</label>
            <span class="btn-container">
           
                <input  class=btn02 type=button value="<?php echo BTN_BACK;?>"  onClick="goback();"  >
                <input type="submit" name="btnSaveChanges" value="<?php echo BTN_UPDATE;?>" class="btn01">
                 
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