<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminUsers
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
		case 9:
			Util::printNotice($TS_LANG['status'][9]);
			break;
	}
} else {
	include VIEWS_PATH . 'AdminUsers/index.php';
}
?>