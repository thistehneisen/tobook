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
include "includes/sitefunctions.php"; 
include "editor/editor_addwidgetcontent.php";
include "includes/cls_htmlform.php";

  
list($categoryid,$templateid,$templateThemeId)=explode('_',$_POST['chekSelTemplate']);
$templateid		= $_SESSION['siteDetails']['templateid'];
$templateThemeId 	= $_SESSION['siteDetails']['themeid'];

//$_SESSION["session_template_dir"] = 'f7979482a14a7aaec0ca7106a98085ea';
$templatePath 		= $_SESSION["session_template_dir"] . "/" . $templateid;

if(isset($_GET['page']))
    $_SESSION['siteDetails']['currentpage'] = $_GET['page'];


$currentPage  = $_SESSION['siteDetails']['currentpage'];

//echopre($_SESSION['siteDetails'][$currentPage]);

// code to get the template details
if($currentPage == 'index') {
    $templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/index_temp.htm';
    $pageType = 1;
}
else {
  
    $pageType = getPageType($templateid,$currentPage);
	if($pageType == '' || $pageType == 2) {
		$pageType = 2;
		  $templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/subpage_temp.htm';
		}
		else 
		  $templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/'.$currentPage.'_temp.htm';
	 
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


$fh 			= fopen($templateFile, 'r');
$theData 		= fread($fh, filesize($templateFile));
fclose($fh);

// unset($_SESSION['siteDetails']);
// echopre($_SESSION['siteDetails']);

// hack to to update the external app positions
if(isset($_SESSION['siteDetails'][$currentPage]['panelpositions'])) {
    foreach($_SESSION['siteDetails'][$currentPage]['panelpositions'] as $key=>$panelPositions) {
        if($key == 'exterbox') {
            $exterItems = array();
            foreach($panelPositions as $positions) {
                if(is_numeric($positions))
                    $addPosition = $positions;
                $exterItems[] = $positions;
            }
            $_SESSION['siteDetails'][$currentPage]['panelpositions'][$addPosition] = $exterItems;
        }
    }
}
// hack ends

$doc = new DOMDocument();

if(!isset($_SESSION['siteDetails'][$currentPage])) { 
	
    // select the panels of the template
    $sql 	= "Select * from tbl_template_panel where temp_id=".$templateid." AND page_type=".$pageType." ORDER BY panel_id ASC";
    $result = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($result) > 0) {
        while($row = mysql_fetch_assoc($result)) { 
            if($_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] == '') {
 
                $panelData = $row['panel_html'];
                $panelData = str_replace('<h4 >Keep in touch</h4>','<h3>Keep in touch</h3>',$panelData);
                //$panelData = preg_replace('#<h4(.*?)<\/h4>#si', '<h3${1}</h3>', $panelData);
                // code to add edit menu for image
                @$doc->loadHTML(mb_convert_encoding($panelData, 'HTML-ENTITIES', 'UTF-8'));
                $tags = $doc->getElementsByTagName('img');

                $imgNo = 1;
                foreach ($tags as $tag) {
                    if($tag->getAttribute('data-edit') == 'true') {
                        $tag->setAttribute("class", "editablefile");
                        $imgId = $row['panel_id'].'_'.$imgNo;
                        $tag->setAttribute("id", "editablefile_".$imgId);

                        $curSource = $tag->getAttribute('src');
                        $fielPath 	= $templatePath."/".$curSource;

                        if($_SESSION['siteDetails']['siteInfo']['logoName']!='') {
                            if($tag->getAttribute('data-type') == 'logo') {
                                $tag->setAttribute("src", $_SESSION['siteDetails']['siteInfo']['logoName']);
                            }else {
                                $tag->setAttribute("src", $fielPath);
                            }
                        }else {
                            $tag->setAttribute("src", $fielPath);
                        }
                        $imgNo++;
                    }
                }

                // Add/Replace company name from customize site apge
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
                $menuExist = 0;
                $menuNo = 1;
                $menus 	= $doc->getElementsByTagName('ul');
                foreach ($menus as $menu) {
                    if($menu->getAttribute('data-type') == 'menu') {
                        $menuId 	= 'editablemenu_'.$row['panel_id'].'_'.$menuNo;

                        // add index menu to the newly created pages
                        $curPanelId 		= $row['panel_id'];
                        $panelDet 			= mysql_query('select temp_id,panel_type from tbl_template_panel where panel_id='.$curPanelId) or die(mysql_error());
                        $rowPanel 			= mysql_fetch_assoc($panelDet);
                        $tempPanel 			= $rowPanel['panel_type'];
                        $tempId 			= $rowPanel['temp_id'];
                        $homePanelDet 		= mysql_query("SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type =1") or die(mysql_error());
                        $rowHomePanelDet 	= mysql_fetch_assoc($homePanelDet);
                        $homePanelId 		= $rowHomePanelDet['panel_id'];
                        $homePageMenuId 	= 'editablemenu_'.$homePanelId.'_'.$menuNo;
                        $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'] = $_SESSION['siteDetails']['index']['datatypes']['menu'][$homePageMenuId]['items'];
                        // ends the index menu adding section
						
                        $menu->setAttribute("class", "editablemenu");
                        $menu->setAttribute("id", $menuId);
                        $menuNo++;
                        $menuExist = 1;
                        if($pageType == 1) {
                        $f = $doc->createDocumentFragment();
                        $f->appendXML('<div class="manageMenuStyle"><a href="#" data-param="'.$menuId.'" class="jqeditormenusettings">'.EDIT_MENU_TITLE.'</a></div>');
                        $menu->appendChild($f);
						}
                    }
                }
                // editable menu section ends

                $panelData = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());

                $_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] = $panelData;
                $_SESSION['siteDetails'][$currentPage]['panelpositions'][$row['panel_id']] = array($row['panel_id']);
            }

            $panelContent = $_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']];
            if($curPageType == 'guestbook' && $row['panel_type'] == 'maincontent') {
                $replacer 	= '{$editable'.$row['panel_type'].'}';
                /*
                $editContent = '<div class="column" id="column_'.$row['panel_id'].'">
				<div class="dragbox" id="item_'.$row['panel_id'].'" >
				<h4><span class="configure" >'.$editLink.'</span>'.$row['panel_type'].':'.$row['panel_id'].'</h4>
				<div class="dragbox-content" id="editpanel_'.$row['panel_id'].'" >Guest Book</div></div> 
				</div>'; */
                $editContent = '<div class="column" id="column_'.$row['panel_id'].'">
				<div class="dragbox" id="item_'.$row['panel_id'].'" >
				<h4><span class="configure" >'.$editLink.'</span>'.'</h4>
				<div class="dragbox-content" id="editpanel_'.$row['panel_id'].'" >Guest Book</div></div>
				</div>';
                $theData 	= str_replace($replacer,$editContent,$theData);

            }
            else {
                //$editLink 	= '<span style="text-align:right;float:right;"> [<a name="600" id="editor/edittemplate.php?panelid='.$row['panel_id'].'&templateid='.$templateid.'" class="modal" href="javascript:void(0);" title="Click to edit the tempplate" >edit</a>]</span>';
                $editLink 	= '<span class="panel_controls" title="Click to edit the tempplate"> <a class="iframe panel_edit" title="Edit Panel Content" href="editor/edittemplate.php?panelid='.$row['panel_id'].'&templateid='.$templateid.'"  > </a><a href="" data-params="'.$row['panel_id'].'" class="jqdeleteextapp panel_delete"> </a></span>';
                $replacer 	= '{$editable'.$row['panel_type'].'}';

                $editContent = '<div class="column" id="column_'.$row['panel_id'].'">
				<div class="dragbox" id="item_'.$row['panel_id'].'" >
				<h4><span class="configure" >'.$editLink.'</span>'.'</h4>
				<div class="dragbox-content" id="editpanel_'.$row['panel_id'].'" >'.$panelContent.'</div></div> 
				</div>';
                $theData 	= str_replace($replacer,$editContent,$theData);
                $theData        = str_replace("&nbsp;", "", $theData);
                
            }
        }
    }
}
else { 
  
    //  echopre($_SESSION['siteDetails'][$currentPage]['panelpositions']);
    foreach($_SESSION['siteDetails'][$currentPage]['panelpositions'] as $key=>$positions) {

        // get the panel details
        if($key != '' && is_numeric($key)) {
           // echo 'select panel_type from tbl_template_panel where  panel_id='.$key." AND page_type=".$pageType.'<br>';
            $panelDet = mysql_query('select panel_type from tbl_template_panel where  panel_id='.$key." AND page_type=".$pageType) or die(mysql_error());
            if(mysql_num_rows($panelDet) > 0) {
                $rowPanel 	= mysql_fetch_assoc($panelDet);
               // echopre($rowPanel);
                $replacer 	= '{$editable'.$rowPanel['panel_type'].'}';
                $editedHtml = '';
                foreach($positions as $panelPos) {

                    $panelBox = explode('_',$panelPos);
                    if($panelBox[0] == 'exterbox') {
                        $editContent = '';
                         
                        if($panelBox[1] == 'navmenu') {	// for navigational menu params
                            $navmenuBox = '';
                            $appDetails = $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['items'];
                            $menuType = $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['settings']['menutype'];
                            if(sizeof($appDetails) > 0) {
                                $navmenuBox .= ' <ul class="'.$menuType.'">';
                                foreach($appDetails as $key=>$details) {
                                    $navmenuBox .= '<li><a href="'.$details['link'].'" target="'.$details['opentype'].'">'.  $details['title'].'</a> </li>';
                                }
                                $navmenuBox .= '</ul>';
                            }
                            else {
                                $navmenuBox = ' <ul class="'.$menuType.'">';
                            } 

                            $deleteLink = '<a href="#" onclick="loadMenuEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"> </a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.'<a href="#" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete">  </a></span>';

                            $editContent = '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4><span class="configure" >'.$editLink.'</span></h4>
							<div class="dragbox-content editablenavmenu" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$navmenuBox.'</div></div> 
							</div>';
                        }
                        else if($panelBox[1] == 'socialshare') {

                            //	$socialshare = 'social share';

                            $socialshare = '';
                            $appParams = getEditorAppParams(EDITOR_APP_SOCIAL_SHARE);
                            if(mysql_num_rows($appParams) > 0) {
                                while($row = mysql_fetch_assoc($appParams)) {
                                    if($row['param_img'] != '') {
                                        $socialshare .= '<img src="'.EDITOR_IMAGES.$row['param_img'].'" class="socialShareClass">';
                                    }
                                }
                            }

                            $deleteLink = '<a href="#" onclick="loadBoxEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"> </a>';
                            $editLink 	= '<span class="panel_controls" >'.$deleteLink.' <a title="Delete widget" href="#" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete">  </a></span>';
                       		
                            if($socialshare == '')  
                            	$socialshare = '<img src="editor/images/icon_facebook.png" class="socialShareClass"> <img src="editor/images/icon_twitter.png" class="socialShareClass"> <img src="editor/images/icon_linkedin.png" class="socialShareClass">';
            
                            //$widgetTitle	= 'Social Shares';
                            $editContent = '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4><span class="configure" >'.$editLink.'</span>'.$widgetTitle.'</h4>
							<div class="dragbox-content editablebox" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$socialshare.'</div></div> 
							</div> ';

                        }
                        elseif ($panelBox[1] == 'form') {// feedback form


                            $deleteLink = '<a href="#" onclick="loadFormEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"> </a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="Edit Panel Content" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"> </a></span>';

                            //$widgetTitle	= 'Feedback widget';
                                $objForm 		= new Htmlform();
                            $formDetails 	= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos];
                            if(sizeof($formDetails) > 0) {
                            	global $formFeedBackItems;
                                $feedBackItems =  $formDetails['items'];
                                foreach($feedBackItems as $key=>$items) {
                                	$itemDet = $formFeedBackItems[$items];
                                    $objFormElement				= new Formelement();
                                    $objFormElement->type		= $itemDet['field'];
                                    $objFormElement->name       = $items;
                                    $objFormElement->id         = $items;
                                    $objFormElement->label      = $itemDet['title'];
                                    $objForm->addElement($objFormElement);
                                }
                            }
                            $formOutPut		= $objForm->renderForm();
                            $formContent 	= $formOutPut;
                            
                            
                            
                           // $formContent = '[Click to edit the form]';
                            $editContent = '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4><span class="configure" >'.$editLink.'</span>'.$widgetTitle.'</h4>
							<div class="dragbox-content editableform" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$formContent.'</div></div> 
							</div> ';

                            //echo $editContent;
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
                        elseif ($panelBox[1] == 'dynamicform') {
                            $deleteLink = '<a href="#" onclick="loadCustomFormEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"> </a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="Edit Panel Content" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete">  </a></span>';
                            $formContent = 'A sample custom form, click edit to update the form fields';
                            $editContent .= '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span></h4>
							<div class="dragbox-content editableform widgetTitleStyle" id="'.$panelPos.'"  data-param="'.$panelPos.'">'.$formContent.'</div></div>
							</div> ';
                        }
                        elseif ($panelBox[1] == 'htmlbox') {// html box


                            $deleteLink = '<a href="#" onclick="loadHtmlboxEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"> </a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="Edit Panel Content" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"> </a></span>';

                            $formContent 	= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos];
                            if($formContent == '') $formContent = 'A sample html content, click edit to enter your own text.';
                            //$widgetTitle	= 'Html widget';

                            $editContent = '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4><span class="configure" >'.$editLink.'</span>'.$widgetTitle.'</h4>
							<div class="dragbox-content editableform" id="editpanel_'.$panelPos.'"  data-param="'.$panelPos.'">'.$formContent.'</div></div> 
							</div> ';
                        }

                        elseif ($panelBox[1] == 'slider') { // image slider


                            $deleteLink = '<a href="#" onclick="loadSliderEditor(\'editablebox_'.$panelBox[1].'\',1,\''.$panelPos.'\')" title="Edit widget" class=" panel_edit"> </a>';
                            $editLink 	= '<span class="panel_controls">'.$deleteLink.' <a title="Edit widget" href="" data-params="'.$panelPos.'" class="jqdeleteextapp panel_delete"> </a></span>';
                            $widgetContent 	=  showSlider($panelPos,$currentPage);
                            $widgetSliderTitle = 'A sample image slider, click edit to update slider';
                            //$widgetTitle	= 'Image Slider';

                            $editContent = '<div class="column" id="column_'.$key.'_'.$panelPos.'">
							<div class="dragbox" id="'.$panelPos.'" >
							<h4><span class="configure" >'.$editLink.'</span></h4>
							<div class="dragbox-content editableform widgetTitleStyle" id="editpanel_'.$panelPos.'"  data-param="'.$panelPos.'"><span style="display:block;padding-bottom:8px;">'.$widgetSliderTitle.'</span>'.$widgetContent.'</div></div>
							</div> ';
                        }

                        $editedHtml .=  $editContent ;
 
                    }
                    else {

                        $panelId = $panelBox[0];
                        if($panelId == 'item') $panelId=$panelBox[1];
                        ///echo 'val:'.$panelId.'<br>';
                        if($panelId != '' && is_numeric($panelId)) {
			 
                            //echo 'select panel_type,temp_id,page_type from tbl_template_panel where panel_id='.$panelId.'<br>';
                            $panelDet = mysql_query('select panel_type,temp_id,page_type from tbl_template_panel where panel_id='.$panelId) or die(mysql_error());

                            $rowPanel 		= mysql_fetch_assoc($panelDet);

                            //echopre($rowPanel);
                            if($rowPanel['panel_type'] == 'header' ||   $rowPanel['panel_type'] == 'footer') {	// footer, topmenu
                                $tempPanel 			= $rowPanel['panel_type'];
                                $tempId 			= $rowPanel['temp_id'];
                                //echo "SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type =1<br>";
                                $homePanelDet 		= mysql_query("SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type =1") or die(mysql_error());
                                $rowHomePanelDet 	= mysql_fetch_assoc($homePanelDet);
                                $homePanelId 		= $rowHomePanelDet['panel_id'];
                                $commonContent 		= $_SESSION['siteDetails']['index']['panels'][$homePanelId];
                                $_SESSION['siteDetails'][$currentPage]['panels'][$panelId] = $commonContent;
                                //echo $homePanelId.':'.$panelId.'<br>';
                                // echo $commonContent;
                                //$editLink 	= '<span style="text-align:right;float:right;"> [<a name="600" id="editor/edittemplate.php?panelid='.$panelId.'&templateid='.$templateid.'" class="modal" href="javascript:void(0);" title="Click to edit the tempplate" >edit</a>]</span>';
                                $editLink 	= '<span class="panel_controls" title="Click to edit the template"> <a title="Edit Panel Content" class="iframe panel_edit"  href="editor/edittemplate.php?panelid='.$panelId.'&templateid='.$templateid.'" > </a> <a href="" data-params="'.$panelId.'" class="jqdeleteextapp panel_delete"> </a></span>';

                                $editContent = '<div class="column" id="column_'.$panelId.'">
				<div class="dragbox" id="item_'.$panelId.'" >
				<h4><span class="configure" >'.$editLink.'</span> </h4>
				<div class="dragbox-content" id="editpanel_'.$panelId.'" >'.$commonContent.'</div></div> 
				</div>';
                                $editedHtml .=  $editContent ;
                            }
                            else {
                                if($curPageType == 'guestbook' && $rowPanel['panel_type'] == 'maincontent') {
                                    $newContent = 'Guest Book';

                                    //	 $editLink 	= '<a href="#" onclick="loadGuestBookEditor(\'editablebox_'.$panelId.'\',1,\''.$panelId.'\')" title="Edit widget" class="  panel_edit">Edit</a>';
                                    //	$deleteLink = '<a href="" data-params="'.$panelId.'" class="jqdeleteextapp panel_delete"> X </a>';
                                    // $panelLink 	= '<span class="panel_controls" > '.$editLink.$deleteLink.' </span>';
                                    $panelLink = '';
                                    $editContent = '<div class="column" id="column_'.$panelId.'">
						<div class="dragbox" id="item_'.$panelId.'" >
						<h4><span class="configure" >'.$panelLink.'</span> </h4>
						<div class="dragbox-content" id="editpanel_'.$panelId.'" >'.$newContent.'</div></div> 
						</div>';

                                    $editedHtml .=  $editContent ;
                                }
                                else {
                                    $newContent = $_SESSION['siteDetails'][$currentPage]['panels'][$panelId];
                                    //$editLink 	= '<span style="text-align:right;float:right;"> [<a name="600" id="editor/edittemplate.php?panelid='.$panelId.'&templateid='.$templateid.'" class="modal" href="javascript:void(0);" title="Click to edit the tempplate" >edit</a>]</span>';
                                    $editLink 	= '<span class="panel_controls" title="Click to edit the template"> <a title="Edit Panel Content" class="iframe panel_edit"  href="editor/edittemplate.php?panelid='.$panelId.'&templateid='.$templateid.'" > </a> <a href="" data-params="'.$panelId.'" class="jqdeleteextapp panel_delete"> </a></span>';

                                    $editContent = '<div class="column" id="column_'.$panelId.'">
						<div class="dragbox" id="item_'.$panelId.'" >
						<h4><span class="configure" >'.$editLink.'</span> </h4>
						<div class="dragbox-content" id="editpanel_'.$panelId.'" >'.$newContent.'</div></div> 
						</div>';

                                    $editedHtml .=  $editContent ;
                                }
                            }
                        }
                    }
                    //$_SESSION['siteDetails'][$currentPage]['panels'][$panelPos] = $editedHtml;
                }

                // ---------- code to add link for datatypes ---------------
                @$doc->loadHTML(mb_convert_encoding($editedHtml, 'HTML-ENTITIES', 'UTF-8'));


                // Replacing logo if uploaded from customize site apge
                if($_SESSION['siteDetails']['siteInfo']['logoName']!='') {
                    applySiteLogo($_SESSION['siteDetails']['siteInfo']['logoName']);
                }

                // Add/Replace company name from customize site apge
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

                 $editedHtml = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
                //echo $editedHtml;


                // finding the template menu details
                $menus 	= $doc->getElementsByTagName('ul');
                foreach ($menus as $menu) {
                    if($menu->getAttribute('data-type') == 'menu') {
                        $menuid = $menu->getAttribute('id');
 
                        // code to make the panel common
                        $curPanelId 		= $menuid;
                        $panelDetails 		= explode('_', $curPanelId);
                        $curPanelId1 		= $panelDetails[1];
                        
                      	//echo 'select temp_id,panel_type from tbl_template_panel where panel_id='.$curPanelId1.'<br>';
                        $panelDet 			= mysql_query('select temp_id,panel_type from tbl_template_panel where panel_id='.$curPanelId1) or die(mysql_error());
                        $rowPanel 			= mysql_fetch_assoc($panelDet);
                        $tempPanel 			= $rowPanel['panel_type'];
                        $tempId 			= $rowPanel['temp_id'];
                        $homePanelDet 		= mysql_query("SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type =1") or die(mysql_error());
                        $rowHomePanelDet 	= mysql_fetch_assoc($homePanelDet);
                        $homePanelId 		= $rowHomePanelDet['panel_id'];
                        $homePageMenuId 	= $panelDetails[0].'_'.$homePanelId.'_'.$panelDetails[2];
                        $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuid]['items'] = $_SESSION['siteDetails']['index']['datatypes']['menu'][$homePageMenuId]['items'];


                        $menuDet = $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuid]['items'];
                        $menuValue = '<ul id="'.$menuid.'" class="editablemenu" data-type="menu">';
                        if($menuDet) {
                            foreach($menuDet as $items) {
                                $menuValue .= '<li><a href="'.$items['link'].'">'.$items['title'].'</a></li>';
                            }
                        }
                       
                        if($pageType == 1) 
                        	$menuValue .= '<div class="manageMenuStyle"><a class="jqeditormenusettings" data-param="'.$menuid.'" href="#">'.EDIT_MENU_TITLE.'</a></div></ul> ';
                        $pattern = '/<ul (.*?)id="'.$menuid.'"[^:]*<\/ul>/';
                        $editedHtml = preg_replace($pattern,$menuValue, $editedHtml);

                    }
                }

                // ----------- data type link adding section ends ---------

                $theData 	= str_replace($replacer,$editedHtml,$theData);
                $theData        = str_replace("&nbsp;", "", $theData);
               //echopre($theData);
              
            }
        }
    }
}


        // form validation starts here
        @$doc->loadHTML(mb_convert_encoding($theData, 'HTML-ENTITIES', 'UTF-8'));
	$forms 	= $doc->getElementsByTagName('form');
    foreach ($forms as $form) {
    	 
    	  $form->setAttribute("action", "mailer.php");
    	   $form->setAttribute("class", "classmailer");
    }
     $theData = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
    
   // exit();
