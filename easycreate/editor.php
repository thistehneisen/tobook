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
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

include "includes/editorheader.php";
include "includes/sitefunctions.php";
include "editor/editor_addwidgetcontent.php";
include "includes/cls_htmlform.php";

echo '<script>';
foreach($uservalidation as $key=>$val){
    echo 'var '.$key.'="'.$val.'";';
}
echo '</script>';
//echopre($_SESSION['siteDetails']);
if($_SESSION["session_loginname"] == "") {
    header("location:login.php");
    exit;
}

//echopre1($_SESSION['siteDetails']['pages']);

?>

<?php 
if(isset($_REQUEST['action']))
    $action  = $_REQUEST['action'];

list($templateid,$templateThemeId)=explode('_',$_POST['chekSelTemplate']);

$templateid		= $_SESSION['siteDetails']['siteInfo']['templateid'];
$templateThemeId 	= $_SESSION['siteDetails']['siteInfo']['themeid']; 

 //echopre($_SESSION['siteDetails']);
 
if($templateid=='') {
	 echo "redirecting..";
	 echo '<script>window.location="sitemanager.php";</script>';
     header('Location:sitemanager.php');
    exit();
}
 
//$_SESSION["session_template_dir"] = 'f7979482a14a7aaec0ca7106a98085ea';
$templatePath 		= $_SESSION["session_template_dir"] . "/" . $templateid;

// code to get the template details
$templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/index_temp.htm'; 
$fh 		= fopen($templateFile, 'r');
$theData 	= fread($fh, filesize($templateFile));
$theData 	= str_replace('BASE_URL_REPLACEMENT','./'.$templatePath, $theData );

fclose($fh);

$doc = new DOMDocument();
 
if(isset($_GET['page']))
    $_SESSION['siteDetails']['currentpage'] = $_GET['page'];
if(isset($_SESSION['siteDetails']['currentpage']))
    $currentPage 	=   'index';
else {
    $currentPage = 'index';

    $arrNewPage 			= array();
    $arrNewPage['title'] 		= 'Home Page';
    $arrNewPage['link'] 		= $currentPage.'.html';
    $arrNewPage['alias'] 		= $currentPage;
    $arrNewPage['pagetype'] 	= 1;

    $rowNo = time();
    $_SESSION['siteDetails']['pages'][$rowNo] = $arrNewPage;
    $_SESSION['siteDetails']['currentpage'] = $currentPage;
}

//echopre($_SESSION['siteDetails']['index']['panelpositions']);

$_SESSION['siteDetails']['templateid'] 	= $templateid;
$_SESSION['siteDetails']['themeid'] 	= $templateThemeId;

if($_SESSION['siteDetails']['templateid']=='') {
    header('Location:index.php');
}
$pageType = 2;
if($currentPage =='index') $pageType = 1;
else {
	$pageType = getPageType($templateid,$currentPage);
	if($pageType == '' || $pageType == 2)  
		$pageType = 2;
}
// Get banner
$createdSiteBanner     = getSettingsValue('enable_created_site_banner');
$createdSiteBannerName = getSettingsValue('created_site_banner_name');
$createdSiteBannerLink = getSettingsValue('created_site_banner_link');

// code to check whether its a guest book
$sitePages = $_SESSION['siteDetails']['pages'];
foreach($sitePages as $key=>$pages) {
    if($pages['pagetype'] == 'guestbook' && $pages['alias'] == $currentPage)	$curPageType = 'guestbook';
}

