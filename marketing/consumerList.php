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
<html lang="en"> <!--<![endif]-->
<head>
    <?php require_once("common/config.php"); ?>
    <?php require_once("common/DB_Connection.php"); ?>
    <?php require_once("common/header.php"); ?>
    <?php require_once("common/asset.php"); ?>
    <?php require_once("common/functions.php"); ?>    
	<link rel='stylesheet' href="css/footable.core.css" type='text/css' media='all'/>
	<link rel='stylesheet' href="css/footable.standalone.css" type='text/css' media='all'/>    
    
	<script src="js/footable.js" type="text/javascript"></script>
	<script src="js/footable.sort.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/consumerList.js"></script>    
        
    <?php
    	if( isset($_GET["username"]) && $_GET["username"] != "" ){
			$prefix = $_GET["username"];
			$ownerId = $_GET["userid"];
			$_SESSION["username"] = $prefix;
			$_SESSION["userid"] = $ownerId;
		}else{
			$prefix = $_SESSION["username"];
			$ownerId = $_SESSION["userid"];
		}
		
		$sql = "select * 
				  from tbl_loyalty_consumer
				
		";
    ?>
</head>
<body>
	<br/>
	<div class="container">
		<div class="col-md-3">            
            <ul class="list-group sidebar-nav-v1">
                <li class="list-group-item active"><a href="userList.php">Consumer Management</a></li>
                <li class="list-group-item "><a href="videoList.php">Stamps Management</a></li>
            </ul>
        </div>
        <div class="col-md-9">
			<div class="panel panel-sea margin-bottom-40">
				<div class="panel-heading">
					<h3 class="panel-title floatleft" style="line-height:30px;"><i class="icon-user"></i> User List</h3>
					<button class="floatright btn-u btn-u-red" onclick="onDeleteUser()" style="width: 90px;"><i class="icon-trash"></i> Delete</button>
					<button class="floatright btn-u btn-u-blue" onclick="onAddUser()" style="margin-right:10px;width: 90px;"><i class="icon-plus"></i> Add</button>
					<div class="clearboth"></div>
				</div>
				<?php
					$sql = "select * 
							  from rb_user";
					$dataUser = $db->queryArray( $sql ); 
				?>
				<table class="table table-striped" id="example">
					<thead>
						<tr>
							<th style="width:60px;"><input type="checkbox" id="checkAll" onclick="onCheckAll( this )"/></th>
							<th style="width:60px;">No</th>
							<th>Consumer</th>
							<th>Email</th>
							<th>Phone</th>
							<th>Visit Count</th>
							<th>Last Visited</th>
						</tr>
					</thead>
					<tbody>
						<?php for($i = 0 ; $i < count( $dataUser); $i ++ ){?>
						<tr>
							<td><input type="checkbox" id="chkUserId" value="<?php echo $dataUser[$i]['rb_user']; ?>"/></td>
							<td><?php echo $i + 1; ?></td>
							<td><a href="userDetail.php?id=<?php echo $dataUser[$i]['rb_user']; ?>"/><?php echo $dataUser[$i]['rb_username']; ?></a></td>
							<td><a href="userDetail.php?id=<?php echo $dataUser[$i]['rb_user']; ?>"/><?php echo $dataUser[$i]['rb_email']; ?></a></td>
							<td><a href="userDetail.php?id=<?php echo $dataUser[$i]['rb_user']; ?>"/><?php echo $dataUser[$i]['rb_name']; ?></a></td>
							<td><img src="<?php echo $dataUser[$i]['rb_photo']; ?>" style="width:32px;height:32px;"/></td>
							<td><a href="userDetail.php?id=<?php echo $dataUser[$i]['rb_cred']; ?>"/><?php echo $dataUser[$i]['rb_cred']; ?></a></td>
							<td><a href="userDetail.php?id=<?php echo $dataUser[$i]['rb_cred']; ?>"/><?php echo $dataUser[$i]['rb_created_time']; ?></a></td>														
						</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>         	
        </div>			           	
	</div>
</body>
</html>