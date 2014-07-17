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

//echo "<pre>";
//print_r($_GET);

if($menuName != '') {
    ?>
<script type="text/javascript" >
    $(document).ready(function(){

        $('#editorviewmenucontainer').load('editor/editor_externalcustomformeditorforms.php?page=<?php echo $pageType;?>&menuname=<?php echo $menuName;?>');

        // function to add new menu item
        $("#btn_editor_updateCustomForm").live('click',(function() {
           // alert(out);
            var frm = document.frmexternalcustomformeditor;
            var msg = "";
            var pages = '<?php echo $pages;?>';
            pages = pages.split(",");
/*
            if(frm.txtPageHeading.value.length == 0){
                msg += "* Page Heading is required!"+ "\n";
            }*/
            if(frm.txtEmailAddress.value.length == 0){
                msg += "*" + CUSTOMFRM_MAIL + "\n";
            }else if(!checkMail(frm.txtEmailAddress.value)){
                msg += "*" + CUSTOMFRM_INVALID_MAIL + "\n";
            }
            if(out.length == 0){
                msg += "*" + CUSTOMFRM_ADDELMTS + "\n";
            }
            if(msg != ""){
                msg = CUSTOMFRM_CORRECTELMNTS + "\n" +msg;
                alert(msg);
                return false;
            }else{
                frm.formelements.value = getOutput(out,true);
            }

            $.ajax({
                url: "editor/editor_externalcustomformeditorformprocessor.php?apptype=1",
                type: "POST",
                data: $("#frmexternalcustomformeditor").serialize(),
                cache: false,
                dataType:'html',
                success: function(html) {  
                    
                    var resval 	= html.split(/~/);
                    if(resval[0] == 'success')	{
                        $('#menueditorresult').html('<span class="msggreen">Successfully updated the form element</span>');

                        var replaceItem = $('#txtparentbox').val();
                    	var formName = $('#txtformname').val();
				 		$('#'+replaceItem).load('editor/editor_ajaxcallresult.php?type=loadcustomform&formname='+formName);
				 	
                        setTimeout(function() {$('#opendialogbox').dialog('close'); },500);
                    }
                    else
                        $('#menueditorresult').html('<span class="msgred">Some unexpected error occurred</span>');
                }
            });
        }));
    });

    function validateForm(){


    }

    function addItemtoSession(fieldArr){
        var txtformname = $("#txtformname").val();
            $.ajax({
                url: "editor/editor_externalcustomformeditorformprocessor.php?apptype=2",
                type: "POST",
                data: "type="+fieldArr['type']+"&display="+fieldArr['display']+"&name="+fieldArr['name']+"&size="+fieldArr['size']+"&maxlenght="+fieldArr['maxlenght']+"&rows="+fieldArr['rows']+"&options="+fieldArr['options']+"&columns="+fieldArr['columns']+"&txtformname="+txtformname,
                cache: false,
                dataType:'json',
                success: function(html) {   
                    }
            });

    }

</script>

<form name="frmexternalcustomformeditor" id="frmexternalcustomformeditor" >
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