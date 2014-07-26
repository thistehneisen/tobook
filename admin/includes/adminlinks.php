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
<script>
function openWindow(url){
	window.open(url,'Help','left=20,top=20,width=700,height=500,toolbar=0,resizable=1' );
}
</script>
<?php //echopre($curTab); ?>
<ul>
    <li><a href="dashboard.php" class="<?php echo ($curTab=='dashboard')?'active':''?>"><?php echo DASHBOARD;?></a></li>
    <li><a href="payment.php" class="<?php echo ($curTab=='payments')?'active':''?>" ><?php echo PAYMENTS;?></a></li>
    <li><a href="sitemanager.php" class="<?php echo ($curTab=='sites')?'active':''?>" ><?php echo SITES;?></a></li>
    <li><a href="usermanager.php" class="<?php echo ($curTab=='users')?'active':''?>" ><?php echo USERS?></a></li>
    <li><a href="templatemanager.php" class="<?php echo ($curTab=='template_manager')?'active':''?>" ><?php echo TEMPLATES;?></a></li>
    <li><a href="cmslisting.php" class="<?php echo ($curTab=='contents')?'active':''?>"><?php echo CONTENTS;?></a></li>
    <li><a href="settings.php" class="<?php echo ($curTab=='settings')?'active':''?>"><?php echo SETTINGS;?></a></li>
    <!--li><a href="adminmain.php" class="<?php //echo ($curTab=='adminmain')?'active':''?>">Main Menu</a></li-->
    <!-- <li><a href=gallerymanager.php class=toplinks>Gallery Manager</a>&nbsp;<img src="../images/sepaation.gif" width="3" height="10">&nbsp;</li> -->
    
    
    
</ul>

