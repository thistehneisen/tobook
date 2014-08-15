<?php
require_once("../../includes/configsettings.php");
require_once('app/config/init.php');

$prefix = $_GET['prefix'];
$owner_id = intval($_GET['owner_id']);

setcookie("as_db_version" . $prefix, "1", time()+3600, "/", "");

$dns = sprintf("mysql:dbname=%s;host=%s", MYSQL_DB, MYSQL_HOST);
$dbh = new PDO($dns, MYSQL_USERNAME, MYSQL_PASSWORD);

$stm = $dbh->prepare("SELECT COUNT(*) as count FROM as_users WHERE owner_id = :owner_id");
$stm->execute(array(':owner_id' => $owner_id));

$result = $stm->fetch();

if(intval($result['count']) == 0){
    try {
        $stm  = $dbh->prepare(sprintf($sql, $owner_id));
        $stm->execute();
    } catch (Exception $ex){
        echo $ex->getMessage();
    }
}

header("location: index.php?as_pf=$prefix&owner_id={$owner_id}");

?>
