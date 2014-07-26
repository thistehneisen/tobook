<!doctype html>
<html>
	<head>
		<title><?php echo $RB_LANG['front']['cancel_note']; ?></title>
		<?php
		foreach ($controller->css as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : INSTALL_URL).$css['path'].htmlspecialchars($css['file']).'" />';
		}
		?>
	</head>
	<body>
		<div style="margin: 0 auto; width: 450px">
		<?php
		if (isset($tpl['status']))
		{
			switch ($tpl['status'])
			{
				case 1:
					?><p><?php echo $RB_LANG['front']['cancel_err'][1]; ?></p><?php
					break;
				case 2:
					?><p><?php echo $RB_LANG['front']['cancel_err'][2]; ?></p><?php
					break;
				case 3:
					?><p><?php echo $RB_LANG['front']['cancel_err'][3]; ?></p><?php
					break;
				case 4:
					?><p><?php echo $RB_LANG['front']['cancel_err'][4]; ?></p><?php
					break;
			}
		} else {
			
			if (isset($_GET['err']))
			{
				switch ((int) $_GET['err'])
				{
					case 200:
						?><p><?php echo $RB_LANG['front']['cancel_err'][200]; ?></p><?php
						break;
				}
			}
			
			if (isset($tpl['arr']))
			{
				?>
				<table cellspacing="2" cellpadding="5" style="width: 100%">
					<thead>
						<tr>
							<th colspan="2" style="text-transform: uppercase; text-align: left"><?php echo $RB_LANG['front']['cancel_heading']; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_from']; ?></td>
							<td><?php echo date($tpl['option_arr']['datetime_format'], strtotime($tpl['arr']['dt'])); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_people']; ?></td>
							<td><?php echo $tpl['arr']['people']; ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_code']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['code']); ?></td>
						</tr>
						<?php
						foreach ($tpl['arr']['table_arr'] as $k => $v)
						{
							?><tr><td><?php echo $RB_LANG['front']['cancel_table']; ?> <?php echo $k + 1; ?></td><td><?php
							$cell = array();
							$cell[] = stripslashes($v['name']);
							echo join(" / ", $cell);
							?></td></tr><?php
						}
						?>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_payment']; ?></td>
							<td><?php echo @$RB_LANG['_payments'][$tpl['arr']['payment_method']]; ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_total']; ?></td>
							<td><?php echo pjUtil::formatCurrencySign(number_format($tpl['arr']['total'], 2), $tpl['option_arr']['currency']); ?></td>
						</tr>
						<tr>
							<td colspan="2" style="font-weight: bold"><?php echo $RB_LANG['front']['cancel_personal']; ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_title']; ?></td>
							<td><?php echo @$RB_LANG['_titles'][$tpl['arr']['c_title']]; ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_fname']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_fname']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_lname']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_lname']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_phone']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_phone']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_email']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_email']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_company']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_company']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_address']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_address']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_city']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_city']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_state']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_state']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_zip']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['c_zip']); ?></td>
						</tr>
						<tr>
							<td><?php echo $RB_LANG['front']['cancel_country']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['country_title']); ?></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjFront&amp;action=cancel" method="post">
									<input type="hidden" name="booking_cancel" value="1" />
									<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
									<input type="hidden" name="hash" value="<?php echo $_GET['hash']; ?>" />
									<input type="submit" value="<?php echo htmlspecialchars($RB_LANG['front']['cancel_confirm']); ?>" />
								</form>
							</td>
						</tr>
					</tfoot>
				</table>
				<?php
			}
		}
		?>
		</div>
	</body>
</html>