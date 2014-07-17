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
<TITLE><?php echo getSettingsValue('site_name'); ?> - Appointment Scheduler</TITLE>
<META name="description" content="Appointment Scheduler">
<META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">


<link href="favicon.ico" type="image/x-icon" rel="icon"> 
<link href="favicon.ico" type="image/x-icon" rel="shortcut icon">


<link href='/asset/css/style.css' type='text/css' rel='stylesheet'>
<link href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext' rel='stylesheet' type='text/css'>
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
		<span>Ajanvaraus</span>
	</div>
	<div class="guideTopSubTitle">
		Vastaanota varauksia verkosta
	</div>
</div>
<div class="greyDivider"></div>
<div class="guideBody">
	<?php include("includes/guideBodyNavigation.php"); ?>
	<div style="font-weight:bold; font-size:20px; padding-top: 30px;">Vaastanota varauksia 24/7</div>
	<div style="color:#999; font-size:20px; padding-top: 10px;">
		Varauksen tekeminen onnistuu muutamalla helpolla ikkauksela
	</div>
	
	<div style="width:80%;margin-left: 10%;text-align: left; margin-top: 30px; margin-bottom: 20px;" >
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Vastaanota varauksia verkosta</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Monipuoliset raportointityökalut</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Työaikasuunnittelu</span>
			</div>				
		</div>
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Käytettävissä kaikkialla</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Tekstiviesti-ilmotukset</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Asiakasrekisteri</span>
			</div>				
		</div>
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Erittäin helppokäyttöinen</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Tekijäkohtaiset kestot</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Markinointityäkalut</span>
			</div>				
		</div>
		<div class="clearboth"></div>		
	</div>
	<div style="margin-top:30px;"><img src="/asset/image/appPCs.png"/></div>
	<div style="color:#999; font-size:16px; padding-top: 10px;margin-bottom:30px;">
		<p style="margin-bottom:2px;margin-top:5px;">Elämä on nykypäivänä hektisempää kuin koskaan aiemmin. Ihmiset ovat kiireisempiä ja kaiken tulisi olla helppoa ja nopeaa.</p>
		<p style="margin-bottom:2px;margin-top:5px;">Sähköinen ajanvaraus palvelee asiakkaitasi ympäri vuorokauden vaikka et itse olisi töissä.</p>
		<p style="margin-bottom:2px;margin-top:5px;">Sähköisen kalenterin kautta kuluttaja voi helposti etsiä kalenteristasi hänelle parhaiten sopivan ajan.</p>
		<p style="margin-bottom:2px;margin-top:5px;">Yrittäjänä pääset sähköiseen kalenteriin käsiksi työpaikalta, kotoa tai vaikka lounasravintolasta.</p>
		<p style="margin-bottom:2px;margin-top:5px;">Verkkopohjainen kalenteri vaatii ainoastaan pääsyn internettiin.</p>
	</div>	
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb01.png" style="width:100%;"></div>
		<div class="floatleft" style="margin-left:5%;width:60%;">
			<p style="font-size:25px;font-weight:bold;padding-left:30px;">Aloita varausten vastaanottaminen muutamassa minuutissa!</p>
			<p style="color:#999;">Ajanvarauksen käyttönottaminen on helppoa ja yksinkertaistaista! Kokeile!</p>
		</div>
		<div class="clearboth"></div>
	</div>
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:60%;">
			<p style="font-size:25px;font-weight:bold;">Vastaanota varauksia kotisivuiltasi!</p>
			<p style="color:#999;padding-left:15px;">Ajanvarausjärjestelmän avulla helpotat sinun ja asiakkaidesi välistä kommunikointia.</p>
		</div>
		<div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb02.png" style="width:100%;"></div>
		<div class="clearboth"></div>
	</div>
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb03.png" style="width:100%;"></div>
		<div class="floatleft" style="margin-left:5%;width:60%;">
			<p style="font-size:25px;font-weight:bold;padding-left:30px;">Varaukset myös facebookista!</p>
			<p style="color:#999;">Sosiaalisen median vaikutus jatkaa kasvamista joten on tärkeää hyödyntää kaikkimahdollisuudet jonka vuoksi suosittelemme lisäämään varauskalenterin myös omille facebook sivuillesi helposti!</p>
		</div>
		<div class="clearboth"></div>
	</div>
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:60%;">
			<p style="font-size:25px;font-weight:bold;">Brändisi mukainen ulkoasu!</p>
			<p style="color:#999;padding-left:15px;">Ajanvarauksen ulkoasu on mahdollista suunnitella toiveidesi mukaan!</p>
		</div>
		<div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb04.png" style="width:100%;"></div>
		<div class="clearboth"></div>
	</div>
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb05.png" style="width:100%;"></div>
		<div class="floatleft" style="margin-left:5%;width:60%;">
			<p style="font-size:25px;font-weight:bold;padding-left:30px;">Joustavat ominaisuudet!</p>
			<p style="color:#999;">Voit räätälöidä työajat, palveluidenkestot, resurssit yms. tarpeidesi mukaan!</p>
		</div>
		<div class="clearboth"></div>
	</div>
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:60%;">
			<p style="font-size:25px;font-weight:bold;">Monipuoliset raportointityökalut</p>
			<p style="color:#999;padding-left:15px;">Monipuolisten raportointityökalujen ansioista sinun on mahdollista saada ensimmäistä kertaa liiketoimintasi tunnuslukuja.</p>
		</div>
		<div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb02.png" style="width:100%;"></div>
		<div class="clearboth"></div>
	</div>
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:35%;"><img src="/asset/image/appThumb06.png" style="width:100%;"></div>
		<div class="floatleft" style="margin-left:5%;width:60%;">
			<p style="font-size:25px;font-weight:bold;padding-left:30px;">Asiakasrekisteri</p>
			<p style="color:#999;">Asiakasrekisterin avulla asiakkaasi ovat kätevästi yhdessä paikassa.</p>
		</div>
		<div class="clearboth"></div>
	</div>
	
	<div style="width:76%;margin-left: 12%;">
		<div class="floatleft" style="width:60%;">
			<p style="font-size:25px;font-weight:bold;">Mahtavat markkinointityökalut</p>
			<p style="color:#999;padding-left:15px;">Asiakasrekisterin avulla asiakkaasi ovat kätevästi yhdessä paikassa.</p>
		</div>
		<div class="floatleft" style="width:35%;margin-left:5%;"><img src="/asset/image/appThumb07.png" style="width:100%;"></div>
		<div class="clearboth"></div>
	</div>				
	<div style="margin-top:50px;">&nbsp;</div>	
</div>

<div class="greyDivider"></div>

<?php include "includes/footArea.php"; ?>

</body>
</html>