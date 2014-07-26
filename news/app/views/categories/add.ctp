<div class="categories form">
<?php echo $this->Form->create('Category');?>
  		<h2><?php __('Add Category'); ?></h2>
	<?php
		echo $this->Form->input('name');
		echo $this->Form->input('description');
 
	?>


                <?php echo  $this->Form->end(__('Save', true));?>


</div>