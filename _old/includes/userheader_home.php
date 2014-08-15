<?php
if(($_SESSION["session_loginname"] == "") && basename($_SERVER["REQUEST_URI"]) != "login.php") {
    header("location:login.php");
    exit;
}
include "applicationheader.php";
?>
<link href="style/common.css" type="text/css" rel="stylesheet">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
</head>
