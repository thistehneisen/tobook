<div class="configurations index">
    <?php
       if ($this->Session->read('Auth.User.level') == "0") {
    echo $this->Html->link($this->Html->image("icons/server--plus.png", array("alt" => __('conf', true))) .__('New Configuration', true), array('action' => 'add'), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 0px;')); 
  }
    echo $this->Html->link($this->Html->image("icons/inbox--exclamation.png", array("alt" => __('conf', true))) .__('Look for bounce mails on all servers', true), array('action' => 'checkAll',1), array("escape"=>false,'class' => 'button', 'style' => 'float: right; margin-top: -5px; margin-right: 5px;')); 
     
    ?>
    <h2><?php __('Configurations'); ?></h2>
    <table cellpadding="0" cellspacing="0">
        <tr>
            <th style="width:20px;text-align: center"><?php __("OUT"); ?></th>
             <th style="width:20px;text-align: center"><?php __("IN"); ?></th>
            <th ><?php echo $this->Paginator->sort('name'); ?></th>
            <th ><?php echo $this->Paginator->sort('description'); ?></th>


            <th class="actions" style="width:100px;"><?php __('Actions'); ?></th>
        </tr>
        <?php
        $i = 0;
        foreach ($configurations as $configuration):
            $class = null;
            if ($i++ % 2 == 0) {
                $class = ' class="altrow"';
            }
        ?>
            <tr<?php echo $class; ?>>
                
                <td><?php if( strtotime($configuration['Configuration']["free"]) - mktime()>0){ echo $this->Html->image("icons/clock.png", array("alt" => __('Wait', true),"title"=>  str_replace("%s", ($configuration['Configuration']['mails_per_time']-$configuration['Configuration']['mcount']), __("You can send %s Mails in the next",true))." ".$this->Time->relativeTime( $configuration['Configuration']['free']),"style"=>"margin-bottom: -4px; margin-left: 3px;"));}else{echo $this->Html->image("icons/server-cast.png", array("alt" => __('Ready', true),"style"=>"margin-bottom: -4px; margin-left: 3px;"));} ?></td>
                <td><?php if( strtotime($configuration['Configuration']["inbox_free"]) - mktime()>0){ echo $this->Html->image("icons/clock.png", array("alt" => __('Wait', true),"title"=>__("Wait",true)." ".$this->Time->relativeTime( $configuration['Configuration']['inbox_free']),"style"=>"margin-bottom: -4px; margin-left: 3px;"));}else{echo $this->Html->image("icons/server-cast.png", array("alt" => __('Ready', true),"style"=>"margin-bottom: -4px; margin-left: 3px;"));} ?></td>
                <td><?php echo $configuration['Configuration']['name']; ?>&nbsp;</td>
                <td><?php echo $configuration['Configuration']['description']; ?>&nbsp;</td>


                <td >
                <?php 
                if ($this->Session->read('Auth.User.level') == "0") {
                    echo $this->Html->link($this->Html->image("icons/server--pencil.png", array("alt" => __('Edit', true))), array('action' => 'edit', $configuration['Configuration']['id']),array("escape"=>false,"title" => __('Edit', true)."  &quot;".htmlspecialchars($configuration['Configuration']['name'])."&quot;")); 

                    echo $this->Html->link($this->Html->image("icons/server--minus.png", array("alt" => __('Delete', true))), array('action' => 'delete', $configuration['Configuration']['id']), array("escape"=>false,"title" => __('Delete', true)."  &quot;".htmlspecialchars($configuration['Configuration']['name'])."&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $configuration['Configuration']['id'])); 

                    echo $this->Html->link($this->Html->image("icons/lock-unlock.png", array("alt" => __('Unblock', true))), array('action' => 'unblock', $configuration['Configuration']['id']), array("escape"=>false,"title" => __('Unblock', true)."  &quot;".htmlspecialchars($configuration['Configuration']['name'])."&quot;"), sprintf(__('Are you sure you want to unblock # %s?', true), $configuration['Configuration']['id'])); 
                   }
               echo $this->Html->link($this->Html->image("icons/rocket-fly.png", array("alt" => __('Test', true))), array('action' => 'test', $configuration['Configuration']['id']),array("escape"=>false,"title" => __('Test settings', true)."  &quot;".htmlspecialchars($configuration['Configuration']['name'])."&quot;")); ?>
                      <?php echo $this->Html->link($this->Html->image("icons/inbox--exclamation.png", array("alt" => __('Check bounce', true) )), array('action' => 'check', $configuration['Configuration']['id']),array("escape"=>false,"title" => __('Look for bounce mails on', true)."  &quot;".htmlspecialchars($configuration['Configuration']['name'])."&quot;")); ?>
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
