<?php session_start(); ?>
<!DOCTYPE html>

<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->

<!--[if IE 7]> <html class="no-js lt-ie9 lt-ie8"> <![endif]-->

<!--[if IE 8]> <html class="no-js lt-ie9"> <![endif]-->

<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->

  <head>

    <meta charset="utf-8">

    <title>Klikajaa</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="description" content="">

    <meta name="author" content="Green.design">





		

	

	<!--  ===================  -->

	<!--  = CSS stylesheets =  -->

	<!--  ===================  -->

	

	<!--  = Twitter Bootstrap =  -->

    <link href="stylesheets/bootstrap.css" rel="stylesheet">

    <!--  = Responsiveness =  -->

    <link rel="stylesheet" href="stylesheets/responsive.css" type="text/css" media="screen" title="no title" />

    <!--  = Custom styles =  -->

    <link rel="stylesheet" href="stylesheets/main.css" />

    <!--  = Revolution Slider =  -->

    <link rel="stylesheet" href="js/rs-plugin/css/settings.css" type="text/css" media="screen" />

    <!--  = LightBox2 =  -->

    <link rel="stylesheet" href="js/lightbox/css/lightbox.css" type="text/css" media="screen" />

    <link rel="stylesheet" type="text/css" href="css/jquery.lightbox-0.5.css" media="screen" />
    
<link href='/asset/css/style.css' type='text/css' rel='stylesheet'>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext' rel='stylesheet' type='text/css'>




	<!--  ===================================================  -->

	<!--  = HTML5 shim, for IE6-8 support of HTML5 elements =  -->

	<!--  ===================================================  -->

    <!--[if lt IE 9]>

      <script src="http://html5shim.googlecode.com/svn/trunk/html5.js"></script>

    <![endif]-->





    <!-- Fav icon -->

    <link rel="shortcut icon" href="favicon.ico">

  </head>



  <body>



     <div class="fullwidthbanner-container-1">

     <div id="links">
		<?php if( isset($_SESSION["session_loginname"]) ){?>
		<div class="topNavigationMenu">
			<a href="websiteList.php" class="selected">Etusivu</a>&nbsp;|&nbsp;
			<a href="usermain.php?succ=msg">Hallintapaneeli</a>&nbsp;|&nbsp;
			<a href="profilemanager.php">Omat tiedot</a>&nbsp;|&nbsp;
			<a href="#" onclick="javascript:openWindow('http://www.klikkaaja.com/userhelp/index.html');">Apua</a>&nbsp;|&nbsp;
			<a href="logout.php">Kirjaudu Ulos</a>
		</div>		
		<?php }else{?>
		<div class="topNavigationMenu">
			<a href="websiteList.php">ETUSIVU</a>&nbsp;|&nbsp;
			<a href="signup.php">REKISTERÖIDY</a>&nbsp;|&nbsp;
			<a href="login.php">KIRJAUDU</a>
		</div>
		<?php } ?>     
     </div>
                        

     <div id="logo"> <img src="images/logo.png"> </div>

     <div id="bxslider">

  <div class="slides">

 

	    <ul class="bxslider">

    <li><img src="images/1.jpg" /></li>

  <li><img src="images/2.jpg" /></li>

  <li><img src="images/3.jpg" /></li>

  <li><img src="images/4.jpg" /></li>

</ul>  </div> 

  </div>

<div id="text-box"><img src="images/text.png"></div>

<div id="nyt"> <a href="../signup.php"><img src="images/button-1.jpg" alt=""></a></div>

  

   </div>



           <div id="gray-line"> </div>  

    <div class="main-content" style="margin-bottom:70px;">
		<?php include("includes/guideBodyNavigation.php"); ?>
	</div>
	<div class="greyDivider"></div>
	<?php include "includes/footArea.php"; ?>	

    	 

<div id="clear"></div>




    <!-- Le javascript

    ================================================== -->

    <!-- Placed at the end of the document so the pages load faster -->

    <!--  ========================================  -->

    <!--  = Uncomment the JS components you need =  -->

    <!--  ========================================  -->

    <script src="js/jquery.min.js"></script>

    <script src="js/bootstrap-transition.js"></script>

    <!-- <script src="js/bootstrap-alert.js"></script> -->

    <!-- <script src="js/bootstrap-modal.js"></script> -->

    <!-- <script src="js/bootstrap-dropdown.js"></script> -->

    <!-- <script src="js/bootstrap-scrollspy.js"></script> -->

    <script src="js/bootstrap-tab.js"></script>

    <!-- <script src="js/bootstrap-tooltip.js"></script> -->

    <!-- <script src="js/bootstrap-popover.js"></script> -->

    <script src="js/bootstrap-button.js"></script>

    <script src="js/bootstrap-collapse.js"></script>

    <!-- <script src="js/bootstrap-carousel.js"></script> -->

    <!-- <script src="js/bootstrap-typeahead.js"></script> -->

    

    

    <!--  ==========  -->

    <!--  = Tweet jQuery plugin for Twitter stream =  -->

    <!--  ==========  -->

    <script src="js/jquery.tweet.js" type="text/javascript" charset="utf-8"></script>

    

    <!--  ==========  -->

    <!--  = Carousel jQuery plugin =  -->

    <!--  ==========  -->

    <script src="js/jquery.carouFredSel-6.1.0-packed.js" type="text/javascript" charset="utf-8"></script>

    

    <!--  ==========  -->

    <!--  = Revolution Slider =  -->

    <!--  ==========  -->

    <script src="js/rs-plugin/js/jquery.themepunch.plugins.min.js" type="text/javascript" charset="utf-8"></script>

    <script src="js/rs-plugin/js/jquery.themepunch.revolution.min.js" type="text/javascript" charset="utf-8"></script>

    

    <!--  ==========  -->

    <!--  = LightBox =  -->

    <!--  ==========  -->

    <script src="js/lightbox/js/lightbox.js" charset="utf-8"></script>

    

    <!--  ==========  -->

    <!--  = Custom JS =  -->

    <!--  ==========  -->

    <script src="plugins/jquery.easing.1.3.js"></script>

    <script src="plugins/jquery.fitvids.js"></script>

    <!-- bxSlider Javascript file -->

    <script src="js/jquery.bxslider.min.js"></script>

    <!-- bxSlider CSS file -->

    <link href="js/jquery.bxslider.css" rel="stylesheet" />

	<script src="js/custom.js" type="text/javascript" charset="utf-8"></script>

    <script src="js/jquery.bxslider.js"></script>    



  </body>

</html>

