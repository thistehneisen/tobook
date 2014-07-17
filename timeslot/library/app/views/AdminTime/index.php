<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminTime
 */
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			Util::printNotice($TS_LANG['status'][1]);
			break;
		case 2:
			Util::printNotice($TS_LANG['status'][2]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 1:
				Util::printNotice($TS_LANG['booking_err'][1]);
				break;
			case 2:
				Util::printNotice($TS_LANG['booking_err'][2]);
				break;
			case 4:
				Util::printNotice($TS_LANG['time_err_4']);
				break;
			case 7:
				Util::printNotice($TS_LANG['status'][7]);
				break;
		}
	}
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $TS_LANG['time_default']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $TS_LANG['time_custom']; ?></a></li>
		</ul>
		<div id="tabs-1">
		<?php include VIEWS_PATH . 'AdminTime/elements/default.php'; ?>
		</div>
		<div id="tabs-2">
		<?php include VIEWS_PATH . 'AdminTime/elements/custom.php'; ?>
		</div>
	</div>
	<?php
}
?>