<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +----------------------------------------------------------------------+

// | PHP version 4/5                                                      |

// +----------------------------------------------------------------------+

// | Copyright (c) 2005-2006 ARMIA INC                                    |

// +----------------------------------------------------------------------+

// | This source file is a part of iScripts EasyCreate 1.1                 |

// +----------------------------------------------------------------------+

// | Authors: Naseema N A<naseema.n@armia.com>                                      |

// |                                                                                                            |

// +----------------------------------------------------------------------+





// Function to getsite details by site id

function getSiteDetailsBySiteWithSession($siteId) {



    // SiteData

    getSiteDataSession($siteId);



    // Site Pages

    $pageData = getSitePagesSession($siteId);



    // Site Page Contents

    getSitePageContentsSession($pageData);



    // Site Page External Contents

    getSitePageExternalContentsSession($pageData);

}



function getSiteData($siteId) {



    $getSiteDataQuery = "SELECT * FROM tbl_site_mast

                         WHERE nsite_id=".$siteId;

    $getSiteDataRes   = mysql_query($getSiteDataQuery) or die(mysql_error());

    $getSiteDataVal   = mysql_fetch_assoc($getSiteDataRes);

    return $getSiteDataVal;

}



function getSiteDataSession($siteId) {



    $siteData   = getSiteData($siteId);



    $companyStyle = explode("**",$siteData['vcompany_style']);

    $captionStyle = explode("**",$siteData['vcaption_style']);



    $_SESSION['session_sitename']        = $siteData['vsite_name'];

    $_SESSION['siteDetails']['siteId']   = $siteData['nsite_id'];

    $_SESSION['siteDetails']['siteInfo'] = array( 'logooption'   => $siteData['vlogo'],

            'siteName'     => $siteData['vsite_name'],

            'companyname'  => $siteData['vcompany'],

            'compfont'     => $companyStyle[0],

            'fontsize'     => $companyStyle[1],

            'fntclr'       => $companyStyle[2],

            'stclr'        => $siteData['vcolor'],
			
			'backgroundimage'  => $siteData['vimage'],

            'captionname'     => $siteData['vcaption'],

            'captionfont'     => $captionStyle[0],

            'captionfontsize' => $captionStyle[1],

            'captfntclr'      => $captionStyle[2],

            'sitetitle'    => $siteData['vtitle'],

            'sitemetadesc' => $siteData['vmeta_description'],

            'sitemetakey'  => $siteData['vmeta_key'],

            'templateid'   => $siteData['ntemplate_id'],

            'themeid'      => $siteData['ntheme_id'],

            'logoName'     => $siteData['vlogo_name'],

            'site_google_analytics_code' => $siteData['google_analytics_code']);



    $_SESSION['siteDetails']['currentpage'] = ($siteData['currentpage'])?$siteData['currentpage']:'index';

    $_SESSION['siteDetails']['templateid']  = $siteData['ntemplate_id'];

    $_SESSION['siteDetails']['themeid']     = $siteData['ntheme_id'];

}



function getSitePages($siteId) {



    $getSitePageQuery = "SELECT * FROM tbl_site_pages

                         WHERE nsite_id=".$siteId;

    $getSitePageRes   = mysql_query($getSitePageQuery) or die(mysql_error());

    while($row = mysql_fetch_assoc($getSitePageRes) ) {

        $getSitePageVal[] = $row;

    }

    return $getSitePageVal;

}



function getSitePagesSession($siteId) {



    $sitePagesVal   = getSitePages($siteId);

    if($sitePagesVal) {

        foreach($sitePagesVal as $sitePage) {

            $sitePages[$sitePage['nsp_id']] =  array( 'title'      => $sitePage['vpage_title'],

                                                       'link'      => $sitePage['vpage_link'],

                                                       'alias'     => $sitePage['vpage_name'],

                                                       'pagetype'  => $sitePage['vpage_type']);

        }

    }

    $_SESSION['siteDetails']['pages']   = $sitePages;

    return $sitePagesVal;

}



function getSitePageContents($pageId) {



    $getSitePageContentQuery = "SELECT * FROM  tbl_site_page_contents

                                WHERE page_id =".$pageId;

    $getSitePageContentRes   = mysql_query($getSitePageContentQuery) or die(mysql_error());

    while($row = mysql_fetch_assoc($getSitePageContentRes) ) {

        $getSitePageContentVal[] = $row;

    }

    return $getSitePageContentVal;

}



function getPanelIdByParentPanel($pageId,$parentPanelId) {

    $panelQuery = "SELECT panel_id FROM tbl_site_page_contents

                   WHERE page_id=".$pageId." AND parent_panel_id=".$parentPanelId;

    $panelRes   =  mysql_query($panelQuery) or die(mysql_error());

    while($row = mysql_fetch_assoc($panelRes)) {

        $panelVal[]= $row['panel_id'];

    }

    return $panelVal;

}



function getSitePageContentsSession($pageData) {



    if($pageData) {

        $doc = new DOMDocument();

        foreach($pageData as $page) {

            $sitePageContentVal   = getSitePageContents($page['nsp_id']);

            $sitePageContents = '';

            if($sitePageContentVal) {



                foreach($sitePageContentVal as $sitePageContent) {



                    $sitePageContents['panels'][$sitePageContent['panel_id']]  = $sitePageContent['panel_content'];

                    $panelPositions = getPanelIdByParentPanel($sitePageContent['page_id'],$sitePageContent['parent_panel_id']);

                    $sitePageContents['panelpositions'][$sitePageContent['parent_panel_id']]  = $panelPositions;



                    // Menu

                    @$doc->loadHTML(mb_convert_encoding($sitePageContent['panel_content'], 'HTML-ENTITIES', 'UTF-8'));



                    $menuNo = 1;

                    $menus 	= $doc->getElementsByTagName('ul');

                    $i =1;

                    foreach ($menus as $menu) {

                        if($menu->getAttribute('data-type') == 'menu') {

                            $menuId 	= 'editablemenu_'.$sitePageContent['parent_panel_id'].'_'.$menuNo;

                            $menuItems 	= $menu->getElementsByTagName('li');

                            $arrMenuItems = array();

                            foreach($menuItems as $items) {

                                $liTitle  =  $items->nodeValue;

                                if($i == 1) $linkVal = 'index';

                                else $linkVal = getAlias($liTitle,'-');

                                $arrMenuItems[] = array('title' => $liTitle ,'link' => $linkVal.'.html');

                                $i++;

                            }

                            $menuNo++;

                        }

                    }



                    // find the datatypes in the template

                    $divList 	= $doc->getElementsByTagName('div');

                    foreach ($divList as $divs) {

                        if($divs->getAttribute('data-type') == 'slider') { // slider checking

                            $sliderId 	= $divs->getAttribute('id');

                            $sliderExistingDataSettings = $_SESSION['siteDetails'][$page['vpage_name']]['datatypes']['slider'][$sliderId]['settings'];

                            $sliderExistingDataImages   = $_SESSION['siteDetails'][$page['vpage_name']]['datatypes']['slider'][$sliderId]['images'];

                        }

                        // image slider ends

                        else if($divs->getAttribute('data-type') == 'socialshare'){ // socilshare  checking section

                            $shareId 	= $divs->getAttribute('id');

                            $socialShareExistingData = $_SESSION['siteDetails'][$page['vpage_name']]['datatypes']['socialshare'][$shareId];

                        } // social share ends

                    }

                }

            }

            $sitePageContents['datatypes']['menu'][$menuId]['items'] = $arrMenuItems;

            $sitePageContents['datatypes']['slider'][$sliderId]['settings'] = $sliderExistingDataSettings;

            $sitePageContents['datatypes']['slider'][$sliderId]['images'] = $sliderExistingDataImages;

            $sitePageContents['datatypes']['socialshare'][$shareId] = $socialShareExistingData;

            $_SESSION['siteDetails'][$page['vpage_name']] = $sitePageContents;  //echopre1($_SESSION['siteDetails']);



        }

    }

}



// Get Site Page External Contents

function getSitePageExternalContents($pageId) {

    $getSitePageExtContentQuery = "SELECT ea.app_class,spec.* FROM tbl_site_page_external_contents spec

                                  INNER JOIN tbl_editor_apps ea ON ea.app_id = spec.external_widget_type_id

                                  WHERE spec.page_id =".$pageId;

    $getSitePageExtContentRes   = mysql_query($getSitePageExtContentQuery) or die(mysql_error());

    while($row = mysql_fetch_assoc($getSitePageExtContentRes) ) {

        $getSitePageExtContentVal[] = $row;

    }

    return $getSitePageExtContentVal;

}



