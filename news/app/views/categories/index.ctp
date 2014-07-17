<div class="categories index">
    <?php echo $this->Html->link($this->Html->image("icons/address-book--plus.png", array("alt" => __('Add', true))) . __('New Category', true), array('action' => 'add'), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px;margin-right:0px;')); ?>    <h2><?php __('Categories'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th  style="width:35px;"><?php echo $this->Paginator->sort('id'); ?></th>
            <th ><?php echo $this->Paginator->sort('name'); ?></th>
            <th ><?php echo $this->Paginator->sort('description'); ?></th>

            <th class="actions" style="width:75px"><?php __('Actions'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($categories as $category):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
            ?>
            <tr<?php echo $class; ?>>
                <td><?php echo $category['Category']['id']; ?>&nbsp;</td>
                <td><?php echo $category['Category']['fullname']; ?>&nbsp;</td>
                <td><?php echo $category['Category']['description']; ?>&nbsp;</td>

                <td>
                    <?php echo $this->Html->link($this->Html->image("icons/contrast.png", array("alt" => __('Split in to A/B Category', true))), array("controller" => "categories", 'action' => 'split', $category['Category']['id']), array("title" => __('Split in to A/B Category', true), "escape" => false)); ?>

                    <?php echo $this->Html->link($this->Html->image("icons/xfn-colleague.png", array("alt" => __('View', true))), array("controller" => "subscribers", 'action' => 'index', $category['Category']['id']), array("title" => __('List subscribers in', true)." &quot;" . htmlspecialchars($category['Category']['name']) . "&quot;", "escape" => false)); ?>

                    <?php echo $this->Html->link($this->Html->image("icons/address-book--pencil.png", array("alt" => __('Edit', true))), array('action' => 'edit', $category['Category']['id']), array("title" => __('Edit category', true)." &quot;" . htmlspecialchars($category['Category']['name']) . "&quot;", "escape" => false)); ?>
                    <?php echo $this->Html->link($this->Html->image("icons/address-book--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $category['Category']['id']), array("title" => __('Delete category', true)." &quot;" . htmlspecialchars($category['Category']['name']) . "&quot;", "escape" => false), sprintf(__('Are you sure you want to delete # %s?', true), $category['Category']['id'])); ?>
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
