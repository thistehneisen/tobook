<?php
if (isset($tpl['status']))
{
	$status = __('status', true);
	switch ($tpl['status'])
	{
		case 2:
			pjUtil::printNotice(NULL, $status[2]);
			break;
	}
} else {
	global $as_pf;
	
	include dirname(__FILE__) . '/elements/menu.php';
	
	$pjTime = pjTime::factory();
		?>
		<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
			<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
				<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionCustomTimes&amp;as_pf=<?php echo $as_pf; ?>">Create Custom Time</a></li>
				<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionEmployeeCustomTime&amp;as_pf=<?php echo $as_pf; ?>">Employees Custom Time</a></li>
			</ul>
		</div>
		
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionCustomTimes&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmCustomtime" class="form pj-form">
			
			<input type="hidden" name="customtine" value="1"> 
			<?php if ( isset($_GET['id']) && !empty($_GET['id']) ) { ?>
				<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>"> 
			<?php }?>
			<fieldset class="fieldset white">
				<legend>Custom Time</legend>
			
				<?php if ( isset($tpl['arr']) ) { ?>
				<p>
					<label class="title">Name</label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<input type="text" name="name" id="name" class="pj-form-field required w200" value="<?php echo $tpl['arr']['name']; ?>"/>
					</span>
				</p>
				<p>
					<label class="title"><?php __('time_from'); ?></label>
					<?php
					$start_time = explode(":", $tpl['arr']['start_time']);
					
					echo $pjTime
						->reset()
						->attr('name', 'start_hour')
						->attr('id', 'start_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$start_time[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_minute')
						->attr('id', 'start_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$start_time[1])
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_to'); ?></label>
					<?php
					$end_time = explode(":", $tpl['arr']['end_time']);
					echo $pjTime
						->reset()
						->attr('name', 'end_hour')
						->attr('id', 'end_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$end_time[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_minute')
						->attr('id', 'end_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$end_time[1])
						->minute();
					?>
				</p>
				
				<p>
					<label class="title"><?php __('time_lunch_from'); ?></label>
					<?php
					$start_lunch = explode(":", $tpl['arr']['start_lunch']);
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_hour')
						->attr('id', 'start_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$start_lunch[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_minute')
						->attr('id', 'start_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$start_lunch[1])
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_lunch_to'); ?></label>
					<?php
					$end_lunch = explode(":", $tpl['arr']['end_lunch']);
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_hour')
						->attr('id', 'end_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->prop('selected', @$end_lunch[0])
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_minute')
						->attr('id', 'end_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->prop('selected', @$end_lunch[1])
						->minute();
					?>
				</p>
				
				<p>
					<label class="title"><?php __('time_is'); ?></label>
					<span class="block float_left t5 b10"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T" <?php echo $tpl['arr']['is_dayoff'] == 'T' ? ' checked="checked"' : NULL; ?>/></span>
				</p>
				
				<?php } else { ?>
				<p>
					<label class="title">Name</label>
					<span class="pj-form-field-custom pj-form-field-custom-before">
						<input type="text" name="name" id="name" class="pj-form-field required w200" />
					</span>
				</p>
				<p>
					<label class="title"><?php __('time_from'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_hour')
						->attr('id', 'start_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_minute')
						->attr('id', 'start_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_to'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_hour')
						->attr('id', 'end_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_minute')
						->attr('id', 'end_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->minute();
					?>
				</p>
				
				<p>
					<label class="title"><?php __('time_lunch_from'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_hour')
						->attr('id', 'start_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'start_lunch_minute')
						->attr('id', 'start_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->minute();
					?>
				</p>
				<p>
					<label class="title"><?php __('time_lunch_to'); ?></label>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_hour')
						->attr('id', 'end_lunch_hour')
						->attr('class', 'pj-form-field w50')
						->hour();
					?>
					<?php
					echo $pjTime
						->reset()
						->attr('name', 'end_lunch_minute')
						->attr('id', 'end_lunch_minute')
						->attr('class', 'pj-form-field w50')
						->prop('step', 5)
						->minute();
					?>
				</p>
				
				<p>
					<label class="title"><?php __('time_is'); ?></label>
					<span class="block float_left t5 b10"><input type="checkbox" name="is_dayoff" id="is_dayoff" value="T" /></span>
				</p>
				<?php } ?>
				<p>
					<label class="title">&nbsp;</label>
					<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button"  />
				</p>
				
			</fieldset>
		</form>
		
		<div id="grid"></div>
		
		<script type="text/javascript">
		var pjGrid = pjGrid || {};
		var myLabel = myLabel || {};
		myLabel.name = "Name";
		myLabel.time_start = "<?php __('time_from', false, true); ?>";
		myLabel.time_end = "<?php __('time_to', false, true); ?>";
		myLabel.time_lunch_start = "<?php __('time_lunch_from', false, true); ?>";
		myLabel.time_lunch_end = "<?php __('time_lunch_to', false, true); ?>";
		myLabel.time_dayoff = "<?php __('time_is', false, true); ?>";
		myLabel.time_yesno = <?php echo pjAppController::jsonEncode(__('_yesno', true)); ?>;
		myLabel.delete_selected = "<?php __('delete_selected', false, true); ?>";
		myLabel.delete_confirmation = "<?php __('delete_confirmation', false, true); ?>";
		</script>
	<?php
}
?>