function getSitePageExternalContentsSession($pageData) {



    if($pageData) {

        foreach($pageData as $page) {

            $sitePageExtContentVal   = getSitePageExternalContents($page['nsp_id']);

            $sitePageExtContents = '';

            $sitePageExtContentsPanel = array();

            if(count($sitePageExtContentVal)>0) {

                foreach($sitePageExtContentVal as $sitePageExtCon) {

                    $sitePageExtContents['exterbox_'.$sitePageExtCon['app_class'].'_'.$sitePageExtCon['external_widget_id']]  = json_decode($sitePageExtCon['external_widget_content'],true);

                    $sitePageExtContentsPanel[$sitePageExtCon['external_widget_panel_position']] = 'exterbox_'.$sitePageExtCon['app_class'].'_'.$sitePageExtCon['external_widget_id'];

                }

            }

            if($_SESSION['siteDetails'][$page['vpage_name']]['panelpositions']) {

                foreach($_SESSION['siteDetails'][$page['vpage_name']]['panelpositions'] as  $keyP => $valP) {

                    if(array_key_exists($keyP, $sitePageExtContentsPanel)) {

                        $_SESSION['siteDetails'][$page['vpage_name']]['panelpositions'][$keyP][] = $sitePageExtContentsPanel[$keyP];

                    }

                }

            }

            $_SESSION['siteDetails'][$page['vpage_name']]['apps'] = $sitePageExtContents;

        }

    }

}



// Insert/Update Site Data

function saveSiteDetails($site_id=0) {



    $siteDetails  = $_SESSION['siteDetails'];

    $getSiteQuery = "SELECT * FROM tbl_site_mast WHERE nsite_id =".$site_id;

    $getSiteRes = mysql_query($getSiteQuery);

    $getSiteVal = mysql_fetch_assoc($getSiteRes);



    $companyStyle = $siteDetails['siteInfo']["compfont"].'**'.$siteDetails['siteInfo']["fontsize"].'**'.$siteDetails['siteInfo']["fntclr"];

    $captionStyle = $siteDetails['siteInfo']["captionfont"].'**'.$siteDetails['siteInfo']["captionfontsize"].'**'.$siteDetails['siteInfo']["captfntclr"];

    $siteLogo = ($siteDetails['siteInfo']["logoName"])?$siteDetails['siteInfo']["logoName"]:$getSiteVal['vlogo_name'];
	$siteBackImage2 = ($siteDetails['siteInfo']["backgroundimage"])?$siteDetails['siteInfo']["backgroundimage"]:$getSiteVal['vimage'];



    if(mysql_num_rows($getSiteRes) > 0) {

        $sqlSiteMaster =  "UPDATE tbl_site_mast SET vsite_name='".mysql_real_escape_string($siteDetails['siteInfo']["siteName"])."',nuser_id='".$_SESSION["session_userid"]."',

                           ncat_id='".$_SESSION["session_categoryid"]."',ntemplate_id='".$siteDetails["templateid"]."',ntheme_id='".$siteDetails["themeid"]."',

                           vtitle='".mysql_real_escape_string($siteDetails['siteInfo']["sitetitle"])."',vmeta_description='".mysql_real_escape_string($siteDetails['siteInfo']["sitemetadesc"])."',vmeta_key='".mysql_real_escape_string($siteDetails['siteInfo']["sitemetakey"])."',

                           vlogo='".$siteDetails['siteInfo']["logooption"]."',vlogo_name='".$siteLogo."',vcompany='".mysql_real_escape_string($siteDetails['siteInfo']["companyname"])."',vcompany_style='".mysql_real_escape_string($companyStyle)."',

                           vcolor='".mysql_real_escape_string($siteDetails['siteInfo']["stclr"])."',

                           vimage='".$siteBackImage2."'
						   
						   ,vcaption='".mysql_real_escape_string($siteDetails['siteInfo']["captionname"])."' ,vcaption_style='".mysql_real_escape_string($captionStyle)."', google_analytics_code='".mysql_real_escape_string($siteDetails['siteInfo']["site_google_analytics_code"])."' WHERE nsite_id=".$site_id;

        mysql_query($sqlSiteMaster) or die(mysql_error());

    }else {

        if($siteDetails['siteInfo']){

            $sqlSiteMaster = "INSERT INTO tbl_site_mast (vsite_name,nuser_id,ncat_id,ntemplate_id,ntheme_id,vtype,vtitle,

                              vmeta_description,vmeta_key,vlogo,vlogo_name,vcompany,vcompany_style,vcolor,vimage,vcaption,vcaption_style,google_analytics_code,ddate) values (

                              '".mysql_real_escape_string($_SESSION['siteDetails']['siteInfo']['siteName'])."',

                              '".mysql_real_escape_string($_SESSION["session_userid"])."',

                              '".mysql_real_escape_string($_SESSION["session_categoryid"])."',

                              '".mysql_real_escape_string($siteDetails["templateid"])."',

                              '".mysql_real_escape_string($siteDetails["themeid"])."',

                              'advanced',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["sitetitle"])."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["sitemetadesc"])."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["sitemetakey"])."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["logooption"])."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["logoName"])."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["companyname"])."',

                              '".mysql_real_escape_string($companyStyle)."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["stclr"])."',
							  
							  '".mysql_real_escape_string($siteDetails['siteInfo']["backgroundimage"])."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["captionname"])."',

                              '".mysql_real_escape_string($captionStyle)."',

                              '".mysql_real_escape_string($siteDetails['siteInfo']["site_google_analytics_code"])."',

                              now())";

            mysql_query($sqlSiteMaster) or die(mysql_error());

            $site_id = mysql_insert_id();

        }

    }

    $_SESSION['siteDetails']['siteId'] = $site_id;

    return $site_id;

}



//Insert/Update Site Pages and Contents

