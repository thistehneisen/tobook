<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php

$curTab = 'profile';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
?>
<?php $linkArray = array(
    TOP_LINKS_DASHBOARD=>'usermain.php',
    TOP_LINKS_MY_ACCOUNT =>'profilemanager.php',
                          );
echo getBreadCrumb($linkArray);?>
<h2><?php echo FOOTER_PROFILE_MANAGER; ?></h2>

<div class="cpanel_container">
	
	<!-- One row -->
	<div class="cpanel_row">
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="editprofile.php"><img src="images/icon_editprofile.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="editprofile.php"><?php echo EDIT_PROFILE; ?></a></h3>
				<p>
				  <?php echo EDIT_PROFILE_DESC; ?>
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="editpassword.php"><img src="images/icon_editpassword.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="editpassword.php"><?php echo EDIT_PASSWORD; ?></a></h3>
				<p>
				 <?php echo EDIT_PASSWORD_DESC; ?>
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item" style="margin-right:0; ">
				<div class="image">
				<a href="viewpayment.php"><img src="images/icon_payment.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="viewpayment.php"><?php echo PAYMENT_DETAILS; ?></a></h3>
				<p>
				 <?php echo PAYMENT_DETAILS_DESC; ?>
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

	
<!--div class="comm_div left"><a href="usermain.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a></div-->
&nbsp;
<?php
include "includes/userfooter.php";
?>