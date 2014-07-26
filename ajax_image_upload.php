<?php
error_reporting(0);
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                      |
// |                                                                                                            |
// +----------------------------------------------------------------------+

include "./includes/class.imagehandle.php";
include "./includes/class.texthandle.php";
include "./includes/class.textoverimage.php";

if($_FILES['userImages']){

    include "includes/session.php";
    include 'includes/config.php';
    list($width,$height) = getimagesize($_FILES['userImages']['tmp_name']);
    $fileName = time().$_FILES['userImages']['name'];

    /*if($width < 350 || $height < 350){
        echo "ERROR: Image size should be atleast 350x350";
    }*/
    if(is_uploaded_file($_FILES['userImages']['tmp_name']))
    {
        //$usergal = "usergallery/" . $_SESSION["session_userid"] . "/images";
        $usergal = "uploads/siteimages";

        //create a directory for user if it doesnt exist
        if(!is_dir($usergal)){
            createdir($usergal);
        }

        if(!move_uploaded_file($_FILES['userImages']['tmp_name'], $usergal.'/'.$fileName))
        {
            echo "ERROR: Sorry, an error occurred, please try again!";
        }
        else
        {

		 	$imgsql 	= "Select img_id from tbl_useruploadimages where user_id=".$_SESSION['session_userid']." AND image_name='".$fileName."' ";
			$resimg 	= mysql_query($imgsql) or die(mysql_error());
			if(mysql_num_rows($resimg) <=  0) {

				$sqlAddImg = "INSERT INTO  tbl_useruploadimages(user_id,image_name,added_on,img_type,status)
					VALUES(".$_SESSION['session_userid'].",'".$fileName."','".time()."',1,0)";
				$result = mysql_query($sqlAddImg) or die(mysql_error());
			}
            //create a thumbnail
            imageResize($usergal, $fileName, $fileName, true, false, true);

            //resize to fit the editor
            imageResize($usergal, $fileName, $fileName);

            //echo $usergal.'/'.$fileName;
            echo $usergal.'/thumb/'.$fileName;
        }
    }
}

