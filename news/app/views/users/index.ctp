<div class="users index">
    <?php echo $this->Html->link($this->Html->image("icons/user--plus.png", array("alt" => __('Add', true))) .__('New User', true), array('action' => 'add'),array("escape"=>false,'class'=>'button','style'=>'float: right; margin-top: -5px; margin-right: 0px;')); ?>    <h2><?php __('Users');?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
                            <th  style="width:35px;"><?php echo $this->Paginator->sort('id');?></th>
                            <th ><?php echo $this->Paginator->sort('username');?></th>
                               <th ><?php echo $this->Paginator->sort('level');?></th>
                            <th  style="width:165px;"><?php
                             __("Last Login",true);
                            echo $this->Paginator->sort('last_login');?></th>
  
                            <th class="actions" style="width:40px;"><?php __('Actions');?></th>
            </tr>
        <?php
	$i = 0;
        $levels=array(0 => __("Superadmin (Can change configurations)",true), 1 => __("User",true));
	foreach ($users as $user):
		$class = null;
		if ($i++ % 2 == 0) {
			$class = ' class="altrow"';
		}
	?>
	<tr<?php echo $class;?>>
		<td><?php echo $user['User']['id']; ?>&nbsp;</td>
		<td><?php echo $user['User']['username']; ?>&nbsp;</td>
 <td><?php echo $levels[$user['User']['level']]; ?>&nbsp;</td>
		<td><?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($user['User']['last_login']))); ?>&nbsp;</td>

		<td>

			<?php echo $this->Html->link($this->Html->image("icons/user--pencil.png", array("alt" => __('Edit', true))), array('action' => 'edit', $user['User']['id']),array("escape"=>false,"title" => __('Edit', true)."  &quot;".htmlspecialchars($user['User']['username'])."&quot;")); ?>
			<?php echo $this->Html->link($this->Html->image("icons/user--minus.png", array("alt" => __('Delete', true))),  array('action' => 'delete', $user['User']['id']), array("escape"=>false,"title" => __('Delete', true)."  &quot;".htmlspecialchars($user['User']['username'])."&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $user['User']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>
            </table>
            <p>
        <?php
	echo $this->Paginator->counter(array(
	'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
	));
	?>            </p>

            <div class="paging">
        	<?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class'=>'disabled'));?>
                        	 | 	<?php echo $this->Paginator->numbers();?>
 |
        	<?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled'));?>
    </div>
</div>
