<?php
include dirname(__FILE__) . '/elements/progress.php';
$STORAGE = &$_SESSION[$controller->defaultInstaller];

$db_url = isset($_COOKIE['appointment_scheduler']) ? $_COOKIE['appointment_scheduler'] : array();
$dbp = isset($_COOKIE['appointment_scheduler_p']) ? $_COOKIE['appointment_scheduler_p'] : '';
$db = array();

if (isset($db_url)) {
	$db_url = urldecode($db_url);

	foreach ( explode('&', $db_url) as $value ) {
		$_db = explode('=', $value);
		$db[$_db[0]] = $_db[1];
	}	
} 
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
		
	<p>Please enter MYSQL login details for your server. If you do not know these please contact your hosting company and ask them to provide you with correct details.</p>
	<p>Alternatively, you can send us acces to your hosting account control panel (the place where you manage your hosting account) and we can create MySQL database and user for you.</p>
	
	<table cellpadding="0" cellspacing="0" class="i-table t20 b20">
		<thead>
			<tr>
				<th>MySQL Login Details</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<form action="index.php?controller=pjInstaller&amp;action=pjActionStep3&amp;install=1&amp;as_pf=<?php echo $as_pf; ?>" method="post" id="frmStep2" class="i-form">
						<input type="hidden" name="step2" value="1" />
						<p>
							<label class="i-title">Hostname <span class="i-red">*</span></label>
							<input type="text" tabindex="1" name="hostname" class="pj-form-field w200 required" value="<?php echo isset($STORAGE['hostname']) ? htmlspecialchars($STORAGE['hostname']) : isset($db['h']) ? $db['h'] : 'localhost'; ?>" />
						</p>
						<p>
							<label class="i-title">Username <span class="i-red">*</span></label>
							<input type="text" tabindex="2" name="username" class="pj-form-field w200 required" value="<?php echo isset($STORAGE['username']) ? htmlspecialchars($STORAGE['username']) : isset($db['u']) ? $db['u'] : NULL; ?>" />
						</p>
						<p>
							<label class="i-title">Password</label>
							<input type="text" tabindex="3" name="password" class="pj-form-field w200" value="<?php echo $dbp; ?>" />
						</p>
						<p>
							<label class="i-title">Database <span class="i-red">*</span></label>
							<input type="text" tabindex="4" name="database" class="pj-form-field w200 required" value="<?php echo isset($STORAGE['database']) ? htmlspecialchars($STORAGE['database']) : isset($db['n']) ? $db['n'] : NULL; ?>" />
						</p>
						<p>
							<label class="i-title">Table prefix</label>
							<input type="text" tabindex="5" name="prefix" class="pj-form-field w200" value="<?php echo $as_pf ?>" />
						</p>
						<div>
							<label class="i-title">&nbsp;</label>
							<div class="float_left">
								<input type="button" tabindex="7" value="&laquo; Back" class="pj-button" onclick="window.location='index.php?controller=pjInstaller&amp;action=pjActionStep1'" />
								<input type="submit" tabindex="6" value="Continue &raquo;" class="pj-button" />
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