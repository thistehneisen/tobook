<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add parameter values to the external boxes								                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php";
 
$pageType 	= $_GET['type'];
$parentBox 	= $_GET['param'];
$menuName 	= $_GET['params'];

/*
echo "<pre>";
print_r($_GET);
*/
if($menuName != ''){
?>
<script type="text/javascript" >
$(document).ready(function(){

	$('#editorviewmenucontainer').load('editor/editor_externalmenueditorforms.php?page=<?php echo $pageType;?>&menuname=<?php echo $menuName;?>');

	 // function to load the menu items
	  $(".loadPage").live('click',(function() {  

		  $(".loadPage").removeClass('linkactive');
			$(this).addClass('linkactive');
		  
		  var params	= $(this).attr('id'); 
		  var menuname 	= $('#txtmenuname').val();
		  $('#txtpagetype').val(params);
		  $('#menueditorresult').html('');
		  $('#editorviewmenucontainer').load('editor/editor_externalmenueditorforms.php?page='+params+'&menuname='+menuname);
		  return false;	  
	  }));


	  // function to add new menu item
	  $("#btn_editor_addmenuitems").live('click',(function() {
	   $('#btn_editor_addmenuitems').attr('disabled','disabled');
		  $.ajax({
				url: "editor/editor_externalmenueditorformprocessor.php?type=1",	
				type: "POST",
				data: $("#frmexternalmenueditor").serialize(),
				cache: false,
				dataType:'html',			
				success: function(html) {	
						 
					var resval 	= html.split(/~/);
					if(resval[0] == 'success')				 
				 		$('#menueditorresult').html('<span class="msggreen">Successfully added the menu item</span>');
					else if(resval[0] == 'editsuccess')				 
				 		$('#menueditorresult').html('<span class="msggreen">Successfully updated the menu item</span>');
			 		
				 	$('#txtaddmenutitle').val('');
				 	$('#txtaddmenulink').val(''); 

					// assign the menu item to parent menu
					//var lastaddmenu = $('#txtlastaddmenu').val();
					var menuname 	= $('#txtmenuname').val();
					var lastaddmenu = $('#txtparentbox').val();
					$('#'+lastaddmenu).load('editor/editor_ajaxcallresult.php?type=loadnavmenu&menuname='+menuname);
			 							
				//	$('#'+lastaddmenu).append(appendText);
					/*
					 alert(lastaddmenu);
					 alert(resval[1]);
					if(lastaddmenu != resval[1]) {
				 		var menuname 	= 'menu'+$('#txtparentbox').val();
				 		appendText 		= '<li>'+resval[1]+'</li>';
				 		$('#'+lastaddmenu).append(appendText);
				 		$('#txtlastaddmenu').val(resval[1]);
					}
					*/
					$('#btn_editor_addmenuitems').removeAttr('disabled');
					return false;
						 
				}			            
			});
	  }));


	  // function to edit the menu item
	  $(".editnavmenuitems").live('click',(function() {
		  	var navid		= $(this).attr('id'); 
			var menuname 	= $('#txtmenuname').val();
			$('#editorviewmenucontainer').load('editor/editor_externalmenueditorforms.php?page=2&menuname='+menuname+'&action=edit&navid='+navid);	
			return false;
	  }));

	  // function to delete the menu item
	  $(".deletenavmenuitems").live('click',(function() {
			if (confirm('Are you sure to remove the menu item')) {
		  		var navid		= $(this).attr('id'); 
		  		var menuname 	= $('#txtmenuname').val();
		  		$('#navid_'+navid).hide();
		  		$('#menueditorresult').load('editor/editor_externalmenueditorformprocessor.php?type=2&navid='+navid+'&menuname='+menuname);
		  	}
			return false;
	  }));


		// function to update the menu settings
		$('#btn_editor_updateSettings').live('click',(function(){
			$.ajax({
				url: "editor/editor_externalmenueditorformprocessor.php?type=3",	
				type: "POST",
				data: $("#frmexternalmenueditor").serialize(),
				cache: false,
				dataType:'html',			
				success: function(html) {
					var resval 	= html.split(/~/);
					if(resval[0] == 'success'){
						menuid = $('#txtmenuname').val();
						menuclass = resval[1];
						$("#"+menuid+" ul").removeClass().addClass(menuclass);
						$('#menueditorresult').html('<span class="msggreen">Successfully updated the settings</span>');
					}
				}
		 	});
			return false;
		}));	 


		
	 
});
 
</script>
 	<a href="" class="loadPage" id="2"  >Add Menu Item</a> | <a href="" class="loadPage" id="3">View Menu Items</a> | <a href="" class="loadPage linkactive" id="1">Menu Settings</a>
 	<br><br>
 	<form name="frmexternalmenueditor" id="frmexternalmenueditor">
 		<div id="menueditorresult"></div>
 		<input type="hidden" name="txtpagetype" id="txtpagetype" value="<?php echo $pageType;?>">
 		<input type="hidden" name="txtparentbox" id="txtparentbox" value="<?php echo $parentBox;?>">
 		<input type="hidden" name="txtmenuname" id="txtmenuname" value="<?php echo $menuName;?>">
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