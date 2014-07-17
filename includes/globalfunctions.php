<?php
error_reporting(0);
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                      |
// |                                                                                                            |
// +----------------------------------------------------------------------+


$templateLogos = array(	'LogoImage'		=> 'tp_logoimage',
        'LogoBand'		=> 'tp_logoband',
        'Company'		=> 'tp_company',
        'CompanyBand'	=> 'tp_companyband',
        'Caption'		=> 'tp_caption',
        'CaptionBand'	=> 'tp_captionband');


$linkOpenTargets = array(	'blank'		=> '_blank',
        'parent'	=> '_parent',
        'self'		=> '_self',
        'top'		=> '_top');


$formFeedBackItems = array(	"txtfname"      => array('title' => 'First Name',		'field' => 'textbox'),
        "txtlname"      => array('title' => 'Last Name',		'field' => 'textbox'),
        "txtAddres1" 	=> array('title' => 'Address Line 1',	'field' => 'textbox'),
        "txtAddress2" 	=> array('title' => 'Address Line 2',	'field' => 'textbox'),
        "txtCity" 	=> array('title' => 'City',				'field' => 'textbox'),
        "txtState" 	=> array('title' => 'State',			'field' => 'textbox'),
        "txtCountry" 	=> array('title' => 'Country',			'field' => 'textbox'),
        "txtZip" 	=> array('title' => 'ZIP',				'field' => 'textbox'),
        "txtPhone" 	=> array('title' => 'Phone',			'field' => 'textbox'),
        "txtFax" 	=> array('title' => 'Fax',				'field' => 'textbox'),
        "txtEmail" 	=> array('title' => 'Email',			'field' => 'textbox')
);
/*
$arrDefaultPages = array(	"1" => array("title" => 'about'),
							"2" => array("title" => 'contact'));
*/

$arrDefaultFormFields = array('txtfname','txtlname','txtEmail');

$adsenseWidth  = array(1,2,3,4);
$adsenseHeight = array(1,2,3,4);
$adSenseDimensionsArray = array( "leaderboard" => array('name'=>'Leaderboard','width' => '728','height' => '90'),
        "banner" => array('name'=>'Banner','width' => '468','height' => '60'),
        "halfbanner" => array('name'=>'Half Banner','width' => '234','height' => '60'),
        "skyscraper" => array('name'=>'Skyscraper','width' => '120','height' => '60'),
        "wideskyscraper" => array('name'=>'Wide Skyscraper','width' => '160','height' => '600'),
        "smallrectangle" => array('name'=>'Small Rectangle','width' => '180','height' => '150'),
        "verticalbanner" => array('name'=>'Vertical Banner','width' => '120','height' => '240'),
        "largeskyscraper" => array('name'=>'Large Skyscraper','width' => '300','height' => '600'),

        "mediumrectangle" => array('name'=>'Medium Rectangle','width' => '300','height' => '250'),
        "largerectangle" => array('name'=>'Large Rectangle','width' => '336','height' => '280'),
        "smallsquare" => array('name'=>'Small Square','width' => '200','height' => '200'),
        "square" => array('name'=>'Square','width' => '250','height' => '250'),
        "button" => array('name'=>'Button','width' => '125','height' => '125'));




/*
	 * function to upload the logo images
*/
function imageUploader($imagesdir,$watermarkdir,$chkHome='') {
    global $templateLogos;
    foreach($templateLogos as $fileid=>$filename) {
        $fileExtention =  getFileExtension($_FILES[$fileid]['name']);

        $newFileName = $filename.'.'.$fileExtention;
        @move_uploaded_file($_FILES[$fileid]["tmp_name"],$imagesdir . "/" . $newFileName);
        @chmod($imagesdir . "/" . $newFileName,0777);
        @copy($imagesdir . "/" . $newFileName,$watermarkdir . "/" . $newFileName);
        @chmod($watermarkdir . "/" . $newFileName,0777);

        if($chkHome == 'home') {
            @copy($imagesdir . "/" . $newFileName,$imagesdir . "/tp_inner" .$newFileName);
            @copy($imagesdir . "/" . $newFileName,$watermarkdir . "/tp_inner" . $newFileName);
            @chmod($imagesdir . "/tp_inner" . $newFileName,0777);
            @chmod($watermarkdir . "/tp_inner" . $newFileName,0777);

        }

    }
    return true;
}