if(isset($_REQUEST['browserimage'])){
    include "includes/session.php";

    //$usergal = "usergallery/" . $_SESSION["session_userid"] . "/images";
    $usergal = "uploads/siteimages";
    $imagepart = explode(".", $_REQUEST['browserimage']);

    if(file_exists($usergal.'/'.$_REQUEST['browserimage'])){

    	$imgType = strtolower(end($imagepart));

        switch($imgType){
            case 'jpg':

                $img = imagecreatefromjpeg($usergal.'/'.$_REQUEST['browserimage']);
                list($org_width, $org_height) = getimagesize($usergal.'/'.$_REQUEST['browserimage']);

                if(isset($_REQUEST['resize'])){
                    $height = $_REQUEST['height'] == 0 ? 10 : $_REQUEST['height'];
                    $width = $_REQUEST['width'] == 0 ? 10 : $_REQUEST['width'];

                    $canvas = imagecreatetruecolor($width, $height);
                    imagecopyresampled($canvas, $img, 0, 0, 0, 0, $width, $height, $org_width, $org_height);
                    $img = $canvas;
                }

                if(isset($_REQUEST['rotate']) && $_REQUEST['rotate'] <> 0){
                    $img = imagerotate($img, (int)$_REQUEST['rotate'], "0xFFFFFF");

                    $iw = imagesx($img);
                    $ih = imagesy($img);
                    if($ih > 560 || $iw > 560){
                        $img = resizeOnTheFly($img, $iw, $ih, 560, 560);
                    }
                }

                if(isset($_REQUEST['brightness'])){
                    imagefilter($img, IMG_FILTER_BRIGHTNESS, $_REQUEST['brightness']);
                }

                if(isset($_REQUEST['contrast'])){
                    imagefilter($img, IMG_FILTER_CONTRAST, $_REQUEST['contrast']);
                }

                if(isset($_REQUEST['flip']) && $_REQUEST['flip'] <> 0){
                    imageflip1($img);
                }

                if(isset($_REQUEST['flop']) && $_REQUEST['flop'] <> 0){
                    imageflop($img);
                }

                if(isset($_REQUEST['coords'])){
                    $coord_parts = explode('_', $_REQUEST['coords']);

                    if($coord_parts[1] - $coord_parts[0] <> 0){
                        $crop_width = $coord_parts[1] - $coord_parts[0];
                        $crop_height = $coord_parts[3] - $coord_parts[2];

                        $img = resizeThumbnailImage($img, $crop_width, $crop_height, $coord_parts[0], $coord_parts[2]);
                    }
                }

                if(isset($_REQUEST['effects'])){
                    switch($_REQUEST['effects']){
                        case 'NA':
                            break;

                        case 'negative':
                            imagefilter($img, IMG_FILTER_NEGATE);
                            break;

                        case 'greyscale':
                            imagefilter($img, IMG_FILTER_GRAYSCALE);
                            break;

                        case 'smooth':
                            imagefilter($img, IMG_FILTER_SMOOTH, 6);
                            break;

                        case 'blur':
                            imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
                            break;

                        default:
                            break;
                    }
                }

                //check if image is needed or just its attributes
                if(isset($_REQUEST['noimage'])){
                    echo imagesy($img)."_".imagesx($img);
                    exit;
                }
                else{
                    header("Content-type: image/jpg");
                    echo imagejpeg($img);
                }
                imagedestroy($img);
                break;
            case 'png':
                $img = imagecreatefrompng($usergal.'/'.$_REQUEST['browserimage']);
                list($org_width, $org_height) = getimagesize($usergal.'/'.$_REQUEST['browserimage']);

                if(isset($_REQUEST['resize'])){
                    $height = $_REQUEST['height'] == 0 ? 10 : $_REQUEST['height'];
                    $width = $_REQUEST['width'] == 0 ? 10 : $_REQUEST['width'];

                    $canvas = imagecreatetruecolor($width, $height);
                    imagecopyresampled($canvas, $img, 0, 0, 0, 0, $width, $height, $org_width, $org_height);
                    $img = $canvas;
                }

                if(isset($_REQUEST['rotate']) && $_REQUEST['rotate'] <> 0){
                    $img = imagerotate($img, (int)$_REQUEST['rotate'], -1);

                    $iw = imagesx($img);
                    $ih = imagesy($img);
                    if($ih > 560 || $iw > 560){
                        $img = resizeOnTheFly($img, $iw, $ih, 560, 560);
                    }
                }

                if(isset($_REQUEST['brightness'])){
                    imagefilter($img, IMG_FILTER_BRIGHTNESS, $_REQUEST['brightness']);
                }

                if(isset($_REQUEST['contrast'])){
                    imagefilter($img, IMG_FILTER_CONTRAST, $_REQUEST['contrast']);
                }

                if(isset($_REQUEST['flip']) && $_REQUEST['flip'] <> 0){
                    imageflip1($img);
                }

                if(isset($_REQUEST['flop']) && $_REQUEST['flop'] <> 0){
                    imageflop($img);
                }

                if(isset($_REQUEST['coords'])){
                    $coord_parts = explode('_', $_REQUEST['coords']);

                    if($coord_parts[1] - $coord_parts[0] <> 0){
                        $crop_width = $coord_parts[1] - $coord_parts[0];
                        $crop_height = $coord_parts[3] - $coord_parts[2];

                        $img = resizeThumbnailImage($img, $crop_width, $crop_height, $coord_parts[0], $coord_parts[2]);
                    }
                }

                if(isset($_REQUEST['effects'])){
                    switch($_REQUEST['effects']){
                        case 'NA':
                            break;

                        case 'negative':
                            imagefilter($img, IMG_FILTER_NEGATE);
                            break;

                        case 'greyscale':
                            imagefilter($img, IMG_FILTER_GRAYSCALE);
                            break;

                        case 'smooth':
                            imagefilter($img, IMG_FILTER_SMOOTH, 6);
                            break;

                        case 'blur':
                            imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
                            break;

                        default:
                            break;
                    }
                }

                //check if image is needed or just its attributes
                if(isset($_REQUEST['noimage'])){
                    echo imagesy($img)."_".imagesx($img);
                    exit;
                }
                else{
                    header("Content-type: image/png");
                    echo imagepng($img);
                }
                imagedestroy($img);
                break;
            case 'gif':
                $img = imagecreatefromgif($usergal.'/'.$_REQUEST['browserimage']);
                list($org_width, $org_height) = getimagesize($usergal.'/'.$_REQUEST['browserimage']);

                if(isset($_REQUEST['resize'])){
                    $height = $_REQUEST['height'] == 0 ? 10 : $_REQUEST['height'];
                    $width = $_REQUEST['width'] == 0 ? 10 : $_REQUEST['width'];

                    $canvas = imagecreatetruecolor($width, $height);
                    imagecopyresampled($canvas, $img, 0, 0, 0, 0, $width, $height, $org_width, $org_height);
                    $img = $canvas;
                }

                if(isset($_REQUEST['rotate']) && $_REQUEST['rotate'] <> 0){
                    $img = imagerotate($img, (int)$_REQUEST['rotate'], "0xFFFFFF");

                    $iw = imagesx($img);
                    $ih = imagesy($img);
                    if($ih > 560 || $iw > 560){
                        $img = resizeOnTheFly($img, $iw, $ih, 560, 560);
                    }
                }

                if(isset($_REQUEST['brightness'])){
                    imagefilter($img, IMG_FILTER_BRIGHTNESS, $_REQUEST['brightness']);
                }

                if(isset($_REQUEST['contrast'])){
                    imagefilter($img, IMG_FILTER_CONTRAST, $_REQUEST['contrast']);
                }

                if(isset($_REQUEST['flip']) && $_REQUEST['flip'] <> 0){
                    imageflip1($img);
                }

                if(isset($_REQUEST['flop']) && $_REQUEST['flop'] <> 0){
                    imageflop($img);
                }

                if(isset($_REQUEST['coords'])){
                    $coord_parts = explode('_', $_REQUEST['coords']);

                    if($coord_parts[1] - $coord_parts[0] <> 0){
                        $crop_width = $coord_parts[1] - $coord_parts[0];
                        $crop_height = $coord_parts[3] - $coord_parts[2];

                        $img = resizeThumbnailImage($img, $crop_width, $crop_height, $coord_parts[0], $coord_parts[2]);
                    }
                }

                if(isset($_REQUEST['effects'])){
                    switch($_REQUEST['effects']){
                        case 'NA':
                            break;

                        case 'negative':
                            imagefilter($img, IMG_FILTER_NEGATE);
                            break;

                        case 'greyscale':
                            imagefilter($img, IMG_FILTER_GRAYSCALE);
                            break;

                        case 'smooth':
                            imagefilter($img, IMG_FILTER_SMOOTH, 6);
                            break;

                        case 'blur':
                            imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
                            break;

                        default:
                            break;
                    }
                }
                //check if image is needed or just its attributes
                if(isset($_REQUEST['noimage'])){
                    echo imagesy($img)."_".imagesx($img);
                    exit;
                }
                else{
                    header("Content-type: image/gif");
                    echo imagegif($img);
                }
                imagedestroy($img);
                break;
            default:
                break;
        }
    }
}