//unset($_SESSION['siteDetails']);
if($_SESSION['siteDetails'][$currentPage]['panels']=='') { 
 
    // select the panels of the template
    $sql 	= "Select * from tbl_template_panel where temp_id=".$templateid." AND page_type=".$pageType." ORDER BY panel_id ASC";

    $result = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_assoc($result)) { 
        	 
            if($_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] == '') {

                $panelData = $row['panel_html'];
 
                $panelData = str_replace('<h4>Keep in touch</h4>','<h3>Keep in touch</h3>',$panelData);
                // code to add edit menu for image
                //@$doc->loadHTML($panelData);
                @$doc->loadHTML(mb_convert_encoding($panelData, 'HTML-ENTITIES', 'UTF-8'));
                $tags = $doc->getElementsByTagName('img');
                $imgNo = 1;
                foreach ($tags as $tag) {

                    $imgSource = $tag->getAttribute('src');
                    $newImgPathWithTemp = BASE_URL.$templatePath.'/'.$imgSource;
                    $tag->setAttribute("src", $newImgPathWithTemp);

                    if($tag->getAttribute('data-edit') == 'true') {
                        $tag->setAttribute("class", "editablefile");
                        $imgId = $row['panel_id'].'_'.$imgNo;
                        $tag->setAttribute("id", "editablefile_".$imgId);

                        if($tag->getAttribute('data-type') == 'logo') {
                            $defaultLogo = $tag->getAttribute('src');  //echopre($defaultLogo);
                            $imageParams = @getimagesize($defaultLogo);  //echopre1($imageParams);
                            $width = ($imageParams[0])?$imageParams[0]:"100"; 
                            $height = ($imageParams[1])?$imageParams[1]:"100"; 
                            if($_SESSION['siteDetails']['siteInfo']['logoName']!='') {
                                $tag->setAttribute("src", $_SESSION['siteDetails']['siteInfo']['logoName']);
                                $tag->setAttribute("width", $width);
                                $tag->setAttribute("height", $height);
                            }
                        }
                        $imgNo++;
                    }
                }

                // Add/Replace company name from customize site page
                if($_SESSION['siteDetails']['siteInfo']['companyname']!='') {
                    applySiteCompany($_SESSION['siteDetails']['siteInfo']['companyname'],$_SESSION['siteDetails']['siteInfo']['compfont'],$_SESSION['siteDetails']['siteInfo']['fntclr'],$_SESSION['siteDetails']['siteInfo']['fontsize']);
                }

                // Add/Replace company name from customize site apge
                if($_SESSION['siteDetails']['siteInfo']['captionname']!='') {
                    applySiteCaption($_SESSION['siteDetails']['siteInfo']['captionname'],$_SESSION['siteDetails']['siteInfo']['captionfont'],$_SESSION['siteDetails']['siteInfo']['captfntclr'],$_SESSION['siteDetails']['siteInfo']['captionfontsize']);
                }

                // Add banner
                if($createdSiteBanner && $createdSiteBannerName!=''){
                    applySiteBanner($createdSiteBannerName,$createdSiteBannerLink);
                }
                

                // find the menus in the html
                $menuNo = 1;
                $menus 	= $doc->getElementsByTagName('ul'); 
                foreach ($menus as $menu) {
                    if($menu->getAttribute('data-type') == 'menu') {
                        $menuId 	= 'editablemenu_'.$row['panel_id'].'_'.$menuNo;
                        $menuItems 	= $menu->getElementsByTagName('li');
                        $arrMenuItems = array();
                        $templatePages = array();
                        $i =1;
                        foreach($menuItems as $items) {

                            $liTitle 		=  $items->nodeValue; 

                            $menuSubItems 	= $items->getElementsByTagName('a');
                            foreach($menuSubItems as $subItems){
                                $menuAlias  = $subItems->getAttribute('data-alias'); 
                            }
                           
                            // If set language from Admin
                            if($siteLanguageOption =="english"){
                                if($i == 1)
                                    $newpage = 'index';
                                else {
                                    $liTitle1 = str_replace(' ', '', $liTitle);
                                    $newpage = getAlias($liTitle1);

                                    $templatePages[]	= array('title' => $liTitle ,'alias' => $newpage ,'link' => $newpage.'.html');
                                }

                                $arrMenuItems[]	= array('title' => $liTitle ,'link' => $newpage.'.html','linkmode' => 'internal');

                            }else{ 
                                $newpageData = getSiteTemplateTopMenuPageAlias($templateid,$menuAlias); 
                                $newpage     = $newpageData['page_name'];
                                $templatePages[]	= array('title' => $liTitle ,'alias' => $newpage ,'link' => $newpage);

                                $arrMenuItems[]	= array('title' => $liTitle ,'link' => $newpage,'linkmode' => 'internal');
                            }
                            
                            $i++;
                        }
                        //exit;
                        $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'] = $arrMenuItems;
                        $menu->setAttribute("class", "editablemenu");
                        $menu->setAttribute("id", $menuId);
                        $menuNo++;
                        $f = $doc->createDocumentFragment();
                        $f->appendXML('<div class="manageMenuStyle"><a href="#" data-param="'.$menuId.'" class="jqeditormenusettings">'.EDIT_MENU_TITLE.'</a></div>');
                        $menu->appendChild($f);
                    }
                }
                // editable menu section ends
                
                
                
                // find the datatypes in the template
                $sliderNo='1';
                $divList 	= $doc->getElementsByTagName('div');
                foreach ($divList as $divs) {
                    if($divs->getAttribute('data-type') == 'slider') {	// slider checking
                    	$sliderId 	= 'editableslider_'.$row['panel_id'].'_'.$sliderNo;
                        $divs->setAttribute("class", "editableslider");
                        $divs->setAttribute("id", $sliderId);
                        $sliderNo++;                
                      	$tags = $doc->getElementsByTagName('img');
		                $imgNo = 1;
		                foreach ($tags as $tag) {
		                    if($tag->getAttribute('data-edit') == 'true') {
		                        $tag->setAttribute("class", "editableslider");
		                        $imgId = $row['panel_id'].'_'.$imgNo;
		                        $tag->setAttribute("id", "editablesliderimage_".$imgId);
		                        $tag->setAttribute("data-param", $sliderId);
		                        $imgNo++;
		                        $imageUrl = $tag->getAttribute('src');
		                        $arrImgDet = array('image' => $imageUrl, 'title' => 'Image Title');
		                        list($imgWidth, $imgHeight) = getimagesize($imageUrl);
		                        
		                        //$imgWidth = $tag->getAttribute('width');
		                       // $imgHeight = $tag->getAttribute('height');
		                        
		                    }
		                }
		                $arrSettings 	= array('height' => $imgHeight,'width' => $imgWidth,'delay'=> '2000');
						$_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderId]['settings']	= $arrSettings;
						$imgrow 		= time();
					 	$_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderId]['images'][$imgrow] = $arrImgDet;
                        //$f = $doc->createDocumentFragment();
                      //  $f->appendXML('<a href="#" data-param="'.$sliderId.'" class="jqeditorslidersettings">Edit Slider</a>');
                        //$divs->appendChild($f);
                    }	// image slider ends
                    else if($divs->getAttribute('data-type') == 'socialshare'){		// socilshare  checking section
 
                    	$shareId 	= 'editableshare_'.$row['panel_id'].'_'.$sliderNo;
                        $divs->setAttribute("id", $shareId);
                        $sliderNo++;                
                      	$tags = $doc->getElementsByTagName('img');
		                $imgNo = 1;
  		                foreach ($tags as $tag) {
                                    $tag->setAttribute("class", "editableshare");
		                    $imgId = $row['panel_id'].'_'.$imgNo;
		                    $tag->setAttribute("id", "editableshare_".$imgId);
		                    $tag->setAttribute("data-param", $shareId);
		                    $imgNo++;
		                    $imageUrl = $tag->getAttribute('src');
		                    $arrShareDet[] = array('image' => $imageUrl, 'link' => '#');
		                }
		                $_SESSION['siteDetails'][$currentPage]['datatypes']['socialshare'][$shareId]	= $arrShareDet;  
                                /******* Modified for common footer in all pages ***/
                                $_SESSION['siteDetails']['commonpanel']['socialshare'] = $arrShareDet;  
                                /******* Modified for common footer in all pages ***/
                    }	// social share ends
                    
                }
                
                $panelData = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', @$doc->saveHTML());
                

                $_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] = $panelData;
                $_SESSION['siteDetails'][$currentPage]['panelpositions'][$row['panel_id']] = array($row['panel_id']);
            }

            $panelContent = $_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']];

            //$editLink 	= '<span style="text-align:right;float:right;"> [<a name="600" id="editor/edittemplate.php?panelid='.$row['panel_id'].'&templateid='.$templateid.'" class="modal" href="javascript:void(0);" title="Click to edit the tempplate" >edit</a>]</span>';
            $deleteLink = ' <a title="'.EDITOR_PANEL.'" href="" data-params="'.$row['panel_id'].'" class="jqdeleteextapp panel_delete">  </a>';
            $editLink 	= '<span class="panel_controls" title="Click to edit the template"> <a class="iframe panel_edit" title="'.EDITOR_PANEL.'"  href="editor/edittemplate.php?panelid='.$row['panel_id'].'&templateid='.$templateid.'"  ></a>'.$deleteLink.'</span>';
            $replacer 	= '{$editable'.$row['panel_type'].'}';

            $editContent = '<div class="column" id="column_'.$row['panel_id'].'">
                                    <div class="dragbox" id="item_'.$row['panel_id'].'" >
                                    <h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span></h4>
                                    <div class="dragbox-content" id="editpanel_'.$row['panel_id'].'" >'.$panelContent.'</div></div>
                                    </div>';
            $theData 	= str_replace($replacer,$editContent,$theData);
            $theData = str_replace("&nbsp;", "", $theData);

        }

    }
    // call the function to generate the pages that in the menu
 
  
    
    
