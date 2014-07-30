<?php session_start(); ?><!DOCTYPE html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!-->
<html class="no-js">
<!--<![endif]-->
<head>
    <meta charset="utf-8">
    <title>Klikajaa</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">

    <link href="stylesheets/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="stylesheets/responsive.css" type="text/css" media="screen" title="no title" />
    <link rel="stylesheet" href="stylesheets/main.css" />
    <link rel="stylesheet" href="js/rs-plugin/css/settings.css" type="text/css" media="screen" />
    <link rel="stylesheet" href="js/lightbox/css/lightbox.css" type="text/css" media="screen" />
    <link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
    <link href='/asset/css/style.css' type='text/css' rel='stylesheet'>
    <link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext' rel='stylesheet' type='text/css'>
    <link href="js/jquery.bxslider.css" rel="stylesheet" />

    <!--[if lt IE 9]>
      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <link rel="shortcut icon" href="favicon.ico">
</head>
<body>
    <div class="fullwidthbanner-container-1">
        <div id="links">
            <?php if( isset($_SESSION[ "session_loginname"]) ){?>
            <div class="topNavigationMenu">
                <a href="usermain.php?succ=msg" class="selected">Etusivu</a>&nbsp;|&nbsp;
                <a href="usermain.php?succ=msg">Hallintapaneeli</a>&nbsp;|&nbsp;
                <a href="profilemanager.php">Omat tiedot</a>&nbsp;|&nbsp;
                <a href="#" onclick="javascript:openWindow('http://www.klikkaaja.com/userhelp/index.html');">Apua</a>&nbsp;|&nbsp;
                <a href="logout.php">Kirjaudu Ulos</a>
            </div>
            <?php }else{?>
            <div class="topNavigationMenu">
                <a href="index.php">ETUSIVU</a>&nbsp;|&nbsp;
                <a href="signup.php">REKISTERÖIDY</a>&nbsp;|&nbsp;
                <a href="login.php">KIRJAUDU</a>
            </div>
            <?php } ?>
        </div>

        <div id="logo">
            <img src="images/logo.png">
        </div>

        <div id="bxslider">
            <div class="slides">
                <ul class="bxslider">
                    <li>
                        <img src="images/1.jpg" />
                    </li>
                    <li>
                        <img src="images/2.jpg" />
                    </li>
                    <li>
                        <img src="images/3.jpg" />
                    </li>
                    <li>
                        <img src="images/4.jpg" />
                    </li>
                </ul>
            </div>
        </div>

        <div id="text-box">
            <img src="images/text.png">
        </div>

        <div id="nyt">
            <a href="../signup.php">
                <img src="images/button-1.jpg" alt="">
            </a>
        </div>
    </div>

    <div id="gray-line"></div>
    <div class="main-content" style="margin-bottom:70px;">
        <?php include( "includes/guideBodyNavigation.php"); ?>
    </div>
    <div class="greyDivider"></div>
    <?php include "includes/footArea.php"; ?>

    <div id="clear"></div>


    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
    <script src="js/jquery.bxslider.min.js"></script>
    <script>
    $(document).ready(function () {
        $('.bxslider').bxSlider();
    });
    </script>
</body>
</html>