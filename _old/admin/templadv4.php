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
//STEP - IV  [admin uploads the images required for the template]
//we enforce certain rules for images being uploaded.
//	(a)		names of templates should start with tp_
//	(b)		format of images should be jpg/gif for "advanced" templates, and, jpg for "simple" templates.   
//(i)	check the $_SESSION["session_steps"], if the value is not >= 3 then we take the user to 
//		templadv1.php or templadv2.php or templadv3.php based on the value of $_SESSION["session_steps"].	
//(ii)	check $_SESSION["session_advTemplateType"], if "advanced" then extension can be "jpg/gif" else "jpg"
//User is given two options - 
//	(A)	Upload individual files
//	(B)	Upload a zip file which will be unzipped to the images and watermarkimages folder
//(A)	Upload individual files
//			Here user is given the option of uploading individual files, along with the option to watermark images
//			at the time of uploading.  On uploading files we copy the images to templatedirectory/images 
//			and  templatedirectory/watermarkimages. If we have selected the watermark option checked,
//			those images will be watermarked and placed in templatedirectory/watermarkimages. 
//(B)	Upload Zip file
//			Here user can create a zip file of all the images and upload the zip file, which will be unzipped
//			and copied to templatedirectory/images and templatedirectory/watermarkimages.   		 		
//In any of the above options all files will be validated for names(tp_) and extension. 
//$_SESSION["session_steps"] is set to 4 and navigated to templadv5.php.  
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/pclzip.lib.php";

$error = false;
$message = "";
$succesfull = "";
$failed = "";

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