function saveSitePages($site_id=0) {



    $siteDetails  = $_SESSION['siteDetails'];

    $getSitePageVal = array();

    $getSitePageContentVal = array();

    $getSitePageExtContentVal = array();



    $getSitePageQuery = "SELECT * FROM  tbl_site_pages

                         WHERE nsite_id =".$site_id;

    $getSitePageRes   = mysql_query($getSitePageQuery) or die(mysql_error());

    while($row = mysql_fetch_assoc($getSitePageRes)) {

        $getSitePageVal[$row['nsp_id']] = $row;



        // Get Site Page Contents

        $getSitePageContentQuery = "SELECT * FROM  tbl_site_page_contents

                                   WHERE page_id =".$row['nsp_id'];

        $getSitePageContentRes   = mysql_query($getSitePageContentQuery) or die(mysql_error());

        while($rowC = mysql_fetch_assoc($getSitePageContentRes)) {

            $getSitePageContentVal[$row['vpage_name']][$rowC['panel_id']] = $rowC;

        }



        // Get Site Page External Contents

        $getSitePageExtContentQuery = "SELECT ea.app_class,spec.* FROM tbl_site_page_external_contents spec

                                      INNER JOIN tbl_editor_apps ea ON ea.app_id = spec.external_widget_type_id

                                      WHERE spec.page_id =".$row['nsp_id'];

        $getSitePageExtContentRes   = mysql_query($getSitePageExtContentQuery) or die(mysql_error());

        while($rowEC = mysql_fetch_assoc($getSitePageExtContentRes)) {

            $getSitePageExtContentVal[$row['vpage_name']]['exterbox_'.$rowEC['app_class'].'_'.$rowEC['external_widget_id']] = $rowEC;

        }



    }





    foreach($siteDetails['pages'] as $keyPage => $sitePage) {

        if(key_exists($keyPage, $getSitePageVal)) {

            $sqlSitePages =  "UPDATE tbl_site_pages SET vpage_name='".mysql_real_escape_string($sitePage["alias"])."',vpage_title='".mysql_real_escape_string($sitePage["title"])."',vpage_link='".mysql_real_escape_string($sitePage["link"])."'

                              WHERE nsp_id=".$keyPage;

            mysql_query($sqlSitePages) or die(mysql_error());

            $site_page_id = $keyPage;

        }else {



            $sqlSitePages =   "INSERT INTO tbl_site_pages (nsite_id,vpage_name,vpage_title,vpage_link,vpage_type,vtype) values (

                              '".mysql_real_escape_string($site_id)."',

                              '".mysql_real_escape_string($sitePage["alias"])."',

                              '".mysql_real_escape_string($sitePage["title"])."',

                              '".mysql_real_escape_string($sitePage["link"])."',

                              '".mysql_real_escape_string($sitePage["pagetype"])."',

                              'advanced')";

            mysql_query($sqlSitePages) or die(mysql_error());

            $site_page_id = mysql_insert_id();

        }



        // Insert/Update Site Page Contents

        foreach($siteDetails[$sitePage['alias']]['panelpositions'] as $key =>$val) {

            foreach($val as $panelPosition) {



                // Insert/Update Site Page External Contents

                $panelBox = explode('_',$panelPosition);

                if($panelBox[0] == 'exterbox') {



                    // Get External Widget/App Type Id

                    $getExternalWidgetType = getExternalWidgetType($panelBox[1]);



                    if(key_exists($panelPosition, $getSitePageExtContentVal[$sitePage['alias']])) {



                        $sqlSitePageExtContents =  "UPDATE tbl_site_page_external_contents SET external_widget_panel_position='".$key."',

                                                        external_widget_content='".mysql_real_escape_string(json_encode($siteDetails[$sitePage['alias']]['apps'][$panelPosition]))."'

                                                        WHERE external_widget_type_id=".$getExternalWidgetType['app_id']." AND external_widget_id=".$panelBox[2]." AND page_id=".$keyPage;

                        mysql_query($sqlSitePageExtContents) or die(mysql_error());



                    }else {



                        $sqlSitePageExtContents =   "INSERT INTO tbl_site_page_external_contents (page_id,external_widget_type_id,external_widget_panel_position,external_widget_id,external_widget_content,date_added) values (

                                                      '".mysql_real_escape_string($site_page_id)."',

                                                      '".mysql_real_escape_string($getExternalWidgetType['app_id'])."',

                                                      '".mysql_real_escape_string($key)."',

                                                      '".mysql_real_escape_string($panelBox[2])."',

                                                      '".mysql_real_escape_string(json_encode($siteDetails[$sitePage['alias']]['apps'][$panelPosition]))."',

                                                      now())";

                        mysql_query($sqlSitePageExtContents) or die(mysql_error());

                    }

                }else {

//echo "<pre>";

//print_r($panelPosition);

//print_r($getSitePageContentVal[$sitePage['alias']]);

                    if(@key_exists($panelPosition, $getSitePageContentVal[$sitePage['alias']])) {

                        $sqlSitePageContents =  "UPDATE tbl_site_page_contents SET parent_panel_id='".$key."',panel_content='".mysql_real_escape_string($siteDetails[$sitePage['alias']]['panels'][$panelPosition])."'

                                                     WHERE panel_id=".$panelPosition." AND page_id=".$keyPage;

                        mysql_query($sqlSitePageContents) or die(mysql_error());

                    }else {

                        $sqlSitePageContents =   "INSERT INTO tbl_site_page_contents (page_id,panel_id,parent_panel_id,panel_content,is_app,date_added) values (

                                                      '".mysql_real_escape_string($site_page_id)."',

                                                      '".mysql_real_escape_string($panelPosition)."',

                                                      '".mysql_real_escape_string($key)."',

                                                      '".mysql_real_escape_string($siteDetails[$sitePage['alias']]['panels'][$panelPosition])."',

                                                      '0',

                                                      now())";

                        mysql_query($sqlSitePageContents) or die(mysql_error());

                    }

                }

            }

        }

    }

}



// Get External Widget/App Type

function getExternalWidgetType($appClass) {



    $getExternalWidgetTypeIdQuery = "SELECT * FROM  tbl_editor_apps WHERE app_status='1' AND app_class='".$appClass."'";

    $getExternalWidgetTypeIdRes   = mysql_query($getExternalWidgetTypeIdQuery) or die(mysql_error());

    $getExternalWidgetTypeIdVal   = mysql_fetch_assoc($getExternalWidgetTypeIdRes);

    return $getExternalWidgetTypeIdVal;

}





function getSiteDetailsBySiteBkp($siteId) {



    $getSiteDetailsBySiteQuery = "SELECT s.*,sp.*,spc.*

                                  FROM tbl_site_mast s

                                  INNER JOIN tbl_site_pages sp On s.nsite_id = sp.nsite_id

                                  INNER JOIN tbl_site_page_contents spc On sp.nsp_id = spc.page_id

                                  WHERE s.nsite_id=".$siteId;

    $getSiteDetailsBySiteRes = mysql_query($getSiteDetailsBySiteQuery) or die(mysql_error());



    while($row = mysql_fetch_assoc($getSiteDetailsBySiteRes)) {

        $getSiteDetailsBySiteVal[] = $row;

    }

    return $getSiteDetailsBySiteVal;

}





function removeNotReplacableContent($theData) {



    $theReplacedData = preg_replace('/\{\$editable(.*)\}/',NULL, $theData);

    return $theReplacedData;

}





//Delete folder function

function deleteDirectory($dir) {

    if (!file_exists($dir)) return true;

    if (!is_dir($dir) || is_link($dir)) return unlink($dir);

    foreach (scandir($dir) as $item) {

        if ($item == '.' || $item == '..') continue;

        if (!deleteDirectory($dir . "/" . $item)) {

            chmod($dir . "/" . $item, 0777);

            if (!deleteDirectory($dir . "/" . $item)) return false;

        };

    }

    return @rmdir($dir);

}



//  For DownloadSite



function getPaymentStatusByUser($siteId,$userId) {



    if($siteId > 0 && $userId > 0 ) {

        $paymentStatusQuery = "SELECT count(npayment_id) as paymentCount FROM tbl_payment WHERE nsite_id=".$siteId." AND nuser_id=".$userId;

        $paymentStatusRes = mysql_query($paymentStatusQuery);

        $paymentStatusVal = mysql_fetch_assoc($paymentStatusRes);

        return $paymentStatusVal['paymentCount'];

    }

}



// For Payment (both free or paid)

function savePaymentDetails($type='Free',$cost=0,$transId=0) {



    $siteId = $_SESSION['siteDetails']['siteId'];

    $userId = $_SESSION["session_userid"];

    $paymentStatus = getPaymentStatusByUser($siteId,$userId);



    if($paymentStatus <= 0) {

        $Cust_id=uniqid("Cust");



        $insertPaymentData = "INSERT INTO tbl_payment(nsite_id,nuser_id,namount,ddate,vpayment_type,vtxn_id,vuniqid) Values(

                                '" . mysql_real_escape_string($siteId) . "',

                                '" . mysql_real_escape_string($userId) . "',

                                '" . $cost . "',

                                now(),

                                '" . mysql_real_escape_string($type) . "',

                                '" . $transId . "',

                                '" . $Cust_id . "')";

        mysql_query($insertPaymentData) or die(mysql_error());

        $invoiceId = mysql_insert_id();

        return $invoiceId;

    }

}



// Publish Site