// get the other pages of the temaplte

$sitePages = getTemplatePages($templateid);
  
generatePages($sitePages,$type = 'mainpages');
generatePages($templatePages,$type = 'subpages');
   
    
}else { // load the template details from session
 
    foreach($_SESSION['siteDetails'][$currentPage]['panelpositions'] as $key=>$positions) { //echopre($key);

        // get the panel details
        if(is_numeric($key)) {
            $panelDet = mysql_query('select panel_type from tbl_template_panel where page_type='.$pageType.' AND panel_id='.$key) or die(mysql_error());
            if(mysql_num_rows($panelDet) > 0) {
                $row 		= mysql_fetch_assoc($panelDet);
                $replacer 	= '{$editable'.$row['panel_type'].'}';
                $editContent = ''; 
                foreach($positions as $panelPos) {  
                   
                    $panelContent = $_SESSION['siteDetails'][$currentPage]['panels'][$panelPos];
                    
                    if($_SESSION['siteDetails']['siteInfo']['logoName']!='' || $_SESSION['siteDetails']['siteInfo']['companyname']!='' || $_SESSION['siteDetails']['siteInfo']['captionname']!='') {
                        @$doc->loadHTML(mb_convert_encoding($panelContent, 'HTML-ENTITIES', 'UTF-8'));
                    }

                    // Replacing logo if uploaded from customize site page
                    if($_SESSION['siteDetails']['siteInfo']['logoName']!='') {
                        applySiteLogo($_SESSION['siteDetails']['siteInfo']['logoName']);
                    }

                    // Add/Replace company name from customize site page
                    if($_SESSION['siteDetails']['siteInfo']['companyname']!='') {
                        applySiteCompany($_SESSION['siteDetails']['siteInfo']['companyname'],$_SESSION['siteDetails']['siteInfo']['compfont'],$_SESSION['siteDetails']['siteInfo']['fntclr'],$_SESSION['siteDetails']['siteInfo']['fontsize']);
                    }

                    // Add/Replace company name from customize site page
                    if($_SESSION['siteDetails']['siteInfo']['captionname']!='') {
                        applySiteCaption($_SESSION['siteDetails']['siteInfo']['captionname'],$_SESSION['siteDetails']['siteInfo']['captionfont'],$_SESSION['siteDetails']['siteInfo']['captfntclr'],$_SESSION['siteDetails']['siteInfo']['captionfontsize']);
                    }

                    // Add banner
                    if($createdSiteBanner && $createdSiteBannerName!=''){
                        applySiteBanner($createdSiteBannerName,$createdSiteBannerLink);
                    }

                    if($_SESSION['siteDetails']['siteInfo']['logoName']!='' || $_SESSION['siteDetails']['siteInfo']['companyname']!='' || $_SESSION['siteDetails']['siteInfo']['captionname']!='') {
                        $panelContent =  preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
                        $_SESSION['siteDetails'][$currentPage]['panels'][$panelPos] = $panelContent;
                    }


                    // code to find the menu items
                    @$doc->loadHTML(mb_convert_encoding($panelContent, 'HTML-ENTITIES', 'UTF-8'));

                    $menus 	= $doc->getElementsByTagName('ul');
                    foreach ($menus as $menu) {
                        if($menu->getAttribute('data-type') == 'menu') {
                            $menuid = $menu->getAttribute('id');
                            $menuDet = $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuid]['items'];
                            $menuValue = '<ul id="'.$menuid.'" class="editablemenu" data-type="menu">';
                            if($menuDet) {
                                foreach($menuDet as $items) { 
                                    $menuValue .= '<li><a href="'.$items['link'].'" linkmode="'.$items['linkmode'].'">'.$items['title'].'</a></li>';
                                }
                            }
                            $menuValue .= '<div class="manageMenuStyle"><a class="jqeditormenusettings" data-param="'.$menuid.'" href="#">'.EDIT_MENU_TITLE.'</a></div></ul> ';
                            $pattern = '/<ul (.*?)id="'.$menuid.'"[^:]*<\/ul>/';
                            $panelContent = preg_replace($pattern,$menuValue, $panelContent);
                        }
                    }
                    // menu check code ends

                    // external box checking starts
                    $panelBox = explode('_',$panelPos); //echopre($panelPos);
                   
                    if($panelBox[0] == 'exterbox') {
                       
                        if ($panelBox[1] == 'htmlbox') {			 // widget external html box
                            $deleteLink 	= '<a href="#" onclick="loadHtmlboxEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"></a>';
                            $editLink 		= '<span class="panel_controls">'.$deleteLink.' <a title="Delete widget" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"></a></span>';
                            $formContent 	= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos];
                            $widgetHtmlTitle 	= 'A sample html content, click edit to enter your own text.';
                            if($formContent){
                                $widgetHtmlTitle = "";
                            }
                            
                            $editContent 	.= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span></h4>
							<div class="dragbox-content editableform widgetTitleStyle" id="editpanel_'.$panelPos.'"  data-param="'.$panelPos.'">'.$widgetHtmlTitle.' '.$formContent.'</div></div>
							</div> ';
                        } 
                        elseif ($panelBox[1] == 'form') { // feedback form
                            $deleteLink = '<a href="#" onclick="loadFormEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"></a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="'.EDITOR_PANEL.'" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete">   </a></span>';
                            
                           //  $widgetTitle	= 'Feedback widget';
                            $objForm 		= new Htmlform();
                            $formDetails 	= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos];
                            if(sizeof($formDetails) > 0) {
                            	global $formFeedBackItems; 
                                $feedBackItems =  $formDetails['items']; 
                                foreach($feedBackItems as $key=>$items) {
                                    $itemDet = $formFeedBackItems[$items]; 
                                    $objFormElement		= new Formelement();
                                    $objFormElement->type	= $itemDet['field'];
                                    $objFormElement->name       = $items;
                                    $objFormElement->id         = $items;
                                    $objFormElement->label      = $itemDet['title'];
                                    $objForm->addElement($objFormElement);
                                }
                            }
                            $formOutPut		= $objForm->renderForm(); 
                            $formContent 	= $formOutPut;
                            
                            $editContent .= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span>'.$widgetTitle.'</h4>
							<div class="dragbox-content editableform" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$formContent.'</div></div> 
							</div> ';
                        }
                        // Slider
                        elseif ($panelBox[1] == 'slider') {

                            $deleteLink = '<a href="#" onclick="loadSliderEditor(\'editablebox_'.$panelBox[2].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"></a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="Edit widget" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"></a></span>';
                            $widgetContent  =  showSlider($panelPos,$currentPage);
 
                            $widgetSliderTitle 	= 'A sample image slider, click edit to modify the image gallery.';
                            //$widgetTitle	= 'Image Slideshow';

                            $editContent .= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span>'.$widgetTitle.'</h4>
							<div class="dragbox-content editableform widgetTitleStyle" id="editpanel_'.$panelPos.'"  data-param="'.$panelPos.'"><span style="display:block;padding-bottom:8px;">'.$widgetSliderTitle.' </span>'.$widgetContent.'</div></div>
							</div> ';

                        }
                            // Google Adsense
                           elseif ($panelBox[1] == 'googleadsense') { 

                            $deleteLink = '<a href="#" onclick="loadGoogleAdsenseEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"></a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="Edit widget" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"></a></span>';

                            $widgetContent 	= 'Google Adsense, click edit to update the form fields';
                           // $widgetTitle	= 'Google Adsense';

                            $editContent .= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span>'.$widgetTitle.'</h4>
							<div class="dragbox-content editableform widgetTitleStyle" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$widgetContent.'</div></div>
							</div> ';
                            //$editLink = $widgetTitle.'<span class="configure"><span title="Click to edit the widget"><a href="#" onclick="loadGoogleAdsenseEditor(\'editablebox_'.$itemVal.'\',1,\''.$dragitem.'\')" title="Edit widget" class="iframe panel_edit">Edit</a> <a class="jqdeleteextapp panel_delete" data-params="'.$dragitem.'" href="#"> X </a></span></span>';
                            //echo $editLink.'~<div id="editablebox_'.$itemVal.'" class="editableform" data-param="'.$dragitem.'">'.$widgetContent.'</div>';
                        }
                        // Dynamic Form
                        elseif ($panelBox[1] == 'dynamicform') {
                            $deleteLink = '<a href="#" onclick="loadCustomFormEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"> </a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="'.EDITOR_PANEL.'" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete">  </a></span>';
                            $formContent = 'A sample custom form, click edit to update the form fields';
                            $editContent .= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span></h4>
							<div class="dragbox-content editableform widgetTitleStyle" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$formContent.'</div></div>
							</div> ';
                        }
                        else if($panelBox[1] == 'socialshare') {		// social share details
                        	$appDetails = $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos];
                            if(sizeof($appDetails) > 0) {
                            	$socialShare = '';
                                foreach($appDetails as $key=>$details) {
	                                if($key == 'twitlink')			$socialShare .= '<a href="'.$details.'"><img src="editor/images/icon_twitter.png" class="socialShareClass"></a>';
	                                else if($key == 'fppage')   		$socialShare .= '<a href="'.$details.'"><img src="editor/images/icon_facebook.png" class="socialShareClass"></a>';
	                                else if($key == 'linkedinlink') 	$socialShare .= '<a href="'.$details.'"><img src="editor/images/icon_linkedin.png" class="socialShareClass"></a>';
                                }
                           	}
                            else 	$socialShare = '<img src="editor/images/icon_facebook.png" class="socialShareClass"> <img src="editor/images/icon_twitter.png" class="socialShareClass"> <img src="editor/images/icon_linkedin.png" class="socialShareClass">';

                            $deleteLink = '<a href="#" onclick="loadBoxEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"></a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="'.EDITOR_PANEL.'" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"> </a></span>';
                            $formContent = $socialShare;
                           // $socialShareTitle = 'Social share';
                            $editContent .= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span>'.$socialShareTitle.'</h4>
							<div class="dragbox-content editableform" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$formContent.'</div></div>
							</div> ';
                            //$editContent .=  $socialShare;
                        }
                        else if($panelBox[1] == 'navmenu') {
                        	 
                        	$menuClass 	= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['settings']['menutype'];
                        	$menuList 	= '';
                                $appDetails     = $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['items'];
                            if(sizeof($appDetails) > 0) {
                            	$menuList .= '<ul class="'.$menuClass.'">';
                                foreach($appDetails as $key=>$details) 
                                	$menuList .= '<li><a href="javascript:void(0)" >'.  $details['title'].'</a></li>';
                                $menuList .= '</ul>';
                            }
                            //$editContent .= $menuList;
                        	
                            
                            
                            $deleteLink = '<a href="#" onclick="loadMenuEditor(\'editablebox_'.$panelBox[2].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"></a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="'.EDITOR_PANEL.'" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"> </a></span>';
                            $formContent = $menuList;
                            $widgetTitle 	= 'A sample navigation menu, click edit to update the menu items';
                            if($formContent){
                                $widgetTitle 	= '';
                            }
                            
                            $editContent .= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span></h4>
							<div class="dragbox-content editableform widgetTitleStyle" id="editablebox_'.$panelBox[2].'"  data-param="'.$panelPos.'">'.$widgetTitle.' '.$formContent.'</div></div>
							</div> ';
                            
                            
                            
                        }

                    }
                    else { // these are exising panel items

                        // check for guest book
                        if($curPageType == 'guestbook' && $row['panel_type'] == 'maincontent') { 

                            $newContent = 'Guest Book';
                            $editLink = '';
                            //$editLink 	= '<a href="#" onclick="loadGuestBookEditor(\'editablebox_'.$panelId.'\',1,\''.$panelId.'\')" title="Edit widget" class="  panel_edit">Edit</a>';
                            $deleteLink = '<a href="" data-params="'.$panelId.'" class="jqdeleteextapp panel_delete"> X </a>';
                            $panelLink 	= '<span class="panel_controls" > '.$editLink.$deleteLink.' </span>';

                            $editContent .= '<div class="column" id="column_'.$panelPos.'">
	                                            <div class="dragbox" id="item_'.$panelPos.'" >
	                                            <h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span></h4>
	                                            <div class="dragbox-content" id="editpanel_'.$panelPos.'" >'.$newContent.'</div></div>
	                                            </div>';

                        }
                        else {   


                            $deleteLink 	= '<a title="Delete widget" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete">  </a> ';

                            $editLink 	= '<span class="panel_controls" title="Click to edit the template"> <a class="iframe panel_edit" title="'.EDITOR_PANEL.'"  href="editor/edittemplate.php?panelid='.$panelPos.'&templateid='.$templateid.'"  ></a>'.$deleteLink.'</span>';

                            $editContent .= '<div class="column" id="column_'.$panelPos.'" >
	                                            <div class="dragbox" id="item_'.$panelPos.'" >
	                                            <h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span> </h4>
	                                            <div class="dragbox-content" id="editpanel_'.$panelPos.'"  >'.$panelContent.'</div></div>
	                                            </div>';
                        }
                    }
                    //echopre($editContent);
                }

                $theData 	= str_replace($replacer,$editContent,$theData);
                $theData = str_replace("&nbsp;", "", $theData);
                $theData = iconv(mb_detect_encoding($theData, mb_detect_order(), true), "UTF-8", $theData);
            }

        }

    }

}



