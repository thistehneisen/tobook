<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+

include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
?>

<div class="new_centercontainer">
	<div class="creationmode_header">
		<div class="process_no">1</div>
		<div class="process_title">Creation Mode</div>
		<div class="clear"></div>
	</div>
	<div class="creationmode_selector">
		<div class="create_option">
			<h2>Simple builder for newbie users</h2>
			<div class="createoption_img"></div>
			<div class="createoption_desc">
				<p>This builder uses templates that are very simple and the entire site building is taken care by . We recommend this for novice and newbie users who are not very proficient in HTML and site designing</p>
			</div>
			<input name="" type="button" class="continue_btn right">
			
			<a href="./showcategories.php?cat=simple"><img border="0" src="images/go_arrow.png"></a>
			<div class="clear"></div>
		</div>
		<div class="create_option_or">OR</div>
		<div class="create_option">
			<h2>Advanced builder for professional users</h2>
			<div class="createoption_img2"></div>
			<div class="createoption_desc">
				<p>This builder uses templates that are highly graphical oriented and the entire site building should be taken care by you.We recommend this for professional users who are very proficient in HTML and site designing</p>
			</div>
			<input name="" type="button" class="continue_btn right">
			<div class="clear"></div>
		</div>
	<div class="clear"></div>
	</div>
<div class="clear"></div>
</div>


<!-- commented for new design
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align=center>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
				<tr>
				  <td align=center>
				  <div class="stage_selector">
				  <span>1</span>&nbsp;&nbsp;Select Template
				  </div>
				  
				 </td>
				</tr>
				<tr>
				  <td align=center>&nbsp;</td>
				</tr>
                <tr>
                <td>
				Main section starts here--
				            <table  width="100%" border="0" cellpadding="0" cellspacing="0">
							  <tr >
							    <td align="left" class="buildtype_img1">&nbsp;
								    
								</td>
								<td width="10">&nbsp;</td>
								<td align="center" class="buildtype_img2">&nbsp;
								    
								</td>
							  </tr>
							  
							  <tr>
							  <td class="buildtype_head"><a href="./showcategories.php?cat=simple">Simple builder for newbie users</a></td>
							  <td>&nbsp;</td>
							  <td class="buildtype_head"><a href="./showcategories.php?cat=adv">Advanced builder for professional users</a></td>
							  </tr>
							  
							  
							  <tr>
							     <td align="left" class="buildtype_text">This builder uses templates that are very simple and the entire site building is taken care by <?php echo($_SESSION["session_lookupsitename"]); ?>.  We recommend this for novice and newbie users who are not very proficient in HTML and site designing</td>
								 
								 <td>&nbsp;</td>
								 
								 <td align="left" class="buildtype_text">
							    This builder uses templates that are highly graphical oriented and the entire site building should be taken care by you.We recommend this for professional users who are very proficient in HTML and site designing</td>
							  </tr>
							  
							  <tr>
							  <td class="buildtype_cnt" align="right"><a href="./showcategories.php?cat=simple"><img src="images/go_arrow.png" border="0"></a></td>
							  <td>&nbsp;</td>
							  <td class="buildtype_cnt" align="right"><a href="./showcategories.php?cat=adv"><img src="images/go_arrow.png" border="0"></a></td>
							  </tr>
							  
							</table>
				<!-- Main section ends here-
                </td>
                </tr>
				<tr><td>&nbsp;</td></tr>
				 <tr><td align="left"><a href="sitemanager.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a></td></tr>
				<tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
				<tr><td>&nbsp;</td></tr>
                </table>
</td>
</tr>
</table>-->
<?php
include "includes/userfooter.php";
?>