function publishSite() {



    $siteDetails  = $_SESSION['siteDetails']; //echopre($siteDetails);

    $siteId       = $siteDetails['siteId'];

    $templateId   = $siteDetails['templateid'];

    $googleAnalyticsCode = $siteDetails['siteInfo']['site_google_analytics_code'];



    $siteThemeData = getSiteTheme($siteDetails['siteInfo']['themeid']);

    $siteTheme     = $siteThemeData['theme_style'];



    $sitePath = "../".USER_SITE_UPLOAD_PATH.$siteId;



    $mode = ($_SESSION['SERVER_PERMISSION'])?$_SESSION['SERVER_PERMISSION']:0777;



    // Get banner

    $createdSiteBanner     = getSettingsValue('enable_created_site_banner');

    $createdSiteBannerName = getSettingsValue('created_site_banner_name');

    $createdSiteBannerLink = getSettingsValue('created_site_banner_link');



    // Remove existing site folder in tempsite folder

    if($siteId >0) {

        deleteDirectory($sitePath);

    }



    // Create Directories

    if (!@is_dir("workarea"))

        @mkdir("workarea",$mode);



    if (!@is_dir("workarea/sites"))

        @mkdir("workarea/sites",$mode);



    if (!@is_dir($sitePath))

        @mkdir($sitePath,$mode);



    $siteImage = $sitePath."/images";

    if (!@is_dir($siteImage))

        @mkdir($siteImage,$mode);



    $siteStyle = $sitePath."/styles";

    if (!@is_dir($siteStyle))

        @mkdir($siteStyle,$mode);



    $siteJs = $sitePath."/js";

    if (!@is_dir($siteJs))

        @mkdir($siteJs,$mode);



    $templateFileBasePath = "../".$_SESSION["session_template_dir"] . "/" . $templateId;





    $doc = new DOMDocument();



    if($siteDetails['pages']) {

        foreach($siteDetails['pages'] as $page) {



            // code to check whether its a guest book

            $curPageType	= '';

            if($page['pagetype'] == 'guestbook'){

                $curPageType = 'guestbook';

                $templateFile 	= $templateFileBasePath.'/subpage_temp.htm';

            }else{

                $pageTypeName    = getPageTypeName($templateId,$page['pagetype']);

                if($pageTypeName!=""){

                    $pageTypeNameRaw = explode(".",$pageTypeName);

                    $templateFile    = $templateFileBasePath.'/'.$pageTypeNameRaw[0].'_temp.htm';

                }else{

                    $templateFile    = $templateFileBasePath.'/subpage_temp.htm';

                }

            }



            // To make menu links active

            $pageLinkVal = str_replace("-", "_", $page['alias']);



            // code to get the template details

            $fh 	= fopen($templateFile, 'r');

            $siteData 	= fread($fh, filesize($templateFile));

            fclose($fh);



            // To replace with corresponding style

            @$doc->loadHTML(mb_convert_encoding($siteData, 'HTML-ENTITIES', 'UTF-8'));

            $styleTags = $doc->getElementsByTagName('link');

            if($styleTags) {

                foreach($styleTags as $styleTag) {

                    $styleData = $styleTag->getAttribute('href');

                    if($styleTag->getAttribute('data-type')=='sitestyle') {

                        @copy($templateFileBasePath.'/'.$siteTheme, $sitePath.'/'.$siteTheme);

                        $styleTag->setAttribute("href",$siteTheme);

                    }

                }

            }



            // Copy basic styles

            $basicStyle  = '../style/basic_site_style.css';

            @copy($basicStyle, $sitePath.'/styles/basic_site_style.css');

            // Copy basic styles



            if($siteDetails['siteInfo']['stclr']!=='') {

                // SiteColor

                $siteColor = $doc->getElementById('sitecolor');

                if(isset($siteColor)) {

                    $siteColor->setAttribute("style","background-color:".$siteDetails['siteInfo']['stclr']);

                }

            }
			
			if($siteDetails['siteInfo']['backgroundimage']!=='') {

                // SiteColor

                $siteColor = $doc->getElementById('sitecolor');

                if(isset($siteColor)) {

                    $siteColor->setAttribute("style","background-image:url(".$siteDetails['siteInfo']['backgroundimage']."); background-repeat:no-repeat; background-size:cover; background-attachment:fixed;background-position:center;");

                }

            }



            if($siteDetails['siteInfo']['sitetitle']!=='') {

                // Site Page Title

                $titleTags = $doc->getElementsByTagName('title');

                if($titleTags) {

                    foreach($titleTags as $titleTag) {

                        $titleTag->nodeValue = $siteDetails['siteInfo']['sitetitle'];

                    }

                }

            }

            if($siteDetails['siteInfo']['sitemetadesc']!=='' || $siteDetails['siteInfo']['sitemetakey']!=='') {

                // Site Meta Desc/ Meta Keywords

                $mataDescTags = $doc->getElementsByTagName('meta');

                if($mataDescTags) {

                    foreach($mataDescTags as $mataDescTag) {

                        if($mataDescTag->getAttribute('name')=='description') {

                            $mataDescTag->setAttribute("content",$siteDetails['siteInfo']['sitemetadesc']);

                        }

                        if($mataDescTag->getAttribute('name')=='keywords') {

                            $mataDescTag->setAttribute("content",$siteDetails['siteInfo']['sitemetakey']);

                        }

                        if($mataDescTag->getAttribute('http-equiv')=='Content-Type') {

                            $mataDescTag->setAttribute("content","text/html;charset=utf-8");

                        }

                    }

                }

            }



            $siteData =  $doc->saveHTML();

            // To replace with corresponding style



            if($siteDetails[$page['alias']]['panelpositions']) {

                $i = 0;

                foreach($siteDetails[$page['alias']]['panelpositions'] as $key=>$positions) {

                    $i++;

                    // get the panel details

                    if(is_numeric($key)) {

                        $panelDet = mysql_query('select panel_type from tbl_template_panel where panel_id='.$key) or die(mysql_error());

                        if(mysql_num_rows($panelDet) > 0) {

                            $rowPanel 	= mysql_fetch_assoc($panelDet);

                            $replacer 	= '{$editable'.$rowPanel['panel_type'].'}';

                            $editedHtml = '';

                            // Add basic style css to code

                            if($i==1){

                                $editedHtml .= '<link rel="stylesheet" href="styles/basic_site_style.css">';

                            }

                            // Add basic style css to code



                            foreach($positions as $panelPos) {



                                // Check for external Apps

                                $panelBox = explode('_',$panelPos);

                                if($panelBox[0] == 'exterbox') {

                                    // For Social Shares

                                    if($panelBox[1] == 'socialshare') {

                                        $appDetails = $siteDetails[$page['alias']]['apps'][$panelPos];

                                        $socialShare = '<div>';

                                        if(sizeof($appDetails) > 0) {

                                            foreach($appDetails as $key=>$details) {

                                                if($key == 'twitlink')

                                                    $socialShare .= '<a href="'.$details.'"><img class="socialShareClass" src="images/icon_twitter.png"></a>';

                                                else if($key == 'fppage')

                                                    $socialShare .= '<a href="'.$details.'"><img class="socialShareClass" src="images/icon_facebook.png"></a>';

                                                else if($key == 'linkedinlink')

                                                    $socialShare .= '<a href="'.$details.'"><img class="socialShareClass" src="images/icon_linkedin.png"></a>';

                                            }

                                        }

                                        else {

                                            $socialShare .= '<img class="socialShareClass" src="images/icon_facebook.png"> <img class="socialShareClass" src="images/icon_twitter.png"> <img class="socialShareClass" src="images/icon_linkedin.png">';

                                        }

                                        $socialShare .= '</div><div class="clear></div>';



                                        $editedHtml .=  $socialShare;

                                        copyfilesdirr("../editor/images/",$siteImage."/",$mode,false);

                                    }



                                    // For navigational menu params

                                    else if($panelBox[1] == 'navmenu') {

                                        $menuList = '';

                                        $appDetails     = $siteDetails[$page['alias']]['apps'][$panelPos]['items'];

                                        $appMenuStyle   = $siteDetails[$page['alias']]['apps'][$panelPos]['settings']['menutype'];

                                        if(sizeof($appDetails) > 0) {

                                            $menuList .= '<div><ul class="'.$appMenuStyle.'">';

                                            foreach($appDetails as $key=>$details) {

                                                $menuList .= '<li><a href="'.$details['link'].'" target="'.$details['opentype'].'">'.  $details['title'].'</a></li>';

                                            }

                                            $menuList .= '</ul></div><div class="clear></div>';

                                        }

                                        $editedHtml .= $menuList;

                                    }



                                    // For Contact Form

                                    else if($panelBox[1] == 'form') {

                                        $objForm = new Htmlform();

                                        $formDetails = $siteDetails[$page['alias']]['apps'][$panelPos];

                                        if(sizeof($formDetails) > 0) {

                                            global $formFeedBackItems;

                                            $feedBackItems =  $formDetails['items'];

                                            foreach($feedBackItems as $key=>$items) {

                                                $itemDet = $formFeedBackItems[$items];

                                                $objFormElement		    = new Formelement();

                                                $objFormElement->type	    = $itemDet['field'];

                                                $objFormElement->name       = $items;

                                                $objFormElement->id         = $items;

                                                $objFormElement->label      = $itemDet['title'];

                                                $objForm->addElement($objFormElement);

                                            }

                                        }

                                        $senderMailId = $siteDetails[$page['alias']]['apps'][$panelPos]['email'] ;

                                        $formOutPut	= $objForm->renderForm();

                                        $editedHtml .= '<div class="feedbackform">'.$formOutPut.'</div>';

                                    }



                                    // For Html widgets

                                    elseif ($panelBox[1] == 'htmlbox') {

                                       // $htmlContent 	= $_SESSION['siteDetails'][$page['alias']]['apps'][$panelPos];

                                        $htmlContent 	= addWidgetContent($_SESSION['siteDetails'][$page['alias']]['apps'][$panelPos]);

                                        $editedHtml    .= $htmlContent;

                                    }

                                    // For slider

                                    elseif ($panelBox[1] == 'slider') {

                                        $panelPosRow = explode("_",$panelPos);

                                        //$widgetHtml = addWidgetSlider($panelPos,$page['alias']);

                                        $widgetHtml = addSlider($panelPos,$sitePath,$page['alias'],'app',$panelPosRow[2]);

                                        $editedHtml .= $widgetHtml;



                                    }

                                    // For google adsense

                                    elseif ($panelBox[1] == 'googleadsense') {

                                        $formDetails =  $_SESSION['siteDetails'][$page['alias']]['apps'][$panelPos];

                                        global $adSenseDimensionsArray;



                                        foreach($adSenseDimensionsArray as $key=>$val) {

                                            if($formDetails['google_ad_dimension']==$key) {

                                                $width = $val['width'];

                                                $height = $val['height'];

                                            }

                                        }



                                        $googleAd =  addGoogleAd($formDetails['google_ad_slot'], $width , $height, $ad_comment , $formDetails['google_ad_client']);

                                        $editedHtml .= $googleAd;

                                    }



                                  else if($panelBox[1] == 'dynamicform') {// loading the custom form elements

                                		$objForm = new Htmlform();

                                		$customFormDetails = $_SESSION['siteDetails'][$page['alias']]['apps'][$panelPos];

                               			$dynamiFormEmail = $customFormDetails['email'];

                                		$editedHtml .= $customFormDetails['formelements'];

                            		}

                                }



                                else { 



                                        // check whether its a guest book and add the code

                                        if($curPageType == 'guestbook' && $rowPanel['panel_type'] == 'maincontent') {

                                        //TODO: need to move the 'gb.txt' file along with the files



                                            //$guestBook = '../widgets/guestbook/gb.txt';

                                            $guestBook = '../gb.txt';

                                            @copy($guestBook, $sitePath.'/gb.txt');



                                            $guestBookCode 	= generateGuestBookContentAddCode($page['link'],'publish');

                                            $editedHtml   .= $guestBookCode;

                                	}

                                	else {



                                            $editedHtml .= $siteDetails[$page['alias']]['panels'][$panelPos]; 

                                            

	                                    // To replace with corresponding images

                                            @$doc->loadHTML(mb_convert_encoding($editedHtml, 'HTML-ENTITIES', 'UTF-8'));

	                                    $tags = $doc->getElementsByTagName('img');

	                                    if($tags) {

	                                        foreach($tags as $tag) {

	                                            $imageData    = $tag->getAttribute('src');

	                                            $imageRelPath = str_replace(BASE_URL, '', $imageData);

	                                            $imageName    = pathinfo($imageData);

	                                            @copy("../".$imageRelPath, $siteImage.'/'.$imageName['basename']);

	                                            $tag->setAttribute("src", 'images/'.$imageName['basename']);

	                                        }

	                                    }

	                                    $editedHtml = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());

	                                    // To replace with corresponding images



                                            



                                    // Menu

                                    @$doc->loadHTML(mb_convert_encoding($editedHtml, 'HTML-ENTITIES', 'UTF-8'));

                                    $menus 	= $doc->getElementsByTagName('ul');

                                    foreach ($menus as $menu) {

                                        if($menu->getAttribute('data-type') == 'menu') {

                                            $menuid = $menu->getAttribute('id');

                                            $menuDet = $siteDetails[$page['alias']]['datatypes']['menu'][$menuid]['items'];

                                            $menuValue = '<ul id="'.$menuid.'" class="editablemenu" data-type="menu">';

                                            if($menuDet) {

                                                foreach($menuDet as $items) {

                                                    //$menuValue .= '<li><a href="'.$items['link'].'">'.$items['title'].'</a></li>';

                                                    $menuValue .= '<li '.(($pageLinkVal.'.html' == $items['link'] || $pageLinkVal.'.php' == $items['link'])?'class="active"':'').' ><a href="'.$items['link'].'">'.$items['title'].'</a></li>';

                                                }

                                            }

                                            $pattern = '/<ul (.*?)id="'.$menuid.'"[^:]*<\/ul>/';

                                            $editedHtml = preg_replace($pattern,$menuValue, $editedHtml);



                                            $editedHtml 	= str_replace('Manage menu','',$editedHtml);



                                        }

                                    }

                                    // Menu



                                    //echopre($editedHtml);



                                    // Slider

                                     $divList 	= $doc->getElementsByTagName('div'); 

                                     foreach ($divList as $divs) {

                                         if($divs->getAttribute('data-type') == 'slider') {

                                             $sliderid          = $divs->getAttribute('id');

                                             $pattern 		= '/<div (.*?)id="'.$sliderid.'"[^~]*<\/div>/';

                                             $sliderValue 	= addSlider($sliderid,$sitePath,$page['alias'],'datatype',2);

                                             $editedHtml = preg_replace($pattern,$sliderValue, $editedHtml);

                                         }

                                         // Social Share

                                         

                                         // Find footer social share

                                         else if($divs->getAttribute('data-type') == 'socialshare'){ 

                                                $commonSocialShareId 	= $divs->getAttribute('id');

                                                $commonSocialLinkDetails      =  $siteDetails['commonpanel']['socialshare'];

                                                $commonSocialShareContent     = addCommonSocialShareContent($commonSocialShareId,$commonSocialLinkDetails);

                                                //$ssPattern = '/<div (.*?)id="'.$commonSocialShareId.'"[^~]*<\/div>/';

                                                $ssPattern = '/<div (.*?)data-type="socialshare"(.*?)id="'.$commonSocialShareId.'"[^~]*<\/div>/';

                                                $editedHtml = preg_replace($ssPattern,$commonSocialShareContent, $editedHtml);

                                         }else{

                                             $editedHtml = $editedHtml;

                                         }



                                             /*

                                             if($divs->getAttribute('data-type') == 'socialshare') {

                                                 $shareid 	= $divs->getAttribute('id');

                                                 /******* Modified for common footer in all pages ******

                                                 $shareDet      = $siteDetails[$page['alias']]['datatypes']['socialshare'][$shareid];

                                                  /******* Modified for common footer in all pages *******/

                                             /*

                                                 $shareDet      = $siteDetails['commonpanel']['socialshare'];



                                                 $shareValue = '<div id="'.$shareid.'" class="socialshare" data-type="socialshare">';

                                                 if($shareDet) {

                                                    foreach($shareDet as $items) {

                                                        $shareValue .= '<a href="http://'.$items['link'].'" target="_blank"><img class="editableshare" src="'.$items['image'].'"></a>';

                                                    }

                                                 }

                                                 $pattern       = '/<div (.*?)id="'.$shareid.'"[^~]*<\/div>/';

                                                 $editedHtml = preg_replace($pattern,$shareValue, $editedHtml);

                                            }*/

                                     }



                                  }



                                }

                            }



                            //echopre($editedHtml);

                            //echopre("******************************************************************");



                            // To copy all images from template folder to  created site folder

                            // (since certain images are set as background images from styles)

                            copyfilesdirr($templateFileBasePath."/images/",$siteImage."/",$mode,false);



                            // To copy button related images to created site folder

                            $btnImageArray = array('orange-btn-bg.jpg','grey-btn01.png','menu_sep.jpg','nxt.png');

                            $btnImagePath = '../style/images/';

                            foreach($btnImageArray as $btnImage){

                                @copy($btnImagePath.$btnImage, $siteImage."/".$btnImage);

                            }



                            // To move site banner image to created site

                            $siteBannerImage = explode("banners/",$createdSiteBannerName);

                            $bannerImagePath = '../uploads/siteimages/banners/'.$siteBannerImage[1];

                            @copy($bannerImagePath, $siteImage."/".$siteBannerImage[1]);





                            // Google Analytics Code

                            if($googleAnalyticsCode!='') {

                                $googleAnalyticsCodeSnippet = getGoogleAnalytics($googleAnalyticsCode);

                            }



                            if($i==count($siteDetails[$page['alias']]['panelpositions'])) {

                                if($googleAnalyticsCode!='') {

                                    $editedHtml .= $googleAnalyticsCodeSnippet;

                                }

                            }

                            // Google Analytics Code



                            /*

                            // Add basic style css to code

                            if($i==count($siteDetails[$page['alias']]['panelpositions'])) {

                                $editedHtml .= '<link rel="stylesheet" href="styles/basic_site_style.css">';

                            }

                            // Add basic style css to code

                            */

                            $siteData   = str_replace($replacer,$editedHtml,$siteData);

                        }

                    }

                }  //exit;

            }



          $siteData   = str_replace("Manage menu", '', $siteData);

          $siteFileName = $page['link'];





        // form validation starts here

        @$doc->loadHTML(mb_convert_encoding($siteData, 'HTML-ENTITIES', 'UTF-8'));



        $doc->encoding = 'UTF-8';

        $forms 	= $doc->getElementsByTagName('form');

        foreach ($forms as $form) {



            $formName = $form->getAttribute('name');



            if($formName!="gbForm"){

                if($form->getAttribute('id') == 'jqCmsForm') {

                    $mailTo = $senderMailId ;

                }

                else if($dynamiFormEmail !=''){

                    $mailTo = $dynamiFormEmail;

                }else{

                    $mailTo = $_SESSION['siteDetails']['contactmailid'] ;

                }



                if($mailTo == '')

                	$mailTo = $_SESSION["session_email"];



                $form->setAttribute("method", "POST");

                $form->setAttribute("action", "mailer.php?mailto=".$mailTo);



                $pageLink = explode('.',$page['link']);



                $contactPage = $pageLink[0].'.php';

            }

    }









    // Add/Replace company name from customize site page

    if($siteDetails['siteInfo']['companyname']!='') {



        $titleTag = $doc->getElementsByTagName('h1');

        foreach($titleTag as $tTag) {

            if($tTag->getAttribute('data-type') == 'heading') {

                $tTag->nodeValue = '';

                $tit = $doc->createDocumentFragment();

                if($_SESSION['siteDetails']['siteInfo']['chksitetitlestyle'] == 1)

                    $tit->appendXML("<span style='font-family:".$siteDetails['siteInfo']['compfont']."; color:".$siteDetails['siteInfo']['fntclr'].";font-size:".$siteDetails['siteInfo']['fontsize']."px;'>".htmlspecialchars($siteDetails['siteInfo']['companyname'])."</span>");

                else

                    $tit->appendXML(htmlspecialchars($siteDetails['siteInfo']['companyname']));

                $tTag->appendChild($tit);

            }

        }

    }



    // Add/Replace company name from customize site apge

    if($siteDetails['siteInfo']['captionname']!='') {



        $captionTag = $doc->getElementsByTagName('h2');

        foreach($captionTag as $cTag) {

            if($cTag->getAttribute('data-type') == 'caption') {

                $cTag->nodeValue = '';

                $cap            = $doc->createDocumentFragment();

                 if($_SESSION['siteDetails']['siteInfo']['chksitedesstyle'] == 1)

                    $cap->appendXML("<span style='font-family:".$siteDetails['siteInfo']['captionfont']."; color:".$siteDetails['siteInfo']['captfntclr'].";font-size:".$siteDetails['siteInfo']['captionfontsize']."px;'>".htmlspecialchars($siteDetails['siteInfo']['captionname'])."</span>");

                 else

                    $cap->appendXML(htmlspecialchars($siteDetails['siteInfo']['captionname']));

                $cTag->appendChild($cap);

            }

        }

    }



    // Add banner

    if($createdSiteBanner && $createdSiteBannerName!=''){

        $siteBannerImage = explode("banners/",$createdSiteBannerName);

        $siteBannerLinkVal = explode("://",$createdSiteBannerLink);

        if($siteBannerLinkVal[0]=='http') $siteBannerLinkReal = $createdSiteBannerLink;

        else  $siteBannerLinkReal = 'http://'.$siteBannerLinkVal[0];

        $banner = $doc->getElementById('site_banner');

        if(isset($banner)) {

            $banner->nodeValue   = '';

            $ban            = $doc->createDocumentFragment();

            $ban->appendXML('<a href="'.$siteBannerLinkReal.'" target="_blank" linkmode="external"><img src="images/'.$siteBannerImage[1].'" /></a>');

            $banner->appendChild($ban);

        }

    }



    //$siteData = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());

    $siteData = $doc->saveHTML();



    // Added since the guestbook file contain > symbol as &gt;

    $siteData = str_replace(array('&gt;', '&lt;'), array('>', '<'), $siteData);

    // Added since the guestbook file contain > symbol as &gt;



    $contactmailer = '../widgets/mailer/mailer.php';

    @copy($contactmailer, $sitePath.'/mailer.php');

    /*

    if($contactPage != '') {



    	 $siteFilePath = $sitePath."/".$contactPage;

            $siteFileHandle = fopen($siteFilePath, 'w') or die("can't open file");

            // To remove replaceble content

            $siteData = removeNotReplacableContent($siteData);

            fwrite($siteFileHandle,$siteData);

        //  fclose($siteFileHandle);

    }

    */



        $siteFilePath = $sitePath."/".$siteFileName;

        $siteFileHandle = fopen($siteFilePath, 'w') or die("can't open file");

        // To remove replaceble content

        $siteData = removeNotReplacableContent($siteData);

        fwrite($siteFileHandle,$siteData);

        fclose($siteFileHandle);



        }

    }

}