// add the theme style to the template
$theData = changeSiteStyle($templateid,$templateThemeId,$theData); 


//Apply Site color/Page title/Meta Description/Meta Keywords
if($_SESSION['siteDetails']['siteInfo']['stclr']!=='' || $_SESSION['siteDetails']['siteInfo']['sitetitle']!=='' || $_SESSION['siteDetails']['siteInfo']['sitemetadesc']!=='' || $_SESSION['siteDetails']['siteInfo']['sitemetakey']!=='' || $_SESSION['siteDetails']['siteInfo']['companyname']!=='' || $_SESSION['siteDetails']['siteInfo']['captionname']!=='') {
    
    @$doc->loadHTML(mb_convert_encoding($theData, 'HTML-ENTITIES', 'UTF-8'));


    // Add/Replace company name from customize site page
    if($_SESSION['siteDetails']['siteInfo']['companyname']!='') {
        applySiteCompany($_SESSION['siteDetails']['siteInfo']['companyname'],$_SESSION['siteDetails']['siteInfo']['compfont'],$_SESSION['siteDetails']['siteInfo']['fntclr'],$_SESSION['siteDetails']['siteInfo']['fontsize']);
    }

    // Add/Replace company name from customize site apge
    if($_SESSION['siteDetails']['siteInfo']['captionname']!='') {
        applySiteCaption($_SESSION['siteDetails']['siteInfo']['captionname'],$_SESSION['siteDetails']['siteInfo']['captionfont'],$_SESSION['siteDetails']['siteInfo']['captfntclr'],$_SESSION['siteDetails']['siteInfo']['captionfontsize']);
    }

    // Add banner
    if($createdSiteBanner && $createdSiteBannerName!=''){
        applySiteBanner($createdSiteBannerName,$createdSiteBannerLink);
    }
                
    if($_SESSION['siteDetails']['siteInfo']['stclr']!=='') {
        // Site Color
        applySiteColor($_SESSION['siteDetails']['siteInfo']['stclr']);
    }
    if($_SESSION['siteDetails']['siteInfo']['sitetitle']!=='') {
        // Site Page Title
        applySitePageTitle($_SESSION['siteDetails']['siteInfo']['sitetitle']);
    }
    if($_SESSION['siteDetails']['siteInfo']['sitemetadesc']!=='' || $_SESSION['siteDetails']['siteInfo']['sitemetakey']!=='') {
        // Site Meta Desc/ Meta Keywords
        applySiteMetaData($_SESSION['siteDetails']['siteInfo']['sitemetakey'],$_SESSION['siteDetails']['siteInfo']['sitemetadesc']);
    } 
    $theData = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
} 

