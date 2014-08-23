<?php
require_once realpath(__DIR__.'/../../Bridge.php');
//------------------------------------------------------------------------------
// Check if the current user are still in the core
// If not, kick him out
// An Cao <an@varaa.com>
//------------------------------------------------------------------------------
if (!Bridge::hasOwnerId()) {
    @session_destroy();
    echo <<< JS
<script>
window.parent.location = '/auth/login';
</script>
JS;
    exit;
}

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
<link rel='stylesheet' href="css/datepicker.css" type='text/css' media='all' />
<?php
$prefix = $_SESSION["username"];
$ownerId = $_SESSION["userid"];
$sql = "select *
          from tbl_marketing_group
         where owner = $ownerId";
$groupList = $db->queryArray($sql);
?>
</head>
<body>
    <br />
    <div class="container">
        <div class="floatleft">
            <a class="btn-u btn-u-blue" href="main.php">
                <i class="icon-home"></i>&nbsp;<?php echo __('mainPage');?>
            </a>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button class="btn-u btn-u-orange" onclick="onOpenEmail()">
                <i class="icon-envelope"></i>&nbsp;
                <?php echo __('sendEmail');?>
            </button>
            <button class="btn-u btn-u-orange" onclick="onOpenSMS()">
                <i class="icon-comment"></i>&nbsp;
                <?php echo __('sendSms');?>
            </button>
        </div>
        <div class="floatright">
            <button class="btn-u btn-u-blue"
                onclick="window.location.href='groupForm.php'">
                <i class="icon-file-text-alt"></i>&nbsp;
                <?php echo __('createGroup');?>
            </button>
            <button class="btn-u btn-u-orange" onclick="onDeleteGroup()">
                <i class="icon-trash"></i>&nbsp;
                <?php echo __('deleteGroup');?>
            </button>
        </div>
        <div class="clearboth"></div>
        <div class="panel panel-orange margin-bottom-40"
            style="margin-top: 20px;">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <i class="icon-envelope"></i>&nbsp;
                    <?php echo __('groupList');?>
                </h3>
            </div>

            <table class="table" id="tblGroupList">
                <thead>
                    <tr>
                        <th style="width: 60px; text-align: center;"
                            data-sort-ignore="true"><input type="checkbox"
                            id="chkAllCustomer" onclick="onCheckAllGroup( this )" /></th>
                        <th style="width: 60px; text-align: center;"
                            data-sort-ignore="true">No</th>
                        <th style="text-align: center;"><?php echo __('groupName');?>
                        </th>
                        <th style="width: 160px; text-align: center;"><?php echo __('createdTime');?>
                        </th>
                        <th style="width: 160px; text-align: center;"><?php echo __('updatedTime');?>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <?php for ($i = 0; $i < count( $groupList ); $i++) {?>
                    <tr>
                        <td style="text-align: center;"><input type="hidden" id="groupId"
                            value="<?php echo $groupList[$i]['marketing_group']?>" /> <input
                            type="checkbox" id="chkGroup" />
                        </td>
                        <td style="text-align: center;"><?php echo $i + 1;?></td>
                        <td><a
                            href="groupForm.php?id=<?php echo base64_encode($groupList[$i]['marketing_group'])?>"><?php echo $groupList[$i]['group_name']?>
                        </a></td>
                        <td style="text-align: center;"><?php echo $groupList[$i]['created_time']?>
                        </td>
                        <td style="text-align: center;"><?php echo $groupList[$i]['updated_time']?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <div id="divSMSArea" class="unshow">
        <span><?php echo __('smsTitle');?> </span> <span
            style="padding-left: 20px;">(&nbsp;<span id="lengthSMSTitle"></span>&nbsp;/&nbsp;40&nbsp;)
        </span> <input type="text" class="form-control" id="titleSMS"
            style="margin-top: 5px; margin-bottom: 15px;" maxlength="40"> <span><?php echo __('smsText');?>
        </span>
        <textarea class="form-control" id="txtSMS" rows="5"
            style="margin-top: 5px; margin-bottom: 15px;" maxlength="160"></textarea>
        <div class="floatleft">
            <span style="color: #F00;">*</span>&nbsp;<span id="lengthSMSText"></span>&nbsp;/160
        </div>
        <div class="floatright">
            <button class="btn-u btn-u-blue" onclick="onSendSMS()">
                <?php echo __('send');?>
            </button>
            <button class="btn-u btn-u-orange" onclick="onCloseSMS()">
                <?php echo __('cancel');?>
            </button>
        </div>
        <div class="clearboth"></div>
    </div>
    <div id="divEmailArea" class="unshow">
        <div
            style="height: 45px; background: #3498db; color: #FFF; line-height: 45px; font-size: 20px; padding-left: 20px;">
            <?php echo __('selectCampaignToSend');?>
        </div>
        <?php
        $sql = "select *
                  from tbl_email_campaign
                 where status = 'DRAFT'";
        $dataCampaign = $db->queryArray($sql);
        ?>
        <div id="campaignList"
            style="background: #FDFDFD; height: 350px; margin: 20px 30px 0px 30px; border: 1px solid #EEE; overflow: auto;">
            <?php for ($i = 0; $i < count( $dataCampaign ); $i++) {?>
            <div id="campaignItem"
                style="height: 35px; line-height: 35px; border-bottom: 1px solid #DDD; padding-left: 20px; cursor: pointer;"
                onclick="onClickCampaignItem(this)">
                <?php echo $dataCampaign[$i]['subject'];?>
                <input type="hidden" id="campaignId"
                    value="<?php echo $dataCampaign[$i]['email_campaign'];?>" />
            </div>
            <?php }
            if (count( $dataCampaign ) == 0) {
                echo "<h2 style='padding-left:10px;padding-top:10px;'>".__('msgNoCampaign')."</h2>";
            } ?>

        </div>
        <hr />
        <div style="text-align: center;">
            <!-- input type="text" class="form-control" id="scheduleDate" style="width:120px;display:initial;margin-top: -3px;text-align:center;" value="<?php echo $today;?>"/>&nbsp;
            <select class="form-control" style="width:70px;display:initial;margin-top: -3px;" id="scheduleHour">
                <?php for( $i = 0; $i < 24; $i++ ){?>
                    <option value="<?php echo $i;?>"><?php echo $i<10?"0".$i:$i?></option>
                <?php } ?>
            </select>&nbsp;&nbsp;
            <button class="btn-u btn-u-red" onclick="onEmailSchedule()">Schedule</button -->
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button class="btn-u btn-u-blue" onclick="onSendEmail()">
                <?php echo __('sendNow')?>
            </button>
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <button class="btn-u btn-u-orange" onclick="onCloseEmail()">
                <?php echo __('cancel')?>
            </button>
        </div>
        <div class="clearboth"></div>
    </div>

    <div id="divBlackBackground" class="unshow" onclick="onCloseSMS();onCloseEmail();"></div>
<script src="js/footable.js" type="text/javascript"></script>
<script src="js/footable.sort.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/groupList.js"></script>
</body>
</html>
