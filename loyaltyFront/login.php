<?php session_start(); ?>
<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en"> <!--<![endif]-->
	<head>
		<link rel='stylesheet' href="css/style.css" type='text/css' media='all'/>
		<script src="//code.jquery.com/jquery-1.10.2.js"></script>
		<script src="//code.jquery.com/ui/1.11.0/jquery-ui.js"></script>
		<script src="js/jquery_cookie.js"></script>
		<script type="text/javascript" src="js/login.js"></script>
	</head>
	<body>
		<div class="frontTopBackground">
			<div class="frontTopTitle">Kantiskortti</div>
		</div>
		<div class="greyDivider"></div>
		
		<div class="frontLoginBackground">
			<div class="frontLoginContainer">
				<div style="height:130px;"></div>
				<div class="floatleft frontLoginLabel">
					Käyttänimi*
				</div>
				<div class="floatleft frontLoginText">
					<input type="text" id="username" placeholder="Käyttänimi"/>
				</div>
				<div class="clearboth"></div>
				<div style="height:30px;"></div>
				<div class="floatleft frontLoginLabel">
					Salasana*
				</div>
				<div class="floatleft frontLoginText">
					<input type="password" id="password" placeholder="Salasana"/>
				</div>
				<div class="clearboth"></div>
				
				<div style="height:30px;"></div>
				<div class="floatleft frontLoginLabel">
					&nbsp;
				</div>
				<div class="floatleft frontLoginText" style="text-align:center;margin-left:15px;">
					<input type="button" value="Login" onclick="onLogin()" style="width: 200px; background: #424242; color: #FFF; cursor: pointer;"/>
				</div>
				<div class="clearboth"></div>
				<div style="height:30px;"></div>
			</div>
		</div>
		
		<div class="greyDivider"></div>
	</body>
</html>