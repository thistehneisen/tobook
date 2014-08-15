<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com> 	        		              |
// +----------------------------------------------------------------------+
//STEP - III of template insertion
//	Admin user uploads the 6 images that are mandatory for templates page sub.htm
//(i)	check the $_SESSION["session_steps"], if the value is not >= 2 then we take the user to 
//		templadv1.php or templadv2.php based on the value of $_SESSION["session_steps"].
//(ii)	$_SESSION["session_advTemplateType"] is checked, if that is "advanced" then the display_format 
//		is set to "jpg/gif", and ext is set as .jpg/.gif else (simple) we set it to "jpg", ext to ".gif".
//(iii)	we check for file name validity, ie. the uploaded files should be of the name 
//			1.tp_innerlogoimage.ext
//			2.tp_innerlogoimageband.ext
//			3.tp_innercompany.ext
//			4.tp_innercompanyband.ext
//			5.tp_innercaption.ext
//			6.tp_innercaptionband.ext
//			If no then admin user is given the error message asking him to upload images of valid name/format
//			If yes then copy the images to templatedirectory/images and  templatedirectory/watermarkimages
//(iv)	$_SESSION["session_steps"] is set to 3(if $_SESSION["session_steps"] <= 2), and User is taken to Step-IV if he has not checked to use the same images for sub.htm, 
//include files
include "../includes/session.php";
include "../includes/config.php";
$error = false;
$message = "";


//If the user directly comes to page3 without visiting page1 then he is sent to page1,
//if he comes to page3 without visiting page2 he is sent to page2 
if(!isset($_SESSION["session_steps"]) || $_SESSION["session_steps"] == "" || $_SESSION["session_steps"] < 1) {
		header("location:templadv1.php");
		exit();
}
elseif($_SESSION["session_steps"] < 2) {
	header("location:templadv2.php");
	exit();
}

$templateType = "";
if($_SESSION["session_advTemplateType"] == "advanced") {
	$display_format = ".jpg/gif";
	$templateType = "advanced";
}
else {
	$display_format = ".jpg";
	$templateType = "simple";
}
//functions
function getExtension($filename)
{
	$ext  = @strtolower(@substr($filename, (@strrpos($filename, ".") ? @strrpos($filename, ".") + 1 : @strlen($filename)), @strlen($filename)));
	return ($ext == 'jpg') ? 'jpeg' : $ext;
}
function getFileName($filename) 
{
	$fn  = @strtolower(@substr($filename, 0, (@strrpos($filename, ".") ? @strrpos($filename, ".") : @strlen($filename))));
	return ($fn);
}
function isValidFiles(&$message,$templateType) {
	$return = true;
	if($templateType == "advanced") {
		$valid = array("jpeg","gif");
		$list = "jpeg,gif";
	}
	else {
		$valid = array("jpeg");
		$list = "jpeg";
	}
	if(!is_uploaded_file($_FILES["LogoImage"]["tmp_name"])) {
		$message .= "Please upload a valid logo image.<br>";
		$return = false;
	}
	/*
	if(getFileName($_FILES["LogoImage"]["name"]) != "tp_innerlogoimage") {
		$message .= "Name of the logo image should be tp_innerlogoimage.<br>";
		$return = false;
	}
	*/
	if(!in_array(getExtension($_FILES["LogoImage"]["name"]),$valid)) {
		$message .= "Logo image should be of any of the following formats (" . $list . ")<br>" ;
		$return = false;
	}
	if(!is_uploaded_file($_FILES["LogoBand"]["tmp_name"])) {
		$message .= "Please upload a valid logo band.<br>";
		$return = false;
	}
	/*
	if(getFileName($_FILES["LogoBand"]["name"]) != "tp_innerlogoband") {
		$message .= "Name of logo band should be tp_innerlogoband<br>";
		$return = false;
	}*/
	if(!in_array(getExtension($_FILES["LogoBand"]["name"]),$valid)) {
		$message .= "Logo Band should be of any of the following formats (" . $list . ")<br>" ;
		$return = false;
	}
	if(!is_uploaded_file($_FILES["Company"]["tmp_name"])) {
		$message .= "Please upload a valid company image.<br>";
		$return = false;
	}
	/*
	if(getFileName($_FILES["Company"]["name"]) != "tp_innercompany") {
		$message .= "Name of company should be tp_innercompany<br>";
		$return = false;
	}*/
	if(!in_array(getExtension($_FILES["Company"]["name"]),$valid)) {
		$message .= "Company should be of any of the following formats (" . $list . ")<br>" ;
		$return = false;
	}
	if(!is_uploaded_file($_FILES["CompanyBand"]["tmp_name"])) {
		$message .= "Please upload a valid company band.<br>";
		$return = false;
	}
	/*
	if(getFileName($_FILES["CompanyBand"]["name"]) != "tp_innercompanyband") {
		$message .= "Name of company band should be tp_innercompanyband<br>";
		$return = false;
	}*/
	if(!in_array(getExtension($_FILES["CompanyBand"]["name"]),$valid)) {
		$message .= "Company band should be of any of the following formats (" . $list . ")<br>" ;
		$return = false;
	}
	if(!is_uploaded_file($_FILES["Caption"]["tmp_name"])) {
		$message .= "Please upload a valid caption.<br>";
		$return = false;
	}
	/*
	if(getFileName($_FILES["Caption"]["name"]) != "tp_innercaption") {
		$message .= "Name of caption  should be tp_innercaption<br>";
		$return = false;
	}*/
	if(!in_array(getExtension($_FILES["Caption"]["name"]),$valid)) {
		$message .= "Caption should be of any of the following formats (" . $list . ")<br>" ;
		$return = false;
	}
	if(!is_uploaded_file($_FILES["CaptionBand"]["tmp_name"])) {
		$message .= "Please upload a valid caption band.<br>";
		$return = false;
	}
	/*
	if(getFileName($_FILES["CaptionBand"]["name"]) != "tp_innercaptionband") {
		$message .= "Name of caption band should be tp_innercaptionband<br>";
		$return = false;
	}*/
	if(!in_array(getExtension($_FILES["CaptionBand"]["name"]),$valid)) {
		$message .= "Caption band should be of any of the following formats (" . $list . ")<br>" ;
		$return = false;
	}
	return $return;
}


