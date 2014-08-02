<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
?>
<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";
?>
<div>
    <?php   
    $table_prefix = $_SESSION["session_loginname"];
    $table_prefix = str_replace("-", "", $table_prefix);
    
    $owner_id = intval($_SESSION['owner_id']);
    $sql = sprintf("SELECT COUNT(*) FROM rs_users WHERE owner_id = %d", $owner_id);
    if(mysql_result(mysql_query($sql), 0, 0)==1){
        $plugins_url = "http://".$_SERVER['SERVER_NAME']."/resbooking/library/session.php?owner_id={$owner_id}&username=".$table_prefix;
    }
    else{
        $plugins_url = "http://".$_SERVER['SERVER_NAME']."/resbooking/install.php?owner_id={$owner_id}&username=".$table_prefix;
    }
    global $userusername;
    $userusername = $table_prefix;
    ?>  
    <iframe onLoad="calcHeight();" id="iFrame" width="100%" src="<?php echo $plugins_url; ?>"  height="1200" frameborder="0"></iframe>  
</div>

<?php include "includes/userfooter.php"; ?>
