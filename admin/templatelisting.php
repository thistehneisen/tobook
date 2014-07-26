<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh.s@armia.com>              		          |
// |          									                          |
// +----------------------------------------------------------------------+
$curTab = 'template_manager';
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
} 
if ($_POST["delact"] == "delete") {

    /* check the template already in use.*/
    /*check whether the templateid exist in tbl_tempsite_mast*/

    $qry = "select * from tbl_tempsite_mast where ntemplate_id='" . addslashes($_POST["delid"]) . "'";
    $res1 = mysql_query($qry);
    if (mysql_num_rows($res1) > 0) {
        $message =  MSG_TEMPL_IN_USE."!." ;
    } else {
        /*check whether the templateid exist in tbl_site_mast*/
        $qry1 = "select * from tbl_site_mast where ntemplate_id='" . addslashes($_POST["delid"]) . "'";
        $res2 = mysql_query($qry1);
        if (mysql_num_rows($res2) > 0) {
            $message = MSG_TEMPL_IN_USE ."!" ;
        } else {
            // remove template folder
            if (is_dir("../" . $_SESSION["session_template_dir"] . "/" . $_POST["delid"])) {
                remove_dir("../" . $_SESSION["session_template_dir"] . "/" . $_POST["delid"]);
            }
            $sql = "delete from tbl_template_mast  where ntemplate_mast='" . addslashes($_POST["delid"]) . "'" ;
            mysql_query($sql);
            $message = MSG_TEMPL_DEL_SUCC." !" ;
        }
    }
} 


?>
<script language="javascript" type="text/javascript" src="../js/jquery.js"></script>
<script language="javascript" src="../js/modal.popup.js"></script>
<script language="javascript" src="../js/validations.js"></script>
<link rel="stylesheet" href="../style/jquery-ui.css" />
<script src="../js/jquery-1.8.3.js"></script>
<script src="../js/jquery-ui.js"></script>
<script language="JavaScript" type="text/JavaScript">

$(document).ready(function () {

    var searchType = '<?php echo $_REQUEST['cmbSearchType'] ?>'; 
    if(searchType == 'date'){
        $("#txtSearch").datepicker();
    }else{
        $("#txtSearch").datepicker("destroy");
    }
    
});

    function toggleSerachType(){
        var searchType = $("#cmbSearchType").val();
        if(searchType == 'date'){
            $("#txtSearch").datepicker();
        }else{
            $("#txtSearch").datepicker("destroy");
        }
    }

    function resetValues(){
        $("#txtSearch").val(" ");
    }

    function clickSearch() {
        document.frmTemplate.postback.value="S";
        document.frmTemplate.action="templatelisting.php";
        document.frmTemplate.submit();
    }

    function deleteTemplate(id,begin,num,numbegin){
        ans=confirm("<?php echo VALMSG_DELETE_TEMP;?>")

        if(ans==true){

            document.frmTemplate.delid.value=id;
            document.frmTemplate.delact.value="delete";
            document.frmTemplate.action="templatelisting.php?begin="+begin+"&num="+num+"&numBegin="+numbegin+"&";
            document.frmTemplate.submit();
        }

    }
    function showpreview(prtype,id,type){
        var leftPos = (screen.availWidth-500) / 2;
        var topPos = (screen.availHeight-400) / 2 ;
        winurl="templatepriview.php?prtype="+prtype+"&id="+id+"&type="+type+"&";
        insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);


    }
</script>

<form name="frmTemplate" method=post>
    <input name="postback" type="hidden" id="postback">
    <input name="delact" type="hidden" id="delact">
    <input name="delid" type="hidden" id="delid">

    <?php
