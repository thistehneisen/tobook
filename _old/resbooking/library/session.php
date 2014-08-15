<?php
@session_start();

// Auto login here we go
require_once("../../includes/configsettings.php");
error_reporting(E_ALL);
$owner_id = intval($_SESSION['owner_id']);
$dns = sprintf("mysql:dbname=%s;host=%s", MYSQL_DB, MYSQL_HOST);

try {
    $dbh = new PDO($dns, MYSQL_USERNAME, MYSQL_PASSWORD);

    // Get user information
    $stm = $dbh->prepare('SELECT * FROM `rb_users` WHERE `owner_id` = ?');
    $stm->execute([$owner_id]);
    $user = $stm->fetch();

    // Set in session for auto-login
    $_SESSION['admin_user'] = $user;

} catch (Exception $ex) {
    
}

header("location: index.php");
