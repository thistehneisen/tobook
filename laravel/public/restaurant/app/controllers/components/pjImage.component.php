<?php
require_once COMPONENTS_PATH . 'pjUpload.component.php';
class pjImage extends pjUpload
{
	var $image;
	var $image_type;
	
	function loadImage()
	{
		$info = $this->getSize();
		$this->image_type = $info[2];
		$file = $this->getFile('tmp_name');
		
		switch ($this->image_type)
		{
			case IMAGETYPE_JPEG:
				$this->image = imagecreatefromjpeg($file);
				break;
			case IMAGETYPE_GIF:
				$this->image = imagecreatefromgif($file);
				break;
			case IMAGETYPE_PNG:
				$this->image = imagecreatefrompng($file);
				break;
		}
		return $this;
	}
	
	function saveImage($dst, $image_type=IMAGETYPE_JPEG, $compression=100, $permissions=null)
	{
		switch ($image_type)
		{
			case IMAGETYPE_JPEG:
				imagejpeg($this->image, $dst, $compression);
				break;
			case IMAGETYPE_GIF:
				imagegif($this->image, $dst);
				break;
			case IMAGETYPE_PNG:
				imagepng($this->image, $dst);
				break;
		}
		if ($permissions != null)
		{
			chmod($dst, $permissions);
		}
		return $this;
	}
}