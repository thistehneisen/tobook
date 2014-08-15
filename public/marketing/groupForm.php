<?php
if (!headers_sent()) {

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
$prefix = $_SESSION["username"];
$ownerId = $_SESSION["userid"];

if ( isset($_GET['id']) ) {
	$groupId = base64_decode($_GET['id']);
	$sql = "select * from tbl_marketing_group where marketing_group = $groupId";
	$dataGroup = $db->queryArray($sql);
	$dataGroup = $dataGroup[0];
	$pageType = "EDIT";

	$sql = "select * from tbl_marketing_group_member where marketing_group = $groupId";
	$memberList = $db->queryArray($sql);
} else {
	$pageType = "ADD";
}
?>
</head>
<body>
	<input type="hidden" value="<?php echo $ownerId;?>" id="ownerId" />
	<input type="hidden" value="<?php echo $campaignId;?>" id="campaignId" />
	<input type="hidden" value="<?php echo $groupId?>" id="groupId" />
	<div class="container">
		<div style="width: 800px; margin: 20px auto;">
			<h3 style="color: #e67e22;">
				<?php echo __('groupManagement');?>
			</h3>
		</div>
		<div style="width: 800px; margin: 20px auto;">
			<div class="floatright">
				<button class="btn-u btn-u-orange" onclick="onSaveGroup()">
					<i class="icon-edit"></i>&nbsp;
					<?php echo __('saveGroup');?>
				</button>
				<button class="btn-u btn-u-blue"
					onclick="window.location.href='groupList.php'">
					<i class="icon-list-ul"></i>&nbsp;
					<?php echo __('groupList');?>
				</button>
			</div>
			<div class="clearboth"></div>
			<br />
			<div class="form-group">
				<label><?php echo __('groupName');?> </label> <input
					type="text" id="groupName" class="form-control"
					value="<?php echo $dataGroup['group_name']?>" />
			</div>
			<hr />
			<?php if ($pageType == "EDIT") {?>
			<div class="floatright">
				<button class="btn-u btn-u-red" onclick="onDeleteMember()">
					<i class="icon-edit"></i>&nbsp;
					<?php echo __('deleteMember');?>
				</button>
			</div>
			<div class="clearboth"></div>
			<div class="panel panel-orange margin-bottom-40"
				style="margin-top: 20px;">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="icon-envelope"></i>&nbsp;
						<?php echo __('memberList');?>
					</h3>
				</div>
				<table class="table" id="memberList">
					<thead>
						<tr>
							<th style="width: 60px; text-align: center;"
								data-sort-ignore="true"><input type="checkbox" id="chkAllMember"
								onclick="onCheckAllMember( this )" /></th>
							<th style="width: 60px; text-align: center;"
								data-sort-ignore="true">No</th>
							<th style="text-align: center;"><?php echo __('pluginType');?>
							</th>
							<th style="text-align: center;"><?php echo __('email');?></th>
							<th style="text-align: center;"><?php echo __('phone');?></th>
							<th style="width: 160px; text-align: center;"><?php echo __('createdTime');?>
							</th>
						</tr>
					</thead>
					<tbody>
						<?php
						for ($i = 0; $i < count($memberList); $i++) {
                        ?>
						<tr>
							<td style="text-align: center;"><input type="hidden"
								id="memberId"
								value="<?php echo $memberList[$i]['marketing_group_member']?>" />
								<input type="checkbox" id="chkMember" />
							</td>
							<td style="text-align: center;"><?php echo $i + 1;?></td>
							<td>
							<?php 
							if ($memberList[$i]['plan_group_code'] == "as") {
								echo "Appointment Scheduler";
							} elseif ($memberList[$i]['plan_group_code'] == "rb") {
								echo "Restaurnat Bookings";
							} elseif ($memberList[$i]['plan_group_code'] == "tb") {
								echo "Timeslot Bookings";
							}
							?>
							</td>
							<td><?php echo $memberList[$i]['email']?></td>
							<td><?php echo $memberList[$i]['phone']?></td>
							<td style="text-align: center;"><?php echo $memberList[$i]['created_time']?>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
			<?php } ?>
		</div>
	</div>
<script src="js/footable.js" type="text/javascript"></script>
<script src="js/footable.sort.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/groupForm.js"></script>
</body>
</html>
