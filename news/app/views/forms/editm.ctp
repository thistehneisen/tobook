<div class="forms form">
    <?php echo $this->Form->create('Form'); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Form.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Form.id'))); ?> 		
    <h2><?php __('Edit Mails related to Form'); ?></h2>
    <?php
    echo $this->Form->input('id');

    echo $this->Form->input('name');
    __("Configuration", true);
    echo $this->Form->input('configuration_id');
    ?><h3>Subscriber confirmation</h3><hr /><?php
    echo $this->Form->input('confirm', array("label" => __("Confirm subscriber", true)));
    __("Title", true);
    __("Content", true);
    echo $this->Form->input('confirmm.title');
    echo $this->Form->input('confirmm.content', array("class" => "wysiwyg", "type" => "textarea"));
    ?>



    <h3><?php __('New Subscription Notify Mail'); ?></h3><hr /><?php
    echo $this->Form->input('notify', array("label" => __("Send mail notification", true)));
    __("Notify Addresse", true);
    echo $this->Form->input('notify_addresse');
    echo $this->Form->input('notifym.title');
    echo $this->Form->input('notifym.content', array("class" => "wysiwyg", "type" => "textarea"));
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
            plugins : "fullpage,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

            external_link_list_url : "<?php echo $this->Html->url("/", true); ?>js/link_list2.js",
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
