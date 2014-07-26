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
<TITLE><?php echo getSettingsValue('site_name'); ?> - Appointment
	Scheduler</TITLE>
<META name="description" content="Appointment Scheduler">
<META name="keywords"
	content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">


<link href="favicon.ico" type="image/x-icon" rel="icon">
<link href="favicon.ico" type="image/x-icon" rel="shortcut icon">


<link href='/asset/css/style.css' type='text/css' rel='stylesheet'>
<link
	href='http://fonts.googleapis.com/css?family=Comfortaa:400,300,700&subset=latin,cyrillic,latin-ext,greek,cyrillic-ext'
	rel='stylesheet' type='text/css'>
</head>
<body style="padding: 0px; margin: 0px;">
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
			<span>Kantiskortti</span>
		</div>
		<div class="guideTopSubTitle">Yksi kanta-asiakaskortti, jonka avulla
			nauttia satojen yritysten eduista!</div>
	</div>
	<div class="greyDivider"></div>
	<div class="guideBody">
		<?php include("includes/guideBodyNavigation.php"); ?>
		<div style="font-weight: bold; font-size: 18px; padding-top: 30px;">Verkkopohjaisen
			kassajärjestelmän avulla luot asiakkaillesi helposti asianmukaiset
			kuitit, sekä hoidat raportit suoraan kirjanpitäjällesi.</div>
		<div style="color: #999; font-size: 18px; padding-top: 10px;">Tule
			mukaan kehittämään ja optimoimaan liiketoimintasi tuottavuus uudelle
			tasolle tarkkojen seuranta- & raportointityökalujemme avulla!</div>
		<div style="margin-top: 20px;">
			<a href="#"><img src="/asset/image/loyalityBtnAloita.png"
				style="width: 300px;" /> </a>
		</div>

		<div
			style="width: 80%; margin-left: 10%; text-align: left; margin-top: 30px; margin-bottom: 20px;">
			<div style="width: 33%; margin-top: 30px;" class="floatleft">
				<div>
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Helppokäyttöinen
						kassajärjestelmä</span>
				</div>
				<div style="margin-top: 15px;">
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Monipuolinen
						varastonhallinta</span>
				</div>
				<div style="margin-top: 15px;">
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Seuraa kehitystä</span>
				</div>
			</div>
			<div style="width: 33%; margin-top: 30px;" class="floatleft">
				<div>
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Kirjanpitovalmiit raportit</span>
				</div>
				<div style="margin-top: 15px;">
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Piste/Leimatyökalu</span>
				</div>
				<div style="margin-top: 15px;">
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Kuittitulostin</span>
				</div>
			</div>
			<div style="width: 33%; margin-top: 30px;" class="floatleft">
				<div>
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Tekijäkohtaiset kuitit</span>
				</div>
				<div style="margin-top: 15px;">
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Kuitit tallessa</span>
				</div>
				<div style="margin-top: 15px;">
					<img src="/asset/image/iconArrow.png"
						style="vertical-align: middle; width: 30px; height: 30px;" />&nbsp;<span
						style="font-size: 18px; color: #999;">Kassalaatikko & Kassa</span>
				</div>
			</div>
			<div class="clearboth"></div>
		</div>

		<div
			style="background: #f6841e; color: #FFF; padding-top: 30px; padding-bottom: 40px;">
			<span style="font-size: 40px;">Helppokäyttöinen kassa apunasi</span>
			<p style="margin-bottom: 2px; margin-top: 5px; width: 80%; margin-left: 10%; font-size:18px;">
				Nyt sinulla on mahdollisuus saada käyttöösi monipuolinen, mutta
				helppokäyttöinen kassajärjestelmä.<br /> Vuonna 2014 voimaan tulleen
				kuittipakko -uudistuksen myötä yrityksien tulee poikkeuksetta
				tarjota asiakkailleen asianmukainen, ostotapahtumien erittelyn
				sisältävä kuitti ostotapahtumasta.<br /> Perinteiset
				kassajärjestelmät sekä käsinkirjoitetut kuitit ovat kankeita
				käytössä, eivätkä paikoin taivu uusien määräysten vaatimiin
				standardeihin.<br /> Tästä johtuen on aika siirtää liiketoimintasi
				nykyaikaan, helppokäyttöisten työkalujemme avulla.
			</p>
		</div>

		<div class="cashierBackground01">
			<div class="floatleft">
				<div style="width: 400px; height: 400px; border-radius: 200px; background: #FFF; margin-top: 100px; margin-bottom: 100px; margin-left: 170px; color: #999; font-size: 17px; font-weight: normal;">
					<div style="padding-top: 90px;">Kassalaatikko &amp; Kassa</div>
					<div style="padding-top: 10px;">
						Vaikka yritykseltäsi ei löydy<br /> kassalaatikkoa tai
						kassakonetta,<br /> ei syytä huoleen.<br /> Toimitamme yritykseesi
						avaimet käteen<br /> -perjaatteella käyttövalmiina ja<br />
						asennettuna pakettina kassajärjestelmän<br /> kuittitulostimen
						sekä kassalaatikon.
					</div>
				</div>
			</div>
			<div class="clearboth"></div>
		</div>

		<div class="cashierBackground02">
			<span style="color: #000; font-size: 36px; font-weight: normal;">Selkeät
				raportit - Järjestelmästä saat selkeät kirjanpitovalmiit raporti</span>
			<div style="margin-top: 70px; width: 80%; margin-left: 10%;">
				<div class="floatleft" style="width: 58%;">
					<img src="/asset/image/cashierBack02.png" style="width: 100%;">
				</div>
				<div class="floatleft"
					style="margin-left: 5%; margin-top: 20px; text-align: left; font-size: 20px; width: 37%;">
					<div
						style="width: 350px; height: 350px; border-radius: 175px; background: #eb8b2e; margin-top: 0px; margin-left: 30px; color: #FFF; font-size: 16px; font-weight: normal; text-align: center;">
						<div style="padding-top: 100px;">Kirjanpitovalmiit raportit</div>
						<div style="padding-top: 10px;">
							Järjestelmä luo sinulle kirjanpitovalmiit<br /> raportit jotka
							voit toimittaa suoraan<br /> kirjanpitäjällesi.<br /> Halutessasi
							saat järjestelmästä katsottua <br /> myös päivä-, kuukausi-, sekä<br />
							tuotekohtaisia raportteja.<br />
						</div>
					</div>
				</div>
				<div class="clearboth"></div>
			</div>
		</div>

		<div class="cashierBackground03">
			<div style="width: 80%; margin-left: 10%; padding-top: 30px;">
				<span style="color: #FFF; font-size: 36px; font-weight: normal;">Tekijäkohtaiset
					kuitit - Tekijäkohtaiset kuitit ja raportit varustettuna omalla
					y-tunnuksella.</span>
			</div>
			<div
				style="width: 350px; height: 350px; border-radius: 175px; background: #FFF; margin-top: 30px; margin-left: 770px; color: #999; font-size: 16px; font-weight: normal;">
				<div style="padding-top: 60px;">Kirjanpitovalmiit raportit</div>
				<div style="padding-top: 10px;">
					Järjestelmä tallentaa kaikki kuitit<br /> palvelimelle, josta voit
					koska vain<br /> hakea asiakkaidesi vanhoja <br /> ostotapahtumia
					tai kuitteja.<br /> <br /> Sinun ei enää tarvitse huolehtia ovatko
					<br /> kuittisi tallessa, sillä järjestelmä huolehtii <br /> niistä
					automaattisesti puolestasi.
				</div>
			</div>
		</div>

	</div>
	<div class="greyDivider"></div>

	<?php include "includes/footArea.php"; ?>

</body>
</html>
