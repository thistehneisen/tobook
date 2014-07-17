<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to edit the built in social link data type                      |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php";
 
// echo "<pre>";
// print_r($_GET);
 
$param = $_GET['param'];
if($param != ''){
 //	$params = explode('_',$param); 
?>



<script type="text/javascript" >
		BASE_URL = '<?php echo BASE_URL;?>';

		// update the panel parameters
		$('#btn_editor_editsociallinks').live('click', function()	{
    		$.ajax({url: "editor/editor_sociallinkformprocessor.php",	
				type: "POST",data: $('#socialboxdataedit').serialize() ,cache: false,dataType:'html',			
				success: function(html) {	
					res = html.toString();						 
					if(res == 1)
						$('#opendialogbox').dialog('close');
					}			            
				});
    		return false;
		});	


		 








	  
	 

	    

	 





		
 	</script>
 
<script src="js/common.js" type="text/javascript"></script>

   
<div id="editor_editexternalbox_section">
 	<form name="socialboxdataedit" id="socialboxdataedit">
 	
 	<?php 
 	$currentPage 	= $_SESSION['siteDetails']['currentpage'];
 	/******* Modified for common footer in all pages ***/
 	//$socialLinkDetails 	= $_SESSION['siteDetails'][$currentPage]['datatypes']['socialshare'][$param] ;
        $socialLinkDetails      =  $_SESSION['siteDetails']['commonpanel']['socialshare'];
        /******* Modified for common footer in all pages ***/       
   	
        ?>
	<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
	<?php 
	
	foreach($socialLinkDetails as $key=>$links) {
		$textBoxName = 'txtsociallink_'.$key;
	 			?>
			
				<tr>
				<td valign="middle" align="left"><img src="<?php echo $links['image'];?>" > </td>
				<td valign="middle" align="left"><input class="textbox_style1" type="text" name="<?php echo $textBoxName?>" id="<?php echo $textBoxName;?>" value="<?php echo $links['link'];?>">
			<!--  <span class="question" title="das dasd das asdasas das asdas">?</span> -->
				</td>
				</tr>
	<?php 	}  	?>
	</table>
 	 
	 <div class="popupeditpanel_ftr">
			<input type="button" name="btn_editor_editsociallinks" id="btn_editor_editsociallinks" value="Update" class="popup_orngbtn right"> 
 	<input type="hidden" name="extboxname" id="extboxname" value="<?php echo $param;?>">
			<div class="clear"></div>
			</div>
 	
  	</form>
 	
 	</div>
 	
 	<?php 
}
else {
	echo "Error....";
}
 	
 	?>