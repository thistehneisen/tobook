<div class="subscribers form">
    <?php echo $this->Form->create('Subscriber'); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Subscriber.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Subscriber.id'))); ?> 		<h2><?php __('Edit Subscriber'); ?></h2>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('first_name');
    echo $this->Form->input('last_name');
    echo $this->Form->input('mail_adresse', array("label" => __("Mail Addresse", true)));
    if (Configure::read('Settings.custom1_show') == "1") {
        echo $this->Form->input('custom1', array("label" => Configure::read('Settings.custom1_label')));
    }
    if (Configure::read('Settings.custom2_show') == "1") {
        echo $this->Form->input('custom2', array("label" => Configure::read('Settings.custom2_label')));
    }
    if (Configure::read('Settings.custom3_show') == "1") {
        echo $this->Form->input('custom3', array("label" => Configure::read('Settings.custom3_label')));
    }
    if (Configure::read('Settings.custom4_show') == "1") {
        echo $this->Form->input('custom4', array("label" => Configure::read('Settings.custom4_label')));
    }
    echo $this->Form->input('notes');
    echo $this->Form->input('deleted', array("label" => __("Unsubscribed", true)));

    echo $this->Form->input('Category', array('multiple' => 'checkbox', "div" => array("class" => "cbox")));
    ?>


    <?php echo $this->Form->end(__('Save', true)); ?>


</div>