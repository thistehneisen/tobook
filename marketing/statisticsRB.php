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
    <style>
		.frontTopBackground{ background-image:url(/loyalty/app/img/topHeader.png); background-position:center top; background-repeat: no-repeat; background-size: cover; width: 100%; max-width: 100%; height:120px; text-align:center;font-family: 'Comfortaa';font-weight:bold; color: #FFF; font-size: 30px; padding-top:20px;}
		.greyDivider{ background:#555;width: 100%; max-width: 100%; height:10px;margin-bottom:10px; }
		.divChart{ height: 500px; margin-top:30px; border:1px solid #DDD;}    
    </style>
	<link rel='stylesheet' href="css/datepicker.css" type='text/css' media='all'/>
	<script src="js/bootstrap-datepicker.js"></script>
    
	<script src="js/highcharts/highcharts.js"></script>
	<script src="js/highcharts/modules/exporting.js"></script>    
		        
    <script type="text/javascript" src="js/statisticsRB.js"></script>

    
</head>
<body>
	<div class="frontTopBackground">
		Restaurant Booking Statistics
	</div>
	<div class="greyDivider"></div>
	<div class="container">
			<div class="panel panel-orange margin-bottom-40">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon-search"></i> Search By</h3>
                </div>
                <div class="panel-body">
                		<div class="col-md-2">
                			<select class="form-control" id="userList">
                				<option value="">Select User</option>
                				<?php 
                					$sql = "select nuser_id as userid, vuser_login as username from tbl_user_mast where vdel_status = 0";
                					$userList = $db->queryArray( $sql );
                					for( $i = 0; $i < count( $userList ); $i ++ ){
                				?>
                				<option value="<?php echo $userList[$i]['userid'];?>"><?php echo $userList[$i]['username'];?></option>
                				<?php } ?>
                			</select>
                		</div>
                		<div class="col-md-2">
                			<input type="text" id="startDate" class="floatleft form-control" readonly placeholder="Start Date" style="text-align:center;cursor:pointer;background:#FFF;"/>
                		</div>
                		<div class="col-md-2">
                			<input type="text" id="endDate" class="floatleft form-control" readonly placeholder="End Date" style="text-align:center;cursor:pointer;background:#FFF;"/>
                		</div>
                		<div class="col-md-2">
                			<select class="form-control" id="viewMode">
                				<option value="daily">By Daily</option>
                				<option value="weekly">By Weekly</option>
                				<option value="monthly">By Monthly</option>
                			</select>
                		</div>
                		<div class="col-md-2"></div>
                		<div class="col-md-2">
                			<button class="btn-u btn-u-blue btn-u-block" onclick="onCalculate()"><i class="icon-search"></i>&nbsp;Show Statistics</button>
                		</div>
                		<div class="clearboth"></div>
                		<div class="col-md-10 col-md-offset-1 divChart" id="divChart1"></div>
                		<div class="col-md-10 col-md-offset-1 divChart" id="divChart2"></div>
                		<div class="col-md-10 col-md-offset-1 divChart" id="divChart3"></div>
                		<div class="col-md-10 col-md-offset-1" style="margin-top: 30px;border: 1px solid #DDD;">
							<div class="panel panel-grey margin-bottom-40" style="margin-top:30px;">
								<div class="panel-heading">
									<h3 class="panel-title"><i class="icon-globe"></i> Statistics Info</h3>
								</div>
								<div class="panel-body ">
									<table class="table table-bordered" id="tblStatistics">
										<thead>
											<tr>
												<th style="width:7%;text-align:center;">#</th>
												<th style="width:13%;text-align:center;">Date</th>
												<th style="width:15%;text-align:center;" id="lblListType">User</th>
												<th style="width:13%;text-align:center;">Revenue</th>
												<th style="width:13%;text-align:center;">Reservations</th>
												<th style="width:13%;text-align:center;">Covers</th>
											</tr>
										</thead>
										<tbody>
	
										</tbody>
									</table>
								</div>						
							</div>     
						</div>           		
                </div>
            </div>		
	</div>
</body>
</html>