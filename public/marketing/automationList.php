<?php
if (!headers_sent()) {
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
<?php 
require_once("common/config.php");
require_once("../DB_Connection.php");
require_once("common/header.php");
require_once("common/asset.php");
require_once("common/functions.php");
?>
<link rel='stylesheet' href="css/footable.core.css" type='text/css' media='all' />
<link rel='stylesheet' href="css/footable.standalone.css" type='text/css' media='all' />
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
$automationList = $db->queryArray($sql);
?>
</head>
<body>
    <br />
    <div class="container">
        <div class="floatleft">
            <a class="btn-u btn-u-blue" href="main.php">
                <i class="icon-home"></i>&nbsp;<?php echo __('mainPage');?>
            </a>
        </div>
        <div class="floatright">
            <button class="btn-u btn-u-blue" onclick="window.location.href='automationForm.php'">
                <i class="icon-file-text-alt"></i>&nbsp;<?php echo __('createAutomation');?>
            </button>
            <button class="btn-u btn-u-orange" onclick="onDeleteAutomation()">
                <i class="icon-trash"></i>&nbsp;<?php echo __('deleteAutomation');?>
            </button>
        </div>
        <div class="clearboth"></div>
        <div class="panel panel-orange margin-bottom-40" style="margin-top: 20px;">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="icon-envelope"></i>&nbsp;<?php echo __('automationList');?>
                </h3>
            </div>
            <table class="table" id="automationList">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;" data-sort-ignore="true">
                            <input type="checkbox" id="chkAllCustomer" onclick="onCheckAllAutomation(this)" />
                        </th>
                        <th style="width: 60px; text-align: center;" data-sort-ignore="true">
                            No
                        </th>
                        <th style="text-align: center;">
                            <?php echo __('automationTitle');?>
                        </th>
                        <th style="width: 80px; text-align: center;">
                            <?php echo __('type');?>
                        </th>
                        <th style="width: 160px; text-align: center;">
                            <?php echo __('pluginName');?>
                        </th>
                        <th style="width: 160px; text-align: center;">
                            <?php echo __('numberOfPreviousBooking');?>
                        </th>
                        <th style="width: 160px; text-align: center;">
                            <?php echo __('dayOfPreviousBooking');?>
                        </th>
                        <th style="width: 160px; text-align: center;">
                            <?php echo __('sentCount');?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count( $automationList ); $i++) {?>
                    <tr>
                        <td style="text-align: center;">
                            <input type="hidden" id="automationId" value="<?php echo $automationList[$i]['marketing_auto']?>" />
                            <input type="checkbox" id="chkAutomation" />
                        </td>
                        <td style="text-align: center;"><?php echo $i + 1;?></td>
                        <td>
                            <a href="automationForm.php?id=<?php echo base64_encode($automationList[$i]['marketing_auto'])?>"><?php echo $automationList[$i]['title']?></a>
                        </td>
                        <td style="text-align: center;">
                            <?php 
                            echo ( $automationList[$i]['type'] == "email" ) ? __('email') : "";
                            echo ( $automationList[$i]['type'] == "sms" ) ? "SMS" : "";
                            ?>
                        </td>
                        <td style="text-align: center;">
                        <?php
                        if ($automationList[$i]['plan_group_code'] == "TB") { 
                            echo __('timeslotBookings');
                        } elseif ($automationList[$i]['plan_group_code'] == "RB") {
                            echo __('restaurantBookings');
                        } else if( $automationList[$i]['plan_group_code'] == "AS" ) {
                            echo __('appointmentScheduler');
                        }
                        ?>
                        </td>
                        <td style="text-align: center;"><?php echo $automationList[$i]['cnt_previous_booking']?>
                        </td>
                        <td style="text-align: center;"><?php echo $automationList[$i]['days_previous_booking']?>
                        </td>
                        <td style="text-align: center;"><?php echo $automationList[$i]['cnt']?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
<script src="js/footable.js" type="text/javascript"></script>
<script src="js/footable.sort.js" type="text/javascript"></script>
<script type="text/javascript" src="js/automationList.js"></script>    
</body>
</html>
