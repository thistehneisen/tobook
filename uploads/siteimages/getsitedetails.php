<?php  

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +----------------------------------------------------------------------+

// | PHP version 4/5                                                      |

// +----------------------------------------------------------------------+

// | Copyright (c) 2005-2006 ARMIA INC                                    |

// +----------------------------------------------------------------------+

// | This source file is a part of iScripts EasyCreate 1.1                 |

// +----------------------------------------------------------------------+

// | Authors: sudheesh<sudheesh@armia.com>                                    |

// |                                                                                                            |

// +----------------------------------------------------------------------+

include "includes/session.php";

include "includes/config.php";

include "includes/function.php";

include "includes/sitefunctions.php";



$siteLanguageOption = getSettingsValue("site_language_option");



if($_REQUEST['siteid']) {

    $site_id = $_REQUEST['siteid'];

    $action  = $_REQUEST['action'];

}



if($_REQUEST['tempid']!=='' && $_REQUEST['themeid']!='' ) {

    $templateid=$_GET['tempid'];

    $templateThemeId=$_GET['themeid'];

}else {

    list($templateid,$templateThemeId)= explode('_',$_POST['chekSelTemplate']);

}



// Added to retain the template theme in show templates page

if(!empty($templateid)) {

    $_SESSION['siteDetails']['siteInfo']['siteTemplateId'] = $templateid;

    $_SESSION['siteDetails']['siteInfo']['siteThemeId']    = $templateThemeId;

}

// Added to retain the template theme in show templates page



// Set site details to session from db in site edit mode

if($site_id > 0) {

    $siteData = getSiteData($site_id);

    $templateid =  $siteData['ntemplate_id'];

    $templateThemeId = $siteData['ntheme_id'];

    $_SESSION['siteDetails']['siteId'] = $site_id;

}



//echo 'before: '; echopre($_SESSION['siteDetails']['siteInfo']);

$_SESSION['cnt']=0;

$siteName     = ($_SESSION['siteDetails']['siteInfo']['siteName'])?stripslashes($_SESSION['siteDetails']['siteInfo']['siteName']):stripslashes($siteData['vsite_name']);

if($siteName=='') $siteName = 'Untitled_'.time();

$siteLogoOption  = ($_SESSION['siteDetails']['siteInfo']['logooption'])?$_SESSION['siteDetails']['siteInfo']['logooption']:$siteData['vlogo'];

$siteLogoName = ($_SESSION['siteDetails']['siteInfo']['logoName'])?$_SESSION['siteDetails']['siteInfo']['logoName']:$siteData['vlogo_name'];

$siteCompany  = ($_SESSION['siteDetails']['siteInfo']['companyname'])?$_SESSION['siteDetails']['siteInfo']['companyname']:$siteData['vcompany'];

$chksitedesstyle  = ($_SESSION['siteDetails']['siteInfo']['chksitedesstyle'])?$_SESSION['siteDetails']['siteInfo']['chksitedesstyle']:$siteData['chksitedesstyle'];

$chksitetitlestyle  = ($_SESSION['siteDetails']['siteInfo']['chksitetitlestyle'])?$_SESSION['siteDetails']['siteInfo']['chksitetitlestyle']:$siteData['chksitetitlestyle'];

$companyStyle = explode("**",$siteData['vcompany_style']);

$siteCompanyFont      = ($_SESSION['siteDetails']['siteInfo']['compfont'])?$_SESSION['siteDetails']['siteInfo']['compfont']:$companyStyle[0];

$siteCompanyFontSize  = ($_SESSION['siteDetails']['siteInfo']['fontsize'])?$_SESSION['siteDetails']['siteInfo']['fontsize']:$companyStyle[1];

$siteCompanyFontColor = ($_SESSION['siteDetails']['siteInfo']['fntclr'])?$_SESSION['siteDetails']['siteInfo']['fntclr']:$companyStyle[2];

$siteColor       = ($_SESSION['siteDetails']['siteInfo']['stclr'])?$_SESSION['siteDetails']['siteInfo']['stclr']:$siteData['vcolor'];

//by me
$siteBackImage       = ($_SESSION['siteDetails']['siteInfo']['background-image'])?$_SESSION['siteDetails']['siteInfo']['background-image']:$siteData['vimage'];

