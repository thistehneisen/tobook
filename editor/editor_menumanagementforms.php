<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add menu item values							                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php";
$action = $_GET['action'];  
if(isset($action) && $action == 'addmenu') {	// add new page
    $buttonText = 'Add Item';
    $menuName = $_GET['id'];
	$linkMode	= 'internal';
    if($_GET['type']=='edit') {
        $menuName 		= $_GET['item'];
        $menuId 		= $_GET['menu'];
        $currentPage 	= $_SESSION['siteDetails']['currentpage'];
        $menuDetails 	= $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuName]['items'][$menuId];
        $title 			= $menuDetails['title'];
        $link 			= $menuDetails['link'];
        $linkMode       = $menuDetails['linkmode'];
        $buttonText 	= 'Update';
         
        echo '<input type="hidden" name="txtpageaction" value="edit">';
        echo '<input type="hidden" name="txtMenuName" value="'.$menuName.'">';
        echo '<input type="hidden" name="txtMenuItem" value="'.$menuId.'">';
        echo '<input type="hidden" name="linkMode" value="'.$linkMode.'">';
        $cancelLink = '<a href="" class="jqloadPage" id="viewmenu">Cancel</a>';
    }

    ?>
<div id="editoraddmenuitems">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
        <tr>
            <td valign="middle" align="left"><?php echo EDITOR_MENU_LABEL;?> </td>
            <td valign="middle" align="left"><input type="text" name="txttitle" id="txttitle" class="textbox_style1" value="<?php echo $title;?>"></td>
        </tr>
        <tr>
            <td valign="middle" align="left"><?php echo EDITOR_LINK_TO;?></td>
            <td valign="middle" align="left"> 
                <div id="jqMenuPagelist" <?php echo (($linkMode=='external')?'style="display:none;"':''); ?> >
                External Link <input type="checkbox" value="1" id="jqChkextlink" <?php echo (($linkMode=='external')?'checked="checked"':''); ?> >  <br>
                    <select name="pagelinklist"   class="select_style1">
                            <?php foreach($_SESSION['siteDetails']['pages'] as $pages) { ?>
                        <option  <?php echo (($link==$pages['link'])?'selected="selected"':''); ?> value="<?php echo $pages['link'] ?>"><?php echo ucwords($pages['title']);?></option>
                                <?php } ?>
                        
                    </select>
               		<br><br>

               	</div>
               	<div id="jqMenuExtBox" <?php echo (($linkMode=='internal')?'style="display:none;"':''); ?> >
					
                    <input type="text" name="txtlink" id="txtlink" class="textbox_style1" value="<?php //echo $link;?>" placeholder="http://www.example.com"> <br>
                    <a href="#" id="jqCancelextlink"><?php echo EDITOR_POP_CANCEL;?></a>
                </div>
            </td>
        </tr>
    </table>

    <input type="hidden" name="txtmenuname" value="<?php echo $menuName;?>">
    
     <div class="popupeditpanel_ftr">
    
                <input type="button" name="btn_editor_addmenuitem" id="btn_editor_addmenuitem" value="<?php echo $buttonText;?>" class="popup_orngbtn right">
              </div>
    
    
    
        <?php echo $cancelLink;?>
</div>
    <?php
}
else if(isset($action) && $action == 'viewmenu'){ // view  menu items

    $menuId 		= $_GET['id'];
    $currentPage 	= $_SESSION['siteDetails']['currentpage'];
    if(isset($_SESSION['siteDetails'][$currentPage]['datatypes'])){
    	
        $menuItems 	= $_SESSION['siteDetails'][$currentPage]['datatypes']['menu'][$menuId]['items'];
         
        // code to get the index page menu items
        $menuDets 	= explode('_',$menuId);
        $curPanelId = $menuDets[1];
        $panelDet = mysql_query('select temp_id,panel_type from tbl_template_panel where panel_id='.$curPanelId) or die(mysql_error());
        $rowPanel 		= mysql_fetch_assoc($panelDet);
		$tempPanel 			= $rowPanel['panel_type'];
		$tempId 			= $rowPanel['temp_id'];
		//echo "SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type =1<br>";
 		$homePanelDet 		= mysql_query("SELECT panel_id FROM tbl_template_panel WHERE panel_type='".$tempPanel."' AND temp_id=".$tempId." AND page_type =1") or die(mysql_error());
		$rowHomePanelDet 	= mysql_fetch_assoc($homePanelDet);
 		$homePanelId 		= $rowHomePanelDet['panel_id'];

 		$homePageMenuId = $menuDets[0].'_'.$homePanelId.'_'.$menuDets[2];
        $menuItems 	= $_SESSION['siteDetails']['index']['datatypes']['menu'][$homePageMenuId]['items'];
      
         
         
         
        
    }
    else
        $menuItems = '';
    // echo "<pre>";
    // print_r($menuItems);
    if (sizeof($menuItems) > 0) {
        ?>
<div id="editorviewmenuitems">
    <div class="edtrnavmenuitems edtrmenumanagenavrow">
        <div class="edtrnavmenuitemscols"><strong><?php echo EDITOR_LABEL;?></strong></div>
        <div class="edtrnavmenuitemscols"><strong><?php echo EDITOR_LINK_TO;?></strong></div>
         <div class="edtrnavmenuitemscols">&nbsp;</div>
        <div class="edtrnavmenuitemscols"><strong><?php echo EDITOR_ACTIONS;?></strong></div>
       
    </div>
            <?php
            if($menuItems){
                foreach($menuItems as $key=>$element) {
                    echo '<div class="edtrnavmenuitems edtrmenumanagenavrow" id="menuid_'.$key.'">';
                    echo '<div class="edtrnavmenuitemscols">'.$element['title'].'</div>';
                    echo '<div class="edtrnavmenuitemscols">'.(($element['link']!='')?$element['link']:'--Not Added--').'</div>';
                    echo '<div class="edtrnavmenuitemscols">&nbsp; </div>';
                    echo '<div class="edtrnavmenuitemscols flright"> <a href="" id="'.$key.'" class="editmenudetails">Edit</a> | <a href=""   class="" onclick="return deleteMenuItem('.$key.')">Delete</a></div>';
                    echo "</div>";
                }
            }
        }

        else {
            echo 'No menu items added. <a href="" class="jqloadPage" id="addmenu">Add menu item</a>';
        }?>
</div>
    <?php  	}  	?>