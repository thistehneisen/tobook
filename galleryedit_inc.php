<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+

/* create a directory  tmpeditimages/$userid/
* this will be the user's image editor folder
*/ 
ini_set("memory_limit","256M");
if (!is_dir("./tmpeditimages/$userid/")) {
				mkdir("./tmpeditimages/$userid/", 0777);
				chmod("./tmpeditimages/$userid/", 0777);
} 

$version = substr(phpversion(), 0, 1);
/* make the gif image transparent.*/ 
function MakeTransparentgifimage($newImage)
{
				list($width, $height, $type, $attr) = @getimagesize($newImage);
				$tosavefileas = $newImage;

				if ($type == "1") {
								$newwidth = $width;
								$newheight = $height;
								$img_temp = NewimageCreatefromtype($newImage); 
								// $returnimage= @imagegif ($newImage, $newImage);
								$tpcolor = imagecolorat($img_temp, 0, 0); 
								// in the real world, you'd better test all four corners, not just one!
								$img_thumb = imagecreate($newwidth, $newheight); 
								// $dest automatically has a black fill...
								imagepalettecopy($img_thumb, $img_temp);
								imagecopyresized($img_thumb, $img_temp, 0, 0, 0, 0, $newwidth, $newheight,
												@imagesx ($img_temp), @imagesy($img_temp));
								$pixel_over_black = imagecolorat($img_thumb, 0, 0); 
								// ...but now make the fill white...
								$bg = imagecolorallocate($img_thumb, 255, 255, 255);
								imagefilledrectangle($img_thumb, 0, 0, $newwidth, $newheight, $bg);
								imagecopyresized($img_thumb, $img_temp, 0, 0, 0, 0, $newwidth, $newheight,
												@imagesx ($img_temp), @imagesy($img_temp));
								$pixel_over_white = imagecolorat($img_thumb, 0, 0); 
								// ...to test if transparency causes the fill color to show through:
								if ($pixel_over_black != $pixel_over_white) {
												// Background IS transparent
												imagefilledrectangle($img_thumb, 0, 0, $newwidth, $newheight,
																$tpcolor);
												imagecopyresized($img_thumb, $img_temp, 0, 0, 0, 0, $newwidth,
																$newheight, @imagesx ($img_temp), @imagesy($img_temp));
												imagecolortransparent($img_thumb, $tpcolor);
												imagegif($img_thumb, $tosavefileas);
								} else // Background (most probably) NOT transparent
												imagecolortransparent($img_thumb, $tpcolor);
								imagegif($img_thumb, $tosavefileas);
				} 
} 
function gdVersion($user_ver = 0)
{
				if (! extension_loaded('gd')) {
								return;
				} 
				static $gd_ver = 0; 
				// Just accept the specified setting if it's 1.
				if ($user_ver == 1) {
								$gd_ver = 1;
								return 1;
				} 
				// Use the static variable if function was called previously.
				if ($user_ver != 2 && $gd_ver > 0) {
								return $gd_ver;
				} 
				// Use the gd_info() function if possible.
				if (function_exists('gd_info')) {
								$ver_info = gd_info();
								preg_match('/\d/', $ver_info['GD Version'], $match);
								$gd_ver = $match[0];
								return $match[0];
				} 
				// If phpinfo() is disabled use a specified / fail-safe choice...
				if (preg_match('/phpinfo/', ini_get('disable_functions'))) {
								if ($user_ver == 2) {
												$gd_ver = 2;
												return 2;
								} else {
												$gd_ver = 1;
												return 1;
								} 
				} 
				// ...otherwise use phpinfo().
				ob_start();
				phpinfo(8);
				$info = ob_get_contents();
				ob_end_clean();
				$info = stristr($info, 'gd version');
				preg_match('/\d/', $info, $match);
				$gd_ver = $match[0];
				return $match[0];
} // End gdVersion()
// Usage:
if ($gdv = gdVersion()) {
				$gdsupport = "1";
} else {
				$gdsupport = "0";
				echo "<script>location.href='sitemanager.php';</script>";
				exit; 
	
} 
/* rotate gif image*/

function MyimageRotateGif($src_img, $angle, $bicubic = false)
{
				$black = @imagecolorallocate ($src_img, 0, 0, 0);
				$white = @imagecolorallocate ($src_img, 255, 255, 255);
				$rotate = imagerotate ($src_img, $angle, $black);
				imagecolortransparent($rotate, 0);

				return($rotate);
} 
function MyimageRotate($src_img, $angle, $bicubic = false)
{
			
				$angle = $angle + 180;
				$angle = deg2rad($angle);
				$src_x = @imagesx($src_img);
				$src_y = @imagesy($src_img);
				$center_x = floor($src_x / 2);
				$center_y = floor($src_y / 2);
				$cosangle = cos($angle);
				$sinangle = sin($angle);
				$corners = array(array(0, 0), array($src_x, 0), array($src_x, $src_y), array(0, $src_y));
				foreach($corners as $key => $value) {
								$value[0] -= $center_x; //Translate coords to center for rotation
								$value[1] -= $center_y;
								$temp = array();
								$temp[0] = $value[0] * $cosangle + $value[1] * $sinangle;
								$temp[1] = $value[1] * $cosangle - $value[0] * $sinangle;
								$corners[$key] = $temp;
				} 

				$min_x = 1000000000000000;
				$max_x = -1000000000000000;
				$min_y = 1000000000000000;
				$max_y = -1000000000000000;

				foreach($corners as $key => $value) {
								if ($value[0] < $min_x)
												$min_x = $value[0];
								if ($value[0] > $max_x)
												$max_x = $value[0];

								if ($value[1] < $min_y)
												$min_y = $value[1];
								if ($value[1] > $max_y)
												$max_y = $value[1];
				} 

				$rotate_width = round($max_x - $min_x);
				$rotate_height = round($max_y - $min_y);

				$rotate = @imagecreatetruecolor($rotate_width, $rotate_height);
				@imagealphablending($rotate, false);
				@imagesavealpha($rotate, true); 
				// Reset center to center of our image
				$newcenter_x = ($rotate_width) / 2;
				$newcenter_y = ($rotate_height) / 2;

				for ($y = 0; $y < ($rotate_height); $y++) {
								for ($x = 0; $x < ($rotate_width); $x++) {
												// rotate...
												$old_x = round((($newcenter_x - $x) * $cosangle + ($newcenter_y - $y) * $sinangle)) + $center_x;
												$old_y = round((($newcenter_y - $y) * $cosangle - ($newcenter_x - $x) * $sinangle)) + $center_y;

												if ($old_x >= 0 && $old_x < $src_x && $old_y >= 0 && $old_y < $src_y) {
																$color = @imagecolorat($src_img, $old_x, $old_y);
												} else {
																// this line sets the background colour
																$color = @imagecolorallocatealpha($src_img, 255, 255, 255, 127);
												} 
												@imagesetpixel($rotate, $x, $y, $color); 
												// imagecolortransparent($rotate, 0);
								} 
				} 

				return($rotate);
} 
function ImagetypeReturn($newImage, $newfile, $editimagefile)
{

				list($width, $height, $type, $attr) = @getimagesize($editimagefile);
				$jpgCompression = "90";
				if ($type == "1") { // gif
								$returnimage = @imagegif($newImage, $newfile);
				} else if ($type == "2") { // jpeg
								$returnimage = @imagejpeg($newImage, $newfile, $jpgCompression);;
				} else if ($type == "3") { // png
								$returnimage = @imagepng($newImage, $newfile);
				} else {
								$returnimage = "Not Supported";
				} 
				return $returnimage;
} 

