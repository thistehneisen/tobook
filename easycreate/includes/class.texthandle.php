<?php
class Texthandle
{
	public $font;  //default font. directory relative to script directory.
	public $msg = "no text"; // default text to display.
	public $size = 24; // default font size.
	public $rot = 0; // rotation in degrees.
	public $pad = 0; // padding. 
	public $transparent = 1; // transparency set to on.
	public $back = 'FFFFFF'; // foreground text...
	public $fore = '000000'; // on white background.  
	public $outline; // outline if any.
	public $outlinecolor; // outline color. 
	 
	function draw() 
	{
		$width = 0;
		$height = 0;
		$offset_x = 0;
		$offset_y = 0;
		$bounds = array();
		$image = ""; 
		
		// get the font height.
		$bounds = imagettfbbox($this->size, 0, $this->font, "W"); 
		$font_height = abs($bounds[7]-$bounds[1]);
		
		// determine bounding box.
		$this->msg = $this->__br2nl($this->msg);//echo $this->msg;exit;
		$lines = explode("/n",$this->msg);//print_r($lines);exit;
		$highVal = 0;
		$highKey = 0;
		foreach($lines as $key=>$val)
		{
			if(strlen($val) > $highVal)
			{
				$highKey = $key;
				$highVal = strlen($val);
			}
		}
		
		$bounds = imagettfbbox($this->size, 0, $this->font, $lines[$highKey]);
		
		$width = abs($bounds[4]-$bounds[6]);
		$height = count($lines)*abs($bounds[7]-$bounds[1])+count($lines);
		$offset_y = $font_height;
		$offset_x = 0;
		
		$image = imagecreate($width+($this->pad*2)+1,$height+($this->pad*2)+1);
		$forearray = $this->html2rgb($this->fore);
		$backarray = $this->html2rgb($this->back);
		$outlinearray = $this->html2rgb($this->outlinecolor);
		
		$background = imagecolorallocate($image, $backarray[0], $backarray[1], $backarray[2]);
		$foreground = imagecolorallocate($image, $forearray[0], $forearray[1], $forearray[2]);
		//----------------For outlining------------------//
		$olcolor = imagecolorallocate($image, $outlinearray[0], $outlinearray[1], $outlinearray[2]);
	
		if ($this->transparent) imagecolortransparent($image, $background);
		imageinterlace($image, false);
	
		// render the image		
		for($i=0; $i< count($lines); $i++)
		{
			$newtop = ($offset_y + $this->pad) + ($i * $this->size);
			//----------------------Outline the text-----------------------//
			if($this->outline <> 'None')
			{
				switch($this->outline)
				{
					case 'Thin':
							$padding = 1;
							break;
					case 'Medium':
							$padding = 2;
							break;
					case 'Thick':
							$padding = 4;
							break;
					default:
							$padding = 0;
							break;	
				}
				imagettftext($image, $this->size, 0, $offset_x+$this->pad+$padding, $newtop, $olcolor, $this->font, $lines[$i]);
				imagettftext($image, $this->size, 0, $offset_x+$this->pad-$padding, $newtop, $olcolor, $this->font, $lines[$i]);
				imagettftext($image, $this->size, 0, $offset_x+$this->pad, $newtop+$padding, $olcolor, $this->font, $lines[$i]);
				imagettftext($image, $this->size, 0, $offset_x+$this->pad, $newtop-$padding, $olcolor, $this->font, $lines[$i]);
			}
			
			imagettftext($image, $this->size, 0, $offset_x+$this->pad, $newtop, $foreground, $this->font, $lines[$i]);
		}
	
		if($this->rot <> 0)
		{
                    $image = imagerotate($image, $this->rot, $background, 0);
                    imagefill($image, 0, 0, imagecolorallocatealpha($image, 255,255, 255, 127));
                    imagesavealpha($image, true);
		}
		
		// output PNG object.
		header("Content-type: image/png");
		imagepng($image);
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
		
		function __br2nl($s, $useReturn=false) 
		{
  			return preg_replace('/<br>/i', (($useReturn) ? '/r' : '/n'), $s);
		}
}
?>