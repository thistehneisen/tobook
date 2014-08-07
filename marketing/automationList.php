<?php
if (!headers_sent())
{
	session_name('MarketingTool');
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
    <?php require_once("../DB_Connection.php"); ?>
    <?php require_once("common/header.php"); ?>
    <?php require_once("common/asset.php"); ?>
    <?php require_once("common/functions.php"); ?>    
	<link rel='stylesheet' href="css/footable.core.css" type='text/css' media='all'/>
	<link rel='stylesheet' href="css/footable.standalone.css" type='text/css' media='all'/>    
    
	<script src="js/footable.js" type="text/javascript"></script>
	<script src="js/footable.sort.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/automationList.js"></script>    
        
    <?php
		$prefix = $_SESSION["username"];
		$ownerId = $_SESSION["userid"];
		$sql = "select t1.*, t2.cnt
  				  from tbl_marketing_auto t1
  				  left join ( select marketing_auto, count(*) cnt
  				                from tbl_marketing_auto_history
  				               group by marketing_auto ) t2
  				    on t1.marketing_auto = t2.marketing_auto
 			 	 where t1.owner = $ownerId";
		$automationList = $db->queryArray( $sql );
    ?>
</head>
<body>
	<br/>
	<div class="container">			
			<div class="floatleft">
				<button class="btn-u btn-u-blue" onclick="window.location.href='index.php'"><i class="icon-home"></i>&nbsp;<?php echo $MT_LANG['mainPage'];?></button>
			</div>	
            <div class="floatright">
            	<button class="btn-u btn-u-blue" onclick="window.location.href='automationForm.php'"><i class="icon-file-text-alt"></i>&nbsp;<?php echo $MT_LANG['createAutomation'];?></button>
            	<button class="btn-u btn-u-orange" onclick="onDeleteAutomation()"><i class="icon-trash"></i>&nbsp;<?php echo $MT_LANG['deleteAutomation'];?></button>            	
            </div>
            <div class="clearboth"></div>	
			<div class="panel panel-orange margin-bottom-40" style="margin-top:20px;">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="icon-envelope"></i> <?php echo $MT_LANG['automationList'];?></h3>
				</div>
														
				<table class="table" id="automationList">
					<thead>
						<tr>
							<th style="width:60px;text-align:center;" data-sort-ignore="true"><input type="checkbox" id="chkAllCustomer" onclick="onCheckAllAutomation( this )"/></th>
							<th style="width:60px;text-align:center;" data-sort-ignore="true">No</th>
							<th style="text-align:center;"><?php echo $MT_LANG['automationTitle'];?></th>
							<th style="width:80px;text-align:center;"><?php echo $MT_LANG['type'];?></th>
							<th style="width:160px;text-align:center;"><?php echo $MT_LANG['pluginName'];?></th>
							<th style="width:160px;text-align:center;"><?php echo $MT_LANG['numberOfPreviousBooking'];?></th>
							<th style="width:160px;text-align:center;"><?php echo $MT_LANG['dayOfPreviousBooking'];?></th>
							<th style="width:160px;text-align:center;"><?php echo $MT_LANG['sentCount'];?></th>
						</tr>
					</thead>
					<tbody>
						<?php for( $i = 0; $i < count( $automationList ); $i ++ ){?>
						<tr>
							<td style="text-align:center;">
								<input type="hidden" id="automationId" value="<?php echo $automationList[$i]['marketing_auto']?>"/>
								<input type="checkbox" id="chkAutomation"/>
							</td>
							<td style="text-align:center;"><?php echo $i + 1;?></td>
							<td><a href="automationForm.php?id=<?php echo base64_encode($automationList[$i]['marketing_auto'])?>"><?php echo $automationList[$i]['title']?></a></td>
							<td style="text-align:center;">
								<?php
									if( $automationList[$i]['type'] == "email" ) echo $MT_LANG['email'];
									else if( $automationList[$i]['type'] == "sms" ) echo "SMS";
								?>
							</td>
							<td style="text-align:center;">
								<?php
									if( $automationList[$i]['plan_group_code'] == "TB" ) echo $MT_LANG['timeslotBookings'];
									else if( $automationList[$i]['plan_group_code'] == "RB" ) echo $MT_LANG['restaurantBookings'];
									else if( $automationList[$i]['plan_group_code'] == "AS" ) echo $MT_LANG['appointmentScheduler'];
								?>
							</td>
							<td style="text-align:center;"><?php echo $automationList[$i]['cnt_previous_booking']?></td>
							<td style="text-align:center;"><?php echo $automationList[$i]['days_previous_booking']?></td>
							<td style="text-align:center;"><?php echo $automationList[$i]['cnt']?></td>	
						</tr>
						<?php } ?>																	
					</tbody>
				</table>
			</div>             	
	</div>
</body>
</html>