$siteCaption     = ($_SESSION['siteDetails']['siteInfo']['captionname'])?$_SESSION['siteDetails']['siteInfo']['captionname']:$siteData['vcaption'];

$captionStyle    = explode("**",$siteData['vcaption_style']);

$siteCaptionFont = ($_SESSION['siteDetails']['siteInfo']['captionfont'])?$_SESSION['siteDetails']['siteInfo']['captionfont']:$captionStyle[0];

$siteCaptionFontSize  = ($_SESSION['siteDetails']['siteInfo']['captionfontsize'])?$_SESSION['siteDetails']['siteInfo']['captionfontsize']:$captionStyle[1];

$siteCaptionFontColor = ($_SESSION['siteDetails']['siteInfo']['captfntclr'])?$_SESSION['siteDetails']['siteInfo']['captfntclr']:$captionStyle[2];

$siteGoogleAnalyticsCode     = ($_SESSION['siteDetails']['siteInfo']['site_google_analytics_code'])?$_SESSION['siteDetails']['siteInfo']['site_google_analytics_code']:$siteData['google_analytics_code'];

$sitePageTitle   = ($_SESSION['siteDetails']['siteInfo']['sitetitle'])?$_SESSION['siteDetails']['siteInfo']['sitetitle']:$siteData['vtitle'];

$siteMetaDesc    = ($_SESSION['siteDetails']['siteInfo']['sitemetadesc'])?$_SESSION['siteDetails']['siteInfo']['sitemetadesc']:$siteData['vmeta_description'];

$siteMetaKey     = ($_SESSION['siteDetails']['siteInfo']['sitemetakey'])?$_SESSION['siteDetails']['siteInfo']['sitemetakey']:$siteData['vmeta_key'];



if($templateid=='' && $templateThemeId=='' ) {

    $templateid      = $_SESSION['siteDetails']['siteInfo']['templateid'];

    $templateThemeId = $_SESSION['siteDetails']['siteInfo']['themeid'];

}









/*

if($templateid == "") {

    header("location:sitemanager.php");

    exit;

}

*/

$userid = $_SESSION["session_userid"];



$createdSiteGoogleAnalytics = getSettingsValue('enable_created_site_google_analytics');



