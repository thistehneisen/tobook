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
//STEP - VI  [shows successfull message to admin on successfull template creation] 
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
$error = false;
$message = "Successfully created template";
//include files
include "./includes/adminheader.php";
$templateid=$_GET['templateid'];

$templatetype=$_GET['temptype'];


//showtplpriview("9", "index", "..");
?>
<script>
<!--
	function clickNext() {
		var frmStep1 = document.frmStep1;
		var txtImageNo = frmStep1.txtImageNo;
		if(txtImageNo.value.length == 0 || isNaN(parseInt(txtImageNo.value)) || parseInt(txtImageNo.value) < 6) {
			alert("Minimum no. of images should be 6.");
			txtImageNo.value=0;
			return false;
		}
		else {
			txtImageNo.value=parseInt(txtImageNo.value);
		}
		var fileImage = frmStep1.fileThumpImage;
		if(fileImage.value.length <= 0) {
			alert("Please select a file for thumpnail image.");
			return;
		} 
		frmStep1.postback.value="step1";
		frmStep1.action="templadv1.php";
		frmStep1.method="post";				
		frmStep1.submit();
	}
	function showpreview(prtype,id,type){
            var leftPos = (screen.availWidth-500) / 2;
		    var topPos = (screen.availHeight-400) / 2 ;
			winurl="templatepriview.php?prtype="+prtype+"&id="+id+"&type="+type+"&";
		    //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
		    insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos); 

			 
   }
	
	
-->	
</script>
<table width="100%"><tr>
<td align=center><br><br><br>
<form name="frmStep1" method="post" action="" enctype="multipart/form-data">
<fieldset>
<table width=470 border=0>

<tr>
<td align=center colspan=3 class=maintext>&nbsp;</td>
</tr>


<tr>
<td align=center colspan=3 class=maintext><font color=red><?php  echo $message;?></font></td>
</tr>
<tr>
<td align=center colspan=3 class=maintext><font color=red>&nbsp;</font></td>
</tr>
<?php
  if($templatetype=="adv"){
  
  
   
   
   
?>  
   <td class=maintext> <a class=subtext  target="_blank" href="./templatepriview.php?prtype=index&id=<?php echo $templateid?>&type=advanced&" >Homepage Priview</td>
   <td class=maintext><a  class=subtext  target="_blank" href="./templatepriview.php?prtype=sub&id=<?php echo $templateid?>&type=advanced&">Subpage Priview</td>
<?php } elseif($templatetype=="simple"){
?>
    <td class=maintext><a class=subtext target="_blank" href="./templatepriview.php?prtype=index&id=<?php echo $templateid?>&type=simple&">Homepage Priview</td>
    <td class=maintext><a class=subtext target="_blank" href="./templatepriview.php?prtype=sub&id=<?php echo $templateid?>&type=simple&">Subpage Priview</td>
<?php  } ?>
<tr>
<td  colspan=3 align=center><br></td>
</tr>

</table>

<br>
&nbsp;
</fieldset>
<br><br><br>&nbsp;

</form>
</td>
</tr></table>

<?php

include "includes/adminfooter.php";
?>