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
		case 3:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
	}
} else {
	$week_start = isset($tpl['option_arr']['week_start']) && in_array((int) $tpl['option_arr']['week_start'], range(0,6)) ? (int) $tpl['option_arr']['week_start'] : 0;
	$jqDateFormat = pjUtil::jqDateFormat($tpl['option_arr']['date_format']);
	
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=1&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=2&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=paper&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=customer&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=statistics&amp;rbpf=<?php echo PREFIX; ?>">Statistics</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=6&amp;rbpf=<?php echo PREFIX; ?>">Group booking</a></li>
			<!-- <li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=formstyle&amp;rbpf=<?php echo PREFIX; ?>">Form Style</a></li>-->
		</ul>
	</div>
	
	<div id="boxCustomer">
		
		<?php
			if (isset($tpl['arr']))
			{
				if (is_array($tpl['arr']))
				{
					$count = count($tpl['arr']);
					if ($count > 0)
					{
						?>
						<a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=download_csv&amp;rbpf=<?php echo PREFIX; ?>" target="_blank"><?php echo $RB_LANG['booking_download']; ?></a>
						<table class="table t10">
							<thead>
								<tr>
									<?php if ( (isset($tpl['option_arr']['cm_include_fname']) && $tpl['option_arr']['cm_include_fname'] == 2) || (isset($tpl['option_arr']['cm_include_lname']) && $tpl['option_arr']['cm_include_lname'] == 2) ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_name']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_phone']) && $tpl['option_arr']['cm_include_phone'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_phone']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_email']) && $tpl['option_arr']['cm_include_email'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_email']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_company']) && $tpl['option_arr']['cm_include_company'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_company']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_address']) && $tpl['option_arr']['cm_include_address'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_address']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_city']) && $tpl['option_arr']['cm_include_city'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_city']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_state']) && $tpl['option_arr']['cm_include_state'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_state']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_zip']) && $tpl['option_arr']['cm_include_zip'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_zip']; ?></th>
									<?php } ?>
									
									<?php if ( isset($tpl['option_arr']['cm_include_count']) && $tpl['option_arr']['cm_include_count'] == 2 ) {?>
									<th class="sub"><?php echo $RB_LANG['booking_count']; ?></th>
									<?php } ?>
									
									<th class="sub"></th>
								</tr>
							</thead>
							<tbody>
						<?php
						for ($i = 0; $i < $count; $i++)
						{
							?>
							<tr class="<?php echo $i % 2 === 0 ? 'odd' : 'even'; ?> ">
								<?php if ( (isset($tpl['option_arr']['cm_include_fname']) && $tpl['option_arr']['cm_include_fname'] == 2) || (isset($tpl['option_arr']['cm_include_lname']) && $tpl['option_arr']['cm_include_lname'] == 2) ) {?>
								<td class="meta"><?php echo isset($tpl['arr'][$i]['c_title']) ? $RB_LANG['_titles'][$tpl['arr'][$i]['c_title']] . ' ' : ''; ?><?php echo stripslashes($tpl['arr'][$i]['c_fname'] . " " . $tpl['arr'][$i]['c_lname']); ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_phone']) && $tpl['option_arr']['cm_include_phone'] == 2 ) {?>
								<td class="meta"><?php echo $tpl['arr'][$i]['c_phone']; ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_email']) && $tpl['option_arr']['cm_include_email'] == 2 ) {?>
								<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_email']); ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_company']) && $tpl['option_arr']['cm_include_company'] == 2 ) {?>
								<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_company']); ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_address']) && $tpl['option_arr']['cm_include_address'] == 2 ) {?>
								<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_address']); ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_city']) && $tpl['option_arr']['cm_include_city'] == 2 ) {?>
								<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_city']); ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_state']) && $tpl['option_arr']['cm_include_state'] == 2 ) {?>
								<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_state']); ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_zip']) && $tpl['option_arr']['cm_include_zip'] == 2 ) {?>
								<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['c_zip']); ?></td>
								<?php } ?>
								
								<?php if ( isset($tpl['option_arr']['cm_include_count']) && $tpl['option_arr']['cm_include_count'] == 2 ) {?>
								<td class="meta"><?php echo stripslashes($tpl['arr'][$i]['count']); ?></td>
								<?php } ?>
									
								
								<td style="min-width: 25px;"><a href="#updatebooking" data-toggle="modal" data-customer="1" data-id="<?php echo $tpl['arr'][$i]['id']; ?>" class="icon icon-edit"><?php echo $RB_LANG['_edit']; ?></a></td>
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
									?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=customer&amp;page=<?php echo $i; ?>&amp;rbpf=<?php echo PREFIX; ?>" class="focus"><?php echo $i; ?></a></li><?php
								} else {
									?><li><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=<?php echo $_GET['controller']; ?>&amp;action=customer&amp;page=<?php echo $i; ?>&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $i; ?></a></li><?php
								}
							}
							?>
							</ul>
							<?php
						}
						
					} else {
						pjUtil::printNotice($RB_LANG['booking_empty']);
					}
				}
			}
			?>
			<!-- Modal -->
			<div class="modal hide fade" id="updatebooking" style="display: none;">
				<div class="modal-body">
					<p>Loading...</p>
				</div>
			</div>
			<!-- End Modal -->
	</div>
	<?php
}
?>