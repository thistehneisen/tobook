<div class="templates form">
    <?php echo $this->Form->create('Template'); ?>
    <?php echo $this->Html->link(__('Delete', true), array('action' => 'delete', $this->Form->value('Template.id')), array('class' => 'button', 'style' => 'float: right; margin-top: -5px;'), sprintf(__('Are you sure you want to delete # %s?', true), $this->Form->value('Template.id'))); ?> 		<h2><?php __('Edit Template'); ?></h2>
    <?php
    echo $this->Form->input('id');
    echo $this->Form->input('name');

    __("content", true);
    echo $this->Form->input('content',array("style"=>"resize: both;"));
    ?>
     <div class="input comment load">
    <a href="#" class="button sbutton" onclick="setup();return false;">Load Editor</a>
     </div>
    <div class="input comment">
        <?php __('The Newsletter Mailer uses the <a href="http://www.smarty.net/">SMARTY</a> template engine.<br />

      Insert field: {$<b>fieldname</b>}<br />
      Start repeating block: <?php echo htmlspecialchars("<!---");?>foreach name=<b>groupname</b> item=<b>anyname</b> from=<b>groupname</b><?php echo htmlspecialchars("--->");?><br />
      Insert field in block: {$<b>anyname</b>.<b>fieldname</b>}<br />
      End repeating block:  <?php echo htmlspecialchars("<!---");?>/foreach<?php echo htmlspecialchars("--->");?><br />
      Conditons:<br />
      -Not empty:  <?php echo htmlspecialchars("<!---");?>if !empty($<b>fieldname</b>)<?php echo htmlspecialchars("--->");?><br />
      End conditon block:  <?php echo htmlspecialchars("<!---");?>/if<?php echo htmlspecialchars("--->");?><br />
      Image: [image src={$<b>fieldname</b>} w=<b>width</b> h=<b>height</b> border=<b>border</b> bcolor=<b>bordercolor</b> ]'); ?>
    <br />
       <?php __('Load Website: [load url=<b>url to page</b> script=<b>Leave javascript=>1, Remove javacript=>0 </b>]'); ?>

    </div>
    <?php    __("Parse Css", true);
    echo $this->Form->input('parse_css');
    ?>
    <div class="input comment">
        <?php __("If this checkbox is checked the Newsletter Mailer will apply the css style of the template to the HTML code.(Most mail clients don\'t parse css)"); ?>
    </div>
    <?php    echo str_replace("</div>", "<a  onclick=\"imgchooser('TemplatePreview')\"><img style=\"margin-bottom: -6px; margin-left: 6px;cursor:pointer\" src=\"" . $this->Html->url("/") . "img/document-open.png\" /></a></div>", $this->Form->input('preview'));
    __("Fields", true);
    echo $this->Form->input('fields');
    ?>
    <div class="input comment">
        <?php __('Write only one field name per line. The fields can be grouped in repeating blocks using HTML like tags &lt;<b>groupname</b>&gt; &lt;/<b>groupname</b>&gt;<br/>
        Field types:<br /><b>-Textfield</b>: Field name starts lower case
        <br />
        <b>-Image chooser</b>: Field name starts lower case and ends with "_imgchooser"
        <br /><b>-Html editor</b>: Field name starts upper case
        <br /><b>-Simple Html editor</b>: Field name  ends with "_lite"');
        __('<br /><b>-Textarea</b>: Field name  ends with "_raw"');?>
    </div>

    <?php echo $this->Form->end(__('Save', true)); ?>


</div><script type="text/javascript" src="<?php echo $this->Html->url("/"); ?>js/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">
   function setup() {
        $(".load").hide();
        $('#TemplateContent').tinymce({
            // Location of TinyMCE script
            script_url : '<?php echo $this->Html->url("/"); ?>js/tiny_mce/tiny_mce.js',
            document_base_url : "<?php echo $this->Html->url("/", true); ?>",
            language : "<?php __("en"); ?>",
            // General options
            theme : "advanced",
            plugins : "fullpage,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",
        
            external_link_list_url : "<?php echo $this->Html->url("/", true); ?>js/link_list.js",

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
    }
    function fileBrowserCallBack(field_name, url, type, win) {
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
