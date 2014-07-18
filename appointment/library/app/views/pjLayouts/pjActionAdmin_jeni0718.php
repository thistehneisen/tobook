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

<!------ myTips tutorial builder ------>
<script type="text/javascript">
var myTipsSetup =
{
    api_key: '19d0e075b6e97a324e77419f6a0c22693662c60d'
};

(function()
{
    var s = document.createElement('script');
    s.type = 'text/javascript';
    s.src = 'https://mytips.co/webclient/loader.js';

    document.head.appendChild(s);
}());
</script>	

	</head>
	<body>

<!------ Tutorial playback codes ------->
<span mytips:tutorial="53b108d0be4a3434dc00000c"></span>
<span mytips:tutorial="53b10936be4a3434dc00000d"></span>
<span mytips:tutorial="53b109edbe4a3434dc00000e"></span>
<span mytips:tutorial="53b10ab8be4a3434dc00000f"></span>
<span mytips:tutorial="53c22cd67cb38b2c0a000007"></span>
<span mytips:tutorial="53c22ddb7cb38b2c0a000008"></span>

		<div id="container">
    		
			
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