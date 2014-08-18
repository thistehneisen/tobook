<?php
require_once realpath(__DIR__.'/../../Bridge.php');
//------------------------------------------------------------------------------
// Check if the current user are still in the core
// If not, kick him out
// An Cao <an@varaa.com>
//------------------------------------------------------------------------------
if (!Bridge::hasOwnerId()) {
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
<link href="wysiwyg/bootstrap/bootstrap_extend.css" rel="stylesheet" type="text/css" />
<link href='http://fonts.googleapis.com/css?family=Abel' rel='stylesheet' type='text/css'>
<?php
$prefix = $_SESSION["username"];
$ownerId = $_SESSION["userid"];

if (isset($_GET['id'])) {
    $automationId = base64_decode($_GET['id']);
    $sql = "select *
              from tbl_marketing_auto
             where marketing_auto = $automationId";
    $dataAutomation = $db->queryArray($sql);
    $dataAutomation = $dataAutomation[0];
    $pageType = "EDIT";
} else {
    $pageType = "ADD";
}

$sql = "select *
          from tbl_email_campaign
         where status = 'DRAFT'
            or status = 'AUTOMATION'";
$campaignList = $db->queryArray($sql);
?>
</head>
<body>
    <input type="hidden" value="<?php echo $ownerId;?>" id="ownerId" />
    <input type="hidden" value="<?php echo $campaignId;?>" id="campaignId" />
    <input type="hidden" value="<?php echo $automationId?>" id="automationId" />
    <div class="container">
        <div style="width: 800px; margin: 20px auto;">
            <h3 style="color: #e67e22;">
                <?php echo __('saveAutomation')?>
            </h3>
        </div>
        <div style="width: 800px; margin: 20px auto;">
            <div class="floatright">
                <button class="btn-u btn-u-orange" onclick="onSaveAutomation()">
                    <i class="icon-edit"></i>&nbsp;<?php echo __('saveAutomation')?>
                </button>
                <button class="btn-u btn-u-blue" onclick="window.location.href='automationList.php'">
                    <i class="icon-list-ul"></i>&nbsp;<?php echo __('automationList')?>
                </button>
            </div>
            <div class="clearboth"></div>
            <br />
            <div class="form-group">
                <label><?php echo __('automationTitle')?></label>
                <input type="text" id="title" class="form-control" value="<?php echo $dataAutomation['title']?>" />
            </div>
            <div class="form-group">
                <label><?php echo __('marketingType')?></label>
                <select class="form-control" id="type" onchange="onChangeMarketingType( this )">
                    <option value=""><?php echo __('selectMarketingType'); ?></option>
                    <option value="email" <?php echo ($dataAutomation['type'] == "email")? "selected": "";?>>
                        <?php echo __('email');?>
                    </option>
                    <option value="sms" <?php echo ($dataAutomation['type'] == "sms")? "selected": "";?>>
                        SMS
                    </option>
                </select>
            </div>

            <div class="form-group" id="divEmail" style="<?php echo ($dataAutomation['type'] != "email")? "display:none;": "";?>">
                <label><?php echo __('campaign')?></label>
                <select class="form-control" id="campaign">
                    <option value=""><?php echo __('selectCampaign');?></option>
                    <?php 
                    for ($i = 0; $i < count($campaignList); $i++) {
                        if ($campaignList[$i]['email_campaign'] == $dataAutomation['email_campaign']) {
                    ?>
                        <option value="<?php echo $campaignList[$i]['email_campaign']?>" selected>
                            <?php echo $campaignList[$i]['subject']?>
                        </option>
                    <?php
                        } else {
                    ?>
                        <option value="<?php echo $campaignList[$i]['email_campaign']?>">
                            <?php echo $campaignList[$i]['subject']?>
                        </option>
                    <?php 
                        } 
                    } 
                    ?>
                </select>
            </div>

            <div class="form-group" id="divSms" style="<?php echo ($dataAutomation['type'] != "sms" ) ? "display:none;" : "";?>">
                <label><?php echo __('smsText')?> </label>
                <textarea class="form-control" id="smsText" maxlength="160">
                    <?php echo $dataAutomation['sms_content'];?>
                </textarea>
            </div>

            <div class="form-group">
                <label><?php echo __('pluginType')?> </label> <select
                    class="form-control" id="pluginType">
                    <option value="">Select Plugin Type.</option>
                    <option value="RB" <?php echo ($dataAutomation['plan_group_code'] == 'RB') ? "selected" : "";?>>
                        <?php echo __('restaurantBookings')?>
                    </option>
                    <option value="TB" <?php echo ($dataAutomation['plan_group_code'] == 'TB') ? "selected" : "";?>>
                        <?php echo __('timeslotBookings')?>
                    </option>
                    <option value="AS" <?php echo ($dataAutomation['plan_group_code'] == 'AS') ? "selected" : "";?>>
                        <?php echo __('appointmentScheduler')?>
                    </option>
                </select>
            </div>

            <div class="form-group">
                <label><?php echo __('numberOfPreviousBooking')?> </label>
                <input type="text" id="cntPreviousBooking" class="form-control" value="<?php echo $dataAutomation['cnt_previous_booking']?>" />
            </div>

            <div class="form-group">
                <label><?php echo __('dayOfPreviousBooking')?> </label>
                <input type="text" id="daysPreviousBooking" class="form-control" value="<?php echo $dataAutomation['days_previous_booking']?>" />
            </div>
        </div>
    </div>
<script src="js/footable.js" type="text/javascript"></script>
<script src="js/footable.sort.js" type="text/javascript"></script>
<script src="js/bootstrap-datepicker.js"></script>
<script type="text/javascript" src="js/automationForm.js"></script>
<script src="wysiwyg/scripts/innovaeditor.js" type="text/javascript"></script>
<script src="wysiwyg/scripts/innovamanager.js" type="text/javascript"></script>
<script src="http://ajax.googleapis.com/ajax/libs/webfont/1/webfont.js" type="text/javascript"></script>
<script src="wysiwyg/scripts/common/webfont.js" type="text/javascript"></script>
<script src="wysiwyg/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
</body>
</html>
