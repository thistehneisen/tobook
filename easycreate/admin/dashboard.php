<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: girish<girish@armia.com>                                  |
// |                                                                                                      // |
// +----------------------------------------------------------------------+
?>
<?php
//error_reporting(E_ALL);
$curTab = 'dashboard';
//include files
include "../includes/session.php";
include "../includes/config.php"; 
include "../includes/function.php"; 
include "includes/adminheader.php";

?>
<script src="../js/jquery.js"></script>
<script language='javascript' src='<?php echo BASE_URL ?>fusioncharts/JSClass/FusionCharts.js'></script>
<?php  
include "../graph/graph.php"; 
include "includes/admin_functions.php"; 

//Sites Created Graph
$sitesCreated = getSiteCreatedGraph();
$graph1   = $sitesCreated;

//Sales Graph

if(getSitePayType()=='no'){
  $users    = getUsersGraph();
  $graph2   = $users;
  $graph_title ='Users';
}
else{
    $sales = getSalesGraph();
    $graph2   = $sales;
    $graph_title ='Sales';
}


//Latest Orders
$latestOrders = getLatestOrders();

//Latest Unpublished Sites
$unpublishedSites = getUnpublishedSites();

//echo '<pre>'; print_r($unpublishedSites); echo '</pre>';
?>




<div class="admin-pnl">
  	<h2><?php echo DASHBOARD;?></h2>
	<div class="">
            <div class="admin-bx lft">
                <h5><?php echo SITE_CREATED;?></h5>
                <div id="flashwindow1">
                <?php
                    $graph1->renderchart();
                ?>
                </div>
                <div class="clear"></div>
            </div>
            <div class="admin-bx ryt">
                <h5><?php echo $graph_title?></h5>
                <div id="flashwindow2">
                <?php
                    $graph2->renderchart();
                ?> </div>
            <div class="clear"></div>
            </div>
	</div>
	<div class="">
                <h5 style="margin-bottom:5px; "><?php echo LATEST_PAID_ORDERS;?></h5>
                <div align="right"><a href="payment.php"><?php echo VIEW_ALL;?></a><div>
		<div class="userdashboard_ursites" style="padding:10px 0 30px 0;">
			<table width="100%"  border="0" cellspacing="1" cellpadding="0">
			  <tr>
				<th align="center">#</th>
				<th align="center"><?php echo AMOUNT;?></th>
				<th align="center"><?php echo USER;?></th>
                                <th align="center"><?php echo SITE?></th>
                                <th align="center"><?php echo PAYMENT_TYPE;?></th>
				<th align="center"><?php echo SM_DATE;?></th>
				<th align="center"><?php echo TRANSACTION_ID?></th>
			  </tr>
                          <?php
                          
                           $currency = getSettingsValue('currency');
                          $curSymbol = $currencyArray[$currency]['symbol'];
                          if($latestOrders){
                          $i=0;
                          foreach($latestOrders AS $orders){ 
                              $i++;   
                                                       
                          ?>
			  <tr>
				<td align="center"><?php echo $i;?></td>
				<td align="center"> <?php echo $curSymbol.$orders['namount'];?></td>
				<td align="center"><?php echo $orders['userName'];?></td>
                                <td align="center"><?php echo $orders['vsite_name'];?></td>
                                <td align="center"><?php echo $paymnttype[$orders['vpayment_type']];?></td>
				<td align="center"><?php echo $orders['orderDate'];?></td>
				<td align="center"><?php echo ($orders['vtxn_id'])?$orders['vtxn_id']:0;?></td>
			  </tr>
                          <?php }
                          }else{
                          ?>
                            <tr><td  colspan="7" align="center"> <?php echo NO_RESULT_FOUND;?>!</td></tr>
                          <?php } ?>
			</table>

		<div class="clear"></div>
		</div>
	</div>
	<script>

var hasFlash = false;
try {
  var fo = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
  if(fo) hasFlash = true;
}catch(e){
  if(navigator.mimeTypes ["application/x-shockwave-flash"] != undefined) hasFlash = true;
}

if(hasFlash == false) {
	message = 'Please install Adobe flash player';
	 document.getElementById("flashwindow1").innerHTML	= message;
	 document.getElementById('flashwindow2').innerHTML	= message;
}

</script>
	
	
        <div class="">
            <div align="left"><h5  style="margin-bottom:5px; "><?php echo LATEST_UNPUBLISHED_SITE;?></h5></div>
                <div align="right"><a href="sitemanager.php"><?php echo VIEW_ALL;?></a><div>
		<div class="userdashboard_ursites" style="padding:10px 0 30px 0; ">
			<table width="100%"  border="0" cellspacing="1" cellpadding="0">
			  <tr>
				<th align="center">#</th>
                                <th align="center"><?php echo SITE;?></th>
				<th align="center"><?php echo USER;?></th>
				<th align="center"><?php echo SM_DATE;?></th>
			  </tr>
                          <?php
                          if($unpublishedSites){
                          $i=0;
                          foreach($unpublishedSites AS $sites){
                              $i++;
                          ?>
			  <tr>
				<td align="center"><?php echo $i;?></td>
				<td align="center"><?php echo $sites['vsite_name'];?></td>
				<td align="center"><?php echo $sites['userName'];?></td>
				<td align="center"><?php echo $sites['siteDate'];?></td>
			  </tr>
                          <?php }
                          }else{
                          ?>
                          <tr><td  colspan="7" align="center"><?php echo NO_RESULT_FOUND;?>!</td></tr>
                          <?php } ?>
			</table>
		</div>
	</div>
</div>
<div class="clear"></div>
</div>
<div class="clear"></div>
</div>
<?php
include "includes/adminfooter.php";

?>