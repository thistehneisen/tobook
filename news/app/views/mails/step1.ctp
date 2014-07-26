<?php
if ($this->Form->value('Mail.id') != "") {
    echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Mail.id')), array('class' => 'button', 'style' => 'float: right; margin-top: 15px;margin-right: 0px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Mail.id')));
}
?> 		   <h2><?php __('Create Newsletter'); ?></h2>
<ul class="fiveStep" id="mainNav">
    <li class="current"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 > 1) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/1/"); ?>"<?php } ?>><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Set subject"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Select recipients"); }else{  __("Set delay"); } ?></span></a></li>
    <li ><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 2) { ?>class="linked"  href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/2/"); ?>"<?php } ?>><em><?php __("Step 2: Template"); ?></em><span><?php __("Choose newsletter template and type"); ?></span></a></li>
    <li><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 3) { ?>class="linked"  href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/3/"); ?>"<?php } ?>><em><?php __("Step 3: Content"); ?></em><span><?php __("Fill in content"); ?></span></a></li>

    <li  ><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/4/"); ?>"<?php } ?> ><em><?php __("Step 4: Preview and Test"); ?></em> <span style="width:200px"><?php __("Preview newsletter"); ?><br /><?php __("Send test newsletter"); ?></span></a></li>
    <li class="mainNavNoBg"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/5/"); ?>"<?php } ?>><em><?php __("Step 5: Send newsletter"); ?></em><span><?php __("Choose tracked links"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Send or schedule"); }else{ __("Publish newsletter"); } ?></span></a></li>
</ul>
<div class="mails form" style="clear:both;padding-top:12px;">

    <?php echo $this->Form->create('Mail'); ?>

    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('step', array("type" => "hidden"));
    echo $this->Form->input('last_step', array("type" => "hidden"));
    echo $this->Form->input('campaign_id', array("type" => "hidden"));

    echo $this->Form->input('subject');
      ?>
    <div class="input comment">
        <?php __("You can personalize the newsletter subject using these tags:"); ?><br />
        <?php __("Subscribers first name"); ?>:<b>{$first_name}</b><br />
        <?php __("Subscribers last name"); ?>:<b>{$last_name}</b>
    </div>
    <?php    echo $this->Form->input('private',array("label"=>__("Private Mail",true)));
   
    ?>
    <div class="input comment">
        <?php __("No public link, Hidde in RSS"); ?>
    </div>
    <?php    __("Configuration",true);
    echo $this->Form->input('configuration_id');
    ?>
    <div class="input comment">
        <?php __("Select mail server to send the newsletter"); ?>
    </div>
    <?php    __("Category",true);
    if ($this->Form->value('Mail.campaign_id') == 0){
        echo $this->Form->input('Category', array('multiple' => 'checkbox', "div" => array("class" => "cbox")));
    }else{
        echo $this->Form->input('delay', array("label"=>__("Send",true),"style"=>"width: 35px;","after"=>" ".__("days after subscription")));
    }
    ?>


    <?php echo $this->Form->end(__('Next', true)); ?>


</div>