// Remove not replcaeble variable
$theData = removeNotReplacableContent($theData);

//------------Joomla Template changes------------
$templateResult = mysql_query('select ntemplate_type from  tbl_template_mast where  ntemplate_mast='.$templateid) or die(mysql_error());
if(mysql_num_rows($templateResult) > 0) {
    $rowTemplate        = mysql_fetch_assoc($templateResult);
    $templateType	= $rowTemplate['ntemplate_type'];
    if($templateType =='J') {
        $templateFiles = mysql_query('select vjoomla_template_file  from  tbl_joomla_template_files where  ntemplate_mast_id='.$templateid) or die(mysql_error());
        if(mysql_num_rows($templateFiles) > 0) {
            while($row = mysql_fetch_assoc($templateFiles)) {
                $themeStyleUrl 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/css/'.$row['vjoomla_template_file'];
                //   $theData 		= str_replace('css/'.$row['vjoomla_template_file'],$themeStyleUrl,$theData);
            }
        }
    }
}
//--------------End of Joomla Template changes------------

?> 

<!-- Default editor styles  -->

<link rel="stylesheet" href="style/colorbox.css" />
<link rel="stylesheet" href="style/editor_styles.css" type="text/css">
<script language="javascript" src="js/jquery.js"></script>
<!--Fix for contact us form edit -->
<style>
    .contact_form h4{
	color:#9b9b9b!important;
	font-family:"open Sans Condensed", arial!important;
	font-size:26px!important;
	font-weight:normal!important;
	float:left!important;
	margin-bottom:10px!important;
	width:320px!important;
        position: static!important;
}
</style>
<!--Fix for contact us form edit -->
<!-- Modal Popup scripts  -->
<script language="javascript" src="js/modal.popup.js"></script>
<!--  Modal Popup scripts  -->


