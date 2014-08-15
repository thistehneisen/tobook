<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Layouts
 */
?>
<!doctype html>
<html>
	<head>
		<title>Install</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->css as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.$css['path'].$css['file'].'" />';
		}
		
		foreach ($controller->js as $js)
		{
			echo '<script type="text/javascript" src="'.$js['path'].$js['file'].'"></script>';
		}
		?>
	</head>
	<body>
		<div id="container_cms">

			<div id="main">
			<?php require $content_tpl; ?>
			</div> <!-- main -->
				
		</div> <!-- container_cms -->
	</body>
</html>