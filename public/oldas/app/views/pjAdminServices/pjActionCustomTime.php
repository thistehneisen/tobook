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
	
	$active = " ui-tabs-active ui-state-active";
	
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuServices'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>"><?php __('service_add'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCategory&amp;as_pf=<?php echo $as_pf; ?>"><?php __('lblAddCategories'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionResources&amp;as_pf=<?php echo $as_pf; ?>"><?php __('lblAddResources'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionExtraService&amp;as_pf=<?php echo $as_pf; ?>"><?php __('lblAddExtraService'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionUpdate&amp;as_pf=<?php echo $as_pf; ?>&amp;id=<?php echo $_GET['foreign_id']; ?>"><?php __('service_update'); ?></a></li>
		</ul>
	</div>
	
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminServices' && $_GET['action'] == 'pjActionCustomTime' ? $active : null; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCustomTime&amp;type=service&amp;foreign_id=<?php echo $_GET['foreign_id']; ?>&amp;as_pf=<?php echo $as_pf; ?>">Custom Time</a></li>
		</ul>
	</div>
	<?php
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	//pjUtil::printNotice(@$titles['AS10'], @$bodies['AS10']);
	
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	
	<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
	<div class="multilang"></div>
	<?php endif; ?>
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCustomTime&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmUpdateService" class="form pj-form">
		
		<input type="hidden" name="service_custom_time" value="1" />
		
		<?php
		if (isset($_GET['foreign_id']) && (int) $_GET['foreign_id'] > 0)
		{
			?><input type="hidden" name="foreign_id" value="<?php echo (int) $_GET['foreign_id']; ?>" /><?php
		}
		if (isset($_GET['type']) && !empty($_GET['type']))
		{
			?><input type="hidden" name="type" value="<?php echo pjSanitize::html($_GET['type']); ?>" /><?php
		}
		?>
		<fieldset class="fieldset white">
			<legend><?php __('time_custom'); ?></legend>
			
		<p>
			<label class="title"><?php __('service_price'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
				<input type="text" name="price" id="price" class="pj-form-field required number w70 align_right" value="" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_length'); ?></label>
			<span class="inline_block">
				<input type="text" name="length" id="length" class="pj-form-field required number w80 spinner" value="" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_length', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_before'); ?></label>
			<span class="inline_block">
				<input type="text" name="before" id="before" class="pj-form-field required number w80 spinner" value="" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_before', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_after'); ?></label>
			<span class="inline_block">
				<input type="text" name="after" id="after" class="pj-form-field required number w80 spinner" value="" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_after', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_total'); ?></label>
			<span id="print_total" class="left"></span>
			<input type="hidden" name="total" id="total" class="required number" value="" />
		</p>
		<p>
			<label class="title">Description</label>
			<span class="inline_block">
				<textarea name="description" class="pj-form-field w200"></textarea>
			</span>
		</p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
		</p>
		</fieldset>
	</form>
	
	<div id="grid"></div>
	
	<script type="text/javascript">
	var pjGrid = pjGrid || {};
	var myLabel = myLabel || {};
	myLabel.price = "<?php __('service_price', false, true); ?>";
	myLabel.len = "<?php __('service_length', false, true); ?>";
	myLabel.before = "<?php __('service_before', false, true); ?>";
	myLabel.after = "<?php __('service_after', false, true); ?>";
	myLabel.total = "<?php __('service_total', false, true); ?>";
	myLabel.delete_selected = "<?php __('delete_selected', false, true); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation', false, true); ?>";
	</script>
	<?php
}
?>