/*
	 * function to upload the subpage images
*/

function subpageImageUploader($imagesdir,$watermarkdir) {
    global $templateLogos;
    foreach($templateLogos as $fileid=>$filename) {
        $fileExtention =  getFileExtension($_FILES[$fileid]['name']);
        $filename = str_replace('tp_','tp_inner',$filename);
        $newFileName = $filename.'.'.$fileExtention;


        @move_uploaded_file($_FILES[$fileid]["tmp_name"],$imagesdir . "/" . $newFileName);
        @chmod($imagesdir . "/" . $newFileName,0777);
        @copy($imagesdir . "/" . $newFileName,$watermarkdir . "/" . $newFileName);
        @chmod($watermarkdir . "/" . $newFileName,0777);
    }
}




function echopre($printArray) {
    echo "<pre>";
    print_r($printArray);
    echo "</pre>";
}

function echopre1($printArray) {
    echo "<pre>";
    print_r($printArray);
    echo "</pre>";
    exit();
}


/*
	 * function to find the extension of a file
*/
function getFileExtension($str) {
    $i 		= strrpos($str,".");
    if (!$i) {
        return "";
    }
    $l 		= strlen($str) - $i;
    $ext 	= substr($str,$i+1,$l);
    return $ext;
}


/*
	 * function to get the file name without extention
*/
function getFileName($fileName) {
    $filename = current(explode(".", $fileName));
    return $filename;
}

/*
     * function to validate the template
*/
function templateVaildation($tempXML,$templatePath) {
    $result = array();
    //  echopre($tempXML);
    if($tempXML != '') {	// check the xml exist or not

        // check the template name exist
        if($tempXML->name =='') {
            $result['status'] 	= 'error';
            $result['message'] 	= 'Template Name missing';
            break;
        }

        // check all the files are existing
        foreach($tempXML->files->filedata as $tempFiles) { 
            $fielPath = $templatePath."/".$tempFiles->filename;
            
            if(!file_exists($fielPath)){
                    $result['status'] 	= 'error';
                    $result['message'] 	= 'Files Missing';
            }
            
        }

        // theme checking starts here
        foreach($tempXML->themes as $tempThemes) {
            foreach($tempThemes as $themes) {
                $themeDet = (array)$themes;
                // validations

                unset($themeDet['name']);		// because no file as theme name
                unset($themeDet['currenttheme']);
                unset($themeDet['themecolor']);
                foreach($themeDet as $keyval=>$themeDetails) {  
                    $fielPath = $templatePath."/".$themeDetails;
                    // 	echo '<br>'.$fielPath;
                    if(!file_exists($fielPath)) {
                        $result['status'] 	= 'error';
                        $result['message'] 	= $keyval.'File Missing';
                    }
                }
            }
        } 
        // theme checking ends here

        // check all the images are existing
        if(sizeof($tempXML->images->filename) >0 ) {
	        foreach($tempXML->images->filename as $tempFiles) {
	            $fielPath = $templatePath."/".$tempFiles[0];
	            if(!file_exists($fielPath)) {
	                $result['status'] 	= 'error';
	                $result['message'] 	= 'Images Missing';
	            }
	        }
        }
        // set success if there is no error
        if($result['status']=='') {
            $result['status'] 		= 'success';
            $res 					= (array)$tempXML->watermarking;
            $result['watermarking'] = $res[0];
        }
    }
    else {
        $result['status'] 	= 'error';
        $result['message'] 	= 'Invalid template configuration file';
    }
    return $result;
}




// Function to remove folders and files 
function rrmdir($dir) {
    if (is_dir($dir)) {
        $files = scandir($dir);
        foreach ($files as $file)
            if ($file != "." && $file != "..") rrmdir("$dir/$file");
        rmdir($dir);
    }
    else if (file_exists($dir)) unlink($dir);
}

