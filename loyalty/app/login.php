<?php session_start(); ?>
<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en"> <!--<![endif]-->
	<head>
	    <meta charset="UTF-8">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap.style.css">
		<link rel="stylesheet" href="css/responsive.css">
		<link rel="stylesheet" href="font-awesome/css/font-awesome.css">
		<link rel='stylesheet' href="css/style.css" type='text/css' media='all'/>	
	</head>
	<body>
		<div class="frontTopBackground">
			<div class="frontTopTitle">Kantiskortti</div>
		</div>
		<div class="greyDivider"></div>
		
		<div class="frontLoginBackground">
			<div class="frontLoginContainer">
				<div class="height30"></div>
				<div class="height30"></div>
				<div class="height30"></div>
				<div class="height30"></div>
				<div class="floatleft frontLoginLabel">
					Käyttänimi*
				</div>
				<div class="floatleft frontLoginText">
					<input type="text" id="username" placeholder="Käyttänimi"/>
				</div>
				<div class="clearboth"></div>
				<div class="height30"></div>
				<div class="floatleft frontLoginLabel">
					Salasana*
				</div>
				<div class="floatleft frontLoginText">
					<input type="password" id="password" placeholder="Salasana"/>
				</div>
				<div class="clearboth"></div>
				
				<div class="height30"></div>
				<div class="floatleft frontLoginLabel">
					&nbsp;
				</div>
				<div class="floatleft frontLoginText">
					<input type="button" value="Kirjaudu" id="btnLogin"/>
				</div>
				<div class="clearboth"></div>
				<div style="height:30px;"></div>
			</div>
		</div>
		
		<div class="greyDivider"></div>
		<script type="text/javascript" src="js/responsive.js"></script>
		<script type="text/javascript" src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script type="text/javascript" src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
		<script type="text/javascript" src="js/jquery_cookie.js"></script>
		<script type="text/javascript" src="js/login.js"></script>
		<script type="text/javascript" src="js/respond.js"></script>
	</body>
</html>