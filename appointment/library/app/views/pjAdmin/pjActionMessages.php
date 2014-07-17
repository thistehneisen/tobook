<?php
$months = __('months', true);
$days = __('days', true);
?>
if (jQuery_1_8_2.datagrid !== undefined) {
	jQuery_1_8_2.extend(jQuery_1_8_2.datagrid.messages, {
		empty_result: "<?php __('gridEmptyResult'); ?>",
		choose_action: "<?php __('gridChooseAction'); ?>",
		goto_page: "<?php __('gridGotoPage'); ?>",
		total_items: "<?php __('gridTotalItems'); ?>",
		items_per_page: "<?php __('gridItemsPerPage'); ?>",
		prev_page: "<?php __('gridPrevPage'); ?>",
		prev: "<?php __('gridPrev'); ?>",
		next_page: "<?php __('gridNextPage'); ?>",
		next: "<?php __('gridNext'); ?>",
		month_names: ['<?php echo $months[1]; ?>', '<?php echo $months[2]; ?>', '<?php echo $months[3]; ?>', '<?php echo $months[4]; ?>', '<?php echo $months[5]; ?>', '<?php echo $months[6]; ?>', '<?php echo $months[7]; ?>', '<?php echo $months[8]; ?>', '<?php echo $months[9]; ?>', '<?php echo $months[10]; ?>', '<?php echo $months[11]; ?>', '<?php echo $months[12]; ?>'],
		day_names: ['<?php echo $days[1]; ?>', '<?php echo $days[2]; ?>', '<?php echo $days[3]; ?>', '<?php echo $days[4]; ?>', '<?php echo $days[5]; ?>', '<?php echo $days[6]; ?>', '<?php echo $days[0]; ?>'],
		delete_title: "<?php __('gridDeleteConfirmation'); ?>",
		delete_text: "<?php __('gridConfirmationTitle'); ?>",
		action_title: "<?php __('gridActionTitle'); ?>",
		btn_ok: "<?php __('gridBtnOk'); ?>",
		btn_cancel: "<?php __('gridBtnCancel'); ?>",
		btn_delete: "<?php __('gridBtnDelete'); ?>"
	});
}

if (jQuery_1_8_2.multilang !== undefined) {
	jQuery_1_8_2.extend(jQuery_1_8_2.multilang.messages, {
		tooltip: "<?php __('multilangTooltip'); ?>"
	});
}