<?php
//if($_GET['act'] == 'company')	{
// Set the content-type
$font_size = $_GET['font_size'];

if($font_size <= 10) {
	$canvas = '40';
	$rectangle = '39';
	$fontbase = '30';
}
else {
	$canvas = '70'; $rectangle = '69'; 	$fontbase = '60';
	
}

header('Content-Type: image/png');
// Create the image
$im = imagecreatetruecolor(400, $canvas);
// Create some colors
$white = imagecolorallocate($im, 255, 255, 255);
$grey = imagecolorallocate($im, 128, 128, 128);
$black = imagecolorallocate($im, 0, 0, 0);
imagefilledrectangle($im, 0, 0, 399, $rectangle, $white);

// The text to draw
$text = $_GET['company_name'];
if($text == '') $text = 'Sample Text';
// Replace path by your own font path
$font = 'fonts/'.$_GET['font_name'];


$font_clr = $_GET['font_clr'];
//[font_size] => 11
  //  [font_clr] => 006600

// Add some shadow to the text
//imagettftext($im, 20, 0, 11, 21, $font_clr, $font, $text);


$hex = str_replace('#','',$font_clr);
    $a = hexdec(substr($hex,0,2));
    $b = hexdec(substr($hex,2,2));
    $c = hexdec(substr($hex,4,2));
    $font_clr =  imagecolorallocate($im, $a, $b, $c);
// Add the text
imagettftext($im, $font_size, 0, 10, $fontbase, $font_clr, $font, $text);

// Using imagepng() results in clearer text compared with imagejpeg()
imagepng($im);
imagedestroy($im);
//}
?>