//========================================================
    $begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
    $num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
    $numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
    $txtSearch=($_GET["txtSearch"] != ""?$_GET["txtSearch"]:$_POST["txtSearch"]);
    $cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);
    if($begin == "") {
        $begin=0;
        $num=1;
        $numBegin=1;
    }
    $qryopt="";
    if($txtSearch != "") {

        if($cmbSearchType == "category") {
            $qryopt .= "  AND tc.vcat_name like '%" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "type") {
            $qryopt .= "  AND tm.vtype like '%" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "date") {
            $qryopt .= "  AND date_format(tm.ddate,'%m/%d/%Y %H:%i:%s') like '" . addslashes($txtSearch) . "%'";
        }elseif($cmbSearchType == "viewall") {
            $qryopt .= " ";
            $txtSearch = "";
        }
    }

    $sql="select tm.ntemplate_mast,tm.vthumpnail,tm.ncat_id,Date_Format(tm.ddate,'%m/%d/%Y') as date ,
   tm.vtype,tc.vcat_name,T.theme_image_thumb,T.theme_id from tbl_template_mast tm left join tbl_template_category tc on tm.ncat_id=tc.ncat_id LEFT JOIN tbl_template_themes AS T ON T.temp_id=tm.ntemplate_mast where 1=1 AND theme_default=1 ". $qryopt." order by ntemplate_mast DESC ";
    $session_back="usermanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
    $_SESSION["gbackurl"] = $session_back;
    $totalrows = mysql_num_rows(mysql_query($sql));
    $navigate = pageBrowser($totalrows,10,5,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);
    $sql = $sql.$navigate[0];
    $rs = mysql_query($sql) or die(mysql_error());

    $linkArray = array( TEMP_MANAGER    =>'admin/templatemanager.php',
            MANAGE_TEMP  =>'admin/templatelisting.php');

    ?>
    
    <div class="admin-pnl">
        <?php echo getBreadCrumb($linkArray); ?>
        <h2><?php echo MANAGE_TEMP;?></h2>
        <table width="100%"  border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td  valign="top" align=center>
                    <div class=errormessage><?php echo $message?>&nbsp;</div>
                    <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                        <tr>
                            <td class="">
                                <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="">
                                    <tr class=>
                                        <td width="100%">

                                            <div class="admin-search-pnl">
                                                <label><?php echo SEARCH;?></label>
                                                <select name="cmbSearchType" id="cmbSearchType" class="selectbox" style="width: 120px;" onchange="return toggleSerachType()">
                                                    <option value="category"
                                                    <?php  if($cmbSearchType == "category" || $cmbSearchType == "") {
                                                        echo("selected");
                                                            } ?>><?php echo CATEGORY;?></option>

                                                    <option value="date"  <?php
                                                    if($cmbSearchType == "date" ) {
                                                        echo("selected");
                                                            } ?>><?php echo DATE;?></option>

                                                    <option value="viewall"  <?php
                                                    if($cmbSearchType == "viewall" ) {
                                                        echo("selected");
                                                            } ?>><?php echo VIEW_ALL;?></option>
                                                </select>

                                                &nbsp;
                                                <input style="width:300px;" type="text" name="txtSearch" id="txtSearch" size="20" maxlength="50" value="<?php  echo($txtSearch); ?>"
                                                       onKeyPress="if(window.event.keyCode == '13'){ return false; }" class="textbox">

                                                <a href="javascript:clickSearch();" class="btn05"><?php echo SEARCH;?></a>
                                                <input class="grey-btn04"  type="button" name="reset" id="reset" value="<?php echo BTN_RESET;?>" onclick="return resetValues();" />
                                                <div class="clear"></div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>


                                <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="admin-table-list">
                                    <tr>
                                        <th width="20%" valign="top"><?php echo CATEGORY;?></th>
                                        <th width="30%" ><?php echo THUMBNAIL;?></th>
                                        <!--<td width="12%"  >Type</td>-->
                                        <th width="10%"  ><?php echo DATE;?></th>
                                        <th width="18%"  ><?php echo PREVIEW;?></th>
                                        <th width="10%"  ><?php echo DELETE;?></th>
                                    </tr>

                                    <?php

                                    //loop and display the limited records being browsed
                                    $counter=1;
                                    if($totalrows > 0 ) {
                                        while ($arr = mysql_fetch_array($rs)) {
                                            // echo "<pre>";
                                            //print_r($arr);

                                            $templateLink="<a class='modal' name='600' id='viewtemplate.php?prtype=index&tid=".$arr['theme_id']."&id=".$arr['ntemplate_mast']."&type=redirect&'  href='javascript:void(0);' title='Click to get preview'>".PREVIEW."</a>";

                                            //echo "../".$_SESSION["session_template_dir"]."/".$arr["ntemplate_mast"]."/".$arr["vthumpnail"]."<br>";
                                            $userlink = "sitemanager.php?begin=0&num=1&numBegin=1&cmbSearchType=username&txtSearch=".$arr["vuser_login"];
                                            $thumbimage="<img src='../".$_SESSION["session_template_dir"]."/".$arr["ntemplate_mast"]."/".$arr["theme_image_thumb"]."'>";
                                            echo "<tr   class=background>
                                                        <td style='word-break:break-all;'
                                                         align='left' >&nbsp;".stripslashes($arr["vcat_name"])."</a></td>";

                                            echo "<td  align='left'  style='word-break:break-all;'>
                                                        &nbsp;" .$thumbimage. "</td>";
                                            // echo "<td align='left' style='word-break:break-all;'>".htmlentities($arr["vtype"])." </td>";
                                            echo "<td align='center'>&nbsp;" .stripslashes($arr["date"]). "</td>";
                                            echo '<td  align="center">'.$templateLink.'</td>';
                                            // echo "<td >&nbsp;<a class=subtext  target='_blank' href='templatepriview.php?prtype=index&id=".stripslashes($arr["ntemplate_mast"])."&type=".$arr["vtype"]."'>Indexpage</a>";
                                            //echo "<br><a class=subtext  target='_blank' href='templatepriview.php?prtype=sub&id=".stripslashes($arr["ntemplate_mast"])."&type=".$arr["vtype"]."'>Subpage</a></td>";

                                            $tempEditLink = '<a href="edittemplate.php?tempid='.$arr['ntemplate_mast'].'">'.EDIT.'</a> | ';
                                            echo "<td >".$tempEditLink."&nbsp;<a href=javascript:deleteTemplate('". stripslashes($arr["ntemplate_mast"]) ."','".$begin."','".$num."','".$numBegin."') class=subtext>".DELETE."</a></td>";
                                            echo "</tr>";
                                            $counter++;
                                        }
                                    }
                                    else { ?>
                                    <tr><td colspan="8" style="text-align: center;"><b><?php echo NO_DATA_FOUND;?>!</b></td></tr>
                                        <?php }
                                    ?>


                                </table>

                                <?php if($totalrows >$pageCount) { ?>
                                <div class="admin-table-btm">
                                    <div class="total-list lft">
                                            <?php echo(SM_LISTING. $navigate[1].SM_OF.$totalrows.SM_RESULTS);?>
                                    </div>
                                    <div class="list-pagin ryt">
                                            <?php echo($navigate[2]); ?>
                                    </div>
                                    <div class="clear"></div>
                                </div>
                                    <?php } ?>

                            </td>
                        </tr>
                        <tr>
                            <td>&nbsp;</td>
                        </tr>
                    </table>

                </td>
            </tr>
        </table>

</form>
</div>


<?php
include "includes/adminfooter.php";
?>