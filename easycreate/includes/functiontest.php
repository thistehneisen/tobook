<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
function pageBrowser($totalrows,$numLimit,$amm,$queryStr,$numBegin,$start,$begin,$num) {

        $larrow = "&nbsp;<<&nbsp;"; //You can either have an image or text, eg. Previous
        $rarrow = "&nbsp;>>&nbsp;"; //You can either have an image or text, eg. Next
        $wholePiece = ""; //This appears in front of your page numbers

        if ($totalrows > 0) {
                        $numSoFar = 1;
                        $cycle = ceil($totalrows/$amm);
                        if (!isset($numBegin) || $numBegin < 1) {
                                        $numBegin = 1;
                                        $num = 1;
                        }
                        if (!isset($start) || $start < 0) {
                                        $minus = $numBegin-1;
                                        $start = $minus*$amm;
                        }
                        if (!isset($begin)) {
                                        $begin = $start;
                        }
                        $preBegin = $numBegin-$numLimit;
                        $preStart = $amm*$numLimit;
                        $preStart = $start-$preStart;
                        $preVBegin = $start-$amm;
                        $preRedBegin = $numBegin-1;
                        if ($start > 0 || $numBegin > 1) {
                                        $wholePiece .= "<a href='?num=".$preRedBegin
                                                                        ."&start=".$preStart
                                                                        ."&numBegin=".$preBegin
                                                                        ."&begin=".$preVBegin
                                                                        .$queryStr."'>"
                                                                        .$larrow."</a>\n";
                        }
                        for ($i=$numBegin;$i<=$cycle;$i++) {
                                        if ($numSoFar == $numLimit+1) {
                                                        $piece = "<a href='?numBegin=".$i
                                                                        ."&num=".$i
                                                                        ."&start=".$start
                                                                        .$queryStr."'>"
                                                                        .$rarrow."</a>\n";
                                                        $wholePiece .= $piece;
                                                        break;
                                        }
                                        $piece = "<a href='?begin=".$start
                                                        ."&num=".$i
                                                        ."&numBegin=".$numBegin
                                                        .$queryStr
                                                        ."'>";
                                        if ($num == $i) {
                                                        $piece .= "<b>$i</b>";
                                        } else {
                                                        $piece .= "$i";
                                        }
                                        $piece .= "</a>\n";
                                        $start = $start+$amm;
                                        $numSoFar++;
                                        $wholePiece .= $piece;
                        }
                        $wholePiece .= "\n";
                        $wheBeg = $begin+1;
                        $wheEnd = $begin+$amm;
                        $wheToWhe = "<b>".$wheBeg."</b> - <b>";
                        if ($totalrows <= $wheEnd) {
                                        $wheToWhe .= $totalrows."</b>";
                        } else {
                                        $wheToWhe .= $wheEnd."</b>";
                        }
                        $sqlprod = " LIMIT ".$begin.", ".$amm;
        } else {
                        $wholePiece = "<br><br><p align=center><font size='1'>Sorry, no records to display.</font></p>";
                        $wheToWhe = "<b>0</b> - <b>0</b>";
        }

        return array($sqlprod,$wheToWhe,$wholePiece);
}



function getResizedImage($file,$img_width) {

  $img_temp = NewimageCreatefromtype($file);
  $black = @imagecolorallocate ($img_temp, 0, 0, 0);
  $white = @imagecolorallocate ($img_temp, 255, 255, 255);
  $font = 2;
  $img_height=@imagesx($img_temp)/imagesy($img_temp)*$img_width;
  $img_thumb=@imagecreatetruecolor($img_width,$img_height);
  @imagecopyresampled($img_thumb,$img_temp,0,0,0,0,$img_width,$img_height,@imagesx ($img_temp),@imagesy($img_temp));
  $originx = @imagesx($img_thumb) - 100;
  $originy = @imagesy($img_thumb) - 15;
  header ("Content-type: image/jpeg");
  imagejpeg($img_thumb, '', 60);

}


