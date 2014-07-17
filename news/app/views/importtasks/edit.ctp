<div class="configurations form">

    <?php echo $this->Form->create('Importtask'); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Importtask.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Configuration.id'))); ?>
    <h2><?php __('Edit Import Task'); ?></h2>
        <ul class="fourStep" id="mainNav">
    <li class="current"><a title=""  class="linked" href="<?php echo $this->Html->url(array("action"=>"edit", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Name"); ?><br /><?php __("Description"); ?></span></a></li>
    <li ><a title="" class="linked"  href="<?php echo $this->Html->url(array("action"=>"edit1", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 2: Connection to DB"); ?></em><span><?php __("Server Host and Port"); ?><br /><?php __("User/Password"); ?></span></a></li>
    <li><a title=""  class="linked"  href="<?php echo $this->Html->url(array("action"=>"edit2", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 3: Query"); ?></em><span><?php __("Enter SQL Query"); ?></span></a></li>

    <li class="mainNavNoBg"><a title="" class="linked" href="<?php echo $this->Html->url(array("action"=>"edit3", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 4: Settings"); ?></em><span><?php __("See Results"); ?><br /><?php __("Select Categories"); ?></span></a></li>
</ul><hr />
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('description');
       
 echo $this->Form->end(__('Next', true)); ?>


</div>