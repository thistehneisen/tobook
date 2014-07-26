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
	}
} else { ?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=1"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=2"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=paper"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=customer"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=statistics">Statistics</a></li>
			<li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=6">Group booking</a></li>
		</ul>
	</div>
	
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#sub-tabs-1"><?php echo $RB_LANG['booking_update']; ?></a></li>
		</ul>
		<div class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=index" method="post" class="form" id="frmMenu">
				<input type="hidden" name="menu" value="1" />
				<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
				<p><label class="title">Name</label>
					<input type="text" name="m_name" class="text w150 pointer required pps" value="<?php echo $tpl['arr']['m_name']?>" />
				</p>
				<p><label class="title">Type</label>
					<select name="m_type" id="type" class="select required">
						<option value="">-- Choose --</option>
						<?php
						foreach ($RB_LANG['menu_types'] as $k => $v)
						{
							?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['m_type'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</p>
				<p>
					<input type="submit" value="" class="button button_save"  />
				</p>
			</form>
		
		</div>
	</div>
	<?php
}
?>