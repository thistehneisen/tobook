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
		case 9:
			pjUtil::printNotice($RB_LANG['status'][9]);
			break;
	}
} else {
	?>
	<?php pjUtil::printNotice($RB_LANG['info']['add_table']); ?>
    
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminSpaces&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>"><?php echo $RB_LANG['table_update']; ?></a></li>
		</ul>
	</div>

	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTables&amp;action=update&amp;id=<?php echo $tpl['arr']['id']; ?>" method="post" id="frmUpdateTable" class="form">
		<input type="hidden" name="table_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<p><label class="title"><?php echo $RB_LANG['table_name']; ?></label><input type="text" name="name" id="name" class="text w400 required" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['name'])); ?>" /></p>
		<p><label class="title"><?php echo $RB_LANG['table_minimum']; ?></label><input type="text" name="minimum" id="minimum" class="text w80 align_right digit required" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['minimum'])); ?>" /></p>
		<p><label class="title"><?php echo $RB_LANG['table_seats']; ?></label><input type="text" name="seats" id="seats" class="text w80 align_right digit required" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['seats'])); ?>" /></p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="" class="button button_save" />
		</p>
	</form>
	<?php
}
?>