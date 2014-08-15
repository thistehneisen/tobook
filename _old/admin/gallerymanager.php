<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// +----------------------------------------------------------------------+
//Admin user gets the option to move on to one of
//	(i)		Image Category Manager
//	(ii)	Image Manager
//include files
include "../includes/session.php";
include "../includes/config.php";
include "includes/adminheader.php";

?>
<br>
<table width=70% border=0>
<tr><td width=100% colspan=2 ><img src=../images/gallerymanager.gif><br>&nbsp;</td></tr>
<tr><td width=100% colspan=2 align=center>

	<!--start HELPPANEL CODE -->
	<fieldset style="width:350px;   border-style:groove; ">
	<script language="javascript">
	function resetHelp(){
		document.getElementById('helpcenter').innerHTML="Welcome to the <font class=redtext>Gallery Manager</font>. You can use this panel to manage your system image gallery supplied to users for site building. Please place the mouse on the icons below to get information on each of the managers.";
	}
	function setHelp(helptext){
		document.getElementById('helpcenter').innerHTML=helptext;
	}	
	function genHex(){
		colors = new Array(14);
		colors[0]="0";
		colors[1]="1";
		colors[2]="2";
		colors[3]="3";
		colors[4]="4";
		colors[5]="5";
		colors[5]="6";
		colors[6]="7";
		colors[7]="8";
		colors[8]="9";
		colors[9]="a";
		colors[10]="b";
		colors[11]="c";
		colors[12]="d";
		colors[13]="e";
		colors[14]="f";
		digit = new Array(5);
		color="";
		for (i=0;i<6;i++){
		digit[i]=colors[Math.round(Math.random()*14)];
		color = color+digit[i];
		}
		document.getElementById('lady').style.backgroundColor=color;
}	
	</script>
	<table width="100%"  border="0" cellpadding="0">
    <tr>
    <td width="25%" bgcolor=#000000 class=whitetext>Help Center</td>
    <td width="75%" rowspan="2" align=center valign=top><table width=100% cellpadding="5"><tr><td align="left"><span id=helpcenter class="helptext" style="width:100%; overflow: hidden;" >
	Welcome to the <font class=redtext>Gallery Manager</font>. You can use this panel to manage your system image gallery supplied to users for site building.
	&nbsp;Please place the mouse on the icons below to get information on each of the managers.
	</span></td></tr></table></td>
    </tr>
    <tr>
    <td align=center valign=top><img src="../images/untitled123.gif" width="100" height="86" id="lady" onLoad=genHex();></td>
    </tr>
    </table>
	</fieldset>
	<!--end  HELPPANEL CODE -->
	
	</td></tr>
	<tr><td><br>
	
	
	
	<fieldset style=width:90%>
	<table width=70% border=0>
	<tr>
  	<td align=center class=toplinks><a href="categorymanager.php"><img src="../images/iconcategories.gif"  border="0"onMouseOver="setHelp('System Gallery images of the site are organized under different image categories like business, sports etc. <font class=redtext>Image Category Manager</font> allows you to add a category or remove a category.');" onMouseOut="resetHelp();"></a></td>
  	<td align=center  class=toplinks><a href="imagemanager.php"><img src="../images/iconimagemanager.gif"  border="0"onMouseOver="setHelp('The site supplies images of system gallery for sitebuilding process. <font class=redtext>Image Manager</font> can be used to add or delete a system gallery image.');" onMouseOut="resetHelp();"></a></td>
	</tr>
	<tr>
	<td align=center width=50% class=maintext>Image Category Manager</td>
	<td align=center width=50%  class=maintext>Image Manager</td>
	</tr>
	</table>
	<br>&nbsp;
	</fieldset>


<br>&nbsp;
</td>
</tr>
</table>

<?php
include "includes/adminfooter.php";
?>