<!doctype html>
<html>
	<head>
		<title>Appointment Scheduler by PHPJabbers.com</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<?php
		foreach ($controller->getCss() as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : PJ_INSTALL_URL).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		foreach ($controller->getJs() as $js)
		{
			echo '<script src="'.(isset($js['remote']) && $js['remote'] ? NULL : PJ_INSTALL_URL).$js['path'].htmlspecialchars($js['file']).'"></script>';
		}
		?>
		<!--[if gte IE 9]>
  		<style type="text/css">.gradient {filter: none}</style>
		<![endif]-->
	</head>
	<body>
		<div id="container">
    		<div id="header">
				<a href="http://varaa.com/" id="logo" target="_blank"><img src="<?php echo PJ_INSTALL_URL . PJ_IMG_PATH; ?>backend/logo.png" alt="Appointment Scheduler by PHPJabbers.com" /></a>
			</div>
			
			<div id="middle" class="<?php echo !isset($tpl['option_arr']['o_layout_backend']) || $tpl['option_arr']['o_layout_backend'] != 1 ? 'layout_2' : 'layout_1'; ?>">
				<div id="leftmenu">
					<?php require PJ_VIEWS_PATH . 'pjLayouts/elements/leftmenu.php'; ?>
				</div>
				<div id="right">
					<div class="content-top"></div>
					<div class="content-middle" id="content">
					<?php require $content_tpl; ?>
					<div id="loading" style="display: none;"></div>
					</div>
					<div class="content-bottom"></div>
				</div> <!-- content -->
				<div class="clear_both"></div>
			</div> <!-- middle -->
		
		</div> <!-- container -->
		<div id="footer-wrap">
			<div id="footer">
			   	<p>Copyright &copy; <?php echo date("Y"); ?> <a href="http://varaa.com/" target="_blank">varaa.com</a></p>
	        </div>
        </div>
	</body>
</html>