function addSlider($widgetid,$sitePath,$page,$mode,$id) {



    $type='simple';



    if($type == 'simple') {

        $silderContent = '';

        $widgetSliderPath = BASE_URL.'widgets/slider/simple/';



        $sliderBjqsFiles = $widgetSliderPath.'js/bjqs-1.3.js';

        $sliderStyle     = $widgetSliderPath.'demo.css';



    	if (!file_exists($sitePath.'/js')) {

            mkdir($sitePath.'/js');

        }



        // To copy button related images to created site folder

        $btnSliderImageArray = array('next.png','next_over.png','prev.png','prev_over.png');

        $btnSliderImagePath = BASE_URL.'widgets/slider/images/';

        $siteImage = $sitePath."/images";

        foreach($btnSliderImageArray as $btnImage){

            @copy($btnSliderImagePath.$btnImage, $siteImage."/".$btnImage);

        }



        @copy($sliderBjqsFiles, $sitePath.'/js/bjqs-1.3.js');

        @copy($sliderStyle, $sitePath.'/styles/demo.css');



        $sliderFiles = '<script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>';

        $sliderFiles .= '<script src="js/bjqs-1.3.js"></script>';



        $sliderFiles .= '<link rel="stylesheet" href="styles/demo.css">';



        if($mode =='datatype')

            $sliderSettings = $_SESSION['siteDetails'][$page]['datatypes']['slider'][$widgetid]['settings'];

        else

            $sliderSettings = $_SESSION['siteDetails'][$page]['apps'][$widgetid]['settings'];



        $slideHeight = $sliderSettings['height'];

        $slideWidth  = $sliderSettings['width'];

        $slideDelay  = $sliderSettings['delay'];

        $jsCode = "   <script >

        		jQuery(document).ready(function($) {

          		$('#banner-fade".$id."').bjqs({

		            height: ".$slideHeight.",width: ".$slideWidth.",responsive  : true, animspeed   : ".$slideDelay."

		        });

        	});

      		</script> ";

        $silderContent 	= '<div id="banner-fade'.$id.'" style="float:left;width:'.$slideWidth.'px;" ><ul class="bjqs">';



        if($mode =='datatype')

            $sliderImages  = $_SESSION['siteDetails'][$page]['datatypes']['slider'][$widgetid]['images'];

        else

            $sliderImages  = $_SESSION['siteDetails'][$page]['apps'][$widgetid]['images'];



        foreach($sliderImages as $image) {

            $imageNameArray   = explode("/",$image['image']);

            $imageNameNumber  = count($imageNameArray)-1;

            $imageName        = $imageNameArray[$imageNameNumber];

            $imagPhysicalPath = str_replace(BASE_URL,"", $image['image']);



            @copy("../".$imagPhysicalPath, $sitePath.'/images/'.$imageName);



            $imgUrl = 'images/'.$imageName;

            $silderContent .= ' <li><img src="'.$imgUrl.'"     > </li>';

        }



        $silderContent .= '</ul> </div>';

        $htmlContent 	= '<div class="widgetimageslider" style="width:100%">'.$sliderFiles.$silderContent.$jsCode.'</div><div class="clear"></div><br/>';

    }



    return $htmlContent;



}