<!--  Drag Drop scripts  -->
<script type="text/javascript" src="js/jquery-1.3.2.js" ></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js" ></script>
<link rel="stylesheet" type="text/css" href="style/dragdropstyles.css" />
<script type="text/javascript" >
    $(function(){
        $('.dragbox')
        .each(function(){
            $(this).hover(function(){
                $(this).find('h4').addClass('collapse');
            }, function(){
                $(this).find('h4').removeClass('collapse');
            })

            .click(function(){
                $(this).siblings('.dragbox-content').toggle();
            })
            .end()
            .find('.configure').css('visibility', 'hidden');
        });
        $('.column').sortable({
            connectWith: '.column',
            handle: 'h4',
            cursor: 'move',
            placeholder: 'placeholder',
            forcePlaceholderSize: true,
            opacity: 0.4,
            stop: function(event, ui){

                //  : code to check the external apps are called or not

                var dragcol=$(this).attr('id');


                //alert(dragcol);


                var entries = dragcol.split(/_/);
                if(entries[0] == 'externalapp'){


                    var selitem = this;

                    // code to load the content to drag items
                    var dragitem=$(this).attr('data-attr');
                    paramvalue1 = "newitem="+dragcol+"&dragitem="+dragitem;
                    $.ajax({url: "editor/editor_assignexternalappcontents.php",
                        type: "POST",data: paramvalue1 ,cache: false,dataType:'html',
                        success: function(html) {
                            //alert(html);

                            //$("#"+dragcol).html(html);
                            var entries = html.split(/~/);
                            $('#head'+dragitem).html(entries[0]);
                            $('#'+dragitem).append(entries[1]);

                        }
                    });

                    //$('.editable'+entries[1]).trigger('click'); // Commented since dragging slider widget triggers the popup which add gallery images to slider session not to app session

                    // code to generate new apps
                    paramvalue = "newitem="+dragcol;
                    // alert(paramvalue);
                    var newdataattr ='';
                    $.ajax({url: "editor/editor_externalappadding.php",
                        type: "POST",data: paramvalue ,cache: false,dataType:'html',
                        success: function(html) {
                            // var dragitem=$(selitem).attr('data-attr');
                            // alert(dragitem);
                            var entries = html.split(/~/);
                            newdataattr = entries[0];
                            $(selitem).attr('data-attr',newdataattr);
                            $("#"+dragcol).html(entries[1]);
                        }
                    });
                    // $('#'+dragitem).append(' <div class="editableform" data-param="exterbox_form_1" id="editablebox_5"> [click here to edit]</div>');

                }
                // external app check ends here
                $(ui.item).find('h4').click();
                var sortorder='';

                $('.column').each(function(){
                    var itemorder=$(this).sortable('toArray');
                    var columnId=$(this).attr('id');
                    sortorder+=columnId+'='+itemorder.toString()+'&';
                });

                sortorder+= 'templateid=<?php echo $templateid;?>&themeid=<?php echo $templateThemeId; ?>';
                //alert(sortorder);
                $("#info").load("editor/editor_panel_sorter.php?"+sortorder);
                /*Pass sortorder variable to server using ajax to save state*/
            }
        })
        .disableSelection();
    });
