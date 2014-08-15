<?php
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
				<table cellpadding="2" cellspacing="1" class="table">
					<thead>
						<tr>
							<th class="sub" style="width: 50%"><?php echo $RB_LANG['option_description']; ?></th>
							<?php if ($tab_id == 7) {?>
								<th class="sub"><?php echo $RB_LANG['option_show']; ?></th>
							<?php } else { ?>
								<th class="sub"><?php echo $RB_LANG['option_value']; ?></th>
							<?php } ?>
						</tr>
					</thead>
					<tbody>
					<?php
					if ($tab_id == 1)
					{
						?>
						
						<?php
					}
				
				$j = 0;
				for ($i = 0; $i < count($arr); $i++)
				{
					if ($arr[$i]['tab_id'] == $tab_id)
					{
						if ($tab_id == 2)
						{
							switch ($arr[$i]['key'])
							{
								case 'payment_enable_paypal':
									list(, $val) = explode("::", $arr[$i]['value']);
									$class_paypal = $val == "No" ? " none" : NULL;
									break;
								case 'payment_enable_authorize':
									list(, $val) = explode("::", $arr[$i]['value']);
									$class_authorize = $val == "No" ? " none" : NULL;
									break;
								case 'payment_enable_worldpay':
									list(, $val) = explode("::", $arr[$i]['value']);
									$class_worldpay = $val == "No" ? " none" : NULL;
									break;
							}
						} elseif ($tab_id == 5) {
							if (!isset($t5))
							{
								$t5 = 0;
							}
							$t5++;
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
									<td class="align_top"><?php echo $RB_LANG['option_cron']; ?></td>
									<td class="align_top"><?php printf($RB_LANG['option_cron_info'], INSTALL_PATH . 'cron.php', INSTALL_URL . 'cron.php'); ?></td>
								</tr>
								<?php
							}
						}
						if (in_array($arr[$i]['key'], array('paypal_address')))
						{
							?><tr class="<?php echo ($j % 2 === 0 ? 'odd' : 'even'); ?><?php echo $class_paypal; ?>"><?php
						} elseif (in_array($arr[$i]['key'], array('payment_authorize_key', 'payment_authorize_mid'))) {
							?><tr class="<?php echo ($j % 2 === 0 ? 'odd' : 'even'); ?><?php echo $class_authorize; ?>"><?php
						} elseif (in_array($arr[$i]['key'], array('payment_worldpay_install_id'))) {
							?><tr class="<?php echo ($j % 2 === 0 ? 'odd' : 'even'); ?><?php echo $class_worldpay; ?>"><?php
						} else {
							?><tr class="<?php echo ($j % 2 === 0 ? 'odd' : 'even'); ?>"><?php
						}
						?>
							<td class="align_top"><?php echo html_entity_decode($arr[$i]['description']); ?></td>
							<td class="align_top">
								<?php
								switch ($arr[$i]['type'])
								{
									case 'string':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w300" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" /><?php
										break;
									case 'text':
										?><textarea name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="textarea w400 h230" style="<?php echo $arr[$i]['style']; ?>"><?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?></textarea><?php
										break;
									case 'int':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w50 align_right digits" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" />
										<?php
										if (in_array($arr[$i]['key'], array('reminder_sms_hours', 'reminder_email_before')))
										{
											echo $RB_LANG['option_hours_before'];
										}
										break;
									case 'float':
										?><input type="text" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" class="text w50 align_right number" value="<?php echo htmlspecialchars(stripslashes($arr[$i]['value'])); ?>" /><?php
										if ($arr[$i]['key'] == 'booking_price')
										{
											?>
											<span><?php echo $tpl['option_arr']['currency']; ?></span><?php
										}
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
									case 'bool':
										?>
										<input type="checkbox" name="value-<?php echo $arr[$i]['type']; ?>-<?php echo $arr[$i]['key']; ?>" value="1|0::1"<?php echo strpos($arr[$i]['value'], '::1') !== false ? ' checked="checked"' : NULL; ?> />
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
