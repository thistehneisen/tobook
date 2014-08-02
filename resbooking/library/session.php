<?php
    if (!headers_sent())
    {
        session_name('StivaSoft');
        @session_start();
    }

    $username = $_GET['username'];
    $owner_id = $_GET['owner_id'];

    $_SESSION['session_loginname'] = $username;
    $_SESSION['owner_id'] = $owner_id;
    header("location: index.php");