function getSiteTheme($themeId) {

    $query = "SELECT * FROM tbl_template_themes

              WHERE theme_status='1' AND theme_id=".$themeId;

    $res  = mysql_query($query) or die(mysql_error());

    $val  = mysql_fetch_assoc($res);

    return $val;

}



// Apply Site styles to editor



// Apply Site Logo

function applySiteLogo($siteLogo) {

    global $doc;

    $imageTags = $doc->getElementsByTagName('img');



    foreach($imageTags as $imageTag) {

        if($imageTag->getAttribute('data-type') == 'logo') {



            $defaultWidth = $imageTag->getAttribute('width');

            if($defaultWidth==""){

                $defaultLogo = $imageTag->getAttribute('src');

                $imageParams = @getimagesize($defaultLogo);

                $width = ($imageParams[0])?$imageParams[0]:"100";

                $height = ($imageParams[1])?$imageParams[1]:"100";

                $imageTag->setAttribute("width", $width);

                $imageTag->setAttribute("height", $height);

            }



            $imageTag->setAttribute("src", $siteLogo);

        }

    }

}



// Apply Site Company

function applySiteCompanyWithIdBkp($siteCompany,$siteCompanyFont,$siteCompanyFontColor,$siteCompanyFontSize) {

    global $doc;





    $titleVal = $doc->getElementById('heading');

    if(isset($titleVal)) {

        $titleVal->nodeValue = '';

        $tit = $doc->createDocumentFragment();

        if($_SESSION['siteDetails']['siteInfo']['chksitetitlestyle'] == 1)

        	$tit->appendXML("<span style='font-family:".$siteCompanyFont."; color:".$siteCompanyFontColor.";font-size:".$siteCompanyFontSize."px;'>".htmlspecialchars($siteCompany)."</span>");

 		else

        	$tit->appendXML(htmlspecialchars($siteCompany));

        $titleVal->appendChild($tit);

    }



    /*

    $styleFilePath = "widgets/styles/widget_style.css";

    $styleData = "@font-face{

                    font-family: ".$siteCompanyFont.";

                    src: url('fonts/Calibri.eot');

                    src: url('fonts/Calibri.eot?#iefix') format('embedded-opentype');

                    src: local('Calibri'), url('fonts/Calibri.ttf') format('truetype'); /* For non-IE */ /*}";

    $siteFileHandle = fopen($styleFilePath, 'w') or die("can't open file");

    fwrite($siteFileHandle,$siteData);

    fclose($siteFileHandle);

    */



}



