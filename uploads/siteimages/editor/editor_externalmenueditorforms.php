<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add menu item values							                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/globalfunctions.php";
global $linkOpenTargets;
$pageType = $_GET['page'];
$menuname = $_GET['menuname'];
$currentPage 	= $_SESSION['siteDetails']['currentpage'];
if($pageType == 1) {

    $menuType 		= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname]['settings']['menutype'];

    ?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
    <tr>
        <td valign="middle" align="left">Menu Type</td>
        <td valign="middle" align="left"><select name="menuType" id="menuType" class="select_style1">
                <option value="vertical" <?php echo (($menuType=='vertical')?'selected="selected"':'');?>   >Vertical Menu</option>
                <option value="horizontal" <?php echo (($menuType=='horizontal')?'selected="selected"':'');?>   >Horizontal Menu</option>
            </select> </td>
    </tr>
</table>

<div class="popupeditpanel_ftr">
    <input type="button" name="btn_editor_updateSettings" id="btn_editor_updateSettings" value="Update" class="popup_orngbtn right">
    <div class="clear"></div>
</div>

<br>


    <?php
}
else if($pageType == 2) {	// add menu items

    $buttonText = 'Add';

    // edit action code starts here
    $action 	= $_GET['action'];
    if($action == 'edit') {
        $navid 		= $_GET['navid'];
        //$currentPage = $_SESSION['siteDetails']['currentpage'];
        $navMenu 	= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname]['items'][$navid];

        $menuTitle 	= $navMenu['title'];
        $menuLink	= $navMenu['link'];
        $menuOpentype= $navMenu['opentype'];
        $buttonText = 'Update';
        echo '<input type="hidden" name="txtnavaction" value="edit">';
        echo '<input type="hidden" name="txtnavid" value="'.$navid.'">';
        //$cancelLink = '<a href="" class="loadPage" id="3">Cancel</a>';
        $cancelLink = '<input name="" type="button" class="loadPage popup_greybtn right" id="3" value="Cancel">';

    }
    // edit action code ends here
    //print_r($navMenu);
//echopre($_SESSION['siteDetails']['pages']);
    ?>
<div id="editoraddmenuitems">

    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
        <tr>
            <td valign="middle" align="left">Menu Title </td>
            <td valign="middle" align="left"><input type="text" name="txtaddmenutitle" id="txtaddmenutitle" class="textbox_style1" value="<?php echo $menuTitle;?>"></td>
        </tr>
        <!--tr>
            <td valign="middle" align="left">Menu Link</td>
            <td valign="middle" align="left"><input type="text" name="txtaddmenulink" id="txtaddmenulink" class="textbox_style1" value="<?php echo $menuLink;?>"></td>
        </tr-->
        <tr>
            <td valign="middle" align="left">Menu Link</td>
            <td valign="middle" align="left">
                <select name="txtaddmenulink" id="txtaddmenulink" class="select_style1">
                   <?php foreach($_SESSION['siteDetails']['pages'] as $page)
                    echo '<option value="'.$page['link'].'" '.(($page['link']==$menuLink)?'selected="selected"':'').'>'.$page['link'].'</option>';
                    ?>
                </select>
            </td>
        </tr>
        <tr>
            <td valign="middle" align="left">Open on</td>
            <td valign="middle" align="left">
                <select name="selmenuopentype" id="selmenuopentype" class="select_style1">
                <?php foreach($linkOpenTargets as $key=>$items)
                    echo '<option value="'.$items.'" '.(($items==$menuOpentype)?'selected="selected"':'').'>'.$items.'</option>';
                ?>
                </select>
            </td>
        </tr>
    </table>

    <div class="popupeditpanel_ftr">
        <input type="button" name="btn_editor_addmenuitems" id="btn_editor_addmenuitems" class="popup_orngbtn right" value="<?php echo $buttonText;?>">
            <?php echo $cancelLink;?>
        <div class="clear"></div>
    </div>
    <div class="clear"></div>
</div>
    <?php

}
else if($pageType == 3)		// view  menu items
{
    //$currentPage 	= $_SESSION['siteDetails']['currentpage'];
    $navMenu 		= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname]['items'];
    if (sizeof($navMenu) > 0) {
        ?>
<div id="editorviewmenuitems">
    <!--div class="edtrnavmenuitems">
 				<div class="edtrnavmenuitemscols">Link Title</div>
 				<div class="edtrnavmenuitemscols">Link</div>
 				<div class="edtrnavmenuitemscols">Target</div>
 				<div class="edtrnavmenuitemscols">Actions</div>
				<div class="clear"></div>
 			</div-->
            <?php
            foreach($navMenu as $key=>$element) {

                echo '<div class="edtrnavmenuitems" id="navid_'.$key.'">';
                echo '<div class="edtrnavmenuitemscols">'.$element['title'].'</div>';
                echo '<div class="edtrnavmenuitemscols">'.$element['link'].'</div>';
                echo '<div class="edtrnavmenuitemscols">'.$element['opentype'].'</div>';
                echo '<div class="edtrnavmenuitemscols alright"> <a href="" id="'.$key.'" class="editnavmenuitems">Edit</a> | <a href="" id="'.$key.'" class="deletenavmenuitems">Delete</a></div>';
                echo '<div class="clear"></div>';
                echo "<div>";
            }
        }
        else {
            echo 'No menus added. <a href="" class="loadPage" id="2">Add menu</a>';
        }?>
</div>
    <?php

}



?>