<?php
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
?>
<?php
function pageBrowser($totalrows, $numLimit, $amm, $queryStr, $numBegin, $start, $begin, $num)
{
    $larrow = "&nbsp;Prev&nbsp;"; //You can either have an image or text, eg. Previous
    $rarrow = "&nbsp;Next&nbsp;"; //You can either have an image or text, eg. Next
    $wholePiece = ""; //This appears in front of your page numbers
    
    if ($totalrows > 0) {
        $numSoFar = 1;
        $cycle = ceil($totalrows / $amm);
        if (!isset($numBegin) || $numBegin < 1) {
            $numBegin = 1;
            $num = 1;
        } 
        if (!isset($start) || $start < 0) {
            $minus = $numBegin-1;
            $start = $minus * $amm;
        } 
        if (!isset($begin)) {
            $begin = $start;
        } 
        $preBegin = $numBegin - $numLimit;
        $preStart = $amm * $numLimit;
        $preStart = $start - $preStart;
        $preVBegin = $start - $amm;
        $preRedBegin = $numBegin-1;
        if ($start > 0 || $numBegin > 1) {
            $wholePiece .= "<a href='?num=" . $preRedBegin
             . "&start=" . $preStart
             . "&numBegin=" . $preBegin
             . "&begin=" . $preVBegin
             . $queryStr . "'>"
             . $larrow . "</a>\n";
        } 
        for ($i = $numBegin;$i <= $cycle;$i++) {
            unset($piece);
            if ($numSoFar == $numLimit + 1) {
                $piece = "<a href='?numBegin=" . $i
                 . "&num=" . $i
                 . "&start=" . $start
                 . $queryStr . "'>"
                 . $rarrow . "</a>\n";
                 $wholePiece .= $piece;  
                break; 
            } 
           
            if ($num != $i) { 
                $piece = "<a href='?begin=" . $start . "&num=" . $i . "&numBegin=" . $numBegin . $queryStr . "'>";
				$piece .= "<b>$i | </b>";
				$piece .= "</a>\n";
            } else { 
                $piece .= "<span class='active'>$i </span> | \n";
            } 
            
            $start = $start + $amm;
            $numSoFar++;
            $wholePiece .= $piece;
        } 
        $wholePiece .= "\n";
        $wheBeg = $begin + 1;
        $wheEnd = $begin + $amm;
        $wheToWhe = "<b>" . $wheBeg . "</b> - <b>";
        if ($totalrows <= $wheEnd) {
            $wheToWhe .= $totalrows . "</b>";
        } else {
            $wheToWhe .= $wheEnd . "</b>";
        } 
        $sqlprod = " LIMIT " . $begin . ", " . $amm;
    } else {
        $wholePiece = "<br><p align=center><font size='2'>".PAGINATION_NO_DATA.".</font></p>";
        $wheToWhe = "<b>0</b> - <b>0</b>";
    } 

	$wholePiece		= "<span style='width:200px;border:none; text-align:right'>".$wholePiece."</span>";
    return array($sqlprod, $wheToWhe, $wholePiece);
} 
function getResizedImage($file, $img_width)
{
    $img_temp = NewimageCreatefromtype($file);
    $black = @imagecolorallocate ($img_temp, 0, 0, 0);
    $white = @imagecolorallocate ($img_temp, 255, 255, 255);
    $font = 2;
    $img_height = @imagesx($img_temp) / imagesy($img_temp) * $img_width;
    $img_thumb = @imagecreatetruecolor($img_width, $img_height);
    @imagecopyresampled($img_thumb, $img_temp, 0, 0, 0, 0, $img_width, $img_height, @imagesx ($img_temp), @imagesy($img_temp));
    $originx = @imagesx($img_thumb) - 100;
    $originy = @imagesy($img_thumb) - 15;
    header ("Content-type: image/jpeg");
    imagejpeg($img_thumb, '', 60);
} 
/* NewimageCreatefromtype($image)
*  $image->image name
*  This function will return image resource from $image,supported image types are gif,png,jpg
*/ 
function NewimageCreatefromtype($image)
{
    list($width, $height, $type, $attr) = @getimagesize($image); 
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
/* CutImage($file, $img_height, $waterMark)
*  $file ->image name
*  $img_height ->the image will resize to this height
*  $waterMark -> The watermark string to be written  on the image
*/
function CutImage($file, $img_height, $waterMark)
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
    ReturnImagetype($img_thumb, $file, $file); 
}
/*Resizeimage($file, $img_height, $img_width, $tosavefileas = null)
* $file ->image name
* $img_height -> image will be resized to this height
* $img_width ->image will be resized to this width
* $tosavefileas ->image name to be saved.if not provided $file will be overwrite 
*/  
function Resizeimage($file, $img_height, $img_width, $tosavefileas = null)
{
    if (!isset($tosavefileas)) {
        $tosavefileas = $file;
    } 
    $img_temp = NewimageCreatefromtype($file);
    $black = @imagecolorallocate ($img_temp, 0, 0, 0);
    $white = @imagecolorallocate ($img_temp, 255, 255, 255);
    $font = 2;
    $cuurentimagewidth = @imagesx($img_temp);
    $cuurentimageheight = @imagesy($img_temp);
    /*  if the given img_height is greater than img_width
	*   	check $img_height greater than $img_width(portrait image)
	*             final image width will be $img_width-10 and final image height will be $img_height - ($img_height / 10)
	*              
	*      otherwsie
	*             final image height will be $img_height-10 and final image width will be $img_width - ($img_width / 10)
	* 
	* 
	*/  
    if ($img_height >= $img_width) {
        if ($cuurentimagewidth > $img_width) {
            $newdith = $img_width-10;
        } else {
            $newdith = $cuurentimagewidth;
        } 
        if ($cuurentimageheight > $img_height) {
            $newheight = $img_height - ($img_height / 10);
        } else {
            $newheight = $cuurentimageheight;
        } 
        $img_height = $newheight;
        $img_width = $newdith;
    } else {
        if ($cuurentimagewidth > $img_width) {
            $newdith = $img_width - ($img_width / 10);
        } else {
            $newdith = $cuurentimagewidth;
        } 
        if ($cuurentimageheight > $img_height) {
            $newheight = $img_height - ($img_height / 10);
        } else {
            $newheight = $cuurentimageheight;
        } 
        $img_height = $newheight;
        $img_width = $newdith;
    } 
    
    list($originalwidth, $originalwidth, $originaltype) = getimagesize($file);
	/* for gif image keep transparency */
    if ($originaltype == "1") { // gif
        $newwidth = $img_width;
        $newheight = $img_height;
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
            imagegif($img_thumb, $tosavefileas);
    } else {
        $img_thumb = @imagecreatetruecolor($img_width, $img_height);
        @imagecopyresampled($img_thumb, $img_temp, 0, 0, 0, 0, $img_width, $img_height, @imagesx ($img_temp), @imagesy($img_temp));

        ReturnImagetype($img_thumb, $tosavefileas, $file);
    } 
} 
function ResizeImageTogivenWitdhAndHeight($file, $img_height, $img_width, $tosavefileas = null)
{
    if (!isset($tosavefileas)) {
        $tosavefileas = $file;
    } 

    $img_temp = NewimageCreatefromtype($file);
    $black = @imagecolorallocate ($img_temp, 0, 0, 0);
    $white = @imagecolorallocate ($img_temp, 255, 255, 255);
    $font = 2;
    $cuurentimagewidth = @imagesx($img_temp);
    $cuurentimageheight = @imagesy($img_temp);

    list($originalwidth, $originalheight, $originaltype) = getimagesize($file);
    if ($originaltype == "1") { // gif
        $newwidth = $img_width;
        $newheight = $img_height;
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
            imagegif($img_thumb, $tosavefileas);
    } else {
        $img_thumb = @imagecreatetruecolor($img_width, $img_height);
        @imagecopyresampled($img_thumb, $img_temp, 0, 0, 0, 0, $img_width, $img_height, @imagesx ($img_temp), @imagesy($img_temp));
        ReturnImagetype($img_thumb, $tosavefileas, $file);
    } 
} 
function ImageType($image)
{ 
    // echo "<br>image==".$image;
	
    list($width, $height, $type, $attr) = getimagesize($image);

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
function watermarkimage($watermarkimage, $saveimageas = null, $watermarkloc = "./images/watermark.gif")
{
    if (!isset($saveimageas)) {
        $saveimageas = $watermarkimage;
    } 
    $size = @getimagesize($watermarkimage);
    $newimagetostore = $watermarkimage;
    $heightoforignalimage = $size[1];
    $watermark = imagecreatefromgif($watermarkloc);
    $watermark_width = imagesx($watermark);
    $watermark_height = imagesy($watermark);
    $dest_x = $size[0] / 2;
    $dest_y = 1;
    $image = NewimageCreatefromtype($watermarkimage);
    $rgb = @ImageColorAt($image, $dest_x, $dest_y);
    $currentdetinationycordinate = $dest_y; 
    // echo "<br>$watermarkimage::::$currentdetinationycordinate:::$size[1]";
    while ($currentdetinationycordinate < $size[1]) {
        @imagecopymerge($image, $watermark, $dest_x, $currentdetinationycordinate, 0, 0, $watermark_width, $watermark_height, 30);
        ReturnImagetype($image, $saveimageas, $watermarkimage);

        $currentdetinationycordinate = $currentdetinationycordinate + $watermark_height;
    } 
} 
function NewImagetype($newImage, $newfile, $editimagefile)
{
    list($width, $height, $type, $attr) = @getimagesize($editimagefile);

    $jpgCompression = "100";
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
    $dest_x = ($size[0] - $embedimage_width) / 2;
    $dest_y = ($size[1] - $embedimage_height) / 2;;
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
                @chmod($to, $chmod); 
                // echo "<br>to==".$to."success";
                @touch($to, filemtime($from)); // to track last modified time
                $messages[] = 'File copied from ' . $from . ' to ' . $to;
            } else
                $errors[] = 'cannot copy file from ' . $from . ' to ' . $to;
        } 
        if (is_dir($from)) {
            if (@mkdir($to, 0777)) {
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
function copyfilesdirr($fromDir, $toDir, $chmod = 0757, $verbose = false)
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
                @chmod($to, $chmod); 
                // echo "<br>to==".$to."success";
                @touch($to, filemtime($from)); // to track last modified time
                $messages[] = 'File copied from ' . $from . ' to ' . $to;
            } else
                $errors[] = 'cannot copy file from ' . $from . ' to ' . $to;
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
function remove_dir($dir)
{
    $handle = @opendir($dir);
    while (false !== ($item = @readdir($handle))) {
        if ($item != '.' && $item != '..') {
            if (is_dir($dir . '/' . $item)) {
                remove_dir($dir . '/' . $item);
            } else {
                @unlink($dir . '/' . $item);
            } 
        } 
    } 
    @closedir($handle);
    if (@rmdir($dir)) {
        $success = true;
    } 
    return $success;
} 

function makeDropDownList($ddlname, $list, $selectedindex, $emptyrequired, $class, $properties, $behaviors)
{ 
    $ddl = "";
    $properties = trim($properties);
    $class = trim($class);
    $properties = trim($properties);
    $behaviors = trim($behaviors);

    $ddl .= "<select name=\"$ddlname\" class=\"$class\" ";
    if ($properties != "") {
        $ddl .= "  $properties  ";
    } 
    if ($behaviors != "") {
        $ddl .= " $behaviors ";
    } 
    $ddl .= " >";
    if ($emptyrequired) {
        $ddl .= "<option value=''";
        $ddl .= "></option>\n";
    } 
    if (count($list) > 0) {
        foreach($list as $key => $value) {
            $ddl .= "<option value= \"" . htmlentities($key) . "\" ";
            if (is_array($selectedindex)) {
                if (in_array("$key", $selectedindex)) {
                    $ddl .= " selected=\"selected\"";
                } 
            } else {
                if ($selectedindex == "$key") {
                    $ddl .= " selected=\"selected\"";
                } 
            } 

            $ddl .= ">" . htmlentities($value) . "</option>\n";
        } 
    } 
    $ddl .= "</select>";
    return $ddl;
} 

function makeCategoryList()
{
    static $options;
    $sql = " SELECT ngcat_id,vcat_name FROM tbl_gallery_category ";
    $resoptions = mysql_query($sql);
    $numoptions = mysql_num_rows($resoptions);
    if ($numoptions > 0) {
        while (list($stid, $stname) = mysql_fetch_row($resoptions)) {
            $options[$stid] = $stname ;
        } 
    } 
    return $options;
} 

function isNotNull($value)
{
    if (is_array($value)) {
        if (sizeof($value) > 0) {
            return true;
        } else {
            return false;
        } 
    } else {
        if (($value != '') && (strtolower($value) != 'null') && (strlen(trim($value)) > 0)) {
            return true;
        } else {
            return false;
        } 
    } 
} 

function isValidWebImageType($mimetype,$filename,$tempname)
{
	$blacklist = array("php", "phtml", "php3", "php4", "js", "shtml", "pl" ,"py", "exe");
	foreach ($blacklist as $file)
	{
		if(preg_match("/\.$file\$/i", "$filename"))
		{
			return false;
		}
	}
	//check if its image file
	if (!getimagesize($tempname))
	{
		return false;
	}
    if (($mimetype == "image/pjpeg") || ($mimetype == "image/jpeg") || ($mimetype == "image/x-png") || ($mimetype == "image/png") || ($mimetype == "image/gif")) {
        return true;
    } else {
        return false;
    } 
} 
/* function used both save and preview sites that are craeted or edited with simple templates
* pymentflag-if it is set to 'Yes' assumes that sites are created and payment id done
* currentloc-location from which the fucntion is called ie-.. or .
* mode-which operation is performed by this function.eg-EditSiteDetails
**/
function SaveSitePreview($userid, $templateid, $tmpsiteid, $siteid, $paymentflag, $currentloc, $mode, $tempFolderID=0)
{


    ini_set("magic_quotes_runtime", 0);
    if ($paymentflag == "Yes") {
        $qry = " select * from tbl_site_mast where nsite_id='" . $siteid . "'";
        $rs = mysql_query($qry);
        $row = mysql_fetch_array($rs);
        $templateype = $row['vtype']; 
        // create neccessary directories .if not exist
		
        if (!is_dir($currentloc . "/workarea/sites/$siteid")) {
            mkdir($currentloc . "/workarea/sites/$siteid", 0777);
			chmod($currentloc . "/workarea/sites/$siteid", 0777);
		    mkdir($currentloc . "/workarea/sites/$siteid/images", 0777);
			chmod($currentloc . "/workarea/sites/$siteid/images", 0777);
			mkdir($currentloc . "/workarea/sites/$siteid/flash", 0777);
			chmod($currentloc . "/workarea/sites/$siteid/flash", 0777);
        }
        
        // copy sitepages
        if (!is_dir($currentloc . "/sites/$siteid")) {
            mkdir($currentloc . "/sites/$siteid", 0777);
			chmod($currentloc . "/sites/$siteid", 0777);
        }
//        copydirr($currentloc ."/workarea/tempsites/". $tempFolderID . "/images", $currentloc . "/workarea/sites/" . $siteid . "/images", 0777, false);
        if($tempFolderID>0 && is_dir($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images") )
        {
            copydirr($currentloc ."/workarea/tempsites/". $tempFolderID . "/images", $currentloc . "/workarea/sites/" . $siteid . "/images", 0777, false);
        }

// copy template images and style
        copydirr($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images", $currentloc . "/workarea/sites/" . $siteid . "/images", 0777, false);
        @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/style.css", $currentloc . "/workarea/sites/" . $siteid . "/style.css");
        $_SESSION['session_sitename'] = $row['vsite_name'];
        if (strcasecmp($templateype, "simple") == 0) { // check if simple
            if ($mode == "EditSiteDetails") {
                $_SESSION['session_logobandname'] = $row['vlogo'];
                $_SESSION['session_innerlogobandname'] = $row['vsub_logo'];
                $_SESSION['session_companybandname'] = $row['vcompany'];
                $_SESSION['session_innercompanybandname'] = $row['vsub_company'];
                $_SESSION['session_captionbandname'] = $row['vcaption'];
                $_SESSION['session_innercaptionbandname'] = $row['vsub_caption'];
                $_SESSION['session_sitemetadesc'] = addslashes($row['vmeta_description']);
                $_SESSION['session_sitemetakey'] = addslashes($row['vmeta_key']);
                $_SESSION['session_sitetitle'] = addslashes($row['vtitle']);
                $_SESSION['session_sitemeemail'] = $row['vemail'];
                $_SESSION['session_sitecolor'] = $row['vcolor'];

                $_SESSION['session_comselfontclr'] = "";
                $_SESSION['session_comselfont'] = "";
                $_SESSION['session_comselfontsize'] = "";
                $_SESSION['session_comseltext'] = "";
                $_SESSION['session_capselfontclr'] = "";
                $_SESSION['session_capselfont'] = "";
                $_SESSION['session_capselfontsize'] = "";
                $_SESSION['session_capseltext'] = "";
            } 
            // strip slashes and put htmlentities for title,metadesc,metakey
            $row['vmeta_description'] = htmlentities($row['vmeta_description']);
            $row['vmeta_key'] = htmlentities($row['vmeta_key']);
            $row['vtitle'] = htmlentities($row['vtitle']);
            if (strcasecmp(substr($row['vlogo'], 0, 2), "tp") == "0") {
                @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $row['vlogo'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vlogo']);
                @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $row['vsub_logo'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vsub_logo']);
            } else if (strcasecmp(substr($row['vlogo'], 0, 2), "ug") == "0") {
                @copy($currentloc . "/usergallery/" . $_SESSION["session_userid"] . "/images/" . $row['vlogo'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vlogo']);
                @copy($currentloc . "/usergallery/" . $_SESSION["session_userid"] . "/images/" . $row['vsub_logo'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vsub_logo']); 
                // echo "hereeee".$row['vsub_logo'];
            } 
            if (strcasecmp(substr($row['vcompany'], 0, 2), "tp") == "0") {
                @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $row['vcompany'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vcompany']);
                @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $row['vsub_company'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vsub_company']);
            } else if (strcasecmp(substr($row['vcompany'], 0, 2), "ug") == "0") {
                @copy($currentloc . "/usergallery/" . $_SESSION["session_userid"] . "/images/" . $row['vcompany'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vcompany']);
                @copy($currentloc . "/usergallery/" . $_SESSION["session_userid"] . "/images/" . $row['vsub_company'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vsub_company']);
            } 
            if (strcasecmp(substr($row['vcaption'], 0, 2), "tp") == "0") {
                @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $row['vcaption'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vcaption']);
                @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $row['vsub_caption'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vsub_caption']);
            } else if (strcasecmp(substr($row['vcaption'], 0, 2), "ug") == "0") {
                @copy($currentloc . "/usergallery/" . $_SESSION["session_userid"] . "/images/" . $row['vcaption'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vcaption']);
                @copy($currentloc . "/usergallery/" . $_SESSION["session_userid"] . "/images/" . $row['vsub_caption'], $currentloc . "/workarea/sites/" . $siteid . "/images/" . $row['vsub_caption']);
            } 
        } 
        // copy the files in resource
        if (!is_file($currentloc . "/sites/$siteid/resource.txt")) {
            echo "Resource file does not exist";
            exit;
        } 

        $handle = fopen($currentloc . "/sites/$siteid/resource.txt", "r");
        while (!feof($handle)) {
            $buffer = fgets($handle, 4096);
            $newfilename = basename($buffer);
            $buffer = trim($buffer);
            if ($buffer != "") {
                if (strcasecmp($templateype, "simple") == 0) {
                    @copy("./" . $buffer, "./workarea/sites/$siteid/images/" . trim($newfilename) . "");
                } else {
                    // echo "copying==".substr($buffer,3 );
                    $buffer = substr($buffer, 3);
					if(strcasecmp(substr(basename($buffer),0,2),"fl")=="0"){
					  //echo "<br>copyingflash=="."./" . $buffer;
					  @copy("./" . $buffer, "./workarea/sites/$siteid/flash/" . trim($newfilename) . "");
					}else{
					//echo "<br>copying=="."./" . $buffer;
					   @copy("./" . $buffer, "./workarea/sites/$siteid/images/" . trim($newfilename) . "");
					}
                   
                } 
            } 
        } 
        @copy($currentloc . "/sites/" . $siteid . "/resource.txt", $currentloc . "/workarea/sites/" . $siteid . "/resource.txt"); 
        // call smarty libraries
        if (strcasecmp($templateype, "simple") == 0) {
            require($currentloc . '/smarty/lib/Smarty.class.php');
            if(is_dir($currentloc . '/smarty/templates_c')){
                remove_dir($currentloc . '/smarty/templates_c');
            }
            @mkdir($currentloc . "/smarty/templates_c", 0777);
            @chmod($currentloc . "/smarty/templates_c", 0777);
            if ($row['vcolor'] != "NULL" and $row['vcolor'] != "") {
                $siteclrvalue = ".variable { background-color:" . $row['vcolor'] . "; }";
            } else {
                $siteclrvalue = "";
            } 
            // $siteclrvalue=".variable { background-color:".$row['vcolor']."; }";
            $smarty = new Smarty();
            $smarty->template_dir = $currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid;
            $smarty->compile_dir = $currentloc . "/smarty/templates_c";
            $smarty->cache_dir = $currentloc . "/smarty/cache";
            $smarty->config_dir = $currentloc . "/smarty/configs"; 
            // clear the cache
            $qry = "select * from tbl_site_pages where nsite_id='" . $siteid . "' order by nsp_id";
            $rs1 = mysql_query($qry);
            $editsitelinks = "";
            $editsitelinktype = "";

            while ($row1 = mysql_fetch_array($rs1)) {
                $editsitelinks .= $row1['vpage_name'] . ",";
                $editsitelinktype .= $row1['vpage_type'] . ",";
                $row1['vpage_name'] = str_replace(" ", "" , $row1['vpage_name']);
                if (strcasecmp($row1['vpage_type'], "Guestbook") == 0) {
                    $pagename = strtolower($row1['vpage_name']) . ".php";
                    $fp = fopen($currentloc . "/workarea/sites/" . $siteid . "/gb.txt", "w");
                    fputs($fp, '.');
                    fclose($fp);
                } else {
                    $pagename = strtolower($row1['vpage_name']) . ".htm";
                } 

                if (strcmp($pagename, "home.htm") == 0 or $row1['vpage_type'] == "homepage") {
                    if (!is_file($currentloc . "/sites/$siteid/$pagename")) {
                        echo "Page could not be found.";
                        exit;
                    } 
                    $filecontent = file_get_contents ($currentloc . "/sites/$siteid/$pagename");
                    $smarty->clear_cache('index.tpl');
                    $smarty->assign('vsite_metadesc', $row['vmeta_description']);
                    $smarty->assign('vsite_metakey', $row['vmeta_key']);
                    $smarty->assign('vsite_title', $row['vtitle']);
                    $smarty->assign('vsitecolor', $siteclrvalue);
                    $smarty->assign('vlogoband', $row['vlogo']);
                    $smarty->assign('vcompanyband', $row['vcompany']);
                    $smarty->assign('captionband', $row['vcaption']);
                    $smarty->assign('vsite_links', $row['vlinks']);
                    $smarty->assign('vsite_editable', $filecontent);
                    $html = $smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
                    $fp = fopen($currentloc . "/workarea/sites/" . $siteid . "/$pagename", "w");
                    fputs($fp, $html);
                    fclose($fp);
                } else {
                    if (!is_file($currentloc . "/sites/$siteid/$pagename")) {
                        echo "Page could not be found.";
                        exit;
                    } 
                    $filecontent = file_get_contents ($currentloc . "/sites/$siteid/$pagename");
                    $smarty->clear_cache('subpage.tpl');
                    $smarty->assign('vsite_metadesc', $row['vmeta_description']);
                    $smarty->assign('vsite_metakey', $row['vmeta_key']);
                    $smarty->assign('vsite_title', $row['vtitle']);
                    $smarty->assign('vsitecolor', $siteclrvalue);
                    $smarty->assign('vinnserlogoband', $row['vsub_logo']);
                    $smarty->assign('vinnercompanyband', $row['vsub_company']);
                    $smarty->assign('innercaptionband', $row['vsub_caption']);
                    $smarty->assign('vsite_links', $row['vsub_sitelinks']);
                    $smarty->assign('vsubsite_editable', $filecontent);

                    $html = $smarty->fetch('subpage.tpl', $cache_id = null, $compile_id = null, false);
                    $fp = fopen($currentloc . "/workarea/sites/" . $siteid . "/$pagename", "w");
                    fputs($fp, $html);
                    fclose($fp);
                } 
            } 
            $editsitelinks = substr($editsitelinks, 0, -1);
            $editsitelinktype = substr($editsitelinktype, 0, -1);
            if ($mode == "EditLinks") {
                $_SESSION['session_sitelinks'] = $editsitelinks;
                $_SESSION['session_sitelinkstype'] = $editsitelinktype;
            } 
        } else {
            $qry = "select * from tbl_site_pages where nsite_id='" . $siteid . "' order by nsp_id";
            $rs1 = mysql_query($qry);
            while ($row1 = mysql_fetch_array($rs1)) {
                $pagename = $row1['vpage_name'];
                if (strcasecmp($row1['vpage_type'], "Guestbook") == 0) {
                    $fp = fopen($currentloc . "/workarea/sites/" . $siteid . "/gb.txt", "w");
                    fputs($fp, '.');
                    fclose($fp);
                } 
                @copy($currentloc . "/sites/" . $siteid . "/$pagename", $currentloc . "/workarea/sites/" . $siteid . "/$pagename");
            } 
        } 
    } else {
        // html files and images are located in workarea/tempsites/tempsitedid
        @copy($currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid . "/style.css", $currentloc . "/workarea/tempsites/" . $tmpsiteid . "/style.css");

        $qry = " select * from tbl_tempsite_mast where ntempsite_id='" . $tmpsiteid . "'";
        $rs = mysql_query($qry);
        $row = mysql_fetch_array($rs);
        $templateype = $row['vtype']; 
        // echo "temptype=".$templateype;
        if (strcasecmp($templateype, "simple") == 0) {
            $_SESSION['session_sitename'] = $row['vsite_name'];
            if ($mode == "EditSiteDetails") {
                $_SESSION['session_logobandname'] = $row['vlogo'];
                $_SESSION['session_innerlogobandname'] = $row['vsub_logo'];
                $_SESSION['session_companybandname'] = $row['vcompany'];
                $_SESSION['session_innercompanybandname'] = $row['vsub_company'];
                $_SESSION['session_captionbandname'] = $row['vcaption'];
                $_SESSION['session_innercaptionbandname'] = $row['vsub_caption'];
                $_SESSION['session_sitemetadesc'] = addslashes($row['vmeta_description']);
                $_SESSION['session_sitemetakey'] = addslashes($row['vmeta_key']);
                $_SESSION['session_sitetitle'] = addslashes($row['vtitle']);
                $_SESSION['session_sitemeemail'] = $row['vemail'];
                $_SESSION['session_sitecolor'] = $row['vcolor'];
                $_SESSION['session_comselfontclr'] = "";
                $_SESSION['session_comselfont'] = "";
                $_SESSION['session_comselfontsize'] = "";
                $_SESSION['session_comseltext'] = "";
                $_SESSION['session_capselfontclr'] = "";
                $_SESSION['session_capselfont'] = "";
                $_SESSION['session_capselfontsize'] = "";
                $_SESSION['session_capseltext'] = "";
            } 
            // strip slashes and put htmlentities
            $row['vmeta_description'] = htmlentities($row['vmeta_description']);
            $row['vmeta_key'] = htmlentities($row['vmeta_key']);
            $row['vtitle'] = htmlentities($row['vtitle']); 
            // call smarty libraries
            $editsitelinks = "";
            $editsitelinktype = "";
            require($currentloc . '/smarty/lib/Smarty.class.php');
            remove_dir($currentloc . '/smarty/templates_c');
            @mkdir($currentloc . "/smarty/templates_c", 0777);
            @chmod($currentloc . "/smarty/templates_c", 0777);

            if ($row['vcolor'] != "NULL" and $row['vcolor'] != "") {
                $siteclrvalue = ".variable { background-color:" . $row['vcolor'] . "; }";
            } else {
                $siteclrvalue = "";
            } 
            $siteclrvalue = ".variable { background-color:" . $row['vcolor'] . "; }";
            $smarty = new Smarty();
            $smarty->template_dir = $currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid;
            $smarty->compile_dir = $currentloc . "/smarty/templates_c";
            $smarty->cache_dir = $currentloc . "/smarty/cache";
            $smarty->config_dir = $currentloc . "/smarty/configs"; 
            // clear the cache
            $qry = "select * from tbl_tempsite_pages where ntempsite_id='" . $tmpsiteid . "' order by ntempsp_id";
            $rs1 = mysql_query($qry);
            while ($row1 = mysql_fetch_array($rs1)) {
                $editsitelinks .= $row1['vpage_name'] . ",";
                $editsitelinktype .= $row1['vpage_type'] . ",";

                $row1['vpage_name'] = str_replace(" ", "" , $row1['vpage_name']);
                if (strcasecmp($row1['vpage_type'], "Guestbook") == 0) {
                    $pagename = strtolower($row1['vpage_name']) . ".php";
                    $fp = fopen($currentloc . "/workarea/tempsites/" . $tmpsiteid . "/gb.txt", "w");
                    fputs($fp, '.');
                    fclose($fp);
                } else {
                    $pagename = strtolower($row1['vpage_name']) . ".htm";
                } 
                // $pagename=strtolower($row1['vpage_name']).".htm";
                if (strcmp($pagename, "home.htm") == 0 or $row1['vpage_type'] == "homepage") {
                    if (!is_file($currentloc . "/sitepages/tempsites/$tmpsiteid/$pagename")) {
                        echo "Page could not be found.";
                        exit;
                    } 
                    $filecontent = file_get_contents ($currentloc . "/sitepages/tempsites/$tmpsiteid/$pagename");
                    $smarty->clear_cache('index.tpl');
                    $smarty->assign('vsite_metadesc', $row['vmeta_description']);
                    $smarty->assign('vsite_metakey', $row['vmeta_key']);
                    $smarty->assign('vsite_title', $row['vtitle']);
                    $smarty->assign('vsitecolor', $siteclrvalue);
                    $smarty->assign('vlogoband', $row['vlogo']);
                    $smarty->assign('vcompanyband', $row['vcompany']);
                    $smarty->assign('captionband', $row['vcaption']);
                    $smarty->assign('vsite_links', $row['vlinks']);
                    $smarty->assign('vsite_editable', $filecontent);
                    $html = $smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
                    $fp = fopen($currentloc . "/workarea/tempsites/" . $tmpsiteid . "/$pagename", "w");
                    fputs($fp, $html);
                    fclose($fp);
                } else {
                    if (!is_file($currentloc . "/sitepages/tempsites/$tmpsiteid/$pagename")) {
                        echo "Page could not be found.";
                        exit;
                    } 
                    $filecontent = file_get_contents ($currentloc . "/sitepages/tempsites/$tmpsiteid/$pagename");
                    $smarty->clear_cache('subpage.tpl');
                    $smarty->assign('vsite_metadesc', $row['vmeta_description']);
                    $smarty->assign('vsite_metakey', $row['vmeta_key']);
                    $smarty->assign('vsite_title', $row['vtitle']);
                    $smarty->assign('vsitecolor', $siteclrvalue);
                    $smarty->assign('vinnserlogoband', $row['vsub_logo']);
                    $smarty->assign('vinnercompanyband', $row['vsub_company']);
                    $smarty->assign('innercaptionband', $row['vsub_caption']);
                    $smarty->assign('vsite_links', $row['vsub_sitelinks']);
                    $smarty->assign('vsubsite_editable', $filecontent);
                    $html = $smarty->fetch('subpage.tpl', $cache_id = null, $compile_id = null, false);
                    $fp = fopen($currentloc . "/workarea/tempsites/" . $tmpsiteid . "/$pagename", "w");
                    fputs($fp, $html);
                    fclose($fp);
                } 
            } 
            $editsitelinks = substr($editsitelinks, 0, -1);
            $editsitelinktype = substr($editsitelinktype, 0, -1);
            if ($mode == "EditLinks") {
                $_SESSION['session_sitelinks'] = $editsitelinks;
                $_SESSION['session_sitelinkstype'] = $editsitelinktype;
            } 
        } 
    } 
} 
// function to get the resource text
function getResourceText($dir, $filename)
{
    static $result;
    if (is_dir($dir)) {
        if (is_file($dir . "/" . $filename)) {
            $handle = fopen($dir . "/" . $filename, "r");
            if (filesize($dir . "/" . $filename) > 0) {
                $contents = fread($handle, filesize($dir . "/" . $filename));
                $result .= $contents;
            } 
        } 
        if ($dh = opendir($dir)) {
            while (($file = readdir($dh)) !== false) {
                if ((filetype($dir . "/" . $file) == "dir") and (($file != ".") and ($file != ".."))) {
                    getResourceText($dir . "/" . $file , $filename);
                } 
            } 
            closedir($dh);
        } 
    } 
    return $result;
} 

function isValidEmail($email)
{
    $email = trim($email);
    if ($email == "") {
        return false;
    } 
   /*  if (!eregi("^" . "[a-z0-9]+([_\\.-][a-z0-9]+)*" . // user
            "@" . "([a-z0-9]+([\.-][a-z0-9]+)*)+" . // domain
            "\\.[a-z]{2,}" . // sld, tld
            "$", $email, $regs)) { */

        if (!preg_match("/^" . "[a-z0-9]+([_\\.-][a-z0-9]+)*" . // user
            "@" . "([a-z0-9]+([\.-][a-z0-9]+)*)+" . // domain
            "\\.[a-z]{2,}" . // sld, tld
            "$/", $email, $regs)) {
        return false;
    } else {
        return true;
    } 
} 
// jimz - October 22,2005
// fetches till nth last occurance ofthe character /
// usage                ==>                fetchTillLast("http://192.168.0.11/sitebuilder/code/test.php","/",2)
// returns         ==>                http://192.168.0.11/sitebuilder
function fetchTillLast($url, $char, $pos = 1)
{
    return ($pos > 1)?fetchTillLast(substr($url, 0, strrpos($url, "/")), $char, --$pos):substr($url, 0, strrpos($url, $char));
} 
// jimz - October 22,2005
// fetches the full url to the location of the sitebuilder install folder
// $pos is the depth level
// if your page is in root then $pos = 0
// if one folder deep , $pos = 1 ....
function fetchBasicLocation($pos = 0)
{
    $url = "";
    if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != "") {
        $url = "https://";
    } else {
        $url = "http://";
    } 
    $url .= $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'];
	$serverUrl=fetchTillLast($url, "/", ++$pos);
	//echo $serverUrl;
    return $serverUrl;
} 
if (!function_exists('file_get_contents')) {
    function file_get_contents($strfilename)
    {
        $handle = fopen($strfilename, "rb");
        $contents = fread($handle, filesize($strfilename));
        fclose($handle);
        return $contents;
    } 
} 
// jimz - November 15,2005
// Computes the size of a givenfolder
// function dirsize($directory)
// function uses a stack to compute the size of a folder.
// The functions used is 'filesize' , 'is_dir', 'is_file' , 'dir'
function dirsize($directory)
{ 
    // Init
    $size = 0; 
    // Trailing slash
    if (substr($directory, -1, 1) !== DIRECTORY_SEPARATOR) {
        $directory .= DIRECTORY_SEPARATOR;
    } 
    // Creating the stack array
    $stack = array($directory); 
    // Iterate stack
    for ($i = 0, $j = count($stack); $i < $j; ++$i) {
        // Add to total size
        if (is_file($stack[$i])) {
            $size += filesize($stack[$i]);
        } 
        // Add to stack
        elseif (is_dir($stack[$i])) {
            // Read directory
            $dir = dir($stack[$i]);
            while (false !== ($entry = $dir->read())) {
                // No pointers
                if ($entry == '.' || $entry == '..') {
                    continue;
                } 
                // Add to stack
                $add = $stack[$i] . $entry;
                if (is_dir($stack[$i] . $entry)) {
                    $add .= DIRECTORY_SEPARATOR;
                } 
                $stack[] = $add;
            } 
            // Clean up
            $dir->close();
        } 
        // Recount stack
        $j = count($stack);
    } 

    return $size;
} 
// jimz - November 15,2005
// Validates the user based on space occupied by the user
// function validateSizePerUser($userid,&$size,$rel="./")
// expects a database connection to be established before calling this function
// $userid                ==>        user id of the user to be validated
// &$size                ==>        size parameter is passed by reference so that after this function is called
// the total size of the parameter will be assigned to this variable
// $rel                        ==>        relative path, default is ./ , if one level deep then use ../ ,
// if two level deep use ../../
function validateSizePerUser($userid, &$size, &$allowed, $rel = "./")
{
    $var_size = 0;
    if (is_dir( $rel."usergallery/$userid")) {
        // echo("usergallery/$userid = " . dirsize($rel . "usergallery/$userid") ."<br>");
        $var_size += dirsize($rel . "usergallery/$userid");
    } 
    $sql = "select IFNULL(ts.ntempsite_id,si.nsite_id) as 'nsite_id',IF( ISNULL(ts.ntempsite_id), 'Completed', 'Under Construction' ) as 'status',
        IFNULL(ts.vtype,si.vtype) as 'vtype'
        from dummy d left join tbl_tempsite_mast ts ON(d.num=0 AND ts.nuser_id='" . addslashes($userid) . "')
        left join tbl_site_mast si on(d.num=1 AND si.nuser_id='" . addslashes($userid) . "' AND si.ndel_status='0')
         where ts.ntemplate_id IS NOT NULL OR si.nsite_id IS NOT NULL  Order by ts.ddate DESC,si.ddate DESC";
    $result = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result) > 0) {
        while ($row = mysql_fetch_array($result)) {
            if ($row["status"] == "Completed") {
                // echo("workarea/sites/". $row["nsite_id"] . " = " . dirsize($rel . "workarea/sites/". $row["nsite_id"]) ."<br>");
                $var_size += dirsize($rel . "workarea/sites/" . $row["nsite_id"]);
                // echo("sites/". $row["nsite_id"] . " = " . dirsize($rel . "sites/". $row["nsite_id"] . "") ."<br>");
                $var_size += dirsize($rel . "sites/" . $row["nsite_id"]);
                if ($row["vtype"] == "simple") {
                    // echo("sitepages/sites/" . $row["nsite_id"] . " = " . dirsize($rel . "sitepages/sites/" . $row["nsite_id"]) ."<br>");
                    $var_size += dirsize($rel . "sitepages/sites/" + $row["nsite_id"]);
                } 
            } else {
                // echo("workarea/tempsites/". $row["nsite_id"] . " = " . dirsize($rel . "workarea/tempsites/". $row["nsite_id"]) ."<br>");
                $var_size += dirsize($rel . "workarea/tempsites/" . $row["nsite_id"]);
                if ($row["vtype"] == "simple") {
                    // echo("sitepages/tempsites/" . $row["nsite_id"] . " = " . dirsize($rel . "sitepages/tempsites/" . $row["nsite_id"]) ."<br>");
                    $var_size += dirsize($rel . "sitepages/tempsites/" + $row["nsite_id"]);
                } 
            } 
        } 
    } 
    mysql_free_result($result);

    $size = $var_size;
    $sql = "Select vname,vvalue from tbl_lookup where vname='user_max_storage'";
    $result = mysql_query($sql) or die(mysql_error());
    if (mysql_num_rows($result) > 0) {
        $row = mysql_fetch_array($result);
        $allowed = $row["vvalue"] * 1024 * 1024;
        if ($var_size >= $allowed) {
            return false;
        } 
    } 
    return true;
} 
// jimz - November 15,2005
// Returns the size passed in using the human readable formats like GB, MB, KB, Bytes etc.
// function human_read($size)
// $size                ==>        The size that is to converted into human readable form.
function human_read($size)
{
    $retstring = '%01.2f %s';
    if ($size >= 1073741824)
        return sprintf($retstring, $size / 1024 / 1024 / 1024, " GB");
    elseif ($size >= 1048576)
        return sprintf($retstring, $size / 1024 / 1024, " MB");
    elseif ($size >= 1024)
        return sprintf($retstring, $size / 1024, " KB");
    elseif ($size < 1024)
        return sprintf($retstring, $size, " Bytes");
} 
// Copied from backupsol payment functions
// function getClientIP()
// returns the client ip
function getClientIP()
{ 
    // Get REMOTE_ADDR as the Client IP.
    $client_ip = (!empty($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : ((!empty($_ENV['REMOTE_ADDR'])) ? $_ENV['REMOTE_ADDR'] : $REMOTE_ADDR); 
    // Check for headers used by proxy servers to send the Client IP. We should look for HTTP_CLIENT_IP before HTTP_X_FORWARDED_FOR.
    if ($_SERVER["HTTP_CLIENT_IP"])
        $proxy_ip = $_SERVER["HTTP_CLIENT_IP"];
    elseif ($_SERVER["HTTP_X_FORWARDED_FOR"])
        $proxy_ip = $_SERVER["HTTP_X_FORWARDED_FOR"]; 
    // Proxy is used, see if the specified Client IP is valid. Sometimes it's 10.x.x.x or 127.x.x.x... Just making sure.
    if ($proxy_ip) {
        if (preg_match("/^([0-9]+\.[0-9]+\.[0-9]+\.[0-9]+)/", $proxy_ip, $ip_list)) {
            $private_ip = array('/^0\./', '/^127\.0\.0\.1/', '/^192\.168\..*/', '/^172\.16\..*/', '/^10.\.*/', '/^224.\.*/', '/^240.\.*/');
            $client_ip = preg_replace($private_ip, $client_ip, $ip_list[1]);
        } 
    } 
    // Return the Client IP.
    return $client_ip;
} 

function deleteOldData($numberOfDays,$relative="./") {
	$checknumber = -1 * $numberOfDays;
	$folders_to_delete=array($relative . "workarea/tempsites",$relative . "sitepages/tempsites",$relative . "tmpeditimages"); 
	$time = DateAdd(time(),$checknumber);
	foreach($folders_to_delete as $value) {
		$handle = opendir($value);
		while (false !== ($file = readdir($handle))) {
			$file_name = $value . "/" . $file;	
			if($file != "." && $file != ".." && is_dir($file_name)) {
 				if($time > filemtime($file_name)) {
					remove_dir($file_name);
					if($value == ($relative . "workarea/tempsites")) {
						$sql = "Delete from tbl_tempsite_mast where ntempsite_id='" . $file . "'";
						mysql_query($sql) or die(mysql_error());
						$sql = "Delete from tbl_tempsite_pages where ntempsite_id='" . $file . "'";
						mysql_query($sql) or die(mysql_error());
					}
				}
			}
		}	
	}
}
 function DateAdd($date, $number, $interval="d") {
    $date_time_array = getdate($date);
    $hours = $date_time_array['hours'];
    $minutes = $date_time_array['minutes'];
    $seconds = $date_time_array['seconds'];
    $month = $date_time_array['mon'];
    $day = $date_time_array['mday'];
    $year = $date_time_array['year'];
    switch ($interval) {
    
        case 'y':   // add year
            $year+=$number;
            break;

        case 'm':    // add month
            $month+=$number;
            break;

        case 'd':    // add days
            $day+=$number;
            break;

        case 'w':    // add week
            $day+=($number*7);
            break;

        case 'h':    // add hour
            $hours+=$number;
            break;

        case 'i':    // add minutes
            $minutes+=$number;
            break;

        case 's':    // add seconds
            $seconds+=$number; 
            break;            

    }
       $timestamp= mktime($hours,$minutes,$seconds,$month,$day,$year);
    return $timestamp;
}
function showtplpriview($templateid, $templatetype, $currentloc)
{
    $qry = " select * from tbl_template_mast where ntemplate_mast='$templateid'";
    $rs = mysql_query($qry);
    $row = mysql_fetch_array($rs);
    require($currentloc . '/smarty/lib/Smarty.class.php');
    remove_dir($currentloc . '/smarty/templates_c');
    @mkdir($currentloc . "/smarty/templates_c", 0777);
    @chmod($currentloc . "/smarty/templates_c", 0777);
    $smarty = new Smarty();
    $smarty->template_dir = $currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid;
    $smarty->compile_dir = $currentloc . "/smarty/templates_c";
    $smarty->cache_dir = $currentloc . "/smarty/cache";
    $smarty->config_dir = $currentloc . "/smarty/configs";
    if ($templatetype == "index") {
        $smarty->assign('vlogoband', "tp_logoimage.jpg");
        $smarty->assign('vcompanyband', "tp_company.jpg");
        $smarty->assign('captionband', "tp_caption.jpg");
        $smarty->assign('vsite_links', $row['vlinks']);
        $smarty->assign('vsite_editable', $row['veditable']);
        $html = $smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
    } else {
        $smarty->clear_cache('index.tpl');
        $smarty->assign('vinnserlogoband', "tp_logoimage.jpg");
        $smarty->assign('vinnercompanyband', "tp_innercompany.jpg");
        $smarty->assign('innercaptionband', "tp_innercaption.jpg");
        $smarty->assign('vsite_links', $row['vsub_links']);
        $smarty->assign('vsubsite_editable', $row['vsub_editable']);
        $html = $smarty->fetch('subpage.tpl', $cache_id = null, $compile_id = null, false);
    } 
    $fp = fopen($currentloc . "/".$_SESSION["session_template_dir"]."/$templateid/priview.htm", "w");
    fputs($fp, $html);
    fclose($fp);
}
 function builFontSelectBox($selecteditem){ 
 	
 	/*
     $dirpath="./fonts/";
	 $handle = opendir($dirpath);
	 $buildoptiontag="";
	 $default=$selecteditem;
     while( false !== ($file = readdir($handle))){
		  if ($file != "." && $file != ".." && $file !="Thumbs.db" && $file!="thumbimages" && $file!="index.html" ) {
		       $filenametobedisplay=strtoupper( substr($file,0,strrpos($file,".")) );
			    $optiontag="";
				if(strcasecmp($default,$file)=="0"){
				   $optiontag="<option value='".$file ."' selected>".$filenametobedisplay."</option>";
				}else{
				   $optiontag="<option value='".$file ."'>".$filenametobedisplay."</option>";
				}
			   $buildoptiontag=$buildoptiontag.$optiontag;
		  }
	 }
	echo $buildoptiontag;   
	*/
 global $fontList;
  	$optiontag = '';
 	foreach($fontList as $key=>$font) {
            $selected = ($font==$selecteditem)?'selected':'';
            $optiontag	.="<option value='".$font ."' $selected >".$key."</option>";
 	}
 	echo $optiontag;
 	
 }
 
 //function to fetch lookup table value
 function LookupDisplay($Name)
 {
 	 $sql=mysql_query("select vvalue from tbl_lookup where vname='".$Name."'") or die(mysql_error());
	 if(mysql_num_rows($sql)>0)
	 {
	 	return (mysql_result($sql,0,'vvalue'));
	 }//end if
 }//end if
 
 function getLicense(){
	$sql  = "SELECT vvalue FROM tbl_lookup WHERE vname = 'vLicenceKey'";
	$result = mysql_query($sql);
	if (mysql_num_rows($result) > 0) {
		$row = mysql_fetch_array($result);
		$var_licencekey = stripslashes($row["vvalue"]);
	}
	return $var_licencekey;
}

/*
 * Function to fetch the form field status
 */
function getFormFieldstatus($permissionArray,$fieldName,$fieldIndex)
{
    if($permissionArray[$fieldName][$fieldIndex]=='on')
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}
?>