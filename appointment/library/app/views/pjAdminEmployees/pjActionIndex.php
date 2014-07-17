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
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	include dirname(__FILE__) . '/elements/menu.php';
	
	pjUtil::printNotice(@$titles['AE11'], @$bodies['AE11']);
	?>
	<div class="b10">
		<form action="" method="get" class="float_left pj-form frm-filter">
			<input type="text" name="q" class="pj-form-field pj-form-field-search w150" placeholder="<?php __('btnSearch', false, true); ?>" />
		</form>
		<?php
		$filter = __('filter', true, true);
		?>
		<div class="float_right t5">
			<a href="#" class="pj-button btn-all"><?php __('lblAll'); ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="is_active" data-value="1"><?php echo $filter['active']; ?></a>
			<a href="#" class="pj-button btn-filter btn-status" data-column="is_active" data-value="0"><?php echo $filter['inactive']; ?></a>
		</div>
		<br class="clear_both" />
	</div>

	<div id="grid"></div>
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	var myLabel = myLabel || {};
	myLabel.menu = "<?php __('menu', false, true); ?>";
	myLabel.view_bookings = "<?php __('employee_view_bookings', false, true); ?>";
	myLabel.working_time = "<?php __('employee_working_time', false, true); ?>";
	myLabel.name = "<?php __('employee_name', false, true); ?>";
	myLabel.email = "<?php __('employee_email', false, true); ?>";
	myLabel.phone = "<?php __('employee_phone', false, true); ?>";
	myLabel.services = "<?php __('employee_services', false, true); ?>";
	myLabel.avatar = "<?php __('employee_avatar', false, true); ?>";
	myLabel.status = "<?php __('employee_status', false, true); ?>";
	myLabel.active = "<?php echo $filter['active']; ?>";
	myLabel.inactive = "<?php echo $filter['inactive']; ?>";
	myLabel.delete_selected = "<?php __('delete_selected', false, true); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation', false, true); ?>";
	</script>
	<?php
}
?>