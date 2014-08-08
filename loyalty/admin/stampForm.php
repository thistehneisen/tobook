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
	
    <script type="text/javascript" src="js/stampForm.js"></script>    
        
    <?php
		$prefix = $_SESSION["username"];
		$ownerId = $_SESSION["userid"];
						
		if( isset($_GET['id']) && $_GET['id'] != "" ){
			$stampId = $_GET['id'];
			$sql = "select * from tbl_loyalty_stamp where loyalty_stamp = $stampId";
			$dataStamp = $db->queryArray( $sql );
			$dataStamp = $dataStamp[0];
			$type = "Edit";
		}else{
			$type = "Add";
		}
				
		$pageId = 2;		
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
					<h3 class="panel-title"><i class="icon-user"></i> <?php echo $LC_LANG['stampManagement'];?></h3>
				</div>
				<div class="panel-body">
                	<div class="form-horizontal">
                		<input type="hidden" id="type" value="<?php echo $type;?>"/>
                		<input type="hidden" id="stampId" value="<?php echo $stampId?>"/>
                		<input type="hidden" id="ownerId" value="<?php echo $ownerId?>"/>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['stampName'];?></label>
                            <div class="col-lg-10">
                                <input type="text" value="<?php echo $dataStamp['stamp_name']?>" class="form-control" id="stampName" placeholder="<?php echo $LC_LANG['stampName'];?>">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['required'];?></label>
                            <div class="col-lg-4">
                                <input type="text" value="<?php echo $dataStamp['cnt_required']?>" class="form-control" id="cntRequired" placeholder="<?php echo $LC_LANG['requiredCount'];?>">
                            </div>
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['free'];?></label>
                            <div class="col-lg-4">
                                <input type="text" value="<?php echo $dataStamp['cnt_free']?>" class="form-control" id="cntFree" placeholder="<?php echo $LC_LANG['freeCount'];?>">
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['valid'];?></label>
                            <div class="col-lg-4">
                                <select class="form-control" id="validYn">
                                	<option value="Y" <?php if( $dataStamp['valid_yn'] == "Y" ) echo "selected";?> >Yes</option>
                                	<option value="N" <?php if( $dataStamp['valid_yn'] == "N" ) echo "selected";?> >No</option>
                                </select>
                            </div>
                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['autoAdd'];?></label>
                            <div class="col-lg-4">
                                <select class="form-control" id="autoAddYn">
                                	<option value="Y" <?php if( $dataStamp['auto_add_yn'] == "Y" ) echo "selected";?> >Yes</option>
                                	<option value="N" <?php if( $dataStamp['auto_add_yn'] == "N" ) echo "selected";?> >No</option>
                                </select>
                            </div>                            
                            <?php 
                                if( $type == "Edit11" /* change to 'Edit' */){
                            		$sql = "select count(*) cnt_used from tbl_loyalty_consumer_stamp where loyalty_stamp = $stampId";
                            		$cntStamp = $db->queryArray( $sql );
                            		$cntStamp = $cntStamp[0]['cnt_used'];
                            ?>
                                <label class="col-lg-2 control-label"><?php echo $LC_LANG['usedCount'];?></label>
                                <div class="col-lg-4">
                                	<input type="text" value="<?php echo $cntStamp?>" class="form-control" readonly style="background:#FEFEFE; cursor: pointer;" >
                                </div>
                            <?php }?>
                        </div>

                        <?php if( $type == "Edit" ){?>
	                        <div class="form-group">
	                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['createdTime'];?></label>
	                            <div class="col-lg-4">
	                                <input type="text" value="<?php echo $dataStamp['created_time']?>" class="form-control" readonly style="background:#FEFEFE; cursor: pointer;" >
	                            </div>
	                            <label class="col-lg-2 control-label"><?php echo $LC_LANG['updatedTime'];?></label>
	                            <div class="col-lg-4">
	                                <input type="text" value="<?php echo $dataStamp['updated_time']?>" class="form-control" readonly style="background:#FEFEFE; cursor: pointer;" >
	                            </div>	                            
	                        </div>
                        <?php }?>

                        <div class="form-group" style="margin-top:40px;">
                            <div class="col-lg-offset-1 col-lg-10" style="text-align:center;">
                                <button class="btn-u btn-u-blue" style="margin-right: 20px;width:120px;" onclick="onStampSave()"><i class="icon-edit"></i> <?php echo $LC_LANG['save'];?></button>
                                <button class="btn-u btn-u-red" style="width:120px;" onclick="window.location.href='stampList.php'"><i class="icon-list"></i> <?php echo $LC_LANG['list'];?></button>
                            </div>
                        </div>                                                                        
					</div>
				</div>				
			</div>         	
        </div>			           	
	</div>
</body>
</html>