</script>


<!--  Drag drop scripts  -->

<!-- Inline editor -->
<!--script src="<?php // echo BASE_URL ?>apps/ckeditor/ckeditor.js"></script>
<script type="text/javascript" >
    CKEDITOR.on( 'instanceCreated', function( event ) {
        var editor = event.editor,
        element = editor.element;

         editor.on('blur', function(){ //alert('hiii');
             //var ck_content =  CKEDITOR.instances['content'].getData(); alert(ck_content);
         });
    });
</script-->
<!-- Inline editor -->



<!--  UI dialogu scripts  -->
<!--link rel="stylesheet" href="http://code.jquery.com/ui/1.10.0/themes/base/jquery-ui.css" /-->
<link rel="stylesheet" href="style/jquery-ui.css" />


<script src="js/jquery-1.8.3.js"></script>
<script src="js/jquery-ui.js"></script>
<!--  UI dialogu scripts  -->

<script language="javascript" src="js/validations.js"></script>
<!--  Context menu scripts  -->
<link rel="stylesheet" href="style/editor-contextmenu.css" type="text/css">
<script src="js/editor-contextmenu.js" type="text/javascript"></script>
<!--  Context menu scripts  --> 

<script src="js/common.js"></script>
<link rel="stylesheet" href="style/common.css" />