if (isset($_POST['btnback'])) {

    //$location = "showtemplates.php?catid=" . $_SESSION['session_categoryid'];

    $location=($_SESSION['gtemplatebackurl'])?$_SESSION['gtemplatebackurl']:"showtemplates.php?catid=" . $_SESSION['session_categoryid'];



    if($site_id > 0) {

        $locationUrl = 'sitemanager.php';

    }else if(isset($location) && $location!='') {

        $locationUrl = $location;

    }

    header("location:$locationUrl");

    exit;

} else if (isset($_POST['btnskip'])) {

    $_SESSION['siteDetails']['siteInfo'] = $_POST;

    $_SESSION['siteDetails']['siteInfo']['stclr'] =  (($_POST['stclr'] =='FEFEFE')?'': '#'.$_POST['stclr']);

    $_SESSION['siteDetails']['siteInfo']['logoName'] = $siteLogoName;
	
	$_SESSION['siteDetails']['siteInfo']['background-image'] = $siteBackImage;  // by me

    if($site_id > 0)

        header("location:editor.php?siteid=$site_id&action=$action");

    else

        header("location:editor.php");

    exit;

}else if (isset($_POST['btncontinue'])) {



    $_SESSION['siteDetails']['siteInfo'] = $_POST;



    $_SESSION['siteDetails']['siteInfo']['stclr'] =  (($_POST['stclr'] =='FEFEFE')?'': '#'.$_POST['stclr']);

    $_SESSION['siteDetails']['siteInfo']['logoName'] = $siteLogoName;
	
	$_SESSION['siteDetails']['siteInfo']['background-image'] = $siteBackImage;  // by me



    $errorFlag = 0;



    if (!validateSizePerUser($_SESSION["session_userid"], $size_per_user, $allowed_space)) {

        $errormessage = VAL_ERRMSG1. human_read($size_per_user) . VAL_ERRMSG2 . human_read($allowed_space) . VAL_ERRMSG3;

    }else if(trim($_POST['siteName'])=='') {

        $errormessage = SM_MESSAGE_VALIDATION_EMPTY_SITENAME;

    } else if (!isSitenameExist($_POST['siteName'],$site_id)) {

        $errormessage = SM_MESSAGE_VALIDATION_SITENAME_EXISTS;

    }

    else {



        if($siteLanguageOption == "english"){

            if (!isValidsitename($_POST['siteName'])) {

                $errormessage = SM_MESSAGE_VALIDATION_SITENAME;

                $errorFlag = 1;

            }

        }



        if($errorFlag==0){



            /* user either uploaded logo or selected our logo samples*/

            $errormessage = "";



            if($_POST['logooption']=="U" ) {



                $errormessagefileupload = "";

                if ($_FILES['filelogo']['size'] > 0) {



                    $path = EDITOR_USER_IMAGES;



                    list($width, $height, $type, $attr) = @getimagesize($_FILES['filelogo']['tmp_name']);

                    if ($type > 3) {

                        $errormessagefileupload = VAL_IMAGETYPE;

                    }else {

                        $imagewidth_height_type_array = explode(":", ImageType($_FILES['filelogo']['tmp_name']));

                        $imagetype = $imagewidth_height_type_array[0];

                        $assignedname = "logo_" . time() . "." . $imagetype;



                        $_SESSION['siteDetails']['siteInfo']['logoName'] = BASE_URL.'uploads/siteimages/'.$assignedname;

                        move_uploaded_file($_FILES['filelogo']['tmp_name'],$path . $assignedname);



                    }

                }

                if ($errormessagefileupload == "") {



                }

            }else if($_POST['logooption']=="S" ) {

                //$_SESSION['siteDetails']['siteInfo']['logoName'] = BASE_URL.'uploads/siteimages/'.$_SESSION['logoname'];

            }



            if (trim($_POST['companyname']) != "") {

                if (get_magic_quotes_gpc() == 1) {

                    $companyname = stripslashes($_POST['companyname']);

                } else {

                    $companyname = $_POST['companyname'];

                }

            }



            if (trim($_POST['captionname']) != "") {

                if (get_magic_quotes_gpc() == 1) {

                    $captionname = stripslashes($_POST['captionname']);

                } else {

                    $captionname = $_POST['captionname'];

                }

            }
			
			
			if(is_uploaded_file($_FILES['backimage']['tmp_name'])) {
				
				$path = EDITOR_USER_IMAGES;



                    list($width, $height, $type, $attr) = @getimagesize($_FILES['backimage']['tmp_name']);

                    if ($type > 3) {

                        $errormessagefileupload = VAL_IMAGETYPE;

                    }else {

                $imagewidth_height_type_array = explode(":", ImageType($_FILES['backimage']['tmp_name']));
				$imagetype = $imagewidth_height_type_array[0];
				
				$assignedname2 = "back_" . time() . "." . $imagetype;
				
				
				$_SESSION['siteDetails']['siteInfo']['background-image'] = BASE_URL.'uploads/siteimages/'.$assignedname2;

                move_uploaded_file($_FILES['backimage']['tmp_name'],$path . $assignedname2);
					}


                }
				
				if ($errormessagefileupload == "") {



                }



            if ($errormessagefileupload == "") {

                $_SESSION['session_backurl'] = "getsitedetail.php";

                if($site_id > 0)

                    header("location:editor.php?siteid=$site_id&action=$action");

                else

                    header("location:editor.php");

                exit;

            }
			
			

        }

    }

}







include "includes/userheader.php";

?>

<script language="javascript" src="js/jquery.js"></script>

<script language="javascript" src="js/getsitedetails.js"></script>



<script type="text/javascript" src="js/jscolor/jscolor.js"></script>



<script type="text/javascript">



$(document).ready(function(){

    $("#jQReset") .click(function(){

        $("#stclr").val('');

        $("#stclr").css("backgroundColor", "");



    });

});

</script>





