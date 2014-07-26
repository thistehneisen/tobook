<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com> 		       		              |
// +----------------------------------------------------------------------+
//Helps admin upload a template belonging to simple/advanced category - STEP I
//Step - I of template addition consists of 
//	(i)		selecting a category for the template
//	(ii)	Selecting the type of the template - simple/advanced
//	(iii)	Uploading a thumpnail image. 
//	Admin may visit the page in two of the following ways:
//		(A)	Visiting the page for the first time
//		(B)	Revisiting the page by coming back to step 1 from any of the higher steps
//
//		(A) Visiting the page for the first time
//			Here admin has to upload a thumpnail image(mandatory) of jpg format and the file will be renamed as 
//			thumpnail.jpg.
//			(i)		template directory will be created in the name templates/timestamp()
//			(ii)	set permissions of template directory to 777 using chmod()
//			(iii)	set $_SESSION["session_advTemplateDir"] to templates/timestamp()
//			(iv)	set $_SESSION["session_advTemplateType"] as ""
//			(v)		move the uploaded file to templatedirectory created.
//			(vi)	set permissions to 777 of the thumpnail file
//			(vii)	check if there is a file called thumpnail.jpg in the templatedirectory created
//			(viii)	IF yes then 
//						set $_SESSION["session_advCatId"] to selected category id
//					 	set $_SESSION["session_advTemplateType"] to selected template type
//						set $_SESSION["session_steps"] to 1.
//						navigate to templadv2.php
//					ELSE
//						error flag is set to true
//						error message is displayed
//					END IF
//		(B)	Revisiting the page by coming back to step 1 from any of the higher steps
//			Here there will be a thumpnail.jpg image present in the templatedirectory folder.
// 		 	(i)		Get the reference of the $_SESSION["session_advTemplateDir"] previously set
//			(ii)	If admin tries to upload a newimage for thumpnail check the format for jpg.
//					If no then show error message.
//					If yes then move the file as thumpnail.jpg to templatedirectory.
//			(iii)	check if there is a file called thumpnail.jpg in the templatedirectory created
//			(iv)	IF yes then 
//						set $_SESSION["session_advCatId"] to selected category id
//					 	set $_SESSION["session_advTemplateType"] to selected template type
//						navigate to templadv2.php
//					ELSE
//						error flag is set to true
//						error message is displayed
//					END IF			
//		(C)	Clear Old Data
//				If admin visits	this page from a higher step, and we have data present which admin wants 
//				to clear he can follow the 'Clear Old Data' button.  It sets the session variables 
//				 session_advCatId, session_advTemplateType, session_steps,  session_advTemplateDir to "". 	 
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";

$error = false;
$message = "";
if($_POST["postback"] == "clearold") {
	$_SESSION["session_advCatId"] = "";
	$_SESSION["session_advTemplateType"] = "";
	if($_SESSION["session_advTemplateDir"] != "") {
		remove_dir($_SESSION["session_advTemplateDir"]);
		$_SESSION["session_advTemplateDir"]="";
	}
	$_SESSION["session_steps"] = "";
}
elseif($_POST["postback"] == "step1") {
	$catid = $_POST["cmbCategory"];
	$templateType=$_POST["cmbTemplateType"];
	$thumpImage = $_FILES['fileThumpImage'];
	
	if(!isset($_SESSION["session_advCatId"]) || $_SESSION["session_advCatId"] == "") {
		//$templateDir = "../templates";
		$templateDir = "../" . $_SESSION["session_template_dir"];
	//	if (!is_readable($templateDir) || !is_writable($templateDir) || !is_executable($templateDir)) {
		if (!is_readable($templateDir) || !is_writable($templateDir) ) {
			$error = true;
			$message .= " * To add a template give full(777) permission for the 'templates' folder.<br>";
	    }
		else {
			$templateDir .= "/" . time();
			if(!is_dir($templateDir)) {
				@mkdir($templateDir,0777);
				@chmod($templateDir,0777);
				$_SESSION["session_advTemplateDir"] = $templateDir;
				$_SESSION["session_advTemplateType"] = "";
			}	
		}
	}
	else {
		$templateDir = $_SESSION["session_advTemplateDir"];
	}
	if($error == false) {
		if(is_uploaded_file($thumpImage['tmp_name'])) {

			if($thumpImage['type'] != "image/pjpeg" && $thumpImage['type'] != "image/pjpg" && $thumpImage['type'] != "image/jpg" && $thumpImage['type'] != "image/jpeg") {
				$error = true;
				$message .= " * Only JPG images are permitted for thumbnail.  Please provide an image of the format JPG.<br>";
			}
			else {
				$thumpImageDest = $templateDir . "/thumpnail.jpg";
				@move_uploaded_file($thumpImage['tmp_name'], $thumpImageDest);
				@chmod($thumpImageDest,0777);
			}
		}
		if(is_file($templateDir . "/thumpnail.jpg")) {
			$_SESSION["session_advCatId"] = $catid;
			$_SESSION["session_advTemplateType"] = $templateType;
		}
		else {
			$error = true;
			$message .= "Thumbnail image not copied.";
		}
	}
	if($error == false){
		if($_SESSION["session_steps"] == "") {
			$_SESSION["session_steps"] = 1;		
		}
		header("location:templadv2.php");
		exit();
	}
}
//include files
include "./includes/adminheader.php";
?>
<script>
<!--
	var id=0;
	<?php
		if($_SESSION["session_steps"] >= 1) {
			echo("id=1;");
		}
	?>
	function clickClear() {
		var frmStep1 = document.frmStep1;
		frmStep1.postback.value="clearold";
		frmStep1.action="templadv1.php";
		frmStep1.method="post";				
		frmStep1.submit();
	}
	function clickNext() {
		var frmStep1 = document.frmStep1;
		var fileImage = frmStep1.fileThumpImage;
		if(fileImage.value.length <= 0 && id == 0) {
			alert("Please select a file for thumbnail image.");
			return;
		} 
		frmStep1.postback.value="step1";
		frmStep1.action="templadv1.php";
		frmStep1.method="post";				
		frmStep1.submit();
	}
