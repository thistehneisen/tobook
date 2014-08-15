<?php
if (!headers_sent())
{
    session_name('LoyaltyCard');
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
<?php require_once("common/config.php"); ?>
<?php require_once("../../DB_Connection.php"); ?>
<?php require_once("common/header.php"); ?>
<?php require_once("common/asset.php"); ?>
<?php require_once("common/functions.php"); ?>
<link rel="stylesheet" type="text/css" href="http://www.datatables.net/media/blog/bootstrap_2/DT_bootstrap.css">
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
$pageId = 1;
?>
</head>
<body>
	<br />
	<div class="container">
		<div class="col-md-3">
			<?php require_once("loyaltyLeftMenu.php"); ?>
		</div>
		<div class="col-md-9">
			<div class="floatright" style="margin-bottom: 5px;">
				<button class="btn-u btn-u-blue" onclick="onAddConsumer()" style="width: 90px;">
					<i class="icon-plus"></i>
					<?php echo $LC_LANG['add'];?>
				</button>
				<button class="btn-u btn-u-red" onclick="onDeleteConsumer()" style="width: 90px;">
					<i class="icon-trash"></i>
					<?php echo $LC_LANG['delete'];?>
				</button>
			</div>
			<div class="clearboth"></div>
			<div class="panel panel-orange margin-bottom-40">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="icon-user"></i>
						<?php echo $LC_LANG['consumerList'];?>
					</h3>
				</div>
				<?php
				$sql = "select *
				          from tbl_loyalty_consumer
				         where owner = $ownerId";
				$dataConsumer = $db->queryArray($sql);
				?>
				<table class="table table-striped" id="tblDataList">
					<thead>
						<tr>
							<th style="width: 60px;"><input type="checkbox" id="checkAll" onclick="onCheckAll( this )" /></th>
							<th style="width: 60px;">No</th>
							<th><?php echo $LC_LANG['consumer'];?></th>
							<th><?php echo $LC_LANG['email'];?></th>
							<th><?php echo $LC_LANG['phone'];?></th>
							<!-- th>Visit Count</th -->
							<th><?php echo $LC_LANG['lastVisited'];?></th>
						</tr>
					</thead>
					<tbody>
						<?php for($i = 0 ; $i < count( $dataConsumer ); $i++ ){?>
						<tr>
							<td>
							    <input type="checkbox" id="chkConsumerId" value="<?php echo $dataConsumer[$i]['loyalty_consumer']; ?>" />
							</td>
							<td><?php echo $i + 1; ?></td>
							<td>
							    <a href="consumerForm.php?id=<?php echo $dataConsumer[$i]['loyalty_consumer']; ?>"><?php echo $dataConsumer[$i]['first_name']." ".$dataConsumer[$i]['last_name']; ?></a>
							</td>
							<td>
							    <a href="consumerForm.php?id=<?php echo $dataConsumer[$i]['loyalty_consumer']; ?>"><?php echo $dataConsumer[$i]['email']; ?></a>
							</td>
							<td>
							    <a href="consumerForm.php?id=<?php echo $dataConsumer[$i]['loyalty_consumer']; ?>"><?php echo $dataConsumer[$i]['phone']; ?></a>
							</td>
							<td>
							    <a href="consumerForm.php?id=<?php echo $dataConsumer[$i]['loyalty_consumer']; ?>"><?php echo $dataConsumer[$i]['updated_time']; ?></a>
							</td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<script type="text/javascript" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/DT_bootstrap.js"></script>
<script type="text/javascript" src="js/consumerList.js"></script>
</body>
</html>
