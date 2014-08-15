<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";

$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_INTEGRATION_MANAGER =>'integrationmanager.php');
echo getBreadCrumb($linkArray);
?>
<h2><?php echo FOOTER_INTEGRATION_MANAGER; ?></h2>

<div class="cpanel_container">
	
	
	
	<!-- One row -->
	<div class="cpanel_row">
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="selectsite.php?page=feedback"><img src="images/icon_feedback.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="selectsite.php?page=feedback">Feedback Form</a></h3>
				<p>
				Add a form to your site so that visitors may give you feedback.
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="selectsite.php?page=customform"><img src="images/icon_customized.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="selectsite.php?page=customform">Customized Form</a></h3>
				<p>
				Add a customized form to your website.
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item" style="margin-right:0; ">
				<div class="image">
				<a href="selectsite.php?page=guestbook"><img src="images/icon_guestbook.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="selectsite.php?page=guestbook">Guest Book</a></h3>
				<p>
				Add a guestbook to your site so that visitors may sign it.
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			
	<div class="clear"></div>
	</div>
	<!-- One row ends -->
	
<div class="clear"></div>
</div>
<!--div class="comm_div" align="left"><a href="usermain.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a></div-->
<?php
include "includes/userfooter.php";
?>