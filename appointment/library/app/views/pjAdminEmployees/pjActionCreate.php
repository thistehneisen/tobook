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
	
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['AE09'], @$bodies['AE09']);
	?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminEmployees&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmCreateEmployee" class="form pj-form" enctype="multipart/form-data">
		<input type="hidden" name="employee_create" value="1" />
		<?php
		foreach ($tpl['lp_arr'] as $v)
		{
		?>
			<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
				<label class="title"><?php __('employee_name'); ?>:</label>
				<span class="inline_block">
					<input type="text" name="i18n[<?php echo $v['id']; ?>][name]" class="pj-form-field w400<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" />
					<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
					<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
					<?php endif; ?>
				</span>
			</p>
			<?php
		}
		?>
		<p>
			<label class="title"><?php __('employee_notes'); ?>:</label>
			<textarea name="notes" id="notes" class="pj-form-field w500 h150"></textarea>
		</p>
		<p style="position: relative">
			<label class="title"><?php __('employee_email'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-email"></abbr></span>
				<input type="text" name="email" id="email" class="pj-form-field required email w200" />
			</span>
			<span style="position: absolute; top: 7px; left: 405px">
				<label><input type="checkbox" name="is_subscribed" id="is_subscribed" value="1" />
				<?php __('employee_is_subscribed'); ?></label>
			</span>
		</p>
		<p>
			<label class="title"><?php __('employee_password'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-password"></abbr></span>
				<input type="text" name="password" id="password" class="pj-form-field required w200" />
			</span>
		</p>
		<p style="position: relative">
			<label class="title"><?php __('employee_phone'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-phone"></abbr></span>
				<input type="text" name="phone" id="phone" class="pj-form-field required w200" />
			</span>
			<span style="position: absolute; top: 7px; left: 405px">
				<label><input type="checkbox" name="is_subscribed_sms" id="is_subscribed_sms" value="1" /> <?php __('employee_is_subscribed_sms'); ?></label>
			</span>
		</p>
		<p>
			<label class="title"><?php __('employee_services'); ?></label>
			<select name="service_id[]" class="pj-form-field" multiple>
				<?php
				foreach ($tpl['service_arr'] as $service)
				{
					?><option value="<?php echo $service['id']; ?>"><?php echo pjSanitize::html($service['name']); ?></option><?php
				}
				?>
			</select>
		</p>
		<p>
			<label class="title"><?php __('employee_status'); ?></label>
			<span class="inline_block">
				<select name="is_active" id="is_active" class="pj-form-field required">
					<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					foreach (__('is_active', true) as $k => $v)
					{
						?><option value="<?php echo $k; ?>"><?php echo $v; ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		<p>
			<label class="title"><?php __('employee_avatar'); ?></label>
			<span class="float_left block">
				<input type="file" name="avatar" id="avatar" />
			</span>
		</p>
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
		</p>
	</form>
	
	<script type="text/javascript">
	var myLabel = myLabel || {};
	myLabel.email_taken = "<?php __('vr_email_taken', false, true); ?>";
	</script>
	<?php
}
?>
