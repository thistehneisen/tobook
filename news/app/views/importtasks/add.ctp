<div class="configurations form">
    <?php echo $this->Form->create('Importtask'); ?>
    <h2><?php __('Add Import Task'); ?></h2>
    <ul class="fourStep" id="mainNav">
        <li class="current"><a title=""  class="linked" href="<?php echo $this->Html->url(array("action" => "edit", $this->Form->value('Importtask.id'))); ?>"><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Name"); ?><br /><?php __("Description"); ?></span></a></li>
        <li ><a title=""  ><em><?php __("Step 2: Connection to DB"); ?></em><span><?php __("Server Host and Port"); ?><br /><?php __("User/Password"); ?></span></a></li>
        <li><a title="" ><em><?php __("Step 3: Query"); ?></em><span><?php __("Enter SQL Query"); ?></span></a></li>

        <li class="mainNavNoBg"><a title="" ><em><?php __("Step 4: Settings"); ?></em><span><?php __("See Results"); ?><br /><?php __("Select Categories"); ?></span></a></li>
    </ul><hr />
    <?php
    echo $this->Form->input('name');
    echo $this->Form->input('description');
    echo $this->Form->end(__('Next', true)); ?>


</div>