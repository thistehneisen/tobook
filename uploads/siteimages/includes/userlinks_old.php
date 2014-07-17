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
// |          									                          |
// +----------------------------------------------------------------------+
?>
<script>
function openWindow(url){
	window.open(url,'Help','left=20,top=20,width=700,height=500,toolbar=0,resizable=1' );
}
</script>
<table><tr><td align=center>
<a href=<?php echo $currentloc?>index.php class=toplinks>Home</a>&nbsp;<img src="images/sepaation.gif" width="3" height="10">&nbsp;
<a href=<?php echo $currentloc?>usermain.php class=toplinks>Main Menu</a>&nbsp;<img src="images/sepaation.gif" width="3" height="10">&nbsp;
<a href=<?php echo $currentloc?>logout.php class=toplinks>Logout</a>&nbsp;<img src="images/sepaation.gif" width="3" height="10">&nbsp;
<a href="#" class=toplinks onClick="javascript:openWindow('userhelp/index.html');" title="Open User Help Window">Help</a>
</td></tr></table>