<div class="configurations form">
    <?php echo $this->Form->create('Configuration'); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Configuration.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Configuration.id'))); ?> 		<h2><?php __('Edit Configuration'); ?></h2>
    <?php
    echo $this->Form->input('id');
      __('Name', true);
    echo $this->Form->input('name');
    __('Description', true);
    echo $this->Form->input('description');
    __('From', true);
    echo $this->Form->input('from');
    ?>
    <div class="input comment">
        <?php __('You can set the display name by using this tag: Display Name &lt;email@domain.com&gt;'); ?>
    </div>
    <?php        __('Reply To', true);
        echo $this->Form->input('reply_to');
        __('Delivery', true);
        echo $this->Form->input('delivery');
    ?>
        <span style="display:none" id="delivery1"  class="deli">
            <h3><?php __('SMTP Settings'); ?></h3><hr /><?php
        __('Host', true);
        echo $this->Form->input('host');
    ?>
        <div class="input comment">
<?php __('For example ssl://smtp.gmail.com'); ?>
        </div>
<?php        __('Port', true);
        echo $this->Form->input('port');
        __('Smtp Auth', true);
        echo $this->Form->input('smtp_auth');
        __('Username', true);
        echo $this->Form->input('username');
        __('Password', true);
        echo $this->Form->input('password');
?>
    </span>
    <span style="display:none" id="delivery3" class="deli">
        <h3><?php __('Amazon Simple Email Service (Amazon SES)'); ?></h3><hr /><?php

            __('Aws Access Key', true);
            echo $this->Form->input('aws_access_key');
            __('Aws Secret Key', true);
            echo $this->Form->input('aws_secret_key');
            ?>
        </span>
        <span style="display:none" id="delivery4" class="deli">
        <h3><?php __('Sendmail'); ?></h3><hr /><?php

            __('Sendmail Path', true);
            echo $this->Form->input('sendmail_path');
               ?>
    <div class="input comment">
        <?php __('Most servers use: /usr/sbin/sendmail -bs'); ?>
    </div>
    <?php
            ?>
        </span><hr />
        <?php            __('Mails Per Connection', true);
            echo $this->Form->input('mails_per_connection');
            $this->Form->input('mails_per_time');
            $this->Form->input('time');
        ?><div class="input text required <?php if (isset($limiter)) {
        ?>error<?php } ?>"><label for="MailDelay"><?php __('Limits'); ?></label><input type="text" id="ConfigurationMailsPerTime" maxlength="11" style="width: 35px;" name="data[Configuration][mails_per_time]" value="<?php echo $this->Form->value('Configuration.mails_per_time'); ?>"> <?php __('mails per'); ?> <input type="text" id="Time" maxlength="11" style="width: 35px;" name="data[Configuration][time]" value="<?php echo $this->Form->value('Configuration.time'); ?>"> <?php __('minutes'); ?>
    <?php if (isset($limiter)) {
    ?><div class="error-message"><?php __('Please enter a number'); ?></div><?php } ?>
       </div>
       <h3><?php __('Inbox connection'); ?></h3><hr /><?php
            __('Inbox', true);
            echo $this->Form->input('inbox');
            __('Inbox Host', true);
            echo $this->Form->input('inbox_host');
            __('Inbox Port', true);
            echo $this->Form->input('inbox_port');
            __('Inbox Service', true);
            echo $this->Form->input('inbox_service', array("options" => array("imap" => "imap", "pop3" => "pop3", "nntp" => "nntp")));
            __('Inbox Settings', true);
            echo $this->Form->input('inbox_settings', array("escape" => false, 'multiple' => 'checkbox', "options" => array(
                    "norsh" => "<b>norsh</b> - " . __("do not use rsh or ssh to establish a preauthenticated IMAP session", true),
                    "ssl" => "<b>ssl</b> - " . __("use the Secure Socket Layer to encrypt the session", true),
                    "novalidate-cert" => "<b>novalidate-cert</b> - " . __("do not validate certificates from TLS/SSL server, needed if server uses self-signed certificates", true),
                    "tls" => "<b>tls</b> - " . __("force use of start-TLS to encrypt the session, and reject connection to servers that do not support it", true),
                    "notls" => "<b>notls</b> - " . __("do not do start-TLS to encrypt the session, even with servers that support it", true),
                    "readonly" => "<b>readonly</b> - " . __("request read-only mailbox open (IMAP only; ignored on NNTP, and an error POP3)", true)
                ), "div" => array("class" => "cbox cbox2")));
    ?><div style="clear:both"></div>
    <?php
            __('Mailbox', true);
            echo $this->Form->input('mailbox');
    ?>
            <div class="input comment">
    <?php __('Mostly empty'); ?>
            </div>
        <?php            __('Inbox Username', true);
            echo $this->Form->input('inbox_username');
            __('Inbox Password', true);
            echo $this->Form->input('inbox_password', array("type" => "password"));
            __('Inbox Wait', true);
            echo $this->Form->input('inbox_wait');
        ?>


<?php echo $this->Form->end(__('Save', true)); ?>


</div>
<script type="text/javascript">

    $(function() {
        $("#ConfigurationDelivery").change(function() {$(".deli").hide();$("#delivery"+$("#ConfigurationDelivery").val()).show();});
        $(".deli").hide();$("#delivery"+$("#ConfigurationDelivery").val()).show();
    });
</script>