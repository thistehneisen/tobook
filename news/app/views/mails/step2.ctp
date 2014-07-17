<?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Mail.id')), array('class' => 'button', 'style' => 'float: right; margin-top: 15px;margin-right: 0px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Mail.id'))); ?> 		   <h2><?php __('Create Newsletter'); ?></h2>
<ul class="fiveStep" id="mainNav">
    <li class="lastDone"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 1) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/1/"); ?>"<?php } ?>><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Set subject"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Select recipients"); }else{  __("Set delay"); } ?></span></a></li>
    <li class="current"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 2) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/2/"); ?>"<?php } ?>><em><?php __("Step 2: Template"); ?></em><span><?php __("Choose newsletter template and type"); ?></span></a></li>
    <li><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 3) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/3/"); ?>"<?php } ?>><em><?php __("Step 3: Content"); ?></em><span><?php __("Fill in content"); ?></span></a></li>

    <li  ><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/4/"); ?>"<?php } ?> ><em><?php __("Step 4: Preview and Test"); ?></em> <span style="width:200px"><?php __("Preview newsletter"); ?><br /><?php __("Send test newsletter"); ?></span></a></li>
    <li class="mainNavNoBg"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/5/"); ?>"<?php } ?>><em><?php __("Step 5: Send newsletter"); ?></em><span><?php __("Choose tracked links"); ?><br /><?php  if ($this->Form->value('Mail.campaign_id') == 0){ __("Send or schedule"); }else{ __("Publish newsletter"); } ?></span></a></li>

</ul>
<div class="mails form" style="clear:both;padding-top:12px;">
    <?php echo $this->Form->create('Mail'); ?>

    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('step', array("type" => "hidden"));
     __("type",true);
    echo $this->Form->input('type');?>
            <div class="input comment">
          <?php __("Most modern graphic email clients allow the use of HTML for the message body."); ?>
    </div>
    <?php
    echo $this->Form->input('template_id',array("div"=>array("style"=>"display:none;")));
    echo $this->Form->input('last_step', array("type" => "hidden"));
    $first = true;
    echo "<h3>". __("Template",true)."</h3>";
    foreach ($templates_full as $templ) {
    ?>
        <div onclick="<?php echo 'selectTemplate(\'' . $templ['Template']['id'] . '\');return false;'; ?>" style="cursor:pointer;"  class="template_box<?php if (($first && $this->Form->value('template_id')=="" ) || $this->Form->value('template_id') == $templ['Template']['id']) {
            echo " notice2";
        } ?>" id="<?php echo "temp" . $templ['Template']['id']; ?>">
        <?php
        echo $html->link(
                '<img src="' . $html->url("/") . 'thumb.php?src=' . $templ['Template']['preview'] . '&w=150&h=112" />',
                '#',
                array('escape' => false, "style" => "margin-left:25px;margin-right:25px;",
                    'onclick' => 'selectTemplate(\'' . $templ['Template']['id'] . '\');return false;'
        ));
        $first = false;
        ?><br />
        <p style="text-align:center;margin-bottom: 0;"><b><?php echo $templ['Template']['name']; ?></b></p>
    </div>

    <?php    }
    ?>


<?php    $this->Form->end(__('Next', true)); ?>
<div class="submit"><a class="button" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/1/"); ?>"><?php __("Back"); ?></a> <input type="submit" value="<?php __("Next"); ?>"></div>
</form>
</div>
<script type="text/javascript">
    function selectTemplate(id){
        $("#MailTemplateId").val(id);
        $('.template_box').removeClass('notice2');
        $('#temp'+id).addClass('notice2');
 

    }

</script>