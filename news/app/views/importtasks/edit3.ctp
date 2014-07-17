   <?php
   echo $this->Form->create('Importtask', array("url" => array("action" => "edit3")));
   echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Importtask.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Configuration.id'))); ?>

<h2>Setup import form DB</h2><ul class="fourStep" id="mainNav">
    <li class="done"><a title=""  class="linked" href="<?php echo $this->Html->url(array("action"=>"edit", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Name"); ?><br /><?php __("Description"); ?></span></a></li>
    <li class="done"><a title="" class="linked"  href="<?php echo $this->Html->url(array("action"=>"edit1", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 2: Connection to DB"); ?></em><span><?php __("Server Host and Port"); ?><br /><?php __("User/Password"); ?></span></a></li>
    <li class="lastDone"><a title=""  class="linked"  href="<?php echo $this->Html->url(array("action"=>"edit2", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 3: Query"); ?></em><span><?php __("Enter SQL Query"); ?></span></a></li>

    <li class="mainNavNoBg current"><a title="" class="linked" href="<?php echo $this->Html->url(array("action"=>"edit3", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 4: Settings"); ?></em><span><?php __("See Results"); ?><br /><?php __("Select Categories"); ?></span></a></li>
</ul><hr />
<?php

echo $form->input("act", array("options" =>
    array("ignore" =>  __("Ignore", true), "update" =>  __("Update categories", true), "overwrite" => __( "Overwrite categories", true)), "label" =>  __('Existing subscribers', true))
);
echo $form->input("form",array("label"=> __("Subscription Form", true)));
echo $form->input("resubscribe", array("label" => __("Resubscribe", true), "type" => "checkbox"));
?>
<?php

echo $this->Form->input('Category', array("label" => "Add to this category", 'multiple' => 'checkbox', "div" => array("class" => "cbox"))) . "<div style=\"clear:boath;\" ></div><br />";
echo "<h3>".__("Data", true)."</h3>";
echo $this->Form->input('id');
if(isset($data[0])&&count($data[0])>0){
foreach ($data[0] as $k => $h) {
  $cols = array("first_name" => __("First Name", true), "last_name" => __("Last Name", true), "mail_adresse" => __("Mail Address", true), "notes" => __("Notes", true));
    if (Configure::read('Settings.custom1_show') == "1") {
        $cols["custom1"] = Configure::read('Settings.custom1_label');
    }
    if (Configure::read('Settings.custom2_show') == "1") {
        $cols["custom2"] = Configure::read('Settings.custom2_label');
    }
    if (Configure::read('Settings.custom3_show') == "1") {
        $cols["custom3"] = Configure::read('Settings.custom3_label');
    }
    if (Configure::read('Settings.custom4_show') == "1") {
        $cols["custom4"] = Configure::read('Settings.custom4_label');
    }
    $heads[] = $form->select(
                    'Import.' . $k,$cols
    );
    $heads2[] = str_replace(":", ".", $k);
}
?><div style="overflow:auto;"><?php
echo $html->tag(
        'table',
        $html->tableHeaders(
                $heads2
        ) . $html->tableHeaders(
                $heads
        ) . $html->tableCells(
                $data
)); ?></div><?php
}else{echo __("NO DATA", true);}
   $this->Form->end(__('Save Task', true));
   ?>
<div class="submit"><a class="button" href="<?php echo $this->Html->url(array("action"=>"edit2", $this->Form->value('Importtask.id'))); ?>"><?php __('Back'); ?></a> <input type="submit" value="<?php __('Save Task'); ?>"></div>
</form>