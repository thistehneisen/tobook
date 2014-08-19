<?php

@session_start();
require_once realpath(__DIR__.'/../../Bridge.php');
$varaaDb = Bridge::dbConfig();

if (!Bridge::hasOwnerId()) {
    @session_destroy();
    echo <<< JS
<script>
window.parent.location = '/auth/login';
</script>
JS;
    exit;
}

$owner_id = intval($_SESSION['owner_id']);
$dns = sprintf("mysql:dbname=%s;host=%s", $varaaDb['database'], $varaaDb['host']);

try {
    $dbh = new PDO($dns, $varaaDb['username'], $varaaDb['password']);

    // Get user information
    $stm = $dbh->prepare('SELECT * FROM `rb_users` WHERE `owner_id` = ?');
    $stm->execute([$owner_id]);
    $user = $stm->fetch();

    // Set in session for auto-login
    $_SESSION['admin_user'] = $user;

} catch (Exception $ex) {
}

header("location: index.php");
