<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                    |
// |                                                                                                            |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

$templateid = addslashes($_GET['templateid']);
/*check for the templateid exist(ie passed from previous page as GET variable) and user selected a new template
 (this will be tracked using session variable 'session_cleared' this variable set to 'no'
 when the user select a template and will be set to 'yes' at the end of this step)
*/
if ($templateid != "" and $_SESSION['session_cleared'] != "yes") {
    /*check the  'session_currenttemplateid'(for the first time 'session_currenttemplateid' this will be null.
          when the user select different template the value of this session varibale will be old templateid)
         is different from templateid(ie passed from previous page as GET variable) the following steps will be executed
    */
    if ($_SESSION['session_currenttemplateid'] != $templateid) {
        remove_dir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid']);
        remove_dir("./sitepages/tempsites/" . $_SESSION['session_currenttempsiteid']);
        if (!is_dir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'])) {
            mkdir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'], 0777);
            chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'], 0777);
            mkdir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/images", 0777);
            chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/images", 0777);
            mkdir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/flash", 0777);
            chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/flash", 0777);
            $fp = fopen("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/resource.txt", "w");
            chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/resource.txt", 0777);
            // copydirr("./".$_SESSION["session_template_dir"]."/".$selectedtemplateid."/watermarkimages","./workarea/tempsites/".$_SESSION['session_currenttempsiteid']."/images",0777,false);
            // copy("./".$_SESSION["session_template_dir"]."/".$selectedtemplateid."/style.css","./workarea/sites/".$_SESSION['session_currenttempsiteid']."/style.css");
        }
        if (!is_dir("./sitepages/tempsites/" . $_SESSION['session_currenttempsiteid'])) {
            mkdir("./sitepages/tempsites/" . $_SESSION['session_currenttempsiteid'], 0777);
            chmod("./sitepages/tempsites/" . $_SESSION['session_currenttempsiteid'], 0777);
        }
    }
    $_SESSION['session_currenttemplateid'] = $templateid;
    $qry = "update tbl_tempsite_mast set vtype='simple',ntemplate_id='" . $_SESSION['session_currenttemplateid'] . "' where ntempsite_id='" . $_SESSION['session_currenttempsiteid'] . "'";
    mysql_query($qry);
    // fetch template logo,caption,company
    $qry = "select * from tbl_template_mast where vtype='simple' and ntemplate_mast='" . $_SESSION['session_currenttemplateid'] . "'";
    // echo $qry;
    $exc = mysql_query($qry);
    if (mysql_num_rows($exc) > 0) {
        $row = mysql_fetch_array($exc);
        // remove all files in tempsite directory
        copydirr("./".$_SESSION["session_template_dir"]."/" . $_SESSION['session_currenttemplateid'] . "/watermarkimages", "./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/images", 0777, false);
        // reset session
        $_SESSION['session_logobandname'] = "";
        $_SESSION['session_innerlogobandname'] = "";
        $_SESSION['logoname'] = "";
        $_SESSION['session_companybandname'] = "";
        $_SESSION['session_innercompanybandname'] = "";
        $_SESSION['session_comselfontclr'] = "";
        $_SESSION['session_comselfont'] = "";
        $_SESSION['session_comselfontsize'] = "";
        $_SESSION['session_comseltext'] = "";
        $_SESSION['session_sitecolor'] = "";
        $_SESSION['session_captionbandname'] = "";
        $_SESSION['session_innercaptionbandname'] = "";
        $_SESSION['session_capselfontclr'] = "";
        $_SESSION['session_capselfont'] = "";
        $_SESSION['session_capselfontsize'] = "";
        $_SESSION['session_capseltext'] = "";
        $_SESSION['session_captionbandname'] = "";
        $_SESSION['session_sitemetadesc'] = "";
        $_SESSION['session_sitemetakey'] = "";
        $_SESSION['session_sitetitle'] = "";
        $_SESSION['session_sitemeemail'] = "";
        $_SESSION['session_published'] = "no";
        $_SESSION['session_oldsitelinks'] = "";
        $_SESSION['session_sitelinks'] = "";
        $_SESSION['session_oldsitelinks'] = "";
        $_SESSION['session_paymentmode'] = "";
        $_SESSION['session_siteid'] = "";
        $_SESSION['session_paymentmode'] = "";
        $_SESSION['session_published'] = "";
        $_SESSION['session_cleared'] = "yes";
    } else {
        header("location:sitemanager.php");
        exit;
    }
} else if ($_SESSION['session_currenttemplateid'] == "") {
    header("location:sitemanager.php");
    exit;
}
if (!is_dir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'])) {
    header("location:sitemanager.php");
    exit;
}
$tmpsiteid = $_SESSION['session_currenttempsiteid'];
$templateid = $_SESSION['session_currenttemplateid'];
$userid = $_SESSION["session_userid"];
/*assumes that logo,caption,company etc  are
tp_logoimage.jpg->logo image,tp_innerlogoimage.jpg->inner logoimage,tp_logoband.jpg->logoband
tp_innerlogoband.jpg->inner logo band,tp_company.jpg->company,tp_innercompany.jpg->inner company
tp_companyband.jpg->company band,tp_innercompanyband.jpg->inner company band,tp_caption.jpg->caption
tp_innercaption.jpg->inner caption,tp_captionband.jpg->caption band,tp_innercaptionband.jpg->inner caption band
*/
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
if ($_POST['btnback'] == "Back") {
    //$location = "showtemplates.php?catid=" . $_SESSION['session_categoryid'];
    $location=$_SESSION['gtemplatebackurl'];
    header("location:$location");
    exit;
} else if ($_POST['btncontinue'] == "Continue") {
    if (!validateSizePerUser($_SESSION["session_userid"], $size_per_user, $allowed_space)) {
        $errormessage = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete
                   unused images or any/all of the sites created by you to proceed further.<br>&nbsp;<br>";
    } else {
        /* user either uploaded logo not selected our logo samples*/
        $errormessage = "";
        if($_POST['logooption']=="N") {
            $logobandname = $tp_logoband;
            $actuallogbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $logobandname;
            $assignedname = $logobandname;
            $_SESSION['session_logobandname'] = $assignedname;
            @copy($actuallogbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            @chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            // make logoband for innerpages
            $logobandname = $tp_innerlogoband;
            $assignedname_innerlogo = $logobandname;
            $_SESSION['session_innerlogobandname'] = $assignedname_innerlogo;
            $actuallogbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $logobandname;
            @copy($actuallogbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo);
            @chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo, 0777);
            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo);
        }else  if ($_FILES['filelogo']['size'] <= 0 and $_SESSION['logoname'] == "") {
            // if logo not uploaded or not selected from our gallery
            $logobandname = $tp_logo;
            $actuallogbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $logobandname;
            $assignedname = $logobandname;
            $_SESSION['session_logobandname'] = $assignedname;
            @copy($actuallogbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            @chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            // make logoband for innerpages
            $logobandname = $tp_innerlogo;
            $assignedname_innerlogo = $logobandname;
            $_SESSION['session_innerlogobandname'] = $assignedname_innerlogo;
            $actuallogbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $logobandname;
            @copy($actuallogbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo);
            @chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo, 0777);
            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo);
        } else {
            /*if user  uploaded logo        or selected from logo soample */
            $errormessagefileupload = "";
            if ($_FILES['filelogo']['size'] > 0) {
                /*if user  uploaded logo
                                  copy the uploaded file to usergallery and watermarked image to workarea and set the session  variable
                                  * logoname to assigned name.this session name will be used for the furthor procedure

                */

                list($width, $height, $type, $attr) = @getimagesize($_FILES['filelogo']['tmp_name']);
                if ($type > 3) {
                    $errormessagefileupload = "Please upload only .jpg,.gif,.png image types";
                }else {
                    $imagewidth_height_type_array = explode(":", ImageType($_FILES['filelogo']['tmp_name']));
                    $imagetype = $imagewidth_height_type_array[0];
                    $assignedname = "ug_" . $userid . "_logo" . time() . "." . $imagetype;
                    $_SESSION['logoname'] = $assignedname;
                    move_uploaded_file($_FILES['filelogo']['tmp_name'], "./usergallery/$userid/images/" . $assignedname);
                    chmod("./usergallery/$userid/images/" . $assignedname, 0777);
                    copy("./usergallery/$userid/images/" . $assignedname, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
                    chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
                    watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
                }
            }
            if ($errormessagefileupload == "") {
                /* session logoname contains the uploaded file or the selected logo sample.
                           * first copy the logoband  to workarea location with assigned name.
                           * resize the image(image in session logoname) to sute in logoband.
                           * embed this image into logoband.
                           * same step for inner logo
                */

                $logobandname = $tp_logoband;
                $actuallogbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $logobandname;
                $imagewidth_height_type_array = explode(":", ImageType($actuallogbandloc));
                $imagetype = $imagewidth_height_type_array[0];
                $imagewidth = $imagewidth_height_type_array[1];
                $imageheight = $imagewidth_height_type_array[2];
                $assignedname = "ug_logoband_t" . $tmpsiteid . ".jpg";
                $assignedname_innerlogo = "ug_innerlogoband_t" . $tmpsiteid . ".jpg";
                $_SESSION['session_logobandname'] = $assignedname;

                copy($actuallogbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
                chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
                if (is_file("./usergallery/$userid/images/" . $_SESSION['logoname'])) {
                    $image2 = "./usergallery/$userid/images/" . $_SESSION['logoname'];
                    copy($image2, "./usergallery/$userid/images/" . $assignedname_innerlogo);
                } elseif (is_file("./samplelogos/" . $_SESSION['logoname'])) {
                    $image2 = "./samplelogos/" . $_SESSION['logoname'];
                    copy($image2, "./usergallery/$userid/images/" . $assignedname_innerlogo);
                }

                $resizedimage = "./workarea/tempsites/$tmpsiteid/images/rez_" . $assignedname;

                Resizeimage($image2, $imageheight, $imagewidth, $resizedimage);
                chmod($resizedimage, 0777);
                embedimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, $resizedimage);
                unlink($resizedimage);

                copy("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, "./usergallery/$userid/images/" . $assignedname);
                chmod("./usergallery/$userid/images/" . $assignedname, 0777);
                chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
                watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
                // make logoband for innerpages
                $logobandname = $tp_innerlogoband;
                $actuallogbandlocinnerlogo = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $logobandname;
                $imagewidth_height_type_array = explode(":", ImageType($actuallogbandlocinnerlogo));
                $imagetype = $imagewidth_height_type_array[0];
                $imagewidth = $imagewidth_height_type_array[1];
                $imageheight = $imagewidth_height_type_array[2];

                $_SESSION['session_innerlogobandname'] = $assignedname_innerlogo;
                copy($actuallogbandlocinnerlogo, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo);
                chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo, 0777);
                if (is_file("./usergallery/$userid/images/" . $assignedname_innerlogo)) {
                    $image2 = "./usergallery/$userid/images/" . $assignedname_innerlogo;
                } elseif (is_file("./samplelogos/" . $_SESSION['logoname'])) {
                    $image2 = "./samplelogos/" . $_SESSION['logoname'];
                }
                $resizedimage = "./workarea/tempsites/$tmpsiteid/images/rezinner_" . $assignedname_innerlogo;
                Resizeimage($image2, $imageheight, $imagewidth, $resizedimage);
                chmod($resizedimage, 0777);
                embedimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo, $resizedimage);
                unlink($resizedimage);
                if (is_file("./usergallery/$userid/images/" . $assignedname_innerlogo)) {
                    unlink("./usergallery/$userid/images/" . $assignedname_innerlogo);
                }
                copy("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo, "./usergallery/$userid/images/" . $assignedname_innerlogo);
                chmod("./usergallery/$userid/images/" . $assignedname_innerlogo, 0777);
                chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo, 0777);
                watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_innerlogo);
                // unlink("./usergallery/$userid/images/".$_SESSION['logoname']);
            }
        }
        if (trim($_POST['companyname']) == "" and $_SESSION['session_companybandname'] == "") {
            // company is empty
            $companybandname = $tp_company;
            $actualcompbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $companybandname;
            $imagewidth_height_type_array = explode(":", ImageType($actuallogbandloc));
            $imagetype = $imagewidth_height_type_array[0];
            $assignedname = $companybandname;
            $_SESSION['session_companybandname'] = $assignedname;
            copy($actualcompbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);

            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            // make innerpage company
            $companybandname = $tp_innercompany;
            $actualcompbandloc_inner = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $companybandname;
            $assignedname_inner = $companybandname;
            $_SESSION['session_innercompanybandname'] = $assignedname_inner;

            copy($actualcompbandloc_inner, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname_inner);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_inner, 0777);
            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname_inner);
        } else if (trim($_POST['companyname']) != "") {
            /* save the posted company data*/

            if (get_magic_quotes_gpc() == 1) {
                $companyname = stripslashes($_POST['companyname']);
            } else {
                $companyname = $_POST['companyname'];
            }
            $selectedfont = addslashes(trim($_POST['compfont']));
            $_SESSION['session_comselfontclr'] = $_POST['fntclr'];
            $fontcolor = addslashes(trim(substr($_POST['fntclr'], 1)));
            $fontsize = addslashes(trim($_POST['fontsize']));
            $_SESSION['session_comselfont'] = $selectedfont;
            $_SESSION['session_comselfontsize'] = $fontsize;
            $_SESSION['session_comseltext'] = $companyname;

            /*copy the companyband,set name for final company image(assignedname)*/

            $companybandname = $tp_companyband;
            $actualcompbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $companybandname;
            $imagewidth_height_type_array = explode(":", ImageType($actualcompbandloc));
            $imagetype = $imagewidth_height_type_array[0];
            $assignedname = "ug_companyband_t" . $tmpsiteid . ".jpg";
            $_SESSION['session_companybandname'] = $assignedname;
            copy($actualcompbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);

            /*find image resource id to manipulate the text operation.this resource id
                        * is passed to text operation function.*/
            $image = NewimageCreatefromtype("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
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
            /* check whether the company name can be write with given font nd givensize
                           this function returns an array(see the manual).suppose the box has cordinate
                           bottom left cordinates-(1,1)
                           bottom right cordinates-(6,1)
                           top left cordinates-(1,10)
                           top right cordinates-(6,10)
                           the function will return array start from index 0->1,1,6,1,6,10,1,10
                           then allowedwidth=6-0 and allowed height=1-10


            $checkmaximumlengthaloowed = imagettfbbox ($fontsize, 0, "./fonts/" . $selectedfont, $companyname);
            $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
            $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
            $innerimagewidth = $imagewidth_height_type_array[1];
            $innerimageheight = $imagewidth_height_type_array[2];

            if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {;
            } else {
                $i = 1;
                                // reduce the fontsize upto allowed width nd height
                while ($i < 100) {
                    $fontsize = $fontsize-1;
                    $checkmaximumlengthaloowed = imagettfbbox ($fontsize, 0, "./fonts/" . $selectedfont, $companyname);
                    $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
                    $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
                    if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {
                        break;
                    }
                    $i++;
                }
            }
                         //reduce the font size again for better performence.
            $fontsize = $fontsize - ($fontsize / 3);*/
            $xcordinate = 10;
            $ycordinate = $imagewidth_height_type_array[2] / 2;
            $ycordinate=$ycordinate+5;
            imagettftext($image, $fontsize, $angle, $xcordinate, $ycordinate, $textcolor, $font, $companyname);
            $newfile = "./workarea/tempsites/$tmpsiteid/images/" . $assignedname;
            NewImagetype($image, $newfile, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            copy($newfile, "./usergallery/$userid/images/" . $assignedname);
            chmod("./usergallery/$userid/images/" . $assignedname, 0777);
            chmod($newfile, 0777);
            watermarkimage($newfile);
            // make innercompany band.procedure is same as above
            $companybandname = $tp_innercompanyband;
            $actualcompbandloc_inner = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $companybandname;
            $imagewidth_height_type_array = explode(":", ImageType($actualcompbandloc_inner));
            $imagetype = $imagewidth_height_type_array[0];
            $assignedname = "ug_innercompanyband_t" . $tmpsiteid . ".jpg";
            $_SESSION['session_innercompanybandname'] = $assignedname;

            copy($actualcompbandloc_inner, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
            $image = NewimageCreatefromtype("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            /*$checkmaximumlengthaloowed = imagettfbbox ($fontsize, 0, "./fonts/" . $selectedfont, $companyname);
            $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
            $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
            $innerimagewidth = $imagewidth_height_type_array[1];
            $innerimageheight = $imagewidth_height_type_array[2];

            if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {;
            } else {
                $i = 1;
                while ($i < 100) {
                    $fontsize = $fontsize-1;
                    $checkmaximumlengthaloowed = imagettfbbox ($fontsize, 0, "./fonts/" . $selectedfont, $companyname);
                    $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
                    $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
                    if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {
                        break;
                    }
                    $i++;
                }
            }
            $fontsize = $fontsize - ($fontsize / 3);*/

            //reduce the font for innerlogo
            $fontsize=$fontsize-2;

            $xcordinate = 10;
            $ycordinate = $imagewidth_height_type_array[2] / 2;
            $ycordinate=$ycordinate+5;
            imagettftext($image, $fontsize, $angle, $xcordinate, $ycordinate, $textcolor, $font, $companyname);
            $newfile = "./workarea/tempsites/$tmpsiteid/images/" . $assignedname;
            NewImagetype($image, $newfile, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            copy($newfile, "./usergallery/$userid/images/" . $assignedname);
            chmod("./usergallery/$userid/images/" . $assignedname, 0777);
            chmod($newfile, 0777);
            watermarkimage($newfile);
        }

        $_SESSION['session_sitecolor'] = $_POST['stclr'];
        if (trim($_POST['captionname']) == "" and $_SESSION['session_captionbandname'] == "") {
            // if caption not enterted
            $captionbandname = $tp_caption;
            $actualcaptionbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $captionbandname;
            $imagewidth_height_type_array = explode(":", ImageType($actualcaptionbandloc));
            $imagetype = $imagewidth_height_type_array[0];
            $assignedname = $captionbandname;
            $_SESSION['session_captionbandname'] = $assignedname;
            copy($actualcaptionbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            // make inner caption band
            $captionbandname = $tp_innnercaption;
            $actualcaptionbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $captionbandname;
            $assignedname = $captionbandname;
            $_SESSION['session_innercaptionbandname'] = $assignedname;

            copy($actualcaptionbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
            watermarkimage("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
        } else if (trim($_POST['captionname']) != "") {
            /*setthecaption
                          The procedure is same as company.only the assigend name and images are different
            */
            $captionbandname = $tp_caption;
            if (get_magic_quotes_gpc() == 1) {
                $captionname = stripslashes($_POST['captionname']);
            } else {
                $captionname =$_POST['captionname'];
            }
            // $captionname=addslashes(trim($_POST['captionname']));;
            $selectedcaptionfont = addslashes(trim($_POST['captionfont']));
            $_SESSION['session_capselfontclr'] = $_POST['captfntclr'];
            $captionfontcolor = addslashes(trim(substr($_POST['captfntclr'], 1)));
            ;
            $captionfontsize = addslashes(trim($_POST['captionfontsize']));
            $captionbandname = $tp_captionband;

            $_SESSION['session_capselfont'] = $selectedcaptionfont;
            $_SESSION['session_capselfontsize'] = $captionfontsize;
            $_SESSION['session_capseltext'] = $captionname;

            $actualcaptionbandloc = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $captionbandname;
            $imagewidth_height_type_array = explode(":", ImageType($actualcaptionbandloc));
            $imagetype = $imagewidth_height_type_array[0];
            $assignedname = "ug_caption_t" . $tmpsiteid . ".jpg";
            ;
            $_SESSION['session_captionbandname'] = $assignedname;

            copy($actualcaptionbandloc, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
            $image = NewimageCreatefromtype("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            $font = "./fonts/" . $selectedcaptionfont;
            // $hexcolor = str_split($fontcolor, 2);
            $hexcolor[0] = substr($captionfontcolor, 0, 2);
            $hexcolor[1] = substr($captionfontcolor, 2, 2);
            $hexcolor[2] = substr($captionfontcolor, 4, 2);
            // Convert HEX values to DECIMAL
            $bincolor[0] = hexdec("0x{$hexcolor[0]}");
            $bincolor[1] = hexdec("0x{$hexcolor[1]}");
            $bincolor[2] = hexdec("0x{$hexcolor[2]}");
            $textcolor = ImageColorAllocate($image, $bincolor[0], $bincolor[1], $bincolor[2]);

            $angle = 0;

            if (trim($captionname) == "") {
                $captionname = "Your Caption";
            }
            /* check whether the text can be append in image.Otherwise chaneg the font size
            $checkmaximumlengthaloowed = imagettfbbox ($captionfontsize, 0, "./fonts/" . $selectedcaptionfont, $captionname);
            $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
            $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
            $innerimagewidth = $imagewidth_height_type_array[1];
            $innerimageheight = $imagewidth_height_type_array[2];

            if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {;
            } else {
                $i = 1;
                while ($i < 100) {
                    $captionfontsize = $captionfontsize-1;
                    $checkmaximumlengthaloowed = imagettfbbox ($fontsize, 0, "./fonts/" . $selectedfont, $companyname);
                    $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
                    $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
                    if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {
                        break;
                    }
                    $i++;
                }
            }
            $captionfontsize = $captionfontsize - ($captionfontsize / 3);*/
            $xcordinate = 10;
            $ycordinate = $imagewidth_height_type_array[2] / 2;
            $ycordinate=$ycordinate+5;
            /*checking ends here*/
            imagettftext($image, $captionfontsize, $angle, $xcordinate, $ycordinate, $textcolor, $font, $captionname);
            $newfile = "./workarea/tempsites/$tmpsiteid/images/" . $assignedname;
            NewImagetype($image, $newfile, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            copy($newfile, "./usergallery/$userid/images/" . $assignedname);
            chmod("./usergallery/$userid/images/" . $assignedname, 0777);
            chmod($newfile, 0777);
            watermarkimage($newfile);
            // make the inner caption band
            $captionbandname = $tp_innercaptionband;
            $actualcaptionbandloc_inner = "./".$_SESSION["session_template_dir"]."/" . $templateid . "/images/" . $captionbandname;
            $imagewidth_height_type_array = explode(":", ImageType($actualcaptionbandloc_inner));
            $imagetype = $imagewidth_height_type_array[0];
            $assignedname = "ug_innercaption_t" . $tmpsiteid . ".jpg";
            ;
            $_SESSION['session_innercaptionbandname'] = $assignedname;
            copy($actualcaptionbandloc_inner, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            chmod("./workarea/tempsites/$tmpsiteid/images/" . $assignedname, 0777);
            $image = NewimageCreatefromtype("./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            /* check whether the text can be append in image.Otherwise chaneg the font size
            $checkmaximumlengthaloowed = imagettfbbox ($captionfontsize, 0, "./fonts/" . $selectedcaptionfont, $captionname);
            $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
            $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
            $innerimagewidth = $imagewidth_height_type_array[1];
            $innerimageheight = $imagewidth_height_type_array[2];

            if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {;
            } else {
                $i = 1;
                while ($i < 100) {
                    $captionfontsize = $captionfontsize-1;
                    $checkmaximumlengthaloowed = imagettfbbox ($captionfontsize, 0, "./fonts/" . $selectedfont, $captionname);
                    $imageallowablwidth = $checkmaximumlengthaloowed[2] - $checkmaximumlengthaloowed[0];
                    $imageallowablheight = $checkmaximumlengthaloowed[1] - $checkmaximumlengthaloowed[7];
                    if ($imageallowablwidth < $innerimagewidth and $imageallowablheight < $innerimageheight) {
                        break;
                    }
                    $i++;
                }
            }
            $captionfontsize = $captionfontsize - ($captionfontsize / 3);*/
            $captionfontsize=$captionfontsize-2;
            $xcordinate = 10;
            $ycordinate = $imagewidth_height_type_array[2] / 2;
            $ycordinate=$ycordinate+5;
            /*checking ends here*/
            imagettftext($image, $captionfontsize, $angle, $xcordinate, $ycordinate, $textcolor, $font, $captionname);
            $newfile = "./workarea/tempsites/$tmpsiteid/images/" . $assignedname;
            NewImagetype($image, $newfile, "./workarea/tempsites/$tmpsiteid/images/" . $assignedname);
            copy($newfile, "./usergallery/$userid/images/" . $assignedname);
            chmod("./usergallery/$userid/images/" . $assignedname, 0777);
            chmod($newfile, 0777);
            watermarkimage($newfile);

            /*end of caption*/
        }

        /*Meta keywords*/

        if (get_magic_quotes_gpc() == 1) {
            $_SESSION['session_sitemetadesc'] = trim($_POST['sitemetadesc']);
            $_SESSION['session_sitemetakey'] = trim($_POST['sitemetakey']);
            $_SESSION['session_sitetitle'] = trim($_POST['sitetitle']);
            $_SESSION['session_sitemeemail'] = trim($_POST['siteemail']);
        } else {
            $_SESSION['session_sitemetadesc'] = addslashes(trim($_POST['sitemetadesc']));
            $_SESSION['session_sitemetakey'] = addslashes(trim($_POST['sitemetakey']));
            $_SESSION['session_sitetitle'] = addslashes(trim($_POST['sitetitle']));
            $_SESSION['session_sitemeemail'] = addslashes(trim($_POST['siteemail']));
        }

        if ($errormessagefileupload == "") {
            $_SESSION['session_backurl'] = "getsitedetail.php";
            header("location:addlinks.php");
            exit;
        }
    }
    /**
     */

}
// echo "assigned company name".$_SESSION['companyname'];
include "includes/userheader.php";

?>
<script>

    function validateform(){
        return true;
        frm=document.frmSiteDetails;
        if(frm.filelogo.value=="" && document.getElementById("preview").innerHTML=="&nbsp;"){
            alert("Please upload your logo");
            return false;
        }else if(frm.companyname.value==""){
            alert("Please enter your company name");
            return false;
        }else if(frm.captionname.value==""){
            alert("Please enter your caption name");
            return false;
        }

        return true;
    }
    function insertourlogo(imageid){
        //alert(document.getElementById("preview").innerHTML);
        var imgtag="<img src='"+imageid+"' width='60' height='40' >";
        //alert(imgtag);
        document.getElementById("preview").innerHTML=imgtag;
        //alert(document.getElementById("preview").innerHTML);
        //alert("test");
    }
    function logosample(){
        winname="LogoSample";
        winurl="logosample.php";
        //alert(winurl);
        var t="logooption(0)";

        document.getElementById("logooption2").checked=true;
        window.open(winurl,winname,'width=400,height=560,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,copyhistory=no,resizable=no');


    }
    function checkupload(){
        document.getElementById("logooption1").checked=true;
    }
    function changecolor(currentid){
        winname="SiteBuilderchangecolor";
        winurl="chnagefontcolor.php";
        window.open(winurl,winname,'width=200,height=220,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
    }
    function changesitecolor(currentid){

        winname="SiteBuilderchangesitecolor";
        winurl="chnagesizecolor.php";
        window.open(winurl,winname,'width=200,height=220,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
    }
    function setclrvalue(x){
        document.getElementById("fontcolor").style.backgroundColor=x;
        document.getElementById("fntclr").value=x;
        changecompanyfont('');
    }
    function setsiteclrvalue(x){
        document.getElementById("sitecolor").style.backgroundColor=x;
        //alert(x);
        document.getElementById("stclr").value=x;
    }
    function changecaptioncolor(currentid){
        winname="SiteBuilderchangecaptioncolor";
        winurl="chnagecaptionfontcolor.php";
        window.open(winurl,winname,'width=200,height=220,toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,copyhistory=no,resizable=no,minimize=no');
    }
    function setcaptionfontclrvalue(x){
        document.getElementById("captfontcolor").style.backgroundColor=x;
        document.getElementById("captfntclr").value=x;
        changecaptionfont('');
    }


    function changecompanyfont(obj){
        var company_name=document.getElementById("companyname").value;
        var font_name=document.getElementById("compfont").value;
        var font_size=document.getElementById("fontsize").value;
        var font_clr=document.getElementById("fntclr").value;

        company_name=escape(company_name);
        var requesturl;
        requesturl="showchangedimage.php?act=company&company_name="+company_name+"&font_name="+font_name+"&font_size="+font_size+"&font_clr="+font_clr.slice(1)+"&";
        var http;
        if (window.XMLHttpRequest) {
            http = new XMLHttpRequest();
        } else {
            try {
                http = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    http = false;
                }
            }
        }
        http.open("GET",requesturl,false)
        http.send();
        //alert(http.responseText);
        var newfilename=http.responseText;

        document.getElementById("companypriview").innerHTML=" <img src='"+newfilename+"'>";

    }

    function changecaptionfont(obj){
        var caption_name=document.getElementById("captionname").value;
        var font_name=document.getElementById("captionfont").value;
        var font_size=document.getElementById("captionfontsize").value;
        var font_clr=document.getElementById("captfntclr").value;
        caption_name=escape(caption_name);
        var requesturl;
        requesturl="showchangedimage.php?act=caption&caption_name="+caption_name+"&font_name="+font_name+"&font_size="+font_size+"&font_clr="+font_clr.slice(1)+"&";

        var http;
        if (window.XMLHttpRequest) {
            http = new XMLHttpRequest();
        } else {
            try {
                http = new ActiveXObject("Msxml2.XMLHTTP");
            } catch (e) {
                try {
                    http = new ActiveXObject("Microsoft.XMLHTTP");
                } catch (e) {
                    http = false;
                }
            }
        }
        http.open("GET",requesturl,false)
        http.send();

        var newfilename=http.responseText;

        document.getElementById("captionpriview").innerHTML=" <img src='"+newfilename+"'>";

    }

</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align=center>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align=center>

                        <div class="stage_selector">
                            <span>2</span>&nbsp;&nbsp;Customize Appearance
                        </div>




                    </td>
                </tr>
                <tr>
                    <td class=errormessage><?php echo $errormessage;
                        ?></td>
                </tr>
                <tr>
                    <td>&nbsp;</td>
                </tr>
                <tr>
                    <td class=maintext>Site name:&nbsp;<b><?php echo $_SESSION['session_sitename'];
                            ?></b></td>
                </tr>
                <tr>
                    <td >
                        <!-- Main section starts here-->
                        <form name=frmSiteDetails method=post  enctype="multipart/form-data" onsubmit="return validateform();">
                            <table width="100%"  border=0>
                                <tr>
                                    <td>
                                        <FIELDSET>
                                            <LEGEND class=maintextbigbold>Logo</LEGEND>
                                            <table border="0" width="97%">
                                                <tr><td colspan=4>&nbsp;</td></tr>
                                                <tr><td colspan=4 class=errormessage><?php echo $errormessagefileupload; ?></td></tr>

                                                <tr>
                                                    <td ><input class=radiobutton type=radio name=logooption id=logooption value="N" checked></td>
                                                    <td class=maintext colspan=3 align=left>
                                                        No logos
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td ><input class=radiobutton type=radio name=logooption id=logooption1 value="U"></td>
                                                    <td class=maintext width="30%" align=left>
                                                        Upload your logo
                                                    </td>
                                                    <td align=left>
                                                        <input class=textbox type=file name=filelogo ONCLICK="checkupload();">
                                                    </td>
                                                    <td width="30%">&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td >&nbsp;</td>
                                                    <td class=maintext width="30%" align=left>
                                                    <td class=maintext align=left colspan=2 ><font size=1>
                                                            You can upload your site's logo to fit in to the site. If you don't have one, you can select from the samples provided by <?php echo($_SESSION["session_lookupsitename"]);
                                                            ?>.


                                                        </font>
                                                    </td>
                                                </tr>
                                                <tr><td colspan=4>&nbsp;</td></tr>


                                                <tr>
                                                    <td ><input class=radiobutton type=radio name=logooption id=logooption2 value="S"></td>
                                                    <td colspan=3  class=maintext align=center>
                                                        <a class=anchor href="javascript:logosample();">Select from our sample logos</a>
                                                    </td>
                                                    <td id=preview>&nbsp;</td>
                                                </tr>


                                                <tr>
                                                <tr><td colspan=4>&nbsp;</td></tr>
                                                <?php
                                                if ($_SESSION['session_logobandname'] != "") {
                                                    echo "<script>document.getElementById('preview').innerHTML='';var imgtag=\"<img src='./workarea/tempsites/" . $tmpsiteid . "/images/" . $_SESSION['session_logobandname'] . "' width='60' height='40' >\";";
                                                    echo "document.getElementById('preview').innerHTML=imgtag;</script>";
                                                }

                                                ?>
                                            </table>
                                        </FIELDSET>



                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td>
                                        <FIELDSET>
                                            <LEGEND class=maintextbigbold>Company</LEGEND>
                                            <table width="97%" border="0">
                                                <tr><td colspan=6>&nbsp;</td></tr>
                                                <tr>

                                                    <td colspan=6 align=center id=companypriview>

                                                    </td>
                                                </tr>



                                                <tr>
                                                    <td class=maintext width="10%" align=left>Company&nbsp;</td>
                                                    <td colspan=5 align=left width="30%">
                                                        <input type=text class=textbox name=companyname id="companyname" size=40 maxlength=99 value="<?php echo htmlentities($_SESSION['session_comseltext']);
                                                               ?>" onchange="changecompanyfont(this)" >
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=maintext   align=left>&nbsp;</td>
                                                    <td class=maintext align=left colspan=5 ><font size=1>
                                                            The company name will be embedded as an image. Select the font properties like color, size and type to best fit to the overall site esthetics
                                                        </font>
                                                    </td>
                                                </tr>
                                                <tr><td colspan=6>&nbsp;</td></tr>
                                                <tr>
                                                    <td class=maintext align=right width="10%">Font&nbsp;</td>
                                                    <td align=left width="30%">
                                                        <SELECT  name=compfont id="compfont" onchange="changecompanyfont(this)" class=selectbox>
                                                            <?php
                                                            builFontSelectBox($_SESSION['session_comselfont']);

                                                            ?>


                                                        </SELECT>
                                                    </td>
                                                    <td class=maintext align=right>Size&nbsp;</td>
                                                    <td align=left >
                                                        <SELECT class=selectbox name=fontsize id="fontsize" onchange="changecompanyfont(this)">
                                                            <?php
                                                            for($i = 10;$i < 30;$i++) {
                                                                if ($i == $_SESSION['session_comselfontsize']) $selflag = "selected";
                                                                else $selflag = "";
                                                                $fontsize .= "<option value=$i $selflag>$i</option>";
                                                            }
                                                            echo $fontsize;

                                                            ?>

                                                        </SELECT>
                                                    </td>

                                                    <td class=maintext align=right>Color&nbsp;</td>
                                                    <td align=left>
                                                        <input class=textbox id=fontcolor size=2 readonly style="background-color:black">
                                                        <input class=button   type=button value="select color" onclick="changecolor(this.id);">
                                                        <input type=hidden name=fntclr id=fntclr value="#000000" >
                                                    </td>
                                                </tr>


                                                <tr><td colspan=6>&nbsp;</td></tr>
                                            </table>
                                            <?php
                                            if ($_SESSION['session_comselfontclr'] != "") {
                                                echo "<script>document.getElementById('fontcolor').style.backgroundColor='" . $_SESSION['session_comselfontclr'] . "';</script>";
                                                echo "<script>document.getElementById('fntclr').value='" . $_SESSION['session_comselfontclr'] . "';</script>";
                                            }

                                            ?>

                                        </FIELDSET>

                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td>
                                        <FIELDSET>
                                            <LEGEND class=maintextbigbold>Site Color</LEGEND>
                                            <table width="97%" border="0">
                                                <tr><td colspan=2>&nbsp;</td></tr>
                                                <tr>
                                                    <td class=maintext align=left width="20%">Select Color</td>
                                                    <td align=left>
                                                        <input class=textbox id=sitecolor size=2 readonly style="">
                                                        <input class=button  type=button value="select color" onclick="changesitecolor(this.id);">
                                                        <input type=hidden name=stclr id=stclr value="">
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td class=maintext width="20%"  align=left>&nbsp;</td>
                                                    <td class=maintext align=left colspan=5 >
                                                        <font size=1>
                                                            Site color can also be changed to make variations in the overall look and feel of the site.
                                                        </font>
                                                    </td>
                                                </tr>
                                                <tr>
                                                <tr><td colspan=2>&nbsp;</td></tr>
                                            </table>
                                        </FIELDSET>
                                    </td>
                                </tr>
                                <?php
                                if ($_SESSION['session_sitecolor'] != "") {
                                    echo "<script>document.getElementById('sitecolor').style.backgroundColor='" . $_SESSION['session_sitecolor'] . "';</script>";
                                    echo "<script>document.getElementById('stclr').value='" . $_SESSION['session_sitecolor'] . "';</script>";
                                }

                                ?>

                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td >
                                        <FIELDSET>
                                            <LEGEND class=maintextbigbold>Caption</LEGEND>
                                            <table width="97%" border="0">
                                                <tr><td colspan=6>&nbsp;</td></tr>
                                                <tr>

                                                    <td colspan=6 align=center id=captionpriview>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=maintext width="10%" align=left>Caption&nbsp;</td>
                                                    <td align=left colspan=5>
                                                        <input type=text class=textbox name=captionname id="captionname" maxlength=99 size=70 value="<?php echo htmlentities($_SESSION['session_capseltext']);
                                                               ?>" onchange="changecaptionfont(this)">
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=maintext width="10%"  align=left>&nbsp;</td>
                                                    <td class=maintext align=left colspan=5 ><font size=1>
                                                            Site Caption will also be embedded as an image in the site. You can set the font properties for Site Caption also.


                                                        </font>
                                                    </td>
                                                </tr>
                                                <tr>
                                                <tr><td colspan=6>&nbsp;</td></tr>
                                                <tr>
                                                    <td class=maintext align=right >Font&nbsp;</td>
                                                    <td align=left width="20%">
                                                        <SELECT class=selectbox name=captionfont id="captionfont" onchange="changecaptionfont(this)">
                                                            <?php
                                                            builFontSelectBox($_SESSION['session_capselfont']);

                                                            ?>
                                                        </SELECT>
                                                    </td>
                                                    <td class=maintext align=right>Size&nbsp;</td>
                                                    <td align=left >
                                                        <SELECT class=selectbox name=captionfontsize id="captionfontsize" onchange="changecaptionfont(this)">
                                                            <?php
                                                            $i = 0;
                                                            $fontsize = "";
                                                            for($i = 10;$i < 30;$i++) {
                                                                if ($i == $_SESSION['session_capselfontsize']) $selflag = "selected";
                                                                else $selflag = "";
                                                                $fontsize .= "<option value=$i $selflag>$i</option>";
                                                            }
                                                            echo $fontsize;

                                                            ?>

                                                        </SELECT>
                                                    </td>

                                                    <td class=maintext align=right>Color&nbsp;</td>
                                                    <td align=left>
                                                        <input class=textbox id=captfontcolor size=2 readonly style="background-color:black">
                                                        <input  class=button type=button value="select color" onclick="changecaptioncolor(this.id);">
                                                        <input type=hidden name=captfntclr id=captfntclr value="#000000">
                                                    </td>
                                                </tr>
                                                <?php
                                                if ($_SESSION['session_capselfontclr'] != "") {
                                                    echo "<script>document.getElementById('captfontcolor').style.backgroundColor='" . $_SESSION['session_capselfontclr'] . "';</script>";
                                                    echo "<script>document.getElementById('captfntclr').value='" . $_SESSION['session_capselfontclr'] . "';</script>";
                                                }

                                                ?>
                                                <tr><td colspan=6>&nbsp;</td></tr>
                                            </table>
                                        </FIELDSET>
                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td>

                                        <FIELDSET>
                                            <LEGEND class=maintextbigbold>Others</LEGEND>
                                            <table width="97%" border="0" CELLSPACING="4">
                                                <tr><td colspan=3>&nbsp;</td></tr>
                                                <tr>
                                                    <td class=maintext width="30%"  align=left VALIGN="top">Page Title&nbsp;</td>
                                                    <td align=left>
                                                        <input type=text class=textbox name=sitetitle size=40 maxlength=99 value="<?php echo htmlentities(stripslashes($_SESSION['session_sitetitle']));
                                                               ?>">

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=maintext width="30%"  align=left>&nbsp;</td>
                                                    <td class=maintext align=left><font size=1>
                                                            The title of a page is what you see on the title bar or top bar of the browser, ie., the top-left corner of the browser</font>
                                                    </td>
                                                </tr>
                                                <!--<tr>
                                                  <td class=maintext width="30%"  align=left VALIGN="top">Email&nbsp;</td>
                                                  <td align=left>
                                                       <input type=text class=textbox name=siteemail size=40 maxlength=99 value="<?php echo htmlentities(stripslashes($_SESSION['session_sitemeemail']));
                                                ?>">

                                                  </td>
                                                </tr>-->
                                                <tr><td colspan=3>&nbsp;</td></tr>
                                                <tr>
                                                    <td class=maintext width="30%"  align=left VALIGN="top">Meta description&nbsp;</td>
                                                    <td align=left>
                                                        <TEXTAREA class=textbox name=sitemetadesc id=sitemetadesc rows=7 cols=40><?php echo htmlentities(stripslashes($_SESSION['session_sitemetadesc']));
                                                            ?></TEXTAREA>

                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=maintext width="30%"  align=left>&nbsp;</td>
                                                    <td class=maintext align=left><font size=1>
                                                            A META description is a one or two sentence description for each page with accurate and interesting description of the page. While creating pages, include the important keyword phrase in the meta description.</font>
                                                    </td>
                                                </tr>
                                                <tr><td colspan=3>&nbsp;</td></tr>
                                                <tr>
                                                    <td class=maintext width="30%"  align=left VALIGN="top">Meta keywords&nbsp;</td>
                                                    <td class=maintext align=left class=maintext>
                                                        <TEXTAREA class=textbox name=sitemetakey id=sitemetakey rows=7 cols=40><?php echo htmlentities(stripslashes($_SESSION['session_sitemetakey']));
                                                            ?></TEXTAREA>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class=maintext width="30%"  align=left>&nbsp;</td>
                                                    <td class=maintext align=left><font size=1>
                                                            Meta Keywords contain the most important words in a page, with their plurals misspellings and variations. The content in this tag is ignored by some search engines but is worth doing. Fill in the content of the meta keywords tag with keywords and keyword phrases relevant to the topic of the page. Include common misspellings of keywords, mix in capitalized versions of appropriate words, as well as plurals. Don't separate words with commas. Never repeat words more than three or four times and be sure to separate repetitions and similar words.

                                                        </font>
                                                    </td>
                                                </tr>
                                                <tr><td colspan=3>&nbsp;</td></tr>
                                            </table>
                                            </legend>
                                        </FIELDSET>
                                    </td>
                                </tr>
                                <tr><td>&nbsp;</td></tr>
                                <tr><td>
                                        <input class=button type=submit name=btncontinue value="Continue">
                                        <input class=button type=submit name=btnback value="Back">
                                    </td></tr>
                            </table>
                        </form>
                        <!-- Main section ends here-->
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>
<?php
/************************************** show company band **********************************************************/
if ($_SESSION['session_companybandname'] != "") {

    ?>
<script>
    <?php
    $compimgurl= "var compimgurl=\"./workarea/tempsites/$tmpsiteid/images/".$_SESSION['session_companybandname']."\";\n";
    echo $compimgurl;
    ?>
        document.getElementById("companypriview").innerHTML=" <img src='"+compimgurl+"'>";
</script>
    <?php
}else {


    ?>
<script>
    <?php
    $compimgurl= "var compimgurl=\"./workarea/tempsites/$tmpsiteid/images/".$tp_company."\";\n";
    echo $compimgurl;
    ?>
        document.getElementById("companypriview").innerHTML=" <img src='"+compimgurl+"'>";
</script>
    <?php


}
?>

<?php
/************************************** show Caption band **********************************************************/
if ($_SESSION['session_captionbandname'] != "") {

    ?>
<script>
    <?php
    $captionimgurl= "var captionimgurl=\"./workarea/tempsites/$tmpsiteid/images/".$_SESSION['session_captionbandname']."\";\n";
    echo $captionimgurl;
    ?>
        document.getElementById("captionpriview").innerHTML=" <img src='"+captionimgurl+"'>";
</script>
    <?php
}else {


    ?>
<script>
    <?php
    $captionimgurl= "var captionimgurl=\"./workarea/tempsites/$tmpsiteid/images/".$tp_caption."\";\n";
    echo $captionimgurl;
    ?>
        document.getElementById("captionpriview").innerHTML=" <img src='"+captionimgurl+"'>";
</script>
    <?php


}
?>


<?php
include "includes/userfooter.php";

?>