<?php
require_once("common/DB_Connection.php");
require_once("common/functions.php");

$type = $_POST['uploadType'];
if( $type == "emailTemplate" ){
	$type = "templateThumb";
}
$path = "img/".$type."/";

MT_MkDir( $path );

$valid_formats = array("jpg", "png", "gif", "bmp","jpeg");
if(isset($_POST) and $_SERVER['REQUEST_METHOD'] == "POST"){
	$name = $_FILES['imageUpload']['name'];
	$size = $_FILES['imageUpload']['size'];
	if(strlen($name))
	{
		list($txt, $ext) = explode(".", $name);
		if(in_array($ext,$valid_formats))
		{
			$actual_image_name = MT_generateRandom(16)."_".str_replace(" ", "", $name);
			$tmp = $_FILES['imageUpload']['tmp_name'];
			move_uploaded_file($tmp, $path.$actual_image_name);
			echo "<img style='width: 100%;' src='$path$actual_image_name'>";
		}
		else
			echo "Invalid file format.."; 
	}
	else
		echo "Please select image..!";
	exit;
}
?>