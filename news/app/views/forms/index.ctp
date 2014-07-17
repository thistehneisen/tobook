<div class="forms index">
    <?php echo $this->Html->link($this->Html->image("icons/application-form--plus.png", array("alt" => __('Add', true))) .__('New Form', true), array('action' => 'add'), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); ?>    <h2><?php __('Forms'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th  style="width:35px;"><?php echo $this->Paginator->sort('id'); ?></th>
            <th ><?php echo $this->Paginator->sort('name'); ?></th>

            <th class="actions" style="width: 114px;"><?php __('Actions'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($forms as $form):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?php echo $class; ?>>
                <td><?php echo $form['Form']['id']; ?>&nbsp;</td>
                <td><?php echo $form['Form']['name']; ?>&nbsp;</td>

                <td class="actions">
                <?php echo $this->Html->link($this->Html->image("icons/document-copy.png", array("alt" => __('Copy link', true))), "#inline" . $form['Form']['id'], array("class" => "moda", "escape" => false, "title" => __('Copy link', true) . " &quot;" . htmlspecialchars($form['Form']['name']) . "&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/application-form.png", array("alt" => __('Preview form', true))), array('action' => 'view', $form['Form']['id']), array("class" => "modal", "escape" => false, "title" => __('Preview form', true) . " &quot;" . htmlspecialchars($form['Form']['name']) . "&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/application-form--info.png", array("alt" => __('Preview unsubscribe', true))), array('action' => 'unsubscribe', $form['Form']['id']), array("class" => "modal", "escape" => false, "title" => __('Preview unsubscribe', true) . " &quot;" . htmlspecialchars($form['Form']['name']) . "&quot;")); ?>
                                    <?php echo $this->Html->link($this->Html->image("icons/application-form--pencil.png", array("alt" => __('Edit form', true))), array('action' => 'edit', $form['Form']['id']), array( "escape" => false, "title" => __('Edit form', true) . " &quot;" . htmlspecialchars($form['Form']['name']) . "&quot;")); ?>
                             <?php echo $this->Html->link($this->Html->image("icons/mail--pencil.png", array("alt" => __('Edit mails', true))), array('action' => 'editm', $form['Form']['id']), array( "escape" => false, "title" => __('Edit mails', true) . " &quot;" . htmlspecialchars($form['Form']['name']) . "&quot;")); ?>
                 <?php echo $this->Html->link($this->Html->image("icons/application-form--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $form['Form']['id']), array("escape" => false,"title" => __('Delete', true)." &quot;".htmlspecialchars($form['Form']['name'])."&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $form['Form']['id'])); ?>
         
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
        </div><div class="hidden">
    <?php
                $i = 0;
                foreach ($forms as $form):
    ?>

                    <div  id="inline<?php echo $form['Form']['id']; ?>">
                        <div class="input text" style="padding: 3px ;">
                            <label><?php __('Standalon form'); ?></label>
                            <input onclick="this.focus(); this.select();" style="width: 400px;" type="text" value="<?php echo $this->Html->url(array('action' => 'view', $form['Form']['id']), true); ?>">
                        </div>
                        <div class="input text"  style="padding: 3px;">
                            <label><?php __('Inframe form'); ?></label>
                            <input onclick="this.focus(); this.select();" style="width: 400px;" type="text" value="<?php echo htmlspecialchars('<iframe src="' . $this->Html->url(array('action' => 'view', $form['Form']['id'], 0), true) . '" height="500px" allowtransparency="true" frameborder="0" border="0" style="border:0 none;" width="100%" scrolling="auto"></iframe>'); ?>">
                        </div>
                    </div>
    <?php endforeach; ?>
</div>
<script type="text/javascript">
    $(document).ready(function() {

        $(".moda").fancybox({
 
        });
    });
</script>