<h2 style="margin-top: 10px; margin-bottom: 15px;"><?php __("Common Tasks"); ?></h2>
<?php echo $this->Html->link($this->Html->image("icons/mail--plus.png", array("alt" => __('Add', true))) . __('Create a new newsletter', true), array("controller" => "mails", 'action' => 'add'), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: 0px; margin-left: 5px; margin-bottom: 5px;')); ?>
<?php if(Configure::read('Settings.parallel_jobs') != '1') echo $this->Html->link($this->Html->image("icons/server-cast.png", array("alt" => __('conf', true))) . __('Run newsletter mailing job', true), array("controller" => "mails", 'action' => 'sendAll', 1), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: 0px; margin-left: 5px; margin-bottom: 5px;'));
echo $this->Html->link($this->Html->image("icons/inbox--exclamation.png", array("alt" => __('conf', true))) . __('Look for bounce mails', true), array("controller" => "configurations", 'action' => 'checkAll', 1), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: 0px; margin-left: 5px; margin-bottom: 5px;')); ?>
<?php echo $this->Html->link($this->Html->image("icons/arrow-circle-double.png", array("alt" => __('conf', true))) . __('Run all import tasks', true), array("controller" => "importtasks", 'action' => 'importjob', 0, 0, 1), array("escape" => false, 'class' => 'button', 'style' => 'float: left; margin-top: 0px; margin-left: 5px; margin-bottom: 5px;')); ?>

<div style="clear:both;"></div>
<div>
    <h2><?php __("Last 5 Newsletter"); ?></h2>

    <table cellpadding="0" cellspacing="0">
        <tr>
            <th style="width:63px"><?php __("Status"); ?></th>
            <th ><?php __("Subject"); ?></th>

            <th style="width:160px"><?php __("Modified"); ?></th>



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
	        $tex = __('Info', true);
	        if ($mail['Mail']['status'] == 0) {
		        $tex = __('Edit', true);
		        $icon = "mail--pencil.png";
	        }
            ?>
            <tr<?php echo $class; ?>>
                <td><span class="tag<?php echo $mail['Mail']['status'] + 0; ?>"><?php
        if ($mail['Mail']['status'] == 0) {
            echo __('Draft', true);
        } else if ($mail['Mail']['status'] == 1) {
            if (strtotime($mail['Mail']["send_on"]) - mktime() > 0) {
                echo __('Scheduled', true);
            } else {
                echo __('Sending', true);
            }
        } else if ($mail['Mail']['status'] == 2) {
            echo __('Sent', true);
        } else if ($mail['Mail']['status'] == 3) {
            echo __('Stopped', true);
        }
            ?></span>&nbsp;</td>
                <td><?php echo $mail['Mail']['subject']; ?>&nbsp;</td>

                <td><?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($mail['Mail']['modified']))); ?>&nbsp;</td>





                <td  >
                    <?php echo $this->Html->link($this->Html->image("icons/blog-blue.png", array("alt" => __('Preview', true))), array("controller" => "mails", 'action' => 'preview', $mail['Mail']['id']), array("class" => "modal", "escape" => false, "title" => __('Preview', true) . " &quot;" . htmlspecialchars($mail['Mail']['subject']) . "&quot;")); ?>
                    <?php echo $this->Html->link($this->Html->image("icons/" . $icon, array("alt" => $tex)), array("controller" => "mails", 'action' => 'step', $mail['Mail']['id'], 1), array("escape" => false, "title" => $tex . " &quot;" . htmlspecialchars($mail['Mail']['subject']) . "&quot;")); ?>
                    <?php echo $this->Html->link($this->Html->image("icons/document-copy.png", array("alt" => __('Duplicate', true))), array("controller" => "mails", 'action' => 'duplicate', $mail['Mail']['id'], 1), array("escape" => false, "title" => __('Duplicate', true) . " &quot;" . htmlspecialchars($mail['Mail']['subject']) . "&quot;"), sprintf(__('Are you sure you want to duplicate # %s?', true), $mail['Mail']['id'])); ?>
                    <?php echo $this->Html->link($this->Html->image("icons/mail--minus.png", array("alt" => __('Delete', true))), array("controller" => "mails", 'action' => 'delete', $mail['Mail']['id']), array("escape" => false, "title" => __('Delete', true) . " &quot;" . htmlspecialchars($mail['Mail']['subject']) . "&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $mail['Mail']['id'])); ?>
                </td>
            </tr>
            <?php
        endforeach;
        $class = null;
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
        }
        ?>
        <tr  style="height:33px" <?php echo $class; ?>>

            <td colspan="4"><a class="button" style="padding: 2px 8px; top: 4px;" href="<?php echo $this->Html->url(array("controller" => "mails", 'action' => 'index')); ?>"><?php __("See all newsletter"); ?></a></td></tr>
    </table>
</div>


<h2><?php __("Last 5 Subscribers"); ?></h2>
<table cellpadding="0" cellspacing="0">
    <tr>

        <th ><?php __("First Name"); ?></th>
        <th ><?php __("Last Name"); ?></th>
        <th ><?php __("Mail Address"); ?></th>

        <th ><?php __("Subscribed"); ?></th>

        <th class="actions" style="width:60px;"><?php __('Actions'); ?></th>
    </tr>
    <?php
    $i = 0;
    foreach ($subscribers as $subscriber):
        $class = null;
        if ($subscriber['Subscriber']['deleted'] == 1) {
            $class = ' class="rowdel"';
        }
        if ($i++ % 2 == 0) {
            $class = ' class="altrow"';
            if ($subscriber['Subscriber']['deleted'] == 1) {
                $class = ' class="altrowdel"';
            }
        }
        ?>
        <tr<?php echo $class; ?>>

            <td><?php echo $subscriber['Subscriber']['first_name']; ?>&nbsp;</td>
            <td><?php echo $subscriber['Subscriber']['last_name']; ?>&nbsp;</td>
            <td><?php echo $subscriber['Subscriber']['mail_adresse']; ?>&nbsp;</td>

            <td style="width:150px"><?php echo toUTF8(strftime("%e %B %Y, %H:%M",  strtotime($subscriber['Subscriber']['created']))); ?>&nbsp;</td>

            <td>
                <?php echo $this->Html->link($this->Html->image("icons/information-white.png", array("alt" => __('View', true))), array("controller" => "subscribers", 'action' => 'view', $subscriber['Subscriber']['id']), array("escape" => false, "title" => __('View', true) . "  &quot;" . htmlspecialchars($subscriber['Subscriber']['mail_adresse']) . "&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/xfn--pencil.png", array("alt" => __('Edit', true))), array("controller" => "subscribers", 'action' => 'edit', $subscriber['Subscriber']['id']), array("escape" => false, "title" => __('Edit', true) . "  &quot;" . htmlspecialchars($subscriber['Subscriber']['mail_adresse']) . "&quot;")); ?>
                <?php echo $this->Html->link($this->Html->image("icons/xfn--minus.png", array("alt" => __('Delete', true))), array("controller" => "subscribers", 'action' => 'delete', $subscriber['Subscriber']['id']), array("escape" => false, "title" => __('Delete', true) . "  &quot;" . htmlspecialchars($subscriber['Subscriber']['mail_adresse']) . "&quot;"), sprintf(__('Are you sure you want to delete # %s?', true), $subscriber['Subscriber']['id'])); ?>
            </td>
        </tr>
        <?php
    endforeach;
    $class = null;
    if ($i++ % 2 == 0) {
        $class = ' class="altrow"';
    }
    ?>
    <tr style="height:33px"<?php echo $class; ?>>

        <td colspan="5"><a class="button" style="padding: 2px 8px; top: 4px;" href="<?php echo $this->Html->url(array("controller" => "subscribers", 'action' => 'index')); ?>"><?php __("See all subscribers"); ?></a></td></tr>

</table>
<?php if ($this->Session->read('Auth.User.level') == "0" ||Configure::read('Settings.cron_show') == "1") { ?>
    <h2><?php __("Cron Jobs"); ?></h2>
    <?php __("Bounce mail check command:"); ?> 
    <code>wget -O - -q -t 1 <?php echo $this->Html->url(array("controller" => "configurations", 'action' => 'checkAll'), true); ?></code><br />
	<?php if(Configure::read('Settings.parallel_jobs') != '1'){ ?>
    <?php __("Send mails command:"); ?>
    <code>wget -O - -q -t 1 <?php echo $this->Html->url(array("controller" => "mails", 'action' => 'sendAll'), true); ?></code><br />
	<?php }else{
		foreach(range(1, Configure::read('Settings.parallel_jobs_count')) as $i){
		?>
	<?php __("Send Job #"); echo "$i: "; ?>
	<code>wget -O - -q -t 1 <?php echo $this->Html->url(array("controller" => "mails", 'action' => 'parallelSend',$i, Configure::read('Settings.parallel_jobs_count')), true); ?></code><br />
	<?php }
	}
		?>
    <?php __("Clean up failed subscribers:"); ?>
    <code>wget -O - -q -t 1 <?php echo $this->Html->url(array("controller" => "subscribers", 'action' => 'cronclean'), true); ?></code><br />
    <?php __("Clean up inactive subscribers:"); ?>
    <code>wget -O - -q -t 1 <?php echo $this->Html->url(array("controller" => "subscribers", 'action' => 'croninactive'), true); ?></code><br />
    <?php
}
  if ($this->Session->read('Auth.User.level') == "0" ||Configure::read('Settings.api_show') == "1") { ?>
<h2><?php __("Api"); ?></h2>
<?php __("API Key:"); ?>&nbsp;

<code><?php
if (!file_exists(CONFIGS . 'api.yml')) {
    file_put_contents(CONFIGS . 'api.yml', substr(MD5(rand(0, 1000000000000) . rand(0, 1000000000000)), 7, 12));
}

echo md5(file_get_contents(CONFIGS . 'code.yml') . "-" . file_get_contents(CONFIGS . 'api.yml'));
?></code><br />
<a href="#" onclick="$('#samplecode').fadeIn();$(this).hide();return false;"><?php __("Show code"); ?></a> <a href="<?php echo $html->url("/"); ?>files/API.zip" ><?php __("Download API"); ?></a>
<div id="samplecode" style="display:none"><br />
    <?php __("Sample Code:"); ?>&nbsp;<br />
    <pre>
require_once 'NewsletterMailerAPI.php';
$nlapi = new NewsletterMailerAPI("<?php echo $this->Html->url("/", true); ?>", "<?php echo md5(file_get_contents(CONFIGS . 'code.yml') . "-" . file_get_contents(CONFIGS . 'api.yml')); ?>");

//Get existing Categories
$categories = $nlapi->getCategories();
//Get existing Forms
$forms=$nlapi->getForms();
//Get more informations about Form #4
$infos=$nlapi->getForm(4);
//Get confirm message 
$message=$nlapi->getFormMessage(4);

//add new subscribers
$data = array("e-mail" => "ynhwebdev@gmail.com",
    "first_name" => "asdasd",   "last_name" => "asdasd",
    "Category" => array(5)
);

//check if subscriber exists
if (!$nlapi->checkSubscriber($data["e-mail"])) {
    //Add subscriber using Form #4
    $return = $nlapi->formAddSubscriber(4,$data);
    //Add subscriber directly
    //$return = $nlapi->addSubscriber($data);

    if ($return["status"] == "ok") {
        echo "Subscriber added";
    } else {
        echo "Add Subscriber Failed:" . $return["msg"];
    }
}else{
    echo "Subscriber exists";
} 
    </pre></div><?php } ?>