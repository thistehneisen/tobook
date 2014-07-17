<?php 

// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add form items						                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/globalfunctions.php";
include "../language/english_lng_user.php";
 
$pageType 			= $_GET['page'];
$menuname 			= $_GET['menuname'];
$currentPage 		= $_SESSION['siteDetails']['currentpage'];
if($pageType == 1) {
    $menuType 		= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname]['settings']['menutype'];
    $formData 		= $_SESSION['siteDetails'][$currentPage]['apps'][$menuname];
    $userEmail 		= $formData['email'];
    ?>
    
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">
  
  <tr>
    <td><a title="<?php echo FORM_EMAIL;?>" class="masterTooltip" ><?php echo CUSTOM_EMAIL;?></a></td>
    <td><input type="text" value="<?php echo $userEmail;?>" name="txtEmailAddress" maxlength="100" size="20" class="textbox_style1" id="txtEmailAddress"> </td>
  </tr>
  <tr>
    <td valign="top"><a title="<?php echo FORM_FIELDS;?>" class="masterTooltip" ><?php echo CUSTOM_REQUIREDFIELDS;?></td>
    <td>
    <?php 
    global $formFeedBackItems;
    echo '<select size="7" multiple="" class="multitextbox_style1" name="ddlParameters[]" style="width:260px;">';
    foreach($formFeedBackItems as $key=>$items){
    	echo ' <option value="'.$key.'"  '.(array_key_exists($key, $formData['items'])?'selected="selected"':'').'  >'.$items['title'].'</option>';
    }  
    echo '</select>';
    ?>
</td>
  </tr>
  
</table>
<div class="popupeditpanel_ftr">
    <input type="button" name="btn_editor_updateFeedbackForm" id="btn_editor_updateFeedbackForm" value="Update" class="popup_orngbtn right">
    <div class="clear"></div>
</div>

    <?php
}

?>