if(isset($_REQUEST['get_img_attributes'])){
    include "includes/session.php";

    //$usergal = "usergallery/" . $_SESSION["session_userid"] . "/images";
    $usergal = "uploads/siteimages";
    list($width, $height) = getimagesize($usergal.'/'.$_REQUEST['get_img_attributes']);

    echo $width.'#'.$height;
}

if(isset($_REQUEST['textgen'])){
    $text = new Texthandle();
    extract($_REQUEST);

    //if (isset($msg)) $text->msg = stripslashes(base64_decode(strtr($msg, '-_-,', '+/='))); // text to display
    if (isset($msg)) $text->msg = urldecode($msg); // text to display
    if(!isset($font)){
            $font = "arial.ttf";
    }
    $text->font = "fonts/".$font; // font to use (include directory if needed).
    if (isset($size)) $text->size = $size; // size in points
    if (isset($fore)) $text->fore = $fore; // text color

    $text->draw(); // GO!!!!!
    exit;
}

if(isset($_REQUEST['str'])){
    extract($_POST);
    //echo strtr(base64_encode($str), '+/=', '-_-,');
    echo urlencode($str);
    exit;
}

if(isset($_POST['replace_or_overwrite'])){
    include "includes/session.php";
    include 'includes/config.php';
    //echopre1($_POST);
    extract($_POST);

    if($replace_or_overwrite == 0){
        $edit_img_name = $img_name;
    }
    else{
        $edit_img_name = "edited_".time()."_".$img_name;
    }

     //----------DB insertion for replace and save------------------
        if($replace_or_overwrite == 0){
            $sqlDelOldImage=" DELETE FROM tbl_useruploadimages WHERE image_name='".$img_name."' AND user_id='".$_SESSION['session_userid']."'";
            $resDelOldImage = mysql_query($sqlDelOldImage) or die(mysql_error());

			 $imgsql 	= "Select img_id from tbl_useruploadimages where user_id=".$_SESSION['session_userid']." AND image_name='".$edit_img_name."' ";
			$resimg 	= mysql_query($imgsql) or die(mysql_error());
			if(mysql_num_rows($resimg) <=  0) {
				$sqlAddImg = "INSERT INTO  tbl_useruploadimages(user_id,image_name,added_on,img_type,status)
						VALUES(".$_SESSION['session_userid'].",'".$edit_img_name."','".time()."',1,0)";
				$result = mysql_query($sqlAddImg) or die(mysql_error());
			}
        }
        else{
			$imgsql 	= "Select img_id from tbl_useruploadimages where user_id=".$_SESSION['session_userid']." AND image_name='".$edit_img_name."' ";
			$resimg 	= mysql_query($imgsql) or die(mysql_error());
			if(mysql_num_rows($resimg) <=  0) {

				 $sqlAddImg = "INSERT INTO  tbl_useruploadimages(user_id,image_name,added_on,img_type,status)
						VALUES(".$_SESSION['session_userid'].",'".$edit_img_name."','".time()."',1,0)";
				$result = mysql_query($sqlAddImg) or die(mysql_error());
			}
        }
       //----------DB insertion for replace and save------------------

    if($hid_coords <> ''){
        //$final_image = new Textoverimage("usergallery/" . $_SESSION["session_userid"] . "/images/".$img_name, "usergallery/" . $_SESSION["session_userid"] . "/images/".$edit_img_name);
        $final_image = new Textoverimage("uploads/siteimages/".$img_name, "uploads/siteimages/".$edit_img_name);

        $final_image->addText($hid_coords);

        //create a thumbnail as its used to display user images
//        if(file_exists("usergallery/" . $_SESSION["session_userid"] . "/images/".$edit_img_name)){
//            imageResize("usergallery/" . $_SESSION["session_userid"] . "/images", $edit_img_name, $edit_img_name, true, false, true);
//        }
        if(file_exists("uploads/siteimages/".$edit_img_name)){
            imageResize("uploads/siteimages/", $edit_img_name, $edit_img_name, true, false, true);
        }
    }
    else{
        //print_r($_POST);
        //$usergal = "usergallery/" . $_SESSION["session_userid"] . "/images";
       $usergal ="uploads/siteimages/";
        if(file_exists($usergal."/".$img_name)){
            $imagepart = explode(".", $img_name);

            switch(end($imagepart)){
                case 'jpg':
                    $img = imagecreatefromjpeg($usergal.'/'.$img_name);
                    list($org_width, $org_height) = getimagesize($usergal.'/'.$img_name);

                    if($org_width <> $image_width || $org_height <> $image_height){
                        $height = $image_height == 0 ? 10 : $image_height;
                        $width = $image_width == 0 ? 10 : $image_width;

                        $canvas = imagecreatetruecolor($image_width, $image_height);
                        imagecopyresampled($canvas, $img, 0, 0, 0, 0, $image_width, $image_height, $org_width, $org_height);
                        $img = $canvas;
                    }

                    if(isset($rotate_angle) && $rotate_angle <> 0){
                        $img = imagerotate($img, (int)$rotate_angle, "0xFFFFFF");

                        $iw = imagesx($img);
                        $ih = imagesy($img);
                        if($ih > 560 || $iw > 560){
                            $img = resizeOnTheFly($img, $iw, $ih, 560, 560);
                        }
                    }

                    if(isset($brightness_value)){
                        imagefilter($img, IMG_FILTER_BRIGHTNESS, $brightness_value);
                    }

                    if(isset($contrast_value)){
                        imagefilter($img, IMG_FILTER_CONTRAST, $contrast_value);
                    }

                    if(isset($flip_count) && $flip_count <> 0){
                        imageflip1($img);
                    }

                    if(isset($flop_count) && $flop_count <> 0){
                        imageflop($img);
                    }

                    if($x2 - $x1 <> 0){
                        $crop_width = $x2 - $x1;
                        $crop_height = $y2 - $y1;

                        $img = resizeThumbnailImage($img, $crop_width, $crop_height, $x1, $y1);
                    }

                    if(isset($image_effects)){
                        switch($image_effects){
                            case 'NA':
                                break;

                            case 'negative':
                                imagefilter($img, IMG_FILTER_NEGATE);
                                break;

                            case 'greyscale':
                                imagefilter($img, IMG_FILTER_GRAYSCALE);
                                break;

                            case 'smooth':
                                imagefilter($img, IMG_FILTER_SMOOTH, 6);
                                break;

                            case 'blur':
                                imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
                                break;

                            default:
                                break;
                        }
                    }

                    imagejpeg($img, $usergal."/".$edit_img_name, 100);
                    imagedestroy($img);
                    break;
                case 'png':
                    $img = imagecreatefrompng($usergal.'/'.$img_name);
                    list($org_width, $org_height) = getimagesize($usergal.'/'.$img_name);

                    if($org_width <> $image_width || $org_height <> $image_height){
                        $height = $image_height == 0 ? 10 : $image_height;
                        $width = $image_width == 0 ? 10 : $image_width;

                        $canvas = imagecreatetruecolor($image_width, $image_height);
                        imagecopyresampled($canvas, $img, 0, 0, 0, 0, $image_width, $image_height, $org_width, $org_height);
                        $img = $canvas;
                    }

                    if(isset($rotate_angle) && $rotate_angle <> 0){
                        $img = imagerotate($img, (int)$rotate_angle, "0xFFFFFF");

                        $iw = imagesx($img);
                        $ih = imagesy($img);
                        if($ih > 560 || $iw > 560){
                            $img = resizeOnTheFly($img, $iw, $ih, 560, 560);
                        }
                    }

                    if(isset($brightness_value)){
                        imagefilter($img, IMG_FILTER_BRIGHTNESS, $brightness_value);
                    }

                    if(isset($contrast_value)){
                        imagefilter($img, IMG_FILTER_CONTRAST, $contrast_value);
                    }

                    if(isset($flip_count) && $flip_count <> 0){
                        imageflip1($img);
                    }

                    if(isset($flop_count) && $flop_count <> 0){
                        imageflop($img);
                    }

                    if($x2 - $x1 <> 0){
                        $crop_width = $x2 - $x1;
                        $crop_height = $y2 - $y1;

                        $img = resizeThumbnailImage($img, $crop_width, $crop_height, $x1, $y1);
                    }

                    if(isset($image_effects)){
                        switch($image_effects){
                            case 'NA':
                                break;

                            case 'negative':
                                imagefilter($img, IMG_FILTER_NEGATE);
                                break;

                            case 'greyscale':
                                imagefilter($img, IMG_FILTER_GRAYSCALE);
                                break;

                            case 'smooth':
                                imagefilter($img, IMG_FILTER_SMOOTH, 6);
                                break;

                            case 'blur':
                                imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
                                break;

                            default:
                                break;
                        }
                    }

                    imagepng($img, $usergal."/".$edit_img_name);
                    imagedestroy($img);
                    break;
                case 'gif':
                    $img = imagecreatefromgif($usergal.'/'.$img_name);
                    list($org_width, $org_height) = getimagesize($usergal.'/'.$img_name);

                    if($org_width <> $image_width || $org_height <> $image_height){
                        $height = $image_height == 0 ? 10 : $image_height;
                        $width = $image_width == 0 ? 10 : $image_width;

                        $canvas = imagecreatetruecolor($image_width, $image_height);
                        imagecopyresampled($canvas, $img, 0, 0, 0, 0, $image_width, $image_height, $org_width, $org_height);
                        $img = $canvas;
                    }

                    if(isset($rotate_angle) && $rotate_angle <> 0){
                        $img = imagerotate($img, (int)$rotate_angle, "0xFFFFFF");

                        $iw = imagesx($img);
                        $ih = imagesy($img);
                        if($ih > 560 || $iw > 560){
                            $img = resizeOnTheFly($img, $iw, $ih, 560, 560);
                        }
                    }

                    if(isset($brightness_value)){
                        imagefilter($img, IMG_FILTER_BRIGHTNESS, $brightness_value);
                    }

                    if(isset($contrast_value)){
                        imagefilter($img, IMG_FILTER_CONTRAST, $contrast_value);
                    }

                    if(isset($flip_count) && $flip_count <> 0){
                        imageflip1($img);
                    }

                    if(isset($flop_count) && $flop_count <> 0){
                        imageflop($img);
                    }

                    if($x2 - $x1 <> 0){
                        $crop_width = $x2 - $x1;
                        $crop_height = $y2 - $y1;

                        $img = resizeThumbnailImage($img, $crop_width, $crop_height, $x1, $y1);
                    }

                    if(isset($image_effects)){
                        switch($image_effects){
                            case 'NA':
                                break;

                            case 'negative':
                                imagefilter($img, IMG_FILTER_NEGATE);
                                break;

                            case 'greyscale':
                                imagefilter($img, IMG_FILTER_GRAYSCALE);
                                break;

                            case 'smooth':
                                imagefilter($img, IMG_FILTER_SMOOTH, 6);
                                break;

                            case 'blur':
                                imagefilter($img, IMG_FILTER_GAUSSIAN_BLUR);
                                break;

                            default:
                                break;
                        }
                    }

                    imagegif($img, $usergal."/".$edit_img_name);
                    imagedestroy($img);
                    break;
                default:
                    break;
            }


            //create a thumbnail as its used to display user images
            if(file_exists($usergal."/".$edit_img_name)){
                imageResize($usergal, $edit_img_name, $edit_img_name, true, false, true);
            }
        }
        //exit;
    }

    //prevent the browser from caching images
    header("cache-Control: no-store, no-cache, must-revalidate");
    header("cache-Control: post-check=0, pre-check=0", false);
    header("Pragma: no-cache");
    header("Location:gallerymanager.php");
    exit;
}

