<!doctype html>
<html>
	<head>
		<title>Appointment Scheduler by PHPJabbers.com</title>
		<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta name="fragment" content="!">
		<link href="<?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFrontEnd&action=pjActionLoadCss" type="text/css" rel="stylesheet" />
		
		<?php 
		$s = 'body { ';
		
		if ( isset($tpl['style'][0]['background']) && !empty($tpl['style'][0]['background']) ) {
			$s .= 'background: '. $tpl['style'][0]['background'] .';';
		}
		
		if ( isset($tpl['style'][0]['color']) && !empty($tpl['style'][0]['color']) ) {
			$s .= 'color: '. $tpl['style'][0]['color'] .';';
		}
		
		$color = '';
		if ( isset($tpl['style'][0]['font']) && !empty($tpl['style'][0]['font']) ) {
			$s .= 'color: '. $tpl['style'][0]['color'] .';';
			$color = '.asContainer .asElementTag, .accordion-title, .asContainer .asHeading, #asContainer_1 td.asCalendarMonth{ color: '. $tpl['style'][0]['color'] .'; }';
		}
		
		$font = '';
		if ( isset ($tpl['style'][0]['font']) && !empty($tpl['style'][0]['font'])) {
			echo '<link rel="stylesheet" type="text/css" href="http://fonts.googleapis.com/css?family='. $tpl['style'][0]['font'] .'">';
		
			$tpl['style'][0]['font'] = str_replace('+', ' ', $tpl['style'][0]['font']);
		
			$s .= 'font-family: '. $tpl['style'][0]['font'] .' ;';
			$font = '.asContainer, .asContainer .asHeading, #asContainer_1 td.asCalendarMonth, .asContainer .asServiceName, .asContainer .asEmployeeName{font-family: '. $tpl['style'][0]['font'] .' ;}';
		}
		
		$message = '';
		if ( isset ($tpl['style'][0]['message']) && !empty($tpl['style'][0]['message'])) {
			$message = $tpl['style'][0]['message'];
		}
		
		$s .= ' }';
		
		echo '<style>' . $s . $font . $color . $message . '</style>';
		?>
	</head>
	<body>
		<header>
			<?php if ( isset ($tpl['style'][0]['logo']) && !empty($tpl['style'][0]['logo'])) { ?>
			<div id="logo">
				<a href="#"><img alt="Logo" src="<?php echo $tpl['style'][0]['logo']; ?>"></a>
			</div>
			<?php } ?>
			<?php if ( isset ($tpl['style'][0]['banner']) && !empty($tpl['style'][0]['banner'])) { ?>
			<div class="banner">
				<img alt="Banner" src="<?php echo $tpl['style'][0]['banner']; ?>">
			</div>
			<?php } ?>
		</header>
		<script type="text/javascript" src="<?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFrontEnd&action=pjActionLoad"></script>
	</body>
</html>