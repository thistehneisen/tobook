<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
include "includes/function.php"; 


// Need to unset sitedetails session if choosing different template
$sessionExistsingData = array();
$sessionExistsingData = $_SESSION['siteDetails']['siteInfo'];
unset($_SESSION['siteDetails']);
$_SESSION['siteDetails']['siteInfo']   = $sessionExistsingData;
$_SESSION['siteDetails']['templateid'] = $sessionExistsingData['templateid'];
$_SESSION['siteDetails']['themeid']    = $sessionExistsingData['themeid'];
// Need to unset sitedetails session if choosing different template

$begin = ($_GET["begin"] != "" ? $_GET["begin"] : $_POST["begin"]);
$num = ($_GET["num"] != "" ? $_GET["num"] : $_POST["num"]);
$numBegin = ($_GET["numBegin"] != "" ? $_GET["numBegin"] : $_POST["numBegin"]);
if ($begin == "") {
    $begin = 0;
    $num = 1;
    $numBegin = 1;
}
$categoryid = addslashes($_GET['catid']);
if ($categoryid != "") {
    $_SESSION['session_categoryid'] = $categoryid;
} else {
    $categoryid = $_SESSION['session_categoryid'];
}
$sqlCategories  =   "select * from tbl_template_category  order by vcat_name ";
$resCategories  =   mysql_query($sqlCategories);
if ($_GET['type'] == "T") {
    $selectedtemplateid = $_GET['templateid'];

    //$_SESSION['session_currenttemplateid']=$selectedtemplateid;
    $_SESSION['session_cleared'] = "no";
    $_SESSION['session_backurl'] = "showtemplates.php?catid=" . $_SESSION['session_categoryid'];
    $location = $url . "templateid=$selectedtemplateid&";

    $_SESSION['session_templateselectedfromindex'] = "";
    //================================PLEASE SPECIFY THE REDIRECT URL HERE=============================
    header("location:$location");
    exit;
}
//$sql = " select ntemplate_mast,vthumpnail,vtype,ncat_id from  tbl_template_mast where vtype='" . addslashes($buildtype) . "'";
$sql = " select ntemplate_mast,vthumpnail,vtype,ncat_id,temp_name from  tbl_template_mast ";
if($categoryid=='all') {
    //$sql .="  order by ddate desc,ncat_id desc ";
    $sql .="  order by temp_name ASC,ncat_id desc ";
}
else {
    $sql .=" where ncat_id='" . addslashes($categoryid) . "' order by ddate desc ";
}

//echo $sql;

$session_back = "showtemplates.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
$_SESSION['gtemplatebackurl'] = $session_back;
// get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));
/*
  Call the function:
  I've used the global $_GET array as an example for people
  running php with register_globals turned 'off' :)
*/
$pageCount = 6;
$navigate = pageBrowser($totalrows, 5, $pageCount, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);
//echo $sql;
//$rs = mysql_query($sql);

$themeId     = $_SESSION['siteDetails']['siteInfo']['siteThemeId'];
$templateId  = $_SESSION['siteDetails']['siteInfo']['siteTemplateId'];
include "includes/userheader.php";
?>
<script>
    function selecttemplate(tempid){
        document.getElementById("selectedtemplate").value=tempid;
        document.getElementById("postback").value="T";

        // document.frmSelectTemplate.submit();

    }
    function showpreview(prtype,id,type){
        var leftPos = (screen.availWidth-500) / 2;
        var topPos = (screen.availHeight-400) / 2 ;
        winurl="templatepriview_loged.php?prtype="+prtype+"&id="+id+"&type="+type+"&";
        //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
        insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos); 


    }
    function selecttemplate_1(tid){
        document.frmSelectTemplate.action="showtemplates.php?type=T&templateid="+tid; 
        document.frmSelectTemplate.submit();
        //location.href="./showtemplates.php?type=T&templateid="+tid;

    }
</script>
<script language="javascript" src="js/jquery.min.js"></script>
<script language="javascript" src="js/modal.popup.js"></script>
<script language="javascript" src="js/validations.js"></script>
<script>
    $(document).ready(function(){
        $('.jtheme').mouseover(function(){ 
            
            var theme_id=$(this).attr('val'); 
            var template_id=$(this).attr('tmpid'); 

            var jtheme = $(this).attr('id'); 
            var resval 	= jtheme.split(/_/);
        	
            $('.jThemeDisplay_'+template_id).hide();
            //cat
            
            $('#div_'+theme_id).show();
            $('#div_radio_'+theme_id).show();
            $('#jqtemp_'+resval[1]).prop("checked", true);
			//alert('jqtemp_'+resval[1]);
        });
        $('#jcontinue').click(function(){
            if($("input[name=chekSelTemplate]:checked").length > 0) {
                frmSelectTemplate.submit();
            }
            else{
                alert("<?php echo VAL_TEMPLATE_CHOOSE;?>");
                return false;
            }
        });
    });
