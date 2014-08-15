<script>
function openWindow(url){
	window.open(url,'Help','left=20,top=20,width=700,height=500,toolbar=0,resizable=1' );
}
</script>
<?php
if($_SESSION["session_rootserver"])
    $currentloc=$_SESSION["session_rootserver"]."/";
else
    $currentloc=BASE_URL;
?>

<div class="hmnavstyles">
    <ul>

    <!--<li><a href="<?php //echo $currentloc?>index.php">Home</a></li>
    <li><a href="<?php //echo $currentloc?>usermain.php">Main Menu</a></li> -->
    <li><a href="<?php echo $currentloc?>usermain.php"><?php echo TOP_LINKS_HOME; ?></a></li>
    <li><a href="<?php echo $currentloc?>logout.php"><?php echo TOP_LINKS_LOGOUT; ?></a></li>
    <li><a href="#" onClick="javascript:openWindow('<?php echo $currentloc?>userhelp/index.html');" title="<?php echo HEADER_HELP_TITLE; ?>"><?php echo HEADER_HELP; ?></a></li>
    </ul>
</div>

