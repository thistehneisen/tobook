<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Mail.id')), array('class' => 'button', 'style' => 'float: right; margin-top: 15px;margin-right: 0px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Mail.id'))); ?> 		   <h2><?php __('Create Newsletter'); ?></h2>
<ul class="fiveStep" id="mainNav">
    <li class="done"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 1) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/1/"); ?>"<?php } ?>><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Set subject"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Select recipients"); }else{  __("Set delay"); } ?></span></a></li>
    <li class="done"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 2) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/2/"); ?>"<?php } ?>><em><?php __("Step 2: Template"); ?></em><span><?php __("Choose newsletter template and type"); ?></span></a></li>
    <li class="lastDone"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 3) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/3/"); ?>"<?php } ?>><em><?php __("Step 3: Content"); ?></em><span><?php __("Fill in content"); ?></span></a></li>

    <li class="current " ><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/4/"); ?>"<?php } ?> ><em><?php __("Step 4: Preview and Test"); ?></em> <span style="width:200px"><?php __("Preview newsletter"); ?><br /><?php __("Send test newsletter"); ?></span></a></li>
    <li class="mainNavNoBg"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/5/"); ?>"<?php } ?>><em><?php __("Step 5: Send newsletter"); ?></em><span><?php __("Choose tracked links"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Send or schedule"); }else{ __("Publish newsletter"); } ?></span></a></li>

</ul>
<div class="mails form" style="clear:both;padding-top:12px;">
    <form accept-charset="utf-8" action="<?php echo $this->Html->url("/mails/test/" . $this->Form->value('Mail.id') . "/"); ?>" method="post" id="MailStepForm"><div style="display: none;"><input type="hidden" value="PUT" name="_method"></div>

        <div class="input text required"><label for="TestAdresse"><?php __("Test address"); ?></label><input type="text" id="TestAdresse" value="" maxlength="255" name="data[Mail][TestAdresse]"></div>
       


        <div class="submit"><input type="submit" value="<?php __("Send test mail"); ?>"></div></form>
        <hr />

<div class="submit" style="padding-top: 0px; height: 33px;"><a class="button" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/3/"); ?>"><?php __("Back"); ?></a> <a class="button" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/5/"); ?>"><?php __("Next"); ?></a></div>
 
    <?php
    if ($this->Form->value('Mail.type') == 0 || $this->Form->value('Mail.type') == 1) {
    ?>
        <label style="clear:both;float:none;" ><?php __("Html Preview"); ?>:</label>
        <iframe src="<?php echo $html->url("/")."mails/preview/".$this->Form->value('Mail.id'); ?>" width="100%" height="450px"></iframe>
    <?php
    }
    if ($this->Form->value('Mail.type') == 0 || $this->Form->value('Mail.type') == 2) {
    ?>
        <label style="clear:both;float:none;" ><?php __("Text Preview"); ?>:</label>
        <pre style="overflow: auto; height:450px;width:100%">
<?php echo wordwrap($this->Form->value('Mail.content_text')); ?>
    </pre>
    <?php
    }
    ?>
</div>