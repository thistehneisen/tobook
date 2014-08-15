<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminOptions
 */
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			?><p class="status_err"><span>&nbsp;</span><?php echo $TS_LANG['status'][1]; ?></p><?php
			break;
		case 2:
			?><p class="status_err"><span>&nbsp;</span><?php echo $TS_LANG['status'][2]; ?></p><?php
			break;
	}
} else {
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $TS_LANG['option_install']; ?></a></li>
		</ul>
		
		<div id="tabs-1">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="tsbc-form">

					<p><span class="bold block b10"><?php echo $TS_LANG['o_install']['js'][1]; ?></span>
					<textarea class="textarea textarea-install w600 h150 overflow">
&lt;link href="<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&amp;action=css&amp;cid=<?php echo $controller->getCalendarId(); ?>" type="text/css" rel="stylesheet" /&gt;
&lt;link href="<?php echo INSTALL_FOLDER . CSS_PATH; ?>calendar.css" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo INSTALL_FOLDER . JS_PATH; ?>jabb-0.2.js"&gt;&lt;/script&gt;
&lt;script type="text/javascript" src="<?php echo INSTALL_FOLDER . JS_PATH; ?>tsbc.js"&gt;&lt;/script&gt;
</textarea></p>

					<p><span class="bold block b10"><?php echo $TS_LANG['o_install']['js'][2]; ?></span>
					<textarea class="textarea textarea-install w600 h80 overflow">
&lt;script type="text/javascript" src="<?php echo INSTALL_FOLDER; ?>index.php?controller=Front&amp;action=load&amp;cid=<?php echo $controller->getCalendarId(); ?>"&gt;&lt;/script&gt;</textarea></p>

			</form>
		</div> <!-- tabs-1 -->

	</div>
	<?php
}
?>