function CutImageNew($file, $img_height, $waterMark)
{
	
				$img_temp = NewimageCreatefromtype($file);
				$black = @imagecolorallocate ($img_temp, 0, 0, 0);
				$white = @imagecolorallocate ($img_temp, 255, 255, 255);
				$font = 2;
	
				$img_width = @imagesx($img_temp) / imagesy($img_temp) * $img_height;
            
				$img_thumb = @imagecreatetruecolor($img_width, $img_height);
										
				@imagecopyresampled($img_thumb,
								$img_temp, 0, 0, 0, 0, $img_width,
								$img_height,
								@imagesx ($img_temp),
								@imagesy($img_temp));
				$originx = @imagesx($img_thumb) - 100;
				$originy = @imagesy($img_thumb) - 15;
				@imagestring ($img_thumb, $font, $originx + 10, $originy, $waterMark, $black);
				@imagestring ($img_thumb, $font, $originx + 11, $originy - 1, $waterMark, $white); 
				// header ("Content-type: image/jpeg");
				ImagetypeReturn($img_thumb, $file, $file); 
				// imagejpeg($img_thumb, $file, 60);
} 
function ImageFlip($imgsrc, $type)
{
				$width = @imagesx($imgsrc);
				$height = @imagesy($imgsrc);

				$imgdest = @imagecreatetruecolor($width, $height);
				$imgdest1 = @imagecreatetruecolor($width, $height);
				switch ($type) {
								// mirror wzgl. osi
								case IMAGE_FLIP_VERTICAL:

												for($y = 0 ; $y < $height ; $y++)
												@imagecopy($imgdest, $imgsrc, 0, $height - $y-1, 0, $y, $width, 1);
												break;

								case IMAGE_FLIP_HORIZONTAL:
												for($x = 0 ; $x < $width ; $x++)
												@imagecopy($imgdest, $imgsrc, $width - $x-1, 0, $x, 0, 1, $height);
												break;

								case IMAGE_FLIP_BOTH:
					

												for($y = 0 ; $y < $height ; $y++) 
												//  @imagecopy ($imgdest, $imgsrc, 0, $height-$y-1, 0, $y, $width, 1);
												@imagecopy($imgdest1, $imgsrc, 0, $height - $y-1, 0, $y, $width, 1); 
												// break;
												for($x = 0 ; $x < $width ; $x++) 
												// @imagecopy($imgdest, $imgsrc, $width-$x-1, 0, $x, 0, 1, $height);
												@imagecopy($imgdest, $imgdest1, $width - $x-1, 0, $x, 0, 1, $height); 
												// break;
												break;
				} 

				return($imgdest);
} 
if ($_POST['btn_savechanges'] == "Save Changes") {
				$userid = $_SESSION["session_userid"];
				if ($_SESSION['currentimage'] != "") {
								$oldfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage'];
								$newfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage'];

								list($width, $height, $type, $attr) = GetImageSize($newfile);
								if ($type == "1")
												$fileextension = "gif";
								else if ($type == "2")
												$fileextension = "jpg";
								else if ($type == "3")
												$fileextension = "png";
							    //check for watermark required					
								if ($_SESSION['sessallowwatermark'] == "Y") {
												watermarkimage($newfile);
								} 
								// system gallery edit
								if ($_SESSION['sess_edittype'] == "systemgallery") { 
					               //for system gallery image we wikll always create a new image
												$_POST['rdsave'] == "2";
								} 
								if ($_POST['rdsave'] == "1") { // replace existing file
												chmod($newfile, 0777);
												$destimage = "./usergallery/$userid/images/" . $_SESSION['currentimage'];
												copy($newfile, $destimage);
												chmod($destimage, 0777); 
								} else {
								              //create new file
												if (trim($_POST['filename']) == "") {
																$_POST['filename'] = $userid . "_" . time(); 
																$destimage = "./usergallery/$userid/images/ug_" . time() . "." . $fileextension;
												} else {
																$imagetype = getimagetype($type);
																$destimage = "./usergallery/$userid/images/ug_" . addslashes($_POST['filename']) . "." . $imagetype;
												} 
												chmod($newfile, 0777);

												@copy($newfile, $destimage);
												@chmod($destimage, 0777); 
								} 
								$fname = $_SESSION['currentimage']; 
								$datasaved = "Image Successfully saved to your gallery. <a class=editorlinks href='gallerymanager.php'>click here to visit your gallery.</a>";
				} else {
								$datasaved = "Image Not Selected ";
				} 
} else {
                
				/* edittype->either 'systemgallery' or 'usergallery'
				   $fname->name of the filename to be edit
				*/
				$fname = urldecode($_GET['fname']);
             	$edittype = $_GET['edittype'];
				if ($edittype == "")
								$edittype = $_SESSION['sess_edittype'];
				/* if $fname is empty  then set $fname to session 'currentimage'*/			
				if ($fname == "")
								$fname = $_SESSION['currentimage']; 
				/* for the first time user enter this page session 'currentimage' will be null and 
				* $fname will be the name the file to be edit.
				* copy the file to tempeditimage location
				*/ 
				
				if ($_SESSION['currentimage'] == "" and $fname != "") {
								// check whether to edit system gallery or usergallery
								if ($edittype == "systemgallery" or $_SESSION['sess_edittype'] == "systemgallery") {
												$_SESSION['sess_edittype'] = "systemgallery";
												//if the requested file not exist // assing session 'currentimage' to null
												if (!is_file("./systemgallery/" . "$fname")) {
																$_SESSION['currentimage'] = "";
												} else {
												                // 'imagenametodisplay' used to display image name in topd of the editor page
																$_SESSION['imagenametodisplay'] = $fname;
																list($width, $height, $type, $attr) = GetImageSize("./systemgallery/" . "$fname");
																if ($type == "1")
																				$fileextension = "gif";
																else if ($type == "2")
																				$fileextension = "jpg";
																else if ($type == "3")
																				$fileextension = "png";

																$fnametosave = "ug_" . time() . ".$fileextension";
																//copy the selected system gallery images to tmpeditimages/$userid/fnametosave 
																@copy("./systemgallery/" . "$fname", "./tmpeditimages/$userid/" . $fnametosave);
																$fname = $fnametosave;
																// assing session 'currentimage' to fnametosave 
																$_SESSION['currentimage'] = $fname;
												} 
								} else {
								                //if the requested file not exist // assing session 'currentimage' to null 
												if (!is_file("./usergallery/" . $userid . "/images/$fname")) {
																$_SESSION['currentimage'] = "";
												} else {
																$_SESSION['imagenametodisplay'] = $fname;
																$_SESSION['currentimage'] = $fname;
																//copy the selected system usergallery images to tmpeditimages/$userid/fname 
																 @copy("./usergallery/" . $userid . "/images/$fname", "./tmpeditimages/$userid/" . $fname);
												} 
								} 
				} 
} 
/* check for session 'currentimage'.if it is null image is invalid*/ 

