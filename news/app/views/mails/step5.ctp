<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Mail.id')), array('class' => 'button', 'style' => 'float: right; margin-top: 15px;margin-right: 0px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Mail.id'))); ?> 		   <h2><?php __('Create Newsletter'); ?></h2>
<ul class="fiveStep" id="mainNav">
    <li class="done"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 1) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/1/"); ?>"<?php } ?>><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Set subject"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Select recipients"); }else{  __("Set delay"); } ?></span></a></li>
    <li class="done"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 2) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/2/"); ?>"<?php } ?>><em><?php __("Step 2: Template"); ?></em><span><?php __("Choose newsletter template and type"); ?></span></a></li>
    <li class="done"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 3) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/3/"); ?>"<?php } ?>><em><?php __("Step 3: Content"); ?></em><span><?php __("Fill in content"); ?></span></a></li>

    <li class="lastDone" ><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/4/"); ?>"<?php } ?> ><em><?php __("Step 4: Preview and Test"); ?></em> <span style="width:200px"><?php __("Preview newsletter"); ?><br /><?php __("Send test newsletter"); ?></span></a></li>
    <li class="mainNavNoBg current"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/5/"); ?>"<?php } ?>><em><?php __("Step 5: Send newsletter"); ?></em><span><?php __("Choose tracked links"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Send or schedule"); }else{ __("Publish newsletter"); } ?></span></a></li>

</ul>
<div class="mails form" style="clear:both;padding-top:12px;">

    <form accept-charset="utf-8" action="<?php echo $this->Html->url("/mails/prepare/" . $this->Form->value('Mail.id') . "/"); ?>" method="post" id="MailStepForm"><div style="display: none;"><input type="hidden" value="PUT" name="_method"></div>

        <div class="cbox required"><label for="CategoryCategory"><?php __("Tracked Links"); ?></label><input type="hidden" id="TrackedLinks" value="" name="data[TrackedLinks]">
            <?php
            $numb = 0;
            if (isset($links) && count($links) > 0) {
                foreach ($links as $link) {
                    $numb++;
            ?>
                    <div class="checkbox"><input type="checkbox" id="TrackedLinks<?php echo $numb; ?>" value="<?php echo urldecode($link) ?>" <?php if (strlen(strstr(urldecode($link), Router::url("/", true))) <= 0) { ?>checked="checked"<?php } ?> name="data[TrackedLinks][]"><label class="selected" for="TrackedLinks<?php echo $numb; ?>"><?php echo urldecode($link) ?></label></div>
            <?php
                }
            } else {
                echo "<div>".__("There are no links in the newsletter.",true)."</div>";
            }
            ?>

        </div>

        <div style="clear:both"></div><?php
            $this->Form->create('Mail');
            echo $this->Form->input('campaign_id', array("type" => "hidden"));
            if ($this->Form->value('Mail.campaign_id') == 0) {
                echo $this->Form->input('send_on', array('selected' => array(
                        'month' => date('m', strtotime("-15 minutes")), 'year' => date('Y', strtotime("-15 minutes")), 'day' => date('d', strtotime("-15 minutes")),
                        'hour' => date('H', strtotime("-15 minutes")), 'minute' => date('i', strtotime("-15 minutes"))
                    ), 'label' => __('Schedule newsletter',true)
                    , 'dateFormat' => 'DMY'
                    , 'minYear' => date('Y')
                    , 'maxYear' => date('Y') + 2, 'interval' => 15, 'timeFormat' => 24));

                echo '<div class="input select"><label >'.__("Server Time",true).'</label><span>'.date("F j, Y, g:i a").'</span></div>';
            } else {
              
            }
            $this->Form->end('Mail');
            ?>
              <div class="submit"><input type="submit" value="<?php if ($this->Form->value('Mail.campaign_id') == 0) {
                __("Send newsletter");
            } else {
                __( "Publish newsletter in campaign");
            } ?>"></div></form>
</div>