//valid formats are given as jpeg,gif
$templateType = "";
if($_SESSION["session_advTemplateType"] == "advanced") {
	$display_format = "jpg/gif";
	$templateType = "advanced";
	$valid = array("jpeg","gif");
	$list = "jpeg,gif";
}
else {
	$display_format = "jpg/gif";
	$templateType = "simple";
	$valid = array("jpeg","gif");
	$list = "jpeg,gif";
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
function isValidFileName($filename) {
	return (substr($filename,0,3) == "tp_");
}
$imagesdir = $_SESSION["session_advTemplateDir"] . "/images";
$watermarkdir = $_SESSION["session_advTemplateDir"]	 . "/watermarkimages";	
$boolComplete=0;
if($_POST["postback"] == "files") {
	$postbackcount = $_POST["postbackcount"];
	if(!is_array($_POST["chkWatermark"])) {
		$_POST["chkWatermark"] = array($_POST["chkWatermark"]);
	}
	for($i = 1; $i <= $postbackcount; $i++ ) {
			if(is_uploaded_file($_FILES["image" . $i]["tmp_name"]) && in_array(getExtension($_FILES["image" . $i]["name"]),$valid)) {
				
				//$fileExtention =  getFileExtension($_FILES["image" . $i]["name"]);
				$fileName =  "/tp_" . $_FILES["image" . $i]["name"];
				$imageloc = $imagesdir . $fileName;
				$watermarkloc = $watermarkdir . "/" . $fileName;
				@move_uploaded_file($_FILES["image" . $i]["tmp_name"],$imageloc);
				@chmod($imageloc,0777);

				@copy($imageloc,$watermarkloc);
				@chmod($watermarkloc,0777);
				
				$succesfull .= " " . $_FILES["image" . $i]["name"] . ",";
				
				if(in_array("image" . $i,$_POST["chkWatermark"])) {
					watermarkimage($watermarkloc,null,"../images/watermark.gif");
				}
			}
			else {
				if(strlen($_FILES["image" . $i]["name"]) > 0) {
					$failed .= " " . $_FILES["image" . $i]["name"] . ",";
					$boolComplete=1;
				}
			}
	}
	if($boolComplete == 0) {
		$message .= "Files succesfully uploaded.";
		//$boolComplete = 2;
		$_SESSION["session_steps"] = 4;
		header("location:templadv5.php");
		exit();
	}
}
elseif($_POST["postback"] == "zip") {
	if(is_uploaded_file($_FILES["zipfile"]["tmp_name"]) &&  getExtension($_FILES["zipfile"]["name"]) == "zip") {

	  $archive = new PclZip($_FILES["zipfile"]["tmp_name"]);
	  if ($archive->extract(PCLZIP_OPT_PATH,$imagesdir) == 0){
		  die("Error : ".$archive->errorInfo(true));
	  }
	  if ($archive->extract(PCLZIP_OPT_PATH,$watermarkdir) == 0){
		  die("Error : ".$archive->errorInfo(true));
	  }
	  $loc = $imagesdir . "/";
	  $handle = opendir($loc);
	  while (false !== ($file = readdir($handle))) {
	   
	   
	   
			if(!in_array(getExtension($file),$valid)) {
				if($file != "." && $file != "..") {
					@unlink($loc . $file);
					@unlink($watermarkdir . "/" . $file);
					$failed .= " " . $file . ",";
					$boolComplete=1;
				}
			}
	   }
	   if($boolComplete == 0) {
			$message .= "Files succesfully uploaded.";
			$_SESSION["session_steps"] = 4;
			header("location:templadv5.php");
			exit();
		}
	}
	else {
		$boolComplete=1;
		$failed = ".  Please upload a valid file of the format .zip if using the 'Upload Zip' option.";
	}	
}
//include files
include "./includes/adminheader.php";
?>
<script>
<!--
	var selId="";
	var selRi=-1;
	trId = 1;

	function clickFiles() {
//		document.all("rdSelect")(0).checked = true; 

                document.frmStep4.rdSelect[0].checked = true; 
                
		var frmSite4 = document.frmStep4;
		frmStep4.postback.value="files";
		frmStep4.postbackcount.value=trId;
		frmStep4.action="templadv4.php";
		frmStep4.method="post";				
		frmStep4.submit();
	}
	function clickZip() {
//		document.all("rdSelect")(1).checked = true; 
                document.frmStep4.rdSelect[1].checked = true; 
		var frmSite4 = document.frmStep4;
		frmStep4.postback.value="zip";
		frmStep4.action="templadv4.php";
		frmStep4.method="post";				
		frmStep4.submit();
	}

	function addRow(){
        var rowcount,cnt;
//        cnt=document.all("tblPartsList").rows.length;
        cnt=document.getElementById('tblPartsList').getElementsByTagName("tr").length;
        cnt++;
        trId++;
//        rowcount = document.all("tblPartsList").rows.length;
        rowcount = document.getElementById('tblPartsList').getElementsByTagName("tr").length;
        document.all("tblPartsList").insertRow();
        document.all("tblPartsList").rows(rowcount).id="part" + trId;
//        document.all("tblPartsList").rows(rowcount).onclick="javascript:alert('hai');";
		
        document.all("tblPartsList").rows(rowcount).insertCell();

        document.all("tblPartsList").rows(rowcount).cells(0).width='5%';
        document.all("tblPartsList").rows(rowcount).cells(0).align='right';

        document.all("tblPartsList").rows(rowcount).cells(0).innerHTML="<input type='checkbox'  name='chkId' id='chkId'  value=\"part" + trId + "\" style='font-family:Arial Narrow;font-size=11px;  background:#FFFFFF'>";

		document.all("tblPartsList").rows(rowcount).insertCell();
        document.all("tblPartsList").rows(rowcount).cells(1).width='3%';
        document.all("tblPartsList").rows(rowcount).cells(1).align='right';

        document.all("tblPartsList").rows(rowcount).cells(1).innerHTML="<input type=text  name='subLine'  size='2' value=\"" + cnt + "\" style='border: 0 solid white;font-family:Arial Narrow;font-size=11px;  background:#FFFFFF; text-align:center;' readonly>";

		document.all("tblPartsList").rows(rowcount).insertCell();
        document.all("tblPartsList").rows(rowcount).cells(2).width='41%';
        document.all("tblPartsList").rows(rowcount).cells(2).align='right';

        document.all("tblPartsList").rows(rowcount).cells(2).innerHTML="<input type='file'  name='image" + trId + "'  size='30'  class='textbox' onChange='javascript:addNew(this);'  onClick='javascript:selectZip(1);'>";


        document.all("tblPartsList").rows(rowcount).insertCell();
        document.all("tblPartsList").rows(rowcount).cells(3).width='51%';
        document.all("tblPartsList").rows(rowcount).cells(3).align='left';
		document.all("tblPartsList").rows(rowcount).cells(3).innerHTML="&nbsp;&nbsp;<input type=\"checkbox\" name=\"chkWatermark[]\" value=\"image" + trId + "\"> Watermark image &nbsp;&nbsp;&nbsp;&nbsp;<a href='#' onClick=\"javascript:clickDelete('part" + trId + "');\">Delete</a>";
/*

        document.all("alert").innerHTML="<font face=verdana size=1 color=#FF0000> Click on a value to select a row</font>";
*/
        }
		 function clickTable()
        {
			try{
				var label= window.event.srcElement.parentElement.parentElement.id;
				if(label.length != 0)
				{
					selId=window.event.srcElement.parentElement.parentElement.id;
					//document.all("selId").value=window.event.srcElement.parentElement.parentElement.id;
					//var ri=window.event.srcElement.parentElement.parentElement.rowIndex;
					//document.all("selRi").value=ri;
					selRi=window.event.srcElement.parentElement.parentElement.rowIndex;
					for(i=0;i<=trId;i++){
						try{
						document.all("part" + i).style.backgroundColor="#FFFFFF";
						}catch(e){}
					}
					resetColor();
					//document.all(window.event.srcElement.parentElement.parentElement.id).style.backgroundColor="#CCCCCC";
					setColor();
				}
			}catch(e){}
        }
		function setColor()
        {
				var ri1=selRi;
				document.all("tblPartsList").rows(ri1).cells(0).firstChild.style.background="#CCCCCC"
                document.all("tblPartsList").rows(ri1).cells(1).firstChild.style.background="#CCCCCC"
                document.all("tblPartsList").rows(ri1).cells(2).firstChild.style.background="#CCCCCC"
                document.all("tblPartsList").rows(ri1).cells(3).firstChild.style.background="#CCCCCC"
        }
        function resetColor()
        {
                for(passid=0;passid<document.all("tblPartsList").rows.length;passid++){
					document.all("tblPartsList").rows(passid).cells(0).firstChild.style.background="#FFFFFF"
					document.all("tblPartsList").rows(passid).cells(1).firstChild.style.background="#FFFFFF"
					document.all("tblPartsList").rows(passid).cells(2).firstChild.style.background="#FFFFFF"
					document.all("tblPartsList").rows(passid).cells(3).firstChild.style.background="#FFFFFF"
                }
        }
		 function clickDelete(delRow){
                if(delRow != ""){
                        document.all(delRow).removeNode(true);
                        resetLine();
						selId = "";
                        selRi = "";
						if(document.all("tblPartsList").rows.length <= 0) {
							addRow();
						}
                        //clear();
                }
                else
                {
                        alert('Please select a row to delete');
                }
        }
		function resetLine()
        {
			for(i=0;i<=trId;i++){
				try{
				document.all("tblPartsList").rows(i).cells(1).firstChild.value=i+1;
				}catch(e){}
			}
        }
		function addNew(t,bAlert) {
			var rowCount = document.getElementById('tblPartsList').getElementsByTagName("tr").length;
			if(document.all("tblPartsList").rows((rowCount - 1)).cells(2).firstChild.value.length > 0) {
				addRow();
			}
			else if(bAlert) {
				alert('Please select an image in the last row to add a new row');
			}
		}
		
		
		function deleteAll() {
			var rowCount = document.all("tblPartsList").rows.length;
			for(passid=0;passid<document.all("tblPartsList").rows.length;passid++){
				if(document.all("tblPartsList").rows(passid).cells(0).firstChild.checked == true) {
					clickDelete(document.all("tblPartsList").rows(passid).cells(0).firstChild.value);
					passid--;
				}
			}
		}
		function viewImages(loc) {
			strUrl = "loc=" + loc; 
			res = showModalDialog("viewimage.php?" + strUrl, null, 'dialogWidth: 500px; dialogHeight: 360px; center: yes; resizable: no; scroll: auto; status: no;');	
		}	
		function clickRadio(i) {
			if(document.all("rdSelect")(0).checked) {
				document.all("btUploadZip").disabled=true;
				document.all("btUploadFiles").disabled=false;
			}			
			else {
				document.all("btUploadZip").disabled=false;
				document.all("btUploadFiles").disabled=true;
			}
		}
		function selectZip(i) {
			if(i == 1) {
				document.all("rdSelect")(0).checked = true;
				clickRadio(0);
			}
			else {
				document.all("rdSelect")(1).checked = true;
				clickRadio(1);
			}
		}
-->	
</script>
<table width="70%"  border="0">
	  <tr>
                	<td align="center"><img src="../images/createtemplate.gif" ><br>&nbsp;</td>
            	</tr>
</table>
<table width="90%"  border="0">
    <tr>
      <td width="6%">&nbsp;</td>
      <td width="17%" class="maintext"><a href="templadv1.php"><img src="../images/lite_step1.gif" height="20" width="56" border="0"></a></td>
      <td width="16%" class="maintext"><a href="templadv2.php"><img src="../images/lite_step2.gif" height="20" width="56" border="0"></a></td>
      <td width="17%" class="maintext"><a href="templadv3.php"><img src="../images/lite_step3.gif" height="20" width="56" border="0"></a></td>
      <td width="19%" class="maintext"><b><a href="templadv4.php"><img src="../images/step4.gif" height="20" width="56" border="0"></a></b></td>
      <td width="25%" class="maintext"><a href="templadv5.php"><img src="../images/lite_step5.gif" height="20" width="56" border="0"></a></td>
    </tr>
</table>
<table width="70%"><tr>
<td align=center>
<form name="frmStep4" method="post" action="" enctype="multipart/form-data">
<input type="hidden" name="postback" value="">
<input type="hidden" name="postbackcount" value="">

<table width=100% border=0>

<tr>
  <td colspan=2 align="left" class=maintext>&nbsp;
  
  </td>
</tr>
<tr>
<td width="100%" colspan=2 align="left" class=maintext>
*&nbsp;&nbsp;&nbsp;&nbsp;The name of the images should start with 'tp_'<br>
**&nbsp;&nbsp;&nbsp;The images should be of the format <?php echo($list); ?><br>
***&nbsp;&nbsp;Total size of the images	that can be uploaded at a time is 2MB<br>
</td>
</tr>


<tr>
  <td align=center colspan=2 class=maintext><font color=red>
  <?php
  	 echo $message;
	 if($boolComplete == 1) {
	 	if(strlen($succesfull) > 0) {
			echo("<BR>Succesfully copied files " . substr($succesfull,0,-1) . "<BR>");
		}
	 	if(strlen($failed) > 0) {
			echo("<BR>Failed to copy files " . substr($failed,0,-1) . "<BR>");
		}
	 }	 
  ?></font></td>
</tr>
<tr>
  <td align=right colspan=2 class=maintext><a href="#" onClick="javascript:viewImages('<?php echo($imagesdir);?>');">View images present</a>&nbsp;
  
  
  
  
  </td>
</tr>
<tr>
<td align=left colspan=2 class=maintext>
<fieldset><legend><input type="radio" name="rdSelect" value="images" onClick="javascript:clickRadio(0);" checked="true"> Add images</legend>
<table width="100%"  border="0">
  <tr>
  	<td height="37" colspan="4">
	
	<table width=100% height="80px;" border="0"><tr><td height="80px;">
		  <span style="width:100%; height:100%; overflow:auto; background-color:#FFFFFF; border:1 groove #990033;"  id="subSpan" >
		  <table name="tblPartsList" id="tblPartsList"  bgcolor="#FFFFFF" width="100%" onClick="javascript:clickTable();">
				<tr id="part1">
					<td width="5%">
						<input type="checkbox" name="chkId" id="chkId" value="part1">
					</td>
					<td width="3%">
						<input type=text  name='subLine'  size='2' value="1" style='border: 0 solid white;font-family:Arial Narrow;font-size=11px;  background:#FFFFFF; text-align:center;' readonly>
					</td>
					<td width="41%">
						<input type="file" name="image1" id="image1" class=textbox onChange="javascript:addNew(this);" size="30"  onClick="javascript:selectZip(1);">
					</td>
					<td width="51%">
						&nbsp;&nbsp;<input type="checkbox" name="chkWatermark[]" value="image1"> 
						Watermark image &nbsp;&nbsp;&nbsp;&nbsp;
						<a href="#" onClick="javascript:clickDelete('part1')">Delete</a>
					</td>
				</tr>
		   </table>
		  </span>
		  </td></tr></table>
		
	</td>
  </tr>
  <tr>
  	<td colspan="4">
	<a href="#" onClick="javascript:deleteAll();">Delete selected</a> &nbsp;&nbsp;
	<a href="#" onClick="javascript:addNew(null,true);">Add New Image</a> &nbsp;&nbsp;
	</td>
  </tr>
  <tr>
  	<td colspan="4" align="center"><input type="button" name="btUploadFiles" id="btUploadFiles" onClick="javascript:clickFiles();" value="Upload Files"  class="editorbutton" >
	
	</td>
  </tr>
</table>
</fieldset>
</td>
</tr>
<tr>
<td align="left" colspan=2 class=maintext>&nbsp;

</td>
</tr>


<tr>
<td  colspan=2 align=center><br>
  </td>
</tr>
<tr>
  <td  colspan=2 align=left  class=maintext>
  <fieldset>
  	<legend align="left"><input type="radio" name="rdSelect" value="zip" onClick="javascript:clickRadio(1);">Upload a zip file</legend>
  <table width="100%"  border="0">
    <tr>
      <td width="3%">&nbsp;</td>
      <td width="19%" class="maintext">Select the zip file </td>
      <td width="78%"><input type="file" name="zipfile" id="zipfile" class="textbox" size="50" onClick="javascript:selectZip(0);"></td>
    </tr>
    <tr>
      <td colspan="3"><div align="center">
        <input name="btUploadZip" type="button" id="btUploadZip" value="Upload Zip" onClick="javascript:clickZip();" class="editorbutton">
      </div></td>
      </tr>
  </table>
  </fieldset>
  </td>
</tr>
</table>

<br><br><br>&nbsp;

</form>
</td>
</tr></table>

<?php
include "includes/adminfooter.php";
?>