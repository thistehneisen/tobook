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
//Step - V [admin uploads index.htm, sub.htm, style.css, home page image, subpage image]
//rules we enforce are
//name of index page must be index.htm
//name of sub page must be sub.htm
//name of style sheet must be style.css
//name of home page image must be homepageimage.jpg
//name of subpage image must be subpageimage.jpg
//For simple templates additional rules are :
//	references to logo,caption,company etc. must be present in index.htm
//	references to innerlogo,innercompany,innercaption must be there in sub.htm
//	link area, editable area must be present in index/sub. (htmltotpl.php defines those checking)
//copy the uploaded files to templateDirectory
//IF templateType == "advanced" then
//	insert into tbl_template_mast 
//	get the id of the inserted record into newid
//	rename present tempateDirectory to 	newid
//ELSE
//	insert into tbl_template_mast
//	create tpl files using htmltotpl files' function
//	get the id of the inserted record into newid
//  rename present tempateDirectory to 	newid
//END IF
//clear session for template addition by calling clearadvsession() and navigate to templadv6.php
//include files
include "../includes/session.php";
include "../includes/config.php";
include "htmltotpl.php";
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
elseif($_SESSION["session_steps"] < 3) {
	header("location:templadv3.php");
	exit();
}
elseif($_SESSION["session_steps"] < 4) {
	header("location:templadv4.php");
	exit();
}


function clearadvsession() {
	$_SESSION["session_advCatId"] = "";
	$_SESSION["session_advTemplateDir"] = "";
	$_SESSION["session_advTemplateType"] = "";
	$_SESSION["session_steps"]="";
}

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
function isValidFiles(&$message) {
	$return = true;
	$valid = array("htm");
	$list = "htm";
	if(!is_uploaded_file($_FILES["homepage"]["tmp_name"])) {
		$message .= "Please upload a valid home page.<br>";
		$return = false;
	}
	if(getFileName($_FILES["homepage"]["name"]) != "index") {
		$message .= "Name of the home page should be index.htm.<br>";
		$return = false;
	}
	if(!in_array(getExtension($_FILES["homepage"]["name"]),$valid)) {
		$message .= "Home page should be of the format " . $list . "<br>";
		$return = false;
	}
	if(!is_uploaded_file($_FILES["subpage"]["tmp_name"])) {
		$message .= "Please upload a valid sub page.<br>";
		$return = false;
	}
	if(getFileName($_FILES["subpage"]["name"]) != "sub") {
		$message .= "Name of sub page should be sub.htm<br>";
		$return = false;
	}
	if(!in_array(getExtension($_FILES["subpage"]["name"]),$valid)) {
		$message .= "Sub page should be of format " . $list . "<br>" ;
		$return = false;
	}
	if(!is_uploaded_file($_FILES["stylepage"]["tmp_name"])) {
		$message .= "Please upload a valid style sheet.<br>";
		$return = false;
	}
	if(getFileName($_FILES["stylepage"]["name"]) != "style") {
		$message .= "Name of style sheet should be style.css<br>";
		$return = false;
	}
	if(getExtension($_FILES["stylepage"]["name"]) != "css") {
		$message .= "Style sheet should be of format css<br>" ;
		$return = false;
	}
	if(getFileName($_FILES["homepageimg"]["name"]) != "homepageimage") {
		$message .= "Name of home page image should be homepageimage.jpg<br>";
		$return = false;
	}
	if(getExtension($_FILES["homepageimg"]["name"]) != "jpeg") {
		$message .= "Home page image should be of format jpg<br>" ;
		$return = false;
	}
	if(getFileName($_FILES["subpageimg"]["name"]) != "subpageimage") {
		$message .= "Name of sub page image should be subpageimage.jpg<br>";
		$return = false;
	}
	if(getExtension($_FILES["subpageimg"]["name"]) != "jpeg") {
		$message .= "Sub page image should be of format jpg<br>" ;
		$return = false;
	}
	return $return;
}
function validateSimple(&$message) {
	if(strlen(trim($_POST["txtIndexSep"])) <= 0) {
		$message .= "Link seperator in index page cannot be empty<br>";
		return false;
	}
	elseif(strlen(trim($_POST["txtSubSep"])) <= 0) {
		$message .= "Link seperator in sub page cannot be empty";
		return false;
	}
	else {
		return true;
	}
}


