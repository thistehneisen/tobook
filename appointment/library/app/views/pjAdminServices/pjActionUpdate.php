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
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCategory&amp;as_pf=<?php echo $as_pf; ?>">Add Categories</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionResources&amp;as_pf=<?php echo $as_pf; ?>">Add Resources</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionExtraService&amp;as_pf=<?php echo $as_pf; ?>">Add Extra Service</a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionUpdate&amp;as_pf=<?php echo $as_pf; ?>&amp;id=<?php echo $tpl['arr']['id']; ?>"><?php __('service_update'); ?></a></li>
		</ul>
	</div>
	
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top<?php echo $_GET['controller'] == 'pjAdminServices' && $_GET['action'] == 'pjActionCustomTime' ? $active : null; ?>"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCustomTime&amp;type=service&amp;foreign_id=<?php echo $tpl['arr']['id']; ?>&amp;as_pf=<?php echo $as_pf; ?>">Custom Time</a></li>
		</ul>
	</div>
	<?php
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['AS10'], @$bodies['AS10']);
	
	if (isset($_GET['err']))
	{
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	?>
	<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
	<div class="multilang"></div>
	<?php endif; ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionUpdate&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmUpdateService" class="form pj-form">
		<input type="hidden" name="service_update" value="1" />
		<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
		<?php
		foreach ($tpl['lp_arr'] as $v)
		{
		?>
			<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
				<label class="title"><?php __('service_name'); ?>:</label>
				<span class="inline_block">
					<input type="text" name="i18n[<?php echo $v['id']; ?>][name]" class="pj-form-field w400<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" value="<?php echo pjSanitize::html($tpl['arr']['i18n'][$v['id']]['name']); ?>" />
					<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
					<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
					<?php endif; ?>
				</span>
			</p>
			<?php
		}
		foreach ($tpl['lp_arr'] as $v)
		{
		?>
			<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
				<label class="title"><?php __('service_desc'); ?>:</label>
				<span class="inline_block">
					<textarea name="i18n[<?php echo $v['id']; ?>][description]" class="pj-form-field w400 h150"><?php echo pjSanitize::html($tpl['arr']['i18n'][$v['id']]['description']); ?></textarea>
					<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
					<span class="pj-multilang-input"><img src="<?php echo PJ_INSTALL_URL . PJ_FRAMEWORK_LIBS_PATH . 'pj/img/flags/' . $v['file']; ?>" alt="" /></span>
					<?php endif; ?>
				</span>
			</p>
			<?php
		}
		?>
		<p>
			<label class="title"><?php __('service_price'); ?></label>
			<span class="pj-form-field-custom pj-form-field-custom-before">
				<span class="pj-form-field-before"><abbr class="pj-form-field-icon-text"><?php echo pjUtil::formatCurrencySign(NULL, $tpl['option_arr']['o_currency'], ""); ?></abbr></span>
				<input type="text" name="price" id="price" class="pj-form-field required number w70 align_right" value="<?php echo number_format($tpl['arr']['price'], 2, ".", ""); ?>" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_length'); ?></label>
			<span class="inline_block">
				<input type="text" name="length" id="length" class="pj-form-field required number w80 spinner" value="<?php echo (int) $tpl['arr']['length']; ?>" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_length', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_before'); ?></label>
			<span class="inline_block">
				<input type="text" name="before" id="before" class="pj-form-field required number w80 spinner" value="<?php echo (int) $tpl['arr']['before']; ?>" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_before', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_after'); ?></label>
			<span class="inline_block">
				<input type="text" name="after" id="after" class="pj-form-field required number w80 spinner" value="<?php echo (int) $tpl['arr']['after']; ?>" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_after', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_total'); ?></label>
			<span id="print_total" class="left"><?php echo (int) $tpl['arr']['total']; ?></span>
			<input type="hidden" name="total" id="total" class="required number" value="<?php echo (int) $tpl['arr']['total']; ?>" />
		</p>
		<p>
			<label class="title"><?php __('service_status'); ?></label>
			<span class="inline_block">
				<select name="is_active" id="is_active" class="pj-form-field required">
					<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					foreach (__('is_active', true) as $k => $v)
					{
						?><option value="<?php echo $k; ?>"<?php echo $k == $tpl['arr']['is_active'] ? ' selected="selected"' : NULL; ?>><?php echo $v; ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		<p>
			<label class="title">Category</label>
			<span class="inline_block">
				<select name="category_id" id="category_id" class="pj-form-field required">
					<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					if (count($tpl['category_arr']) > 0 ) {

						foreach ( $tpl['category_arr'] as $category ) {
							?><option <?php echo $tpl['arr']['category_id'] == $category['id'] ? 'selected="selected"' : ''; ?> value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option><?php
						}
					} 
					?>
				</select>
			</span>
		</p>
		<p>
			<label class="title">Resources</label>
			<span class="inline_block">
				<select name="resources_id[]" class="pj-form-field" multiple>
					<?php
					foreach ($tpl['resources_arr'] as $resources)
					{
						?><option value="<?php echo $resources['id']; ?>"<?php echo in_array($resources['id'], $tpl['rs_arr']) ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($resources['name']); ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		
		<p>
			<label class="title">Extra Service</label>
			<span class="inline_block">
				<select name="extra_id[]" class="pj-form-field" multiple>
					<?php
					foreach ($tpl['extra_arr'] as $extra)
					{
						?><option value="<?php echo $extra['id']; ?>"<?php echo in_array($extra['id'], $tpl['ses_arr']) ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($extra['name']); ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		<div>
			<label class="title" style="margin-top: 10px;"><?php __('service_employees'); ?></label>
			<div class="inline_block">
			<?php foreach ($tpl['employee_arr'] as $employee) { 
				
					$checked = '';
					$plus = 0;
					foreach ( $tpl['es_arr'] as $es ) {
						
						if ( $employee['id'] == $es['employee_id'] ) {
							
							$checked = 'checked="checked"';
							
							if ( isset($es['plustime']) && !empty($es['plustime']) ) $plus = $es['plustime'];
							break;
						}
					}
				?>
			
				<p>
				<label class="w200 inline_block">
					<input value="<?php echo $employee['id']; ?>" type="checkbox" name="employee_id[]" <?php echo $checked; ?> style="margin-right: 5px;"><?php echo pjSanitize::html($employee['name']); ?>
				</label>
				<?php 
				$plustime = array();
				for ($i = 0; $i <= 60; $i +=5) {

					$plustime[] = $i;
					
					if ( $i > 0 ) $plustime[] = -$i;
				}
				
				sort($plustime);
				?>
				<select name="plustime[<?php echo $employee['id']; ?>]" id="plustime" class="pj-form-field required">
					<?php
					foreach ( $plustime as $t ) {
						?><option <?php echo $t == $plus ? 'selected="selected"' : null; ?> value="<?php echo $t; ?>"><?php echo $t; ?></option><?php
					}
					?>
				</select>
				</p>
			<?php }?>
			</div>
		</div>
		<!-- 
		<p>
			<label class="title"><?php __('service_employees'); ?></label>
			<span class="inline_block">
				<select name="employee_id[]" class="pj-form-field" multiple>
					<?php
					foreach ($tpl['employee_arr'] as $employee)
					{
						?><option value="<?php echo $employee['id']; ?>"<?php echo in_array($employee['id'], $tpl['es_arr']) ? ' selected="selected"' : NULL; ?>><?php echo pjSanitize::html($employee['name']); ?></option><?php
					}
					?>
				</select>
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_employees', false, true); ?>"></a>
			</span>
		</p>
		-->
		<p>
			<label class="title">&nbsp;</label>
			<input type="submit" value="<?php __('btnSave', false, true); ?>" class="pj-button" />
		</p>
	</form>
	
	<script type="text/javascript">
	<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
	var pjLocale = pjLocale || {};
	pjLocale.langs = <?php echo $tpl['locale_str']; ?>;
	pjLocale.flagPath = "<?php echo PJ_FRAMEWORK_LIBS_PATH; ?>pj/img/flags/";
	(function ($) {
		$(function() {
			$(".multilang").multilang({
				langs: pjLocale.langs,
				flagPath: pjLocale.flagPath,
				tooltip: "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris sit amet faucibus enim.",
				select: function (event, ui) {
					// Callback, e.g. ajax requests or whatever
				}
			});
		});
	})(jQuery_1_8_2);
	<?php endif; ?>
	</script>
	<?php
}
?>