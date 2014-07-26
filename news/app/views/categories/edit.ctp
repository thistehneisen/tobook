<div class="categories form">
    <?php echo $this->Form->create('Category'); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->data["Category"]["id"]), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true),  $this->data["Category"]["id"])); ?> 		<h2><?php __('Edit Category'); ?></h2>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    echo $this->Form->input('description');
    ?>


    <?php echo $this->Form->end(__('Save', true)); ?>


</div>