if($_POST["postback"] == "step2") {
	
	 
	
	
	
	$message="";
	//$templdir = "../".$_SESSION["session_template_dir"]."/";
	$templdir = "../" . $_SESSION["session_template_dir"] . "/";
	if(isValidFiles($message,$templateType)) {
		$imagesdir = $_SESSION["session_advTemplateDir"] . "/images";
		$watermarkdir = $_SESSION["session_advTemplateDir"]	 . "/watermarkimages";	

		
		// function to upload the images
		subpageImageUploader($imagesdir,$watermarkdir);
		
		/*
		@move_uploaded_file($_FILES["LogoImage"]["tmp_name"],$imagesdir . "/" . $_FILES["LogoImage"]["name"]);		
		@chmod($imagesdir . "/" . $_FILES["LogoImage"]["name"],0777);
		@copy($imagesdir . "/" . $_FILES["LogoImage"]["name"],$watermarkdir . "/" . $_FILES["LogoImage"]["name"]);		
		@chmod($watermarkdir . "/" . $_FILES["LogoImage"]["name"],0777);
		
		@move_uploaded_file($_FILES["LogoBand"]["tmp_name"],$imagesdir . "/" . $_FILES["LogoBand"]["name"]);
		@chmod($imagesdir . "/" . $_FILES["LogoBand"]["name"],0777);
		@copy($imagesdir . "/" . $_FILES["LogoBand"]["name"],$watermarkdir . "/" . $_FILES["LogoBand"]["name"]);
		@chmod($watermarkdir . "/" . $_FILES["LogoBand"]["name"],0777);

		@move_uploaded_file($_FILES["Company"]["tmp_name"],$imagesdir . "/" . $_FILES["Company"]["name"]);
		@chmod($imagesdir . "/" . $_FILES["Company"]["name"],0777);
		@copy($imagesdir . "/" . $_FILES["Company"]["name"],$watermarkdir . "/" . $_FILES["Company"]["name"]);		
		@chmod($watermarkdir . "/" . $_FILES["Company"]["name"],0777);

		@move_uploaded_file($_FILES["CompanyBand"]["tmp_name"],$imagesdir . "/" . $_FILES["CompanyBand"]["name"]);		
		@chmod($imagesdir . "/" . $_FILES["CompanyBand"]["name"],0777);
		@copy($imagesdir . "/" . $_FILES["CompanyBand"]["name"],$watermarkdir . "/" . $_FILES["CompanyBand"]["name"]);		
		@chmod($watermarkdir . "/" . $_FILES["CompanyBand"]["name"],0777);

		@move_uploaded_file($_FILES["Caption"]["tmp_name"],$imagesdir . "/" . $_FILES["Caption"]["name"]);		
		@chmod($imagesdir . "/" . $_FILES["Caption"]["name"],0777);
		@copy($imagesdir . "/" . $_FILES["Caption"]["name"],$watermarkdir . "/" . $_FILES["Caption"]["name"]);		
		@chmod($watermarkdir . "/" . $_FILES["Caption"]["name"],0777);

		@move_uploaded_file($_FILES["CaptionBand"]["tmp_name"],$imagesdir . "/" . $_FILES["CaptionBand"]["name"]);		
		@chmod($imagesdir . "/" . $_FILES["CaptionBand"]["name"],0777);
		@copy($imagesdir . "/" . $_FILES["CaptionBand"]["name"],$watermarkdir . "/" . $_FILES["CaptionBand"]["name"]);		
		@chmod($watermarkdir . "/" . $_FILES["CaptionBand"]["name"],0777);
*/
		if($_SESSION["session_steps"] <= 2) {
			$_SESSION["session_steps"] = 3;
		}
		header("location:templadv4.php");
		exit();
	}
}
//include files
include "./includes/adminheader.php";
?>
<script>
<!--
	function clickNext() {
		var frmStep2 = document.frmStep2;
		frmStep2.postback.value="step2";
		frmStep2.action="templadv3.php";
		frmStep2.method="post";				
		frmStep2.submit();
	}
