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
	<div class="guideTopTitle" style="font-size:60px;">
		<span>Markkinointityökalut &amp; Asiakasrekisteri</span>
	</div>
</div>
<div class="greyDivider"></div>
<div class="guideBody">
	<?php include("includes/guideBodyNavigation.php"); ?>
	<div style="font-weight:bold; font-size:18px; padding-top: 30px;">Yhteydessä asiakkaisiisi</div>
	<div style="color:#999; font-size:18px; padding-top: 10px;">Kaikki asiakasatietosi helposti saatavilla!</div>
	<div style="margin-top:20px;"><a href="#"><img src="/asset/image/loyalityBtnAloita.png" style="width:300px;"/></a></div>

	<div style="width:80%;margin-left: 10%;text-align: left; margin-top: 30px; margin-bottom: 20px;" >
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">asiakastiedot</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">sms markkinointi</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">lisämyynti</span>
			</div>				
		</div>
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Vaivatonta markkinointia</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">sähkopostimarkkinointi</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">asiakashistoria</span>
			</div>				
		</div>
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">erittäin helppokäyttöinen</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">asiakasryhmät</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">tunnista parhaat asiakkaasi</span>
			</div>				
		</div>
		<div class="clearboth"></div>		
	</div>
	
	<div style="background:#f6841e;color:#FFF;padding-top:30px; padding-bottom:40px;">
		<span style="font-size:40px;">Markkinointityökalut &amp; Asiakasrekisteri</span>
		<p style="margin-bottom:2px;margin-top:5px;width:70%;margin-left:15%;">
			Elämä nykypäivänä on hektisempää kuin koskaan aiemmin.<br/>
			Ihmiset ovat kiireisiä, ja kaiken tulisi olla arjen sujuvuuden kannalta mahdollisimman helppoa sekä nopeaa. <br/>
			Sinä määräät liiketoimintasi suunnan ja rytmin, me tarjoamme markkinoiden edistyneimmät ja <br/>
			helppokäyttöisimmät työkalut hurjimpienkin visioidesi toteuttamiseen!
		</p>
	</div>

	<div class="marketingBackground01">
		<div class="floatleft" style="width:40%;margin-left:5%;">
			<img src="/asset/image/marketingBack01.png" style="width:500px;"/>
		</div>
		<div class="floatleft" style="width:55%;">
			<div style="width:80%;margin-left:10%;padding-top:30px;">
				<span style="color:#000;font-size:36px;font-weight:normal;">Asiakastiedot yhdessä paikassa</span>
			</div>
			<div style="width:300px;height:300px; border-radius: 150px;background:#f6851f;margin-top:40px;margin-left:170px;color:#FFF;font-size:18px;font-weight:normal;">
				<div style="padding-top:100px;line-height:30px;">
				Asiakastietosi ovat varmassa<br/>
				tallessa. Voit helposti ylläpitää ja <br/>
				muokata asiakaskortteja, sekä <br/>
				kerätä tietoja asiakkaistasi.<br/>
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
	</div>	
	
	<div class="marketingBackground02">
		<div class="floatleft" style="width:40%; margin-right:3%;">
			<div style="width:300px;height:300px; border-radius: 150px;background:#f6851f;margin-top:140px;margin-left:170px;color:#FFF;font-size:18px;font-weight:normal;">
				<div style="padding-top:100px;line-height:30px;">
				Pidä yhteyttä asiakkaisiisi<br/>
				helppokäyttöisen sähköposti- tai <br/>
				sms-markkinointityökalun avulla.<br/>
				</div>
			</div>
		</div>
		<div class="floatleft" style="width:57%">
			<div style="margin-left:10%;padding-top:80px;">
				<span style="color:#FFF;font-size:36px;font-weight:normal;">Vaivatonta  markkinointia</span>
			</div>			
		</div>
		<div class="clearboth"></div>	
	</div>
	
	<div class="marketingBackground03">
		<div class="floatleft" style="width:40%;margin-left:5%;">
			<img src="/asset/image/marketingBack03.png" style="width:500px;"/>
		</div>
		<div class="floatleft" style="width:55%;">
			<div style="width:80%;margin-left:10%;padding-top:30px;">
				<span style="color:#000;font-size:36px;font-weight:normal;">Kasvata myyntiä!</span>
			</div>
			<div style="width:300px;height:300px; border-radius: 150px;background:#f6851f;margin-top:40px;margin-left:170px;color:#FFF;font-size:18px;font-weight:normal;">
				<div style="padding-top:100px;line-height:30px;	">
				Markkinointiautomaatio huolehtii<br/>
				säännöllisestä markkinoinnista<br/>
				asiakkaillesi ja auttaa sinua<br/>
				kasvattamaan myyntiäsi!<br/>
				</div>
			</div>
		</div>
		<div class="clearboth"></div>
	</div>

	<div class="marketingBackground04">
		<div class="floatleft" style="width:60%;">
			<div style="margin-left:170px;padding-top:80px;text-align:left;">
				<span style="color:#FFF;font-size:36px;font-weight:normal;">Kasvata myyntiä!</span>
			</div>
			<div style="width:300px;height:300px; border-radius: 150px;background:#f6851f;margin-top:100px;margin-left:170px;color:#FFF;font-size:18px;font-weight:normal;">
				<div style="padding-top:100px;line-height:30px;">
				Markkinointityökalulla tavoitat<br/>
				asiakkaasi silloin kun haluat<br/>
				keskimäärin 20minuutissa!<br/>
				</div>
			</div>
		</div>
		<div class="clearboth"></div>	
	</div>	
</div>
<div class="greyDivider"></div>

<?php include "includes/footArea.php"; ?>

</body>
</html>