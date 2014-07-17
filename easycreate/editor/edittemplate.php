<?php   
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2012-2013 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 2                 |
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
include "../includes/session.php";
include "../includes/config.php";
include "../includes/sitefunctions.php";
include "../includes/cls_htmlform.php";

include "editor_functions.php";

//$createdSiteBannerName = getSettingsValue('created_site_banner_name');
//$createdSiteBannerLink = getSettingsValue('created_site_banner_link');
/*
  * file to modify the panels of the template
*/

$currentPage 	= $_SESSION['siteDetails']['currentpage']; 

if($currentPage == ''){
	?>
	<script>
	parent.window.location = '../usermain.php';
	</script>
	<?php 
}

$panelId 		= $_GET['panelid'];
$templateid 	= $_GET['templateid']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<link rel="stylesheet" href="../style/editor_styles.css" type="text/css">
<script type="text/javascript" src="<?php echo BASE_URL?>ckeditor/ckeditor/ckeditor.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL?>ckeditor/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo BASE_URL?>js/validations.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        CKEDITOR.replace( 'editor1', {
            toolbar :[
                ['Bold','Italic','Underline', '-', 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock', '-', 'NumberedList','BulletedList'],
                ['Font','FontSize', 'TextColor']]
        });
        CKEDITOR.config.width = 850;
        CKEDITOR.config.height = 300;
        CKEDITOR.config.fullPage = false;
    });
</script>
<?php 
if($panelId != '') { 
	
	$templateid = $_SESSION['siteDetails']['siteInfo']['templateid'];
	$templateThemeId = $_SESSION['siteDetails']['siteInfo']['themeid'];
	$temThemeCss = getThemeCss($templateid,$templateThemeId);
	
	//$panelHtml = '<link type="text/css" rel="stylesheet" href="../f7979482a14a7aaec0ca7106a98085ea/610/styles/style.css" data-type="sitestyle">';
	 $panelHtml = $temThemeCss;
         $panelHtml .= $_SESSION['siteDetails'][$currentPage]['panels'][$panelId]; //echopre($panelHtml);

         //$pattern = '/<a href="#"(.*?)[^:]*<\/a>/';
         $pattern = '/<div class="manageMenuStyle"(.*?)[^:]*<\/a><\/div>/';
         $panelHtml = preg_replace($pattern,'', $panelHtml); //echopre($panelHtml);

         /*
         $bannerId = "editableshare_".$panelId."_1";
         $siteBannerContent = '<div id="site_banner" data-type="site_banner"><a href="'.$createdSiteBannerLink.'" target="_blank" linkmode="external"><img src="'.$createdSiteBannerName.'" class="editableshare" id="'.$bannerId.'" data-param="'.$bannerId.'"></a></div>';
         $panelHtml = str_replace($siteBannerContent,' ', $panelHtml);
         */

         //$sbPattern = '/<div id="site_banner"(.*?)[^:]<\/div>/';
         $sbPattern = '/<div id="site_banner"(.*?)[^:]*<\/a><\/div>/';
         $panelHtml = preg_replace($sbPattern,' ', $panelHtml);

         $doc = new DOMDocument();
         
         @$doc->loadHTML(mb_convert_encoding($panelHtml, 'HTML-ENTITIES', 'UTF-8'));
         
         // Add/Replace company name from customize site page
        if($_SESSION['siteDetails']['siteInfo']['companyname']!='') {
            applySiteCompany($_SESSION['siteDetails']['siteInfo']['companyname'],$_SESSION['siteDetails']['siteInfo']['compfont'],$_SESSION['siteDetails']['siteInfo']['fntclr'],$_SESSION['siteDetails']['siteInfo']['fontsize']);
        }

        // Add/Replace company name from customize site apge
        if($_SESSION['siteDetails']['siteInfo']['captionname']!='') {
            applySiteCaption($_SESSION['siteDetails']['siteInfo']['captionname'],$_SESSION['siteDetails']['siteInfo']['captionfont'],$_SESSION['siteDetails']['siteInfo']['captfntclr'],$_SESSION['siteDetails']['siteInfo']['captionfontsize']);
        }
        
        $panelHtml = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
         
    ?>



<div class="popupcontent_newwrapper">

    <div class="editpanel_popuptabs"><a href="" id="editor_showplainpanel" data-params="<?php echo $panelId.':'.$templateid;?>"><?php echo EDITOR_DESIGN;?></a> | <a href="" id="editor_showhtmlpanel"><?php echo EDITOR_HTML_VIEW;?></a> </div>

    <div id="panelEditResult"></div>
    <form name="frmHtmlPanelUpdator" id="frmHtmlPanelUpdator">
        <div id="editor_htmleditor" style="display: none;">
            <input type="hidden" name="panelid" id="panelid" value="<?php echo $panelId; ?>">
            <input type="hidden" name="templateid" id="templateid" value="<?php echo $templateid; ?>">
            <textarea name="panelhtml" id="panelhtml" rows="20" cols="110">
            <?php //echo $temThemeCss;?>
            <?php echo trim($panelHtml);?></textarea>
            <div class="popupeditpanel_ftr">
                <input type="button" name="btnHtmlPanelUpdate" id="btnHtmlPanelUpdate" value="Update" class="popup_orngbtn right">
                <input name="btnPanelCancel" id="btnPanelCancel" type="button" value="Cancel" class="popup_greybtn right">
                <!--<br>Set this panel as default <input type="checkbox" value="1" name="commonpanel" id="commonpanel">-->
            </div>

        </div>
    </form>
    <form name="frmPlainPanelUpdator" id="frmPlainPanelUpdator">
        <div id="editor_plaineditor" >
            <input type="hidden" name="panelid" id="panelid" value="<?php echo $panelId; ?>">
            <input type="hidden" name="templateid" id="templateid" value="<?php echo $templateid; ?>">
            <textarea id="editor1" name="panelContent"><?php echo trim($panelHtml);?></textarea>

            <div class="popupeditpanel_ftr">
                <input type="button" name="btnPlainPanelUpdate" id="btnPlainPanelUpdate" value="<?php echo EDITOR_UPDATE;?>" class="popup_orngbtn right">
                <input name="btnPanelCancel" id="btnPanelCancel" type="button" value="<?php echo CANCEL;?>" class="popup_greybtn right">
                <!--<br>Set this panel as default <input type="checkbox" value="1" name="commonpanel" id="commonpanel">-->
            </div>


        </div>
    </form>

</div>
    <?php
}
else
    echo "Some unexpected error occurred";
?>




