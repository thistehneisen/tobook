<div class="users form">
    <?php echo $this->Form->create('User'); ?>
    <h2><?php __('Add User'); ?></h2>
    <?php
    __("Username", true);
    __("Password", true);
    __("Level", true);
    echo $this->Form->input('username');
    echo $this->Form->input('password');
    echo $this->Form->input('level', array("options" => array(0 => __("Superadmin (Can change configurations)",true), 1 => __("User",true))));
    ?>


    <?php echo $this->Form->end(__('Save', true)); ?>


</div>