if ($_SESSION['currentimage'] == "") {
				$Invalidimage = "TRUE";
				$invalidmessage = "Image Not  Selected";
} else {

                /* build new copy of image for each operation
				*   for eg-> a user start editing image ug_test1.jpg
				*            when he perform an operation ie(rotation) a new copy will be created (ie rotated image)
				*            then he/she try to perform another operation another copy of the image will be created,
				* 			 this process will be continue until he/she save the image.
				*            when user save the image then all files will be deleted.
				* 
				*  $newfile -> image with performed effect(ie rotation).for the fist time it will the copy of the actulal image
				*/   
				$newfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage'];

				/* copy the image to same location as another name.this image will be used for furthor image manupulation*/
     		    @copy($newfile, "./tmpeditimages/$userid/" . time() . $_SESSION['currentimage']);

				$imagetoedit = "./tmpeditimages/$userid/" . time() . basename($newfile); 
	
				list($width, $height, $type, $attr) = @getimagesize($imagetoedit);

				/* only gif,jpg,png image types are allowed for editing
				*  width and height should be less than 1300
				*  width and height are >700 then the image will be cut to 450 height for better image manipulation 
				* 
				*/ 
														
				if ($type > 3) {
								$Invalidimage = "TRUE";
								$invalidmessage = "Image not supported";
				} else if ($width > 5000) {
								$Invalidimage = "TRUE";
								$invalidmessage = "Image size too large for the editor!";
				} else if ($height > 5000) {
								$Invalidimage = "TRUE";
								$invalidmessage = "Image size too large for the editor!";
				} else if ($width > 700) {
                           //echo "iniget==".ini_get("max_execution_time");
						   //echo "<br>iniget==".ini_get("max_input_time");
						   
						   //exit;
						  
						   
				                $waterMark="";

								CutImageNew($imagetoedit, 450, $waterMark);
								 
					
				} else if ($height > 700) {
				                $waterMark="";
								
								CutImageNew($imagetoedit, 450, $waterMark);
								
				} 
} 

