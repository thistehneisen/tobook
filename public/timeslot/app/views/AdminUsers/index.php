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
			Util::printNotice($TS_LANG['status'][1]);
			break;
		case 2:
			Util::printNotice($TS_LANG['status'][2]);
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
			case 0:
				Util::printNotice($TS_LANG['user_err'][0]);
				break;
			case 1:
				Util::printNotice($TS_LANG['user_err'][1]);
				break;
			case 2:
				Util::printNotice($TS_LANG['user_err'][2]);
				break;
			case 3:
				Util::printNotice($TS_LANG['user_err'][3]);
				break;
			case 4:
				Util::printNotice($TS_LANG['user_err'][4]);
				break;
			case 5:
				Util::printNotice($TS_LANG['user_err'][5]);
				break;
			case 7:
				Util::printNotice($TS_LANG['status'][7]);
				break;
			case 8:
				Util::printNotice($TS_LANG['user_err'][8]);
				break;
		}
	}
	?>
	
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $TS_LANG['user_list']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $TS_LANG['user_create']; ?></a></li>
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
					<table class="tsbc-table">
						<thead>
							<tr>
								<th class="sub"><?php echo $TS_LANG['user_username']; ?></th>
								<th class="sub"><?php echo $TS_LANG['user_role']; ?></th>
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
							<td><?php echo stripslashes($tpl['arr'][$i]['username']); ?></td>
							<td><span class="user-role user-role-<?php echo $tpl['arr'][$i]['role']; ?>"><?php echo $tpl['arr'][$i]['role']; ?></span></td>
							<td><a class="icon icon-edit" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminUsers&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $TS_LANG['_edit']; ?></a></td>
							<td><a class="icon icon-delete" rev="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminUsers&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $TS_LANG['_delete']; ?></a></td>
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
						<div id="dialogDelete" title="<?php echo htmlspecialchars($TS_LANG['user_del_title']); ?>" style="display:none">
							<p><?php echo $TS_LANG['user_del_body']; ?></p>
						</div>
						<?php
					}
					?>
					<div id="record_id" style="display:none"></div>
					<?php
				} else {
					echo $TS_LANG['user_empty'];
				}
			}
		}
		?>
		</div> <!-- tabs-1 -->
		<div id="tabs-2">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=AdminUsers&amp;action=create" method="post" id="frmCreateUser" class="tsbc-form">
				<input type="hidden" name="user_create" value="1" />
				<p><label class="title"><?php echo $TS_LANG['user_role']; ?></label>
					<select name="role_id" id="role_id" class="select w150 required">
						<option value=""><?php echo $TS_LANG['user_choose']; ?></option>
					<?php
					foreach ($tpl['role_arr'] as $v)
					{
						?><option value="<?php echo $v['id']; ?>"><?php echo stripslashes($v['role']); ?></option><?php
					}
					?>
					</select>
				</p>
				<p><label class="title"><?php echo $TS_LANG['user_username']; ?></label><input type="text" name="username" id="username" class="text w150 required" /></p>
				<p><label class="title"><?php echo $TS_LANG['user_password']; ?></label><input type="password" name="password" id="password" class="text w150 required" /></p>
				<p><label class="title"><?php echo $TS_LANG['user_status']; ?></label>
					<select name="status" id="status" class="select w150">
					<?php
					foreach ($TS_LANG['user_statarr'] as $k => $v)
					{
						?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
					}
					?>
					</select>
				</p>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="" class="button button_save" />
				</p>
			</form>
		</div> <!-- tabs-2 -->
	</div>
	<?php
}
?>