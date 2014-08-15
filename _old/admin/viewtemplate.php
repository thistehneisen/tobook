<?php
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
if(file_exists('language/english_lng_admin.php')) {
	include 'language/english_lng_admin.php';
}
else {
	include '../language/english_lng_admin.php';
}

$templateid     =  $_GET['id'];
$theme_id       =  $_GET['tid'];
$redirect_type  =  $_GET['type'];
$qry            = "select t.ntemplate_mast,t.temp_name,t.vtype,c.ncat_id,c.vcat_name,c.vcat_desc from tbl_template_mast t 
                    left join tbl_template_category c on t.ncat_id=c.ncat_id where t.ntemplate_mast='" . addslashes($templateid) . "'";
$rs             =   mysql_query($qry);
$row            =   mysql_fetch_array($rs);

//echo "<pre>";
//print_r($row);


$sql_theme      =   "SELECT * FROM  tbl_template_themes  WHERE temp_id=" . $templateid . " AND theme_id=".$theme_id." AND theme_status='1' ORDER BY theme_id";
$rs_theme       =   mysql_query($sql_theme);
$row_theme      =   mysql_fetch_array($rs_theme);

//echopre($row_theme);
?>
<div class="tpl-close"><a href="javascript:void(0);" onclick="closePopup('300');"><img src="../themes/Coastal-Green/close.png"></a></div>
<div style="height:600px;overflow:auto;">
    <table  align="center" class="template-pop">
        <tr>
            <td>
                <div class="tpl-preview">
                    <h6 class="left">
                    
                     <?php echo $row['temp_name']; ?>
                    </h6>
                    <div class="color-picker01 right">
                        <?php
                        $sql_template_theme = "SELECT * FROM  tbl_template_themes  WHERE temp_id=" . $templateid . " AND theme_status='1' ORDER BY theme_id";
                        $res_template_theme = mysql_query($sql_template_theme);
                        if (mysql_num_rows($res_template_theme) > 1) {
                            $cnt = 1;
                            while ($row_template_theme = mysql_fetch_array($res_template_theme)) {
                                ?>
                         <a href="javascript:void(0);" onclick="changehiddenvalue(<?php echo $templateid; ?>,<?php echo $row_template_theme['theme_id'] ?>);changehomeimagead(<?php echo $templateid; ?>,'home','<?php echo $row_template_theme['theme_image_home'] ?>'); changesubimagead(<?php echo $templateid; ?>,'sub','<?php echo $row_template_theme['theme_image_sub'] ?>');">
                                    <ul>
                                        <li class="blue jtheme <?php echo (($row_template_theme['theme_id']==$theme_id)?'themeactive':''); ?>" style="background:<?php echo $row_template_theme['theme_color'] ?>  !important"></li>
                                    </ul></a>
                               <?php
                            }
                        }?>
                    </div>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        
        <tr>
            <td> 
                <table width="100%" border="0" class="tpl-preview">
                    <tr><td><strong><?php echo HOME_PAGE;?> </strong></td></tr>
                    <tr>
                        <?php
                        //$pichome = "../showtemplateimage.php?type=home&tmpid=" . $templateid . "&imgname=" . $row_theme['theme_image_home'];
 						$pichome= "templates/".$templateid."/".$row_theme['theme_image_home'];
                        ?> 
                        <td align=center><img id="homeimage" src="<?php echo $pichome; ?>"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>		
                </table>
            </td>
        </tr>
            
        <tr>
            <td>
               <table width="100%" border="0" class="tpl-preview">
                    <tr><td><strong><?php echo INNER_PAGE;?></strong></td></tr> 
                    <tr>
                        <?php
                        //$picsub = "../showtemplateimage.php?type=sub&tmpid=" . $templateid . "&imgname=" . $row_theme['theme_image_sub'];
                        $picsub= "templates/".$templateid."/".$row_theme['theme_image_sub'];
                        ?> 
                        <td align=center><img id="subimage" src="<?php echo $picsub; ?>"></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>		
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <?php
                //if ($redirect_type == 'redirect') {
                    if ($_SESSION['session_userid'] > 0) {
                        ?>
                        <form name="frmChooseTemplate" method="post" action="getsitedetails.php">
                            <?php
                        } else {
                            ?>
                            <form name="frmChooseTemplate" method="post" action="login.php">
                                <?php }
                            ?>
                            <input type="hidden" name="chekSelTemplate" id="chekSelTemplate" value="<?php echo $templateid ?>_<?php echo $theme_id ?>">
                            
                        </form>
                        <?php
                    //}
                    ?> 
            </td>
        </tr>
            
    </table>
        
</div>