function applySiteCompany($siteCompany,$siteCompanyFont,$siteCompanyFontColor,$siteCompanyFontSize) {

    global $doc;



    $titleTag = $doc->getElementsByTagName('h1');

    foreach($titleTag as $tTag) {

        if($tTag->getAttribute('data-type') == 'heading') {

            $tTag->nodeValue = '';

            $tit = $doc->createDocumentFragment();

            if($_SESSION['siteDetails']['siteInfo']['chksitetitlestyle'] == 1)

                    $tit->appendXML("<span style='font-family:".$siteCompanyFont."; color:".$siteCompanyFontColor.";font-size:".$siteCompanyFontSize."px;'>".htmlspecialchars($siteCompany)."</span>");

                    else

                    $tit->appendXML(htmlspecialchars($siteCompany));

            $tTag->appendChild($tit);

        }

    }



}



// Apply Site Caption

function applySiteCaptionByIdBkp($siteCaption,$siteCaptionFont,$siteCaptionFontColor,$siteCaptionFontSize) {

    global $doc;

    $caption = $doc->getElementById('caption');

    if(isset($caption)) {

        $caption->nodeValue   = '';

        $cap            = $doc->createDocumentFragment();

        if($_SESSION['siteDetails']['siteInfo']['chksitedesstyle'] == 1)

        	$cap->appendXML("<span style='font-family:".$siteCaptionFont."; color:".$siteCaptionFontColor.";font-size:".$siteCaptionFontSize."px;'>".htmlspecialchars($siteCaption)."</span>");

        else

        	$cap->appendXML(htmlspecialchars($siteCaption));

        $caption->appendChild($cap);

    }

}



// Apply Site Caption

function applySiteCaption($siteCaption,$siteCaptionFont,$siteCaptionFontColor,$siteCaptionFontSize) {

    global $doc;



    $captionTag = $doc->getElementsByTagName('h2');

    foreach($captionTag as $cTag) {



        if($cTag->getAttribute('data-type') == 'caption') {

            $cTag->nodeValue = '';

            $cap            = $doc->createDocumentFragment();

            if($_SESSION['siteDetails']['siteInfo']['chksitedesstyle'] == 1)

                    $cap->appendXML("<span style='font-family:".$siteCaptionFont."; color:".$siteCaptionFontColor.";font-size:".$siteCaptionFontSize."px;'>".htmlspecialchars($siteCaption)."</span>");

            else

                    $cap->appendXML(htmlspecialchars($siteCaption));

            $cTag->appendChild($cap);

        }

    }

}



// Apply Site Banner

function applySiteBanner($siteBanner,$siteBannerLink) {

    global $doc;

    $siteBannerLinkVal = explode("://",$siteBannerLink);

    if($siteBannerLinkVal[0]=='http') $siteBannerLinkReal = $siteBannerLink;

    else  $siteBannerLinkReal = 'http://'.$siteBannerLinkVal[0];

    $banner = $doc->getElementById('site_banner');

    if(isset($banner)) {

        $banner->nodeValue   = '';

        $ban            = $doc->createDocumentFragment();

        $ban->appendXML('<a href="'.$siteBannerLinkReal.'" target="_blank" linkmode="external"><img src="'.$siteBanner.'" /></a>');

        $banner->appendChild($ban);

    }

}





// Apply Site Background Color

function applySiteColor($siteColor) {

    global $doc;

    $siteColorTag = $doc->getElementById('sitecolor');

    if(isset($siteColorTag)) {

        $siteColorTag->setAttribute("style","background-color:".$siteColor);

    }
}

// Apply Site Background Image

function applySiteBackImage($siteBackImage234) {

    global $doc;

    $siteColorTag = $doc->getElementById('sitecolor');
	
	if ($siteBackImage234 != '')
		{
			$siteColorTag->setAttribute("style","background-image:url(".$siteBackImage234."); background-repeat:no-repeat; background-size:cover; background-attachment:fixed; background-position:center;");
		}

}



// Apply Site Page Title

function applySitePageTitle($pageTitle) {

    global $doc;

    $titleTags = $doc->getElementsByTagName('title');

    if($titleTags) {

        foreach($titleTags as $titleTag) {

            $titleTag->nodeValue = $pageTitle;

        }

    }

}



// Apply Site Meta Data

function applySiteMetaData($metaKey,$metaDesc) {

    global $doc;

    $mataDescTags = $doc->getElementsByTagName('meta');

    if($mataDescTags) {

        foreach($mataDescTags as $mataDescTag) {

            if($mataDescTag->getAttribute('name')=='description') {

                $mataDescTag->setAttribute("content",$metaDesc);

            }

            if($mataDescTag->getAttribute('name')=='keywords') {

                $mataDescTag->setAttribute("content",$metaKey);

            }

        }

    }

}



// Function to check site name exists

function isSitenameExist($sitename,$site_id='') {

    $qry = "select * from tbl_site_mast where vsite_name='" . mysql_real_escape_string(trim($sitename)) . "' and ndel_status='0'";

    if($site_id > 0)

        $qry .= " and nsite_id!=".$site_id;

    if (mysql_num_rows(mysql_query($qry)) > 0) {

        return false;

    } else {

        $qry = "select * from tbl_site_mast where ndel_status !='1' and vsite_name='" . mysql_real_escape_string(trim($sitename)) . "' and ndel_status='0'";

        if($site_id > 0)

        $qry .= " and nsite_id!=".$site_id;

        if (mysql_num_rows(mysql_query($qry)) > 0) {

            return false;

        }

    }

    return true;

}



function isValidsitename($str) {

    if (trim($str) != "") {

        if (!preg_match("/^[0-9a-zA-Z+_+.+ +,]*$/",$str)){

            return false;

        } else {

            return true;

        }

    } else {

        return false;

    }

}





function isValidsitenameBkp($str) {

    if (trim($str) != "") {

        if (preg_match("[^0-9a-zA-Z+_+.+ +,]", $str)) {

            return false;

        } else {

            return true;

        }

    } else {

        return false;

    }

}



// Panel Delete



// Get Site Page Id

function getSitePageId($siteId,$pageAlias){

    $pageQuery = "SELECT nsp_id FROM  tbl_site_pages

                  WHERE nsite_id='".$siteId."' AND vpage_name='".$pageAlias."'";

    $pageRes   = mysql_query($pageQuery) or die(mysql_error());

    $pageVal   = mysql_fetch_assoc($pageRes);

    return $pageVal['nsp_id'];

}



// Check Exists For Site Page Panels

