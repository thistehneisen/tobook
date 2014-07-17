<div class="templates index">
    <?php echo $this->Html->link($this->Html->image("icons/box.png", array("alt" => __('Import', true))) . __('Import Template', true), array('action' => 'import'), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px;')); ?>
   <?php echo $this->Html->link($this->Html->image("icons/blog--plus.png", array("alt" => __('Add', true))) . __('New Template', true), array('action' => 'add'), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px;')); ?>    <h2><?php __('Templates'); ?></h2>
    <?php
    foreach ($templates as $template) {
    ?>
        <div  class="template_box"  >
        <?php

                echo '<img alt="'.$template['Template']['name'].'" style="margin-left:25px;margin-right:25px;" src="'.htmlspecialchars($html->url("/") . 'thumb.php?src=' . $template['Template']['preview'] . '&w=150&h=112').'" />';
        $first = false;
        ?><br />
        <p style="text-align:center;margin-bottom: 0;font-size: 14px;"><b ><?php echo $template['Template']['name']; ?></b><br />
            <?php echo $this->Html->link($this->Html->image("icons/image-import.png", array("alt" => __('images', true))), array('action' => 'images', $template['Template']['id']), array("escape" => false,"title" => __('Upload missing image for', true)."  &quot;".htmlspecialchars($template['Template']['name'])."&quot;")); ?>
            <?php echo $this->Html->link($this->Html->image("icons/blog-blue.png", array("alt" => __('Preview', true))), array('controller'=>"mails",'action' => 'preview', $template['Template']['id']*-1), array("class" => "modal", "escape" => false,"title" => __('Preview', true)."  &quot;".htmlspecialchars($template['Template']['name'])."&quot;")); ?>

            <?php echo $this->Html->link($this->Html->image("icons/blog--pencil.png", array("alt" => __('Edit', true))), array('action' => 'edit', $template['Template']['id']), array("escape" => false,"title" => __('Edit', true)."  &quot;".htmlspecialchars($template['Template']['name'])."&quot;")); ?>
            <?php echo $this->Html->link($this->Html->image("icons/blog--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $template['Template']['id']), array("escape" => false,"title" => __('Delete', true)."  &quot;".htmlspecialchars($template['Template']['name'])."&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $template['Template']['id'])); ?>
          <?php echo $this->Html->link($this->Html->image("icons/box--arrow.png", array("alt" => __('export', true))), array('action' => 'export', $template['Template']['id']), array("escape" => false,"title" => __('Export Template ', true)."  &quot;".htmlspecialchars($template['Template']['name'])."&quot;")); ?>

        </p>
    </div>

    <?php        }
    ?>
    <div style="clear:both;"></div>

    <?php
        echo $this->Paginator->counter(array(
            'format' => __('Page %page% of %pages%, showing %current% records out of %count% total, starting on record %start%, ending on %end%', true)
        ));
    ?>

        <div class="paging">
        <?php echo $this->Paginator->prev('<< ' . __('previous', true), array(), null, array('class' => 'disabled')); ?>
                                                                	 | 	<?php echo $this->Paginator->numbers(); ?>
        |
        <?php echo $this->Paginator->next(__('next', true) . ' >>', array(), null, array('class' => 'disabled')); ?>
    </div>
</div>
