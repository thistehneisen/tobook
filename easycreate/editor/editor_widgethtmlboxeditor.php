<?php   
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2012-2013 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 2                 |
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
include "../includes/session.php";
include "../includes/config.php"; 
/*
  * file to modify the panels of the template
*/
// echo "<pre>";
// print_r($_GET);
//exit();
$currentPage 	= $_SESSION['siteDetails']['currentpage']; 
$params 		= $_GET['params'];
$param 			= $_GET['param']; 
?>
 <script type="text/javascript" >
$(document).ready(function(){
	 // function to edit the menu item
	  $("#btnHtmlBoxUpdate").live('click',(function() {
		  $.ajax({
				url: "editor/editor_widgethtmlboxprocessor.php?type=1",	
				type: "POST",
				data: $("#frmHtmlBoxUpdator").serialize(),
				cache: false,
				dataType:'html',			
				success: function(html) {	
					 if(html == 'success'){
		                    newPanelContent = $('#panelboxhtml').val();

		                    htmlboxid = $('#params').val();
		                   // alert(htmlboxid);
		                    $('#panelEditResult').html('<span class="success">Successfully Updated the Panel</span>');
		                    window.parent.$('#editpanel_'+htmlboxid).html(newPanelContent);
		                    setTimeout(function() {$('#opendialogbox').dialog('close'); },500);
		                }
		                else
		                    $('#panelEditResult').html('<span class="error">Some unexpected error occurred</span>');
					 return false;
				}			            
			});
			return false; 
	  }));


});
 

</script>
<?php
if($params != '') {
    $panelBoxHtml = $_SESSION['siteDetails'][$currentPage]['apps'][$params];
    ?>
<div class="popupcontent_newwrapper">

      <div id="panelEditResult"></div>
    <form name="frmHtmlBoxUpdator" id="frmHtmlBoxUpdator">
        <div id="editor_htmleditor">
            <input type="hidden" name="param" id="param" value="<?php echo $param; ?>">
            <input type="hidden" name="params" id="params" value="<?php echo $params; ?>">
            <textarea style="height: 190px;" name="panelboxhtml" id="panelboxhtml" rows="12" cols="71"><?php echo trim($panelBoxHtml);?></textarea>
            <div class="popupeditpanel_ftr">
                <input type="button" name="btnHtmlBoxUpdate" id="btnHtmlBoxUpdate" value="Update" class="popup_orngbtn right">
             </div>
        </div>
    </form>
    

</div>
    <?php
}
else
    echo "Some unexpected error occurred";
?>




