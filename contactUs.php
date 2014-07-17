<?php 
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
    <style>
      #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
      input, textarea{ padding: 14px; }
    </style>
<script src="https://maps.googleapis.com/maps/api/js?v=3.exp"></script>
<script>
var map;
function initialize() {
  var mapOptions = {
    zoom: 14,
    center: new google.maps.LatLng(60.1708, 24.9375)
  };
  map = new google.maps.Map(document.getElementById('map-canvas'),
      mapOptions);
}

google.maps.event.addDomListener(window, 'load', initialize);

    </script>
</head>
<body style="padding:0px; margin:0px;">
<div class="topNavigationMenu">
	<a href="index.php">ETUSIVU</a>&nbsp;|&nbsp;
	<a href="signup.php">REKISTERÖIDY</a>&nbsp;|&nbsp;
	<a href="login.php">KIRJAUDU</a>
</div>
<div class="guideTopArea">
	<div class="guideTopVaraa">
		<span>varaa</span><span class="fontBlack">.com</span>
	</div>
	<div class="guideTopTitle">
		<span>Yhteystiedot</span>
	</div>
</div>
<div class="greyDivider"></div>
<div style="width:86%;margin-left:7%;font-family:'Comfortaa';">
	<div style="width:100%;height:450px;margin-top:50px;" id="map-canvas"></div>
	<div style="margin-top:50px;">
		<div style="width:33%;" class="floatleft">
			<p style="color:#000;font-size:28px;font-weight:bold;">Osoite:</p>
			<p>
			<img src="/asset/image/iconHome1.png"> <span style="color:#AAA;padding-left:20px;">Kauppatie 800200, Helsinki</span>
			</p>
		</div>
		<div style="width:33%;" class="floatleft">
			<p style="color:#000;font-size:28px;font-weight:bold;">Puhelimet:</p>
			<p>
			<img src="/asset/image/iconTel.png"> <span style="color:#AAA;padding-left:20px;">+358 451605455</span>
			</p>
			<p>
			<img src="/asset/image/iconTel.png"> <span style="color:#AAA;padding-left:20px;">+358 451463755</span>
			</p>						
		</div>
		<div style="width:33%;" class="floatleft">
			<p style="color:#000;font-size:28px;font-weight:bold;">Sähköposti:</p>
			<p>
			<img src="/asset/image/iconEmail.png"> <span style="color:#AAA;padding-left:20px;">yritys@varaa.com</span>
			</p>			
		</div>
		<div class="clearboth"></div>
	</div>
	<div style="margin-top:50px;width:90%;margin-left:5%;color:#999;">
		<div style="width:33%;" class="floatleft">
			<p style="padding-left:7%;">Nimi*</p>
			<input type="text" style="width:86%;margin-left:7%;">
		</div>
		<div style="width:33%;" class="floatleft">
			<p style="padding-left:7%;">Sähköposti*</p>
			<input type="text" style="width:86%;margin-left:7%;">
		</div>
		<div style="width:33%;" class="floatleft">
			<p style="padding-left:7%;">Aihe*</p>
			<input type="text" style="width:86%;margin-left:7%;">
		</div>				
		<div class="clearboth"></div>
		
		<p style="padding-left:3%;">Viesti*</p>
		<textarea style="width:94%;margin-left:3%;" rows="5"></textarea>
		
		<button class="floatright" style="border:0px;cursor:pointer;font-size:18px;color:#EDEDED;background:#ED6A4C;font-weight: bold;padding:8px 30px 8px 30px;margin-top:15px;margin-right:3%;margin-bottom:100px;">SEND EMAIL</button>
		<div class="clearboth"></div>
	</div>
</div>
<div class="greyDivider"></div>

<?php include "includes/footArea.php"; ?>

</body>
</html>