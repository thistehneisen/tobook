<?php
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add external navigational menu elements to session						                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include_once "../includes/config.php";
include 'editor_functions.php';
//include "../includes/globalfunctions.php";


$type = $_GET['type'];
if($type == 1) {   //echopre1($_POST); // adding new page
    $menuTitle		= $_POST['txttitle'];
    $extLink		= $_POST['txtlink'];
    $pageLink		= $_POST['pagelinklist'];
    $linkMode           = $_POST['linkMode'];

    if($extLink != ''){	// check the external link or not
        $menuLink	= $extLink;
        $linkMode       = 'external';
    }else{
        $menuLink	= $pageLink;
        $linkMode       = 'internal';
    }

    $menuName		= $_POST['txtmenuname'];

    // validation
    if($menuTitle != '' && $menuLink != '' ) {
        $arrNewItem 			= array();
        $arrNewItem['title'] 		= $menuTitle;
        $arrNewItem['link'] 		= $menuLink;
        $arrNewItem['linkmode'] 	= $linkMode;

        if($_POST['txtpageaction'] == 'edit' && $_POST['txtMenuName'] != '' && $_POST['txtMenuItem'] != '') {		// editing the menu item
            $menuName 		= $_POST['txtMenuName'];
            $menuItem 		= $_POST['txtMenuItem'];
            $currentPage 	= $_SESSION['siteDetails']['currentpage'];
            $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuName]['items'][$menuItem] = $arrNewItem;
            updateMenuWithSession($currentPage,$menuName);
            echo "editsuccess";
        }
        else {	// adding new menu item

            // adding new menu item
            $rowNo 			= time();
            $currentPage 	= $_SESSION['siteDetails']['currentpage'];
            $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuName]['items'][$rowNo] = $arrNewItem;
            updateMenuWithSession($currentPage,$menuName);
            echo "success";
        }
    }
}
else if($type == 2) {

    $menuName 		= $_GET['menuname'];
    $menuItem 		= $_GET['menuitem'];
    $currentPage 	= $_SESSION['siteDetails']['currentpage'];
    if($menuName != '' && $menuItem != '') {
        unset($_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuName]['items'][$menuItem]);
        updateMenuWithSession($currentPage,$menuName);
        echo '<span class="success">Successfully deleted the menu item</span>';
    }
    else echo "error..";

    exit();
}	


/*
* function to update the session value with the menu items
*/

function updateMenuWithSessionBkp($curPage,$menuid) {

    $panelDet 	= explode('_',$menuid);
    $panelId 	= $panelDet[1];

    $sitePages = $_SESSION['siteDetails']['pages'];

    $newPanelData = $_SESSION['siteDetails'][$curPage]['panels'][$panelId];

    // generate new menu items
    $menuItems 		= $_SESSION['siteDetails'][$curPage]['datatypes']['menu'][$menuid]['items'];

    foreach($menuItems as $key=>$element) {
        $menuHtml .= '<li><a href="'.$element['link'].'">'.$element['title'].'</a></li>';
    }
    $menuHtml .= '<a href="#" data-param="'.$menuid.'" class="jqeditormenusettings">'.EDIT_MENU_TITLE.'</a>';
    $newMenu = $ulStart.$menuHtml.$ulEnd;


    $pattern 	= '/<ul (.*?)id="'.$menuid.'"[^:]*<\/ul>/';
    //$html=preg_replace ('\<ul(.*?)\</ul>', 'bla bla', $html);
    $html 		= preg_replace($pattern, $newMenu, $newPanelData);


    $sql 	= "Select temp_id,panel_type from tbl_template_panel where panel_id=".$panelId." ";
    $result = mysql_query($sql) or die(mysql_error());
    if(mysql_num_rows($result) > 0) {
        $row = mysql_fetch_assoc($result);
        $temp_id = $row['temp_id'];
        $panel_type = $row['panel_type'];


        $sqlPanels 	= "Select panel_id from tbl_template_panel where temp_id=".$temp_id." AND panel_type='".$panel_type."' AND  panel_id !=".$panelId."  ";
        $resPanels = mysql_query($sqlPanels) or die(mysql_error());
        if(mysql_num_rows($resPanels) > 0) {
            while($rowPanel = mysql_fetch_assoc($resPanels)) {
                $otherPanelIds = $rowPanel['panel_id'];
                //echopre($rowPanel);
                foreach($sitePages as $pages) {
                    $pageName = $pages['alias'];

                    $_SESSION['siteDetails'][$pageName]['panels'][$otherPanelIds] = $html;
                }
            }
        }

    }


    include 'editor_functions.php';

    if($curPage != '' && $menuid != '') {
        $panelDet 	= explode('_',$menuid);
        $panelId 	= $panelDet[1];
        $panelData 	= $_SESSION['siteDetails'][$curPage]['panels'][$panelId];
        //echo "<pre>";
        //print_r($panelDet);
        // create ul params
        $doc 	= new DOMDocument();
        @$doc->loadHTML($panelData);
        $menus 	= $doc->getElementsByTagName('ul');
        foreach ($menus as $menu) {
            if($menu->getAttribute('id') == $menuid) {
                $uldataType = $menu->getAttribute('data-type');
                $ulClass 	= $menu->getAttribute('class');
                $ulStart 	= '<ul data-type="'.$uldataType.'" class="'.$ulClass.'" id="'.$menuid.'">';
                $ulEnd  	= '</ul>';
            }
        }

        // generate new menu items
        $menuItems 		= $_SESSION['siteDetails'][$curPage]['datatypes']['menu'][$menuid]['items'];
        foreach($menuItems as $key=>$element) {
            $menuHtml .= '<li><a href="'.$element['link'].'">'.$element['title'].'</a></li>';
        }
        $menuHtml .= '<a href="#" data-param="'.$menuid.'" class="jqeditormenusettings">'.EDIT_MENU_TITLE.'</a>';
        $newMenu = $ulStart.$menuHtml.$ulEnd;


        $pattern 	= '/<ul (.*?)id="'.$menuid.'"[^:]*<\/ul>/';
        //$html=preg_replace ('\<ul(.*?)\</ul>', 'bla bla', $html);
        $html 		= preg_replace($pattern, $newMenu, $panelData);


        $_SESSION['siteDetails'][$curPage]['panels'][$panelId] = $html;

        //include_once "../includes/config.php";

        // call the function to update in all pages
        //synchronizePanel($panelId,$curPage);
        //echo "<pre>";
        /// print_r($_SESSION['siteDetails'][$curPage]['panels'][$panelId]);
    }

}



?>