$numComplete = 0;
if($_POST["postback"] == "finish") {
	// copy 3 files index.htm, sub.htm, style.css to timestamp folder.	
	if(isValidFiles($message)) {
		$workdir = $_SESSION["session_advTemplateDir"];
		@move_uploaded_file($_FILES["homepage"]["tmp_name"],$workdir . "/" . $_FILES["homepage"]["name"]);		
		@chmod($workdir . "/" . $_FILES["homepage"]["name"],0777);
		@move_uploaded_file($_FILES["subpage"]["tmp_name"],$workdir . "/" . $_FILES["subpage"]["name"]);		
		@chmod($workdir . "/" . $_FILES["subpage"]["name"],0777);
		@move_uploaded_file($_FILES["stylepage"]["tmp_name"],$workdir . "/" . $_FILES["stylepage"]["name"]);		
		@chmod($workdir . "/" . $_FILES["stylepage"]["name"],0777);
		@move_uploaded_file($_FILES["homepageimg"]["tmp_name"],$workdir . "/" . $_FILES["homepageimg"]["name"]);		
		@chmod($workdir . "/" . $_FILES["homepageimg"]["name"],0777);
		@move_uploaded_file($_FILES["subpageimg"]["tmp_name"],$workdir . "/" . $_FILES["subpageimg"]["name"]);		
		@chmod($workdir . "/" . $_FILES["subpageimg"]["name"],0777);
		
		if($_SESSION["session_advTemplateType"] == "advanced") {
			$sql =  "Insert into tbl_template_mast(ntemplate_mast,ncat_id,vthumpnail,vtype,ddate) 
				Values('','" . $_SESSION["session_advCatId"] . "',
				'thumpnail.jpg',
				'advanced',
				now())";
			mysql_query($sql) or die(mysql_error());	
			$var_insert_id = mysql_insert_id();
			//rename($workdir,"../templates/" . $var_insert_id);
			@rename($workdir,"../" . $_SESSION["session_template_dir"] . "/" . $var_insert_id);
			clearadvsession();
			header("location:templadv6.php?temptype=adv&templateid=$var_insert_id");
			exit();
		}
		elseif($_SESSION["session_advTemplateType"] == "simple") {

			if(validateSimple($message) && HtmlToTplCheckforErros($workdir,"",$message)) {
				$sql =  "Insert into tbl_template_mast(ntemplate_mast,ncat_id,vthumpnail,vtype,
				vlink_type,vlink_separator,vsublink_type,vsublink_separator,ddate) 
					Values('','" . $_SESSION["session_advCatId"] . "',
					'thumpnail.jpg',
					'simple',
					'" . addslashes($_POST["cmbIndexType"]) . "',
					'" . addslashes($_POST["txtIndexSep"]) . "',
					'" . addslashes($_POST["cmbSubType"]) . "',
					'" . addslashes($_POST["txtSubSep"]) . "',
					now())";
				mysql_query($sql) or die(mysql_error());	
				$var_insert_id = mysql_insert_id();
				$Html2Tpl=HtmlToTpl($workdir,$var_insert_id,"");
				rename($workdir,"../" . $_SESSION["session_template_dir"] . "/" . $var_insert_id);
				clearadvsession();
				header("location:templadv6.php?temptype=simple&templateid=$var_insert_id");
				exit();
			}
			else {
				$numComplete=1;
			}
			/*HtmlToTpl($templateid,$originaltemplateid,$relativepathtoroot)
			* $templateid->tempory location
			* $originaltemplateid->templateid after insertion into db 
			*/ 
			
		}
	}
	else {
		$numComplete=1;
	}
}
//include files
include "./includes/adminheader.php";
?>
<script>
<!--
	var id = 0;
	<?php
		if($_SESSION["session_advTemplateType"] == "simple") {
			echo("id=1;");
		}
	?>
	function clickUpload() {
		if(validateForm()) {
			var frmStep2 = document.frmStep2;
			frmStep2.postback.value="finish";
			frmStep2.action="templadv5.php";
			frmStep2.method="post";				
			frmStep2.submit();
		}
	}
	function clickSame() {
		if(id == 1) {
			if(document.all("chkSame").checked == true) {
				document.all("txtSubSep").value = document.all("txtIndexSep").value;
				document.all("cmbSubType").value = document.all("cmbIndexType").value;
			}
			else {
				document.all("txtSubSep").value = "";
			}
		}	
	}
	function validateForm() {
		var frmStep2 = document.frmStep2;
		if(id == 1) {
			if(frmStep2.txtSubSep.value.length <= 0) {
				alert("Please enter a link seperator for index page.");
				return false;
			}
			if(frmStep2.txtIndexSep.value.length <= 0) {
				alert("Please enter a link seperator for index page.");
				return false;
			}
		}
		if(frmStep2.homepage.value.length <= 0) {
			alert("Please select the index page to be uploaded.");
			return false;
		}			
		if(frmStep2.stylepage.value.length <= 0) {
			alert("Please select the sub page to be uploaded.");
			return false;
		}			
		if(frmStep2.stylepage.value.length <= 0) {
			alert("Please select the style sheet to be uploaded.");
			return false;
		}	
		return true;		
	}
-->	
</script>
<table width="70%"  border="0">
	  <tr>
                	<td align="center"><img src="../images/createtemplate.gif" ><br>&nbsp;</td>
            	</tr>