-->	
</script>
<!--table width="70%"  border="0">
	  <tr>
                	<td align="center"><img src="../images/createtemplate.gif" ><br>&nbsp;</td>
            	</tr>
</table-->
<?php
/*
$linkArray = array( 'Home'=>'index.php',
                    'Features'  =>'features.php');
echo getBreadCrumb($linkArray);
 */
?>
<div class="admin-pnl"> 
	<div class="bc01">
            <ul>
                <li><a href="#">Home</a> &#187; </li>
                <li class="active">&nbsp;Features</li>
            </ul>
            <div class="clear"></div>
	</div>
	<div class="clear"></div>
	<h2>Create Template</h2>
	<div class="steps">
		<ul>
			<li class="step1 active">Step 1</li>
			<li class="step2">Step 2</li>
			<li class="step3">Step 3</li>
			<li class="step4">Step 4</li>
			<li class="step5">Step 5</li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<table width="100%"  border="0">
		<tr>
		  <td width="6%">&nbsp;</td>
		  <td width="17%" class="maintext"><b><a href="templadv1.php"  style="text-decoration:none; "><img src="../images/step1.gif" height="20" width="56" border="0"></a></b></td>
		  <td width="16%" class="maintext"><a href="templadv2.php"><img src="../images/lite_step2.gif" height="20" width="56" border="0"></a></td>
		  <td width="17%" class="maintext"><a href="templadv3.php"><img src="../images/lite_step3.gif" height="20" width="56" border="0"></a></td>
		  <td width="19%" class="maintext"><a href="templadv4.php"><img src="../images/lite_step4.gif" height="20" width="56" border="0"></a></td>
		  <td width="25%" class="maintext"><a href="templadv5.php"><img src="../images/lite_step5.gif" height="20" width="56" border="0"></a></td>
		</tr>
	</table>
	<table width="100%" class="template-design-pnl">
		<tr>
			<td width="100%">
				<form name="frmStep1" method="post" action="" enctype="multipart/form-data">
					
					<table width="100%" border="0">
						<tr>
							<td colspan="3">&nbsp;<font color=red><?php echo $message;?></font></td>
						</tr>
						<tr>
							<td><label>Select a category for your template <font color=red><sup>*</sup></font></label></td>
							<td align=left>
								<input type="hidden" name="postback" id="postback" value="">
								<select name="cmbCategory">
								<?php
								
									$sql = "Select ncat_id,vcat_name from tbl_template_category";
									$result = mysql_query($sql) or die(mysql_error());
									if(mysql_num_rows($result) > 0) {
										while($row = mysql_fetch_array($result)) {
											echo("<option value=\"". $row["ncat_id"] . "\"" . (($_SESSION["session_advCatId"] == $row["ncat_id"])?"Selected":"") . ">" . htmlentities($row["vcat_name"]) . "</option>");
										}
									}
								?>
								</select> 
							</td>
						</tr>
						<tr>
							<td><label>Type of template <font color=red><sup>*</sup></font></label></td>
							<td align=left>
								<select name="cmbTemplateType">
									<option value="simple" <?php echo(($_SESSION["session_advTemplateType"] == "simple")?"Selected":""); ?>>Simple</option>
									<option value="advanced" <?php echo(($_SESSION["session_advTemplateType"] == "advanced")?"Selected":""); ?>>Advanced</option>
								</select>
							</td>
						</tr>
						<tr>
							<td><label>Thumbnail Image of your template (215 X 145)<font color=red><sup>*</sup></font><span class="help-ico"><img src="../themes/Coastal-Green/help-ico.png"></span></label></td>
							<td align=left><input type="file" name="fileThumpImage" id="fileThumpImage" onKeyPress=""  class="file-browse"  size="23"></td>
						</tr>
						<tr>
							<td  colspan=3 align=center>
							  <?php
								if($_SESSION["session_advTemplateDir"] != "") {
									echo("<img id='imgThumpnail' src=\"../images/spacer.jpg\">");
									$script_string = "<script>document.all(\"imgThumpnail\").src=\"" . $_SESSION["session_advTemplateDir"] . "/thumpnail.jpg\";</script>";
									$button_string = "<input type=\"button\" onClick=\"javascript:clickClear();\" value=\"Clear old data\"  class=\"editorbutton\" >";
								}
							  ?>
							</td>
						</tr>
						<tr>
							<td>&nbsp;</td>
							<td align="right">
							<input type="button" onClick="javascript:clickNext();" value="Continue"  class="btn01" > &nbsp;<?php echo($button_string); ?>
							</td>
						</tr>
					</table>
					
				</form>
			</td>
		</tr>
	</table>
<div class="clear"></div>
</div>
<?php
	echo($script_string); 
?>
<?php

include "includes/adminfooter.php";
?>