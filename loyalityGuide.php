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
		<span>Kantiskortti</span>
	</div>
	<div class="guideTopSubTitle">
		Yksi kanta-asiakaskortti, jonka avulla nauttia satojen yritysten eduista!
	</div>
</div>
<div class="greyDivider"></div>
<div class="guideBody">
	<?php include("includes/guideBodyNavigation.php"); ?>
	<div style="font-weight:bold; font-size:20px; padding-top: 30px;">Kantiskortti yhdistää kaikki kanta-asiakaskortit</div>
	<div style="color:#999; font-size:20px; padding-top: 10px;">Räätälöitävä kanta-asiakasohjelma</div>
	<div style="font-weight:bold; font-size:20px; padding-top: 10px;">Markkinointiautomaatio tukemaan markkinointiasi</div>
	<div style="margin-top:20px;"><a href="#"><img src="/asset/image/loyalityBtnAloita.png" style="width:300px;"/></a></div>

	<div style="width:80%;margin-left: 10%;text-align: left; margin-top: 30px; margin-bottom: 20px;" >
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#999;">Oma räätälöity kanta-asiakasohjelma</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Oma Kortti</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Seuraa asiakkaitasi</span>
			</div>				
		</div>
		<div style="width:33%;margin-top:30px;" class="floatleft">
			<div>
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Markkinointiautomaatio</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Piste/Leimatyökalu</span>
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
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Markkinointityökalut</span>
			</div>
			<div style="margin-top:15px;">
				<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:18px;color:#999;">Kohdistetut kampanjat</span>
			</div>				
		</div>
		<div class="clearboth"></div>		
	</div>
	
	<div style="background:#f6841e;color:#FFF;padding-top:30px; padding-bottom:40px;">
		<span style="font-size:40px;">Kantiskortti</span>
		<p style="line-height:30px;">
			Sähköinen kanta-asiakasjärjestelmä kuuluu nykyaikaisen palvelualan yrittäjän vakiokalustoon.<br/>
			Helppokäyttöisten sähköisten työkalujemme avulla siirryt pahvikorttien ja kömpelön asiakashallinnan viime vuosituhannelta suoraan nykyaikaan kivuttomasti ja helposti.<br/>
			Intuitiivinen ja selkeä hallintapaneeli sisällyttää kaikki sähköiset työkalusi yhteen ja samaan paikkaan,  johon pääset käsiksi niin töistä kuin tarvittaessa kotoakin.<br/>
			Nopea ja toimiva järjestelmä takaa sujuvan käyttökokemuksen, joka välittyy parhaimmillaan jopa asiakkaillesi saakka!
		</p>
	</div>
	
	<div class="loyalityBackground01">
		<div class="floatleft" style="width:42%;">
			<div style="width:300px;height:300px; border-radius: 150px;background:#eb8b2e;margin-top:200px;margin-left:170px;color:#FFF;font-size:18px;font-weight:normal;">
				<div style="padding-top:90px;line-height:30px;">
				Palvele asiakkaitasi entistäkin<br>
				henkilökohtaisemmin yrityksesi<br> 
				oman kanta-asiakasjärjestelmän<br>
				avulla!
				</div>
			</div>
		</div>
		<div class="floatleft" style="margin-left:3%;margin-top:80px;text-align:left;width:55%;">
			<div>
				<span style="font-size:30px;font-weight: normal;">Ominaisuudet:</span>
			</div>
			<div style="margin-top:40px;">
				<div style="margin-top:15px;">
					<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Yrityksesi oma räätälöity kanta-asiakasohjelma</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Kanta-asiakaskortti tai –mobiilikortti valintasi mukaan</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Helppokäyttöinen ja selkeä hallintapaneeli</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconArrow.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Tehokkaat suoramarkkinointityökalut</span>
				</div>				
			</div>
			
			<div style="margin-top:40px;">
				<span style="font-size:30px;font-weight: normal;">Hyödyt:</span>
			</div>
			<div style="margin-top:40px;">
				<div style="margin-top:15px;">
					<img src="/asset/image/iconPlus.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Kasvata yrityksesi myyntiä pienellä vaivalla</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconPlus.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Täytä tyhjiä aikoja kalenteristasi</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconPlus.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Palkitse ja sitouta asiakkaasi</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconPlus.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Korvaa vanhat pahviset leimakortit nykyaikaisella sähköisellä järjestelmällä</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconPlus.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Tihennä asiakkaidesi käyntiväliä</span>
				</div>
				<div style="margin-top:15px;">
					<img src="/asset/image/iconPlus.png" style="vertical-align: middle;width:30px;height:30px;"/>&nbsp;<span style="font-size:16px;color:#FFF;font-weight:normal;padding-left:25px;">Ole osa kansallista kanta-asiakkuuksien edelläkävijöiden yhteisöä</span>
				</div>																
								
			</div>			
		</div>
		<div class="clearboth"></div>
	</div>
	
	<div style="margin-top:50px;border-bottom:3px solid #CCC;">
		<span style="color:#000;font-size:36px;font-weight:normal;">Kanta-asiakkuuksien hallinta on nyt vaivattomampaa kuin koskaan!</span>
		<div style="margin-top:70px;width:90%;margin-left:5%;">
			<div class="floatleft" style="width:50%;">
				<img src="/asset/image/loyalityBody02.png" style="width:100%;">
			</div>
			<div class="floatleft" style="margin-left:50px;margin-top:20px;text-align:left;font-size:20px;width:45%;">
				<span style="color:#f6841e;">Kaikki tärkeä tieto asiakkaistasi on vihdoin ulottuvillasi!</span>
				<br/><br/>
				<span style="color:#000;">
					Kanta-asiakasjärjestelmä kerää ja jäsentelee automaattisesti
					tärkeät tiedot asiakkaistasi sekä heidän käynneistään.
					Reaaliaikaisten tietojen ansiosta pystyt ohjaamaan
					liiketoimintaasi haluamaasi suuntaan paremmin  ja
					helpommin kuin koskaan aikaisemmin.
				</span>
				<div style="margin-top:20px;">
					<img src="/asset/image/iconCheck.png" style="vertical-align: middle;width:26px;height:26px;"/>&nbsp;<span style="font-size:20px;color:#000;font-weight:normal;padding-left:25px;">Helppokäyttöinen</span>
				</div>
				<div style="margin-top:20px;">
					<img src="/asset/image/iconCheck.png" style="vertical-align: middle;width:26px;height:26px;"/>&nbsp;<span style="font-size:20px;color:#000;font-weight:normal;padding-left:25px;">Toimintavarma</span>
				</div>
				<div style="margin-top:20px;margin-bottom:30px;">
					<img src="/asset/image/iconCheck.png" style="vertical-align: middle;width:26px;height:26px;"/>&nbsp;<span style="font-size:20px;color:#000;font-weight:normal;padding-left:25px;">Tarpeisiisi mukautuva</span>
				</div>													
			</div>
			<div class="clearboth"></div>			
		</div>
	</div>
	
	<div style="margin-top:40px;">
		<div style="font-size:56px;">
			<span style="color:#f6841e;">Kantiskortti</span><span class="fontBlack">.com</span>
		</div>
		<img src="/asset/image/loyalityBody03.png" style="width:100%;margin-top:100px;">	
	</div>
</div>
<div class="greyDivider"></div>

<?php include "includes/footArea.php"; ?>

</body>
</html>