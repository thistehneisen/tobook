<?php 
 
//function for editors
 
/*
 * this function is to synchronize the panels
 */
 

function synchronizePanel($panelId,$currentPage) {
	$curPanelId 		= $panelId;
	 
    $panelDet 			= mysql_query('select temp_id,panel_type,page_type from tbl_template_panel where panel_id='.$curPanelId) or die(mysql_error());
    $rowPanel 			= mysql_fetch_assoc($panelDet);
	$tempPanel 			= $rowPanel['panel_type'];
	$tempId 			= $rowPanel['temp_id'];
	$page_type  		= $rowPanel['page_type'];
	 
	if($tempPanel == 'topmenu') {
		if($page_type == 2) {	// the panel is subpage panel
			$homePanelDet 		= mysql_query("SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type = 1") or die(mysql_error());
			$rowHomePanelDet 	= mysql_fetch_assoc($homePanelDet);
	 		$homePanelId 		= $rowHomePanelDet['panel_id'];
	 		//echo "<pre>";
	 		//echo $currentPage;
	 		//print_r($_SESSION['siteDetails'][$currentPage]['panels'] );
	  		$_SESSION['siteDetails']['index']['panels'][$homePanelId] = $_SESSION['siteDetails'][$currentPage]['panels'][$homePanelId];
		
		}
		$homePanelDet 		= mysql_query("SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type =2") or die(mysql_error());
		$rowHomePanelDet 	= mysql_fetch_assoc($homePanelDet);
	 	$homePanelId 		= $rowHomePanelDet['panel_id'];
	 	
		$sitePages = $_SESSION['siteDetails']['pages'];
	//	echo $currentPage.":".$homePanelId.'<br>';
		foreach($sitePages as $key=>$pages){
			$page = $pages['alias'];
		 	$_SESSION['siteDetails'][$page]['panels'][$homePanelId] = $_SESSION['siteDetails'][$currentPage]['panels'][$homePanelId];
		 //	echo $page.":".$homePanelId.'<br>';
		 	
		 	
		 	
		 	/*
		 	// synchronize the datatytpe value
		 	$panelData 	= $_SESSION['siteDetails'][$page]['panels'][$homePanelId];
	   		$doc 	= new DOMDocument();
	   		@$doc->loadHTML($panelData);
	   		    $menus 	= $doc->getElementsByTagName('ul');
	   		       foreach ($menus as $menu) {
                    if($menu->getAttribute('data-type') == 'menu') {
                    	$menuid = $menu->getAttribute('id');
                    	 $menuItems 	= $menu->getElementsByTagName('li');
                        $arrMenuItems = array();
                        
                      
                        foreach($menuItems as $items) {
                            $liTitle 		=  $items->nodeValue;
                            
                            $arrMenuItems[]	= array('title' => $liTitle ,'link' => $newpage.'.html');
                             
                        }
                        $_SESSION['siteDetails'][$page]['datatypes']['menu'][$menuid]['items'] = $arrMenuItems;
               
                    }
	   		       }
		 	
	 */
		 	
		 	
		 	
		 	
		}
	}
	
  
	 
}
 


/*
 * function to get the site theme
 */

function getThemeCss($templateid,$templateThemeId){
	// find active theme of the template
	$actTheme = mysql_query('select theme_style from tbl_template_themes where temp_id='.$templateid.' and theme_default=1') or die(mysql_error());
    if(mysql_num_rows($actTheme) > 0) {
    	$rowActTheme 	= mysql_fetch_assoc($actTheme);
    	$defTheme 		= $rowActTheme['theme_style'];
    }
 	$themeResult = mysql_query('select theme_style from tbl_template_themes where theme_id='.$templateThemeId) or die(mysql_error());
    if(mysql_num_rows($themeResult) > 0) {
		$rowTheme 		= mysql_fetch_assoc($themeResult);
        $themeCss 		= $rowTheme['theme_style'];
        $themeStyleUrl 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/'.$themeCss;
       // $theData 		= str_replace($defTheme,$themeStyleUrl,$theData);
       $themeStyle = '<link type="text/css" rel="stylesheet" href="../'.$themeStyleUrl.'" data-type="sitestyle">';
        
    }
    return $themeStyle;
}




// function to update the session value with the menu items

function updateMenuWithSession($curPage,$menuid) {

    $templateId  = $_SESSION['siteDetails']['templateid'];
    $sitePages   = $_SESSION['siteDetails']['pages'];

    if($curPage != '' && $menuid != '') {
       $panelDet 	= explode('_',$menuid);
       $panelKeyId 	= $panelDet[1];

       $sqlPanelType    = "SELECT panel_type FROM tbl_template_panel
                           WHERE temp_id = '".$templateId."' AND panel_id='".$panelKeyId."'";
       $resultPanelType = mysql_query($sqlPanelType) or die(mysql_error());
       $valPanelType    = mysql_fetch_assoc($resultPanelType) or die(mysql_error());
       $panelType       = $valPanelType['panel_type'];

        $panelKeyData 	= $_SESSION['siteDetails'][$curPage]['panels'][$panelKeyId];
        $doc 	= new DOMDocument();
        @$doc->loadHTML($panelKeyData);
        $menus 	= $doc->getElementsByTagName('ul');
        foreach ($menus as $menu) {
            if($menu->getAttribute('id') == $menuid) {
                $uldataType = $menu->getAttribute('data-type');
                $ulClass 	= $menu->getAttribute('class');
                $ulStart 	= '<ul data-type="'.$uldataType.'" class="'.$ulClass.'" id="'.$menuid.'">';
                $ulEnd  	= '</ul>';
            }
        }
        // Generate new menu items
        $menuItems = $_SESSION['siteDetails'][$curPage]['datatypes']['menu'][$menuid]['items']; 

        foreach($menuItems as $key=>$element) {
            $menuHtml .= '<li><a href="'.$element['link'].'" linkmode="'.$element['linkmode'].'">'.$element['title'].'</a></li>';
        }
        $menuHtml .= '<div class="manageMenuStyle"><a href="#" data-param="'.$menuid.'" class="jqeditormenusettings">'.EDIT_MENU_TITLE.'</a></div>';
        $newMenu = $ulStart.$menuHtml.$ulEnd;


        foreach($sitePages as $page){

            $pageType = $page['pagetype'];
            if($pageType == "guestbook") $pageType = 2;

            $sql    = "SELECT * FROM tbl_template_panel
                       WHERE temp_id = '".$templateId."' AND page_type='".$pageType."' AND panel_type='".$panelType."'";
            $result = mysql_query($sql) or die(mysql_error());
            if(mysql_num_rows($result) > 0) {
                while($row = mysql_fetch_assoc($result)){
                    $panelId = $row['panel_id'];

                    // TODO - "1" below represents menu count. Right now we have only one menu, we are hardcoding it as 1.
                    $menuNewId = "editablemenu_".$panelId."_1";

                    // Updating datatype menu array
                    $_SESSION['siteDetails'][$page['alias']]['datatypes']['menu'][$menuNewId]['items'] = $menuItems;

                    // Updating panel data containing menu
                    $panelData 	= $_SESSION['siteDetails'][$page['alias']]['panels'][$panelId];


                    $newPageMenu =  str_replace($menuid, $menuNewId, $newMenu);

                    $pattern 	= '/<ul(.*?)id="'.$menuNewId.'"[^:]*<\/ul>/';
                    $html 	= preg_replace($pattern, $newPageMenu, $panelData);

                    $_SESSION['siteDetails'][$page['alias']]['panels'][$panelId] = $html;
                
                }
            }
        }
    }

}
 
?>