<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
	if($_POST['btnuploadtype'] !=""){
	    
	  if($_POST['uptype']=="0"){
	     // if user select to upload image.call gallerymanager.php and focus to upload block
	     header("location:gallerymanager.php#");
		 exit;
	  }else if($_POST['uptype']=="1"){
	    //if user want to edit our gallery redirect to manageourgallery.php
	    header("location:manageourgallery.php");
		exit;
	  }if($_POST['uptype']=="2"){
	     header("location:gallerymanager.php");
		exit;
	  }
	}
include "includes/userheader.php";
?>
<script>
      function checkupload(){
	  
		document.getElementById("btnuploadtype").value="Upload";
	  }	
	  function checkourgallery(){
	    document.getElementById("btnuploadtype").value="Use images from system gallery";
	  }
	  function checkyourgallery(){
	    document.getElementById("btnuploadtype").value="Use images from your gallery";
	  }
	  function saveimagepro(){
			if(document.getElementById("btnuploadtype").value=="Upload"){
				returnValue.url = "uploadimage.php";
			}else if(document.getElementById("btnuploadtype").value=="Use images from system gallery"){
				returnValue.url = "ourgallerynew.php";	
			}else{
			   returnValue.url = "manageusergallery.php";
			}
	  }
</script>

<table width="80%"  border="0">
  <tr>
    <td width="12%">&nbsp;</td>
    <td width="76%" align="center" ><img src=images/imagemanager.gif><br>&nbsp; </td>
    <td width="12%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan=3 align=center>
	                      
	                      <FIELDSET>
					      <legend class=maintextbold>Select an image using one of the following methods to edit with image editor</legend>
						    <form name=frmImageGallery method=post>
						    <table border=0 width=60%>
							      <form nameimageproper method=post>
							      <tr>
								     <td width=20%><input id=uptype name=uptype type=radio class=raiobutton checked onclick="checkupload()"; value="0"></td>
								     <td class=maintext align=left  width=80%>Upload Your Image</td>
								  </tr>
								  <tr>
								     <td width=20%><input id=uptype  name=uptype type=radio class=raiobutton onclick="checkourgallery()"; value="1"></td>
								     <td class=maintext align=left  width=80%>Use images from system gallery</td>
								  </tr>
								  <tr>
								     <td width=20%><input id=uptype name=uptype type=radio class=raiobutton onclick="checkyourgallery()"; value="2"></td>
								     <td class=maintext align=left  width=80%>Use images from your gallery</td>
								  </tr>
								
								  <tr>
								    <td colspan=4 align=center>
									
									<input id=btnuploadtype  name=btnuploadtype class=button  type=submit value="Upload" >
									
								  </tr>
								 
								  </form>
							  </table>
						      </form>
						  
					  </FIELDSET><br>
	<a href="usermain.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a>
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" valign="top"></td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
include "includes/userfooter.php";
?>