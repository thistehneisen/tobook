<?php

function makefields($fieldnamme, $fieldpath, $imgchooser, $baseurl) {

    $textarea = ' <div class="input textarea"><label style="clear:both;float:none;" for="{id}">{lable}</label><textarea id="{id}" class="wysiswyg{lite}" rows="6" cols="30" name="{name}">{content}</textarea></div>';

    $input = '<div class="input input"><label style="clear:both;float:none;" for="{id}">{lable}</label><input type="text" id="{id}" value="{content}"  name="{name}" />{chooser}</div>';

    $output = "";
    if ($imgchooser == 3) {
        $output = str_replace("{lite}", "_raw", $textarea);
    } else if ($imgchooser == 2) {
        $output = str_replace("{lite}", "_lite", $textarea);
    } else if ($fieldnamme[0] == strtoupper($fieldnamme[0])) {

        $output = str_replace("{lite}", "", $textarea);
    } else {
        $output = $input;
    }
    if ($imgchooser == 1) {
        $output = str_replace("{chooser}", "<a  onclick=\"imgchooser('{id}')\"><img style=\"margin-bottom: -6px; margin-left: 6px;cursor:pointer\" src=\"" . $baseurl . "img/document-open.png\" /></a>", $output);
    }
    $output = str_replace(array("{chooser}", "{lable}", "{id}", "{name}"), array("", Inflector::humanize($fieldnamme), str_replace(array("data[", "[", "]"), array("", "", ""), $fieldpath) . $fieldnamme, $fieldpath . "[$fieldnamme]"), $output);
    return $output;
}

echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Mail.id')), array('class' => 'button', 'style' => 'float: right; margin-top: 15px;margin-right: 0px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Mail.id')));
?> 		   <h2><?php __('Create Newsletter'); ?></h2>
<ul class="fiveStep" id="mainNav">
    <li class="done"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 1) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/1/"); ?>"<?php } ?>><em><?php __("Step 1: Base Settings"); ?></em><span><?php __("Set subject"); ?><br /><?php
if ($this->Form->value('Mail.campaign_id') == 0) {
    __("Select recipients");
} else {
    __("Set delay");
}
?></span></a></li>
    <li class="lastDone"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 2) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/2/"); ?>"<?php } ?>><em><?php __("Step 2: Template"); ?></em><span><?php __("Choose newsletter template and type"); ?></span></a></li>
    <li class="current"><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 3) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/3/"); ?>"<?php } ?>><em><?php __("Step 3: Content"); ?></em><span><?php __("Fill in content"); ?></span></a></li>

    <li ><a title="" <?php if ($this->Form->value('Mail.last_step') + 1 >= 4) { ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/4/"); ?>"<?php } ?> ><em><?php __("Step 4: Preview and Test"); ?></em> <span style="width:200px"><?php __("Preview newsletter"); ?><br /><?php __("Send test newsletter"); ?></span></a></li>
    <li class="mainNavNoBg"><a title=""<?php if ($this->Form->value('Mail.last_step') + 1 >= 4) {
    ?>class="linked" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/5/"); ?>"<?php } ?>><em><?php __("Step 5: Send newsletter"); ?></em><span><?php __("Choose tracked links"); ?><br /><?php
                               if ($this->Form->value('Mail.campaign_id') == 0) {
                                   __("Send or schedule");
                               } else {
                                   __("Publish newsletter");
                               }
?></span></a></li>

