<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($RB_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
		case 9:
			pjUtil::printNotice($RB_LANG['status'][9]);
			break;
	}
} else {
	include VIEWS_PATH . 'pjAdminTables/index.php';
}
?>