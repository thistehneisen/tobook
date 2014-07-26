<?php
if ((defined('PHP_SESSION_NONE') && session_status() === PHP_SESSION_NONE) || session_id() === '') {
    session_start();
}

// stripslashes from params
function stripslashes_deep($value) {
    $value = is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
    return $value;
}
ini_set('magic_quotes_runtime', 0);
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}
