<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>	        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php


if(($_SESSION["session_loginname"] == "") && basename($_SERVER["REQUEST_URI"]) != "login.php") {
    header("location:login.php");
    exit;
}
/*
if($_SESSION["session_loginname"] != "" && $_SERVER['HTTP_REFERER'] == "" && basename($_SERVER["REQUEST_URI"]) != "usermain.php" && basename($_SERVER['REQUEST_URI']) != "publishpage.php"  && basename($_SERVER['REQUEST_URI']) != "sitemanager.php"){
	header("location:usermain.php");
	exit;	
}
*/
include "applicationheader.php";
?>
<link href="style/common.css" type="text/css" rel="stylesheet">
</head>
<body>

    <div class="wrps_main">
        <!-- top hrd wrap start -->
        <div class="inner_top_wrps">

            <div class="tpwrp_inner">
                <div class="logo_tpsections">
                    <a href="index.php"><img src ="<?php echo BASE_URL.$logo; ?>" border=0></a>
                    <div class="clear"></div>
                </div>
                <div class="top_rightsection">
                    <!-- ///start of header links.........................................    -->
                    <?php
                    include "toplinks.php";
                    ?>

                    <!-- ///end of headerlinks....................................................    -->

                    <div class="clear"></div>
                </div>
                <div class="clear"></div>
            </div>

            <div class="clear"></div>
        </div>
        <!-- top hrd wrap start -->


        <!-- Content area home start -->


        <div class="cntarea_dvs">

            <div class="cnt_innerdvs">
