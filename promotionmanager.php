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
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";

$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_PROMOTION_MANAGER =>'promotionmanager.php');
echo getBreadCrumb($linkArray);
?>

<h2><?php echo FOOTER_PROMOTION_MANAGER; ?></h2>

<div class="cpanel_container">
	
	<!-- One row -->
	<div class="cpanel_row">
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="sitesubmission.php"><img src="images/icon_searchengine.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="sitesubmission.php"><?php echo SEARCH_ENGINE_SUBMISSION; ?></a></h3>
				<p>
				<?php echo SEARCH_ENGINE_DESCRIPTION; ?>
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item">
				<div class="image">
				<a href="tellfriend.php"><img src="images/icon_tellafriend.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="tellfriend.php"><?php echo TELL_FRIEND; ?></a></h3>
				<p>
				<?php echo TELL_FRIEND_DESCRIPTION; ?>
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item -->
			<div class="item" style="margin-right:0; ">
				<div class="image">
				<a href="metagen.php"><img src="images/icon_metetaggen.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="metagen.php"><?php echo META_TAG_GENERATOR; ?></a></h3>
				<p>
				<?php echo META_TAG_GENERATOR_DESCRIPTION; ?>
				</p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			
	<div class="clear"></div>
	</div>
	<!-- One row ends -->
	
	<!-- One row -->
	<div class="cpanel_row" >
			
			<!-- One item -->
			<div class="item" align="center">
				<div class="image">
				<a href="metaanalyser.php"><img src="images/icon_metetaganl.png"></a>
				</div>
				<div class="cnt">
				<h3><a href="metaanalyser.php"><?php echo META_TAG_ANALYZER; ?></a></h3>
				<p>
				<?php echo META_TAG_ANALYZER_DESCRIPTION; ?>
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