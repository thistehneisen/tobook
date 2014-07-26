<?php 
/*
 *  page to update the panel of a template
 */

include "../includes/session.php";
include "../includes/config.php"; 
include "editor_functions.php";
$currentPage 	= $_SESSION['siteDetails']['currentpage'];

if($_POST['type']=='plain'){
   $panelid =  $_POST['panelId'];
   $templateid = $_POST['templateId'];
   $panelhtml = stripslashes($_POST['content']); 
}else{
    extract($_POST);
}

$doc 	= new DOMDocument();
 
if($panelid != '' && $templateid != '' && $panelhtml != ''){  
	
	$templateid = $_SESSION['siteDetails']['siteInfo']['templateid'];
	$templateThemeId = $_SESSION['siteDetails']['siteInfo']['themeid'];
 	$tempThemeCss = getThemeCss($templateid,$templateThemeId);

 	$pattern = '/<link(.*?)>/';
        $panelhtml = preg_replace($pattern,'', $panelhtml);
        $pattern2 = '/<div class="manageMenuStyle"(.*?)[^:]*<\/a><\/div>/';
        $panelhtml = preg_replace($pattern2,'', $panelhtml); 
        
        @$doc->loadHTML(mb_convert_encoding($panelhtml, 'HTML-ENTITIES', 'UTF-8'));
        
        $menus 	= $doc->getElementsByTagName('ul'); 
        $menuTitle = array();
        foreach ($menus as $menu) {
            if($menu->getAttribute('class') == "editablemenu") {

                // TODO - "1" below represents menu count. Right now we have only one menu, we are hardcoding it as 1.
                $menuId = "editablemenu_".$panelid."_1";

                $links = $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'];

                if($menu->getAttribute('id') == $menuId) {
                    $liData = $doc->getElementsByTagName('a');
                    foreach ($liData as $li) {
                        $menuTitle[] =  $li->nodeValue;
                    }
                }
            }
        }

        $i=0;
        $arrNewItem[$i]['title'] = array();
        foreach($menuTitle as $mTitle){
            $arrNewItem[$i]['title'] = $mTitle;
            $arrNewItem[$i]['link'] = $links[$i]['link'];
            $i++;
        }
        

    if($arrNewItem[0]['title']!=""){
        // Updating datatype menu array
        $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'] = $arrNewItem;

        // Append Manage Menu with the panel content
        @$doc->loadHTML(mb_convert_encoding($panelhtml, 'HTML-ENTITIES', 'UTF-8'));
        $menus 	= $doc->getElementsByTagName('ul');
        foreach ($menus as $menu) {
            if($menu->getAttribute('data-type') == 'menu') {
                $menuid = $menu->getAttribute('id');
                $menuDet = $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuid]['items'];
                $menuValue = '<ul id="'.$menuid.'" class="editablemenu" data-type="menu">';
                if($menuDet) {
                    foreach($menuDet as $items) {
                        $menuValue .= '<li><a href="'.$items['link'].'">'.$items['title'].'</a></li>';
                    }
                }
                $menuValue .= '<div class="manageMenuStyle"><a class="jqeditormenusettings" data-param="'.$menuid.'" href="#">'.EDIT_MENU_TITLE.'</a></div></ul> ';
                $pattern = '/<ul (.*?)id="'.$menuid.'"[^:]*<\/ul>/';
                $panelhtml = preg_replace($pattern,$menuValue, $panelhtml);
            }
        }
        // Append Manage Menu with the panel content
    }

    // Updating panel data containing menu
    $_SESSION['siteDetails'][$currentPage]['panels'][$panelid]= $panelhtml;
    $titleVal = $doc->getElementById('heading'); 
    $captionVal = $doc->getElementById('caption'); 
    $_SESSION['siteDetails']['siteInfo']['companyname'] = $titleVal->textContent;
    $_SESSION['siteDetails']['siteInfo']['captionname'] = $captionVal->textContent;
    
    if($arrNewItem[0]['title']!=""){
        updateMenuWithSession($currentPage,$menuId);
    }
    echo "1"."**".$panelhtml;
}
else
    echo 0;

?>