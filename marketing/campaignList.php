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

    <script type="text/javascript" src="js/campaignList.js"></script>    
        
    <?php
		$prefix = $_SESSION["username"];
		$ownerId = $_SESSION["userid"];
		$sql = "select * from tbl_email_campaign where owner = $ownerId";
		$campaignList = $db->queryArray( $sql );
    ?>
</head>
<body>
	<br/>
	<div class="container">
			<div class="floatleft">
				<button class="btn-u btn-u-blue" onclick="window.location.href='index.php'"><i class="icon-home"></i>&nbsp;<?php echo $MT_LANG['mainPage'];?></button>
			</div>	
			<?php if( in_array( $prefix, $emailCreators)){?>
			<div class="floatleft" style="margin-left:10px;">
				<button class="btn-u btn-u-blue" onclick="window.location.href='templateList.php'"><i class="icon-list-ul"></i>&nbsp;<?php echo $MT_LANG['templateList'];?></button>
			</div>
			<?php } ?>			
            <div class="floatright">
            	<button class="btn-u btn-u-blue" onclick="window.location.href='campaignForm.php'"><i class="icon-file-text-alt"></i>&nbsp;<?php echo $MT_LANG['createCampaign'];?></button>
            	<button class="btn-u btn-u-orange" onclick="onDeleteCampaign()"><i class="icon-trash"></i>&nbsp;<?php echo $MT_LANG['deleteCampaign'];?></button>            	
            </div>
            <div class="clearboth"></div>	
			<div class="panel panel-orange margin-bottom-40" style="margin-top:20px;">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="icon-envelope"></i> <?php echo $MT_LANG['campaignList'];?></h3>
				</div>
														
				<table class="table" id="campaignList">
					<thead>
						<tr>
							<th style="width:60px;text-align:center;" data-sort-ignore="true"><input type="checkbox" id="chkAllCustomer" onclick="onCheckAllCampaign( this )"/></th>
							<th style="width:60px;text-align:center;" data-sort-ignore="true">No</th>
							<th style="text-align:center;"><?php echo $MT_LANG['subject'];?></th>
							<th style="width:80px;text-align:center;"><?php echo $MT_LANG['status'];?></th>
							<th style="width:150px;text-align:center;"><?php echo $MT_LANG['launchTime'];?></th>
							<th style="width:150px;text-align:center;"><?php echo $MT_LANG['createdTime'];?></th>
							<th data-sort-ignore="true" style="width:200px;text-align:center;"></th>
						</tr>
					</thead>
					<tbody>
						<?php for( $i = 0; $i < count( $campaignList ); $i ++ ){?>
						<tr>
							<td style="text-align:center;">
								<input type="hidden" id="campaignId" value="<?php echo $campaignList[$i]['email_campaign']?>"/>
								<input type="checkbox" id="chkCampaign"/>
							</td>
							<td style="text-align:center;"><?php echo $i + 1;?></td>
							<td><?php echo $campaignList[$i]['subject']?></td>
							<td style="text-align:center;">
								<?php 
									if( $campaignList[$i]['status'] == "DRAFT" ){
										echo '<button class="btn btn-info btn-xs">'.$MT_LANG['draft'].'</button>';
									}else if( $campaignList[$i]['status'] == "SCHEDULED" ){
										echo '<button class="btn btn-danger btn-xs">'.$MT_LANG['scheduled'].'</button>';
									}else if( $campaignList[$i]['status'] == "SENT" ){
										echo '<button class="btn btn-success btn-xs">'.$MT_LANG['sent'].'</button>';
									}else if( $campaignList[$i]['status'] == "AUTOMATION" ){
										echo '<button class="btn btn-success btn-xs">'.$MT_LANG['automation'].'</button>';
									}
								?>
							</td>
							<td style="text-align:center;"><?php echo $campaignList[$i]['launched_time']?></td>
							<td style="text-align:center;"><?php echo $campaignList[$i]['created_time']?></td>
							<td style="text-align:center;">
								<?php 
									if( $campaignList[$i]['status'] == "DRAFT" ){
										echo '<a class="btn-u btn-u-blue btn-xs" href="campaignForm.php?id='.base64_encode($campaignList[$i]['email_campaign']).'">'.$MT_LANG['edit'].'</a>';	
									}else if( $campaignList[$i]['status'] == "SCHEDULED" ){
										echo '<a class="btn-u btn-u-red btn-xs" onclick="onSendCampaignNow('.$campaignList[$i]['email_campaign'].')">'.$MT_LANG['sendNow'].'</a>';
									}else if( $campaignList[$i]['status'] == "SENT" ){
										echo '<a class="btn-u btn-u-success btn-xs" onclick="onShowStatistics('.$campaignList[$i]['email_campaign'].')">'.$MT_LANG['statistics'].'</a>';
									}
								?>							
								
								<a class="btn-u btn-u-orange btn-xs" onclick="onShowDuplicatePopup(<?php echo $campaignList[$i]['email_campaign']?>)"><?php echo $MT_LANG['duplicate'];?></a>
							</td>		
						</tr>
						<?php } ?>																	
					</tbody>
				</table>
			</div>             	
	</div>
	<div id="divBlackBackground" class="unshow"></div>
	<div id="campaignDuplicatePopup" class="unshow">
		<input type="hidden" id="duplicateCampaignId"/>
		<div style="height: 45px;background:#3498db;color:#FFF; line-height: 45px; font-size: 20px; padding-left: 20px;"><?php echo $MT_LANG['createNewCampaign'];?></div>
		<div style="margin:30px 90px 30px 90px;">
			<p><b><?php echo $MT_LANG['enterNewCampaignName'];?>:</b></p>
			<p><input type="text" class="form-control" id="newCampaignName"/>
		</div>
		<hr/>
		<div style="text-align:center;">
			<button class="btn-u btn-u-blue" style="text-align:center;" onclick="onDuplicate()"><?php echo $MT_LANG['duplicate'];?></button>
			<button class="btn-u btn-u-orange" style="text-align:center;" onclick="onCloseDuplicatePopup()"><?php echo $MT_LANG['cancel'];?></button>
		</div>
	</div>
	<div id="campaignStatisticsPopup" class="unshow">
		<div style="height: 45px;background:#3498db;color:#FFF; line-height: 45px; font-size: 20px; padding-left: 20px;"><?php echo $MT_LANG['campaignStatisticsInfo'];?></div>
		<div style="margin:30px 90px 30px 90px;">

			<table class="table table-bordered">
				<thead>
					<tr>
						<th><?php echo $MT_LANG['delivered'];?></th>
						<th><?php echo $MT_LANG['unsubscribes'];?></th>
						<th><?php echo $MT_LANG['invalid'];?></th>
						<th><?php echo $MT_LANG['opens'];?></th>
						<th><?php echo $MT_LANG['clicks'];?></th>
						<th><?php echo $MT_LANG['bounces'];?></th>
						<th><?php echo $MT_LANG['requests'];?></th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td><span id="cntDelivered"></span></td>
						<td><span id="cntUnsubscribes"></span></td>
						<td><span id="cntInvalid"></span></td>
						<td><span id="cntOpens"></span></td>
						<td><span id="cntClicks"></span></td>
						<td><span id="cntBounces"></span></td>
						<td><span id="cntRequests"></span></td>
					</tr>
				</tbody>
			</table>		
		
		</div>
		<hr/>
		<div style="text-align:center;">
			<button class="btn-u btn-u-orange" style="text-align:center;" onclick="onCloseStatisticsPopup()"><?php echo $MT_LANG['cancel'];?></button>
		</div>
	</div>		
</body>
</html>