</ul>
<div class="mails form" style="clear:both;padding-top:12px;">
    <?php echo $this->Form->create('Mail'); ?>
    <?php
    if ($this->Form->value('Mail.type') == 0 || $this->Form->value('Mail.type') == 1) {
        $template_fields = false;
        $template_fields = @myunserialize($this->data["Template"]["fields_array"]);
        $template_comment = @myunserialize($this->data["Template"]["comment_array"]);


        $additionalboxes = array();
        if ($template_fields !== false) {
            foreach ($template_fields as $key => $fielddata) {
                if (!is_array($fielddata)) {
                    $imgchooser = 0;
                    if (strpos($fielddata, "_imgchooser") !== false) {
                        $fielddata = str_replace("_imgchooser", "", $fielddata);
                        $imgchooser = 1;
                    }
                    if (strpos($fielddata, "_lite") !== false) {
                        $fielddata = str_replace("_lite", "", $fielddata);
                        $imgchooser = 2;
                    }
                     if (strpos($fielddata, "_raw") !== false) {
                        $fielddata = str_replace("_raw", "", $fielddata);
                        $imgchooser = 3;
                    }
                    echo str_replace("{content}", isset($this->data["Mail"]["Data"][$fielddata]) ? $this->data["Mail"]["Data"][$fielddata] : "", makefields($fielddata, "data[Mail][Data]", $imgchooser, $this->Html->url("/")));
                    if (isset($template_comment[$fielddata])) {
                        echo '<div style="color:#444444;margin-top: -8px;">' . $template_comment[$fielddata] . ' </div>';
                    }
                } else {
                    $baseobj = "data[Mail][Data][$key][countnumber]";
                    echo '<fieldset id="Content' . $key . '" style="position:relative;" class="cblocks">';
                    echo '<a style="position:absolute;top:0;right:22px;" class="button" href="#" onclick="addsub(\'' . $key . '\');return false;">'.__("Add Item",true).'</a>';
                    echo '<a style="position:absolute;bottom:22px;right:22px;" class="button downbutton" href="#" onclick="addsub(\'' . $key . '\');return false;">'.__("Add Item",true).'</a>';
                    echo '<legend style="float:left;">' . Inflector::humanize($key) . '</legend>';
                    $additionalboxes[$key] = "<div id=\"addbox_" . $key . "_countnumber\" class=\"addbox\">";


                    foreach ($fielddata as $fieldname) {
                        $imgchooser = 0;
                        if (strpos($fieldname, "_imgchooser") !== false) {
                            $fieldname = str_replace("_imgchooser", "", $fieldname);
                            $imgchooser = 1;
                        }
                        if (strpos($fieldname, "_lite") !== false) {
                            $fieldname = str_replace("_lite", "", $fieldname);
                            $imgchooser = 2;
                        }
                         if (strpos($fieldname, "_raw") !== false) {
                            $fieldname = str_replace("_raw", "", $fieldname);
                            $imgchooser = 3;
                        }
                        $additionalboxes[$key].=str_replace('{content}', '{' . $fieldname . '}', makefields($fieldname, $baseobj, $imgchooser, $this->Html->url("/")));
                        if (isset($template_comment[$key . "." . $fieldname])) {
                            $additionalboxes[$key].= '<div style="color:#444444;margin-top: -8px;">' . $template_comment[$key . "." . $fieldname] . ' </div>';
                        }
                    }
                    $additionalboxes[$key] .='<a style="  margin-top: 15px; margin-right: 0px;" class="button" href="#" onclick="$(\'#addbox_' . $key . '_countnumber\').fadeOut(\'slow\', function() { $(this).remove();udate(); });return false;">Remove</a>';

                    $additionalboxes[$key].="<br /><br /><hr /></div>";
                    $mycounter = 0;
                    if (isset($this->data["Mail"]["Data"][$key]) && count($this->data["Mail"]["Data"][$key]) != 0) {
                        foreach ($this->data["Mail"]["Data"][$key] as $inhalt) {
                            $data = $additionalboxes[$key];
                            foreach ($fielddata as $fieldname) {
                                $fieldname = str_replace(array("_imgchooser", "_lite","_raw"), "", $fieldname);
                                $data = str_replace('{' . $fieldname . '}', isset($inhalt[$fieldname]) ? $inhalt[$fieldname] : "", $data);
                            }
                            echo str_replace("countnumber", $mycounter, $data);
                            $mycounter++;
                        }
                    }

                    echo '</fieldset>';
                }
            }
        }
    }
    echo $this->Form->input('id');


    if ($this->Form->value('Mail.type') == 0 || $this->Form->value('Mail.type') == 2){
        echo $this->Form->input('content_text', array("label" => __("Plain Text", true)));
    ?><input type="submit" name="loadtext" value="<?php __("Load plain text from HTML"); ?>"><?php
    }
    echo $this->Form->input('step', array("type" => "hidden"));
    echo $this->Form->input('type', array("type" => "hidden"));

    echo $this->Form->input('last_step', array("type" => "hidden"));
    ?>


    <?php $this->Form->end(__('Next', true)); ?>
    <div class="submit"><a class="button" href="<?php echo $this->Html->url("/mails/step/" . $this->Form->value('Mail.id') . "/2/"); ?>"><?php __("Back"); ?></a> <input type="submit" value="<?php __("Next"); ?>"></div>