// Function to Copy folders and files       
function rcopy($src, $dst) {
    if (file_exists ( $dst ))
        rrmdir ( $dst );
    if (is_dir ( $src )) {
        mkdir ( $dst );
        $files = scandir ( $src );
        foreach ( $files as $file )
            if ($file != "." && $file != "..")
                rcopy ( "$src/$file", "$dst/$file" );
    } else if (file_exists ( $src ))
        copy ( $src, $dst );
}


//Function to generate the breadcrumb
function getBreadCrumb($links) {
    if(sizeof($links)>0) {
        $breadcrumb = '';
        $arrow = '';
        $count = 0;
        foreach($links as $title=>$link) {
            $count++;
            if($count==count($links)) $activeClass = "class=bc01-active";
            $breadcrumb.=($link!='')?'<li '.$activeClass.' ><a href="'.BASE_URL.$link.'">'.
                            $arrow.stripslashes($title).'</a></li> ':stripslashes($title);
            $arrow = '<span>&nbsp; &raquo; &nbsp;</span>';
        }
        $breadcrumb = '<div class="bc01"><ul>'.$breadcrumb.'</ul><div class="clear"></div></div>';
    }
    return $breadcrumb;
}

function getCmsData($sectionName) {
    $cmsQuery = "SELECT * FROM tbl_cms WHERE section_name='".$sectionName."' AND section_status=1";
    $cmsRes   = mysql_query($cmsQuery);
    $cmsVal   = mysql_fetch_assoc($cmsRes);
    return $cmsVal;
}

// Sort Arrow Image
function getSortArrow($orderType) {
    if($orderType=='ASC') {
        $sortArrow = 'up-arrow.png';
    }else if($orderType=='DESC') {
        $sortArrow = 'down-arrow.png';
    }
    return $sortArrow;
}

//Date Format
function getDateFormat($dateEntity, $dateFormat="m/d/Y") {
    if($dateEntity!='') {
        return $formatedDate = date($dateFormat,strtotime($dateEntity));
    }
}

function getSettingsValue($fieldName) {
    $sql="SELECT vvalue FROM ".MYSQL_TABLE_PREFIX."lookup WHERE vname='".$fieldName."'";
    $settingRes = mysql_query($sql);
    $settingVal = mysql_fetch_assoc($settingRes);
    return $settingVal['vvalue'];
}


   
    function getAppSettingsValue($fieldName) {
	    $sql="SELECT app_status FROM ".MYSQL_TABLE_PREFIX."editor_apps WHERE app_alias='".$fieldName."'";
	    $settingRes = mysql_query($sql);
	    $settingVal = mysql_fetch_assoc($settingRes);
	    return $settingVal['app_status'];
	}


function getCountries() {
    $sql="SELECT * FROM ".MYSQL_TABLE_PREFIX."country WHERE tc_status='A'";
    $countryRes = mysql_query($sql);
    while($row = mysql_fetch_assoc($countryRes)) {
        $countryVal[] = $row;
    }
    return $countryVal;
}

/*
	 * function to create an alias of the text
*/
function getAlias($alias_text,$replace_type='-') {

    //format alias
    $alias = str_replace("&amp;", "and", $alias_text); 
    $alias = htmlspecialchars_decode($alias, 3); 
    $alias = str_replace($replace_type, " ", $alias);
    $alias = preg_replace("/[^a-zA-Z0-9\s]/", "", $alias);
    $alias = preg_replace('/[\r\n\s]+/xms', ' ', trim($alias));
    $alias = strtolower(str_replace(" ", $replace_type, $alias));
    return strtolower($alias);
}

function getPageLink() {

    $pages = $_SESSION['siteDetails']['pages']; 
    $pagesCount = count($pages); 
    $pageLimit = $pagesCount+1; 
    $pLink = "page_".$pageLimit; 
    return $pLink;
}


/*
	 * function to load the editor application parameters
*/
function getEditorAppParams($appId) {
    $sql 	= "select * from tbl_editor_apps_params where app_id=".$appId." ORDER BY param_id ASC";
    $result = mysql_query($sql) or die(mysql_error());
    return $result;
}


/*
	 * function to redirect the user to dashboard
*/
function redirectLoginUser() {
    if(isset($_SESSION['session_userid']))
        header('location:sitemanager.php');
}

