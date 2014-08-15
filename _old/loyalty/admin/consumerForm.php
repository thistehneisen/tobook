<?php
if (!headers_sent()) {
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
<?php
require_once("common/config.php");
require_once("../../DB_Connection.php");
require_once("common/header.php");
require_once("common/asset.php");
require_once("common/functions.php");

$prefix = $_SESSION["username"];
$ownerId = $_SESSION["userid"];

if (isset($_GET['id']) && $_GET['id'] != "") {
	$consumerId = $_GET['id'];
	$sql = "select *
	          from tbl_loyalty_consumer
	         where loyalty_consumer = $consumerId";
	$dataConsumer = $db->queryArray($sql);
	$dataConsumer = $dataConsumer[0];
	$type = "Edit";
} else {
	$type = "Add";
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
			<div class="panel panel-orange margin-bottom-40">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="icon-user"></i>
						<?php echo $LC_LANG['consumerManagement'];?>
					</h3>
				</div>
				<div class="panel-body">
					<div class="form-horizontal">
						<input type="hidden" id="type" value="<?php echo $type;?>" /> <input
							type="hidden" id="consumerId" value="<?php echo $consumerId?>" />
						<input type="hidden" id="ownerId" value="<?php echo $ownerId?>" />
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['firstName'];?>
							</label>
							<div class="col-lg-4">
								<input type="text"
									value="<?php echo $dataConsumer['first_name']?>"
									class="form-control" id="firstName"
									placeholder="<?php echo $LC_LANG['firstName'];?>">
							</div>
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['lastName'];?>
							</label>
							<div class="col-lg-4">
								<input type="text"
									value="<?php echo $dataConsumer['last_name']?>"
									class="form-control" id="lastName"
									placeholder="<?php echo $LC_LANG['lastName'];?>">
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['emailAddress'];?>
							</label>
							<div class="col-lg-4">
								<input type="text" value="<?php echo $dataConsumer['email']?>"
									class="form-control" id="email"
									placeholder="<?php echo $LC_LANG['emailAddress'];?>">
							</div>
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['phone'];?>
							</label>
							<div class="col-lg-4">
								<input type="text" value="<?php echo $dataConsumer['phone']?>"
									class="form-control" id="phone"
									placeholder="<?php echo $LC_LANG['phone'];?>">
							</div>
						</div>

						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['address1'];?>
							</label>
							<div class="col-lg-4">
								<input type="text"
									value="<?php echo $dataConsumer['address1']?>"
									class="form-control" id="address1"
									placeholder="<?php echo $LC_LANG['address1'];?>">
							</div>
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['city'];?>
							</label>
							<div class="col-lg-4">
								<input type="text" value="<?php echo $dataConsumer['city']?>"
									class="form-control" id="city"
									placeholder="<?php echo $LC_LANG['city'];?>">
							</div>
						</div>

						<?php if( $type == "Edit" ){?>
						<div class="form-group">
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['createdTime'];?>
							</label>
							<div class="col-lg-4">
								<input type="text"
									value="<?php echo $dataConsumer['created_time']?>"
									class="form-control" readonly
									style="background: #FEFEFE; cursor: pointer;">
							</div>
							<label class="col-lg-2 control-label"><?php echo $LC_LANG['updatedTime'];?>
							</label>
							<div class="col-lg-4">
								<input type="text"
									value="<?php echo $dataConsumer['updated_time']?>"
									class="form-control" readonly
									style="background: #FEFEFE; cursor: pointer;">
							</div>
						</div>
						<?php }?>

						<div class="form-group" style="margin-top: 40px;">
							<div class="col-lg-offset-1 col-lg-10"
								style="text-align: center;">
								<button class="btn-u btn-u-blue"
									style="margin-right: 20px; width: 120px;"
									onclick="onConsumerSave()">
									<i class="icon-edit"></i>
									<?php echo $LC_LANG['save'];?>
								</button>
								<button class="btn-u btn-u-red" style="width: 120px;"
									onclick="window.location.href='consumerList.php'">
									<i class="icon-list"></i>
									<?php echo $LC_LANG['list'];?>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<script type="text/javascript" src="js/consumerForm.js"></script>	
</body>
</html>
