<?php 
/*  
 *  // | Authors: Jinson<jinson.m@armiasystems.com>	        		              |
 * 		this page is to load the slide show images
 * 
 */
include "../includes/session.php";
include "../includes/config.php";
?>
<script type="text/javascript" >
		BASE_URL = '<?php echo BASE_URL;?>';
		// update the image link
		$('.jqUseSlideImage').live('click', function()	{
			var imgid	=$(this).attr('id'); 
 
			$(".edtrimggallerylist ").removeClass('edtrimggallerylistactive');
 
			$(this).parent('div:first').addClass('edtrimggallerylistactive');
			$("#imagename").val(imgid);
    		return false;
		});
 	</script>
 
<?php
// echo "<pre>";
// print_r($_GET);
$galleryType 	= $_GET['action'];
$sliderid		= $_GET['slider'];
//echo "<pre>";
// echo $galleryType;
 $currentPage = $_SESSION['siteDetails']['currentpage'] ;
 if($galleryType == 'slideshow' && $currentPage != ''){		 	// slide show images
 	echo '<br>';
	$galleryImage = $_SESSION['siteDetails'][$currentPage]['apps'][$sliderid]['images'];
	if(sizeof($galleryImage) > 0){
		foreach($galleryImage as $key=>$images) {
			$imgUrl = $images['image'];
			$removeLink = '<br><a href="#" class="jqremslideimg" id="'.$sliderid.':'.$key.'">remove</a>';
		   	echo '<div class="edtrimggallerylist" id="img_'.$key.'"><a href="" class="jqUseSlideImage" id="'.$imgUrl.'"> <img src="'.$imgUrl.'"   class="edtrimggalleryimg" > </a>'.$removeLink.' </div>';	 
		}
	}
  	else echo 'No images in the slideshow. <a href="" id="showuserimageuploader">Add image</a> ';
}
elseif($galleryType == 'gallery' && $currentPage != ''){	
	echo '<div class="msg_usergallery">	Click and select an image to use it</div>';
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
elseif($galleryType == 'myuploads' && $currentPage != ''){	
	echo '<div class="msg_usergallery">	Click and select an image to use it</div>';
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
			echo '<br>No images uploaded. <a href="" id="showuserimageuploader">Upload image</a>';
	}
}
elseif($galleryType == 'settings' && $currentPage != ''){	
	
	$arrSettingsInfo = $_SESSION['siteDetails'][$currentPage]['apps'][$sliderid]['settings'];
	 
	$slideHeight 	= $arrSettingsInfo['height'];
	$slideWidth 	= $arrSettingsInfo['width'];
	$slideDelay		= $arrSettingsInfo['delay'];
	
	
        echo '<div id="settingsresult"></div><br>
		<table    border="0" cellspacing="0" class="pageadd_tbl" cellpadding="0">
		<tr>
			<td width="20%">Width </td>
			<td width="30%"><input class="textbox_style2" type="text" name="width" value="'.$slideWidth.'" size="10" ></td>
			<td width="50%">width of the slider<td>
			</tr>
<tr><td>Height</td><td><input type="text" class="textbox_style2" name="height" value="'.$slideHeight.'" size="10"></td><td>height of the slider<td></tr>
<tr><td>Delay</td><td><input type="text" class="textbox_style2" name="delay" value="'.$slideDelay.'" size="10"></td><td>delay between transitions<td></tr>
</table>  <div class="popupeditpanel_ftr">
                <input type="button" name="btnSliderUpdate" id="btnSliderUpdate" value="Update" class="popup_orngbtn right ">
                <input name="btnPanelCancel" id="btnPanelCancel" type="button" value="Cancel" class="popup_greybtn right jqPanelCancel">
               
            </div>
               ';
}
?>


