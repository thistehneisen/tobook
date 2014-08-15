<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to handle menu management							                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php"; 
 
$action 	= $_GET['action'];
$id 		= $_GET['id'];
if($action != '' && $id != ''){
?>
<script type="text/javascript" >
$(document).ready(function(){
 
	$('#jqeditorviewpagecontainer').load('editor/editor_menumanagementforms.php?action=viewmenu&id=<?php echo $id;?>');
 	// function to load the page options
 		//$(".jqloadPage").live('click',(function() {
                $('.jqloadPage').die('click').live('click', function () {
			$(".jqloadPage").removeClass('linkactive');
			$(this).addClass('linkactive');
		  	var action	=$(this).attr('id'); 
		  	var id	=$('#txtmenuid').val(); 
		  	$('#jqpageeditorresult').html('');
		  	$('#jqeditorviewpagecontainer').load('editor/editor_menumanagementforms.php?action='+action+'&id='+id);
		  	return false;	  
	 	});



		// function to add new menu
                $('#btn_editor_addmenuitem').die('click').live('click', function () {
			$.ajax({
				url: "editor/editor_menumanagementformprocessor.php?type=1",	
				type: "POST",
				data: $("#frmeditormenumgmnt").serialize(),
				cache: false,
				dataType:'html',			
				success: function(html) {	
					// alert(html);
					$('#jqpageeditorresult').html(html);
						if(html == 'success')
							$('#jqpageeditorresult').html('<span class="success">Successfully added the menu item</span>');	
						else if(html == 'editsuccess')
							$('#jqpageeditorresult').html('<span class="success">Successfully updated the menu item</span>');	

						$('#txttitle').val('');
						
						setTimeout(function() {
							var id			= $('#txtmenuid').val(); 
							updateMenu(id); }, 1000);
					 	
						return false;
				}			            
			});
	  });
	  


	  // function to edit the menu item
	  $(".editmenudetails").live('click',(function() {
		  	var menuid		= $(this).attr('id'); 
		  	var id			= $('#txtmenuid').val(); 
			$('#jqeditorviewpagecontainer').load('editor/editor_menumanagementforms.php?action=addmenu&menu='+menuid+'&item='+id+'&type=edit');	 
			return false; 
	  }));


 

 
});

function deleteMenuItem(menuitemid)
{
	if (confirm('Are you sure to remove the menu item')) {
	  if(menuitemid != '') {
	  $('#menuid_'+menuitemid).hide();
	 	var id			= $('#txtmenuid').val(); 
		$('#jqpageeditorresult').load('editor/editor_menumanagementformprocessor.php?type=2&menuitem='+menuitemid+'&menuname='+id);
		updateMenu(id);
	  }
	}
 	return false;
}

// function to update the menu in the html after an action
function updateMenu(menuname){
	//alert('hello:'+menuname);
	$('#'+menuname).load('editor/editor_ajaxcallresult.php?type=loadmenu&menuname='+menuname);
}
 
</script>
 	<a href="" class="jqloadPage linkactive" id="viewmenu"><?php echo EDITOR_VIEW_ITEMS;?></a> | <a href="" class="jqloadPage" id="addmenu"  ><?php echo EDITOR_ADD_MENU;?></a>
 	<form name="frmeditormenumgmnt" id="frmeditormenumgmnt">
 		<div id="jqpageeditorresult"></div>
 		<input type="hidden" name="txtpageaction" id="txtpageaction" value="<?php echo $action;?>">
 		<input type="hidden" name="txtmenuid" id="txtmenuid" value="<?php echo $id;?>">
 		<div id="jqeditorviewpagecontainer"> </div>
  	</form>
  
 	
 	<?php  
}
else {
	echo "Error....";
}
 	
 	?>