if ($Invalidimage != "TRUE") {
				$saveimage = "0";
				/* to avoid duplication when user click refresh button */
				if ($_SESSION["session_checkid"] >= $_POST["checkid"]) {
				   ;
				} else {
								$_SESSION["session_checkid"] = $_POST["checkid"];
								$saveimage = "1" ;
				} 
				/* for all image operations file name to be edited will be passed as 'hfile'
				*  and the action to be performed will be passed as  'haction' either as GET or POST
				*  
				*/ 
				if ($saveimage == "1" and isset($_POST['hfile']) != "" and ($_GET['haction'] == "crop" or $_POST['haction'] == "crop")) {
				                /* create image resourceid for the image to be edited
								   this function will check the image type and return the resourceid according to the type
								   ie imagegif function will be called for gif images
								*/
							
								$image = NewimageCreatefromtype($_POST['hfile']); 
								list($width, $height, $type, $attr) = getimagesize($_POST['hfile']);
								/* if image width or height is greater than 450 we should scale x or y cordinate for the crop
								*   bcs we already resized the image that is greater than 700.this '450' calculated by trial
								* 
								*/ 
								
								$widthpercentage = $width / 450;
								$heightpercentage = $height / 450;
								if ($widthpercentage > 1) {
												$_POST['hex'] = $_POST['hex'] * $widthpercentage;
												$_POST['hsx'] = $_POST['hsx'] * $widthpercentage;
								} 
								if ($heightpercentage > 1) {
												$_POST['hey'] = $_POST['hey'] * $heightpercentage;
												$_POST['hsy'] = $_POST['hsy'] * $heightpercentage;
								} 
								$nx = abs($_POST['hex'] - $_POST['hsx']);
								$ny = abs($_POST['hey'] - $_POST['hsy']);
								/* create blank image resourceid from give x nd y cordinate.*/
								$image_p = @ImageCreateTrueColor($nx, $ny);
								$newfile = @ImageCreateTrueColor($nx, $ny);
								/* 
								* $image_p->destination image (ie newimage)
								* $image -> source image(ie the image to be edit)
								* 0->destination image start x cordinate
								* 0->destination image start y cordinate
								* $_POST['hsx'],$_POST['hsy']->xcordinate,ycordinate for source image(ie from which point we want to crop)
								* $nx ->destination image width
								* $ny->destination image height
								*/ 
								@imagecopyresized($image_p, $image, 0, 0, $_POST['hsx'], $_POST['hsy'], $nx, $ny, $nx, $ny);
								$newfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage'];
								/* $image_p-> will be cropped image,copy this image to $newfile*/
								ImagetypeReturn($image_p, $newfile, $_POST['hfile']);
								/* make the image transpernt if it is gif*/
								MakeTransparentgifimage($newfile);

								@copy($newfile, "./tmpeditimages/$userid/" . time() . $_SESSION['currentimage']);
								$imagetoedit = "./tmpeditimages/$userid/" . time() . basename($newfile);

								$_SESSION['currentimage'] = basename($newfile);

								$currentaction = "crop";
								$_POST['haction'] = "";
				} else if ($saveimage == "1" and isset($_POST['hfile']) != "" and ($_GET['action'] == "addtext" or $_POST['haction'] == "addtext")) {
				                /* create image resourceid for the image to be edited
								   this function will check the image type and return the resourceid according to the type
								   ie imagegif function will be called for gif images
								*/
								$image = NewimageCreatefromtype($_POST['hfile']); 
								/* if image width or height is greater than 450 we should scale x or y cordinate for the crop
								*   bcs we already resized the image that is greater than 700.this '450' calculated by trial
								* 
								*/ 
								list($width, $height, $type, $attr) = @getimagesize($_POST['hfile']);
								$widthpercentage = $width / 450;
								$heightpercentage = $height / 450;
								if ($widthpercentage > 1) {
												$_POST['hsx'] = $_POST['hsx'] * $widthpercentage;
								} 
								if ($heightpercentage > 1) {
												$_POST['hsy'] = $_POST['hsy'] * $heightpercentage;
								} 

								$selectedfont = $_POST['cmbfontname'];
								$font = "./fonts/" . $selectedfont;
								$fontcolor = substr($_POST['hfontcolor'], 1); 
							    //split the color code to red,green,blue
								$hexcolor[0] = substr($fontcolor, 0, 2);
								$hexcolor[1] = substr($fontcolor, 2, 2);
								$hexcolor[2] = substr($fontcolor, 4, 2); 
								// Convert HEX values to DECIMAL
								$bincolor[0] = hexdec("0x{$hexcolor[0]}");
								$bincolor[1] = hexdec("0x{$hexcolor[1]}");
								$bincolor[2] = hexdec("0x{$hexcolor[2]}");
								
								$textcolor = @ImageColorAllocate($image, $bincolor[0], $bincolor[1], $bincolor[2]);

								$text1 = $_POST['htextval']; 
								// Here is our text
								if (get_magic_quotes_gpc()) {
												$text1 = stripslashes($text1);
								} 
								$fontsize = $_POST['cmbfontsize'];
								$angle = $_POST['textangle']; 
								/* imagettftext ( resource image, int size, int angle, int x, int y, int color, string fontfile, string text)
								*  $image->resoure image(ie image to be edit)
								*  $fontsize->font size
								*  $angle -> default  0
								* $_POST['hsx'] ->  starting xcordinate 
								* $_POST['hsy'] ->starting ycordinate
								* $textcolor ->font color
								* $font->font name
								* $text1 ->text to be added on image
								*/ 
								@imagettftext($image, $fontsize, $angle, $_POST['hsx'], $_POST['hsy'] + $fontsize, $textcolor, $font, $text1);
								$newfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage'];
								ImagetypeReturn($image, $newfile, $_POST['hfile']);
								/* make the image transpernt if it is gif*/
								MakeTransparentgifimage($newfile);
								@copy($newfile, "./tmpeditimages/$userid/" . time() . $_SESSION['currentimage']);
								$imagetoedit = "./tmpeditimages/$userid/" . time() . basename($newfile);

								$_SESSION['currentimage'] = basename($newfile);
								$currentaction = "addtext";
				} else if ($saveimage == "1" and isset($_POST['file']) != "" and $_POST['action'] == "applyfilter") {
				                /* older version of gd doesnot support this filtering*/
								$image = NewimageCreatefromtype($_POST['hfile']);
								if ($_POST['hfiltervalue'] == "NG") {
												@imagefilter ($image, IMG_FILTER_NEGATE);
								} else if ($_POST['hfiltervalue'] == "GR") {
												@imagefilter ($image, IMG_FILTER_GRAYSCALE);
								} else if ($_POST['hfiltervalue'] == "BR") {
												$brvalue = $_POST['hbrvalue'];
												@imagefilter ($image, IMG_FILTER_BRIGHTNESS, $brvalue);
								} else if ($_POST['hfiltervalue'] == "CO") {
												$crvalue = $_POST['hcrvalue'];
												@imagefilter ($image, IMG_FILTER_CONTRAST, $crvalue);
								} else if ($_POST['hfiltervalue'] == "CLR") {
												$colorcode = substr($_POST['hcolorcode'], 0); 
												// $hexcolor = str_split($colorcode, 2);
												// Convert HEX values to DECIMAL
												$hexcolor[0] = substr($fontcolor, 0, 2);
												$hexcolor[1] = substr($fontcolor, 2, 2);
												$hexcolor[2] = substr($fontcolor, 4, 2);

												$bincolor[0] = hexdec("0x{$hexcolor[0]}");
												$bincolor[1] = hexdec("0x{$hexcolor[1]}");
												$bincolor[2] = hexdec("0x{$hexcolor[2]}");
												@imagefilter ($image, IMG_FILTER_COLORIZE, $bincolor[0] , $bincolor[1] , $bincolor[2]);
								} else if ($saveimage == "1" and $_POST['hfiltervalue'] == "SM") {
												// echo "heree";
												// exit;
												@imagefilter ($image, IMG_FILTER_SMOOTH, -3000.124);
								} else if ($_POST['hfiltervalue'] == "ED") {
												imagefilter ($image, IMG_FILTER_EDGEDETECT);
								} else if ($_POST['hfiltervalue'] == "EB") {
												@imagefilter ($image, IMG_FILTER_EMBOSS);
								} else if ($_POST['hfiltervalue'] == "BL") {
												@imagefilter ($image, IMG_FILTER_SELECTIVE_BLUR);
								} else if ($_POST['hfiltervalue'] == "SK") {
												@imagefilter ($image, IMG_FILTER_MEAN_REMOVAL);
								} 
								$newfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage'];

								ImagetypeReturn($image, $newfile, $_POST['file']);
								MakeTransparentgifimage($newfile);

								@copy($newfile, "./tmpeditimages/$userid/" . time() . $_SESSION['currentimage']);
								$imagetoedit = "./tmpeditimages/$userid/" . time() . basename($newfile);

								$_SESSION['currentimage'] = basename($newfile);
								$currentaction = "addtext";
				} else if ($saveimage == "1" and isset($_POST['hfile']) != "" and $_POST['haction'] == "rotate") {
								$filename = $_POST['hfile'];
								$degrees = $_POST['hrotatevalue'];
								$source = NewimageCreatefromtype($filename);
								list($width, $height, $type, $attr) = @getimagesize($filename);
								if ($type == "1") {
												$rotate = MyimageRotateGif($source, $degrees);
								} else {
												$rotate = MyimageRotate($source, $degrees);
								} 
								$newfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage']; 
								// echo "making tranparent=".$newfile;
								ImagetypeReturn($rotate, $newfile, $_POST['hfile']);
								MakeTransparentgifimage($newfile);
								@copy($newfile, "./tmpeditimages/$userid/" . time() . $_SESSION['currentimage']);
								$imagetoedit = "./tmpeditimages/$userid/" . time() . basename($newfile);
								$_SESSION['currentimage'] = basename($newfile);
								$currentaction = "rotate";
				} else if ($saveimage == "1" and isset($_POST['hfile']) != "" and $_POST['haction'] == "flip") {
								$filename = $_POST['hfile'];

								$source = NewimageCreatefromtype($filename);
								$type = $_POST['hflipdirection'];
								$flipimage = ImageFlip($source, $type);
								$newfile = "./tmpeditimages/$userid/" . $_SESSION['currentimage'];
								ImagetypeReturn($flipimage, $newfile, $_POST['hfile']);
								MakeTransparentgifimage($newfile);
								@copy($newfile, "./tmpeditimages/$userid/" . time() . $_SESSION['currentimage']);
								$imagetoedit = "./tmpeditimages/$userid/" . time() . basename($newfile);
								$_SESSION['currentimage'] = basename($newfile);
								$currentaction = "flip";
				} 

				$_SESSION['backtoimageediting'] = $_SERVER['REQUEST_URI'];
				require_once './includes/class.cropinterface.php';
				$ci = new cropInterface();

?>
								<script  LANGUAGE="JavaScript" TYPE="text/javascript">
								function changefiltertype(frm){
								
								if(document.getElementById('cmbfiltertype').value=="CLR"){
								   document.getElementById('colorize').style.display="";
								}else{
								     document.getElementById('colorize').style.display="none";
								}	   
								
								}
								function checkradio1(){
								
								 current_cropx=dd.elements.theCrop.x - dd.elements.theImage.x;
								 current_cropy=dd.elements.theCrop.y - dd.elements.theImage.y;
								 croplayer=document.getElementById('theCrop');
								 cropimage=document.getElementById('cropimage');
								 cropimage_w=cropimage.width;
								 cropimage_h=cropimage.height;
								 croplayer.style.width=cropimage_w-current_cropx;
								 croplayer.style.height=dd.elements.theCrop.h;
								 dd.elements.theCrop.w=cropimage_w-current_cropx;
								 dd.elements.theCrop.h=dd.elements.theCrop.h;
								
								
								}  
								function checkradio2(){
								 current_cropx=dd.elements.theCrop.x - dd.elements.theImage.x;
								 current_cropy=dd.elements.theCrop.y - dd.elements.theImage.y;
								 croplayer=document.getElementById('theCrop');
								 cropimage=document.getElementById('cropimage');
								 cropimage_w=cropimage.width;
								 cropimage_h=cropimage.height;
								 croplayer.style.height=cropimage_h-current_cropy;
								 croplayer.style.width=dd.elements.theCrop.w;
								 
								 dd.elements.theCrop.w=dd.elements.theCrop.w;
								 dd.elements.theCrop.h=cropimage_h-current_cropy;
								 
								} 
								function checkradio3(){
								       current_cropx=dd.elements.theCrop.x - dd.elements.theImage.x;
								 current_cropy=dd.elements.theCrop.y - dd.elements.theImage.y;
								 croplayer=document.getElementById('theCrop');
								 cropimage=document.getElementById('cropimage');
								 cropimage_w=cropimage.width;
								 cropimage_h=cropimage.height;
								 croplayer.style.width=cropimage_w-current_cropx;
								 croplayer.style.height=cropimage_w-current_cropx;
								 dd.elements.theCrop.w=cropimage_w-current_cropx;
								 dd.elements.theCrop.h=cropimage_w-current_cropx;
								}   
								</script>	
								
								
								<table width="100%"  border="0" cellspacing="0" cellpadding="1">
								<tr>
								  <td class=maintext><b><?php echo $datasaved; ?></b></td>
								</tr>
								<tr>
								  <td>&nbsp;</td>
								</tr>
								<tr>
								  <td>
								              <table width="100%"  border="0" cellspacing="0" cellpadding="0">
								                         <tr>
								                           <td align="center" align=center VALIGN="top" >
														   <FIELDSET>
															<table width="100%"  border="0" cellpadding="5" cellspacing="0" class=listingmain>
								                                        <tr align="center" class="icontext">
								                                          <td width="4%">&nbsp; </td>
								                                          <td width="20%">
																	
																	<a href="editgallery.php?action=rotate&fname=<?php echo $fname; ?>&" ><img border=0 src="images/imgicon2.gif" width="18" height="18"></a><a href="editgallery.php?action=rotate&fname=<?php echo $fname; ?>&dir=c" ><img border=0 src="images/imgicon3.gif" width="18" height="18"></a></td>
																	<?php if($version>=5 ) {?>
								                                          <td width="9%">
																	 
																	 <a href="editgallery.php?action=applybright&fname=<?php echo $fname; ?>&"><img src="images/imgicon5.gif" width="18" height="18" border=0></a><a href="editgallery.php?action=applybright&fname=<?php echo $fname; ?>&dir=add"> <img border=0 src="images/imgicon4.gif" width="18" height="18"></a>
																	 </td>
																	 <?php }?>
																	 <?php if($version>=5) {?>									
								                                          <td width="20%"><a href="editgallery.php?action=applycontrast&fname=<?php echo $fname; ?>&"><img border=0 src="images/imgicon5.gif" width="18" height="18"></a><a href="editgallery.php?action=applycontrast&fname=<?php echo $fname; ?>&dir=add"> <img border=0 src="images/imgicon4.gif" width="18" height="18"></a></td>
																	<?php }?>
								                                          <td width="5%"><a href="editgallery.php?action=crop&fname=<?php echo urlencode($fname); ?>&"><img border=0 src="images/imgicon6.gif" width="18" height="18"></a></td>
																	
								                                          <td width="5%"><a href="editgallery.php?action=flip&fname=<?php echo $fname; ?>&"><img border=0 src="images/imgicon7.gif" width="18" height="18"></a></td>
																	<?php if($version>=5) {?>	
								                                          <td width="6%"> <a href="editgallery.php?action=applyfilter&fname=<?php echo $fname; ?>&"><img src="images/imgicon8.gif" width="18" height="18" border=0></a></td>
																	<?php }?>
								                                          <td width="4%"> <a href="editgallery.php?action=addtext&fname=<?php echo $fname; ?>&"><img border=0 src="images/imgicon1.gif" width="18" height="18"></a></td>
								                                          <td width="4%">&nbsp;</td>
								                                          <td >&nbsp;</td>
								                                        </tr>
								                                        <tr align="center" valign="top" class=background class="icontext">
								                                          <td>&nbsp;</td>
								                                          <td class=maintext><font color=black>Rotate</font></td>
								                                          <?php if($version>=5){?><td class=maintext><font color=black>Brightness</font></td><?php }?>
								                                          <?php if($version>=5){?><td class=maintext><font color=black>Contrast</font></td><?php }?>
								                                          <td class=maintext><font color=black>Crop</font></td>
								                                          <td class=maintext><font color=black>Flip</font></td>
								                                          <?php if($version>=5){?><td class=maintext><font color=black>Effects</font></td><?php }?>
								                                          <td class=maintext><font color=black>Text</font></td>
								                                          <td class=maintext>&nbsp;</td>
								                                          <td>&nbsp;</td>
								                                        </tr>
																  <tr>
																   <td colspan=10 class=maintext align=center >Edit&nbsp;&nbsp;<b><?php echo $_SESSION['imagenametodisplay'];?></b></td>
																  </tr>
								                                      </table>                                          
																
																	  <?php
																	          require_once './includes/class.cropinterface.php'; 
												                              $ci = new cropInterface(); 
																			  /* default action is rotate*/
																		      if($_GET['action']=="" and $_GET['haction']=="" and $currentaction ==""){
																			         $_GET['action']="rotate";
																			  }
																			  if($currentaction !=""){
																			      $_GET['action']=$currentaction;
																			  }
																			   if($_GET['action']=="crop"){
																				    $currentfunctionname="Crop Now";
																					$currentfunction="my_Submit();";
																					/* load the crop interface window.function in class.cropinterface.php
																					   $imagetoedit ->image that are going to be edit
																					*/
																					$ci->loadInterfaceCrop($imagetoedit,$_SESSION['currentimage']); 
																			   }else if($_GET['action']=="addtext" or $_POST['haction']=="addtext"){
																				
																				  $currentfunctionname="Add text";
																				  $currentfunction="my_Submittext();";
																				  /* load the test add  interface window.function in class.cropinterface.php
																					   $imagetoedit ->image that are going to be edit
																					*/
																				  $ci->loadInterfaceText($imagetoedit,$_SESSION['currentimage']); 
																		          //$ci->loadJavaScript();
																				}else if($_GET['action']=="applyfilter"){
																				   $currentfunctionname="Apply";
																				   $currentfunction="my_Submitfiltereffects();";
																				    /* load the test filter interface window.function in class.cropinterface.php
																					   $imagetoedit ->image that are going to be edit
																					*/
																				  $ci->loadInterfaceFilter($imagetoedit,$_SESSION['currentimage']); 
																		          //$ci->loadJavaScript();
																				}else if($_GET['action']=="applybright"){
																				   $currentfunctionname="Apply";
																				   $currentfunction="my_Submitfilterbrightness(this);";
																				
																				  $ci->loadInterfaceFilter($imagetoedit,$_SESSION['currentimage']); 
																		          //$ci->loadJavaScript();
																				}else if($_GET['action']=="applycontrast"){
																				   $currentfunctionname="Apply";
																				   $currentfunction="my_Submitfiltercontrast(this);";
																				
																				  $ci->loadInterfaceFilter($imagetoedit,$_SESSION['currentimage']); 
																		          //$ci->loadJavaScript();
																				}else if($_GET['action']=="rotate"){
																				
																				
																					$currentfunctionname="Rotate";
																					$currentfunction="my_Submitrotate();";
																					 /* load the test image rotate   interface window.function in class.cropinterface.php
																					   $imagetoedit ->image that are going to be edit
																					*/
																				  	$ci->loadInterfaceRotate($imagetoedit,$_SESSION['currentimage']); 
																		          //$ci->loadJavaScript();
																				}else if($_GET['action']=="flip"){
																					$currentfunctionname="Flip";
																					$currentfunction="my_Submitflip();";
																					/* load the test image flip window   interface window.function in class.cropinterface.php
																					   $imagetoedit ->image that are going to be edit
																					*/
																				  	$ci->loadInterfaceRotate($imagetoedit,$_SESSION['currentimage']); 
																		          //$ci->loadJavaScript();
																				}
														
																				
																		?>		
																</FIELDSET>		
														    </td>
															<td>&nbsp;</td>
															<td valign=top width="300px">
															    <FIELDSET>
															                        <table   border="0" cellpadding="0" cellspacing="0" class="maintext" width="300px">
																																   <?php  if($_GET['action']=="crop"){ ?>
																																                 
																						                                                        <tr>
																																				 <td><b>Crop Option</b></td>
																																				</tr>
																																				<tr>
																						                                                          <td align="left" >&nbsp;</td>
																						                                                        </tr>
																																				<tr>
																																				 <td align=left>
																																				 hold down the 'shift' button to resize the cropping area
																																				 </td>
																																				</tr>
																																				
																						                                                       <tr>
																						                                                          		<td align="left" >&nbsp;</td>
																						                                                           </tr>
																																				 <tr>
																						                                                          <td align="left">&nbsp;</td>
																						                                                        </tr>
																																 <?php }else if($_GET['action']=="applyfilter"){
																																			  $filtercmb="<select name=cmbfiltertype id=cmbfiltertype class=selectbox onChange=\"changefiltertype(this);\">";
																																			  $filtercmb .="<option value=NG>NEGATE</option>";
																																			  $filtercmb .="<option value=GR>GRAYSCALE</option>";
																																			  $filtercmb .="<option value=CLR>COLORIZE</option>";
																																			  $filtercmb .="<option value=SM>SMOOTH</option>";
																																			  $filtercmb .="<option value=SK>SKETCHY</option>";
																																			  $filtercmb .="<option value=ED>EDGEDETECT</option>";
																																			  $filtercmb .="<option value=EB>EMBOSS</option>";
																																			  $filtercmb .="<option value=BL>BLUR</option></select>";
																																 ?>
																																                
																																                   <tr>
																																					 <td colspan=2><b>Effects</b></td>
																																					</tr>
																																					 <tr>
																							                                                          <td align="left" colspan=2>.....................................</td>
																							                                                        </tr>
																																					
																																				    <tr>
																																				      <td>Select</td><td><?php echo $filtercmb; ?></td>
																																				   </tr>
																																				  
																																				   <tr>
																																					  <td colspan=2>&nbsp;</td>
																																					</tr>
																																				
																																				 <tr id=colorize>
																																				 
																																				 <td colspan=2>
																																            <SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
																																							function changecolor(currentid){
																																							
																																							           winname="Easycreate";
																																									   winurl="chnagetextcolor.php";
																																									   window.open(winurl,winname,'width=200,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
																																						   } 
																																						   function setclrvalue(x){
																																							   	 document.getElementById("bgtd").style.backgroundColor=x;
																																							  	 document.getElementById("yourcol").value=x;
																																						  }
																																	      </SCRIPT>
																																		<div id="main">
																																		<form name="picker">
																																		<table width="10%" align=center border=0 cellpadding=0 cellspacing=0 >
																																		    <tr>
																																		     <td ><input class=textbox id=bgtd size=5 readonly style="background-color:black">
																																		     <input class=button type=button value=".." onclick="changecolor(this.id);"></td>
																																	     </tr>
																																		</table>
																																		</form>
																																		</div>
																															        
																																 </td></tr>
																																 <?php
																																 		} else if($_GET['action']=="rotate"){
																																				    $rotatecmb="<select id=cmbrotate class=selectbox>";
																																					
																																				    if($_GET['dir']=="c" or $_POST['hdir']=='c'){
																																					 $rotatedirection="c";
																																					 $rotatedir="Clockwise";
																																					 $rotateangle=1;
																																					}else{
																																					 $rotatedir="Anti Clockwise";
																																					 $rotateangle=-1;
																																					}
																																					for($i=0;$i<360;$i=$i+30){
																																					  $selectedcmb="";
																																					  if($degrees==$i*$rotateangle)
																																					     $selectedcmb="selected";
																																					$rotatecmb .="<option value=".$rotateangle*$i." $selectedcmb >$i</option>";
																																					}
																																					$rotatecmb .="</select>";
																																  ?>	
																																				   <tr>
																																					 <td colspan=2><b>Rotate <?php echo $rotatedir; ?></b></td>
																																					</tr>
																																					<tr>
																						                                                          		<td align="left" >&nbsp;</td>
																						                                                           </tr>
																																					
																																				    <tr>
																																				      <td>Rotate Angle</td><td><?php echo $rotatecmb; ?></td>
																																				   </tr>
																																				   <tr>
																																					  <td colspan=2>&nbsp;</td>
																																					</tr>
																																   <?php }  else if($_GET['action']=="flip"){
																																				    $flipcombo="<select id=cmbflip class=selectbox>";
																																				    
																																					
																																					$flipcombo .="<option value=IMAGE_FLIP_HORIZONTAL>HORIZONTAL</option>";
																																					$flipcombo .="<option value=IMAGE_FLIP_VERTICAL>VERTICAL</option>";
																																					$flipcombo .="<option value=IMAGE_FLIP_BOTH>BOTH</option>";
																																					$flipcombo .="</select>";
																																  ?>	
																																				   <tr>
																																					 <td colspan=2><b>Flip</b></td>
																																					</tr>
																																					<tr>
																						                                                          		<td align="left" >&nbsp;</td>
																						                                                           </tr>
																																					
																																				    <tr>
																																				      <td >Flip Option</td><td><?php echo $flipcombo; ?></td>
																																				   </tr>
																																				   
																																					  <tr>
																																					  <td colspan=2>&nbsp;</td>
																																					</tr>
																																					
																																   <?php } 
																																    else if($_GET['action']=="applybright"){
																																					
																																					$brighnesscmb="<select id=brighnesscmb class=selectbox>";
																																				    if($_GET['dir']=="add"){
																																					 $brighnessdir="Increase";
																																					 $brighness=-1;
																																					}else{
																																					 $brighnessdir="Decrease";
																																					 $brighness=1;
																																					}
																																					for($i=50;$i<500;$i=$i+50){
																																					$brighnesscmb .="<option value=".$brighness*$i.">$i</option>";
																																					}
																																					$brighnesscmb .="</select>";
																																	
																																	?>
																																				    <tr>
																																					 <td colspan=2><b><?php echo $brighnessdir; ?> &nbsp;Brightness</b></td>
																																					</tr>
																																					<tr>
																							                                                          <td align="left" colspan=2>.....................................</td>
																							                                                        </tr>
																																					
																																					<tr>
																																					  <td>Select</td>
																																					  <td>
																																					    <?php echo $brighnesscmb; ?>
																																					  </td>
																																					</tr>
																																					<tr>
																																					  <td colspan=2>&nbsp;</td>
																																					</tr>
																																	<?php } 							
																																    else if($_GET['action']=="applycontrast"){
																																					
																																					$contrastcmb="<select id=contrastcmb class=selectbox>";
																																				    if($_GET['dir']=="add"){
																																					 $contrastdir="Increase";
																																					 $contrast=-1;
																																					}else{
																																					 $contrastdir="Decrease";
																																					 $contrast=1;
																																					}
																																					for($i=10;$i<100;$i=$i+10){
																																					$contrastcmb .="<option value=".$contrast*$i.">$i</option>";
																																					}
																																					$contrastcmb .="</select>";
																																	?>
																																	
																																				    <tr>
																																					 <td colspan=2><b><?php echo $contrastdir; ?> &nbsp;Contrast</b></td>
																																					</tr>
																																					<tr>
																						                                                          		<td align="left" >&nbsp;</td>
																						                                                           </tr>
																																					<tr>
																																					  <td>Select</td>
																																					  <td>
																																					    <?php echo $contrastcmb; ?>
																																					  </td>
																																					</tr>
																																					<tr>
																																					<td colspan=2>&nbsp;</td>
																																					</tr>
																																	<?php } ?>
																																	<?php 
																																	 if( $_GET['action']=="addtext" or $_POST['haction']=="addtext") { 
																																	    $fontsize="<select name=cmbfontsize id=cmbfontsize class=selectbox>";
																																		for($i=10;$i<50;$i++){
																																		 $fontsize .="<option value=$i>$i</option>";
																																		}
																																		$fontname="<select name=cmbfontname id=cmbfontname class=selectbox>";
																																		//select the font from fonts directory
																																		
																																		/*$fontname .="<option value='times.ttf'>Times</option>";
																																		$fontname .="<option value='timesi.ttf'>Times Italic</option>";
																																		$fontname .="<option value='timesbd.ttf'>Times Bold</option>";
																																		$fontname .="<option value='timesbi.ttf'>Times Bold Italic</option>";
																																		$fontname .="<option value='arial.ttf'>Arial</option>";
																																		$fontname .="<option value='ariali.ttf'>Arial Italic</option>";
																																		$fontname .="<option value='arialbd.ttf'>Arial Bold</option>";
																																		$fontname .="<option value='arialbi.ttf'>Arial Bold Italic</option>";*/
																																		$fontname .="</select>";
																																	 
																																	 ?>
																																	<tr>
																																			 <td colspan=2><b>Add Text Options</b></td>
																																	</tr>
																																	<tr>
																						                                                          		<td align="left" >&nbsp;</td>
																						                                                   </tr>
																																   <tr>
																																   	<td colspan=2 align=left>
																																  			'hold down either <br>the 'shift' or 'control'<br> button to resize the text area'
																																 	</td>
																																     </tr>
																																	<tr>
																						                                                          		<td align="left" >&nbsp;</td>
																						                                             </tr>
																																	<tr>
																																	 <td valign=top align=left>Font color</b></td>
																																	<td valign=top align=left>
																																	<SCRIPT LANGUAGE="JavaScript" TYPE="text/javascript">
																																			function changecolor(currentid){
																																			
																																			           winname="Easycreate";
																																					   winurl="chnagetextcolor.php";
																																					   window.open(winurl,winname,'width=200,height=300,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
																																		   } 
																																		   function setclrvalue(x){
																																			   	 document.getElementById("bgtd").style.backgroundColor=x;
																																			  	 document.getElementById("yourcol").value=x;
																																		  }
																																	</SCRIPT>
																																		<div id="main">
																																		<form name="picker">
																																		<table width="80%" align=center border=0 cellpadding=0 cellspacing=0 >
																																		  <tr>
																																		     <td ><input class=textbox id=bgtd size=5 readonly style="background-color:black">
																																		     <input class=button type=button value=".." onclick="changecolor(this.id);"></td>
																																	     </tr>
																																		<input type="hidden" name="yourcol" id="yourcol" size="7" id=yourcol value="#000000">
																																		
																																		
																																		
																																		
																																		</table>
																																		</form>
																																		</div>
																															        <form name=frmtxtval method=post action=editgallery.php   onsubmit="return <?php echo $currentfunction; ?>">
																																	
																																	<tr>
																																	  <td align=left>Selct Font</td>
																																	  <td align=left>
																																	  <select name=cmbfontname id=cmbfontname class=selectbox>
																																	  <?php
																																		   builFontSelectBox('');
																																		
																																		?>
																																	  </select>
																																	  </td>
																																	</tr>
																																	<tr>
																																	  <td align=left>Font Size</td>
																																	  <td align=left><?php echo $fontsize; ?></td>
																																	</tr>	
																																	<tr>
																																	<tr>
																																	 <td align=left>
																																	   Select Angle
																																	 </td>
																																	 <td  align=left>
																																	   <select name=textangle id=textangle class=selectbox>
																																	   <?php
																																	    for($i=0;$i<360;$i=$i+30){
																																		$angle="<option value=".$i.">$i</option>";
																																		echo $angle;
																																		}
																																		?>
																																					
																																	   </select>
																																	 </td>
																																	</tr>
																																	<tr>
																																	  <td colspan=2>&nbsp;</td>
																																	</tr>
																																	<tr><td colspan=2 align=center>
																																	        <input type=hidden name=htextval id=htextval>
																																	        <input type=hidden name=hsx id=hsx>
																																			<input type=hidden name=hsy id=hsy>
																																			<input type=hidden name=hfile id=hfile>
																																			<input type=hidden name=haction id=haction>
																																			<input type=hidden name=hfontcolor id=hfontcolor>
																																			<input type="hidden" name="checkid" id="checkid" value="<?php echo($_SESSION["session_checkid"]); ?>">
																																			
																																			
																			                                                    			<input name="Submit" class="button" type="submit" class="textbox" value="<?php echo $currentfunctionname; ?>" >
																			                                                        </form>
																																	<?php }else{?>
																																	<tr><td colspan=2 align=center>
																																	
																																	<!--<form name="form1" method="post" action="">-->
																																	<form name=frmeditimage method=post action=editgallery.php   onsubmit="return <?php echo $currentfunction; ?>">
																																	        <input type=hidden name=hsx id=hsx>
																																			<input type=hidden name=hsy id=hsy>
																																			<input type=hidden name=hex id=hex>
																																			<input type=hidden name=hey id=hey>
																																			<input type=hidden name=hfile id=hfile>
																																			<input type=hidden name=haction id=haction>
																																			<input type=hidden name=hflipdirection id=hflipdirection>
																																			<input type=hidden name=hrotatevalue id=hrotatevalue>
																																			<input type=hidden name=hbrvalue id=hbrvalue>
																																			<input type=hidden name=hcrvalue id=hcrvalue>
																																			<input type=hidden name=hfiltervalue id=hfiltervalue>
																																			<input type=hidden name=hblockrefresh id=hblockrefresh>
																																			<input type="hidden" name="checkid" id="checkid" value="<?php echo($_SESSION["session_checkid"]); ?>">
																																			<input type="hidden" name="hdir" id="hdir" value="<?php echo($rotatedirection); ?>">
																																			
																			                                                    			<input name="Submit" type="submit" class="button" value="<?php echo $currentfunctionname; ?>" >
																			                                                        </form>
																																	
																																	<?php } 
																																	if($_GET['action']=="applyfilter"){
																																	?>
																																	<script>
																																	document.getElementById('colorize').style.display="none";
																																	</script>
																																	<?php } ?>
																															  		</td></tr>
																			                                                      
													              
																                                   				</table>
																										 </FIELDSET> 
																										 <form name=frmsaveimage method=post >	
																												 <table>
																												  <tr>
																													  <td colspan="2" class=maintext>
																													    <?php 
																														    /* for system gallery images this block will not be shown*/  
																														    if($_SESSION['sess_edittype'] !="systemgallery"){ ?>
																														  <input type=radio name=rdsave value="1" checked >Replace Existing file
																													  	  <input type=radio name=rdsave value="2" class=maintext>Create New file
																														<?php } ?>  
																													  </td>
																													</tr>
																													<tr>
																													  <td colspan="3">&nbsp;</td>
																													</tr>
																													<tr  align=center>
																													  <td colspan=3>
																													  
																													    <input type=submit class=button name=btn_savechanges value="Save Changes">
																														
																													  </td>
																													</tr>
																												 </table>	
																										 </form>
															   
															
															</td>
													</tr>	
													
											</table>			
																                                
								   
								  </td>
								</tr>
								
								<tr>
								   <td>&nbsp;</td>
								 </tr>
								 <form name=frmbackbuttons method=post>
								 <?php if($_SESSION['sess_edittype']=="systemgallery"){ ?>
									 <tr>
									    <td>
										   <input type="submit" name=btnbacktoimagegallery  class=button value="Back to System gallery" >
										</td>
									 </tr>
									 
									<?php }else{?>
									
									 <tr>
									    <td>
										   <input  type="submit" class=button name=btnbacktoimagegallery1 value="Back to Image gallery">
										</td>
	                                </tr>
									<?php } ?>
								 </form>
								</table>
								<script>
							       if(isNaN(document.all("checkid").value) || document.all("checkid").value.length <= 0 || (parseInt(document.all("checkid").value) <= 0)) {
										document.all("checkid").value=1;
									}
									else {
										document.all("checkid").value = parseInt(document.all("checkid").value) + 1;
									}
							</script>
								
								
<?php }else{ ?>		
<form name=frmbackbuttons method=post>
  <table>
     <tr><td class=errormessage>Image read error! <?php echo $invalidmessage; ?></td></tr>
	 <tr><td>&nbsp;</td></tr>
	 <?php if($_SESSION['sess_edittype']=="systemgallery"){ ?>
	 <tr>
	    <td>
		   <input type="submit" name=btnbacktoimagegallery class=button value="Back to Image gallery" >
		</td>
	 </tr>
	 
	<?php }else{?>
	
	<tr>
	    <td>
		   <input type="submit" class=button name=btnbacktoimagegallery1 value="Back to Image gallery" >
		</td>
	 </tr>
	<?php } ?>
	 
  
  </table>
  </form>						
<?php } ?>


																		
