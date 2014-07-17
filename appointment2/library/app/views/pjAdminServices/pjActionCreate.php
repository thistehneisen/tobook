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
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionIndex&amp;as_pf=<?php echo $as_pf; ?>"><?php __('menuServices'); ?></a></li>
			<li class="ui-state-default ui-corner-top ui-tabs-active ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>"><?php __('service_add'); ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCategory&amp;as_pf=<?php echo $as_pf; ?>">Add Categories</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionResources&amp;as_pf=<?php echo $as_pf; ?>">Add Resources</a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionExtraService&amp;as_pf=<?php echo $as_pf; ?>">Add Extra Service</a></li>
		</ul>
	</div>
	<?php
	$titles = __('error_titles', true);
	$bodies = __('error_bodies', true);
	pjUtil::printNotice(@$titles['AS09'], @$bodies['AS09']);
	?>
	<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1) : ?>
	<div class="multilang"></div>
	<?php endif; ?>
	<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminServices&amp;action=pjActionCreate&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmCreateService" class="form pj-form" autocomplete="off">
		<input type="hidden" name="service_create" value="1" />
		<?php
		foreach ($tpl['lp_arr'] as $v)
		{
		?>
			<p class="pj-multilang-wrap" data-index="<?php echo $v['id']; ?>" style="display: <?php echo (int) $v['is_default'] === 0 ? 'none' : NULL; ?>">
				<label class="title"><?php __('service_name'); ?>:</label>
				<span class="inline_block">
					<input type="text" name="i18n[<?php echo $v['id']; ?>][name]" class="pj-form-field w400<?php echo (int) $v['is_default'] === 0 ? NULL : ' required'; ?>" />
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
					<textarea name="i18n[<?php echo $v['id']; ?>][description]" class="pj-form-field w400 h150"></textarea>
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
				<input type="text" name="price" id="price" class="pj-form-field required number w70 align_right" />
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_length'); ?></label>
			<span class="inline_block">
				<input type="text" name="length" id="length" class="pj-form-field required number w80 spinner" value="0" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_length', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_before'); ?></label>
			<span class="inline_block">
				<input type="text" name="before" id="before" class="pj-form-field required number w80 spinner" value="0" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_before', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_after'); ?></label>
			<span class="inline_block">
				<input type="text" name="after" id="after" class="pj-form-field required number w80 spinner" value="0" />
				<a href="#" class="pj-form-langbar-tip listing-tip" original-title="<?php __('service_tip_after', false, true); ?>"></a>
			</span>
		</p>
		<p>
			<label class="title"><?php __('service_total'); ?></label>
			<span id="print_total" class="left">0</span>
			<input type="hidden" name="total" id="total" class="required number" value="0" />
		</p>
		<p>
			<label class="title"><?php __('service_status'); ?></label>
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
			<label class="title">Category</label>
			<span class="inline_block">
				<select name="category_id" id="category_id" class="pj-form-field required">
					<option value="">-- <?php __('lblChoose'); ?>--</option>
					<?php
					if (count($tpl['category_arr']) > 0 ) {

						foreach ( $tpl['category_arr'] as $category ) {
							?><option value="<?php echo $category['id']; ?>"><?php echo $category['name']; ?></option><?php
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
						?><option value="<?php echo $resources['id']; ?>"><?php echo pjSanitize::html($resources['name']); ?></option><?php
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
						?><option value="<?php echo $extra['id']; ?>"><?php echo pjSanitize::html($extra['name']); ?></option><?php
					}
					?>
				</select>
			</span>
		</p>
		
		<div>
			<label class="title" style="margin-top: 10px;"><?php __('service_employees'); ?></label>
			<div class="inline_block">
			<?php foreach ($tpl['employee_arr'] as $employee) { ?>
				<p>
				<label class="w200 inline_block">
					<input value="<?php echo $employee['id']; ?>" type="checkbox" name="employee_id[]" style="margin-right: 5px;"><?php echo pjSanitize::html($employee['name']); ?>
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
						?><option value="<?php echo $t; ?>" <?php echo $t==0 ? 'selected="selected"' : null; ?>><?php echo $t; ?></option><?php
					}
					?>
				</select>
				</p>
			<?php } ?>
			</div>
		</div>
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