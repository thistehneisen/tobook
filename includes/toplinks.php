<script>
function openWindow(url){
    window.open(url,'Help','left=20,top=20,width=700,height=500,toolbar=0,resizable=1' );
}
</script>
<?php
if(isset($_SESSION["session_loginname"]) || $_SESSION["session_loginname"]!="") {
?>
<div class="welcome">
        <?php echo DASHBOARD_WELCOME; ?> <span ><?php echo ucwords($_SESSION['session_loginname']);?></span>
    </div>
<?php } ?>
<div class="hmnavstyles">
    <ul>
        <li><a href="index.php" class="<?php echo ($curTab=='home')?'active':''?>"><?php echo TOP_LINKS_HOME; ?></a></li>
        <?php
        if(!isset($_SESSION["session_loginname"]) || $_SESSION["session_loginname"]=="") {
        ?>
            <li><a href="signup.php" class="<?php echo ($curTab=='signup')?'active':''?>"><?php echo TOP_LINKS_SIGNUP; ?></a></li>
            <li><a href="login.php" class="<?php echo ($curTab=='login')?'active':''?>"><?php echo LOGIN_TITLE; ?></a></li>
        <?php
        }else {
        ?>
            <li><a href="<?php echo BASE_URL?>usermain.php" class="<?php echo ($curTab=='dashboard')?'active':''?>" ><?php echo TOP_LINKS_DASHBOARD; ?></a></li>
            <li><a href="<?php echo BASE_URL?>profilemanager.php" class="<?php echo ($curTab=='profile')?'active':''?>"><?php echo TOP_LINKS_MY_ACCOUNT; ?></a></li>
            <li><a href="#" onClick="javascript:openWindow('<?php echo BASE_URL ?>userhelp/index.html');" title="<?php echo HEADER_HELP_TITLE; ?>"><?php echo HEADER_HELP; ?></a></li>
            <li><a href="<?php echo BASE_URL?>logout.php" class="<?php echo ($curTab=='logout')?'active':''?>"><?php echo TOP_LINKS_LOGOUT; ?></a></li>
        <?php
        }
        ?>
    </ul>
</div>






