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
			
			<div id="middle">
				<div id="leftmenu">
					<?php include_once VIEWS_PATH . 'Layouts/elements/leftmenu.php'; ?>
				</div>
				<div id="right">
					<div class="content-top"></div>
					<div class="content-middle" id="content">
