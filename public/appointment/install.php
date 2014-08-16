<?php
require_once realpath(__DIR__.'/../../Bridge.php');
require_once 'app/config/init.php';

$varaaDb = Bridge::dbConfig();

$prefix = empty($_GET['prefix']) ? '' : $_GET['prefix'];
$owner_id = empty($_GET['owner_id']) ? null : intval($_GET['owner_id']);

$dns = sprintf("mysql:dbname=%s;host=%s", $varaaDb['database'], $varaaDb['host']);
$dbh = new PDO($dns, $varaaDb['username'], $varaaDb['password']);

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

header("location: index.php?as_pf=$prefix&owner_id=$owner_id");