<table width="100%"  border="0" cellspacing="0" cellpadding="0">

    <tr>

        <td  valign="top" align="center">

            <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                <tr>

                    <td align="left">

                        <h2><span class="step-cnt">3</span> <?php echo CUSTOM_APPEARANCE;?></h2>

                    </td>

                </tr>

                <tr>

                    <td class=errormessage><?php echo $errormessage; ?></td>

                </tr>

                <tr>

                    <td >

                        <!-- Main section starts here-->

                        <form name=frmSiteDetails method=post  enctype="multipart/form-data" onsubmit="return validateform();">

                            <input type="hidden" name="templateid" value="<?php echo $templateid;?>">

                            <input type="hidden" name="themeid" value="<?php echo $templateThemeId;?>">

                            <table width="100%"  border="0" class="customize-tbl" cellpadding="0" cellspacing="0">

                                <tr>

                                    <td>

                                        <table border="0" width="100%" cellpadding="0" cellspacing="0">

                                            <tr><td colspan="6"><h4><?php echo CUSTOM_SITE_NAME;?></h4></td></tr> 

                                            <tr><td></td></tr>

                                            <tr>

                                                <td class=maintext width="45%" align="left"><?php echo CUSTOM_SITE_NAME;?></td>

                                                <td colspan=5 align=left width="55%">

                                                    <input type="text" class="textbox" name="siteName" id="siteName" value="<?php echo $siteName; ?>">

                                                    <span> <?php echo CUSTOM_SITE_DESCRIPTION;?></span>

                                                </td>

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        <table border="0" width="100%" cellpadding="0" cellspacing="0">

                                            <td colspan="4"><h4><?php echo CUSTOM_SELECT_LOGO;?></h4></td>

                                            <tr><td colspan="4" class=errormessage><?php echo $errormessagefileupload; ?></td></tr>



                                            <tr>

                                                <td ></td>

                                                <td colspan="3" align="left"><input class="radiobutton" type="radio" name="logooption" id="logooption" value="N"  <?php echo ($siteLogoOption=='N' || $siteLogoOption=='' )?'checked':''; ?>>

                                                    <label><?php echo CUSTOM_NO_LOGOS;?></label>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td ></td>

                                                <td width="24%" align=left><input class="radiobutton" type="radio" name="logooption" id="logooption1" value="U" <?php echo ($siteLogoOption=='U')?'checked':''; ?>>

                                                    <label><?php echo CUSTOM_UPLOAD_LOGO;?></label>

                                                </td>

                                                <td align="left" colspan="2" valign="top">

                                                    <input class="textbox" type="file" name="filelogo" ONCLICK="checkupload();">&nbsp;&nbsp;

                                                </td>

                                            </tr>

                                            <tr>

                                                <td colspan="2">&nbsp;</td>

                                                <td align="left" colspan="2" >

                                                    <span><?php echo CUSTOM_UPLOAD_LOGO_DESC;?></span> 





                                                </td>

                                            </tr>

                                            <tr>

                                                <td valign="top" ></td>

                                                <td  class="maintext"><input class=radiobutton type=radio name=logooption id=logooption2 value="S" <?php echo ($siteLogoOption=='S')?'checked':''; ?>>

                                                    <label><?php echo CUSTOM_SELECT_SAMPLE;?></label>

                                                </td>

                                                <td width="45%">

                                                    <a class="anchor grey-btn04" href="javascript:logosample();"><?php echo SELECT;?></a>  <span><?php echo CUSTOM_SELECT_SAMPLE_LOGO;?></span>                                            </td>

                                                <td width="9%" id=preview>&nbsp;</td>

                                            </tr>

                                            <?php if($siteLogoName) { ?>

                                            <tr>

                                                <td width="22%" align=left>

                                                    <label><?php echo CUSTOM_YOUR_LOGO;?></label>

                                                </td>

                                                <td align="left" colspan="2" >

                                                    <div id="jQLogo"><img src="<?php echo $siteLogoName;?>" width="70" height="70" /> </div>

                                                </td>

                                            </tr>

                                                <?php  } ?>



                                        </table>



                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">

                                            <tr><td colspan="6"><h4><?php echo CUSTOM_WEBSITE_TITLE;?></h4></td></tr>

                                            <tr>

                                                <td colspan="6" id="companypriview"> </td>

                                            </tr>

                                            <tr>

                                                <td class=maintext width="55%" align="left"><?php echo CUSTOM_SITE_TITLE;?></td>

                                                <td colspan=5 align=left>

                                                    <input type="text" class="textbox" name="companyname" id="companyname" value="<?php echo trim($siteCompany);

                                                           ?>" onchange="changecompanyfont(this)" >





                                                </td>

                                            </tr>

                                            <tr>

                                                <td>&nbsp;</td>

                                                <td class="maintext" align="left" colspan="5" >

                                                    <span> <?php echo CUSTOM_SITE_TILEDESCRIPTION;?></span>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td class="maintext" width="55%"></td>

                                                <td align="left" width="13%" class="maintext" ><?php echo CUSTOM_FONT;?> &nbsp;

                                                    <SELECT  name=compfont id="compfont" onchange="changecompanyfont(this)" class=selectbox>

                                                        <?php

                                                        builFontSelectBox($siteCompanyFont);

                                                        ?>

                                                    </SELECT>

                                                </td>

                                                <td width="5%" align="right" class="maintext"><label><?php echo CUSTOM_SIZE;?></label></td>

                                                <td width="12%" align="left" >&nbsp;

                                                    <SELECT class=selectbox name=fontsize id="fontsize" onchange="changecompanyfont(this)">

                                                        <?php

                                                        for($i = 10;$i < 50;$i++) {

                                                            if ($i == $siteCompanyFontSize) $selflag = "selected";

                                                            else $selflag = "";

                                                            $fontsize .= "<option value=$i $selflag>$i</option>";

                                                        }

                                                        echo $fontsize;



                                                        ?>



                                                    </SELECT>

                                                </td>



                                                <td width="4%" align="right" class="maintext"><label><?php echo CUSTOM_COLOR;?></label></td>

                                                <td width="11%" align="left">&nbsp;

                                                    <!--

                                                     <input class="textbox" id="fontcolor" size="2" readonly style="background-color:black">

                                                     <input class="button grey-btn04"   type="button" value="Select" onclick="changecolor(this.id);">

                                                     <input type="hidden" name="fntclr" id="fntclr" value="#000000" >

                                                    -->

                                                    <input type="hidden" name="fntclr" id="fntclr" value="" >

                                                    <input class="color" name="titlecolor" id="titlecolor" value="<?php echo $siteCompanyFontColor; ?>" size="6" onchange="changeTextColor('#'+this.color)">



                                                </td>

                                            </tr>

                                            <tr><td> </td>

                                                <td colspan="5">

                                                    <span style="float: left;"><input id="chksitetitlestyle" name="chksitetitlestyle" type="checkbox" value="1" <?php echo (($chksitetitlestyle ==1)?'checked="checked"':'');?>  /></span>

                                                    <span style="float:left;font-size: 12px; color: #0EB9AF;">  <?php echo CUSTOM_STYLE4SITE;?> </span>



                                                </td>

                                            </tr>

                                        </table>

                                        <?php

                                        if ($siteCompanyFontColor != "") {

                                            //echo "<script>document.getElementById('fontcolor').style.backgroundColor='" . $siteCompanyFontColor . "';</script>";

                                            echo "<script>document.getElementById('fntclr').value='" . $siteCompanyFontColor . "';</script>";

                                        }

                                        ?>

                                    </td>

                                </tr>

                                <tr>

                                    <td>



                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">

                                            <tr><td colspan="2"><h4><?php echo CUSTOM_SITE_COLOR;?> </h4></td></tr>

                                            <tr>

                                                <td class="maintext" width="23%"><label><?php echo CUSTOM_SELECT_BACKGROUND;?></label></td>

                                                <td width="77%" align="left" valign="top">

                                                    <!--

                                                       <input class="textbox txt-bx05" id=sitecolor size=2 readonly style="">

                                                       <input class="button grey-btn04"  type=button value="Select" onclick="changesitecolor(this.id);" style="margin:0px 0 0 3px; float:left ">

                                                       <input type=hidden name=stclr id=stclr value="">

                                                    -->

                                                    <input class="color" name="stclr" id="stclr" value="" size="6">&nbsp;&nbsp;

                                                    <input style="width: 60px;" class="grey-btn04" type="button" name="reset" id="jQReset" value="Reset" size="6">



                                                </td>

                                            </tr>



                                            <tr>

                                                <td class=maintext width="23%"  align=left>&nbsp;</td>

                                                <td class=maintext align=left colspan=5 >

                                                    <span><?php echo CUSTOM_BACKGROUND_DESCRIPTION;?></span>

                                                </td>

                                            </tr>

                            

                                        </table>

                                    </td>

                                </tr>
                                
                                <tr>

                                    <td>



                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">

                                            <tr><td colspan="2"><h4><?php echo 'Background Image'; ?></h4></td></tr>

                                            <tr>

                                                <td class="maintext" width="23%"><label><?php echo 'Set Background Image'; ?></label></td>

                                                <td width="77%" align="left" valign="top">


                                                    <input type="file" name="backimage" id="backimage">&nbsp;&nbsp;



                                                </td>

                                            </tr>
                            

                                        </table>

                                    </td>

                                </tr>

                                <?php

                                if ($siteColor != "") {

                                    echo "<script>document.getElementById('stclr').value='#" . $siteColor . "';</script>";

                                }

                                ?>

                                <tr>

                                    <td>



                                        <table width="100%" border="0" cellpadding="0" cellspacing="0">

                                            <tr><td colspan="6"><h4><?php echo CUSTOM_DESCRIPTION;?></h4></td></tr>

                                            <tr><td colspan="6" id=captionpriview></td> </tr>

                                            <tr>

                                                <td class="maintext" width="45%" align="left"><label><?php echo CUSTOM_SITE_DESC;?></label></td>

                                                <td align="left" colspan="5">

                                                    <input type=text class=textbox name=captionname id="captionname" maxlength=99 size=70 value="<?php echo trim($siteCaption);?>" onchange="changecaptionfont(this)">

                                                </td>

                                            </tr>

                                            <tr>

                                                <td class="maintext" width="45%"  align="left">&nbsp;</td>

                                                <td class="maintext" align="left" colspan="5" >

                                                    <span> 

                                                       <?php echo CUSTOM_DESCRIPTION_DETAIL;?>

                                                    </span>



                                                </td>

                                            </tr>

                                            <tr>

                                                <td ></td>

                                                <td align=left width="21%" class="maintext" ><label>    <?php echo CUSTOM_FONT;?></label>&nbsp;

                                                    <SELECT class=selectbox name=captionfont id="captionfont" onchange="changecaptionfont(this)">

                                                        <?php

                                                        builFontSelectBox($siteCaptionFont);

                                                        ?>

                                                    </SELECT>

                                                </td>

                                                <td width="4%" align="right" class="maintext"><label><?php echo CUSTOM_SIZE;?></label></td>

                                                <td width="5%" align=left >&nbsp;

                                                    <SELECT class=selectbox name=captionfontsize id="captionfontsize" onchange="changecaptionfont(this)">

                                                        <?php

                                                        $i = 0;

                                                        $fontsize = "";

                                                        for($i = 10;$i < 50;$i++) {

                                                            if ($i == $siteCaptionFontSize) $selflag = "selected";

                                                            else $selflag = "";

                                                            $fontsize .= "<option value=$i $selflag>$i</option>";

                                                        }

                                                        echo $fontsize;

                                                        ?>



                                                    </SELECT>

                                                </td>



                                                <td width="7%" align="right" class="maintext"><label><?php echo CUSTOM_COLOR;?></label></td>

                                                <td width="18%" align=left>&nbsp;

                                                    <!--

                                                        <input class=textbox id=captfontcolor size=2 readonly style="background-color:black">

                                                        <input  class="button grey-btn04" type=button value="Select" onclick="changecaptioncolor(this.id);">

                                                        <input type=hidden name=captfntclr id=captfntclr value="#000000">

                                                    -->

                                                    <input type=hidden name=captfntclr id=captfntclr value="#000000">

                                                    <input class="color" name="captfntclr1" id="captfntclr1" value="<?php echo $siteCaptionFontColor; ?>" size="6" onchange="changeDesriptionTextColor('#'+this.color)">

                                                </td>

                                            </tr>

                                            <?php

                                            if ( $siteCaptionFontColor != "") {

                                                //echo "<script>document.getElementById('captfontcolor').style.backgroundColor='" . $siteCaptionFontColor . "';</script>";

                                                echo "<script>document.getElementById('captfntclr').value='" . $siteCaptionFontColor . "';</script>";

                                            }

                                            ?>





                                            <tr><td> </td>

                                                <td colspan="5">



                                                    <span style="float: left;"><input id="chksitedesstyle" name="chksitedesstyle" type="checkbox" value="1" <?php echo (($chksitedesstyle ==1)?'checked="checked"':'');?>  /></span>

                                                    <span style="float:left;font-size: 12px; color: #0EB9AF;"><?php echo CUSTOM_STYLE4DESCRIPTION;?></span>



                                                </td>

                                            </tr>



                                        </table>

                                    </td>

                                </tr>

                                <tr>

                                    <td>

                                        <table width="100%" border="0" CELLSPACING="0" cellpadding="0">

                                            <tr><td colspan="2"><h4><?php echo CUSTOM_OTHERS;?></h4></td></tr>

                                            <tr><td>&nbsp;</td></tr>

                                            <?php if($createdSiteGoogleAnalytics=='Y') { ?>

                                            <tr>

                                                <td class=maintext width="22%"  align=left VALIGN="top"><?php echo CUSTOM_ANALYTICS;?> </td>

                                                <td align=left colspan="2">

                                                    <input type=text class=textbox name=site_google_analytics_code value="<?php echo trim(stripslashes($siteGoogleAnalyticsCode)); ?>">



                                                </td>

                                            </tr>

                                            <tr>

                                                <td class=maintext  align=left>&nbsp;</td>

                                                <td class=maintext align=left>

                                                    <span> 

                                                        <?php echo CUSTOM_ANALYTICS_DESCRIPTION;?>

                                                        <a href="http://www.google.com/analytics/" target="_blank"><?php echo CUSTOM_GET_ANALYTICS;?></a>



                                                    </span>

                                                </td>

                                            </tr>

                                                <?php } ?>

                                            <tr>

                                                <td class=maintext width="22%"  align=left VALIGN="top"><?php echo CUSTOM_META_TITLE;?>&nbsp;</td>

                                                <td align=left colspan="2">

                                                    <input type=text class=textbox name=sitetitle size=40 maxlength=99 value="<?php echo trim(stripslashes($sitePageTitle)); ?>">



                                                </td>

                                            </tr>



                                            <tr>

                                                <td class=maintext  align=left>&nbsp;</td>

                                                <td class=maintext align=left>

                                                    <span>  <?php echo CUSTOM_META_TITLE_DESCRIPTION;?>









                                                    </span>

                                                </td>

                                            </tr>

                                            <!--<tr>

                                              <td class=maintext width="30%"  align=left VALIGN="top">Email&nbsp;</td>

                                              <td align=left>

                                                   <input type=text class=textbox name=siteemail size=40 maxlength=99 value="<?php //echo trim(stripslashes($_SESSION['session_sitemeemail']));?>">



                                              </td>

                                            </tr>-->

                                            <tr>

                                                <td class="maintext"  align="left" VALIGN="top"><?php echo CUSTOM_META_DESCRIPTION;?></td>

                                                <td align="left">

                                                    <TEXTAREA class="textbox" name=sitemetadesc id=sitemetadesc rows=7 cols=40><?php echo trim(stripslashes($siteMetaDesc));?></TEXTAREA>



                                                </td>

                                            </tr>

                                            <tr>

                                                <td class=maintext  align=left>&nbsp;</td>

                                                <td class=maintext align=left>

                                                    <span> <?php echo CUSTOM_META_DETAIL;?></span>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td class="maintext" align="left" VALIGN="top"><?php echo CUSTOM_META_KEYWORDS;?></td>

                                                <td class="maintext" align="left">

                                                    <TEXTAREA class=textbox name=sitemetakey id=sitemetakey rows=7 cols=40><?php echo trim(stripslashes($siteMetaKey));?></TEXTAREA>

                                                </td>

                                            </tr>

                                            <tr>

                                                <td>&nbsp;</td>

                                                <td class=maintext align=left>

                                                    <span> 

                                                        <?php echo CUSTOM_META_KEYWORDS_DESCRIPTION;?> </span>

                                                </td>

                                            </tr>

                                            <tr><td colspan="3">&nbsp;</td></tr>

                                            <tr>

                                                <td>&nbsp;</td>

                                                <td colspan="2" align="right">

                                                    <input class="grey-btn02" type="submit" name="btnback" value="<?php echo TEMPLATE_SAVE;?> "> &nbsp;

                                                    <input class="btn04" type="submit" name="btncontinue" value="<?php echo TEMPLATE_CONTINUE;?>">

                                                    <input class="grey-btn02" type="submit" name="btnskip" value="<?php echo TEMP_SKIP;?>"> &nbsp;

                                                </td>

                                            </tr>

                                        </table>

                                    </td>

                                </tr>

                                <tr>

                                    <td>&nbsp;</td>

                                    <td colspan="2">

                                    </td>

                                </tr>

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



include "includes/userfooter.php";

?>