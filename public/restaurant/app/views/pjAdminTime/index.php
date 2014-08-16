<?php
include VIEWS_PATH . 'pjLayouts/elements/menu_restaurant.php';
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
	}
} else {
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 1:
				pjUtil::printNotice($RB_LANG['booking_err'][1]);
				break;
			case 2:
				pjUtil::printNotice($RB_LANG['booking_err'][2]);
				break;
			case 4:
				pjUtil::printNotice($RB_LANG['time_err_4']);
				break;
			case 7:
				pjUtil::printNotice($RB_LANG['status'][7]);
				break;
		}
	}
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $RB_LANG['time_default']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $RB_LANG['service']; ?></a></li>
			<li><a href="#tabs-3"><?php echo $RB_LANG['time_custom']; ?></a></li>
		</ul>
		<div id="tabs-1">
		<?php include VIEWS_PATH . 'pjAdminTime/elements/default.php'; ?>
		</div>
		<div id="tabs-2">
		<?php include VIEWS_PATH . 'pjAdminTime/elements/service.php'; ?>
		</div>
		<div id="tabs-3">
		<?php include VIEWS_PATH . 'pjAdminTime/elements/custom.php'; ?>
		</div>
	</div>
	<?php
}
?>