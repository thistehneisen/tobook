<?php
include "includes/session.php";
include 'includes/config.php';
//  echopre($_GET);
$imageEditorType = $_GET['type'];


 //TODO: need to load the user site id from session 
 $siteId = 1;
 
 $imgtagIdforReplace = $_GET['param'];
 
 $currentPage = $_SESSION['siteDetails']['currentpage'] ;
 
 if($imageEditorType == 3)	// image settings page
 {
 	?>
 	<script type="text/javascript" >
		BASE_URL = '<?php echo BASE_URL;?>';

		// update the image link
		$('#btn_editor_imageaddlink').live('click', function()	{
			replaceid		= $('#param').val();
			imgLink 		= $("#txt_editor_edit_image_addlink").val();
			sitepage 		= $("#sitepage").val();
			// ajax call to update the image
    		var data = "replaceid="+replaceid+"&imgLink="+ imgLink+"&sitepage="+ sitepage;
    		$.ajax({url: "editor/editor_updateeditedimagelink.php",	
					type: "POST",data: data ,cache: false,dataType:'html',			
					success: function(html) {	
						alert(html);
						/*
						if(html == 'success'){
							$("#"+replaceid).attr("src",newImg);
							$('#opendialogbox').dialog('close');
							return false;
						}
						*/		
					}			            
				});
    		return false;
		});

			
 	</script>
 	<div id="editor_editimagelink_section">
 	<form>
 	<div>Add Your Link</div>
 	<input type="text" name="txt_editor_edit_image_addlink" id="txt_editor_edit_image_addlink"><br>
 	
 	<input type="button" name="btn_editor_imageaddlink" id="btn_editor_imageaddlink" value="Update"> 
 	<input type="hidden" name="param" id="param" value="<?php echo $imgtagIdforReplace;?>"> 
 	<input type="hidden" id="sitepage" name="sitepage" value="<?php echo $currentPage;?>">
 	</form>
 	
 	</div>
 	
 	<?php 
  }
 else {						// change the image
 	
  
?>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" >
	BASE_URL = '<?php echo BASE_URL;?>';
	 
	$(document).ready(function() { 
    	$('#btnsubmit').live('click', function(){ 

        	imgVal = $("#photoimg").val();
        	if(imgVal == '') {
        		$("#preview").html('<span class="msgred">Please select the image</span>');
        		return false;
        	}
        	$("#btnsubmit").attr("disabled", true);
			$("#preview").html('');
			$("#preview").html('<img src="loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({
						target: '#imgname',
						success: showResponse
				}).submit();
		});	
    	 $('#editor_imguploader').hide();
    	 $('#editorimagegallery').load('editor/editor_loadusergallery.php?action=gallery');
    	 
	}); 

    $(".jqloadPage").live('click',(function() {
    	
    	$("#showuserimageuploader").removeClass('linkactive');
    	$(".jqloadPage").removeClass('linkactive');
		$(this).addClass('linkactive');
        
    	var action	= $(this).attr('id');   
        $('#editor_imguploader').hide();
        $('#editorimagegallery').show();
        $('#editorimagegallery').load('editor/editor_loadusergallery.php?action='+action);
        return false;
    }));



	
	/* cancel the uploaded image and load the uploader
	*/
	$('#cancelUpImage').live('click', function()	{
		$('#editor_imguploader').show();
		$("#preview").html('');
		return false;
	});
	
	/* update the parent user image
	*/
	$('#actUseImage').live('click', function()	{

			// assigning the new image
 			replaceid	= $('#param').val();
			newImg 		= $("#imagename").val();
			sitepage 		= $("#sitepage").val();

    		// ajax call to update the image
    		var data = "replaceid="+replaceid+"&newimg="+ newImg+"&sitepage="+ sitepage;
    		 
    		$.ajax({
					url: "editor/editor_updateeditedimage.php",	
					type: "POST",
					data: data ,
					cache: false,
					dataType:'html',			
					success: function(html) {	
						
						if(html == 'success'){
							$("#"+replaceid).attr("src",newImg);
							$('#opendialogbox').dialog('close');
							return false;
						}		
					}			            
				});
    		return false;
		});


	/* function to add the result after image uploading
	*/
	function showResponse(){
		$("#btnsubmit").removeAttr("disabled");
		BASE_URL = '<?php echo BASE_URL;?>';
		imgupres = $("#imgname").html();
		var entries = imgupres.split(/~/);
		if(entries[0] == 'success'){
 			
			$('#editor_imguploader').hide();
			cancelLink = '&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="cancelUpImage">Cancel</a>';
			$("#preview").html('<img src="' +BASE_URL+entries[1]+'"><br><a href="#" id="actUseImage">Use Image</a>'+cancelLink); 
			$("#imagename").val(entries[1]); 
			$("#photoimg").val('');
		}
		else{
			$("#preview").html('Some error occured');
		}	 
	}
</script>
 
<div >
<form id="imageform" method="post" enctype="multipart/form-data" action='editor_imageuploader.php'>

<a href="" id="gallery" class="jqloadPage linkactive">View Gallery</a> | <a href="" id="tempimages" class="jqloadPage">Template Images</a> | <a href="" id="myuploads" class="jqloadPage">My Uploads</a> | <a href="" id="showuserimageuploader">Upload Image</a>  


<div id="editorimagegallery"></div>
<div id="editor_imguploader">
<br>
	<table width="100%"  border="0" cellspacing="0" class="pageadd_tbl" cellpadding="0">
	
  <tr>
    <td>Choose your image</td>
    <td><input type="file" name="photoimg" id="photoimg" /></td>
  </tr>
</table>



<!--  <input type="button" value="Submit" name="btnsubmit" id="btnsubmit"> --> 
   <div class="popupeditpanel_ftr">
     <input type="button" name="btnsubmit" id="btnsubmit" value="Upload" class="popup_orngbtn right">
              </div>
<input type="hidden" name="type" value="<?php echo $_GET['type'];?>">
<input type="hidden" name="userid" value="<?php echo $_SESSION["session_userid"];?>">
<input type="hidden" name="siteid" value="<?php echo $siteId;?>">
</div>
<input type="hidden" name="param" id="param" value="<?php echo $_GET['param'];?>"> 
<input type="hidden" id="imagename" name="imagename" value="">
<input type="hidden" id="sitepage" name="sitepage" value="<?php echo $currentPage;?>">
</form>
<div id='preview' ></div>
<div  id="imgname" style="display:none"></div>


<input type="hidden" name="oldimgname" id="oldimgname" value="<?php echo $_GET['param'];?>"> 

</div> 

<?php } ?>
