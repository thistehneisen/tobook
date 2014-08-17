<?php
if (!headers_sent())
{

    @session_start();
}
?>
<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en">
<!--<![endif]-->
<head>
<?php
require_once("common/config.php");
require_once("../DB_Connection.php");
require_once("common/header.php");
require_once("common/asset.php");
require_once("common/functions.php");
?>
<link rel='stylesheet' href="css/footable.core.css" type='text/css' media='all' />
<link rel='stylesheet' href="css/footable.standalone.css" type='text/css' media='all' />
<link rel='stylesheet' href="css/datepicker.css" type='text/css' media='all' />
<?php

if (isset($_GET["username"]) && $_GET["username"] != "") {
    $prefix = $_GET["username"];
    $ownerId = $_GET["userid"];
    $_SESSION["username"] = $prefix;
    $_SESSION["userid"] = $ownerId;
} else {
    $prefix = $_SESSION["username"];
    $ownerId = $_SESSION["userid"];
}
$startDate = getVal('startDate');
$endDate = getVal('endDate');

$tb = getVal('tb');
$rb = getVal('rb');
$as = getVal('as');
$hb = getVal('hb');

$sql1 = "select max(id) as cId, concat(c_fname, ' ', c_lname) as cName, c_email as cEmail, c_phone as cPhone, 'rb' as planGroupCode, max(created) as bookingTime
           from rb_bookings
          where owner_id = $ownerId
          group by cEmail, cPhone";

$sql2 = "select id as cId, customer_name as cName, customer_email as cEmail, customer_phone as cPhone, 'tb' as planGroupCode, created as bookingTime
           from ts_bookings
          where owner_id = $ownerId
          group by cEmail, cPhone";

$sql3 = "select id as cId, c_name as cName, c_email as cEmail, c_phone as cPhone, 'as' as planGroupCode, created as bookingTime
           from as_bookings
          where owner_id = $ownerId
          group by cEmail, cPhone";

$sqlSub = "$sql1 union all $sql2 union all $sql3";
$sql = "select * from ( $sqlSub ) t1 where cId != 0";

if ($startDate != "")
    $sql .= " and t1.bookingTime >= '$startDate 00:00:00'";
if ($endDate != "")
    $sql .= " and t1.bookingTime <= '$endDate 23:59:59'";

$strTemp = "1";
if ($tb == "Y") $strTemp .= ", 'tb'";
if ($rb == "Y") $strTemp .= ", 'rb'";
if ($as == "Y") $strTemp .= ", 'as'";
$sql .=" and t1.planGroupCode in ($strTemp)";

$customerList = $db->queryArray($sql);

