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
	
	<!-- script type="text/javascript" src="https://d345spfe4d65od.cloudfront.net/static/tourmyapp/v1/tourmyapp.js"></script -->
	
	<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js" type="text/javascript"></script>
	<script>var jQuery123 = $.noConflict(true);</script>		
	<link rel="stylesheet" href="https://d345spfe4d65od.cloudfront.net/static/tourmyapp/v1/tourmyapp.css" type="text/css">
	<script type="text/javascript" src="/js/tourmyapp.js"></script>	  	  
	<script type="text/javascript">
	var tour;
	jQuery123(document).ready(function() {
		tour = new TourMyApp("f778a3a0b39c2733342969925a4c6441");
		// tour.start("53abbd04bc1fbe2aed000ef0", true);
		tour.start("53ad8252bc1fbe2aed00102d", true);
	});
	</script -->
	
	<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script src="http://serve.drawium.com/14818803_3034.js"></script -->	
		
	<!-- script src="http://ajax.googleapis.com/ajax/libs/jquery/1.5.1/jquery.min.js"></script>
	<script src="http://serve.drawium.com/14818803_3035.js"></script -->	
	
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
		<div id="container">
    		<div id="header">
				<a href="http://varaa.com/" id="logo" target="_blank"><img src="<?php echo PJ_INSTALL_URL . PJ_IMG_PATH; ?>backend/logo.png" alt="Appointment Scheduler by PHPJabbers.com" /></a>
				<!------ Tutorial playback codes ------->
<!------ Tutorial playback codes ------->
<span mytips:tutorial="53b108d0be4a3434dc00000c"></span>
<span mytips:tutorial="53b10936be4a3434dc00000d"></span>
<span mytips:tutorial="53b109edbe4a3434dc00000e"></span>
<span mytips:tutorial="53b10ab8be4a3434dc00000f"></span>
				
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