-->	
</script>
<!--table width="70%"  border="0">
	<tr>
	<td align="center"><img src="../images/createtemplate.gif" ><br>&nbsp;</td>
	</tr>
</table-->
<div class="admin-pnl">
	<div class="bc01">
		<ul>
			<li><a href="templatemanager.php">Create Template</a> &#187; </li>
			<li class="active">&nbsp;New Template</li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>
	<h2>Create Template</h2>
	<div class="steps">
		<ul>
			<li class="step1">Step 1</li>
			<li class="step2">Step 2</li>
			<li class="step3 active">Step 3</li>
			<li class="step4">Step 4</li>
			<li class="step5">Step 5</li>
		</ul>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>

<table width="100%"  border="0">
    <tr>
      <td width="6%">&nbsp;</td>
      <td width="17%" class="maintext"><a href="templadv1.php"><img src="../images/lite_step1.gif" height="20" width="56" border="0"></a></td>
      <td width="16%" class="maintext"><a href="templadv2.php"><img src="../images/lite_step2.gif" height="20" width="56" border="0"></a></td>
      <td width="17%" class="maintext"><b><a href="templdv3.php"><img src="../images/step3.gif" height="20" width="56" border="0"></a></b></td>
      <td width="19%" class="maintext"><a href="templadv4.php"><img src="../images/lite_step4.gif" height="20" width="56" border="0"></a></td>
      <td width="25%" class="maintext"><a href="templadv5.php"><img src="../images/lite_step5.gif" height="20" width="56" border="0"></a></td>
    </tr>
  </table>
<table width="100%" class="template-design-pnl"><tr>
<td align=center>
<form name="frmStep2" method="post" action="" enctype="multipart/form-data">

<table width=100% border=0>

<tr>
<td width="100%" colspan=2 align=center class=maintext>&nbsp;</td>
</tr>


<tr>
  <td align=center colspan=2 class=maintext>&nbsp;
  
  
  </td>
</tr>
<tr>
  <td align=center colspan=2 class=maintext><font color=red><?php  echo $message;?></font></td>
</tr>
<tr>
<td align=left colspan=2 class=maintext>
<h4>Sub Page</h4>
<table width="100%"  border="0">
  <tr>
    <td width="46%"><label>Logo image with name</label>
  <input type="hidden" name="postback" value=""></td><td width="20%"><img src="../images/logo_image.gif" alt="Logo image" border="1"></td>
    <td width="34%"><input type="file" name="LogoImage" id="LogoImage" onKeyPress=""  class="file-browse01" ></td>
    </tr>
  <tr>
    <td><label>Logo band alone</label> </td>
    <td><img src="../images/logo_band.gif" alt="Logo band" border="1"></td>
    <td><input type="file" name="LogoBand" id="LogoBand" onKeyPress=""  class="file-browse01"></td>
    </tr>
  <tr>
    <td><label>Company with name</label> </td>
    <td><img src="../images/company_image.gif" alt="Company" border="1"></td>
    <td><input type="file" name="Company" id="Company" onKeyPress=""  class="file-browse01" ></td>
    </tr>
  <tr>
    <td><label>Company band alone</label> </td>
    <td><img src="../images/company_band.gif" alt="CompanyBand" border="1"></td>
    <td><input type="file" name="CompanyBand" id="CompanyBand" onKeyPress="" class="file-browse01" ></td>
    </tr>
  <tr>
    <td><label>Caption image with caption</label> </td>
    <td><img src="../images/caption_image.gif" alt="Caption" border="1"></td>
    <td><input type="file" name="Caption" id="Caption" onKeyPress="" class="file-browse01" ></td>
    </tr>
  <tr>
    <td><label>Caption band alone</label> </td>
    <td><img src="../images/caption_band.gif" alt="CaptionBand" border="1"></td>
    <td><input type="file" name="CaptionBand" id="CaptionBand" onKeyPress="" class="file-browse01"></td>
    </tr>
</table>

</td>
</tr>


<tr>
<td>&nbsp;</td>
<td align="right"><input type="button" onClick="javascript:clickNext();" value="Continue"  class="btn01" ></td>
</tr>

</table>
</form>
</td>
</tr></table>
<div class="clear"></div>
</div>
<?php

include "includes/adminfooter.php";
?>