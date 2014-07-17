<?php 
class Textoverimage
{
	private $original;
	private $edited;
	private $imageType;
	private $editCount;
	private $tempText;
	
	function Textoverimage($imagePath, $savePath){
		$this->original = $imagePath;
		//----------Find the format of the original image-----------//
		$tmp = explode(".",$savePath);
		$this->imageType = strtolower(end($tmp));
		$this->edited = $savePath;
		$this->editCount = 0;
		
		return $editedImg;
	}
	
	function addText($textToBeAdded){
		$this->tempText = explode("#",$textToBeAdded);
		
		foreach($this->tempText as $textVal){
			if($textVal != ''){
				$this->editCount == 0 ? $dest = $this->__createimage($this->original, $this->imageType) : $dest = $this->__createimage($this->edited, $this->imageType);
				
				$temp = explode('|',$textVal);
				//print_r($temp);
				$colors = $temp[3];				
				$fontClr = "0x00".$colors;
				
				$fontWeight = $temp[4];
				$left = $temp[1];
				$top = $temp[0];
				$text = $this->__br2nl($temp[2]);
				$font = 'fonts/'.$temp[5];
				
				$lines=explode("/n",$text);
				//-------------------------------------------------//
				// get the font height.
				$bounds = imagettfbbox($fontWeight, 0, $font, "W"); 
				$font_height = abs($bounds[7]-$bounds[1]);
		
				// determine bounding box.
				$highVal = 0;
				$highKey = 0;
				foreach($lines as $key=>$val)
				{
					if(strlen($val) > $highVal){
						$highKey = $key;
						$highVal = strlen($val);
					}
				}
				
				$bounds = imagettfbbox($fontWeight, 0, $font, $lines[$highKey]);
				
				$width = abs($bounds[4]-$bounds[6]);
				$height = count($lines)*abs($bounds[7]-$bounds[1])+count($lines);
				$offset_y = $font_height;
				$offset_x = 0;
				
				$image = imagecreate($width+1,$height+1);
				$forearray = $this->html2rgb($colors);
				$backarray = $this->html2rgb('FFFFFF');
				
				$background = imagecolorallocate($image, $backarray[0], $backarray[1], $backarray[2]);
				$foreground = imagecolorallocate($image, $forearray[0], $forearray[1], $forearray[2]);
			
				imagecolortransparent($image, $background);
				imageinterlace($image, false);
			
				// render the image		
				for($i=0; $i< count($lines); $i++)
				{
					$newtop = ($offset_y) + ($i * $fontWeight);			
					imagettftext($image, $fontWeight, 0, $offset_x, $newtop, $foreground, $font, $lines[$i]);
				}                                
				//-------------------------------------------------//
				
				imagecopymerge($dest, $image, $left, $top, 0, 0, imagesx($image), imagesy($image), 100);
				//header("Content-type: image/jpg");
                //echo imagejpeg($image);exit;
				//--------image is saved-----------//
				$this->__saveimage($dest);
				imagedestroy($dest);
				
				$this->editCount++;
			}
		}			
	}
	
	function __createimage($imgSrc, $imgTyp){
		switch($imgTyp){
			case 'jpg':
				$op = @imagecreatefromjpeg($imgSrc);
				break;
			case 'png':
				$op = @imagecreatefrompng($imgSrc);
				break;
			case 'gif':
				$op = @imagecreatefromgif($imgSrc);
				break;
			default:
				$op = false;
				break;
		}
		return $op;
	}
	
	function __saveimage($imgSrc){
		switch($this->imageType){
			case 'jpg':
				@imagejpeg($imgSrc,$this->edited,100);
				break;
			case 'png':
				@imagepng($imgSrc,$this->edited);
				break;
			case 'gif':
				@imagegif($imgSrc,$this->edited);
				break;
			default:
				false;
				break;
		}
	}
	
	function __getmicrotime($e = 7){
		list($u, $s) = explode(' ',microtime());
		return str_replace(".","_",bcadd($u, $s, $e));
	}
	
	function __br2nl($s, $useReturn=false) {
  		return preg_replace('/(<br ?\/?>)/i', (($useReturn) ? '/r' : '/n'), $s);
	}
	
	function html2rgb($color)
	{		
		if (strlen($color) == 6)
			list($r, $g, $b) = array($color[0].$color[1], $color[2].$color[3], $color[4].$color[5]);
		elseif (strlen($color) == 3)
			list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
		else
			return false;
	
		$r = hexdec($r); 
		$g = hexdec($g); 
		$b = hexdec($b);
	
		return array($r, $g, $b);
	}
	
}