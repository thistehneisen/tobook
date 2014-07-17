<div class="subscribers view">
    <?php
    if($subscriber['Subscriber']['deleted']==1){
        ?>
<div class="notice" id="flashMessage"><?php echo $this->Html->image("icons/exclamation-diamond.png", array("alt" => __('cat', true))); ?> <?php __("This user unsubscribed your newsletter."); ?></div>
<?php }
?>
    <h2><?php __('Subscriber'); ?></h2>
    <dl><?php $i = 0;
$class = ' class="altrow"'; ?>
        <dt<?php if ($i % 2 == 0)
            echo $class; ?>><?php __('Id'); ?></dt>
        <dd<?php if ($i++ % 2 == 0)
                echo $class; ?>>
                <?php echo $subscriber['Subscriber']['id']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php __('First Name'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['first_name']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php __('Last Name'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['last_name']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php __('Mail Address'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['mail_adresse']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php __('Notes'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['notes']; ?>
            &nbsp;
        </dd>
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php __('Subscribed'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($subscriber['Subscriber']['created']))); ?>
            &nbsp;
        </dd>

        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php __('Unsubscribe Code'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['unsubscribe_code']; ?>
            &nbsp;
        </dd>

<?php
        if (Configure::read('Settings.custom1_show') == "1") {
 ?>
   
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php echo Configure::read('Settings.custom1_label'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['custom1']; ?>
            &nbsp;
        </dd>
      <?php   }

        if (Configure::read('Settings.custom2_show') == "1") {
 ?>
   
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php echo Configure::read('Settings.custom2_label'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['custom2']; ?>
            &nbsp;
        </dd>
      <?php   }

        if (Configure::read('Settings.custom3_show') == "1") {
 ?>
   
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php echo Configure::read('Settings.custom3_label'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['custom3']; ?>
            &nbsp;
        </dd>
      <?php   }

        if (Configure::read('Settings.custom4_show') == "1") {
 ?>
   
        <dt<?php if ($i % 2 == 0)
                    echo $class; ?>><?php echo Configure::read('Settings.custom4_label'); ?></dt>
            <dd<?php if ($i++ % 2 == 0)
                    echo $class; ?>>
                <?php echo $subscriber['Subscriber']['custom4']; ?>
            &nbsp;
        </dd>
      <?php   }
           
 ?>
        
    </dl>
</div>
<h3><?php __('Subscribed Categories'); ?></h3><br />
<?php
                if (!empty($subscriber['Category'])) {
                    foreach ($subscriber['Category'] as $value) {


                        echo $this->Html->link($this->Html->image("icons/xfn-colleague.png", array("alt" => __('cat', true))) . $value["fullname"], array('action' => 'index', $value["id"]), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: -5px;'));
                    }
                }
?><div style="clear:both">&nbsp;</div>
                <div class="related"> <?php if (!empty($subscriber['RecivedMails'])): ?>
                        <h3><?php __('Sent Newsletters'); ?></h3>

                        <table cellpadding = "0" cellspacing = "0">
                            <tr>
                                <th style="width:65px"></th>
                                <th><?php __('Subject'); ?></th>

                                <th  style="width:140px"><?php __('Sent'); ?></th>
                                <th  style="width:140px"><?php __('Read'); ?></th>
                                     <th  style="width:85px"><?php __('Open Count'); ?></th>
                                <th class="actions" style="width:60px"><?php __('Actions'); ?></th>
                            </tr>
        <?php
                    $i = 0;
                    foreach ($subscriber['RecivedMails'] as $mail):
                        $class = null;
                        if ($i++ % 2 == 0) {
                            $class = ' class="altrow"';
                        }
        ?>
                        <tr<?php echo $class; ?>>
                            <td><span class="tag<?php echo $mail['read'] + 1; ?>"><?php
                        if ($mail['read'] == 0) {
                            echo __('Sent', true);
                        } else if ($mail['read'] == 1) {
                            echo __('Read', true);
                        }
        ?></span>&nbsp;</td>
                <td><?php echo $mail["Mail"]['subject']; ?></td>

                <td><?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($mail['send_date']))); ?></td>
                <td><?php
                        if ($mail['read'] == 1) {
                            echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($mail['read_date'])));
                        } else {
                            echo "-";
                        }
        ?></td><td><?php echo $mail['open_count']; ?></td>
                    <td  ><?php echo $this->Html->link($this->Html->image("icons/mail-open-document-text.png", array("alt" => __('Preview', true))), "/show/" . $mail["Mail"]["id"] . "/" . $subscriber['Subscriber']['id'] . "-" . $subscriber['Subscriber']['unsubscribe_code'] . "/n", array("class" => "modal", "escape" => false)); ?>

                            </td>
                        </tr>
<?php endforeach; ?>
                        </table>
<?php endif; ?>

                    </div>
                    <div class="related">    <?php if (!empty($subscriber['FailedMails'])): ?>
                            <h3><?php __('Failured Newsletters'); ?></h3>

                            <table cellpadding = "0" cellspacing = "0">
                                <tr>
                                    <th style="width:60px"></th>
                                    <th><?php __('Subject'); ?></th>

                                    <th  style="width:140px"><?php __('Sent'); ?></th>
                      
                                <th class="actions" style="width:60px"><?php __('Actions'); ?></th>
                            </tr>
        <?php
                            $i = 0;
                            foreach ($subscriber['FailedMails'] as $mail):
                                $class = null;
                                if ($i++ % 2 == 0) {
                                    $class = ' class="altrow"';
                                }
        ?>
                                <tr<?php echo $class; ?>>
                                    <td><span class="tag0">Failed</span>&nbsp;</td>
                                    <td><?php echo $mail["Mail"]['subject']; ?></td>

                                    <td><?php if (!empty ($mail['send_date'])) {echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($mail['send_date']))); }else{ echo "-"; } ?></td>
                      
                                        <td  ><?php echo $this->Html->link($this->Html->image("icons/mail-open-document-text.png", array("alt" => __('Preview', true))), "/show/" . $mail["Mail"]["id"] . "/" . $subscriber['Subscriber']['id'] . "-" . $subscriber['Subscriber']['unsubscribe_code'] . "/n", array("class" => "modal", "escape" => false)); ?>

                                        </td>
                                    </tr>
<?php endforeach; ?>
                                </table>
<?php endif; ?>

</div>