//--------------------------------Functions---------------------------------------//

function imageResize($path, $sourceFileName, $destFileName, $enlarge = false, $proportional = true, $thumbnail = false)
{
    $image = new Zebra_Image();
    $image->source_path = $path.'/'.$sourceFileName;

    $image->preserve_aspect_ratio = $proportional;
    $image->enlarge_smaller_images = $enlarge;
    $image->preserve_time = true;

    if($thumbnail){
        $image->target_path = $path.'/thumb/'.$destFileName;
        $image->resize(60, 60, ZEBRA_IMAGE_NOT_BOXED);
    }
    else{
        $image->target_path = $path.'/'.$destFileName;
        $image->resize(560, 560, ZEBRA_IMAGE_NOT_BOXED);
    }
}

function getthumbs()
{
    //createdir("usergallery/" . $_SESSION["session_userid"] . "/images");

    //$image_file_path = "usergallery/" . $_SESSION["session_userid"] . "/images/thumb/";
    $image_file_path = "uploads/siteimages/thumb/";
    $fileArray[0] = $image_file_path;
    $allowedTypes = array("jpg","png","gif");
    $images = array();
    //echopre($_SESSION);
    $imgsql 	= "Select * from tbl_useruploadimages where user_id=".$_SESSION["session_userid"]." ORDER BY img_id DESC";
    $resimg 	= mysql_query($imgsql) or die(mysql_error());
    if(mysql_num_rows($resimg) > 0) {
            while($rowimg = mysql_fetch_assoc($resimg)) {
                    $images[]  = BASE_URL.EDITOR_USER_IMAGES.$rowimg['image_name']."?time=".time();
                    //echo '<div class="edtrimggallerylist"><a href="" class="usegalleryimage" id="'.$imgUrl.'"><img src="'.$imgUrl.'"  class="edtrimggalleryimg" > </a> </div>';
            }
    }

//    foreach($fileArray as $fileDir){
//        $file_handle = dir($fileDir);
//        while (false !== ($entry = $file_handle->read())) {
//            if($entry != '.' && $entry != '..' && !is_dir($fileDir.$entry)){
//                $filetype = explode(".",$entry);
//                $ext = strtolower(end($filetype));
//                if(in_array($ext,$allowedTypes)){
//                    //------------show only images with size 50x50-------------------//
//                    list($width, $height) = getimagesize($image_file_path.$entry);
//                    if($width == 60 && $height == 60) $images[] = $image_file_path.$entry;
//                }
//            }
//        }
//    }
//    $file_handle->close();
    //$this->set('limit', 12);
    //$this->set('page', $page);
    //$this->set('dirname', $dirname);
    //echopre($images);
    return $images;
}

