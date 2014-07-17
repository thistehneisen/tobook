<?php
	session_start(); 
    if((strpos($_SERVER['REQUEST_URI'], "admin") != '') ){
        include_once "../includes/config.php"; 
    }else{ 
        include_once "includes/config.php";
    }
    include_once "function.php";
    $theme = getSettingsValue('theme');
    $_SESSION["session_style"] = $theme;

    $logo = getSettingsValue('Logourl');
?>
<html>
<head>
<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
<meta content="utf-8" http-equiv="encoding">
<TITLE><?php echo getSettingsValue('site_name'); ?> - Web Site List</TITLE>
<META name="description" content="Appointment Scheduler">
<META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">


<link href="favicon.ico" type="image/x-icon" rel="icon"> 
<link href="favicon.ico" type="image/x-icon" rel="shortcut icon">

<script src="//code.jquery.com/jquery-1.10.2.js"></script>

<link href='/asset/css/style.css' type='text/css' rel='stylesheet'>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext' rel='stylesheet' type='text/css'>
<script>
	$(document).ready( function(){
		$("div.weblistItemIcon img").mouseover( function( ){
			$(this).parents("div").eq(0).find(".weblistItemIconThumb").fadeIn();
		});
		$("div.weblistItemIcon img").mouseout( function( ){
			$(this).parents("#weblistItems").find("div.weblistItemIconThumb").fadeOut();
		});		
	});
</script>
</head>
<body style="padding:0px; margin:0px;">
		<?php if( isset($_SESSION["session_loginname"]) ){?>
		<div class="topNavigationMenu">
			<a href="websiteList.php">Etusivu</a>&nbsp;|&nbsp;
			<a href="usermain.php?succ=msg" class="navSelected">Hallintapaneeli</a>&nbsp;|&nbsp;
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
<div class="guideTopArea">
	<div class="guideTopVaraa">
		<span>varaa</span><span class="fontBlack">.com</span>
	</div>
	<div class="guideTopTitle">
		<span>Asiakkaidemme kotisivut</span>
	</div>
	<div class="guideTopSubTitle">
		Tyylikkäät ensiostokseen kannustavat kotisivut!
	</div>
</div>
<div class="greyDivider"></div>
<div class="guideBody">
	<div style="font-weight:bold; font-size:20px; padding-top: 30px;">Meidän intohimomme on luoda laadukkaita kotisivuja joista asiakkaamme voivat olla ylpeitä.</div>
	<div style="color:#999; font-size:20px; padding-top: 10px;">
		Panosta yrityksen kotisivun ulkoasuun, niin kuin panostat myymälän ja liiketilan tyylikkyyteen ja viihtyvyyteen.
	</div>
	<div style="margin-top:50px;margin-bottom:45px;"><a href="#"><img src="/asset/image/btnTutustu.png" style="width:300px;"/></a></div>
	<div id="weblistItems" style="width:80%;margin-left:10%;">
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb01.png" style="width:60%;"/>
				<p>Rauman Keskus-Apteekki</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb01.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb02.png" style="width:60%;"/>
				<p>Boutique Cameo</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb02.png" style="width:100%;"/></div>
			</div>
		</a>		
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb03.png" style="width:60%;"/>
				<p>Ravintola Torni</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb01.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb04.png" style="width:60%;"/>
				<p>West Beverly Ravintola</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb01.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb05.png" style="width:60%;"/>
				<p>Trattoria Il Faro</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb05.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb06.png" style="width:60%;"/>
				<p>BM Day Spa</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb06.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb07.png" style="width:60%;"/>
				<p>Myllypuron Apteekki</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb07.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb08.png" style="width:60%;"/>
				<p>Kauneushuone Rilla</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb01.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb09.png" style="width:60%;"/>
				<p>Pasilan Hiuspiste</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb09.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb10.png" style="width:60%;"/>
				<p>Ravintola Thalassa</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb01.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb11.png" style="width:60%;"/>
				<p>Hiustrendi</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb11.png" style="width:100%;"/></div>
			</div>
		</a>		
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb12.png" style="width:60%;"/>
				<p>Riitan Salonki</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb12.png" style="width:100%;"/></div>
			</div>
		</a>

		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb13.png" style="width:60%;"/>
				<p>M.Sport Liikuntakeskus</p>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb13.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb14.png" style="width:60%;"/>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb14.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb15.png" style="width:60%;"/>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb15.png" style="width:100%;"/></div>
			</div>
		</a>
		<a href="#">
			<div class="weblistItemIcon">
				<img src="/asset/image/homeWeb16.png" style="width:60%;"/>
				<div class="weblistItemIconThumb"><img src="/asset/image/thumbWeb16.png" style="width:100%;"/></div>
			</div>
		</a>								
		 							
						
		<div class="clearboth"></div>
	</div>
   
</div>

<div class="greyDivider"></div>

<?php include "includes/footArea.php"; ?>

</body>
</html>