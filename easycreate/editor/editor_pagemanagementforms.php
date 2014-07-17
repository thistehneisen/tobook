<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add menu item values							                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";

$action = $_GET['action'];
$title = '';
$cancelLink = '';
if($action == 'addpage') {	// add new page
    $buttonText = 'Add';

    if(isset($_GET['type']) && $_GET['type']=='edit') {
        // echo "<pre>";
        //print_r($_GET);
        $page = $_GET['page'];

        $pageDetails = 	$_SESSION['siteDetails']['pages'][$page];
        //echo "<pre>";
        //print_r($pageDetails);
        $title = $pageDetails['title'];
        //$link = $pageDetails['link'];
        $buttonText = 'Update';

        echo '<input type="hidden" name="txtpageaction" value="edit">';
        echo '<input type="hidden" name="txtpageid" value="'.$page.'">';
        //$cancelLink = '<a href="" class="jqloadPage" id="viewpage"></a>';
        $cancelLink ='<input name="" type="button" value="Cancel" class="jqloadPage popup_greybtn right" id="viewpage">';
    }

    ?>
<div id="editoraddmenuitems">
    <table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
        <tr>
            <td valign="middle" align="left">Page Title <span style="color:red;">*</span> </td>
            <td valign="middle" align="left">
                <input type="text" class="textbox_style1" name="txtaddpagename" id="txtaddpagename" value="<?php echo $title;?>">
            </td>
        </tr>
        <!--tr>
            <td valign="middle" align="left">Page Link</td>
            <td valign="middle" align="left"><input class="textbox_style1" type="text" name="txtaddpagelink" id="txtaddpagelink" value="<?php echo $link;?>"></td>
        </tr-->
        <tr>
            <td valign="middle" align="left">Page Type <span style="color:red;">*</span></td>
            <td valign="middle" align="left">
                <select class="select_style1" name="pagetype" id="pagetype">
                    <option value="2"> Sub Page</option>
                     <option value="guestbook">Guest Book</option>
                    <!--option value="contact"> Contact</option-->
                </select>
            </td>
        </tr>
    </table>

    <div class="popupeditpanel_ftr">
        <input type="button" name="btn_editor_addpage" id="btn_editor_addpage" class="popup_orngbtn right" value="<?php echo $buttonText;?>">
            <?php echo $cancelLink;?>
        <div class="clear"></div>
    </div>
</div>

<?php

}
else if($action == 'viewpage'){ // view  menu items
    //echo "<pre>";
    //print_r($_SESSION['siteDetails']['pages']);

    $currentPage 	= $_SESSION['siteDetails']['currentpage'];
    $pageList 		= $_SESSION['siteDetails']['pages'];
    if (sizeof($pageList) > 0) {
?>
<div id="editorviewmenuitems">

    <!--div class="edtrnavmenuitems">
 				<div class="edtrnavmenuitemscols">Page</div>
 				<div class="edtrnavmenuitemscols">Link</div>
 				<div class="edtrnavmenuitemscols">Alias</div>
 				<div class="edtrnavmenuitemscols ">&nbsp;&nbsp; &nbsp;Actions</div>
				<div class="clear"></div>
            </div-->
            <?php
            foreach($pageList as $key=>$element) {
                //echo "<pre>";
                //print_r($element);
                echo '<div class="edtrnavmenuitems" id="pageid_'.$key.'">';
                echo '<div class="edtrnavmenuitemscols">'.$element['title'].'</div>';
                echo '<div class="edtrnavmenuitemscols">'.$element['link'].'</div>';
                //echo '<div class="edtrnavmenuitemscols">'.$element['alias'].'</div>';

                echo '<div class="edtrnavmenuitemscols alright right"> <a href="" id="'.$key.'" class="editpagedetails">Edit</a> | <a href=""   class="" onclick="return deletePage('.$key.')">Delete</a></div>';
                echo '<div class="clear"></div>';
                echo "</div>";
            }

        }
        else {
            echo 'No pages added. <a href="" class="jqloadPage" id="addpage">Add page</a>';
        }?>

    <div>
        <br>
        <input name="" type="button" value="Cancel" class="popup_greybtn right" style="margin-right:0; ">
        <div class="clear"></div>
    </div>
</div>

    <?php
}
?>
