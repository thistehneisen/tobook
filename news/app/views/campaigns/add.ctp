<div class="campaigns form">
    <?php echo $this->Form->create('Campaign'); ?>
    <h2><?php __('Add Campaign'); ?></h2>
    <?php
    __('Name',true);
     __('Description',true);
     __('Start',true);
     __('End',true);
      __('Forever',true);
    echo $this->Form->input('name');
    echo $this->Form->input('description');
     echo $this->Form->input('Category', array("label" => __("Categories",true), 'multiple' => 'checkbox', "options" => $cats, "div" => array("class" => "cbox")));
    echo "<div style=\"clear:both;\" ></div>";
    echo $this->Form->input('start', array("div"=>array("style"=>"padding-top:12px;"),'type' => 'date', 'dateFormat' => 'DMY'));
    $st = "";
    if ($this->Form->value('Campaign.forever') == 1) {
        $st = "display:none;";
    }
    echo $this->Form->input('forever', array("onclick" => 'if($(\'#CampaignForever\').is(\':checked\')){$(\'#enddate\').fadeOut();}else{$(\'#enddate\').fadeIn();}'));

    echo $this->Form->input('end', array("div" => array("id" => "enddate", "style" => $st), 'type' => 'date', 'dateFormat' => 'DMY'));
      echo $this->Form->input('sendtoold',array("label"=>__("Send to subscribers",true),"after"=>"  ".__("subscribed before start date",true)));

    ?>


    <?php echo $this->Form->end(__('Save', true)); ?>


</div>