function createdir($usergal)
{
    //root directory
    //uploads/siteimages
    if(!is_dir("./uploads/siteimages")){
        mkdir("./uploads/siteimages",0777);
        chmod("./uploads/siteimages",0777);
    }
//    if(!is_dir("./usergallery/" . $_SESSION["session_userid"])){
//        mkdir("./usergallery/" . $_SESSION["session_userid"],0777);
//        chmod("./usergallery/" . $_SESSION["session_userid"],0777);
//    }
//
//    //image directory
//    if(!is_dir($usergal)){
//        mkdir($usergal,0777);
//        chmod($usergal,0777);
//    }

    //thumbnail directory
    if(!is_dir($usergal.'/thumb')){
        mkdir($usergal.'/thumb',0777);
        chmod($usergal.'/thumb',0777);
    }
}

/*
 * function to flip an image using GD library
 */
function imageflip1(&$image, $x = 0, $y = 0, $width = null, $height = null)
{
    if ($width  < 1) $width  = imagesx($image);
    if ($height < 1) $height = imagesy($image);
    // Truecolor provides better results, if possible.
    if (function_exists('imageistruecolor') && imageistruecolor($image))
    {
        $tmp = imagecreatetruecolor(1, $height);
    }
    else
    {
        $tmp = imagecreate(1, $height);
    }
    $x2 = $x + $width - 1;
    for ($i = (int) floor(($width - 1) / 2); $i >= 0; $i--)
    {
        // Backup right stripe.
        imagecopy($tmp,   $image, 0,        0,  $x2 - $i, $y, 1, $height);
        // Copy left stripe to the right.
        imagecopy($image, $image, $x2 - $i, $y, $x + $i,  $y, 1, $height);
        // Copy backuped right stripe to the left.
        imagecopy($image, $tmp,   $x + $i,  $y, 0,        0,  1, $height);
    }
    imagedestroy($tmp);
    return true;
}