/*
	 * function to filter the unwanted items using DOM
*/
function removeItemsUsingDOM($regquery,$replacecontent) {
    $dom 	= new DOMDocument;
    $dom->loadHTML( $replacecontent );
    $xpath 	= new DOMXPath( $dom );
    $pDivs 	= $xpath->query($regquery);
    foreach ( $pDivs as $div ) {
        $div->parentNode->removeChild( $div );
    }
    $panelData =  preg_replace( "/.*<body>(.*)<\/body>.*/s", "$1", $dom->saveHTML() );
    return $panelData;
}


function getGoogleAnalytics($analyticsid) {

    return "<script type=\"text/javascript\">
    var _gaq = _gaq || [];
    _gaq.push(['_setAccount', '".$analyticsid."']);
    _gaq.push(['_trackPageview']);
    (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
    })();
    </script>";
}


/*
	 * function to generate the other pages
*/
function generatePages($pages,$pagetype) { 
	/*
	$sitePages = $_SESSION['siteDetails']['pages'];
	foreach($sitePages as $tempPages) {
		
		if (in_array("Homepage",  $tempPages)) {
   	 		echo "Got Irix";
		}
	}
	
	

	
	echopre($pages);
 echo "<pre>";
  print_r( $_SESSION['siteDetails']['pages']);
  exit();
  */
  
  
  if(sizeof($pages) > 0) {
    foreach($pages as $page) {
    	$pagename = $page['alias'];
    	$pageTitle = $page['title'];
    	 $pageExist = 0;
    $sitePages = $_SESSION['siteDetails']['pages'];
	foreach($sitePages as $tempPages) {
		
		if (in_array($pagename,  $tempPages)) {
   	 		$pageExist = 1;
		}
	 
	} 
	if($pageExist == 0) {
        //$pageType = 2;
        if($pagetype == 'mainpages') {

            $aliasDet 		= explode('.',$page['link']);
            $fileName		= $aliasDet[0].'_temp.htm';
            //	$pageType = 3;
        }
        else {
            $fileName		= 'subpage_temp.htm';
            //$pageType = 2;
        }
        $templateid = $_SESSION['siteDetails']['templateid'];
        $pageType = getPageType($templateid,$page['alias']);

        if($pageType == '' || $pageType == 2)
            $pageType = 2;


        //	}
        //if($pagename != '') {
         //echo $fileName.':'.$pagename.'<br>';

        $templateid		= $_SESSION['siteDetails']['templateid'];
        $templateThemeId 	= $_SESSION['siteDetails']['themeid'];
        //$_SESSION["session_template_dir"] = 'f7979482a14a7aaec0ca7106a98085ea';
        $templatePath 		= $_SESSION["session_template_dir"] . "/" . $templateid;
        $templateFile           = $_SESSION["session_template_dir"] . "/" . $templateid.'/'.$fileName;

        $fh 			= fopen($templateFile, 'r');
        $theData 		= fread($fh, filesize($templateFile));
        fclose($fh);

        $doc = new DOMDocument();

        $currentPage = getAlias($pagename);

        // add the pages to session
        $arrNewPage 			= array();
        $arrNewPage['title'] 		= $pageTitle;
        $arrNewPage['link'] 		= $currentPage.'.html';
        $arrNewPage['alias'] 		= $currentPage;
        //$arrNewPage['pagetype'] 	= ($currentPage=='index')?'index':'subpage';
        $arrNewPage['pagetype'] 	= $pageType;


        $rowNo = rand(100, 10000);
        $_SESSION['siteDetails']['pages'][$rowNo] = $arrNewPage;


        //$currentPage  = $pageName;

        $sql 	= "Select * from tbl_template_panel where temp_id=".$templateid." AND page_type=".$pageType." ORDER BY panel_id ASC";
//    echo '<br>';

        $result = mysql_query($sql) or die(mysql_error());
        if(mysql_num_rows($result) > 0) {
            while($row = mysql_fetch_assoc($result)) {
                if($_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] == '') {

                    $panelData = $row['panel_html'];

                    // code to add edit menu for image

                    //@$doc->loadHTML($panelData);
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

                    // Add/Replace company name from customize site apge
                    if($_SESSION['siteDetails']['siteInfo']['companyname']!='') {
                        $titleVal = $doc->getElementById('heading');
                        if(isset($titleVal)) {
                            $titleVal->nodeValue = '';
                            $tit = $doc->createDocumentFragment();
                            $tit->appendXML('<span style="font-family:'.$_SESSION['siteDetails']['siteInfo']['compfont'].'; color:'.$_SESSION['siteDetails']['siteInfo']['fntclr'].';font-size:'.$_SESSION['siteDetails']['siteInfo']['fontsize'].'">'.$_SESSION['siteDetails']['siteInfo']['companyname'].'</span>');
                            $titleVal->appendChild($tit);
                        }
                    }

                    // Add/Replace company name from customize site apge
                    if($_SESSION['siteDetails']['siteInfo']['captionname']!='') {
                        $caption = $doc->getElementById('caption');
                        if(isset($caption)) {
                            $caption->nodeValue   = '';
                            $cap            = $doc->createDocumentFragment();
                            $cap->appendXML('<span style=" font-family:'.$_SESSION['siteDetails']['siteInfo']['captionfont'].'; color:'.$_SESSION['siteDetails']['siteInfo']['captfntclr'].';font-size:'.$_SESSION['siteDetails']['siteInfo']['captionfontsize'].'">'.$_SESSION['siteDetails']['siteInfo']['captionname'].'</span>');
                            $caption->appendChild($cap);
                        }
                    }


                    // find the menus in the html
                    $menuExist = 0;
                    $menuNo = 1;
                    $menus 	= $doc->getElementsByTagName('ul');
                    foreach ($menus as $menu) {
                        if($menu->getAttribute('data-type') == 'menu') {
                            $menuId 	= 'editablemenu_'.$row['panel_id'].'_'.$menuNo;

                            /*
                        $menuItems 	= $menu->getElementsByTagName('li');
                        $arrMenuItems = array();
                        foreach($menuItems as $items) {
                            $liTitle 		=  $items->nodeValue;
                            $arrMenuItems[]	= array('title' => $liTitle ,'link' => '');
                        }
                            */
                            // $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'] = $_SESSION['siteDetails']['index']['datatypes']['menu']['editablemenu_1786_1']['items'];

                            //  $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'] = $arrMenuItems;

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
                            //echo "<pre>";
                            //print_r($_SESSION['siteDetails']);
                            $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'] = $_SESSION['siteDetails']['index']['datatypes']['menu'][$homePageMenuId]['items'];
                            // ends the index menu adding section



                            $menu->setAttribute("class", "editablemenu");
                            $menu->setAttribute("id", $menuId);
                            $menuNo++;
                            $menuExist = 1;
                            $f = $doc->createDocumentFragment();
                            $f->appendXML('<div class="manageMenuStyle"><a href="#" data-param="'.$menuId.'" class="jqeditormenusettings">'.EDIT_MENU_TITLE.'</a></div>');
                            $menu->appendChild($f);
                        }
                    }
                    // editable menu section ends


                    // code to find the datatypes in the template
                    $datatypeNo='1';
                    $divList 	= $doc->getElementsByTagName('div');
                    foreach ($divList as $divs) {
                        if($divs->getAttribute('data-type') == 'contactform') {	// contact form checking
                            $contactId 	= 'editablecontact_'.$row['panel_id'].'_'.$datatypeNo;
                            $divs->setAttribute("id", $contactId);
                            $oldClass 	= $divs->getAttribute('class');
                            $divs->setAttribute("class", $oldClass." editablecontactform");
                            $datatypeNo++;

                            $_SESSION['siteDetails'][$currentPage]['datatypes']['contactform'][$contactId]  = '';



                        }		// contact form checking ends here
                        
                        if($divs->getAttribute('data-type') == 'socialshare') {	// socialshare form checking                          
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
                                
                        }
                    }
                    // datatype finding ends here




                    $panelData = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());

                    $_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']] = $panelData;
                    $_SESSION['siteDetails'][$currentPage]['panelpositions'][$row['panel_id']] = array($row['panel_id']);
                }

                $panelContent = $_SESSION['siteDetails'][$currentPage]['panels'][$row['panel_id']];

                //$editLink 	= '<span style="text-align:right;float:right;"> [<a name="600" id="editor/edittemplate.php?panelid='.$row['panel_id'].'&templateid='.$templateid.'" class="modal" href="javascript:void(0);" title="Click to edit the tempplate" >edit</a>]</span>';
                $editLink 	= '<span class="panel_controls" title="Click to edit the tempplate"> <a class="iframe panel_edit" title="Edit Panel Content" href="editor/edittemplate.php?panelid='.$row['panel_id'].'&templateid='.$templateid.'"  > </a></span>';
                $replacer 	= '{$editable'.$row['panel_type'].'}';

                $editContent = '<div class="column" id="column_'.$row['panel_id'].'">
				<div class="dragbox" id="item_'.$row['panel_id'].'" >
				<h4>&nbsp; &nbsp;<span class="configure" >'.$editLink.'</span>'.$row['panel_type'].':'.$row['panel_id'].'</h4>
				<div class="dragbox-content" id="editpanel_'.$row['panel_id'].'" >'.$panelContent.'</div></div> 
				</div>';
                $theData 	= str_replace($replacer,$editContent,$theData);

            }
        }


	}
    }

    }
}





