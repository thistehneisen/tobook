<?php
require_once("../../DB_Connection.php");
require_once("../common/functions.php");
require_once base_path().'/Bridge.php';

$result = "success";
$error = "";
$msg = "";
$data = array();

$username = $_POST['username'];
$password = $_POST['password'];

$user = Bridge::login($username, $password);
if ($user !== false) {
    $customerId = $user->id;
    $customerToken = base64_encode(base64_encode($customerId));
    $data['customerToken'] = $customerToken;
} else {
    $result = "failed";
    $msg = "User authentication failed.";
    $error = "LC001";
}

$data['msg'] = $msg;
$data['result'] = $result;
$data['error'] = $error;
header('Content-Type: application/json');
echo json_encode($data);
?>
