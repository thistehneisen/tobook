<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminOptions.elements
 */
if (isset($tpl['arr']))
{
	if (is_array($tpl['arr']))
	{
		$count = count($tpl['arr']);
		if ($count > 0)
		{
			foreach ($tpl['arr'] as $group => $arr)
			{
				if (count($arr) == 0) continue;
				ob_start();
				if (!empty($group))
				{
					?><h3><?php echo $group; ?></h3><?php
				}
				?>
				<table cellpadding="2" cellspacing="1" class="tsbc-table">
					<thead>
						<tr>
							<th class="sub" style="width: 50%"><?php echo $TS_LANG['option_description']; ?></th>
							<th class="sub"><?php echo $TS_LANG['option_value']; ?></th>
						</tr>
					</thead>
					<tbody>
					<?php
					if ($tab_id == 1)
					{
						?>
						<tr class="odd">
							<td class="align_top"><?php echo $TS_LANG['option_username']; ?></td>
							<td class="align_top"><input type="text" name="username" value="<?php echo htmlspecialchars(stripslashes($_SESSION[$controller->default_user]['username'])); ?>" class="text w200" /></td>
						</tr>
						<tr class="even">
							<td class="align_top"><?php echo $TS_LANG['option_password']; ?></td>
							<td class="align_top"><input type="text" name="password" value="<?php echo htmlspecialchars(stripslashes($_SESSION[$controller->default_user]['password'])); ?>" class="text w200" /></td>
						</tr>
						<?php
					}
				
				$j = 0;
				for ($i = 0; $i < count($arr); $i++)
				{
					if ($arr[$i]['key'] == 'timezone') continue;
					if ($arr[$i]['tab_id'] == $tab_id)
					{
						if ($tab_id == 3)
						{
							switch ($arr[$i]['key'])
							{
								case 'payment_enable_paypal':
									list(, $val) = explode("::", $arr[$i]['value']);
									$class_paypal = $val == "No" ? " none" : NULL;
									$paypal_ipn = $val == "No" ? NULL : 'This is your IPN url: <span class="bold">' . INSTALL_URL . 'index.php?controller=Front&action=confirmPaypal</span>';
									break;
								case 'payment_enable_authorize':
									list(, $val) = explode("::", $arr[$i]['value']);
									$class_authorize = $val == "No" ? " none" : NULL;
									break;
							}
						} elseif ($tab_id == 6) {
							if (!isset($t6))
							{
								$t6 = 0;
							}
							$t6++;
							if ($t6 == 2)
							{
								?>
								<tr class="odd">
									<td class="align_top"><?php echo $TS_LANG['option_cron']; ?></td>
									<td class="align_top"><?php printf($TS_LANG['option_cron_info'], INSTALL_PATH . 'cron.php', INSTALL_URL . 'cron.php'); ?></td>
								</tr>
								<?php
							}
						}
						if (in_array($arr[$i]['key'], array('paypal_address')))
						{
							?><tr class="<?php echo ($j % 2 === 0 ? 'odd' : 'even'); ?><?php echo $class_paypal; ?>"><?php
						} elseif (in_array($arr[$i]['key'], array('payment_authorize_key', 'payment_authorize_mid'))) {
							?><tr class="<?php echo ($j % 2 === 0 ? 'odd' : 'even'); ?><?php echo $class_authorize; ?>"><?php
						} else {
							?><tr class="<?php echo ($j % 2 === 0 ? 'odd' : 'even'); ?>"><?php
						}
						?>
							<td class="align_top"><?php echo stripslashes($arr[$i]['description']);
							if ($arr[$i]['key'] == 'payment_enable_paypal') 
							{
								?><br /><span style="font-size: 0.9em"><?php echo $paypal_ipn; ?></span><?php
							}
							?></td>
							<td class="align_top">
								<?php
								switch ($arr[$i]['type'])
								{
									case 'string':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w300" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" /><?php
										if ($arr[$i]['key'] == 'reminder_sms_api')
										{
											?><a href="http://varaa.com/" target="_blank"><?php echo $TS_LANG['option_get_key']; ?></a><?php
										}
										break;
									case 'text':
										?><textarea name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="textarea w400 h320"><?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?></textarea><?php
										break;
									case 'int':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w50 align_right digits" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" />
										<?php
										if (in_array($arr[$i]['key'], array('reminder_sms_hours', 'reminder_email_before')))
										{
											echo $TS_LANG['option_hours_before'];
										}
										break;
									case 'float':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w50 align_right number" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" /><?php
										break;
									case 'enum':
										?><select name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="select">
										<?php
										$default = explode("::", $arr[$i]['value']);
										$enum = explode("|", $default[0]);
										
										$enumLabels = array();
										if (!empty($arr[$i]['label']) && strpos($arr[$i]['label'], "|") !== false)
										{
											$enumLabels = explode("|", $arr[$i]['label']);
										}
										
										foreach ($enum as $k => $el)
										{
											if ($default[1] == $el)
											{
												?><option value="<?php echo $default[0].'::'.$el; ?>" selected="selected"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
											} else {
												?><option value="<?php echo $default[0].'::'.$el; ?>"><?php echo array_key_exists($k, $enumLabels) ? stripslashes($enumLabels[$k]) : stripslashes($el); ?></option><?php
											}
										}
										?>
										</select>
										<?php
										break;
									case 'color':
										?>
										<div id="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="colorSelector"><div style="background-color: #<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>"></div></div>
										<input type="hidden" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" class="hex" />
										<?php
										break;
								}
								?>
							</td>
						</tr>
						<?php
						$j++;
					}
				}
				?>
					</tbody>
				</table>
				<?php
				if ($j > 0)
				{
					ob_end_flush();
				} else {
					ob_end_clean();
				}
			}
			?>
			<p>&nbsp;</p>
			<p><input type="submit" value="" class="button button_save" /></p>
			<?php
		}
	}
}
?>