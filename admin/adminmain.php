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

$curTab = 'adminmain';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "./includes/adminheader.php";
?>
<div class="admin-pnl">
<h2>Admin Panel</h2>
<div class="cpanel_container">

<!-- One row -->
	<div class="cpanel_row">
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="settings.php"><img src="../images/icon_integrationmanager.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="settings.php">Settings Manager</a></h3>
				<p>
				Set the basic parameters by defining site publishing permissions, passwords, duration of temporary site maintenance and the administrator email. 
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="templatemanager.php"><img src="../images/icon_templatemanager.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="templatemanager.php">Template Manager </a></h3>
				<p>
				Upload templates under particular categories and add or remove categories of templates for the site. 
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item" style="margin-right:0; ">
				<div class="image">
				<a href="usermanager.php"><img src="../images/icon_profilemanager.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="usermanager.php">User Manager</a></h3>
				<p>
				View all the details of the users who are using the site, delete users and edit user information.
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			
	<div class="clear"></div>
	</div>
	<!-- One row ends -->
	
	
	<!-- One row -->
	<div class="cpanel_row">
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="sitemanager.php"><img src="../images/icon_sitemanager.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="sitemanager.php">Site Manager</a></h3>
				<p>
				View details, preview and delete sites created by users who are using this site. 
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="payment.php"><img src="../images/icon_payment.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="payment.php">Payment Manager</a></h3>
				<p>
				View details of payments made by users.
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item" style="margin-right:0; ">
				<div class="image">
				<a href="cmslisting.php"><img src="../images/icon_contentmanager.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="cmslisting.php">Content Manager</a></h3>
				<p>
				Edit the main site content.
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
<div class="clear"></div>
</div>



<?php

include "includes/adminfooter.php";
?>