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
	include_once PJ_VIEWS_PATH . 'pjLayouts/elements/optmenu.php';
	include dirname(__FILE__) . '/elements/menu.php';
	if (isset($_GET['err']))
	{
		$titles = __('error_titles', true);
		$bodies = __('error_bodies', true);
		pjUtil::printNotice(@$titles[$_GET['err']], @$bodies[$_GET['err']]);
	}
	pjUtil::printNotice(__('plugin_locale_ie_title', true), __('plugin_locale_ie_body', true));
	?>
	
	<fieldset class="fieldset white">
		<legend><?php __('plugin_locale_import'); ?></legend>
		<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjLocale&amp;action=pjActionImport" method="post" class="form pj-form" enctype="multipart/form-data">
			<input type="hidden" name="import" value="1" />
			<p>
				<label class="title"><?php __('plugin_locale_language'); ?></label>
				<select name="locale" class="pj-form-field">
				<?php
				foreach ($tpl['locale_arr'] as $locale)
				{
					?><option value="<?php echo $locale['id']; ?>"><?php echo pjSanitize::html($locale['title']); ?></option><?php
				}
				?>
				</select>
			</p>
			<p>
				<label class="title"><?php __('plugin_locale_browse'); ?></label>
				<input type="file" name="file" />
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('plugin_locale_import'); ?>" class="pj-button" />
			</p>
		</form>
	</fieldset>
	
	<fieldset class="fieldset white">
		<legend><?php __('plugin_locale_export'); ?></legend>
		<form action="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjLocale&amp;action=pjActionExport" method="post" class="form pj-form">
			<input type="hidden" name="export" value="1" />
			<p>
				<label class="title"><?php __('plugin_locale_language'); ?></label>
				<select name="locale" class="pj-form-field">
				<?php
				foreach ($tpl['locale_arr'] as $locale)
				{
					?><option value="<?php echo $locale['id']; ?>"><?php echo pjSanitize::html($locale['title']); ?></option><?php
				}
				?>
				</select>
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" value="<?php __('plugin_locale_export'); ?>" class="pj-button" />
			</p>
		</form>
	</fieldset>
	<?php
}
?>