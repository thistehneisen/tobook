<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | page to set the contact mail							                          |
// +----------------------------------------------------------------------+
 
include "../includes/session.php";
include "../includes/config.php";
 
 $contactEmail = $_SESSION['siteDetails']['contactmailid'] ;
 
?>
<script type="text/javascript" >
$(document).ready(function(){

	 
	  // function to add new menu item
	  $("#btn_editor_updateFeedbackForm").live('click',(function() {

		  toemail = $('#txtEmailAddress').val();
		  if(toemail == '')
			  $('#menueditorresult').html('<span class="msgred">Please enter the email</span>');
			 
		  $.ajax({
				url: "editor/editor_externalformeditorformprocessor.php?type=2",	
				type: "POST",
				data: $("#frmexternalformeditor").serialize(),
				cache: false,
				dataType:'html',			
				success: function(html) {
				 
					 
					if(html == 'success')	{	
						 	 
				 		$('#menueditorresult').html('<span class="msggreen">Successfully updated the form element</span>');
				 		setTimeout(function() {$('#opendialogbox').dialog('close'); },500);
					}
 				}
		  });
	  }));
});
 
</script>
  
 	<form name="frmexternalformeditor" id="frmexternalformeditor">
 		<div id="menueditorresult"></div>
 	 
 		<div id="editorviewmenucontainer">
 		
 		 
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
  
  <tr>
    <td>Your email address</td>
    <td><input type="text" value="<?php echo $contactEmail;?>" name="txtEmailAddress" maxlength="100" size="20" class="textbox_style1" id="txtEmailAddress"> </td>
  </tr>
   
  
</table>
<div class="popupeditpanel_ftr">
    <input type="button" name="btn_editor_updateFeedbackForm" id="btn_editor_updateFeedbackForm" value="Update" class="popup_orngbtn right">
    <div class="clear"></div>
</div>
 		
 		
  		</div>
  	</form>
 	
 	 