/*
 * function to flop an image using GD library
 */
function imageflop(&$img)
{
    $size_x = imagesx($img);
    $size_y = imagesy($img);
    $temp = imagecreatetruecolor($size_x, $size_y);
    $x = imagecopyresampled($temp, $img, 0, 0, 0, ($size_y-1), $size_x, $size_y, $size_x, 0-$size_y);

    $img = $temp;
}

function resizeThumbnailImage(&$img, $width, $height, $start_width, $start_height)
{
    $imagewidth = imagesx($img);
    $imageheight = imagesy($img);
    $scale = 1;

    //suppose its for thumbnail cropping hence disabling
    /*if($width > $height){
        $scale = $width/$imagewidth;
    }
    else{
        $scale = $height/$imageheight;
    }*/


    $newImageWidth = ceil($width * $scale);
    $newImageHeight = ceil($height * $scale);
    $newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);

    imagecopyresampled($newImage,$img,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);

    return $newImage;
}

function getfonts()
{
    $image_file_path = "fonts/";
    $fileDir = $image_file_path;
    $allowedTypes = array("ttf","otf");
    $images = array();

    $i = 0;
    $file_handle = dir($fileDir);
    while (false !== ($entry = $file_handle->read())) {
        if($entry != '.' && $entry != '..' && !is_dir($fileDir.$entry)){
            $filetype = explode(".",$entry);
            $ext = strtolower(end($filetype));
            if(in_array($ext,$allowedTypes)){
                $fonts[$i]['val'] = $entry;

                $name = '';
                $part = explode(".", $entry);
                foreach($part as $font){
                    if($font <> end($part)) $name .= $font;
                }
                $fonts[$i]['name'] = $name;
                $i++;
            }
        }
    }

    $file_handle->close();

    return $fonts;
}

