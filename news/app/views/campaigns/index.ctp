<div class="importtask index">
    <?php echo $this->Html->link($this->Html->image("icons/chart-up--plus.png", array("alt" =>'conf')) . __('New Campaign', true), array('action' => 'add'), array("escape" => false, 'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); ?>
 <?php echo $this->Html->link($this->Html->image("icons/server-cast.png", array("alt" =>'conf')) .__('Run newsletter mailing job', true), array("controller"=>"mails",'action' => 'sendAll',1), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 5px;')); ?>
  
    <h2><?php __('Campaigns'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
 <th ><?php echo $this->Paginator->sort(__("Status",true),"active"); ?></th>
            <th ><?php echo $this->Paginator->sort('name'); ?></th>
         

            <th style="width:250px"><?php echo $this->Paginator->sort(__("Time span",true),'start'); ?></th>
            <th class="actions" style="width:65px;"><?php __('Actions'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($campaigns as $campaign):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?php echo $class; ?>>
                   <td style="width:63px"><span <?php
if ($campaign['Campaign']['active'] == 2) {
?>title="<?php echo strftime("%e %B %Y",  strtotime($campaign['Campaign']['start'])); ?>"<?php } ?>  class="tag<?php if ($campaign['Campaign']['active'] == 0) {
                echo 0;
            } else if ($campaign['Campaign']['active'] == 1) {

                echo 2;

            } else if ($campaign['Campaign']['active'] == 2) {
                echo 1;
            } ?>"><?php
            if ($campaign['Campaign']['active'] == 0) {
                echo __('Expired', true);
            } else if ($campaign['Campaign']['active'] == 1) {

                echo __('Active', true);

            } else if ($campaign['Campaign']['active'] == 2) {
                echo __('Scheduled', true);
            }
        ?></span>&nbsp;</td>

                <td><?php echo $campaign['Campaign']['name']; ?>&nbsp;</td>
              
                <td><?php echo strftime("%e %B %Y",  strtotime($campaign['Campaign']['start']));
                if($campaign['Campaign']["forever"]==0){
                    echo " <b>".__("to",true)."</b> ". strftime("%e %B %Y",  strtotime($campaign['Campaign']['end']));
                }else {
                      echo " <b>".__("to",true)."</b> ".__("an indefinite time",true);
                }
                ?>&nbsp;</td>

                <td >
                <?php echo $this->Html->link($this->Html->image("icons/mails-stack.png", array("alt" => __('View Newsletters', true))), array('controller'=>'mails','action' => 'campaign', $campaign['Campaign']['id']), array("escape" => false, "title" => __('View Newsletters', true) . "  &quot;" . htmlspecialchars($campaign['Campaign']['name']) . "&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/chart-up--pencil.png", array("alt" => __('Edit', true))), array('action' => 'edit', $campaign['Campaign']['id']), array("escape" => false, "title" => __('Edit', true) . "  &quot;" . htmlspecialchars($campaign['Campaign']['name']) . "&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/chart-up--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $campaign['Campaign']['id']), array("escape" => false, "title" => __('Delete', true) . "  &quot;" . htmlspecialchars($campaign['Campaign']['name']) . "&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $campaign['Campaign']['id'])); ?>
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
