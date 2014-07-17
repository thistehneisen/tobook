<?php
if (isset($tpl['status']))
{
	switch ($tpl['status'])
	{
		case 1:
			pjUtil::printNotice($RB_LANG['status'][1]);
			break;
		case 2:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
		case 3:
			pjUtil::printNotice($RB_LANG['status'][2]);
			break;
	}
} else {
	?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=reminder&amp;id=<?php echo $tpl['arr']['id']; ?>"><?php echo $RB_LANG['booking_remind']; ?></a></li>
		</ul>
	</div>
	<?php pjUtil::printNotice($RB_LANG['info']['booking_reminder']); ?>
	
	<div class="sub-tabs">
		<ul>
			<li><a href="#tabs-1">Default</a></li>
			<?php if ( isset($tpl['arr']['template_arr']) && count ($tpl['arr']['template_arr']) > 0 ) { 
					
					$i=1;
					foreach ($tpl['arr']['template_arr'] as $template) {
						$i++;
					?>
					
					<li><a href="#tabs-<?php echo $i; ?>"><?php echo $template['name']; ?></a></li>
			<?php } } ?>
		</ul>
		<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=reminder" method="post" class="form frmReminder" id="tabs-1">
			<input type="hidden" name="reminder" value="1" />
			<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
			<p>
				<label class="title"><?php echo $RB_LANG['booking_remind_to']; ?></label>
				<input type="text" name="to" id="to" class="text w450 required email" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_email'])); ?>" />
			</p>
			<p>
				<label class="title"><?php echo $RB_LANG['booking_remind_subject']; ?></label>
				<input type="text" name="subject" id="subject" class="text w450 required" value="<?php echo htmlspecialchars(stripslashes($tpl['option_arr']['email_confirmation_subject'])); ?>" />
			</p>
			<p>
				<label class="title"><?php echo $RB_LANG['booking_remind_message']; ?></label>
				<textarea name="message" id="message" class="textarea w550 h300"><?php echo str_replace($tpl['arr']['data']['search'], $tpl['arr']['data']['replace'], $tpl['option_arr']['email_confirmation_message']); ?></textarea>
			</p>
			<p>
				<label class="title">&nbsp;</label>
				<input type="submit" class="button button_send" value="" />
			</p>
		</form>
		
		<?php if ( isset($tpl['arr']['template_arr']) && count ($tpl['arr']['template_arr']) > 0 ) {
				
			$i=1;
			foreach ($tpl['arr']['template_arr'] as $template) {
				$i++;
				?>
							
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=reminder" method="post" class="form frmReminder" id="tabs-<?php echo $i; ?>">
					<input type="hidden" name="reminder" value="1" />
					<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']; ?>" />
					<p>
						<label class="title"><?php echo $RB_LANG['booking_remind_to']; ?></label>
						<input type="text" name="to" id="to" class="text w450 required email" value="<?php echo htmlspecialchars(stripslashes($tpl['arr']['c_email'])); ?>" />
					</p>
					<p>
						<label class="title"><?php echo $RB_LANG['booking_remind_subject']; ?></label>
						<input type="text" name="subject" id="subject" class="text w450 required" value="<?php echo htmlspecialchars(stripslashes($template['subject']) ); ?>" />
					</p>
					<p>
						<label class="title"><?php echo $RB_LANG['booking_remind_message']; ?></label>
						<textarea name="message" id="message" class="textarea w550 h300"><?php echo str_replace($tpl['arr']['data']['search'], $tpl['arr']['data']['replace'], $template['message']); ?></textarea>
					</p>
					<p>
						<label class="title">&nbsp;</label>
						<input type="submit" class="button button_send" value="" />
					</p>
				</form>
				
		<?php } } ?>
	</div>
	<?php
}
?>