<script src="js/dialoges.js"></script>
<script type="text/javascript" src="js/jquery.colorbox.js"></script>
<div id="opendialogbox"></div>


<div id="info"></div>


<table width="100%"  border="0" cellspacing="0" cellpadding="0" align="center">
    <tr>
        <td  valign="top" align=center>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td>
                        <div id="jqsiteeditor"><?php echo $theData;?></div>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

<script>
    $(document).ready(function(){

        // To disable the Form button actions
        $(".submitButton").live('click',(function() {
            return false;
        }));
    	  
        $(".iframe").colorbox({iframe:true, width:"70%", height:"600px"});
        $(".sitepreview").colorbox({iframe:true, width:"90%", height:"90%"});
        $('.editablefile').trigger('click');
        $('.editablebox').trigger('click');
        $('.editablemenu a').live('click',(function() { 

            if($(this).attr('class') != 'jqeditormenusettings')
                return false;
        }));


        // $('#jqwidgetshow').live('click',(function(){
        $('#jqwidgetshow').toggle(function(){
         
            $('#jqwidgetbox').slideDown();
        }, function(){
            $('#jqwidgetbox').slideUp();
        });
        $('#jqclosewidgetbox').live('click',(function(){
            $('#jqwidgetbox').slideUp();
        }));

      	$('.editorloader').hide();
        var imageUrl = '';
    });


    $(function () {
        $("img").click(function() {
            $(this).addClass('editable_image_border_onclick');
        }); 	});
    $(document.body).click(function() { $("img").removeClass('editable_image_border_onclick'); });

</script>
<link href='http://fonts.googleapis.com/css?family=Dosis:300' rel='stylesheet' type='text/css'>
                    
<?php
include "includes/editorfooter.php";
//include "test/session.php";
$sliderArray 	= $_SESSION['siteDetails'][$currentPage]['datatypes']['slider'];

foreach ($sliderArray as $key=>$value){
    $sliderId = $key;
}
$galleryImage  = $_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderId]['images'];
 $i=0;
 
foreach($galleryImage as $key=>$images) {
    if($i==0){
        $_SESSION['BANNER_IMAGE_URL'] = $images['image'];
    }
                    $i++;
}

if(count($galleryImage)==0){
     $_SESSION['BANNER_IMAGE_URL'] = '';
}

?>
<script>
   imageUrl = '<?php echo $_SESSION['BANNER_IMAGE_URL']?>';
   
  
   var defaultSliderImage = $('div.editableslider').children('img').eq(0).attr('src');
   if(imageUrl != '')
     $('.editableslider').attr('src',imageUrl);
 
 
</script>


