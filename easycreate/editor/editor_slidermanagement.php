<?php
//session_start();
include "../includes/session.php";
include '../includes/config.php';
 
 
$currentPage = $_SESSION['siteDetails']['currentpage'] ;
  //TODO: need to load the user site id from session 
$siteId 	= 1;
$sliderId 	= $_GET['params'];
$sliderBox 	= $_GET['param'];
  // echopre($_GET);
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
			$("#preview").html('<img src="images/loader.gif" alt="Uploading...."/>');
			$("#imageform").ajaxForm({
						target: '#imgname',
						success: showResponse
				}).submit();
		});	
    	 $('#editor_imguploader').hide();
    	 $('#editorimagegallery').load('editor/editor_slidergallery.php?action=slideshow&slider=<?php echo $sliderId;?>'); 


  		}); 
		
    	$(".jqPanelCancel").live('click',(function() {
    		$('#opendialogbox').dialog('close');
			return false;
    	}));

	
    $(".jqloadSlidePage").live('click',(function() {
    	$("#showuserimageuploader").removeClass('linkactive');
    	$(".jqloadSlidePage").removeClass('linkactive');
		$(this).addClass('linkactive');
    	var action	=$(this).attr('id'); 
    	var sliderId = $('#sliderid').val();
        $('#editor_imguploader').hide();
        $('#editorimagegallery').show();
        $('#editorimagegallery').load('editor/editor_slidergallery.php?action='+action+'&slider='+sliderId);
        return false;
    }));

    /* function to add the result after image uploading
	*/
	function showResponse(){
		$("#btnsubmit").removeAttr("disabled");
		BASE_URL 	= '<?php echo BASE_URL;?>';
		imgupres 	= $("#imgname").html();
		var entries = imgupres.split(/~/);
		
		if(entries[0] == 'success'){
			$('#editor_imguploader').hide();
			cancelLink = '&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" id="cancelUpImage">Cancel</a>';
			$("#preview").html('<img src="' +BASE_URL+entries[1]+'"><br><a href="#" id="actUseImage">Use Image</a>'+cancelLink); 
			$("#imagename").val(entries[1]); 
			$("#photoimg").val('');
			
		}
		else{
			$("#preview").html('<span class="msgred">Some error occured<span>');
		}	 
	}

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
 			sliderid	= $('#sliderid').val();	
			newImg 		= $("#imagename").val();
    		// ajax call to update the image
    		var data = "sliderid="+sliderid+"&newimg="+ newImg+"&action=add";    		 
    		 
    		$.ajax({
					url: "editor/editor_sliderformprocessor.php",	
					type: "POST",
					data: data ,
					cache: false,
					dataType:'html',			
					success: function(html) {	
						// alert(html);
 						if(html == 'success'){
							$("#preview").html('');
							$('#editor_imguploader').hide();
						    $('#editorimagegallery').show();
						    
						    $('#slideshow').addClass('linkactive');
						    $("#showuserimageuploader").removeClass('linkactive');
						    $('#editorimagegallery').load('editor/editor_slidergallery.php?action=slideshow&slider='+sliderid);

							//var sliderbox = $('#slidermainbox').val();
							//alert(sliderid);
					 		$('#editpanel_'+sliderid).load('editor/editor_ajaxcallresult.php?type=loadsliderimage&slider='+sliderid);
					 		//alert('editor/editor_ajaxcallresult.php?type=loadsliderimage&slider='+sliderid);

							return false;
						}		
					}			            
				});
    		return false;
		});

	// remove the image from gallery
	$('.jqremslideimg').live('click',function(){
                        sliderid = $('#sliderid').val();	
			var remid = $(this).attr('id');
			var entries = remid.split(/:/);
			var data = "sliderid="+entries[0]+'&imgkey='+entries[1]+'&action=delete';
			$.ajax({
				url:"editor/editor_sliderformprocessor.php",
				type: "POST",
				data: data,
				cache:false,
				dataType:'html',
				success:function(html){
					$('#img_'+entries[1]).remove();
                                        $('#editpanel_'+sliderid).load('editor/editor_ajaxcallresult.php?type=loadsliderimage&slider='+sliderid);
				}
			});
			
			return false;
		 });

	$('#btnSliderUpdate').live('click',function(){
		$.ajax({
			url:"editor/editor_sliderformprocessor.php",
			type: "POST",
			data: $("#imageform").serialize()+'&action=settings',
			cache:false,
			dataType:'html',
			success:function(html){
				if(html == 'success'){
					$('#settingsresult').html('<span class="msggreen">Successfully updated the settings</span>');
				}
			}
		});
		
		return false;
	 });	
	


	    
</script>
<body>
<div >
<form id="imageform" method="post" enctype="multipart/form-data" action='editor_imageuploader.php'>

<a href="" id="slideshow" class="jqloadSlidePage linkactive"><?php echo SLIDER_SHOW;?></a> |
 <a href="" id="gallery" class="jqloadSlidePage"><?php echo SLIDER_SHOW_GALLERY;?></a> | 
 <a href="" id="myuploads" class="jqloadSlidePage"><?php echo SLIDER_UPLOADS;?></a> | 
 <a href="" id="showuserimageuploader"><?php echo SLIDER_UPLOAD_IMAGE;?></a> | 
 <a href="" id="settings" class="jqloadSlidePage"><?php echo SLIDER_SETTINGS;?></a>

<div id="editorimagegallery"></div>
<div id="editor_imguploader">
<br>
	<table width="100%"  border="0" cellspacing="0" class="pageadd_tbl" cellpadding="0">
	
  <tr>
    <td><?php echo SLIDER_CHOOSE_IMAGE;?></td>
    <td><input type="file" name="photoimg" id="photoimg" /></td>
  </tr>
</table>



<!--  <input type="button" value="Submit" name="btnsubmit" id="btnsubmit"> --> 
   <div class="popupeditpanel_ftr">
     <input type="button" name="btnsubmit" id="btnsubmit" value="Upload" class="popup_orngbtn right">
              </div>
 <input type="hidden" name="type" value="3">
<input type="hidden" name="userid" value="<?php echo $_SESSION["session_userid"];?>">
<input type="hidden" name="siteid" value="<?php echo $siteId;?>">
</div>
<input type="hidden" name="sliderid" id="sliderid" value="<?php echo $sliderId;?>"> 
<input type="hidden" name="sliderbox" id="sliderbox" value="<?php echo $sliderBox;?>"> 
<input type="hidden" id="imagename" name="imagename" value="">
<input type="hidden" id="sitepage" name="sitepage" value="<?php echo $currentPage;?>">
</form>
<div id='preview' ></div>
<div  id="imgname" style="display:none"></div>
 

</div> 

 
 
