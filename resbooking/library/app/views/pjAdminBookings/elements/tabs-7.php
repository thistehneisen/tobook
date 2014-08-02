<div class="sub-tabs">
	<ul>
		<li><a href="#sub-tabs-1">Booking</a></li>
		<li><a href="#sub-tabs-2">Template</a></li>
		<li><a href="#sub-tabs-3">Create group</a></li>
		<li><a href="#sub-tabs-4">Menu</a></li>
		<li><a href="#sub-tabs-5">Tables Group</a></li>
	</ul>
	<div id="sub-tabs-1">
	<?php
	if (isset($tpl['arr_group']))
	{
		include_once VIEWS_PATH . 'pjHelpers/time.widget.php';
		$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
		$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
		
		if (is_array($tpl['arr_group']))
		{
			$count = count($tpl['arr_group']);
			if ($count > 0)
			{
				?>
				<table class="table">
					<thead>
						<tr>
							<th class="sub"><?php echo $RB_LANG['booking_dt']; ?></th>
							<th class="sub">Amount of people</th>
							<th class="sub"><?php echo $RB_LANG['booking_name']; ?></th>
							<th class="sub"><?php echo $RB_LANG['booking_email']; ?></th>
							<th class="sub"><?php echo $RB_LANG['booking_status']; ?></th>
							<th class="sub" style="width: 7%"></th>
						</tr>
					</thead>
					<tbody>
				<?php
				for ($i = 0; $i < $count; $i++)
				{
					?>
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?> pointer {id: '<?php echo $tpl['arr_group'][$i]['id']; ?>'}">
						<td class="meta"><?php echo date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr_group'][$i]['dt'])); ?></td>
						<td class="meta"><?php echo $tpl['arr_group'][$i]['people']; ?></td>
						<td class="meta"><?php echo stripslashes($tpl['arr_group'][$i]['c_fname'] . " " . $tpl['arr_group'][$i]['c_lname']); ?></td>
						<td class="meta"><?php echo stripslashes($tpl['arr_group'][$i]['c_email']); ?></td>
						
						<?php 
						if ( isset($tpl['arr_group'][$i]['reminder_email']) && $tpl['arr_group'][$i]['reminder_email'] == 1 ) {
							$status = 'Replied';
						} else {
							$status = 'New';
						}
						?>
						<td class="meta"><?php echo $status; ?></td>
						<td><a class="icon icon-delete" data-id="<?php echo $tpl['arr_group'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=delete&amp;id=<?php echo $tpl['arr_group'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
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
							?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;pageg=<?php echo $i; ?>&amp;rbpf=<?php echo PREFIX; ?>" class="focus"><?php echo $i; ?></a></li><?php
						} else {
							?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;pageg=<?php echo $i; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $i; ?></a></li><?php
						}
					}
					?>
					</ul>
					<?php
				}
				
				if (!$controller->isAjax())
				{
					?>
					<div id="dialogDelete" title="<?php echo htmlspecialchars($RB_LANG['booking_del_title']); ?>" style="display:none">
						<p><?php echo $RB_LANG['booking_del_body']; ?></p>
					</div>
					<?php
				}
			} else {
				pjUtil::printNotice($RB_LANG['booking_empty']);
			}
		}
	}
	?>
	</div>
	<div id="sub-tabs-2">
		<div class="form-email" style="display: inline-block; width: 100%; margin-bottom: 20px;">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=index&amp;rbpf=<?php echo PREFIX; ?>" method="post" class="form" id="frmTemplate" style="float: left">
				<input type="hidden" name="template" value="1" />
				<p>
					<label class="title">Name</label>
					<input type="text" name="name" class="text w150 pointer required pps"  />
				</p>
				<p>
					<label class="title">Subject</label>
					<input type="text" name="subject" class="text w150 pointer required pps"  />
				</p>
				<p>
					<label class="title">Message</label>
					<textarea class="textarea required" name="message" cols="50" rows="5"></textarea>
				</p>
				<p>
					<input type="submit" value="" class="button button_save"  />
				</p>
			</form>
			<div class="tokens w400" style="float: right;">
					
				<table class="table" style="width: 100%;">
					<thead>
						<tr>
							<th class="sub">Available Tokens:</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{Title}</td>
							<td>{DtFrom}</td>
						</tr>
						<tr>
							<td>{FirstName}</td>
							<td>{Table}</td>
						</tr>
						<tr>
							<td>{LastName}</td>
							<td>{People}</td>
						</tr>
						<tr>
							<td>{Email}</td>
							<td>{BookingID}</td>
						</tr>
						<tr>
							<td>{Phone}</td>
							<td>{UniqueID}</td>
						</tr>
						<tr>
							<td>{Notes}</td>
							<td>{Total}</td>
						</tr>
						<tr>
							<td>{Country}</td>
							<td>{PaymentMethod}</td>
						</tr>
						<tr>
							<td>{City}</td>
							<td>{CCType}</td>
						</tr>
						<tr>
							<td>{State}</td>
							<td>{CCNum}</td>
						</tr>
						<tr>
							<td>{Zip}</td>
							<td>{CCExp}</td>
						</tr>
						<tr>
							<td>{Address}</td>
							<td>{CCSec}</td>
						</tr>
						<tr>
							<td>{Company}</td>
							<td>{CancelURL}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<?php
		if ( isset($tpl['template_arr']) && is_array($tpl['template_arr']) ) {
	
			$count = count($tpl['template_arr']);
			if ($count > 0) {
				?>
				<table class="table" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="sub">Name</th>
							<th class="sub">Subject</th>
							<th class="sub">Message</th>
							<th class="sub" style="width: 8%"></th>
							<th class="sub" style="width: 8%"></th>
						</tr>
					</thead>
					<tbody>
				<?php
				for ($i = 0; $i < $count; $i++) {	
					?>
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
						<td><?php echo $tpl['template_arr'][$i]['name']; ?></td>
						<td><?php echo $tpl['template_arr'][$i]['subject']; ?></td>
						<td><?php echo $tpl['template_arr'][$i]['message']; ?></td>
						<td><a class="icon icon-edit" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=tupdate&amp;id=<?php echo $tpl['template_arr'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_edit']; ?></a></td>
						<td><a class="icon icon-delete" data-template="1" data-id="<?php echo $tpl['template_arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=delete&amp;template=1&amp;id=<?php echo $tpl['template_arr'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
					</tr>
					<?php
				}
				?>
					</tbody>
				</table>
				
				<?php if (isset($tpl['tpaginator'])) { ?>
				
					<ul class="paginator">
					<?php
					for ($i = 1; $i <= $tpl['tpaginator']['pages']; $i++)
					{
						if ((isset($_GET['tpage']) && (int) $_GET['tpage'] == $i) || (!isset($_GET['tpage']) && $i == 1))
						{
							?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;spage=<?php echo $i; ?>&amp;tab=6&amp;rbpf=<?php echo PREFIX; ?>" class="focus"><?php echo $i; ?></a></li><?php
						} else {
							?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=index&amp;spage=<?php echo $i; ?>&amp;tab=6&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $i; ?></a></li><?php
						}
					}
					?>
					</ul>
					<?php
				}
				
			} else {
				pjUtil::printNotice($RB_LANG['service_empty']);
			}
			
		} ?>
	</div>
	<div id="sub-tabs-3">
		<form id="frmAddbooking" class="form" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule&amp;rbpf=<?php echo PREFIX; ?>">
			<input type="hidden" value="1" name="rbBookingForm" class="error_title" rev="<?php echo $RB_LANG['front']['4_v_err_title']; ?>">
			<input type="hidden" value="1" name="frmAddGroup">
			<fieldset class="fieldset white">
				<legend><?php echo $RB_LANG['booking_personal']; ?></legend>
				<p><label class="title"><?php echo $RB_LANG['booking_fname']; ?></label><input type="text" name="c_fname" id="c_fname" class="text w250<?php echo $tpl['option_arr']['bf_include_fname'] == 3 ? ' required' : NULL; ?>" value="" /></p>
				<p><label class="title"><?php echo $RB_LANG['booking_lname']; ?></label><input type="text" name="c_lname" id="c_lname" class="text w250<?php echo $tpl['option_arr']['bf_include_lname'] == 3 ? ' required' : NULL; ?>" value="" /></p>
				<p><label class="title"><?php echo $RB_LANG['booking_phone']; ?></label><input type="text" name="c_phone" id="c_phone" class="text w250<?php echo $tpl['option_arr']['bf_include_phone'] == 3 ? ' required' : NULL; ?>" value="" /></p>
				<p><label class="title"><?php echo $RB_LANG['booking_email']; ?></label><input type="text" name="c_email" id="c_email" class="text w250<?php echo $tpl['option_arr']['bf_include_email'] == 3 ? ' required' : NULL; ?>" value="" /></p>
				<p><label class="title"><?php echo $RB_LANG['front']['1_people']; ?></label>
					<select name="people" id="b_people" class="select w150">
						<?php
						foreach (range($tpl['option_arr']['booking_group_booking'], 20) as $i)
						{
							?><option value="<?php echo $i; ?>"><?php echo $i; ?></option><?php
						}
						?>
					</select>
				</p>
				<p><label class="title"><?php echo $RB_LANG['booking_dt']; ?></label>
					<?php
					list($date, $time) = explode(" ", date('Y-m-d H:i:s'));
					list($hour, $minute,) = explode(":", $time);
					?>
					<input type="text" name="date" id="date" class="text w80 required pointer datepick" value="<?php echo pjUtil::formatDate($date, 'Y-m-d', $tpl['option_arr']['date_format']); ?>" readonly="readonly" rel="<?php echo $week_start; ?>" rev="<?php echo $jqDateFormat; ?>" />
					<?php TimeWidget::hour($hour, 'hour', 'hour', 'select', array(), array('time_format' => $tpl['option_arr']['time_format'])); ?>
					<?php TimeWidget::minute(null, 'minute', 'minute', 'select'); ?>
				</p>
				
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="" class="button button_save" />
				</p>
			</fieldset>
		</form>
	</div>
	
	<div id="sub-tabs-4">
		<div class="form-email" style="display: inline-block; width: 100%; margin-bottom: 20px;">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=index&amp;rbpf=<?php echo PREFIX; ?>" method="post" class="form" id="frmMenu">
				<input type="hidden" name="menu" value="1" />
				<p>
					<label class="title">Name</label>
					<input type="text" name="m_name" class="text w150 pointer required pps"  />
				</p>
				<p>
					<label class="title">Type</label>
					<select name="m_type" id="type" class="select required">
						<option value="">-- Choose --</option>
						<?php
						foreach ($RB_LANG['menu_types'] as $k => $v)
						{
							?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
						}
						?>
					</select>
				</p>
				<p>
					<input type="submit" value="" class="button button_save"  />
				</p>
			</form>
		</div>
		<?php
		if ( isset($tpl['menu_arr']) && is_array($tpl['menu_arr']) ) {
	
			$count = count($tpl['menu_arr']);
			if ($count > 0) {
				?>
				<table class="table" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="sub">Name</th>
							<th class="sub">Type</th>
							<th class="sub" style="width: 8%"></th>
							<th class="sub" style="width: 8%"></th>
						</tr>
					</thead>
					<tbody>
				<?php
				for ($i = 0; $i < $count; $i++) {	
					?>
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
						<td><?php echo $tpl['menu_arr'][$i]['m_name']; ?></td>
						<td><?php echo $RB_LANG['menu_types'][$tpl['menu_arr'][$i]['m_type']]; ?></td>
						<td><a class="icon icon-edit" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=mupdate&amp;id=<?php echo $tpl['menu_arr'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_edit']; ?></a></td>
						<td><a class="icon icon-delete" data-menu="1" data-id="<?php echo $tpl['menu_arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=delete&amp;menu=1&amp;id=<?php echo $tpl['menu_arr'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
					</tr>
					<?php
				}
				?>
					</tbody>
				</table>
				
				<?php 
			} else {
				pjUtil::printNotice($RB_LANG['service_empty']);
			}
			
		} ?>
	</div>
	
	<div id="sub-tabs-5">
		<div class="form-email" style="display: inline-block; width: 100%; margin-bottom: 20px;">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=index&amp;rbpf=<?php echo PREFIX; ?>" method="post" class="form" id="frmTablesGroup">
				<input type="hidden" name="tables_group" value="1" />
				<p>
					<label class="title">Name</label>
					<input type="text" name="name" class="text w150 pointer required pps"  />
				</p>
				<p>
					<label class="title">Tables ID</label>
					<?php 
						
						if ( isset($tpl['tg_arr']) && is_array($tpl['tg_arr']) ) {
							
							$tables_id = array();
							foreach ( $tpl['tg_arr'] as $tg ) {
								$tables_id = array_merge($tables_id, explode(',', $tg['tables_id']));
							}
						}
						
						$table_arr = array();
						foreach ($tpl['table_arr'] as $table) {

							if ( !in_array($table['id'], $tables_id) ) {
								$table_arr[] = $table;
							}
						}
						
						foreach ( $table_arr as $table ) { 
						?>
							<label style="margin-right: 20px;" for="table-<?php echo $table['id']; ?>"><input type="checkbox" name="table_id[]" id="table-<?php echo $table['id']; ?>" value="<?php echo $table['id']; ?>"> <?php echo $table['name']; ?></label>
						<?php }
					?>
				</p>
				<p>
					<label class="title">Description</label>
					<textarea name="description" id="description" class="textarea w500 h120"></textarea>
				</p>
				<p>
					<input type="submit" value="" class="button button_save"  />
				</p>
			</form>
		</div>
		<?php
		if ( isset($tpl['tg_arr']) && is_array($tpl['tg_arr']) ) {
	
			$count = count($tpl['tg_arr']);
			if ($count > 0) {
				?>
				<table class="table" cellpadding="0" cellspacing="0">
					<thead>
						<tr>
							<th class="sub">Name</th>
							<th class="sub">Tables ID</th>
							<th class="sub">Description</th>
							<th class="sub" style="width: 8%"></th>
							<th class="sub" style="width: 8%"></th>
						</tr>
					</thead>
					<tbody>
				<?php
				for ($i = 0; $i < $count; $i++) {	
					?>
					<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?>">
						<td><?php echo $tpl['tg_arr'][$i]['name']; ?></td>
						<td><?php echo $tpl['tg_arr'][$i]['tables_id']; ?></td>
						<td><?php echo $tpl['tg_arr'][$i]['description']; ?></td>
						<td><a class="icon icon-edit" href="<?php echo  $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=tgupdate&amp;id=<?php echo $tpl['tg_arr'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_edit']; ?></a></td>
						<td><a class="icon icon-delete" data-tables_group="1" data-id="<?php echo $tpl['tg_arr'][$i]['id']; ?>" href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=delete&amp;tables_group=1&amp;id=<?php echo $tpl['tg_arr'][$i]['id']; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['_delete']; ?></a></td>
					</tr>
					<?php
				}
				?>
					</tbody>
				</table>
				
				<?php 
			} else {
				pjUtil::printNotice($RB_LANG['service_empty']);
			}
			
		} ?>
	</div>
</div>