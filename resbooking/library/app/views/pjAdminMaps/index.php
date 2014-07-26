<?php
include VIEWS_PATH . 'pjLayouts/elements/menu_restaurant.php';
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			?><p class="status_err"><?php echo $RB_LANG['status'][1]; ?></p><?php
			break;
		case 2:
			?><p class="status_err"><?php echo $RB_LANG['status'][2]; ?></p><?php
			break;
	}
} else {
	if (isset($_GET['err']))
	{
		switch ($_GET['err'])
		{
			case 1:
				pjUtil::printNotice($RB_LANG['map_err'][1]);
				break;
			case 2:
				pjUtil::printNotice($RB_LANG['map_err'][2]);
				break;
			case 5:
				pjUtil::printNotice($RB_LANG['map_err'][5]);
				break;
			case 7:
				pjUtil::printNotice($RB_LANG['status'][7]);
				break;
		}
	}
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminMaps&amp;action=index" method="post" id="frmUpdateMap" class="form" enctype="multipart/form-data">
		<input type="hidden" name="map_update" value="1" />
		<input type="hidden" name="id" value="1" />
		<input type="hidden" name="tab_id" value="<?php echo isset($_GET['tab_id']) && !empty($_GET['tab_id']) ? $_GET['tab_id'] : 'tabs-1'; ?>" />
	
		<div id="tabs">
			<ul>
				<li><a href="#tabs-1"><?php echo $RB_LANG['map_update']; ?></a></li>
				<li><a href="#tabs-2"><?php echo $RB_LANG['map_options']; ?></a></li>
			</ul>
			<div id="tabs-1">
				<?php pjUtil::printNotice($RB_LANG['map_info_1'], false); ?>
			
				<p>
					<label class="title"><?php echo $RB_LANG['map_use']; ?></label>
					<span class="left"><input type="checkbox" name="use_map" id="use_map" value="1|0::1"<?php echo (int) $tpl['option_arr']['use_map'] == 1 ? ' checked="checked"' : NULL; ?> /></span>
				</p>
			
				<div id="boxMap">
				<?php
				$map = UPLOAD_PATH . 'maps/map.jpg';
				if (is_file($map))
				{
					$size = getimagesize($map);
					?>
					<div id="mapHolder" style="position: relative; overflow: hidden; width: <?php echo $size[0]; ?>px; height: <?php echo $size[1]; ?>px; margin: 0 auto;">
						<img id="map" src="<?php echo $map; ?>" alt="" style="margin: 0; border: none; position: absolute; top: 0; left: 0; z-index: 500" />
						<?php
						foreach ($tpl['seat_arr'] as $seat)
						{
							?><span rel="hi_<?php echo $seat['id']; ?>" class="rect empty" style="width: <?php echo $seat['width']; ?>px; height: <?php echo $seat['height']; ?>px; left: <?php echo $seat['left']; ?>px; top: <?php echo $seat['top']; ?>px; line-height: <?php echo $seat['height']; ?>px"><?php echo stripslashes($seat['name']); ?></span><?php
						}
						?>
					</div>
					<div id="hiddenHolder">
						<?php
						foreach ($tpl['seat_arr'] as $seat)
						{
							?><input id="hi_<?php echo $seat['id']; ?>" type="hidden" name="seats[]" value="<?php echo join("|", array($seat['id'], $seat['width'], $seat['height'], $seat['left'], $seat['top'], $seat['name'], $seat['seats'], $seat['minimum'])); ?>" /><?php
						}
						?>
					</div>
					<?php
					if (!$controller->isAjax())
					{
						?>
						<div id="dialogDel" title="<?php echo htmlspecialchars($RB_LANG['map_deli_title']); ?>" style="display:none">
							<p><?php echo $RB_LANG['map_deli_body']; ?></p>
						</div>
						<div id="dialogUpdate" title="<?php echo htmlspecialchars($RB_LANG['map_upd_title']); ?>" style="display:none">
							<p><?php echo $RB_LANG['map_upd_body']; ?></p>
							<div class="form">
								<p>
									<label class="title"><?php echo $RB_LANG['map_seat_name']; ?></label>
									<input type="text" name="seat_name" id="seat_name" class="text w250" />
								</p>
								<p>
									<label class="title"><?php echo $RB_LANG['map_seat_seats']; ?></label>
									<input type="text" name="seat_seats" id="seat_seats" class="text w50 align_right" />
								</p>
								<p>
									<label class="title"><?php echo $RB_LANG['map_seat_minimum']; ?></label>
									<input type="text" name="seat_minimum" id="seat_minimum" class="text w50 align_right" />
								</p>
							</div>
						</div>
						<?php
					}
					?>
					<p class="align_center">
						<input type="submit" value="" class="button button_save" />
						<input type="button" value="" class="button button_delete" rel="<?php echo $tpl['arr']['id']; ?>" />
					</p>
					<?php
				} else {
					?>
					<p>
						<label class="title"><?php echo $RB_LANG['map_path']; ?>:</label>
						<input type="file" name="path" id="path" />
					</p>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" value="" class="button button_save" />
					</p>
					<?php
				}
				?>
				</div>
			</div>
			<div id="tabs-2">
				<?php pjUtil::printNotice($RB_LANG['map_info_2'], false); ?>
				<table class="table" style="width: auto">
					<thead>
						<tr>
							<th class="sub">Width</th>
							<th class="sub">Height</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><input type="text" id="width" class="text align_right w40" value="20" /></td>
							<td><input type="text" id="height" class="text align_right w40" value="20" /></td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	
	</form>
	<?php
	if (isset($_GET['tab_id']) && !empty($_GET['tab_id']))
	{
		$tab_id = explode("-", $_GET['tab_id']);
		$tab_id = (int) $tab_id[1] - 1;
		//$tab_id = (int) $_GET['tab_id'] - 1;
		$tab_id = $tab_id < 0 ? 0 : $tab_id;
		?>
		<script type="text/javascript">
		(function ($) {
			$(function () {
				$("#tabs").tabs("option", "selected", <?php echo $tab_id; ?>);
			});
		})(jQuery);
		</script>
		<?php
	}
}
?>