<?php
function divi($a, $b) {
    if ($b == 0) {
        return 0;
    }
    return $a / $b;
}
?><div class="mails index">
      <?php echo $this->Html->link($this->Html->image("icons/chart-up--pencil.png", array("alt" => __('conf', true))) . __('Edit Campaign', true), array("controller"=>"campaigns",'action' => 'edit',$campaign['Campaign']["id"],1), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); ?>

     <?php echo $this->Html->link($this->Html->image("icons/server-cast.png", array("alt" => __('conf', true))) .__('Run newsletter mailing job', true), array('action' => 'sendAll',1), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 5px;')); ?>
  
    <h2>     <span <?php
if ($campaign['Campaign']['active'] == 2) {
?>title="<?php strftime("%e %B %Y",  strtotime($campaign['Campaign']['start'])); ?>"<?php } ?>  style="float:left;margin-top: -1px;margin-right: 4px;" class="tag<?php if ($campaign['Campaign']['active'] == 0) {
                echo 0;
            } else if ($campaign['Campaign']['active'] == 1) {

                echo 2;

            } else if ($campaign['Campaign']['active'] == 2) {
                echo 1;
            } ?>"><?php
            if ($campaign['Campaign']['active'] == 0) {
                echo __('Expired', true);
            } else if ($campaign['Campaign']['active'] == 1) {

                echo __('Active', true);

            } else if ($campaign['Campaign']['active'] == 2) {
                echo __('Scheduled', true);
            }
        ?></span><?php
 
    __('Campaign'); echo " &ldquo;".$campaign["Campaign"]["name"]."&rdquo;"; ?>

     </h2>

    <div class="progress-container" style="margin-top: 55px;"><div title="<?php __("Sent &amp; Read"); ?> (<?php echo  round(divi(($recipient["read"]) * 100, $recipient["total"])); ?>%)" style="background:#69C620; width: <?php echo round(divi($recipient["read"] * 100, $recipient["total"]), 2); ?>%"></div>
        <div title="<?php __("Sent"); ?>  (<?php echo  round(divi(($recipient["sent"] ) * 100, $recipient["total"])); ?>%)"  style="width: <?php echo round(divi($recipient["sent"] * 100, $recipient["total"]), 2); ?>%"></div>  <div title="<?php __("Failed"); ?> (<?php echo  round(divi(($recipient["failed"]) * 100, $recipient["total"])); ?>%)"  style="width: <?php echo round(divi($recipient["failed"] * 100, $recipient["total"]), 2) - 0.1; ?>%;background: #E9867C"></div>
        <div title="<?php __("Unsent"); ?> (<?php echo  round(divi(($recipient["total"]-$recipient["sent"]-$recipient["read"]-$recipient["failed"]) * 100, $recipient["total"])); ?>%)"  style="width: <?php echo round(divi(($recipient["total"]-$recipient["sent"]-$recipient["read"]-$recipient["failed"]) * 100, $recipient["total"]), 2) - 0.1; ?>%;background: #F3F3F3"></div>
    </div>


    <dl style="margin-top:12px;">
         <dt ><?php __("Time span"); ?></dt>
        <dd >
           <?php echo strftime("%e %B %Y",  strtotime($campaign['Campaign']['start']));
                if($campaign['Campaign']["forever"]==0){
                    echo " <b>".__("to",true)."</b> ". strftime("%e %B %Y",  strtotime($campaign['Campaign']['end']));
                }else {
                     echo " <b>".__("to",true)."</b> ".__("an indefinite time",true);
                }
                ?>
            &nbsp;
        </dd>
        <dt><?php __("Unique recipients"); ?></dt>
        <dd>
            <?php echo $recipient["total2"]; ?>
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
            <?php echo $unsub . " / " . $recipient["read"] . " (" . round(divi($unsub * 100, $recipient["read"])) . "%)";
            ; ?>
            &nbsp;
        </dd>
      <dt ><?php __("Sent to Friends"); ?></dt>
        <dd >
            <?php echo $sendtof . " / " . $recipient["read"] . " (" . round(divi($sendtof * 100, $recipient["read"])) . "%)";
            ; ?>
            &nbsp;
        </dd>
    </dl>
    <h3><?php __("Newsletters"); ?></h3>
    <?php echo $this->Html->link($this->Html->image("icons/mail--plus.png", array("alt" => __('Add', true))) . __('Create new newsletter', true), array('action' => 'add',$campaign["Campaign"]["id"]), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -40px; margin-right: 0px; margin-bottom: 0px;')); ?>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th style="width:63px"><?php echo $this->Paginator->sort(__("Status",true),'status'); ?></th>
            <th ><?php echo $this->Paginator->sort(__("Subject",true),'subject'); ?></th>

            <th style="width:230px"><?php echo $this->Paginator->sort(__("Send",true),'delay'); ?></th>



            <th class="actions" style="width:80px"><?php __('Actions'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($mails as $mail):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
            $icon = "information-white.png";
            $tex=__('Info', true);
            if ($mail['Mail']['status'] == 0) {
                $tex=__('Edit', true);
                $icon = "mail--pencil.png";
            }
        ?>
            <tr<?php echo $class; ?>>
                <td><span class="tag<?php if($mail['Mail']['status']==0){ echo 0; }else{echo 2; }  ?>"><?php
            if ($mail['Mail']['status'] == 0) {
                echo __('Draft', true);
            } else if ($mail['Mail']['status'] == 1) {

                      echo __('Ready', true);
              
            } else if ($mail['Mail']['status'] == 2) {
                echo __('Ready', true);
            }
        ?></span>&nbsp;</td>
            <td><?php echo $mail['Mail']['subject']; ?>&nbsp;</td>

            <td><?php echo $mail['Mail']['delay']; ?> <?php __('days after subscription'); ?>&nbsp;</td>





            <td  >
 <?php echo $this->Html->link($this->Html->image("icons/blog-blue.png", array("alt" => __('Preview', true))), array('action' => 'preview', $mail['Mail']['id']), array("class" => "modal", "escape" => false,"title" => __('Preview', true)." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/" . $icon, array("alt" => __('Edit', true))), array('action' => 'step', $mail['Mail']['id'], 1), array("escape" => false,"title" => $tex." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;")); ?>
                                <?php echo $this->Html->link($this->Html->image("icons/document-copy.png", array("alt" => __('Duplicate', true))), array('action' => 'duplicate', $mail['Mail']['id'], 1), array("escape" => false,"title" => __('Duplicate', true)." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;"), sprintf(__('Are you sure you want to duplicate # %s?', true), $mail['Mail']['id'])); ?>
                <?php echo $this->Html->link($this->Html->image("icons/mail--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $mail['Mail']['id'],$campaign['Campaign']['id']), array("escape" => false,"title" => __('Delete', true)." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $mail['Mail']['id'])); ?>
                </td>
            </tr>
        <?php endforeach; ?>
                </table>
                <p>
        <?php
                    echo $this->Paginator->counter(array(
                        'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
                    ));
        ?>            </p>

                <div class="paging">
        <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
                                                	 | 	<?php echo $this->Paginator->numbers(); ?>
                    |
        <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>
                 <hr /><img src="<?php echo $html->url("/"); ?>img/leg.png" style="float: right; padding-top: 8px; padding-right: 8px;"/>  <h3><?php __('Timeline'); ?></h3>

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
                        $.plot($("#placeholder"),  [send,rec], { series: {
                                lines: { show: true },
                                points: { show: true }
                            },
                            colors: ["#ACE97C", "#69C620"], xaxis: { mode: "time" } });


                    });

            </script>
        


</div>
