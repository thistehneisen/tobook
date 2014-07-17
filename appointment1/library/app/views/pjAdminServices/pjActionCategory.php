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
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCategory&amp;as_pf=<?php echo $as_pf; ?>">Add Categories</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionResources&amp;as_pf=<?php echo $as_pf; ?>">Add Resources</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionExtraService&amp;as_pf=<?php echo $as_pf; ?>">Add Extra Service</a></li>
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
	
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCategory&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmUpdateService" class="form pj-form">
		
		<input type="hidden" name="service_category" value="1" />
		
		<fieldset class="fieldset white">
			<legend>Add Category</legend>
			
			<p>
				<label class="title">Category Name</label>
				<span class="pj-form-field-custom pj-form-field-custom-before">
					<input type="text" name="name" id="name" class="pj-form-field required w200" value="" />
				</span>
			</p>
			<p>
				<label class="title">Show on (front end)</label>
				<span class="pj-form-field-custom pj-form-field-custom-before">
					<input type="checkbox" name="show_front" id="show_front" class="pj-form-field " />
				</span>
			</p>
			<p>
				<label class="title">Message</label>
				<span class="inline_block">
					<textarea name="message" id="message" class="pj-form-field w200" rows="" cols=""></textarea>
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
	myLabel.name = "Category Name";
	myLabel.show_front = "Show on";
	myLabel.message = "Message";
	myLabel.delete_selected = "<?php __('delete_selected', false, true); ?>";
	myLabel.delete_confirmation = "<?php __('delete_confirmation', false, true); ?>";
	</script>
	<?php
}
?>