function checkSitePagePanelExists($panelId,$pageId,$type) {

    if($type =='app'){

        $panelExistsQuery = "SELECT * FROM  tbl_site_page_external_contents

                             WHERE external_widget_id='".$panelId."' AND page_id=".$pageId;

    }else{

        $panelExistsQuery = "SELECT * FROM  tbl_site_page_contents

                             WHERE panel_id='".$panelId."' AND page_id=".$pageId;

    }

    $panelExistsRes   = mysql_query($panelExistsQuery) or die(mysql_error());

    $panelExists      = mysql_num_rows($panelExistsRes);



    return $panelExists;

}



// Delete Site Page Panels

function deleteSitePagePanel($panelId,$pageId,$type) {



    if($type =='app'){

        $panelDeleteQuery = "DELETE FROM tbl_site_page_external_contents

                             WHERE external_widget_id='".$panelId."' AND page_id=".$pageId;

    }else{

        $panelDeleteQuery = "DELETE FROM tbl_site_page_contents

                             WHERE panel_id='".$panelId."' AND page_id=".$pageId;

    }

    $panelExistsRes   = mysql_query($panelDeleteQuery) or die(mysql_error());

}





function getUserDetails($userId){

    $userQuery = "SELECT * FROM tbl_user_mast WHERE nuser_id=".$userId;

    $userRes   = mysql_query($userQuery);

    $userVal = mysql_fetch_assoc($userRes);

    return $userVal;

}



function updateSitePublishStatus($siteId){

    if($siteId > 0){

        $query = "UPDATE tbl_site_mast SET is_published ='1' WHERE nsite_id=".$siteId;

        $res   = mysql_query($query);

    }

}



// New code to save sitedetails from session as a whole to db and retrieve it



function saveSiteDetailsAsWholeToDb($siteId){

    $siteDetails = json_encode($_SESSION['siteDetails']);

    $sqlSiteData = "INSERT INTO tbl_site_details (site_id,site_data,created_on) values (

                          '".mysql_real_escape_string($siteId)."',

                          '".mysql_real_escape_string($siteDetails)."',

                          now())";

    mysql_query($sqlSiteData) or die(mysql_error());

}



function getSiteDetailsAsWhole($siteId){

    $sqlSiteData = "Select * FROM tbl_site_details WHERE site_id=".$siteId;

    $resSiteData = mysql_query($sqlSiteData) or die(mysql_error());

    $valSiteData = mysql_fetch_assoc($resSiteData);

    return $valSiteData;

}



function deleteSiteDetails($siteId){



    $siteData = getSiteDetailsAsWhole($siteId);

    if($siteData!=""){

        $deleteSiteData = "DELETE FROM tbl_site_details WHERE site_id=".$siteId;

        mysql_query($deleteSiteData) or die(mysql_error());

    }

}



function assignSiteDetailsAsWholeToSession($siteId){



    $siteDetails = getSiteDetailsAsWhole($siteId);

    $siteData = json_decode($siteDetails['site_data'],true);

    // Assign values to session

    $_SESSION['siteDetails']           = $siteData;

    $_SESSION['siteDetails']['siteId'] = $siteId;

}



// New code to save sitedetails from session as a whole to db and retrieve it



// Send mail after successfully creating the site

function sendSiteCreatedMail($userDetails){



 	//mail the user welcome mail

    $rootDirectory = getSettingsValue('root_directory');

	$sitelogo = $rootDirectory . '/' . getSettingsValue('Logourl');



    $userName    = $userDetails['vuser_name'].' '.$userDetails['vuser_lastname'];

    $createdSite = $_SESSION['siteDetails']['siteInfo']['siteName'];

    $siteName    = getSettingsValue('site_name');

    $adminEmail  = getSettingsValue('admin_mail');

    $email       = $userDetails['vuser_email'];



    $mailbody = "<table>

                <tr>

                    <td>

                        <img src=".$sitelogo."><br><br>

                    </td>

                  </tr>

                  <tr>

                       <td>";

    $mailbody .=  " Dear ". stripslashes($userName).",<br><br>";

    $mailbody .=  "You have successfully created the site with site name : '".$createdSite."'<br>";

    $mailbody .= "You can visit the <a href=".BASE_URL."> site </a> for further assistance.<br><br>";

    $mailbody .= "Regards,<br>The $siteName Team<br></td></tr><table>";



    $Headers="From: $siteName <$adminEmail>\n";

    $Headers.="Reply-To: $siteName <$adminEmail>\n";

    $Headers.="MIME-Version: 1.0\n";

    $Headers.="Content-type: text/html; charset=iso-8859-1\r\n";

    $subject = "Your Site Created Successfully";

    $mailsent = @mail($email, $subject, $mailbody,$Headers);

}





function updateNewPageToSession($templateid,$pageType,$currentPage){



    include "../editor/editor_addwidgetcontent.php";



    $templatePath  = $_SESSION["session_template_dir"] . "/" . $templateid;

    $doc = new DOMDocument();



    $currentPageType = $pageType;

    if($pageType =="guestbook") $currentPageType = 2;



    // select the panels of the template

    $sql    = "Select * from tbl_template_panel where temp_id=".$templateid." AND page_type='".$currentPageType."' ORDER BY panel_id ASC";

    $result = mysql_query($sql) or die(mysql_error());

    if(mysql_num_rows($result) > 0) {

        while($row = mysql_fetch_assoc($result)) {

            if($_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] == '') {



                $panelData = $row['panel_html'];

                $panelData = str_replace('<h4>Keep in touch</h4>','<h3>Keep in touch</h3>',$panelData);



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



                            $imageParams = @getimagesize($fielPath);

                            $width = ($imageParams[0])?$imageParams[0]:"100";

                            $height = ($imageParams[1])?$imageParams[1]:"100";



                            if($_SESSION['siteDetails']['siteInfo']['logoName']!='') {

                                if($tag->getAttribute('data-type') == 'logo') {

                                    $tag->setAttribute("src",$_SESSION['siteDetails']['siteInfo']['logoName']);

                                    $tag->setAttribute("width", $width);

                                    $tag->setAttribute("height", $height);

                                }else {

                                    $tag->setAttribute("src", $fielPath);

                                }

                            }else {

                                $tag->setAttribute("src", $fielPath);

                            }



                            //$theData 	= str_replace($tempImages[0],$fielPath,$theData);

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

                            if($i == 1)

                                $newpage = 'index';

                            else {

                            	$liTitle1 = str_replace(' ', '', $liTitle);

                                $newpage = getAlias($liTitle1);



                                $templatePages[]	= array('title' => $liTitle ,'alias' => $newpage ,'link' => $newpage.'.html');

                            }

                            $arrMenuItems[]	= array('title' => $liTitle ,'link' => $newpage.'.html');

                            $i++;

                        }

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

		                    }

		                }

		                $arrSettings 	= array('height' => $imgHeight,'width' => $imgWidth,'delay'=> '2000');

						$_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderId]['settings']	= $arrSettings;

						$imgrow 		= time();

					 	$_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$sliderId]['images'][$imgrow] = $arrImgDet;

                    }	// image slider ends

                    else  if($divs->getAttribute('data-type') == 'socialshare') {	// socialshare form checking

                            $arrShareDet='';

                            $shareId 	= 'editableshare_'.$row['panel_id'].'_'.$datatypeNo;

                            $divs->setAttribute("id", $shareId);

                            $datatypeNo++;

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



                        }	// social share ends

                }



                // code to get the guest book details

                if($pageType == 'guestbook' && $row['panel_type'] == 'maincontent') {

                    $guestBookCode = generateGuestBookContentAddCode('','preview');

                    $guestBookCode = str_replace("&nbsp;", " <br>", $guestBookCode);

                    $panelData = $guestBookCode;

                }



                $panelData = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());



                $_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] = $panelData;

                $_SESSION['siteDetails'][$currentPage]['panelpositions'][$row['panel_id']] = array($row['panel_id']);

            }

        }



    }



}



function getPageTypeVal($currentPage,$templateid){



    $pageType = 2;

    if($currentPage =='index') $pageType = 1;

    else {

        $pageType = getPageType($templateid,$currentPage);

        if($pageType == '' || $pageType == 2)

            $pageType = 2;

    }

    return $pageType;

}





function getSiteTemplateTopMenuPageAlias($templateId, $pageName){

    $sqlData = "Select * FROM tbl_template_pages WHERE temp_id='".$templateId."' AND page_alias='".$pageName."' AND page_name!='subpage.html'";

    $resData = mysql_query($sqlData) or die(mysql_error());

    $valData = mysql_fetch_assoc($resData);

    return $valData;

}







?>