<div class="users form">
<?php echo $this->Form->create('User');?>
 		 <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('User.id')), array('class'=>'button','style'=>'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('User.id'))); ?> 		<h2><?php __('Edit User'); ?></h2>
	<?php
		echo $this->Form->input('id');
		echo $this->Form->input('username');
                echo $this->Form->input('level', array("options" => array(0 => __("Superadmin (Can change configurations)",true), 1 => __("User",true))));
    
?>  <a href="#" class="button" style="margin-left: 150px; padding: 2px 8px;" id="cp" onclick="$('#cp').after($('#pass').html());$('#pass').html('');$('#cp').hide();return false;"><?php __("Change Password"); ?></a>
 


                <?php 
                
                echo  $this->Form->end(__('Save', true));?>


</div><div id="pass" style="display: none;">
<?php
        $this->Form->create('User');
        echo $this->Form->input('password');
?>
</div>