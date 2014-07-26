<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add parameter values to the external forms								                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php";

$pageType 	= $_GET['type'];
$parentBox 	= $_GET['param'];
$menuName 	= $_GET['params'];

if($menuName != '') {
    ?>
<script type="text/javascript" >
    $(document).ready(function(){

        $('#editorviewmenucontainer').load('editor/editor_externaladsenseeditorforms.php?page=<?php echo $pageType;?>&menuname=<?php echo $menuName;?>');

        // function to add new menu item
        $("#btn_editor_updateAdsense").live('click',(function() {
            $.ajax({
                url: "editor/editor_externaladsenseeditorformprocessor.php?type=1",
                type: "POST",
                data: $("#frmexternaladsenseeditor").serialize(),
                cache: false,
                dataType:'html',		
                success: function(html) {
                    var resval 	= html.split(/~/);
                    if(resval[0] == 'success')	{
                        $('#menueditorresult').html('<span class="msggreen">Successfully updated the form element</span>');
                        setTimeout(function() {$('#opendialogbox').dialog('close'); },500);
                    } else if(resval[0] == 'failure'){
                       $('#menueditorresult').html('<span class="msgred" style="font-size:12px;">'+resval[1]+'</span>');
                    }else
                        $('#menueditorresult').html('<span class="msgred">Some unexpected error occured</span>');
                }
            });
        }));
    });

</script>

<form name="frmexternaladsenseeditor" id="frmexternaladsenseeditor" method="">
    <div id="menueditorresult"></div>
    <input type="hidden" name="txtpagetype" id="txtpagetype" value="<?php echo $pageType;?>">
    <input type="hidden" name="txtparentbox" id="txtparentbox" value="<?php echo $parentBox;?>">
    <input type="hidden" name="txtformname" id="txtformname" value="<?php echo $menuName;?>">
    <input type="hidden" name="txtlastaddmenu" id="txtlastaddmenu" value="">
    <div id="editorviewmenucontainer">
    </div>
</form>

    <?php
}
else {
    echo "Error....";
}

?>