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
	}
} else { ?>
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=schedule"><?php echo $RB_LANG['booking_schedule']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=1"><?php echo $RB_LANG['booking_list']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=2"><?php echo $RB_LANG['booking_find']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=paper"><?php echo $RB_LANG['booking_paper']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=customer"><?php echo $RB_LANG['booking_customer']; ?></a></li>
			<li class="ui-state-default ui-corner-top"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=statistics">Statistics</a></li>
			<li class="ui-state-default ui-corner-top  ui-tabs-selected ui-state-active"><a href="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;tab=6">Group booking</a></li>
		</ul>
	</div>
	
	<div class="ui-tabs ui-widget ui-widget-content ui-corner-all b10">
		<ul class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">
			<li class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active"><a href="#sub-tabs-1"><?php echo $RB_LANG['booking_update']; ?></a></li>
		</ul>
		<div class="ui-tabs-panel ui-widget-content ui-corner-bottom">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>?controller=pjAdminBookings&amp;action=index" method="post" class="form" id="frmTemplate" style="float:left">
				<input type="hidden" name="template" value="1" />
				<input type="hidden" name="id" value="<?php echo $tpl['arr']['id']?>" />
				<p>
					<label class="title">Name</label>
					<input type="text" name="name" class="text w150 pointer required pps" value="<?php echo $tpl['arr']['name']?>" />
				</p>
				<p>
					<label class="title">Subject</label>
					<input type="text" name="subject" class="text w150 pointer required pps"  value="<?php echo $tpl['arr']['subject']?>"/>
				</p>
				<p>
					<label class="title">Message</label>
					<textarea class="textarea required" name="message" cols="50" rows="5"><?php echo $tpl['arr']['message']?></textarea>
				</p>
				<p>
					<input type="submit" value="" class="button button_save"  />
				</p>
			</form>
		
			<div class="tokens w400" style="float: right;">
						
				<table class="table" style="width: 100%;">
					<thead>
						<tr>
							<th class="sub">Available Tokens:</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td>{Title}</td>
							<td>{DtFrom}</td>
						</tr>
						<tr>
							<td>{FirstName}</td>
							<td>{Table}</td>
						</tr>
						<tr>
							<td>{LastName}</td>
							<td>{People}</td>
						</tr>
						<tr>
							<td>{Email}</td>
							<td>{BookingID}</td>
						</tr>
						<tr>
							<td>{Phone}</td>
							<td>{UniqueID}</td>
						</tr>
						<tr>
							<td>{Notes}</td>
							<td>{Total}</td>
						</tr>
						<tr>
							<td>{Country}</td>
							<td>{PaymentMethod}</td>
						</tr>
						<tr>
							<td>{City}</td>
							<td>{CCType}</td>
						</tr>
						<tr>
							<td>{State}</td>
							<td>{CCNum}</td>
						</tr>
						<tr>
							<td>{Zip}</td>
							<td>{CCExp}</td>
						</tr>
						<tr>
							<td>{Address}</td>
							<td>{CCSec}</td>
						</tr>
						<tr>
							<td>{Company}</td>
							<td>{CancelURL}</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
}
?>