function showCatTemplates(catId){
    window.location='showtemplates.php?catid='+catId;
}
</script>

<table width="900"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align=center>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left">
                        <!--div class="stage_selector">
                            <span>1</span>&nbsp;&nbsp;Select Template
                        </div-->
                        <?php
                        $linkArray = array(
                            'Home' => 'usermain.php',
                            'Site Manager' => 'sitemanager.php',
                            'Select Category' => 'showcategories.php',
                            'Select Template' => 'showtemplates.php?catid=' . $categoryid,);

                        echo getBreadCrumb($linkArray);
                        ?>
                        <h2><span class="step-cnt">2</span><?php echo SELECT_TEMPLATE;?></h2>
                    </td>
                </tr>
                    
                <tr>
                    <td >
                        <div class="temp_category_selector"><?php echo TEMPLATE_SELECT_CATEGORY;?>
                            <select name="txtCategory" onchange="showCatTemplates(this.value)">
                                <option value="all"><?php echo TEMPLATE_SELECTALL;?></option>
                               <?php 
                               while ($rowCategories = mysql_fetch_array($resCategories)) {//window.location='showtemplates.php?catid=this.options[this.selectedIndex].value'?>
                                    <option value="<?php echo $rowCategories['ncat_id']?>" <?php if($categoryid==$rowCategories['ncat_id']) echo"selected";?>><?php echo $rowCategories['vcat_name']?></option>
                               <?php
                               }?>
                            </select>
                        </div>
                        <!-- Main section starts here-->
                        <form name="frmSelectTemplate" method="post" action="getsitedetails.php">
                            <input type=hidden name=selectedtemplate id=selectedtemplate>
                            <input type=hidden name=postback id=postback>
                            <table width="100%" border="0" cellspacing="0">
                                <?php
                                if (mysql_num_rows($rs) > 0) {
                                    $i = 0;
                                    $j = 1;
                                    while ($row = mysql_fetch_array($rs)) {
                                        if ($i == 0) {
                                            echo "<tr>";
                                        }
                                        if ($i == 3) {
                                            $i = 0;
                                            echo "</tr>";
                                            $marginRight = 'style="margin-right:0;" ';
                                        }
                                        $prvlink = "<a class=subtext style=\"text-decoration:none;\" href='javascript:void(0)' onclick=\"showpreview('index','" . stripslashes($row["ntemplate_mast"]) . "','" . $row["vtype"] . "');\">";
                                        ?>
                                                    
                                        <td width="33%">
                                            <div class="temp_list_box" <?php $marginRight;?> >
                                                <?php
                                                //echo $row['ntemplate_mast'];
                                                //$picthumb = "showtemplateimage.php?tmpid=" . $row['ntemplate_mast'] . "&";
                                                //Differnt Themes
                                                $sql_template_theme = "SELECT * FROM  tbl_template_themes  WHERE temp_id=" . $row['ntemplate_mast'] . " AND theme_status='1' ORDER BY theme_id ASC";
                                                $res_template_theme = mysql_query($sql_template_theme);
                                                if (mysql_num_rows($res_template_theme) > 0) { 
                                                    $cnt = 1;
                                                    while ($row_template_theme = mysql_fetch_assoc($res_template_theme)) { 
                                                       // $picthumb = "showtemplateimage.php?tmpid=" . $row['ntemplate_mast'] . "&type=thumb&imgname=" . $row_template_theme['theme_image_thumb'];
                                                       
                                                           $picthumb= "templates/".$row['ntemplate_mast']."/".$row_template_theme['theme_image_thumb'];
                                                           if($row['ntemplate_mast'] ==$templateId ){
                                                               if($themeId > 0){
                                                                   if($row_template_theme['theme_id']==$themeId){
                                                                       $display = "block";
                                                                       $cnt = 1;
                                                                   }else{
                                                                       $cnt = 2;
                                                                   }
                                                               }
                                                           }else{
                                                               $cnt = $cnt;
                                                           }
                                                           
                                                           if($cnt != 1) $display = "none";
                                                           else $display = "block";
                                                           
                                                        ?>
                                                        <div id="div_<?php echo $row_template_theme['theme_id'] ?>" class="jThemeDisplay_<?php echo $row['ntemplate_mast'] ?> temp_img_preview" style="display:<?php echo $display;?>">
                                                            <a name="600" id="viewtemplate.php?prtype=index&tid=<?php echo $row_template_theme['theme_id'] ?>&id=<?php echo $row['ntemplate_mast']; ?>&type=simple&" class="modal" href="javascript:void(0);" title="Click to get preview">
                                                                <img src="<?php echo $picthumb; ?>"  border="0">
                                                            </a>
                                                                
                                                        <!--&nbsp;[<?php echo $prvlink; ?>&nbsp;<b>Select/View</b>&nbsp;</a>]-->
                                                        </div>
                                                            
                                                        <div class="tpl-id jThemeDisplay_<?php echo $row['ntemplate_mast'] ?>" id="div_radio_<?php echo $row_template_theme['theme_id'] ?>" style="display:<?php echo $display;?>"> 
                                                            <?php 
                                                           // echo "<pre>";
                                                           // print_r($row);
                                                            if($templateId > 0 ){  
                                                                if($row['ntemplate_mast'] == $templateId && $cnt=='1'){
                                                                    $checked = "checked=checked";
                                                                }else{
                                                                    $checked = "";
                                                                }
                                                            }else if($j == '1' && $cnt=='1'){ 
                                                                $checked = "checked=checked";
                                                            }else{ 
                                                                $checked = "";
                                                            } //echopre($row['ntemplate_mast']); echopre($templateId);
                                                            ?>
                                                            <input id="jqtemp_<?php echo $row['ntemplate_mast'].$row_template_theme['theme_id']; ?>" type="radio" name="chekSelTemplate" <?php echo $checked;?> value="<?php echo $row['ntemplate_mast'] . "_" . $row_template_theme['theme_id']; ?>"><?php echo($row['temp_name'].": <span>" . TEMPLATE_PREFIX . $row['ntemplate_mast'] . "</span>"); ?>
                                                        </div>
                                                            
                                                        <?php
                                                        $cnt++;
                                                    }
                                                }
                                                ?>
                                                <div class="dsply-tpl-btm">
                                                    <div class="color-picker">
                                                        <ul>
                                                            <?php
                                                            $sql_template_theme = "SELECT * FROM  tbl_template_themes  WHERE temp_id=" . $row['ntemplate_mast'] . " AND theme_status='1' ORDER BY theme_id";
                                                            $res_template_theme = mysql_query($sql_template_theme);
                                                            if (mysql_num_rows($res_template_theme) > 0) {
                                                                while ($row_template_theme = mysql_fetch_array($res_template_theme)) {
                                                                    $active = ($row_template_theme['theme_id']==$themeId)?"themeactive":"";
                                                                    ?> 
                                                                    <li class="blue jtheme <?php echo $active;?>" style="background:<?php echo $row_template_theme['theme_color'] ?>  !important" val="<?php echo $row_template_theme['theme_id'] ?>" tmpid="<?php echo $row['ntemplate_mast'] ?>" id="jtemp_<?php echo $row['ntemplate_mast'].$row_template_theme['theme_id']; ?>"><?php //echo $row_template_theme['theme_name'] ?></li>
                                                                        
                                                                    <?php
                                                                }
                                                            }
                                                            ?>
                                                        </ul>
                                                    </div>
                                                    <div class="clear"></div>
                                                </div>
                                                <div class="clear"></div>
                                            </div>
                                        </td>
                                        <?php
                                        $i++;
                                        $j++;
                                    }
                                    if ($i == 1) {
                                        echo "<td >&nbsp;</td></tr>";
                                    } else if ($i == 3) {
                                        echo "</tr>";
                                    }
                                } else {
                                    ?>
                                    <tr class=background>
                                        <td colspan="4" align="center" height="30"><?php echo SORRY_NO_RECORDS;?></td>
                                    </tr>
                                    <?php
                                }
                                if ($totalrows > $pageCount) {
                                    ?>
                                    <tr class=background><td colspan="4" align="right" height="30">
                                    <?php echo($navigate[2]); ?>&nbsp;
                                        </td>
                                    </tr>
<?php } ?>
                                <!--<tr>
                                    <td>
                                        <div class="lft">
                                            &nbsp;
                                        </div>
                                    </td>
                                    <td colspan="2">
                                        <div class="pagination ryt">
                                            <ul>
                                                <li><img src="themes/Coastal-Green/prev.png"></li>
                                                <li>Prev</li>
                                                <li><a href="#">1 </a>| </li>
                                                <li><a href="#" class="active">2 </a>| </li>
                                                <li>Next</li>
                                                <li><img src="themes/Coastal-Green/next.png"></li>
                                            </ul>
                                        </div>
                                    </td>
                                </tr>-->
                                <tr><td>&nbsp;</td></tr>
                                <tr>
                                    <td align="right" colspan="3" width="100%">
                                        <a href="showcategories.php" class="grey-btn02" onclick="">
                                                <!--<img src="./images/back.gif" border="0" width="54px" height="15px">-->
                                            <?php echo TEMPLATE_SAVE;?>
                                        </a> &nbsp;
                                        <!--<a href="showcategories.php?cat=<?php echo $buildtype; ?>" class="btn04">Continue</a>-->
                                        <a href="#" id="jcontinue"  class="btn04"><?php echo TEMPLATE_CONTINUE;?></a>
                                    </td>
                                </tr>
                            </table>
                        </form>
                        <!-- Main section ends here-->
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

<?php
include "includes/userfooter.php";
?>