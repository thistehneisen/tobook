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
 
if($menuName != ''){
?>
<script type="text/javascript" >
$(document).ready(function(){

	$('#editorviewmenucontainer').load('editor/editor_externalformeditorforms.php?page=<?php echo $pageType;?>&menuname=<?php echo $menuName;?>');

	  // function to add new menu item
	  $("#btn_editor_updateFeedbackForm").live('click',(function() { 

		  toemail = $('#txtEmailAddress').val(); 
		  if(toemail == ''){
                      $('#menueditorresult').html('<span class="msgred">Please enter the email</span>');
                  }else if(!checkMail(toemail)){ 
                      $('#menueditorresult').html('<span class="msgred">Please enter a valid email address</span>');
                      return false;
                  }
			 
		  $.ajax({
				url: "editor/editor_externalformeditorformprocessor.php?type=1",	
				type: "POST",
				data: $("#frmexternalformeditor").serialize(),
				cache: false,
				dataType:'html',			
				success: function(html) {
				 
					var resval 	= html.split(/~/);
					if(resval[0] == 'success')	{	
						 	 
				 		$('#menueditorresult').html('<span class="msggreen">Successfully updated the form element</span>');
				 		var replaceItem = $('#txtparentbox').val();
				 		
				 		var formName = $('#txtformname').val();
				 		//$('#'+replaceItem).html('test');
				 		$('#'+replaceItem).load('editor/editor_ajaxcallresult.php?type=loadcontactform&formname='+formName);
				 		//alert(replaceItem);
				 		setTimeout(function() {$('#opendialogbox').dialog('close'); },500);
					}
					else if(resval[0] == 'error')	
		            	$('#menueditorresult').html('<span class="msgred">'+resval[1]+'</span>');
				}
		  });
	  }));
});


 function checkMail(email){ 
        var str1=email;
        var arr=str1.split('@');
        var eFlag=true;
        if(arr.length != 2)
        {
            eFlag = false;
        }
        else if(arr[0].length <= 0 || arr[0].indexOf(' ') != -1 || arr[0].indexOf("'") != -1 || arr[0].indexOf('"') != -1 || arr[1].indexOf('.') == -1)
        {
            eFlag = false;
        }
        else
        {
            var dot=arr[1].split('.');
            if(dot.length < 2)
            {
                eFlag = false;
            }
            else
            {
                if(dot[0].length <= 0 || dot[0].indexOf(' ') != -1 || dot[0].indexOf('"') != -1 || dot[0].indexOf("'") != -1)
                {
                    eFlag = false;
                }

                for(i=1;i < dot.length;i++)
                {
                    if(dot[i].length <= 0 || dot[i].indexOf(' ') != -1 || dot[i].indexOf('"') != -1 || dot[i].indexOf("'") != -1 || dot[i].length > 4)
                    {
                        eFlag = false;
                    }
                }
            }
        }
        return eFlag;
    }
</script>
  
 	<form name="frmexternalformeditor" id="frmexternalformeditor">
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