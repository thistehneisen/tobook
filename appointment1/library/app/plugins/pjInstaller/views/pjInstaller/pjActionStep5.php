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
	?>
	<p>Enter your StivaSoft client License key. You can find that key under your <a href="http://support.stivasoft.com" target="_blank">http://support.stivasoft.com</a> account.
<br /><br />Please, note that it is against our license policy to install our products without providing valid license key. You can check our our License policy <a href="licence.html" target="_blank">here</a>.</p>
	
	<table cellpadding="0" cellspacing="0" class="i-table t20 b20">
		<thead>
			<tr>
				<th>License Key</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<form action="index.php?controller=pjInstaller&amp;action=pjActionStep6&amp;install=1&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmStep5" class="i-form">
						<input type="hidden" name="step5" value="1" />
						<p>
							<label class="i-title">Key <span class="i-red">*</span></label>
							<input type="text" tabindex="1" name="license_key" class="pj-form-field w300 required" value="<?php echo isset($STORAGE['license_key']) ? htmlspecialchars($STORAGE['license_key']) : NULL; ?>" />
						</p>
						<div>
							<label class="i-title">&nbsp;</label>
							<div class="float_left">
								<input type="button" tabindex="3" value="&laquo; Back" class="pj-button" onclick="window.location='index.php?controller=pjInstaller&amp;action=pjActionStep4'" />
								<input type="submit" tabindex="2" value="Continue &raquo;" class="pj-button" />
							</div>
							<div class="float_right t10">Need help? <a href="http://varaa.com/" target="_blank">Contact us</a></div>
							<br class="clear_both" />
						</div>
					</form>
				</td>
			</tr>
		</tbody>
	</table>
</div>