$sql = "select date(now()) as today";
$dataResult = $db->queryArray($sql);
$today = $dataResult[0]['today'];
?>
</head>
<body>
	<br>
	<input type="hidden" id="ownerId" value="<?php echo $ownerId;?>" />
	<input type="hidden" id="CREDITS_PRICE"
		value="<?php echo CREDITS_PRICE;?>" />
	<div class="container">
		<div class="floatright" style="margin-bottom: 10px;">
			<a class="btn btn-link" href='campaignList.php'
				style="font-weight: bold;"><?php echo __('campaignManagement');?>
			</a> &nbsp;|&nbsp; <a class="btn btn-link" href='automationList.php'
				style="font-weight: bold;"><?php echo __('automationManagement');?>
			</a> &nbsp;|&nbsp; <a class="btn btn-link" href='groupList.php'
				style="font-weight: bold;"><?php echo __('groupManagement');?>
			</a>
		</div>
		<div class="clearboth"></div>
		<div class="panel panel-orange margin-bottom-40">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="icon-search"></i>
					<?php echo __('searchCustomers');?>
				</h3>
			</div>
			<div class="panel-body">
				<div>
					<label class="floatleft marginRight20" style="padding-top: 7px;">
					    <b><?php echo __('searchDate');?> :</b>
					</label>
					<input type="text" id="txtStartDate"
						class="floatleft form-control marginRight20"
						style="width: 120px; text-align: center; cursor: pointer; background: #FFF;"
						readonly placeholder="<?php echo __('startDate');?>"
						value="<?php echo $startDate;?>" /> <input type="text"
						id="txtEndDate" class="floatleft form-control marginRight20"
						style="width: 120px; text-align: center; cursor: pointer; background: #FFF;"
						readonly placeholder="<?php echo __('endDate');?>"
						value="<?php echo $endDate;?>" />
					<button class="btn-u btn-u-orange" onclick="onResetDate()">
						<?php echo __('reset');?>
					</button>
					<div class="clearboth"></div>
				</div>
				<div style="margin-top: 15px;">
					<label class="checkbox-inline" style="padding-right: 30px;">
					    <input
						type="checkbox" id="chkGroupAll" value="all"
						onclick="onAllGroup(this)"> <b><u><?php echo __('allGroup');?></u></b>
					</label>
					<label class="checkbox-inline">
					    <input type="checkbox"
						id="chkTimeslot" value="tb"
						<?php if ($tb == "Y") echo "checked"; ?>> <?php echo __('timeslotBookings'); ?>
					</label>
					<label class="checkbox-inline">
					    <input type="checkbox"
						id="chkRestaurant" value="rb"
						<?php if ($rb == "Y") echo "checked"; ?>> <?php echo __('restaurantBookings'); ?>
					</label>
					<label class="checkbox-inline">
					    <input type="checkbox"
						id="chkAppointment" value="as"
						<?php if ($as == "Y") echo "checked"; ?>> <?php echo __('appointmentScheduler'); ?>
					</label>
					<label class="checkbox-inline" style="display: none;">
					    <input
						type="checkbox" id="chkHairBeauty" value="hb"
						<?php if ($hb == "Y") echo "checked"; ?>> Hair &amp; Beauty Bookings
					</label>
				</div>
				<hr />
				<div style="text-align: center;">
					<button class="btn-u btn-u-blue" onclick="onSearch()">
						<i class="icon-search"></i>&nbsp;
						<?php echo __('search');?>
					</button>
				</div>
			</div>
		</div>
		<?php
		$sql = "select *
		          from tbl_owner_premium
		         where owner = $ownerId
		           and plan_group_code = 'mt'";
		$dataResult = $db->queryArray($sql);
		if ($dataResult == null) {
    		$credits = 0;
    		$alreadyPaidYn = "N";
    	} else {
			$credits = $dataResult[0]['credits'];
			// $credits = 0;
			$alreadyPaidYn = "Y";
		}
		?>
		<input type="hidden" id="alreadyPaidYn"
			value="<?php echo $alreadyPaidYn?>" />
		<div class="floatleft">
			<button class="btn-u btn-u-orange" onclick="onOpenEmail()">
				<i class="icon-envelope"></i>&nbsp;
				<?php echo __('sendEmail');?>
			</button>
			<button class="btn-u btn-u-orange" onclick="onOpenSMS()">
				<i class="icon-comment"></i>&nbsp;
				<?php echo __('sendSms');?>
			</button>

			<button class="btn-u btn-u-blue" onclick="onShowGroup()"
				style="margin-left: 15px;">
				<i class="icon-group"></i>&nbsp;
				<?php echo __('joinGroup')?>
			</button>
		</div>
		<div class="floatright">
			<span style="padding-right: 20px;"><span
				style="color: #e67e22; padding-right: 30px; font-size: 16px;"><b>1
						Euro = <?php echo CREDITS_PRICE;?> Credits
				</b> </span><b><span id="cntCredits"><?php echo $credits;?> </span>
					Credits</b> </span>
			<button class="btn-u btn-u-blue" onclick="parent.onOpenPayment();">
				<i class="icon-money"></i>&nbsp;
				<?php echo __('buyCredits');?>
			</button>
		</div>
		<div class="clearboth"></div>
		<div class="panel panel-orange margin-bottom-40"
			style="margin-top: 20px;">
			<div class="panel-heading">
				<h3 class="panel-title">
					<i class="icon-user"></i>
					<?php echo __('customerList');?>
				</h3>
			</div>

			<table class="table" id="customerList">
				<thead>
					<tr>
						<th data-sort-ignore="true"><input type="checkbox"
							id="chkAllCustomer" onclick="onCheckAllCustomer( this )" /></th>
						<th data-sort-ignore="true">No</th>
						<th><?php echo __('name');?></th>
						<th><?php echo __('emailAddress');?></th>
						<th><?php echo __('phoneNo');?></th>
						<th><?php echo __('customerGroup');?></th>
						<th><?php echo __('lastBookingDate');?></th>
						<th data-sort-ignore="true"></th>
					</tr>
				</thead>
				<tbody>
					<?php for ($i = 0; $i < count( $customerList ); $i++) {?>
					<tr>
						<td>
						    <input type="hidden" id="customerId" value="<?php echo $customerList[$i]['cId']?>" />
						    <input type="hidden" id="planGroupCode" value="<?php echo $customerList[$i]['planGroupCode']?>" />
						    <input type="checkbox" id="chkCustomer" />
						</td>
						<td><?php echo $i + 1;?></td>
						<td><?php echo $customerList[$i]['cName']?></td>
						<td><?php echo $customerList[$i]['cEmail']?></td>
						<td><?php echo $customerList[$i]['cPhone']?></td>
						<td>
						<?php
						if ($customerList[$i]['planGroupCode'] == "tb") {
							echo __('timeslotBookings');
						} elseif ($customerList[$i]['planGroupCode'] == "rb") {
							echo __('restaurantBookings');
						} elseif ( $customerList[$i]['planGroupCode'] == "as") {
							echo __('appointmentScheduler');
						}
						?>
						</td>
						<td><?php echo $customerList[$i]['bookingTime']?></td>
						<td>
							<button class="btn btn-info btn-xs" onclick="onEditCustomer('<?php echo $customerList[$i]['planGroupCode']?>', <?php echo $customerList[$i]['cId']?> )">
								<?php echo __('edit');?>
							</button>
						</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>
		</div>
	</div>
	<div id="divSMSArea" class="unshow">
		<span><?php echo __('smsTitle');?> </span>
		<span style="padding-left: 20px;">(&nbsp;<span id="lengthSMSTitle"></span>&nbsp;/&nbsp;40&nbsp;)</span>
		<input type="text" class="form-control" id="titleSMS" style="margin-top: 5px; margin-bottom: 15px;" maxlength="40">
		<span><?php echo __('smsText');?></span>
		<textarea class="form-control" id="txtSMS" rows="5" style="margin-top: 5px; margin-bottom: 15px;" maxlength="160"></textarea>
		<div class="floatleft">
			<span style="color: #F00;">*</span>&nbsp;<span id="lengthSMSText"></span>&nbsp;/160
		</div>
		<div class="floatright">
			<button class="btn-u btn-u-blue" onclick="onSendSMS()">
				<?php echo __('send')?>
			</button>
			<button class="btn-u btn-u-orange" onclick="onCloseSMS()">
				<?php echo __('cancel')?>
			</button>
		</div>
		<div class="clearboth"></div>
	</div>
	<div id="divEmailArea" class="unshow">
		<div style="height: 45px; background: #3498db; color: #FFF; line-height: 45px; font-size: 20px; padding-left: 20px;">
			<?php echo __('selectCampaignToSend');?>
		</div>
		<?php
		$sql = "select *
                  from tbl_email_campaign
                 where status = 'DRAFT'";
		$dataCampaign = $db->queryArray($sql);
		?>
		<div id="campaignList" style="background: #FDFDFD; height: 350px; margin: 20px 30px 0px 30px; border: 1px solid #EEE; overflow: auto;">
			<?php for ($i = 0; $i < count( $dataCampaign ); $i++) {?>
			<div id="campaignItem" style="height: 35px; line-height: 35px; border-bottom: 1px solid #DDD; padding-left: 20px; cursor: pointer;" onclick="onClickCampaignItem(this)">
				<?php echo $dataCampaign[$i]['subject'];?>
				<input type="hidden" id="campaignId" value="<?php echo $dataCampaign[$i]['email_campaign'];?>" />
			</div>
			<?php }
			if (count( $dataCampaign ) == 0) {
				echo "<h2 style='padding-left:10px;padding-top:10px;'>".__('msgNoCampaign')."</h2>";
			} ?>

		</div>
		<hr />
		<div style="text-align: center;">
			<input type="text" class="form-control" id="scheduleDate" style="width: 120px; display: initial; margin-top: -3px; text-align: center;" value="<?php echo $today;?>" />&nbsp;
			<select class="form-control" style="width: 70px; display: initial; margin-top: -3px;" id="scheduleHour">
				<?php for ($i = 0; $i < 24; $i++) {?>
				<option value="<?php echo $i;?>">
					<?php echo $i<10?"0".$i:$i?>
				</option>
				<?php } ?>
			</select>&nbsp;&nbsp;
			<button class="btn-u btn-u-red" onclick="onEmailSchedule()">
				<?php echo __('schedule')?>
			</button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button class="btn-u btn-u-blue" onclick="onSendEmail()">
				<?php echo __('sendNow')?>
			</button>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<button class="btn-u btn-u-orange" onclick="onCloseEmail()">
				<?php echo __('cancel')?>
			</button>
		</div>
		<div class="clearboth"></div>
	</div>

	<div id="divBlackBackground" class="unshow"
		onclick="onCloseSMS();onCloseEmail();"></div>

	<div class="modal fade" id="myModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only"><?php echo __('close')?>
						</span>
					</button>
					<h4 class="modal-title">
						<?php echo __('customerInformation')?>
					</h4>
				</div>
				<div class="modal-body">
					<div style="width: 70%; float: left;">
						<input type="hidden" id="cId" /> <input type="hidden"
							id="pGroupCode" />

						<div style="width: 25%; float: left; font-weight: bold; text-align: right; line-height: 30px;">
							<?php echo __('name')?>:
						</div>
						<div style="padding-left: 5%; width: 70%; float: left;">
							<input type="text" id="txtName" class="form-control" />
						</div>
						<div style="clear: both;"></div>
						<br />
						<div style="width: 25%; float: left; font-weight: bold; text-align: right; line-height: 30px;">
							<?php echo __('email')?>:
						</div>
						<div style="padding-left: 5%; width: 70%; float: left;">
							<input type="text" id="txtEmail" class="form-control" />
						</div>
						<div style="clear: both;"></div>
						<br />
						<div style="width: 25%; float: left; font-weight: bold; text-align: right; line-height: 30px;">
							<?php echo __('phone')?>:
						</div>
						<div style="padding-left: 5%; width: 70%; float: left;">
							<input type="text" id="txtPhone" class="form-control" />
						</div>
						<div style="clear: both;"></div>
						<br />
						<div style="width: 25%; float: left; font-weight: bold; text-align: right; line-height: 30px;">
							<?php echo __('note')?>:
						</div>
						<div style="padding-left: 5%; width: 70%; float: left;">
							<textarea id="txtNote" class="form-control" rows="4"></textarea>
						</div>
						<div style="clear: both;"></div>
						<br />
						<div style="width: 25%; float: left; font-weight: bold; text-align: right; line-height: 30px;">
							<?php echo __('count')?>:
						</div>
						<div style="padding-left: 5%; width: 70%; float: left;">
							<input type="text" id="txtBookedCnt" readonly class="form-control" />
						</div>
						<div style="clear: both;"></div>
						<br />
					</div>
					<div style="width: 30%; float: left;">
						<div>
							<b><?php echo __('previousBookings')?> </b>
						</div>
						<div id="bookingList"></div>
					</div>
					<div style="clear: both;"></div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal"
						id="btnClose">
						<?php echo __('close')?>
					</button>
					<button type="button" class="btn btn-primary"
						onclick="onSaveCustomer()">
						<?php echo __('save')?>
					</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->

	<?php
	$sql = "select *
	          from tbl_marketing_group
	         where owner = $ownerId";
	$groupList = $db->queryArray($sql);

	?>
	<div class="modal fade" id="myModalGroupList">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal">
						<span aria-hidden="true">&times;</span><span class="sr-only"><?php echo __('close')?>
						</span>
					</button>
					<h4 class="modal-title">
						<?php echo __('groupList');?>
					</h4>
				</div>
				<div class="modal-body">
					<div id="groupList">
						<?php for ($i = 0 ; $i < count( $groupList ); $i++) {?>
						<div id="groupItem"
							data="<?php echo $groupList[$i]['marketing_group'];?>"
							onclick="onClickGroupItem( this )">
							<?php echo $groupList[$i]['group_name'];?>
						</div>
						<?php } ?>
						<?php if (count( $groupList ) == 0) { ?>
						<div>
							<?php echo __('msgNoGroup');?>
						</div>
						<?php } ?>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default" data-dismiss="modal" id="btnClose1">
						<?php echo __('close')?>
					</button>
					<button type="button" class="btn btn-primary" onclick="onJoinGroup()">
						<?php echo __('join')?>
					</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
<script src="js/footable.js" type="text/javascript"></script>
<script src="js/footable.sort.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/main.js"></script>
</body>
</html>