/*
 * function to resize images on the fly
 */
function resizeOnTheFly(&$img, $iw, $ih, $maxw, $maxh){
    if ($iw > $maxw || $ih > $maxh){
        if ($iw>$maxw && $ih<=$maxh){//too wide, height is OK
            $proportion=($maxw*100)/$iw;
            $neww=$maxw;
            $newh=ceil(($ih*$proportion)/100);
        }

        else if ($iw<=$maxw && $ih>$maxh){//too high, width is OK
            $proportion=($maxh*100)/$ih;
            $newh=$maxh;
            $neww=ceil(($iw*$proportion)/100);
        }

        else {//too high and too wide

            if ($iw/$maxw > $ih/$maxh){//width is the bigger problem
                $proportion=($maxw*100)/$iw;
                $neww=$maxw;
                $newh=ceil(($ih*$proportion)/100);
            }

            else {//height is the bigger problem
                $proportion=($maxh*100)/$ih;
                $newh=$maxh;
                $neww=ceil(($iw*$proportion)/100);
            }
        }
    }

    else {//copy image even if not resizing
        $neww=$iw;
        $newh=$ih;
    }

    if (function_exists("imagecreatetruecolor")){//GD 2.0=good!
        $newImage=imagecreatetruecolor($neww, $newh);
        imagecopyresampled($newImage, $img, 0,0,0,0, $neww, $newh, $iw, $ih);
    } else {//GD 1.8=only 256 colours
        $newImage=imagecreate($neww, $newh);
        imagecopyresized($newImage, $img, 0,0,0,0, $neww, $newh, $iw, $ih);
    }
    return $newImage;
}
?>