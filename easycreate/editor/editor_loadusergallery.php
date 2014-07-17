<?php
include "../includes/session.php";
include "../includes/config.php";
?>
<script type="text/javascript" >
		BASE_URL = '<?php echo BASE_URL;?>';
		// update the image link
		// update the image link
		$('.jqUseSlideImage').live('click', function()	{
			var imgid	=$(this).attr('id'); 
 
			$(".edtrimggallerylist ").removeClass('edtrimggallerylistactive');
 
			$(this).parent('div:first').addClass('edtrimggallerylistactive');
			$("#imagename").val(imgid);
    		return false;
		});
		/*
		$('.usegalleryimage').live('click', function()	{
			// assigning the new image
 			replaceid	= $('#oldimgname').val();
			newImg 		= $(this).attr('id');

    		// ajax call to update the image
    		var data = "replaceid="+replaceid+"&newimg="+ newImg;
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
		*/
 	</script>
 <div class="msg_usergallery">	Click and select an image to use it</div>
<?php 
/*  
 *  // | Authors: Jinson<jinson.m@armiasystems.com>	        		              |
 * 		this page is to load the user uploaded images
 * 
 */

//echo "<pre>";
//print_r($_GET);
$galleryType = $_GET['action'];
//echo "<pre>";
//print_r($_SESSION);

if($galleryType == 'myuploads') {			// user uploaded images
	//echo 'My Uploaded Images<br>';
	$userid = $_SESSION['session_userid'];
	if($userid != '') {
		
		$imgsql 	= "Select * from tbl_useruploadimages where user_id=".$userid." GROUP BY image_name ORDER BY img_id DESC ";
		$resimg 	= mysql_query($imgsql) or die(mysql_error());
		if(mysql_num_rows($resimg) > 0) {
			while($rowimg = mysql_fetch_assoc($resimg)) {	
				$imgUrl = BASE_URL.EDITOR_USER_IMAGES.$rowimg['image_name'];
				echo '<div class="edtrimggallerylist"><a href="" class="jqUseSlideImage" id="'.$imgUrl.'"><img src="'.$imgUrl.'"  class="edtrimggalleryimg" > </a> </div>';
			}
			echo ' <div class="popupeditpanel_ftr"><input type="button" name="actUseImage" id="actUseImage" value="Add Image" class="popup_orngbtn right "></div>';
			
		}
		else
			echo '<br>No Images in gallery. <a href="" id="showuserimageuploader">Upload image</a>';
	}
}
else if($galleryType == 'gallery'){									// system gallery images
	
	//echo "Image gallery<br>";
	$galleryFolder = 'samplelogos/';
	$folder 	= opendir("../".$galleryFolder); 
    $pic_types 	= array("jpg", "jpeg", "gif", "png");
    $index 		= array();
    while ($file = readdir ($folder)) {
	    if(in_array(substr(strtolower($file), strrpos($file,".") + 1),$pic_types))   {
	   		 $imgUrl = BASE_URL.$galleryFolder.$file;
	   		 echo '<div class="edtrimggallerylist"><a href="" class="jqUseSlideImage" id="'.$imgUrl.'"> <img src="'.$imgUrl.'"   class="edtrimggalleryimg" > </a> </div>';	 
	    }
	}  
		echo ' <div id="editor_plaineditor" > <div class="popupeditpanel_ftr"><input type="button" name="actUseImage" id="actUseImage" value="Add Image" class="popup_orngbtn right "></div></div>';
	
    closedir($folder);
}
else if($galleryType == 'tempimages'){				// load template images
	$templatePath		= '../'.$_SESSION["session_template_dir"] . "/" . $_SESSION['siteDetails']['templateid'].'/'; 
	$tempImgList 		= recursiveFileList($templatePath);
	if(sizeof($tempImgList) > 0){
		foreach($tempImgList as $images) {
			$imgUrl = str_replace('../','',$images)  ;
			$imgUrl = BASE_URL.$imgUrl;
	   		echo '<div class="edtrimggallerylist"><a href="" class="jqUseSlideImage" id="'.$imgUrl.'"> <img src="'.$imgUrl.'"   class="edtrimggalleryimg" > </a> </div>';	 
		}
		echo ' <div class="popupeditpanel_ftr"><input type="button" name="actUseImage" id="actUseImage" value="Add Image" class="popup_orngbtn right "></div>';
		
	}
	else echo "No template images";
}

?>


