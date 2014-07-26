<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminCalendars
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
		case 8:
			Util::printNotice($TS_LANG['status'][8]);
			break;
		case 9:
			Util::printNotice($TS_LANG['status'][9]);
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 1:
				Util::printNotice($TS_LANG['calendar_err'][1]);
				break;
			case 2:
				Util::printNotice($TS_LANG['calendar_err'][2]);
				break;
			case 3:
				Util::printNotice($TS_LANG['calendar_err'][3]);
				break;
			case 4:
				Util::printNotice($TS_LANG['calendar_err'][4]);
				break;
			case 5:
				Util::printNotice($TS_LANG['calendar_err'][5]);
				break;
			case 7:
				Util::printNotice($TS_LANG['status'][7]);
				break;
			case 8:
				Util::printNotice($TS_LANG['calendar_err'][8]);
				break;
			case 9:
				Util::printNotice($TS_LANG['calendar_err'][9]);
				break;
		}
	}
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $TS_LANG['calendar_list']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $TS_LANG['calendar_create']; ?></a></li>
		</ul>
		<div id="tabs-1">
		<?php
		if (isset($tpl['arr']))
		{
			if (is_array($tpl['arr']))
			{
				$count = count($tpl['arr']);
				if ($count > 0)
				{
					?>
					<table class="tsbc-table" cellpadding="0" cellspacing="0">
						<thead>
							<tr>
								<th class="sub"><?php echo $TS_LANG['calendar_title']; ?></th>
								<?php if ($controller->isMultiUser()) : ?>
								<th class="sub"><?php echo $TS_LANG['calendar_owner']; ?></th>
								<?php endif; ?>
								<th class="sub" style="width: 5%"></th>
								<th class="sub" style="width: 10%"></th>
								<th class="sub" style="width: 10%"></th>
							</tr>
						</thead>
						<tbody>
					<?php
					for ($i = 0; $i < $count; $i++)
					{
						?>
						<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
							<td><?php echo stripslashes($tpl['arr'][$i]['calendar_title']); ?></td>
							<?php if ($controller->isMultiUser()) : ?>
							<td><?php echo stripslashes($tpl['arr'][$i]['username']); ?></td>
							<?php endif; ?>
							<td><a class="icon icon-calendar" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCalendars&amp;action=view&amp;cid=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $TS_LANG['_view_calendar']; ?></a></td>
							<td><a class="icon icon-edit" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCalendars&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $TS_LANG['_edit']; ?></a></td>
							<td><a class="icon icon-delete calendar-delete" rev="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=AdminCalendars&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $TS_LANG['_delete']; ?></a></td>
						</tr>
						<?php
					}
					?>
						</tbody>
					</table>
					<?php
					if (isset($tpl['paginator']))
					{
						?>
						<ul class="tsbc-paginator">
						<?php
						for ($i = 1; $i <= $tpl['paginator']['pages']; $i++)
						{
							if ((isset($_GET['page']) && (int) $_GET['page'] == $i) || (!isset($_GET['page']) && $i == 1))
							{
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>" class="focus"><?php echo $i; ?></a></li><?php
							} else {
								?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;page=<?php echo $i; ?>"><?php echo $i; ?></a></li><?php
							}
						}
						?>
						</ul>
						<?php
					}
					
					if (!$controller->isAjax())
					{
						?>
						<div id="dialogDelete" title="<?php echo htmlspecialchars($TS_LANG['calendar_del_title']); ?>" style="display:none">
							<p><?php echo $TS_LANG['calendar_del_body']; ?></p>
						</div>
						<?php
					}
					?>
					<div id="record_id" style="display:none"></div>
					<?php
				} else {
					?><p class="status_notice"><?php echo $TS_LANG['calendar_empty']; ?></p><?php
				}
			}
		}
		?>
		</div>
		<div id="tabs-2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminCalendars&amp;action=create" method="post" id="frmCreateCalendar" class="tsbc-form">
				<input type="hidden" name="calendar_create" value="1" />
				<p>
					<label class="title"><?php echo $TS_LANG['calendar_title']; ?>:</label>
					<input type="text" name="calendar_title" id="calendar_title" class="text w400 required" />
				</p>
				<?php if ($controller->isAdmin() && $controller->isMultiUser()) : ?>
				<p>
					<label class="title"><?php echo $TS_LANG['calendar_owner']; ?>:</label>
					<select name="user_id" id="user_id" class="select">
						<option value=""><?php echo $TS_LANG['calendar_choose']; ?></option>
						<?php
						foreach ($tpl['user_arr'] as $v)
						{
							?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['username']); ?></option><?php
						}
						?>
					</select>
				</p>
				<?php endif; ?>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="" class="button button_save" />
				</p>
			</form>
		</div>
	</div>
	<?php
}
?>