<div class="campaigns form">
    <?php echo $this->Form->create('Campaign',array("url"=>array('action' => 'edit', $this->Form->value('Campaign.id'),$r))); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Campaign.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Campaign.id'))); ?>

    <h2><?php __('Edit Campaign'); ?></h2>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('description');
     echo $this->Form->input('Category', array("label" => "Categories", 'multiple' => 'checkbox', "options" => $cats, "div" => array("class" => "cbox")));
       echo "<div style=\"clear:both;\" ></div>";
    echo $this->Form->input('start', array("div"=>array("style"=>"padding-top:12px;"),'type' => 'date', 'dateFormat' => 'DMY'));

    $st = "";
    if ($this->Form->value('Campaign.forever') == 1) {
        $st = "display:none;";
    }
    echo $this->Form->input('forever', array("onclick" => 'if($(\'#CampaignForever\').is(\':checked\')){$(\'#enddate\').fadeOut();}else{$(\'#enddate\').fadeIn();}'));

    echo $this->Form->input('end', array("div" => array("id" => "enddate", "style" => $st), 'type' => 'date', 'dateFormat' => 'DMY'));
        echo $this->Form->input('sendtoold',array("label"=>"Send to subscribers","after"=>"  subscribed before start date"));

    ?>


    <?php echo $this->Form->end(__('Save', true)); ?>


</div>