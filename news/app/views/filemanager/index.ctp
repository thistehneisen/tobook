<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
    "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <title>elFinder</title>
        <!--
        <script type='text/javascript' src='http://getfirebug.com/releases/lite/1.2/firebug-lite-compressed.js'></script>
	-->
        <link rel="stylesheet" href="<?php echo $html->url("/elfinder/"); ?>js/ui-themes/base/ui.all.css" type="text/css" media="screen" title="no title" charset="utf-8">
        <link rel="stylesheet" href="<?php echo $html->url("/elfinder/"); ?>css/elfinder.css" type="text/css" media="screen" title="no title" charset="utf-8">

        <!--
        <script src="js/jquery-1.3.2.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="js/jquery-ui-1.8b1.min.js" type="text/javascript" charset="utf-8"></script>
	-->

        <script src="<?php echo $html->url("/elfinder/"); ?>js/jquery-1.4.1.min.js" type="text/javascript" charset="utf-8"></script>
        <script src="<?php echo $html->url("/elfinder/"); ?>js/jquery-ui-1.7.2.custom.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?php echo $html->url("/elfinder/"); ?>js/elfinder.min.js" type="text/javascript" charset="utf-8"></script>
        
        <script src="<?php echo $html->url("/elfinder/"); ?>js/i18n/elfinder.<?php __("en"); ?>.js" type="text/javascript" charset="utf-8"></script>
        <!--
        <script src="js/i18n/elfinder.ru.js" type="text/javascript" charset="utf-8"></script>
	-->

        <style type="text/css">
            #close, #open, #dock, #undock {
                width: 100px;
                position:relative;
                display: -moz-inline-stack;
                display: inline-block;
                vertical-align: top;
                zoom: 1;
                *display: inline;
                margin:0 3px 3px 0;
                padding:1px 0;
                text-align:center;
                border:1px solid #ccc;
                background-color:#eee;
                margin:1em .5em;
                padding:.3em .7em;
                border-radius:5px;
                -moz-border-radius:5px;
                -webkit-border-radius:5px;
                cursor:pointer;
            }
        </style>


        <script type="text/javascript" charset="utf-8">
            $().ready(function() {

                var f = $('#finder').elfinder({
                    url : '<?php echo $html->url("/filemanager/connector/"); ?>',
                    lang : '<?php __("en"); ?>',

                    editorCallback : function(url) {
                        if (window.console && window.console.log) {
                            window.console.log(url);
                        } else {
                            alert(url);
                        }

                    },
                    editorCallback : function(url) {
                        if(window.top.opener.jq!=null){
                            window.top.opener.browserField.val( url.substr(<?php echo strlen($html->url("/")); ?>));
                        }else{

 
                            window.tinymceFileWin.document.forms[0].elements[window.tinymceFileField].value = url;
                            window.tinymceFileWin.focus();
                            if (window.tinymceFileWin.document.forms[0].elements[window.tinymceFileField].onchange != null) window.tinymceFileWin.document.forms[0].elements[window.tinymceFileField].onchange();
                        }
                        window.close();
                    },
                    closeOnEditorCallback : true
                    // docked : true,
                    //    dialog : {
                    //       	title : 'File manager',
                    /////         	height : 500
                    //     }
                })
                // window.console.log(f)
                $('#close,#open,#dock,#undock').click(function() {
                    $('#finder').elfinder($(this).attr('id'));
                })

            })
        </script>

    </head>
    <body>

        <div id="finder">finder</div>



    </body>
</html>