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
    <?php require_once("common/DB_Connection.php"); ?>
    <?php require_once("common/header.php"); ?>
    <?php require_once("common/asset.php"); ?>
    <?php require_once("common/functions.php"); ?>    
	<link rel='stylesheet' href="css/footable.core.css" type='text/css' media='all'/>
	<link rel='stylesheet' href="css/footable.standalone.css" type='text/css' media='all'/>    
    
	<script src="js/footable.js" type="text/javascript"></script>
	<script src="js/footable.sort.js" type="text/javascript"></script>

    <script type="text/javascript" src="js/templateList.js"></script>    
        
    <?php
		$prefix = $_SESSION["username"];
		$ownerId = $_SESSION["userid"];
		if( !in_array( $prefix, $emailCreators)){
			echo "You can't access this page.";
			exit();
		}		
		$sql = "select * from tbl_email_template";
		$templateList = $db->queryArray( $sql );

    ?>
</head>
<body>
	<br/>
	<div class="container">
			<div class="floatleft">
				<button class="btn-u btn-u-blue" onclick="window.location.href='index.php'"><i class="icon-home"></i>&nbsp;<?php echo $MT_LANG['homePage']?></button>
			</div>
            <div class="floatright">
            	<button class="btn-u btn-u-blue" onclick="window.location.href='templateForm.php'"><i class="icon-file-text-alt"></i>&nbsp;<?php echo $MT_LANG['createTemplate']?></button>
            	<button class="btn-u btn-u-orange" onclick="onDeleteTemplate()"><i class="icon-trash"></i>&nbsp;<?php echo $MT_LANG['deleteTemplate']?></button>            	
            </div>
            <div class="clearboth"></div>	
			<div class="panel panel-blue margin-bottom-40" style="margin-top:20px;">
				<div class="panel-heading">
					<h3 class="panel-title"><i class="icon-list-ul"></i> <?php echo $MT_LANG['templateList']?></h3>
				</div>
														
				<table class="table" id="templateList">
					<thead>
						<tr>
							<th style="width:60px;text-align:center;" data-sort-ignore="true"><input type="checkbox" id="chkAllCustomer" onclick="onCheckAllTemplate( this )"/></th>
							<th style="width:60px;text-align:center;" data-sort-ignore="true">No</th>
							<th style="text-align:center;"><?php echo $MT_LANG['templateName']?></th>
							<th style="width:180px;text-align:center;"><?php echo $MT_LANG['createdTime']?></th>
							<th data-sort-ignore="true" style="width:100px;"></th>
						</tr>
					</thead>
					<tbody>
						<?php for( $i = 0; $i < count( $templateList ); $i ++ ){?>
						<tr>
							<td style="text-align: center;">
								<input type="hidden" id="templateId" value="<?php echo $templateList[$i]['email_template']?>"/>
								<input type="checkbox" id="chkTemplate"/>
							</td>
							<td style="text-align: center;"><?php echo $i + 1;?></td>
							<td><?php echo $templateList[$i]['subject']?></td>
							<td style="text-align: center;"><?php echo $templateList[$i]['created_time']?></td>
							<td><a class="btn-u btn-u-blue btn-small" href="templateForm.php?id=<?php echo base64_encode($templateList[$i]['email_template'])?>"><?php echo $MT_LANG['edit']?></a></td>		
						</tr>
						<?php } ?>																	
					</tbody>
				</table>
			</div>             	
	</div>
</body>
</html>