<?php

 	include 'includes/config.php';
	//$path = EDITOR_USER_IMAGES;
 	 
	$uploadStatus = uploadNewFile($_FILES);
	echo $uploadStatus;

	//echopre($_POST);
	
	
	/*
	 * function to upload images 
	 */
	function uploadNewFile($upfiles){
		$userId = $_POST['userid'];
		$path = EDITOR_USER_IMAGES;
 
		$type = $_POST['type'];
		
		$valid_formats = array("jpg", "png", "gif", "bmp");
		if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST")	{
			$name = $upfiles['photoimg']['name'];
			$size = $upfiles['photoimg']['size'];
			
			if(strlen($name))	{
				list($txt, $ext) = explode(".", $name);
				if(in_array(strtolower($ext),$valid_formats))	{
					if($size<(1024*1024))	{
						$actual_image_name = time().substr(str_replace(" ", "_", $txt), 5).".".$ext;
						$tmp = $upfiles['photoimg']['tmp_name'];
						
						$upImage = $path.$actual_image_name;
						
						if(move_uploaded_file($tmp,$upImage ))	{	
							
						
							if (file_exists($upImage)){
								
								if (!file_exists($path.'thumb/')) {
    								mkdir($path.'thumb/');
								}


								list($width, $height, $type, $attr) = getimagesize($upImage);
							 	if($width > 150 ){
							 		
							 		
							 		include('includes/SimpleImage.php');
									$image = new SimpleImage();
									$image->load($upImage);
									$image->resizeToWidth(150);
									$image->save($path.'thumb/'.$actual_image_name);
								}
								else {
									copy($upImage, $path.'thumb/'.$actual_image_name);
								}
							}
							// insert the image details to database
							
							$imgsql 	= "Select img_id from tbl_useruploadimages where user_id=".$userId." AND image_name='".$actual_image_name."' ";
							$resimg 	= mysql_query($imgsql) or die(mysql_error());
							if(mysql_num_rows($resimg) <=  0) {
							
							
								$sqlAddImg = "INSERT INTO  tbl_useruploadimages(user_id,site_id,image_name,added_on,img_type,status)
										 VALUES(".$userId.",".$_POST['siteid'].",'".$actual_image_name."','".time()."',".$type.",0)";
								$result = mysql_query($sqlAddImg) or die(mysql_error());
							}
							// image resizing function
							
							 
							$imgDet = 'success~'.$path.'thumb/'.$actual_image_name;
							return $imgDet;
						}
						else
							return "error~failed";
					}
					else
						return "error~Image file size max 1 MB";					
				}
				else
					return "error~Invalid file format..";	
			}	
			else
				return "error~Please select image..!";
			exit;
		}
	}
?>