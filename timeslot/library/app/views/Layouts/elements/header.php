<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Layouts.elements
 */
?>
<!doctype html>
<html>
	<head>
		<title>Time Slots Booking Calendar</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->css as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : BASE_PATH).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		foreach ($controller->js as $js)
		{
			echo '<script type="text/javascript" src="'.(isset($js['remote']) && $js['remote'] ? NULL : BASE_PATH).$js['path'].htmlspecialchars($js['file']).'"></script>';
		}
		?>
	</head>
	<script>
		function onChangeLanguage(){
			var language = $("#language").val();
			$.ajax({
				type: "POST",
				data: { language : language},
				url: "index.php?controller=Admin&action=setLanguage"
			}).success(function (data) {
				window.location.reload();
			});			
		}
	</script>
	<body>
	
		<div id="container">
    		<!-- div id="header">
				<a href="http://varaa.com/" id="logo" target="_blank"><img src="<?php echo BASE_PATH . IMG_PATH; ?>logo.png" alt="Time Slots Booking Calendar" /></a>
				<?php $currentLang = $_SESSION[ 'admin_language' ]; ?>
				<select id="language" style="position:absolute; right: 00px; top: 80px; width: 120px;height:25px;" onchange="onChangeLanguage()">
					<option value="en" <?php if( $currentLang == "en" ) echo " selected"; ?>>English</option>
					<option value="fi" <?php if( $currentLang == "fi" ) echo " selected"; ?>>Finland</option>
				</select>
			</div -->
			
			<div id="middle">
				<div id="leftmenu">
					<?php include_once VIEWS_PATH . 'Layouts/elements/leftmenu.php'; ?>
				</div>
				<div id="right">
					<div class="content-top"></div>
					<div class="content-middle" id="content">