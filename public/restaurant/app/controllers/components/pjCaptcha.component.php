<?php
class pjCaptcha
{
/**
 * Path to font file
 *
 * @access private
 * @var string
 */
	var $font = null;
/**
 * Font size in pixels
 *
 * @access private
 * @var int
 */
	var $fontSize = 12;
/**
 * Image height in pixels
 *
 * @access private
 * @var int
 */
	var $height = 35;
/**
 * Path to image background
 *
 * @access private
 * @var string
 */
	var $image = null;
/**
 * Length of word
 *
 * @access private
 * @var int
 */
	var $length = null;
/**
 * Session variable name
 *
 * @access private
 * @var string
 */
	var $sessionVariable = null;
	
	var $productPrefix = null;
/**
 * Image width in pixels
 *
 * @access private
 * @var int
 */
	var $width = 79;
/**
 * Constructor
 *
 * @param string $fontPath Path to font file
 * @param string $sessionVariable Session variable name
 * @param int $length Length of word
 * @access public
 * @return void
 */
	function pjCaptcha($fontPath, $productPrefix, $sessionVariable, $length = 4)
	{
		$this->font = $fontPath;
		$this->productPrefix = $productPrefix;
		$this->sessionVariable = $sessionVariable;
		$this->length = intval($length);
	}
/**
 * Output image to browser
 *
 * @param mixed $renew
 * @access public
 * @return binary
 */
	function init($renew=null)
	{
    	if (!is_null($renew))
    	{
    		$_SESSION[$this->productPrefix][$this->sessionVariable] = NULL;
    	}

		if (!isset($_SESSION[$this->productPrefix][$this->sessionVariable]) || empty($_SESSION[$this->productPrefix][$this->sessionVariable]))
		{
			$str = "";
			$length = 0;
			for ($i = 0; $i < $this->length; $i++)
			{
				//this numbers refer to numbers of the ascii table (small-caps)
				// 97 - 122 (small-caps)
				// 65 - 90 (all-caps)
				// 48 - 57 (digits 0-9)
				$str .= chr(rand(65, 90));
			}
			$_SESSION[$this->productPrefix][$this->sessionVariable] = $str;
			$rand_code = $_SESSION[$this->productPrefix][$this->sessionVariable];
		} else {
			$rand_code = $_SESSION[$this->productPrefix][$this->sessionVariable];
		}

		if (!is_null($this->image))
		{
			$image = imagecreatefrompng($this->image);
		} else {
			$image = imagecreatetruecolor($this->width, $this->height);
			
			$backgr_col = imagecolorallocate($image, 204, 204, 204);
			$border_col = imagecolorallocate($image, 153, 153, 153);
			
			imagefilledrectangle($image, 0, 0, $this->width, $this->height, $backgr_col);
			imagerectangle($image, 0, 0, $this->width - 1, $this->height - 1, $border_col);
		}
		
		$text_col = imagecolorallocate($image, 68, 68, 68);

		$angle = rand(-10, 10);
		$box = imagettfbbox($this->fontSize, $angle, $this->font, $rand_code);
		$x = (int)($this->width - $box[4]) / 2;
		$y = (int)($this->height - $box[5]) / 2;
		imagettftext($image, $this->fontSize, $angle, $x, $y, $text_col, $this->font, $rand_code);
		
		header("Content-type: image/png");
		imagepng($image);
		imagedestroy ($image);
	}
/**
 * Set path to font file
 *
 * @param string $fontPath Path to font file
 * @access public
 * @return void
 */
	function setFont($fontPath)
	{
		$this->font = $fontPath;
	}
/**
 * Set length of word
 *
 * @param int $length Length of word
 * @access public
 * @return void
 */
	function setLength($length)
	{
		if ((int) $length > 0)
		{
			$this->length = intval($length);
		}
	}
/**
 * Set session variable name
 *
 * @param string $sessionVariable Session variable name
 * @access public
 * @return void
 */
	function setSessionVariable($sessionVariable)
	{
		$this->sessionVariable = $sessionVariable;
	}
/**
 * Set image height
 *
 * @param int $height Image height in pixels
 * @access public
 * @return void
 */
	function setHeight($height)
	{
		if ((int) $height > 0)
		{
			$this->height = intval($height);
		}
	}
/**
 * Set image width
 *
 * @param int $width Image width in pixels
 * @access public
 * @return void
 */
	function setWidth($width)
	{
		if ((int) $width > 0)
		{
			$this->width = intval($width);
		}
	}
/**
 * Set font size
 *
 * @param int $fontSize Font size in pixels
 * @access public
 * @return void
 */
	function setFontSize($fontSize)
	{
		if ((int) $fontSize > 0)
		{
			$this->fontSize = intval($fontSize);
		}
	}
/**
 * Set image background
 *
 * @param string $image Path to image file
 * @access public
 * @return void
 */
	function setImage($image)
	{
		$this->image = $image;
	}
}
?>