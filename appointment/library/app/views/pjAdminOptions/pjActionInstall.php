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
	?>
	<div id="tabs">
		<ul>
			<li><a href="#tabs-1"><?php __('menuInstall'); ?></a></li>
			<!-- <li><a href="#tabs-2"><?php __('menuSeo'); ?></a></li>-->
		</ul>
		<div id="tabs-1">
			<?php pjUtil::printNotice(__('lblInstallJs1_title', true), __('lblInstallJs1_body', true), false, false); ?>
			
			<?php if ((int) $tpl['option_arr']['o_multi_lang'] === 1 && isset($tpl['locale_arr']) && count($tpl['locale_arr']) > 1) : ?>
			<form action="" method="get" class="pj-form form">
				<fieldset class="fieldset white">
					<legend><?php __('lblInstallConfig'); ?></legend>
					<p>
						<label class="title"><?php __('lblInstallConfigLocale'); ?></label>
						<select class="pj-form-field w200" name="install_locale">
							<option value="">-- <?php __('lblChoose'); ?> --</option>
							<?php
							foreach ($tpl['locale_arr'] as $locale)
							{
								?><option value="<?php echo $locale['id']; ?>"><?php echo pjSanitize::html($locale['title']); ?></option><?php
							}
							?>
						</select>
					</p>
					<p>
						<label class="title"><?php __('lblInstallConfigHide'); ?></label>
						<span class="left">
							<input type="checkbox" name="install_hide" value="1" />
						</span>
					</p>
				</fieldset>
			</form>
			<?php endif; ?>
			<div class="b10">
			<a id="installcontent" href="#" class="pj-button">Insert Content</a>
			</div>
			<p style="margin: 20px 0 7px; font-weight: bold"><?php __('lblInstallJs1_1'); ?></p>
	<textarea class="pj-form-field w700 textarea_install" id="install_code" style="overflow: auto; height:80px"><iframe width="100%" height="1000px" src="<?php echo PJ_INSTALL_URL; ?>index.php?controller=pjAdminOptions&amp;action=pjActionPreview&amp;as_pf=<?php echo $as_pf; ?>&owner_id=<?php echo $owner_id;?>"></textarea>

			<div style="display:none" id="hidden_code">&lt;link href="<?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFrontEnd&action=pjActionLoadCss" type="text/css" rel="stylesheet" /&gt;
&lt;script type="text/javascript" src="<?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFrontEnd&action=pjActionLoad"&gt;&lt;/script&gt;</div>
		</div>
		<!-- 
		<div id="tabs-2">
			<?php pjUtil::printNotice(@$titles['AO30'], @$bodies['AO30']); ?>
			<p style="margin: 20px 0 7px; font-weight: bold"><?php __('lblInstallSeo_1'); ?></p>
			<input type="text" id="uri_page" class="pj-form-field w700" value="myPage.php" />
			
			<p style="margin: 20px 0 7px; font-weight: bold"><?php __('lblInstallSeo_2'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" style="overflow: auto; height:30px">
&lt;meta name="fragment" content="!"&gt;</textarea>

			<p style="margin: 20px 0 7px; font-weight: bold"><?php __('lblInstallSeo_3'); ?></p>
			<textarea class="pj-form-field w700 textarea_install" id="install_htaccess" style="overflow: auto; height:80px">
RewriteEngine On
RewriteCond %{QUERY_STRING} _escaped_fragment_=(.*)
RewriteRule ^myPage.php <?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFrontPublic&action=pjActionRouter&_escaped_fragment_=%1 [L,NC]</textarea>

			<div style="display: none" id="hidden_htaccess">RewriteEngine On
RewriteCond %{QUERY_STRING} _escaped_fragment_=(.*)
RewriteRule ^::URI_PAGE:: <?php echo PJ_INSTALL_FOLDER; ?>index.php?controller=pjFrontPublic&action=pjActionRouter&_escaped_fragment_=%1 [L,NC]</div>
		</div>-->
		
		<div style="display: none;" id="dialogInstallContent" title="Insert Content">
			<div class="pj-form">
				<p>
					<input type="text" name="search_posts" id="search_posts" class="text w300" placeholder="Search Posts"/>
					<button id="buttonSearchContent" class="pj-button" >Search</button>
				</p>
				<p style="margin-bottom: 20px"></p>
				<div class="content">Loading...</div>
			</div>
		</div>
	
	</div>
	<?php
}
?>
