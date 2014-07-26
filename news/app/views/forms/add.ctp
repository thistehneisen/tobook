<div class="forms form">
<?php echo $this->Form->create('Form');?>
  		<h2><?php __('Add Form'); ?></h2>
	<?php
		echo $this->Form->input('name');
	
	?>


                <?php echo  $this->Form->end(__('Save', true));?>


</div>