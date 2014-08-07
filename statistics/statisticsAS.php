<!DOCTYPE html>
<!--[if IE 8]><html lang="en" id="ie8" class="lt-ie9 lt-ie10"> <![endif]-->
<!--[if IE 9]><html lang="en" id="ie9" class="gt-ie8 lt-ie10"> <![endif]-->
<!--[if gt IE 9]><!-->
<html lang="en"> <!--<![endif]-->
<head>
    <?php require_once("common/config.php"); ?>
    <?php require_once("../DB_Connection.php"); ?>
    <?php require_once("common/header.php"); ?>
    <?php require_once("common/asset.php"); ?>
    <?php require_once("common/functions.php"); ?>
    <style>
		.frontTopBackground{ position:relative;background-image:url(/loyalty/app/img/topHeader.png); background-position:center top; background-repeat: no-repeat; background-size: cover; width: 100%; max-width: 100%; height:120px; text-align:center;color: #FFF; font-size: 30px; padding-top:20px;}
		.frontTopBackground span{ font-family: 'Comfortaa';font-weight:bold;  }
		.greyDivider{ background:#555;width: 100%; max-width: 100%; height:10px;margin-bottom:10px; }
		.divChart{ height: 500px; margin-top:30px; border:1px solid #DDD;}
		#btnMainPage{ position:absolute;right:100px;bottom:20px;font-size:14px; }
		#btnMainPage a{ color: #FFF; }
		#btnMainPage a:hover{ color: #333; text-decoration: none;}
    </style>
	<link rel='stylesheet' href="css/datepicker.css" type='text/css' media='all'/>
	<script src="js/bootstrap-datepicker.js"></script>
    
	<script src="js/highcharts/highcharts.js"></script>
	<script src="js/highcharts/modules/exporting.js"></script>    
		        
    <script type="text/javascript" src="js/statisticsAS.js"></script>    
</head>
<body>
	<div class="frontTopBackground" style="position:relative;">
		<span>
			<?php echo $ST_LANG['appointmentSchedulerStatistics'];?>
		</span>
		<div id="btnMainPage">
			<a href="/"><?php echo $ST_LANG['mainPage'];?></a>
		</div> 
	</div>
	<div class="greyDivider"></div>
	<div class="container">
			<div class="panel panel-orange margin-bottom-40">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="icon-search"></i> <?php echo $ST_LANG['searchBy'];?></h3>
                </div>
                <div class="panel-body">
                		<div class="col-md-2">
                			<select class="form-control" id="userList" onchange="onChangeUser()">
                				<option value=""><?php echo $ST_LANG['selectUser'];?></option>
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
                			<select class="form-control" id="employeeList">
                				<option value=""><?php echo $ST_LANG['allEmployees'];?></option>
                				<option value=""><?php echo $ST_LANG['individualEmployees'];?></option>
                			</select>
                		</div>  
                		<div class="col-md-2">
                			<input type="text" id="startDate" class="floatleft form-control" readonly placeholder="<?php echo $ST_LANG['startDate'];?>" style="text-align:center;cursor:pointer;background:#FFF;"/>
                		</div>
                		<div class="col-md-2">
                			<input type="text" id="endDate" class="floatleft form-control" readonly placeholder="<?php echo $ST_LANG['endDate'];?>" style="text-align:center;cursor:pointer;background:#FFF;"/>
                		</div>
                		<div class="col-md-2">
                			<select class="form-control" id="viewMode">
                				<option value="daily"><?php echo $ST_LANG['byDaily'];?></option>
                				<option value="weekly"><?php echo $ST_LANG['byWeekly'];?></option>
                				<option value="monthly"><?php echo $ST_LANG['byMonthly'];?></option>
                			</select>
                		</div>
                		<div class="col-md-2">
                			<button class="btn-u btn-u-blue btn-u-block" onclick="onCalculate()"><i class="icon-search"></i>&nbsp;<?php echo $ST_LANG['showStatistics'];?></button>
                		</div>
                		<div class="clearboth"></div>
                		<div class="col-md-10 col-md-offset-1 divChart" id="divChart1"></div>
                		<div class="col-md-10 col-md-offset-1 divChart" id="divChart2"></div>
                		<div class="col-md-10 col-md-offset-1 divChart" id="divChart3"></div>
                		<div class="col-md-10 col-md-offset-1 divChart" id="divChart4"></div>
                		<div class="col-md-10 col-md-offset-1" style="margin-top: 30px;border: 1px solid #DDD;">
							<div class="panel panel-grey margin-bottom-40" style="margin-top:30px;">
								<div class="panel-heading">
									<h3 class="panel-title"><i class="icon-globe"></i> <?php echo $ST_LANG['statisticsInfo'];?></h3>
								</div>
								<div class="panel-body ">
									<table class="table table-bordered" id="tblStatistics">
										<thead>
											<tr>
												<th style="width:7%;text-align:center;">#</th>
												<th style="width:13%;text-align:center;"><?php echo $ST_LANG['date'];?></th>
												<th style="width:15%;text-align:center;"><?php echo $ST_LANG['employee'];?></th>
												<th style="width:13%;text-align:center;"><?php echo $ST_LANG['revenue'];?></th>
												<th style="width:13%;text-align:center;"><?php echo $ST_LANG['numberOfBooking'];?></th>
												<th style="width:13%;text-align:center;"><?php echo $ST_LANG['workingHours'];?></th>
												<th style="width:13%;text-align:center;"><?php echo $ST_LANG['bookingHours'];?></th>
												<th style="width:13%;text-align:center;"><?php echo $ST_LANG['bookingRate'];?></th>
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
	<input type="hidden" id="statisticsOfRevenue" value="<?php echo $ST_LANG['statisticsOfRevenue']?>" />
	<input type="hidden" id="revenue" value="<?php echo $ST_LANG['revenue']?>" />
	<input type="hidden" id="statisticsOfBookingCount" value="<?php echo $ST_LANG['statisticsOfBookingCount']?>" />
	<input type="hidden" id="numberOfBooking" value="<?php echo $ST_LANG['numberOfBooking']?>" />
	<input type="hidden" id="statisticsOfHours" value="<?php echo $ST_LANG['statisticsOfHours']?>" />
	<input type="hidden" id="hours" value="<?php echo $ST_LANG['hours']?>" />
	<input type="hidden" id="percentOfBookingNWorkingHours" value="<?php echo $ST_LANG['percentOfBookingNWorkingHours']?>" />
	<input type="hidden" id="percent" value="<?php echo $ST_LANG['percent']?>" />
	<input type="hidden" id="allEmployees" value="<?php echo $ST_LANG['allEmployees']?>" />
	<input type="hidden" id="individualEmployees" value="<?php echo $ST_LANG['individualEmployees']?>" />
			
</body>
</html>