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
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=1&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=2&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=paper&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=customer&amp;rbpf=<?php echo PREFIX; ?>"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=statistics&amp;rbpf=<?php echo PREFIX; ?>">Statistics</a></li>
			<li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=6&amp;rbpf=<?php echo PREFIX; ?>">Group booking</a></li>
			<!-- <li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=formstyle&amp;rbpf=<?php echo PREFIX; ?>">Form Style</a></li>-->
		</ul>
	</div>
	
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#sub-tabs-1"><?php echo $RB_LANG['booking_update']; ?></a></li>
		</ul>
		<div class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=index&amp;rbpf=<?php echo PREFIX; ?>" method="post" class="form" id="frmMenu">
				<input type="hidden" name="tables_group" value="1" />
				<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
				<p><label class="title">Name</label>
					<input type="text" name="name" class="text w150 pointer required pps" value="<?php echo $tpl['arr']['name']?>" />
				</p>
				<p><label class="title">Tables ID</label>
					<?php 
					
						if ( isset($tpl['tg_arr']) && is_array($tpl['tg_arr']) ) {
								
							$tables_id = array();
							foreach ( $tpl['tg_arr'] as $tg ) {
								
								if ( $tg['id'] !=  $tpl['arr']['id']) {
									$tables_id = array_merge($tables_id, explode(',', $tg['tables_id']));
								}
							}
						}
						
						$table_arr = array();
						foreach ($tpl['table_arr'] as $table) {
						
							if ( !in_array($table['id'], $tables_id) ) {
								$table_arr[] = $table;
							}
						}
					
						$tables = explode(',', $tpl['arr']['tables_id']);
						
						foreach ( $table_arr as $table ) { 
						?>
							<label style="margin-right: 20px;" for="table-<?php echo $table['id']; ?>"><input type="checkbox" name="table_id[]" id="table-<?php echo $table['id']; ?>" <?php echo in_array($table['id'], $tables) ? 'checked' : ''; ?> value="<?php echo $table['id']; ?>"> <?php echo $table['name']; ?></label>
						<?php }
					?>
				</p>
				<p>
					<label class="title">Description</label>
					<textarea name="description" id="description" class="textarea w500 h120"><?php echo $tpl['arr']['description']?></textarea>
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