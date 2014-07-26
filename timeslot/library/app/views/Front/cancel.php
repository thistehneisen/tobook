<!doctype html>
<html>
	<head>
		<title><?php echo $TS_LANG['front']['cancel_note']; ?></title>
		<?php
		foreach ($controller->css as $css)
		{
			echo '<link type="text/css" rel="stylesheet" href="'.(isset($css['remote']) && $css['remote'] ? NULL : BASE_PATH).$css['path'].htmlspecialchars($css['file']).'" />';
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
					?><p><?php echo $TS_LANG['front']['cancel_err'][1]; ?></p><?php
					break;
				case 2:
					?><p><?php echo $TS_LANG['front']['cancel_err'][2]; ?></p><?php
					break;
				case 3:
					?><p><?php echo $TS_LANG['front']['cancel_err'][3]; ?></p><?php
					break;
				case 4:
					?><p><?php echo $TS_LANG['front']['cancel_err'][4]; ?></p><?php
					break;
			}
		} else {
			
			if (isset($_GET['err']))
			{
				switch ((int) $_GET['err'])
				{
					case 200:
						?><p><?php echo $TS_LANG['front']['cancel_err'][200]; ?></p><?php
						break;
				}
			}
			
			if (isset($tpl['arr']))
			{
				?>
				<table cellspacing="2" cellpadding="5" style="width: 100%">
					<thead>
						<tr>
							<th colspan="2" style="text-transform: uppercase; text-align: left"><?php echo $TS_LANG['front']['cancel_heading']; ?></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td style="vertical-align: top"><?php echo $TS_LANG['front']['cancel_datetime']; ?></td>
							<td>
							<?php
							foreach ($tpl['arr']['slot_arr'] as $v)
							{
								echo date($tpl['option_arr']['date_format'], $v['start_ts']); ?> | <?php echo date($tpl['option_arr']['time_format'], $v['start_ts']); ?> - <?php echo date($tpl['option_arr']['time_format'], $v['end_ts']); ?><br /><?php
							}
							?>
							</td>
						</tr>
						<tr>
							<td><?php echo $TS_LANG['front']['cancel_name']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['customer_name']); ?></td>
						</tr>
						<tr>
							<td><?php echo $TS_LANG['front']['cancel_email']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['customer_email']); ?></td>
						</tr>
						<tr>
							<td><?php echo $TS_LANG['front']['cancel_phone']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['customer_phone']); ?></td>
						</tr>
						<tr>
							<td><?php echo $TS_LANG['front']['cancel_country']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['country_title']); ?></td>
						</tr>
						<tr>
							<td><?php echo $TS_LANG['front']['cancel_city']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['customer_city']); ?></td>
						</tr>
						<tr>
							<td><?php echo $TS_LANG['front']['cancel_address']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['customer_address']); ?></td>
						</tr>
						<tr>
							<td><?php echo $TS_LANG['front']['cancel_zip']; ?></td>
							<td><?php echo stripslashes($tpl['arr']['customer_zip']); ?></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="2">
								<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=Front&amp;action=cancel" method="post">
									<input type="hidden" name="booking_cancel" value="1" />
									<input type="hidden" name="id" value="<?php echo $_GET['id']; ?>" />
									<input type="hidden" name="hash" value="<?php echo $_GET['hash']; ?>" />
									<input type="submit" value="<?php echo htmlspecialchars($TS_LANG['front']['cancel_confirm']); ?>" />
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