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
    <?php require_once("../../DB_Connection.php"); ?>
    <?php require_once("common/header.php"); ?>
    <?php require_once("common/asset.php"); ?>
    <?php require_once("common/functions.php"); ?>    
	
    <script type="text/javascript" src="js/pointForm.js"></script>    
        
    <?php
		$prefix = $_SESSION["username"];
		$ownerId = $_SESSION["userid"];
						
		if( isset($_GET['id']) && $_GET['id'] != "" ){
			$pointId = $_GET['id'];
			$sql = "select * from tbl_loyalty_point where loyalty_point = $pointId";
			$dataPoint = $db->queryArray( $sql );
			$dataPoint = $dataPoint[0];
			$type = "Edit";
		}else{
			$type = "Add";
		}
				
		$pageId = 3;		
    ?>
</head>
<body>
	<br/>
	<div class="container">
		<div class="col-md-3">            
			<?php require_once("loyaltyLeftMenu.php"); ?>
        </div>
        <div class="col-md-9">
			<div class="panel panel-orange margin-bottom-40">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="icon-user"></i> <?php echo $LC_LANG['pointManagement'];?></h3>
				</div>
				<div class="panel-body">
                	<div class="form-horizontal">
                		<input type="hidden" id="type" value="<?php echo $type;?>"/>
                		<input type="hidden" id="pointId" value="<?php echo $pointId?>"/>
                		<input type="hidden" id="ownerId" value="<?php echo $ownerId?>"/>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['pointName'];?></label>
                            <div class="col-lg-10">
                                <input type="text" value="<?php echo $dataPoint['point_name']?>" class="form-control" id="pointName" placeholder="<?php echo $LC_LANG['pointName'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['required'];?></label>
                            <div class="col-lg-4">
                                <input type="text" value="<?php echo $dataPoint['score_required']?>" class="form-control" id="scoreRequired" placeholder="<?php echo $LC_LANG['requiredScore'];?>">
                            </div>
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['discount'];?></label>
                            <div class="col-lg-4">
                                <input type="text" value="<?php echo $dataPoint['discount']?>" class="form-control" id="discount" placeholder="<?php echo $LC_LANG['discountPercent'];?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['valid'];?></label>
                            <div class="col-lg-4">
                                <select class="form-control" id="validYn">
                                	<option value="Y" <?php if( $dataPoint['valid_yn'] == "Y" ) echo "selected";?> >Yes</option>
                                	<option value="N" <?php if( $dataPoint['valid_yn'] == "N" ) echo "selected";?> >No</option>
                                </select>
                            </div>
                            <?php 
                            	if( $type == "Edit" ){
                            		$sql = "select count(*) cnt_used from tbl_loyalty_consumer_point where loyalty_point = $pointId";
                            		$cntPoint = $db->queryArray( $sql );
                            		$cntPoint = $cntPoint[0]['cnt_used'];
                            ?>
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['usedCount'];?></label>
                            <div class="col-lg-4">
                            	<input type="text" value="<?php echo $cntPoint?>" class="form-control" readonly style="background:#FEFEFE; cursor: pointer;" >
                            </div>
                            <?php }?>
                        </div>

                        <?php if( $type == "Edit" ){?>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['createdTime'];?></label>
	                            <div class="col-lg-4">
	                                <input type="text" value="<?php echo $dataPoint['created_time']?>" class="form-control" readonly style="background:#FEFEFE; cursor: pointer;" >
	                            </div>
	                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['updatedTime'];?></label>
	                            <div class="col-lg-4">
	                                <input type="text" value="<?php echo $dataPoint['updated_time']?>" class="form-control" readonly style="background:#FEFEFE; cursor: pointer;" >
	                            </div>	                            
	                        </div>
                        <?php }?>

                        <div class="form-group" style="margin-top:40px;">
                            <div class="col-lg-offset-1 col-lg-10" style="text-align:center;">
                                <button class="btn-u btn-u-blue" style="margin-right: 20px;width:90px;" onclick="onPointSave()"><i class="icon-edit"></i> <?php echo $LC_LANG['save'];?></button>
                                <button class="btn-u btn-u-red" style="width:90px;" onclick="window.location.href='pointList.php'"><i class="icon-list"></i> <?php echo $LC_LANG['list'];?></button>
                            </div>
                        </div>                                                                        
					</div>
				</div>				
			</div>         	
        </div>			           	
	</div>
</body>
</html>