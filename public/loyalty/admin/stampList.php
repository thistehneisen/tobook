<?php
require_once realpath(__DIR__.'/../../../Bridge.php');
//------------------------------------------------------------------------------
// Check if the current user are still in the core
// If not, kick him out
// An Cao <an@varaa.com>
//------------------------------------------------------------------------------
if (!Bridge::hasOwnerId()) {
	@session_destroy();
	echo <<< JS
<script>
window.parent.location = '/business/auth/login';
</script>
JS;
	exit;
}

if (!headers_sent()) {
    // session_name('LoyaltyCard');
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
<?php require_once("common/config.php");
require_once("../../DB_Connection.php");
require_once("common/header.php");
require_once("common/asset.php");
require_once("common/functions.php");
$prefix = $_SESSION["username"];
$ownerId = $_SESSION["userid"];
$pageId = 2;
?>
<link rel="stylesheet" type="text/css" href="http://www.datatables.net/media/blog/bootstrap_2/DT_bootstrap.css">
</head>
<body>
	<br />
	<div class="container">
		<div class="col-md-3">
			<?php require_once("loyaltyLeftMenu.php"); ?>
		</div>
		<div class="col-md-9">
			<div class="floatright" style="margin-bottom: 5px;">
				<button class="btn-u btn-u-blue" onclick="onAddStamp()"
					style="width: 90px;">
					<i class="icon-plus"></i>
					<?php echo $LC_LANG['add'];?>
				</button>
				<button class="btn-u btn-u-red" onclick="onDeleteStamp()"
					style="width: 90px;">
					<i class="icon-trash"></i>
					<?php echo $LC_LANG['delete'];?>
				</button>
			</div>
			<div class="clearboth"></div>
			<div class="panel panel-orange margin-bottom-40">
				<div class="panel-heading">
					<h3 class="panel-title">
						<i class="icon-user"></i>
						<?php echo $LC_LANG['stampList'];?>
					</h3>
				</div>
				<?php
				$sql = "select t1.*, ifnull( t2.cnt, 0 ) cnt_used
				          from tbl_loyalty_stamp t1
				          left join (
				                    select count(*) cnt, loyalty_stamp
				                      from tbl_loyalty_consumer_stamp
				                     group by loyalty_stamp
				                    ) t2
				            on t1.loyalty_stamp = t2.loyalty_stamp
				         where t1.owner = $ownerId";
				$dataStamp = $db->queryArray($sql);
				?>
				<table class="table table-striped" id="tblDataList">
					<thead>
						<tr>
							<th style="width: 60px;"><input type="checkbox" id="checkAll"
								onclick="onCheckAll( this )" /></th>
							<th style="width: 60px;">No</th>
							<th><?php echo $LC_LANG['stampName'];?></th>
							<th><?php echo $LC_LANG['totalUsed'];?></th>
							<th><?php echo $LC_LANG['required'];?></th>
							<th><?php echo $LC_LANG['free'];?></th>
							<th><?php echo $LC_LANG['autoAdd'];?></th>
							<th><?php echo $LC_LANG['status'];?></th>
						</tr>
					</thead>
					<tbody>
						<?php for ($i = 0 ; $i < count( $dataStamp ); $i++) {?>
						<tr>
							<td><input type="checkbox" id="chkStampId"
								value="<?php echo $dataStamp[$i]['loyalty_stamp']; ?>" /></td>
							<td><?php echo $i + 1; ?></td>
							<td>
							    <a href="stampForm.php?id=<?php echo $dataStamp[$i]['loyalty_stamp']; ?>"><?php echo $dataStamp[$i]['stamp_name']; ?></a>
							</td>
							<td><?php echo $dataStamp[$i]['cnt_used']; ?></td>
							<td><?php echo $dataStamp[$i]['cnt_required']; ?></td>
							<td><?php echo $dataStamp[$i]['cnt_free']; ?></td>
							<td><?php
							if ($dataStamp[$i]['auto_add_yn'] == "Y") {
                                echo "Yes";
                            } else {
                                echo "No";
                            }
                            ?>
							</td>
							<td><?php echo $dataStamp[$i]['valid_yn']; ?></td>
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
<script type="text/javascript" src="js/jquery.dataTables.js"></script>
<script type="text/javascript" src="js/DT_bootstrap.js"></script>
<script type="text/javascript" src="js/stampList.js"></script>	
</body>
</html>
