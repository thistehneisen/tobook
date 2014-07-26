<div class="forms form">
    <?php echo $this->Form->create('Form'); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Form.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Form.id'))); ?> 		<h2><?php __('Edit Form'); ?></h2>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');
    ?><h3><?php __("Subscription Form"); ?></h3><hr /><?php
    echo $this->Form->input('title');
    echo $this->Form->input('description', array("class" => "wysiwyg"));
    ?><h4><?php __("E-Mail"); ?></h4><hr /><?php
    __("E-mail Label", true);
    echo $this->Form->input('Content.e-mail_label');

    __("E-mail Exist", true);
    echo $this->Form->input('Content.e-mail_exist', array('default' => __('You have already subscribed this newsletter',true)));

    __("Update Subscriptions", true);
    echo $this->Form->input('Content.update_subscriber', array('default' => "1", "type" => "checkbox"));
    __("Update Informations", true);
    echo $this->Form->input('Content.update_infos', array('default' => "1", "type" => "checkbox"));
    __("E-mail Error", true);
    echo $this->Form->input('Content.e-mail_error');
    ?><h4><?php __("First Name"); ?></h4><hr /><?php
    __("First Name Label", true);
    echo $this->Form->input('Content.first_name_label');
    __("First Name Error", true);
    echo $this->Form->input('Content.first_name_error');
    __("First Name Required", true);
    echo $this->Form->input('Content.first_name_required', array("type" => "checkbox"));
    ?><h4><?php __("Last Name"); ?></h4><hr /><?php
    __("Last Name Label", true);
    echo $this->Form->input('Content.last_name_label');
    __("Last Name Error", true);
    echo $this->Form->input('Content.last_name_error');
    __("Last Name Required", true);
    echo $this->Form->input('Content.last_name_required', array("type" => "checkbox"));
    ?>
    <h4><?php __("Captcha"); ?></h4><hr /><?php
    __("Captcha Label", true);
    echo $this->Form->input('Content.captcha_label', array('default' => 'Captcha'));
    __("Captcha Error", true);
    echo $this->Form->input('Content.captcha_error', array('default' => __('Please solve this captcha',true)));
    __("Captcha Show", true);
    echo $this->Form->input('Content.captcha_show', array("type" => "checkbox"));
    ?>
    <h4><?php __("Required Checkbox"); ?></h4><hr /><?php
    __("Checkbox Label", true);
    echo $this->Form->input('Content.checkbox_label', array('type' => 'textarea', 'default' => __('I accept the Terms of Service',true)));
    __("Checkbox Error", true);
    echo $this->Form->input('Content.checkbox_error', array('default' => __('Please check this checkbox',true)));
    __("Checkbox Show", true);
    echo $this->Form->input('Content.checkbox_show', array("type" => "checkbox"));
    for ($index = 1; $index <= 4; $index++) {


        if (Configure::read('Settings.custom'.$index.'_show') == "1") {
            ?>
            <h4><?php echo Configure::read('Settings.custom'.$index.'_label'); ?></h4><hr /><?php
        echo $this->Form->input('Content.custom'.$index.'_label', array("label" => Configure::read('Settings.custom'.$index.'_label')." " . __("Label", true)));
        echo $this->Form->input('Content.custom'.$index.'_options', array("label" => Configure::read('Settings.custom'.$index.'_label')." " . __("Options", true)));
            ?>
            <div class="input comment">
                <?php __("Seperate options using a semicolon: Option 1;Option 2"); ?><br />

            </div><?php        echo $this->Form->input('Content.custom'.$index.'_error', array("label" => Configure::read('Settings.custom1_label')." " . __("Error", true)));

        echo $this->Form->input('Content.custom'.$index.'_required', array("label" => Configure::read('Settings.custom'.$index.'_label')." " . __("Required", true), "type" => "checkbox"));
        echo $this->Form->input('Content.custom'.$index.'_top', array("label" => Configure::read('Settings.custom'.$index.'_label')." " . __("in Top", true), "type" => "checkbox"));
    }
}
        ?> 


    <h4><?php __("Categories"); ?></h4><hr /><?php
    __("Categories Label", true);
    echo $this->Form->input('Content.categories_label');
    __("Categories Error", true);
    echo $this->Form->input('Content.categories_error');
    __("User Can Choose", true);
    echo $this->Form->input('Content.user_can_choose', array("type" => "checkbox"));

    echo $this->Form->input('Category', array("label" => __("Add to this categories", true), 'multiple' => 'checkbox', "options" => $cats, "div" => array("class" => "cbox")));
    echo "<div style=\"clear:both;\" ></div>";
    __("Submit Button", true);
    echo $this->Form->input('Content.submit_button');
        ?><h3><?php __("Thanks"); ?></h3><hr /><?php
    __("Thanks Page Url", true);
    echo $this->Form->input('Content.thanks_page_url');
    __("Thanks Title", true);
    echo $this->Form->input('thanks_title');
    __("Thanks Text", true);
    echo $this->Form->input('thanks_text', array("class" => "wysiwyg"));
        ?><h3><?php __("Message if subscriber needs to confirm subscription"); ?></h3><hr /><?php
    __("Confirm Page Url", true);
    echo $this->Form->input('Content.confirm_page_url');
    __("Confirm Title", true);
    echo $this->Form->input('confirm_title');
    __("Confirm Text", true);
    echo $this->Form->input('confirm_text', array("class" => "wysiwyg"));
        ?><h3><?php __("Unsubscribe"); ?></h3><hr /><?php
    __("Unsubscribe Page Url", true);
    echo $this->Form->input('Content.unsubscribe_page_url');
    __("Unsubscribe Title", true);
    echo $this->Form->input('unsubscribe_title');
    __("Unsubscribe Text", true);
    echo $this->Form->input('unsubscribe_text', array("class" => "wysiwyg"));
        ?>


    <?php echo $this->Form->end(__('Save', true)); ?>


</div>
<script type="text/javascript" src="<?php echo $this->Html->url("/"); ?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
    $().ready(function() {
        
        $('.wysiwyg').tinymce({
            // Location of TinyMCE script
            script_url : '<?php echo $this->Html->url("/"); ?>js/tiny_mce/tiny_mce.js',
            document_base_url : "<?php echo $this->Html->url("/", true); ?>",
            language : "<?php __("en"); ?>",
            // General options
            theme : "advanced",
            plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",


            // Theme options
            theme_advanced_buttons1 : "save,newdocument,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
            theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
            theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
            theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
            remove_script_host : false,relative_urls : false,
            file_browser_callback: 'fileBrowserCallBack'

        });
    });
    function fileBrowserCallBack(field_name, url, type, win) {
        jq=null;
        var w = window.open('<?php echo $this->Html->url("/filemanager", true); ?>', null, 'width=800,height=455');
        // Save required parameters in global variables of window (not the best solution, can offer better?)
        // else you can pass parameters using GET and than parse them in elfinder.html
        w.tinymceFileField = field_name;
        w.tinymceFileWin = win;
    }
    function imgchooser(name){
        jq="1";
        browserField = $("#"+name);
        var w = window.open('<?php echo $this->Html->url("/filemanager", true); ?>', null, 'width=800,height=455');


    }
</script>
