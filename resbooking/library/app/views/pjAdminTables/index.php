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
} elseif ((int) $tpl['option_arr']['use_map'] == 1) {
	pjUtil::printNotice($RB_LANG['map_using'], false);
} else {
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$RB_LANG['errors'][$_GET['err']]);
	}
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php echo $RB_LANG['table_list']; ?></a></li>
			<li><a href="#tabs-2"><?php echo $RB_LANG['table_create']; ?></a></li>
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
					<table class="table">
						<thead>
							<tr>
								<th class="sub"><?php echo $RB_LANG['table_name']; ?></th>
								<th class="sub align_center w100"><?php echo $RB_LANG['table_minimum']; ?></th>
								<th class="sub align_center w100"><?php echo $RB_LANG['table_seats']; ?></th>
								<th class="sub" style="width: 7%"></th>
								<th class="sub" style="width: 7%"></th>
							</tr>
						</thead>
						<tbody>
					<?php
					for ($i = 0; $i < $count; $i++)
					{
						?>
						<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?> {id: '<?php echo $tpl['arr'][$i]['id']; ?>'}">
							<td><?php echo stripslashes($tpl['arr'][$i]['name']); ?></td>
							<td class="align_center"><?php echo (int) $tpl['arr'][$i]['minimum']; ?></td>
							<td class="align_center"><?php echo (int) $tpl['arr'][$i]['seats']; ?></td>
							<td><a class="icon icon-edit" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTables&amp;action=update&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $RB_LANG['_edit']; ?></a></td>
							<td><a class="icon icon-delete" rel="<?php echo $tpl['arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTables&amp;action=delete&amp;id=<?php echo $tpl['arr'][$i]['id']; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
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
						<ul class="paginator">
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
						<div id="dialogDelete" title="<?php echo htmlspecialchars($RB_LANG['table_del_title']); ?>" style="display:none">
							<p><?php echo $RB_LANG['table_del_body']; ?></p>
						</div>
						<?php
					}
				} else {
					pjUtil::printNotice($RB_LANG['table_empty']);
				}
			}
		}
		?>
		</div> <!-- tabs-1 -->
		<div id="tabs-2">
			<?php pjUtil::printNotice($RB_LANG['info']['add_table']); ?>
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminTables&amp;action=create" method="post" id="frmCreateTable" class="form">
				<input type="hidden" name="table_create" value="1" />
				<p><label class="title"><?php echo $RB_LANG['table_name']; ?></label><input type="text" name="name" id="name" class="text w400 required" /></p>
				<p><label class="title"><?php echo $RB_LANG['table_minimum']; ?></label><input type="text" name="minimum" id="minimum" class="text w80 align_right digit required" /></p>
				<p><label class="title"><?php echo $RB_LANG['table_seats']; ?></label><input type="text" name="seats" id="seats" class="text w80 align_right digit required" /></p>
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