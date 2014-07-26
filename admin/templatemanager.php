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
//	(i)		Template Categories
//	(ii)	Add a new template
//	(iii)	List all templates
//-------------------------------------------------------------------------+

$curTab = 'template_manager';

//include files
include "../includes/session.php";
include "../includes/config.php";
include "includes/adminheader.php";
?>

<div class="admin-pnl">
<h2><?php echo TEMP_MANAGER;?></h2>

<div class="cpanel_container">

<!-- One row -->
	<div class="cpanel_row">
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="tempcategorymanager.php"><img src="../images/icon_tempcategory.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="tempcategorymanager.php"><?php echo TEMP_CATEGORIES;?></a></h3>
				<p>
				<?php echo ADD_REMOVE_CATEGORY;?>
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="addtemplate.php"><img src="../images/icon_newtemplate.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="addtemplate.php"><?php echo ADD_NEW_TEMP;?></a></h3>
				<p>
				<?php echo UPLOAD_TEMP_ZIP;?>.
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item" style="margin-right:0; ">
				<div class="image">
				<a href="templatelisting.php"><img src="../images/icon_templatemanager.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="templatelisting.php"><?php echo MANAGE_TEMP;?></a></h3>
				<p>
				<?php echo VIEW_DELETE_TEMP;?>. 
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
</div>
<?php
include "includes/adminfooter.php";
?>