function NewimageCreatefromtype($image)
{
    list($width, $height, $type, $attr) = @getimagesize($image);
    // echo "inside";
    if ($type == "1") { // gif
        $returnimage = @imagecreatefromgif($image);
    } else if ($type == "2") { // jpeg
        $returnimage = @imagecreatefromjpeg($image);
    } else if ($type == "3") { // png
        $returnimage = @imagecreatefrompng($image);
    } else {
        $returnimage = "Not Supported";
    }
    return $returnimage;
}

function CutImage($file, $img_height, $waterMark)
{
    // echo "heree";
    $img_temp = NewimageCreatefromtype($file);

    $black = @imagecolorallocate ($img_temp, 0, 0, 0);
    $white = @imagecolorallocate ($img_temp, 255, 255, 255);
    $font = 2;
    $img_width = @imagesx($img_temp) / imagesy($img_temp) * $img_height;
        //echo "imagesx=".imagesx($img_temp)."imagewidth==".$img_width."image height==".$img_height;
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
    ReturnImagetype($img_thumb, $file, $file);
    // imagejpeg($img_thumb, $file, 60);
}
function Resizewatermarkimage($file, $img_height, $img_width){
  //$img_thumb = @imagecreatetruecolor($img_width, $img_height);
  $img_temp = NewimageCreatefromtype($file);
  $img_thumb = NewimageCreatefromtype($file); 
  echo "imagewidth==$img_width::$img_height";
    @imagecopyresized  ($img_thumb,
        $img_temp, 0, 0, 0, 0, $img_width,
        $img_height,
        $img_width,
        $img_height);

    //$originx = @imagesx($img_thumb) - 100;
    //$originy = @imagesy($img_thumb) - 15;
    //@imagestring ($img_thumb, $font, $originx + 10, $originy, $waterMark, $black);
    //@imagestring ($img_thumb, $font, $originx + 11, $originy - 1, $waterMark, $white);
    ReturnImagetype($img_thumb, $file, $file);
}
function Resizeimage($file, $img_height, $img_width)
{

    $img_temp = NewimageCreatefromtype($file);
    $black = @imagecolorallocate ($img_temp, 0, 0, 0);
    $white = @imagecolorallocate ($img_temp, 255, 255, 255);
    $font = 2;
        $cuurentimagewidth=@imagesx($img_temp);
        $cuurentimageheight=@imagesy($img_temp);
        if($img_height>=$img_width){
                          if($cuurentimagewidth>$img_width){
                               $newdith=$img_width-10;
                           }else{
                              $newdith=$cuurentimagewidth;
                           }
                           if($cuurentimageheight>$img_height){
                             $newheight=$img_height-($img_height/10);
                           }else{
                             $newheight=$cuurentimageheight;
                           }
                            $img_height=$newheight;
                            $img_width=$newdith;
        }else{
                           if($cuurentimagewidth>$img_width){
                               $newdith=$img_width-10;
                           }else{
                              $newdith=$cuurentimagewidth;
                           }
                           if($cuurentimageheight>$img_height){
                             $newheight=$img_height-($img_height/10);
                           }else{
                             $newheight=$cuurentimageheight;
                           }
                           $img_height=$newheight;
                           $img_width=$newdith;
        }
		//echo "imagewidth==$img_width::$img_height";
    $img_thumb = @imagecreatetruecolor($img_width, $img_height);
    @imagecopyresampled($img_thumb,
        $img_temp, 0, 0, 0, 0, $img_width,
        $img_height,
        @imagesx ($img_temp),
        @imagesy($img_temp));

    //$originx = @imagesx($img_thumb) - 100;
    //$originy = @imagesy($img_thumb) - 15;
    //@imagestring ($img_thumb, $font, $originx + 10, $originy, $waterMark, $black);
    //@imagestring ($img_thumb, $font, $originx + 11, $originy - 1, $waterMark, $white);
    ReturnImagetype($img_thumb, $file, $file);
}
function ImageType($image)
{
    list($width, $height, $type, $attr) = @getimagesize($image);
    switch ($type) {
        case 1 :
            $returntype = "gif";
            break;
        case 2 :
            $returntype = "jpg";
            break;
        case 3 :
            $returntype = "png";
            break;
        case 4 :
            $returntype = "swf";
            break;
        case 5 :
            $returntype = "psd";
            break;
        case 6 :
            $returntype = "bmp";
            break;
        case 7 :
            $returntype = "tiff";
            break;
        case 8 :
            $returntype = "tiff";
            break;
        default :
            $returntype = "notsupportted" ;
            break;
    }
    return $returntype . ":$width:$height";
}
function ReturnImagetype($newImage, $newfile, $editimagefile)
{
    list($width, $height, $type, $attr) = @getimagesize($editimagefile);
    $jpgCompression = "60";
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
function watermarkimage($watermarkimage)
{
    $size = @getimagesize($watermarkimage);
    $newimagetostore = $watermarkimage;
	$heightoforignalimage=$size[1];
	
	if($heightoforignalimage>50 and $heightoforignalimage<100){
	   $watermark = imagecreatefromgif('./images/watermarkflip50.gif');
	}
    
   	$watermark_width = imagesx($watermark);
    $watermark_height = imagesy($watermark);
    $dest_x = $size[0] / 2;
    $dest_y = 10;
    $image = NewimageCreatefromtype($watermarkimage);
    $rgb = @ImageColorAt($image, $dest_x, $dest_y);
    @imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 30); 
    ReturnImagetype($image, $newimagetostore, $watermarkimage);
}
function NewImagetype($newImage, $newfile, $editimagefile)
{
    list($width, $height, $type, $attr) = @getimagesize($editimagefile);

    $jpgCompression = "60";
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

function embedimage($image1, $image2)
{
    $newimagetostore = $image1;
    $embedimage = NewimageCreatefromtype($image2);
    $image = NewimageCreatefromtype($image1);
    $embedimage_width = imagesx($embedimage);
    $embedimage_height = imagesy($embedimage);
    $actualimage_width = imagesx($image);
    $actualmage_height = imagesy($image);
    $embedimagesize = @getimagesize($embedimage);
    $size = @getimagesize($image1);
    $dest_x = ($size[0]-$embedimage_width)/2;
    $dest_y = ($size[1]-$embedimage_height)/2;;
    $rgb = @ImageColorAt($image, $dest_x, $dest_y);
    imagecopymerge($image, $embedimage, $dest_x, $dest_y, 0, 0, $embedimage_width, $embedimage_height, 100);
    ReturnImagetype($image, $newimagetostore, $image1);
}
function copydirr($fromDir, $toDir, $chmod = 0757, $verbose = false)
/*
   copies everything from directory $fromDir to directory $toDir
   and sets up files mode $chmod
*/
{
    // * Check for some errors
    $errors = array();
    $messages = array();
    if (!is_writable($toDir))
        $errors[] = 'target ' . $toDir . ' is not writable';
    if (!is_dir($toDir))
        $errors[] = 'target ' . $toDir . ' is not a directory';
    if (!is_dir($fromDir))
        $errors[] = 'source ' . $fromDir . ' is not a directory';
    if (!empty($errors)) {
        if ($verbose)
            foreach($errors as $err)
            echo '<strong>Error</strong>: ' . $err . '<br />';
        return false;
    }
    // */
    $exceptions = array('.', '..');
    // * Processing
    $handle = opendir($fromDir);
    while (false !== ($item = readdir($handle)))
    if (!in_array($item, $exceptions)) {
        // * cleanup for trailing slashes in directories destinations
        $from = str_replace('//', '/', $fromDir . '/' . $item);
        $to = str_replace('//', '/', $toDir . '/' . $item);
        // */
        if (is_file($from)) {
            if (@copy($from, $to)) {
                chmod($to, $chmod);
                touch($to, filemtime($from)); // to track last modified time
                $messages[] = 'File copied from ' . $from . ' to ' . $to;
            } else
                $errors[] = 'cannot copy file from ' . $from . ' to ' . $to;
        }
        if (is_dir($from)) {
            if (@mkdir($to,0777)) {
                chmod($to, $chmod);
                $messages[] = 'Directory created: ' . $to;
            } else
                $errors[] = 'cannot create directory ' . $to;
            copydirr($from, $to, $chmod, $verbose);
        }
    }
    closedir($handle);
    // */
    // * Output
    if ($verbose) {
        foreach($errors as $err)
        echo '<strong>Error</strong>: ' . $err . '<br />';
        foreach($messages as $msg)
        echo $msg . '<br />';
    }
    // */
    return true;
}

?>