/* function to get the images in the template  */

function recursiveFileList($filePath) {
    $imgs 		= '';
    $images 	= glob("" . $filePath . "*.{gif,jpg}", GLOB_BRACE);
    foreach($images as $image) {
        $imgs[] = "$image";
    }
    $images 	= glob("" . $filePath . "*/*.{gif,jpg}", GLOB_BRACE);
    foreach($images as $image) {
        $imgs[] = "$image";
    }

    return $imgs;
}

/* function to get the pages of the remplate */
function getTemplatePages($templateid) {
    $sqlPages 	= "SELECT * FROM tbl_template_pages WHERE temp_id=".$templateid." AND (page_name !='index.html' AND page_name != 'subpage.html')";
    $resPages = mysql_query($sqlPages) or die(mysql_error());
    if(mysql_num_rows($resPages) > 0) {
        while($rowPages = mysql_fetch_assoc($resPages)) {
        	
        	 
            $aliasDet 		= explode('.',$rowPages['page_name']);
            $alias		= $aliasDet[0];

            $pageLink = $rowPages['page_name'];
            $arrPage[] = array('title'=> $alias,'alias'=> $alias,'link' => $pageLink);
        }
        return $arrPage;
    }
}


/*
 * function to get the page type
*/
function getPageType($tempId,$pageName) {
    $pageNameHtml = $pageName.'.html';
    $pageNameHtm  = $pageName.'.htm';
    $sqlPageType  = "SELECT panel_ref FROM tbl_template_pages WHERE temp_id=".$tempId." AND (page_name ='".$pageNameHtml."' OR page_name = '".$pageNameHtm."')";
    $resPages = mysql_query($sqlPageType) or die(mysql_error());
    if(mysql_num_rows($resPages) > 0) {
        $row = mysql_fetch_array($resPages);
        return $row['panel_ref'];
    }
}

/*
 * function to get the page type name
*/
function getPageTypeName($tempId,$pageTypeId) {
    $sqlPageType  = "SELECT page_name FROM tbl_template_pages WHERE temp_id=".$tempId." AND panel_ref ='".$pageTypeId."'";
    $resPages = mysql_query($sqlPageType) or die(mysql_error());
    if(mysql_num_rows($resPages) > 0) {
        $row = mysql_fetch_array($resPages);
        return $row['page_name'];
    }

}

/*
 * function to get the template file
*/
function getTemplateFile($templateId,$pageType) { 
    
    $templateFileBasePath = $_SESSION["session_template_dir"] . "/" . $templateId;
    if($pageType == 'guestbook') {
        $templateFile 	= $templateFileBasePath.'/subpage_temp.htm';
    }else {
        $pageTypeName    = getPageTypeName($templateId,$pageType);  
        if($pageTypeName!=""){
            $pageTypeNameRaw = explode(".",$pageTypeName);
            $templateFile    = $templateFileBasePath.'/'.$pageTypeNameRaw[0].'_temp.htm';
        }else{
            $templateFile    = $templateFileBasePath.'/subpage_temp.htm';
        }
    }
    return $templateFile;
}


?>