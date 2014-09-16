<?php
include dirname(__FILE__) . '/elements/progress.php';
$STORAGE = &$_SESSION[$controller->defaultInstaller];
global $as_pf;
?>
<div class="i-wrap">
	<?php
	if (isset($_GET['err']) && !empty($_GET['err']) && isset($_SESSION[$controller->defaultErrors][$_GET['err']]))
	{
		?>
		<div class="i-status i-status-error">
			<div class="i-status-icon"><abbr></abbr></div>
			<div class="i-status-txt">
				<h2>Installation error!</h2>
				<p class="t10"><?php echo @$_SESSION[$controller->defaultErrors][$_GET['err']]; ?></p>
			</div>
		</div>
		<?php
	}
	if (isset($tpl['warning']))
	{
		?>
		<div class="i-status i-status-error">
			<div class="i-status-icon"><abbr></abbr></div>
			<div class="i-status-txt">
				<h2>Warning!</h2>
				<p class="t10">If you proceed with the installation your current database tables and all the data will be deleted.</p>
			</div>
		</div>
		<?php
	}
	?>
	<p>We've detected the following server paths where product is uploaded. Most probably you will not have to change these paths.</p>
	
	<table cellpadding="0" cellspacing="0" class="i-table t20 b20">
		<thead>
			<tr>
				<th>Installation paths</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<form action="index.php?controller=pjInstaller&amp;action=pjActionStep4&amp;install=1&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmStep3" class="i-form">
						<input type="hidden" name="step3" value="1" />
						<p>
							<label class="i-title">Folder Name <span class="i-red">*</span></label>
							<input type="text" tabindex="1" name="install_folder" class="pj-form-field w400 required" value="<?php echo isset($tpl['paths']) ? $tpl['paths']['install_folder'] : htmlspecialchars(@$STORAGE['install_folder']); ?>" /></p>
						<p>
							<label class="i-title">Full URL <span class="i-red">*</span></label>
							<input type="text" tabindex="2" name="install_url" class="pj-form-field w400 required" value="<?php echo isset($tpl['paths']) ? $tpl['paths']['install_url'] : htmlspecialchars(@$STORAGE['install_url']); ?>" />
						</p>
						<p>
							<label class="i-title">Server Path <span class="i-red">*</span></label>
							<input type="text" tabindex="3" name="install_path" class="pj-form-field w400 required" value="<?php echo isset($tpl['paths']) ? $tpl['paths']['install_path'] : htmlspecialchars(@$STORAGE['install_path']); ?>" />
						</p>
						<div>
							<label class="i-title">&nbsp;</label>
							<div class="float_left">
								<input type="button" tabindex="5" value="&laquo; Back" class="pj-button" onclick="window.location='index.php?controller=pjInstaller&amp;action=pjActionStep2'" />
								<input type="submit" tabindex="4" value="Continue &raquo;" class="pj-button" />
							</div>
							<div class="float_right t10">Need help? <a href="http://varaa.com/" target="_blank">Contact us</a></div>
							<br class="clear_both" />
						</div>
					</form>
				</td>
			</tr>
		</tbody>
	</table>
	
	<div class="i-status i-status-notice">
		<div class="i-status-icon"><span class="bold block t15 l15">Examples:</span></div>
		<div class="i-status-txt">
			<p class="float_left"># if the product is uploaded in http://www.website.com/script/ then<br />Folder name should be: /script/<br />Full URL should be: http://website.com/script/</p>
			<p class="float_right"># if the product is uploaded in http://website.com/folder/script/ then<br />Folder name should be: /folder/script/<br />Full URL should be: http:/website.com/folder/script</p>
		</div>
	</div>
</div>