</table>
<table width="70%"  border="0">
    <tr>
      <td width="6%">&nbsp;</td>
      <td width="17%" class="maintext"><a href="templadv1.php"><img src="../images/lite_step1.gif" height="20" width="56" border="0"></a></td>
      <td width="16%" class="maintext"><a href="templadv2.php"><img src="../images/lite_step2.gif" height="20" width="56" border="0"></a></td>
      <td width="17%" class="maintext"><a href="templadv3.php"><img src="../images/lite_step3.gif" height="20" width="56" border="0"></a></td>
      <td width="19%" class="maintext"><a href="templadv4.php"><img src="../images/lite_step4.gif" height="20" width="56" border="0"></a></td>
      <td width="25%" class="maintext"><b><a href="templadv5.php"><img src="../images/step5.gif" height="20" width="56" border="0"></a></b></td>
    </tr>
  </table>
<table width="70%"><tr>
<td align=center><br><br><br>
<form name="frmStep2" method="post" action="" enctype="multipart/form-data">
<fieldset>
<table width=100% border=0>

<tr>
<td width="100%" colspan=2 align=center class=maintext>&nbsp;</td>
</tr>


<tr>
  <td align=center colspan=2 class=maintext>&nbsp;
  
  </td>
</tr>
<tr>
  <td align=center colspan=2 class=maintext><font color=red>
  <?php
   if($numComplete == 1) {
	   echo $message;
   }
   ?></font></td>
</tr>
<tr>
<td align=left colspan=2 class=maintext>
<fieldset><legend>Insert home.htm, sub.htm, style.css here</legend>
<table width="100%"  border="0">
  <tr>
    <td width="6%"><input type="hidden" name="postback" value=""></td>
    <td width="29%"><img src="../images/homepage_1.JPG" alt="Home Page"></td>
    <td width="32%"><input type="file" name="homepage" id="homepage" onKeyPress=""  class=textbox ></td>
    <td width="33%" class="maintext">Home page <br>
    ( index.htm ) </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><img src="../images/subpage_1.JPG" alt="Sub Page"></td>
    <td><input type="file" name="subpage" id="subpage" onKeyPress=""  class=textbox ></td>
    <td class="maintext">Sub page <br>( sub.htm )</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><img src="../images/stylesheet_1.JPG" alt="Style Sheet"></td>
    <td><input type="file" name="stylepage" id="stylepage" onKeyPress=""  class=textbox ></td>
    <td class="maintext">Style sheet <br>( style.css ) </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><img src="../images/homeimage.jpg" alt="Homepage Image"></td>
    <td><input type="file" name="homepageimg" id="homepageimg" onKeyPress=""  class=textbox ></td>
    <td class="maintext">Homepage Image <br>
    ( homepageimage.jpg ) </td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><img src="../images/subimage.jpg" alt="Subpage Image"></td>
    <td><input type="file" name="subpageimg" id="subpageimg" onKeyPress=""  class=textbox ></td>
    <td class="maintext">Subpage Image <br>
    ( subpageimage.jpg ) </td>
  </tr>

</table>

</fieldset>
</td>
</tr>
<?php
if($_SESSION["session_advTemplateType"] == "simple") {
?>
<tr>
  <td colspan="2" align="left"  class=maintext>
  <fieldset>
 	<legend>Others</legend> 
	<table width="100%" border="0">
		<tr>
		  <td align="right" class="maintext">Link Seperator for index.htm </td>
		  <td>&nbsp;</td>
		  <td><input name="txtIndexSep" type="text" id="txtIndexSep" size="20" maxlength="100" class="textbox"></td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td align="right" class="maintext">Link Type  for index.htm</td>
		  <td>&nbsp;</td>
		  <td>
		  	<select name="cmbIndexType" id="cmbIndexType" class="selectbox" style="width:115px; ">
				<option value="vertical">Vertical</option>
				<option value="horizontal">Horizontal</option>
		    </select></td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
		  <td align="right">&nbsp;</td>
		  <td>&nbsp;</td>
		  <td>&nbsp;</td>
		  <td class="maintext"><input type="checkbox" name="chkSame" id="chkSame" value="same" onClick="javascript:clickSame();"> Same as Index</td>
		  </tr>
		<tr>
		  <td align="right"><span class="maintext">Link Seperator for sub.htm</span></td>
		  <td>&nbsp;</td>
		  <td><input name="txtSubSep" type="text" id="txtSubSep" size="20" maxlength="100"  class="textbox"></td>
		  <td>&nbsp;</td>
		  </tr>
		<tr>
			<td width="41%" align="right" class="maintext">Link Type for sub.htm </td>
			<td width="2%">&nbsp;
				
			</td>
			<td width="32%">
			<select name="cmbSubType" id="cmbSubType" class="selectbox"  style="width:115px; ">
				<option value="vertical">Vertical</option>
				<option value="horizontal">Horizontal</option>
			  </select>
				
			</td>
			<td width="25%">&nbsp;
				
			</td>
		</tr>
	</table>
  </fieldset>
   </td>
</tr>
<?php
	}
?>
<tr>
	<td colspan="2" align="center">
	<input type="button" onClick="javascript:clickUpload();" value="Upload"  class="editorbutton" >
	</td>
</tr>

<tr>
<td  colspan=2 align=center><br></td>
</tr>

</table>
</fieldset>
<br><br><br>&nbsp;

</form>
</td>
</tr></table>

<?php

include "includes/adminfooter.php";
?>