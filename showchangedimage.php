<?php 
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$tmpsiteid = $_SESSION['session_currenttempsiteid'];
$templateid = $_SESSION['session_currenttemplateid'];
$userid = $_SESSION["session_userid"];
$siteid=$_SESSION['session_siteid'];
if (!is_dir("./tmpeditimages/$userid/")) {
				mkdir("./tmpeditimages/$userid/", 0777);
				chmod("./tmpeditimages/$userid/", 0777);
} 

$tp_logo = "tp_logoimage.jpg";
$tp_innerlogo = "tp_innerlogoimage.jpg";
$tp_logoband = "tp_logoband.jpg";
$tp_innerlogoband = "tp_innerlogoband.jpg";
$tp_company = "tp_company.jpg";
$tp_innercompany = "tp_innercompany.jpg";
$tp_companyband = "tp_companyband.jpg";
$tp_innercompanyband = "tp_innercompanyband.jpg";
$tp_caption = "tp_caption.jpg";
$tp_innnercaption = "tp_innercaption.jpg";
$tp_captionband = "tp_captionband.jpg";
$tp_innercaptionband = "tp_innercaptionband.jpg";
if ($_GET['act'] == "company") {
                if($_SESSION['sess_companypriview']){
				   @unlink("./tmpeditimages/$userid/".$_SESSION['sess_companypriview']);
				}
				if (get_magic_quotes_gpc() == 1) {
								$companyname = stripslashes($_GET['company_name']);
				} else {
								$companyname = $_GET['company_name'];
				} 
				$selectedfont = addslashes(trim($_GET['font_name']));
				$fontcolor = addslashes(trim(substr($_GET['font_clr'], 1)));
				$fontsize = addslashes(trim($_GET['font_size']));

				$companybandname = $tp_companyband;
				$actualcompbandloc = "./" . $_SESSION["session_template_dir"] . "/" . $templateid . "/images/" . $companybandname;
				$imagewidth_height_type_array = explode(":", ImageType($actualcompbandloc));
				$imagetype = $imagewidth_height_type_array[0];

				$assignedname = "companybandpriview_" . $tmpsiteid .time(). ".jpg";
                $_SESSION['sess_companypriview']=$assignedname;
				copy($actualcompbandloc, "./tmpeditimages/$userid/" . $assignedname);
				chmod("./tmpeditimages/$userid/" . $assignedname, 0777);

				/*find image resource id to manipulate the text operation.this resource id 
			* is passed to text operation function.*/
				$image = NewimageCreatefromtype("./tmpeditimages/$userid/" . $assignedname);
				$font = "./fonts/" . $selectedfont;
				$hexcolor[0] = substr($fontcolor, 0, 2);
				$hexcolor[1] = substr($fontcolor, 2, 2);
				$hexcolor[2] = substr($fontcolor, 4, 2); 
				// Convert HEX values to DECIMAL
				$bincolor[0] = hexdec("0x{$hexcolor[0]}");
				$bincolor[1] = hexdec("0x{$hexcolor[1]}");
				$bincolor[2] = hexdec("0x{$hexcolor[2]}");
				/* find the int value for the color.this will be passed to text manupulation function*/
				$textcolor = ImageColorAllocate($image, $bincolor[0], $bincolor[1], $bincolor[2]);

				$angle = 0;
				
				if (trim($companyname) == "") {
								$companyname = "Your Company";
				} 
				
				$xcordinate = 10;
				//echo $imagewidth_height_type_array[2];
				$ycordinate = $imagewidth_height_type_array[2] / 2;
				$ycordinate=$ycordinate+5;
				imagettftext($image, $fontsize, $angle, $xcordinate, $ycordinate, $textcolor, $font, $companyname);
				$newfile = "./tmpeditimages/$userid/" . $assignedname;
				NewImagetype($image, $newfile,"./tmpeditimages/$userid/" . $assignedname);
				chmod($newfile, 0777);
				
				if($_SESSION['session_published']!="yes"){
					watermarkimage($newfile);	 
				
				}
				echo $newfile;
}else if ($_GET['act'] == "caption") {
                if($_SESSION['sess_captionpriview']){
				   @unlink("./tmpeditimages/$userid/".$_SESSION['sess_captionpriview']);
				}
				if (get_magic_quotes_gpc() == 1) {
								$captionname = stripslashes($_GET['caption_name']);
				} else {
								$captionname = $_GET['caption_name'];
				} 
				$selectedfont = addslashes(trim($_GET['font_name']));
				$fontcolor = addslashes(trim(substr($_GET['font_clr'], 1)));
				$fontsize = addslashes(trim($_GET['font_size']));

				$captionbandname = $tp_captionband;
				$actualcaptbandloc = "./" . $_SESSION["session_template_dir"] . "/" . $templateid . "/images/" . $captionbandname;
				$imagewidth_height_type_array = explode(":", ImageType($actualcaptbandloc));
				$imagetype = $imagewidth_height_type_array[0];

				$assignedname = "captionbandpriview_" . $tmpsiteid .time(). ".jpg";
                $_SESSION['sess_captionpriview']=$assignedname;
				copy($actualcaptbandloc, "./tmpeditimages/$userid/" . $assignedname);
				chmod("./tmpeditimages/$userid/" . $assignedname, 0777);

				/*find image resource id to manipulate the text operation.this resource id 
			* is passed to text operation function.*/
				$image = NewimageCreatefromtype("./tmpeditimages/$userid/" . $assignedname);
				$font = "./fonts/" . $selectedfont;
				$hexcolor[0] = substr($fontcolor, 0, 2);
				$hexcolor[1] = substr($fontcolor, 2, 2);
				$hexcolor[2] = substr($fontcolor, 4, 2); 
				// Convert HEX values to DECIMAL
				$bincolor[0] = hexdec("0x{$hexcolor[0]}");
				$bincolor[1] = hexdec("0x{$hexcolor[1]}");
				$bincolor[2] = hexdec("0x{$hexcolor[2]}");
				/* find the int value for the color.this will be passed to text manupulation function*/
				$textcolor = ImageColorAllocate($image, $bincolor[0], $bincolor[1], $bincolor[2]);

				$angle = 0;
				$xcordinate = 10;
				$ycordinate = 20;
				if (trim($captionname) == "") {
								$captionname = "Your Caption";
				} 
				
				$xcordinate = 10;
				$ycordinate = $imagewidth_height_type_array[2] / 2;
				$ycordinate=$ycordinate+5;
				//echo "font==".$fontsize."  ";
				imagettftext($image, $fontsize, $angle, $xcordinate, $ycordinate, $textcolor, $font, $captionname);
				$newfile = "./tmpeditimages/$userid/" . $assignedname;
				NewImagetype($image, $newfile,"./tmpeditimages/$userid/" . $assignedname);
				chmod($newfile, 0777);
				if($_SESSION['session_published']!="yes"){
					watermarkimage($newfile);	 
				
				}
				echo $newfile;

} 

?>