// form validation ends here


//echopre($theData);
// add the theme style to the template.
$theData = changeSiteStyle($templateid,$templateThemeId,$theData);



//Apply Site color/Page title/Meta Description/Meta Keywords
if($_SESSION['siteDetails']['siteInfo']['stclr']!=='' || $_SESSION['siteDetails']['siteInfo']['sitetitle']!=='' || $_SESSION['siteDetails']['siteInfo']['sitemetadesc']!=='' || $_SESSION['siteDetails']['siteInfo']['sitemetakey']!=='' || $_SESSION['siteDetails']['siteInfo']['companyname']!=='' || $_SESSION['siteDetails']['siteInfo']['captionname']!=='') {
    @$doc->loadHTML(mb_convert_encoding($theData, 'HTML-ENTITIES', 'UTF-8'));

    // Add/Replace company name from customize site apge
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

$theData = removeNotReplacableContent($theData);
//echopre($theData); 
// echopre($_SESSION['siteDetails']);

?>

<!-- ColorBox Popup -->
<link rel="stylesheet" href="style/common.css" />
<link rel="stylesheet" href="style/colorbox.css" />
<link rel="stylesheet" href="style/editor_styles.css" type="text/css">

<!-- Modal Popup scripts  -->
<script language="javascript" src="js/modal.popup.js"></script>
<!--  Modal Popup scripts  -->
<script type="text/javascript" src="js/jquery.colorbox.js"></script>

<!--  Drag Drop scripts  -->
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
                            // alert(html);

                            var entries = html.split(/~/);
                            $('#head'+dragitem).html(entries[0]);
                            $('#'+dragitem).append(entries[1]);

                        }
                    });
                    // alert(dragitem);

                    $('.editable'+entries[1]).trigger('click');
                    // code to generate new apps
                    paramvalue = "newitem="+dragcol;
                    // alert(paramvalue);
                    var newdataattr ='';
                    $.ajax({url: "editor/editor_externalappadding.php",
                        type: "POST",data: paramvalue ,cache: false,dataType:'html',
                        success: function(html) {
                            //  alert(html);

                            // var dragitem=$(selitem).attr('data-attr');
                            // alert(dragitem);
                            var entries = html.split(/~/);

                            newdataattr = entries[0];


                            $(selitem).attr('data-attr',newdataattr);
                            $("#"+dragcol).html(entries[1]);
                        }
                    });
                    //alert(newdataattr);


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

<?php  echo $theData;?> 

<script>
    $(document).ready(function(){

        // To disable the Form button actions
        $(".submitButton").live('click',(function() {
            return false;
        }));

        $(".iframe").colorbox({iframe:true, width:"50%", height:"72%"});

        //   $('.editablefile').trigger('click');
        // $('.editablebox').trigger('click');
    });


    $(function () {
        $("img").click(function() {
            $(this).addClass('editable_image_border_onclick');
        }); 	});
    $(document.body).click(function() { $("img").removeClass('editable_image_border_onclick'); });
</script>