</form>
</div>
<div style="display:none;"><?php
    foreach ($additionalboxes as $k => $box) {
        $out = str_replace("wysiswyg", $k . "_countnumber", $box);
        foreach ($template_fields[$k] as $fieldname) {
            $fieldname = str_replace(array("_imgchooser", "_lite", "_raw"), "", $fieldname);
            $out = str_replace('{' . $fieldname . '}', "", $out);
        }
        echo $out;
    }
    ?>
</div>
<script type="text/javascript" src="<?php echo $this->Html->url("/"); ?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
    function udate(){
        $(".cblocks").each(function(){
            if($(this).find(".addbox").length==0){
                $(this).find(".downbutton").hide();
            }else{
                $(this).find(".downbutton").show();
            }
        });
    }
    function addsub( name){
        var num=100+Math.floor(Math.random()*401);
        $(("<div id=\"addbox_"+name+"_countnumber\" class=\"addbox\">"+$("#addbox_"+name+"_countnumber").html()+"</div>").replace(/countnumber/gi, num)).hide().appendTo('#Content'+name).fadeIn();
        $("."+name+"_"+num).tinymce({
            // Location of TinyMCE script
            script_url : '<?php echo $this->Html->url("/"); ?>js/tiny_mce/tiny_mce.js',
            document_base_url : "<?php echo $this->Html->url("/", true); ?>",
            language : "<?php __("en"); ?>",
            // General options
            theme : "advanced",
            plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
            forced_root_block : false,
            force_br_newlines : true,
            force_p_newlines : false,
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
        $("."+name+"_"+num+'_lite').tinymce({
            // Location of TinyMCE script
            script_url : '<?php echo $this->Html->url("/"); ?>js/tiny_mce/tiny_mce.js',
            document_base_url : "<?php echo $this->Html->url("/", true); ?>",
            language : "<?php __("en"); ?>",
            // General options
            theme : "advanced",
            mode : "none",
            forced_root_block : false,
            force_br_newlines : true,
            force_p_newlines : false,
            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,undo,redo,link,unlink,image,|,forecolor,backcolor,|,formatselect,removeformat,|,bullist,numlist,|,cleanup,code",
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : "",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
            remove_script_host : false,relative_urls : false,
            file_browser_callback: 'fileBrowserCallBack'

        });
        udate();
    }
    $().ready(function() {
        $('textarea.wysiswyg').tinymce({
            // Location of TinyMCE script
            script_url : '<?php echo $this->Html->url("/"); ?>js/tiny_mce/tiny_mce.js',
            document_base_url : "<?php echo $this->Html->url("/", true); ?>",
            language : "<?php __("en"); ?>",
            // General options
            theme : "advanced",
            plugins : "pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
            forced_root_block : false,
            force_br_newlines : true,
            force_p_newlines : false,
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
        $('textarea.wysiswyg_lite').tinymce({
            // Location of TinyMCE script
            script_url : '<?php echo $this->Html->url("/"); ?>js/tiny_mce/tiny_mce.js',
            document_base_url : "<?php echo $this->Html->url("/", true); ?>",
            language : "<?php __("en"); ?>",
            // General options
            theme : "advanced",
            mode : "none",
            forced_root_block : false,
            force_br_newlines : true,
            force_p_newlines : false,
            // Theme options
            theme_advanced_buttons1 : "bold,italic,underline,undo,redo,link,unlink,image,|,forecolor,backcolor,|,formatselect,removeformat,|,bullist,numlist,|,cleanup,code",
            theme_advanced_buttons2 : "",
            theme_advanced_buttons3 : "",
            theme_advanced_toolbar_location : "top",
            theme_advanced_toolbar_align : "left",
            theme_advanced_statusbar_location : "bottom",
            theme_advanced_resizing : true,
            remove_script_host : false,relative_urls : false,
            file_browser_callback: 'fileBrowserCallBack'

        });
        udate();
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