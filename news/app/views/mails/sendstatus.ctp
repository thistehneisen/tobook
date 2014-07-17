<?php
if(strtotime($mail['Mail']["send_on"]) - mktime()>0){
?>
<div class="notice" id="flashMessage"><?php echo $this->Html->image("icons/clock.png", array("alt" => __('cat', true))); ?>  <?php __("Newsletter is scheduled"); ?>: <?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($mail['Mail']['send_on']))); ?></div>
<?php

}
$tdiv1 = strtotime($mail['Configuration']["free"]) - mktime();
$tdiv=$tdiv1;
if($mail['Configuration']["mcount"]<$mail['Configuration']["mails_per_time"]){
    $tdiv=0;
}
$tosend= $recipient["total"]-($recipient["sent"] + $recipient["read"]);
if($mail['Configuration']["mails_per_time"]-$mail['Configuration']["mcount"]<$tosend&&$tdiv==0){
    $tosend=$mail['Configuration']["mails_per_time"]-$mail['Configuration']["mcount"];
}else if($mail['Configuration']["mails_per_time"] <$tosend){
$tosend=$mail['Configuration']["mails_per_time"];
}
function divi($a, $b) {
    if ($b == 0) {
        return 0;
    }
    return $a / $b;
}
?><div class="mails view">
     <?php echo $this->Html->link($this->Html->image("icons/xfn-colleague.png", array("alt" => __('conf', true))) . __('More Recipent Information', true), array("controller"=>"recipients",'action' => 'index', $mail['Mail']['id']), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); ?>

  <a href="<?php echo $this->Html->url(array( "action" => "campaign", $mail['Mail']["campaign_id"])); ?>" class="button sbutton" style="float:left;top:3px !important;"><?php __("Back"); ?></a>
  <h2><?php __("Newsletter"); ?> "<?php echo $mail['Mail']['subject']; ?>"</h2>

    <div class="progress-container" style="margin-top: 55px;"><div title="<?php __("Sent &amp; Read"); ?> (<?php echo  round(divi(($recipient["read"]) * 100, $recipient["total"])); ?>%)" style="background:#69C620; width: <?php echo number_format(round(divi($recipient["read"] * 100, $recipient["total"]), 2), 2, '.', ''); ?>%"></div>
        <div title="<?php __("Sent"); ?>  (<?php echo  round(divi(($recipient["sent"] ) * 100, $recipient["total"])); ?>%)"  style="width: <?php echo round(divi($recipient["sent"] * 100, $recipient["total"]), 2); ?>%"></div>  <div title="<?php __("Failed"); ?> (<?php echo  round(divi(($recipient["failed"]) * 100, $recipient["total"])); ?>%)"  style="width: <?php echo number_format(round(divi($recipient["failed"] * 100, $recipient["total"]), 2), 2, '.', '') - 0.1; ?>%;background: #E9867C"></div>
        <div title="<?php __("Unsent"); ?> (<?php echo  round(divi(($recipient["total"]-$recipient["sent"]-$recipient["read"]-$recipient["failed"]) * 100, $recipient["total"])); ?>%)"  style="width: <?php echo number_format(round(divi(($recipient["total"]-$recipient["sent"]-$recipient["read"]-$recipient["failed"]) * 100, $recipient["total"]), 2), 2, '.', '') - 0.1; ?>%;background: #F3F3F3"></div>
    </div>


    <dl style="margin-top:12px;">
        <dt><?php __("Total recipients"); ?></dt>
        <dd>
            <?php echo $recipient["total"]; ?>
            &nbsp;
        </dd>
        <dt style="color:darkgreen"><?php __("Newsletters sent"); ?></dt>
        <dd style="color:darkgreen">
            <?php echo ($recipient["sent"] + $recipient["read"]) . " / " . $recipient["total"] . " (" . round(divi(($recipient["sent"] + $recipient["read"]) * 100, $recipient["total"])) . "%)"; ?>
            &nbsp;
        </dd>
        <dt style="color:green"><?php __("Newsletters read"); ?></dt>
        <dd style="color:green">
            <?php echo ($recipient["read"]) . " / " . ($recipient["sent"] + $recipient["read"]) . " (" . round(divi(($recipient["read"]) * 100, ($recipient["sent"] + $recipient["read"]))) . "%)"; ?>
            &nbsp;
        </dd>
        <dt style="color:darkred"><?php __("Newsletters failed"); ?></dt>
        <dd style="color:darkred">
            <?php echo $recipient["failed"] . " / " . $recipient["total"] . " (" . round(divi($recipient["failed"] * 100, $recipient["total"])) . "%)";
            ; ?>
            &nbsp;
        </dd>
        <dt ><?php __("Unsubscribes"); ?></dt>
        <dd >
            <?php echo $mail['Mail']["unsubscribed"] . " / " . $recipient["read"] . " (" . round(divi($mail['Mail']["unsubscribed"] * 100, $recipient["read"])) . "%)";
            ; ?>
            &nbsp;
        </dd>
                <dt ><?php __("Sent to Friends"); ?></dt>
        <dd >
            <?php echo $mail['Mail']["sendtof"] . " / " . $recipient["read"] . " (" . round(divi($mail['Mail']["sendtof"] * 100, $recipient["read"])) . "%)";
            ; ?>
            &nbsp;
        </dd>

    </dl>

    <?php
            if ($mail['Mail']["status"] != 2&&$mail['Mail']["status"] != 3) {

            if($tosend!=0&& Configure::read('Settings.parallel_jobs') != '1'){
    if (strtotime($mail['Mail']["send_on"]) - mktime() < 0) {
        if ($tdiv > 0) {
            echo $this->Html->link($this->Html->image("icons/clock.png", array("alt" => __('Send', true))) . __('Send next newsletters in', true) . ' <span class="countdown">' . $tdiv . '</span> ' . __('seconds', true), "#", array("escape" => false, 'class' => 'button', "id" => "countD", 'style' => 'margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;'));
        }

        echo $this->Html->link($this->Html->image("icons/mails-stack.png", array("alt" => __('Send', true))) . __('Send next ', true) . $tosend . " Newsletters", array('action' => 'send', $mail['Mail']["id"]), array("escape" => false, 'class' => 'button', 'style' => 'margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;', "id" => "sendNl"));
    }
            }
    if ($recipient["sent"] + $recipient["read"] == 0) {
        echo $this->Html->link($this->Html->image("icons/mail--pencil.png", array("alt" => __('Send', true))) . __('Go back to edit', true), array('action' => 'back', $mail['Mail']["id"]), array("escape" => false, 'class' => 'button', 'style' => 'margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;'));
    }
      echo $this->Html->link($this->Html->image("icons/exclamation-diamond.png", array("alt" => __('Stop Sending', true))) . __('Stop Sending', true), array('action' => 'stop', $mail['Mail']["id"]), array("escape" => false, 'class' => 'button', 'style' => 'margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;'));

}
echo $this->Html->link($this->Html->image("icons/document-copy.png", array("alt" => __('Duplicate Newsletter', true))) . __('Duplicate Newsletter', true), array('action' => 'duplicate', $mail['Mail']["id"]), array("escape" => false, 'class' => 'button', 'style' => 'margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;'), sprintf(__('Are you sure you want to duplicate # %s?', true), $mail['Mail']['id']));
 
 if($mail['Mail']["campaign_id"]==0){
              

  echo $this->Html->link($this->Html->image("icons/arrow-circle-double.png", array("alt" => __('Reactivate Newsletter', true))) . __('Reactivate Newsletter', true), array('action' => 'reactivate', $mail['Mail']["id"]), array("escape" => false, 'class' => 'button', 'style' => 'margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;'), sprintf(__('Are you sure you want to reactivate # %s?', true), $mail['Mail']['id']));

    echo $this->Html->link($this->Html->image("icons/arrow-circle-double.png", array("alt" => __('Reactivate Failed', true))) . __('Reactivate Failed', true), array('action' => 'reactivatefailed', $mail['Mail']["id"]), array("escape" => false, 'class' => 'button', 'style' => 'margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;'), sprintf(__('Are you sure you want to reactivate # %s?', true), $mail['Mail']['id']));
}
if (count($mail['Link']) == 0) {
    ?>
                <a style="margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;" class="button modal" href="<?php echo $html->url("/"); ?>mails/preview/<?php echo $mail["Mail"]["id"]; ?>"><?php echo $this->Html->image("icons/mail-open-document-text.png", array("alt" => __('Open', true))); ?><?php __("Open Newsletter"); ?></a>
    <?php } else { ?>
                <hr />  <h3><?php __("Tracked links"); ?></h3>

                <table>
                    <thead><th><?php __("Url"); ?></th><th><?php __("Clicks"); ?></th></thead>
        <?php
                foreach ($mail['Link'] as $l) {
        ?>
                    <tr>
                        <td ><?php echo $l["url"]; ?></td>
                        <td style="width:80px">
                <?php echo $l["clicks"] . " (" . round(divi(($l["clicks"]) * 100, $recipient["read"])) . "%)"; ?>
                    &nbsp;
                </td></tr>
        <?php
                }
        ?>


            </table>
            <a style="margin-right: 0px; float: left; margin-bottom: 7px; margin-left: 12px;" class="button modal" href="<?php echo $html->url("/"); ?>mails/preview/<?php echo $mail["Mail"]["id"]; ?>"><?php echo $this->Html->image("icons/mail-open-document-text.png", array("alt" => __('Open', true))); ?><?php __("Open Newsletter with click overlay"); ?></a>
    <?php } ?>
            <hr /><img src="<?php echo $html->url("/"); ?>img/leg.png" style="float: right; padding-top: 8px; padding-right: 8px;"/>  <h3>Timeline</h3>

            <div id="placeholder" style="width:100%;height:200px;"></div>




            <script id="source">
                $(function () {
                    var send = [ <?php
            foreach ($chartdata as $k => $v) {


                echo " [$k, $v],";
            }
    ?>];
                    var rec = [ <?php
            foreach ($chartdata2 as $k => $v) {


                echo " [$k, $v],";
            }
    ?>];
                        // var d1 = [ [738885600000, 359.41], [741477600000, 357.44], [744156000000, 355.30], [746834400000, 353.87], [749430000000, 354.04], [752108400000, 355.27], [754700400000, 356.70], [757378800000, 358.00], [760057200000, 358.81], [762476400000, 359.68], [765151200000, 361.13], [767743200000, 361.48], [770421600000, 360.60], [773013600000, 359.20], [775692000000, 357.23], [778370400000, 355.42], [780966000000, 355.89], [783644400000, 357.41], [786236400000, 358.74], [788914800000, 359.73], [791593200000, 360.61], [794012400000, 361.58], [796687200000, 363.05], [799279200000, 363.62], [801957600000, 363.03], [804549600000, 361.55], [807228000000, 358.94], [809906400000, 357.93], [812502000000, 357.80], [815180400000, 359.22], [817772400000, 360.44], [820450800000, 361.83], [823129200000, 362.95], [825634800000, 363.91], [828309600000, 364.28], [830901600000, 364.94], [833580000000, 364.70], [836172000000, 363.31], [838850400000, 361.15], [841528800000, 359.40], [844120800000, 359.34], [846802800000, 360.62], [849394800000, 361.96], [852073200000, 362.81], [854751600000, 363.87], [857170800000, 364.25], [859845600000, 366.02], [862437600000, 366.46], [865116000000, 365.32], [867708000000, 364.07], [870386400000, 361.95], [873064800000, 360.06], [875656800000, 360.49], [878338800000, 362.19], [880930800000, 364.12], [883609200000, 364.99], [886287600000, 365.82], [888706800000, 366.95], [891381600000, 368.42], [893973600000, 369.33], [896652000000, 368.78], [899244000000, 367.59], [901922400000, 365.84], [904600800000, 363.83], [907192800000, 364.18], [909874800000, 365.34], [912466800000, 366.93], [915145200000, 367.94], [917823600000, 368.82], [920242800000, 369.46], [922917600000, 370.77], [925509600000, 370.66], [928188000000, 370.10], [930780000000, 369.08], [933458400000, 366.66], [936136800000, 364.60], [938728800000, 365.17], [941410800000, 366.51], [944002800000, 367.89], [946681200000, 369.04], [949359600000, 369.35], [951865200000, 370.38], [954540000000, 371.63], [957132000000, 371.32], [959810400000, 371.53], [962402400000, 369.75], [965080800000, 368.23], [967759200000, 366.87], [970351200000, 366.94], [973033200000, 368.27], [975625200000, 369.64], [978303600000, 370.46], [980982000000, 371.44], [983401200000, 372.37], [986076000000, 373.33], [988668000000, 373.77], [991346400000, 373.09], [993938400000, 371.51], [996616800000, 369.55], [999295200000, 368.12], [1001887200000, 368.38], [1004569200000, 369.66], [1007161200000, 371.11], [1009839600000, 372.36], [1012518000000, 373.09], [1014937200000, 373.81], [1017612000000, 374.93], [1020204000000, 375.58], [1022882400000, 375.44], [1025474400000, 373.86], [1028152800000, 371.77], [1030831200000, 370.73], [1033423200000, 370.50], [1036105200000, 372.18], [1038697200000, 373.70], [1041375600000, 374.92], [1044054000000, 375.62], [1046473200000, 376.51], [1049148000000, 377.75], [1051740000000, 378.54], [1054418400000, 378.20], [1057010400000, 376.68], [1059688800000, 374.43], [1062367200000, 373.11], [1064959200000, 373.10], [1067641200000, 374.77], [1070233200000, 375.97], [1072911600000, 377.03], [1075590000000, 377.87], [1078095600000, 378.88], [1080770400000, 380.42], [1083362400000, 380.62], [1086040800000, 379.70], [1088632800000, 377.43], [1091311200000, 376.32], [1093989600000, 374.19], [1096581600000, 374.47], [1099263600000, 376.15], [1101855600000, 377.51], [1104534000000, 378.43], [1107212400000, 379.70], [1109631600000, 380.92], [1112306400000, 382.18], [1114898400000, 382.45], [1117576800000, 382.14], [1120168800000, 380.60], [1122847200000, 378.64], [1125525600000, 376.73], [1128117600000, 376.84], [1130799600000, 378.29], [1133391600000, 380.06], [1136070000000, 381.40], [1138748400000, 382.20], [1141167600000, 382.66], [1143842400000, 384.69], [1146434400000, 384.94], [1149112800000, 384.01], [1151704800000, 382.14], [1154383200000, 380.31], [1157061600000, 378.81], [1159653600000, 379.03], [1162335600000, 380.17], [1164927600000, 381.85], [1167606000000, 382.94], [1170284400000, 383.86], [1172703600000, 384.49], [1175378400000, 386.37], [1177970400000, 386.54], [1180648800000, 385.98], [1183240800000, 384.36], [1185919200000, 381.85], [1188597600000, 380.74], [1191189600000, 381.15], [1193871600000, 382.38], [1196463600000, 383.94], [1199142000000, 385.44]];

                        $.plot($("#placeholder"),  [send,rec], { series: {
                                lines: { show: true,fill: true },
                                points: { show: true }
                                
                            },
                            colors: ["#e2e508", "#69C620"], xaxis: { mode: "time" } });


                    });
<?php
            if ($mail['Mail']["status"] != 2) {
                if ($tdiv > 0) {
?>
                            $("#sendNl").hide();
                            $('span.countdown').countdown({seconds: <?php echo $tdiv; ?>,callback:"showSend()"});
                            function showSend(){

                                $("#countD").hide();
                                $("#sendNl").show();
                            }
<?php
                }
            }
?>
            </script><?php
            if($mail['Mail']["campaign_id"]==0){
                ?>

            <hr />
            <h3><?php __('Categories sent to'); ?></h3><br />
    <?php
            if (!empty($mail['Category'])) {
                foreach ($mail['Category'] as $value) {


                    echo $this->Html->link($this->Html->image("icons/xfn-colleague.png", array("alt" => __('cat', true))) . $value["fullname"], array("controller" => "subscribers", 'action' => 'index', $value["id"]), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: -5px;'));
                }
            }
    ?><div style="clear:both">&nbsp;</div>
<?php } ?>

</div>