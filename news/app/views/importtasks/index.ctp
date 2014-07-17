<div class="importtask index">
    <?php echo $this->Html->link($this->Html->image("icons/databases-relation--plus.png", array("alt" => __('conf', true))) .__('New Import Task', true), array('action' => 'add'), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); ?>
<?php echo $this->Html->link($this->Html->image("icons/arrow-circle-double.png", array("alt" => __('conf', true))) .__('Run All Tasks', true), array('action' => 'importjob',0,0,1), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 5px;')); ?>
    <h2><?php __('Import Tasks'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>

            <th ><?php echo $this->Paginator->sort('name'); ?></th>
            <th ><?php echo $this->Paginator->sort('description'); ?></th>


            <th class="actions" style="width:65px;"><?php __('Actions'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($importtasks as $importtask):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?php echo $class; ?>>
               <td><?php echo $importtask['Importtask']['name']; ?>&nbsp;</td>
                <td><?php echo $importtask['Importtask']['description']; ?>&nbsp;</td>


                <td >
                <?php echo $this->Html->link($this->Html->image("icons/databases-relation--pencil.png", array("alt" => __('Edit', true))), array('action' => 'edit3', $importtask['Importtask']['id']),array("escape"=>false,"title" => __('Edit', true)."  &quot;".htmlspecialchars($importtask['Importtask']['name'])."&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/databases-relation--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $importtask['Importtask']['id']), array("escape"=>false,"title" => __('Delete', true)."  &quot;".htmlspecialchars($importtask['Importtask']['name'])."&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $importtask['Importtask']['id'])); ?>
                <?php echo $this->Html->link($this->Html->image("icons/arrow-circle-double.png", array("alt" => __('Run Task', true) )), array('action' => 'importjob', $importtask['Importtask']['id']),array("escape"=>false,"title" => __('Run Task', true)."  &quot;".htmlspecialchars($importtask['Importtask']['name'])."&quot;")); ?>
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
        <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
                                	 | 	<?php echo $this->Paginator->numbers(); ?>
                |
        <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>
</div>
