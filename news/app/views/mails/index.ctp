<p class="rss"><?php echo str_replace("%link%", $this->Html->link(__("Link",true),array("action"=>"feed")) , __("Sent Newsletters RSS Feed (%link%). You can edit the rss chanel in the application settings.",true)); ?></p>
<div class="mails index">
    <?php echo $this->Html->link($this->Html->image("icons/mail--plus.png", array("alt" => __('Add', true))) . __('Create new newsletter', true), array('action' => 'add'), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); ?>
    <?php if(Configure::read('Settings.parallel_jobs') != '1') echo $this->Html->link($this->Html->image("icons/server-cast.png", array("alt" => __('conf', true))) .__('Run newsletter mailing job', true), array('action' => 'sendAll',1), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 5px;')); ?>
    <h2><?php __('Newsletters'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th style="width:63px"><?php echo $this->Paginator->sort('status'); ?></th>
            <th ><?php echo $this->Paginator->sort('subject'); ?></th>

            <th style="width:160px"><?php echo $this->Paginator->sort('modified'); ?></th>



            <th class="actions" style="width:80px"><?php __('Actions'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($mails as $mail):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
            $icon = "information-white.png";
            $tex=__('Info', true);
            if ($mail['Mail']['status'] == 0) {
                $tex=__('Edit', true);
                $icon = "mail--pencil.png";
            }
            
        ?>
            <tr<?php echo $class; ?>>
                <td><span <?php
if(strtotime($mail['Mail']["send_on"]) - mktime()>0){
?>title="<?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($mail['Mail']['send_on']))); ?>"<?php } ?> class="tag<?php echo $mail['Mail']['status'] + 0; ?>"><?php
            if ($mail['Mail']['status'] == 0) {
                echo __('Draft', true);
            } else if ($mail['Mail']['status'] == 1) {
               if(strtotime($mail['Mail']["send_on"]) - mktime()>0){
                echo __('Scheduled', true);
               }else{
                      echo __('Sending', true);
               }
            } else if ($mail['Mail']['status'] == 2) {
                echo __('Sent', true);
            }else if ($mail['Mail']['status'] == 3) {
                echo __('Stopped', true);
            }
        ?></span>&nbsp;</td>
            <td><?php echo $mail['Mail']['subject']; ?>&nbsp;</td>

            <td><?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($mail['Mail']['modified']))); ?>&nbsp;</td>





            <td  >
 <?php echo $this->Html->link($this->Html->image("icons/blog-blue.png", array("alt" => __('Preview', true))), array('action' => 'preview', $mail['Mail']['id']), array("class" => "modal", "escape" => false,"title" => __('Preview', true)." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/" . $icon, array("alt" => __('Edit', true))), array('action' => 'step', $mail['Mail']['id'], 1), array("escape" => false,"title" => $tex." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;")); ?>
                                <?php echo $this->Html->link($this->Html->image("icons/document-copy.png", array("alt" => __('Duplicate', true))), array('action' => 'duplicate', $mail['Mail']['id'], 1), array("escape" => false,"title" => __('Duplicate', true)." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;"), sprintf(__('Are you sure you want to duplicate # %s?', true), $mail['Mail']['id'])); ?>
                <?php echo $this->Html->link($this->Html->image("icons/mail--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $mail['Mail']['id']), array("escape" => false,"title" => __('Delete', true)." &quot;".htmlspecialchars($mail['Mail']['subject'])."&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $mail['Mail']['id'])); ?>
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
