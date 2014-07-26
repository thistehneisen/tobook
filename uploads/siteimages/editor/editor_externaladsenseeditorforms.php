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
    $google_ad_client 	= $formData['google_ad_client'];
    $google_ad_slot 	= $formData['google_ad_slot'];
    $google_ad_dimension  	= $formData['google_ad_dimension'];
    //$google_ad_width  	= $formData['google_ad_width '];
    //$google_ad_height 	= $formData['google_ad_height'];
    ?>
 
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="pageadd_tbl">

    <tr>
        <td width="200"><a title="<?php echo GOOGLE_VALID_AD_ID;?>" class="masterTooltip" ><?php echo GOOGLE_AD_ID;?></a> <span style="color:red">*</span></td>
        <td><input type="text" value="<?php echo $google_ad_client;?>" name="google_ad_client" maxlength="100" size="20" class="textbox_style1">
       <span class="helptext"> <?php echo GOOGLE_AD_CID;?> </span>
        
         </td>
    </tr>
    <tr>
        <td><a title="<?php echo GOOGLE_VALID_SLOT;?>" class="masterTooltip" ><?php echo GOOGLE_AD_SLOT;?></a> <span style="color:red">*</span></td>
        <td><input type="text" value="<?php echo $google_ad_slot;?>" name="google_ad_slot" maxlength="100" size="20" class="textbox_style1">
        
         <span class="helptext"> <?php echo GOOGLE_AD_SLOTNO;?> </span> </td>
    </tr>
    
    <tr>
        <td><a title="<?php echo GOOGLE_VALID_ADSENSE;?>" class="masterTooltip" ><?php echo GOOGLE_AD_DIMENSION;?></a></td>
        <td>
                <?php
                global $adSenseDimensionsArray;
                echo '<select class="select_style1" name="google_ad_dimension" >';
                foreach($adSenseDimensionsArray as $key=>$val) {
  if($google_ad_dimension==$key)
                $selected = "selected=selected";
           else
               $selected = "";

                    echo ' <option value="'.$key.'"  '.$selected.'  >'.$val['name']." (".$val['width']." X ".$val['height'].")".'</option>';
                }
                echo '</select>';

                ?>
        </td>
    </tr>
    
</table>
<div class="popupeditpanel_ftr">
    <input type="button" name="btn_editor_updateAdsense" id="btn_editor_updateAdsense" value="Update" class="popup_orngbtn right">
    <div class="clear"></div>
</div>

    <?php
}

?>