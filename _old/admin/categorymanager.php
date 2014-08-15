<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
//include files
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}

function isCategoryDeletable($catid){
        global $con;
        $sql = "SELECT count(ngcat_id) as cnt FROM tbl_gallery WHERE ngcat_id = '".addslashes($catid)."' ";
        $res = mysql_query($sql,$con);

        if(mysql_num_rows($res) > 0){
                $row = mysql_fetch_array($res);
                $count = $row["cnt"];
                if($count > 0){
                        return false;
                }else{
                        return true;
                }
        }else{
                return true;
        }
}

if($_GET["delact"]=="delete"){
        if(isCategoryDeletable($_GET["delid"])){//if category can be deleted, then delete the category
                $sql="DELETE FROM tbl_gallery_category WHERE ngcat_id='".$_GET["delid"]."'";
                mysql_query($sql,$con);
                header("location:".$_SESSION["gbackurl"]."&msg=del");
                exit;
        }else{
                $message = "This category cannot be deleted since it is in use! ";
        }

}

if($_GET["msg"] == "del"){
        $message = "Category deleted successfully! ";
}

if($_GET["msg"] == "cs"){
        $message = "Category created successfully! ";
}



include "includes/adminheader.php";

?>
<script language="JavaScript" type="text/JavaScript">
function clickSearch()
{
        document.frmCategories.postback.value="S";
        document.frmCategories.action="categorymanager.php";
        document.frmCategories.submit();
}

function deleteCategory(id){
        if(confirm("Are you sure you want to delete this category?")){
                document.frmCategories.delid.value=id;
                document.frmCategories.delact.value="delete";
                document.frmCategories.action="categorymanager.php";
                document.frmCategories.submit();
        }
}


</script>

<form name="frmCategories" >
<input name="postback" type="hidden" id="postback">
<input name="delact" type="hidden" id="delact">
<input name="delid" type="hidden" id="delid">

<?php



//========================================================
$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
$txtSearch=($_GET["txtSearch"] != ""?trim($_GET["txtSearch"]):trim($_POST["txtSearch"]));
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);


if($begin == ""){
        $begin=0;
        $num=1;
        $numBegin=1;
}

$qryopt="";

if($txtSearch != ""){

        if($cmbSearchType == "name"){
                $qryopt .= "  WHERE vcat_name like '" . urldecode(addslashes($txtSearch)) . "%'";
        }elseif($cmbSearchType == "description"){
                $qryopt .= "  WHERE vcat_desc   like '" . urldecode(addslashes($txtSearch)) . "%'";
        }
}

$sql="select * from tbl_gallery_category " . $qryopt . " order by vcat_name";


//echo $sql;
$session_back="categorymanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" .urlencode($txtSearch);
$_SESSION["gbackurl"] = $session_back;
//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$txtSearch1 = urlencode($txtSearch);
$navigate = pageBrowser($totalrows,10,10,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch1",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql) or die(mysql_error());
?>

<table width="82%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align=center>





                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                        <td align="center"><img src="../images/systemgallry.gif" ><br>&nbsp;</td>
                    </tr>
                                <tr>
                <td class="listingmain">
                          <table width="100%"  border="0" cellpadding="2" cellspacing="1" class="text">

                          <tr class=background>
                                  <td width="100%" align="left" class="redtext"><?php echo $message?></td>
                                                  </tr>
                                                                 <tr class=background>
                                  <td width="100%" align="left" > <a href="editcategory.php" class=toplinks>Add New Category</a> </td>
                                                  </tr>
                                                  <tr class=background>
                          <td width="100%">


                                           <table  border="0"><tr>
                                           <td width="3%" align="center" valign="top">&nbsp;</td>
                                           <td width="30%" valign="bottom"
                                           class="category"><?php echo("Listing $navigate[1] of $totalrows results."); ?>
                                           </td>
                                           <td width="67%" valign="top"
                                            class="listing126" align=right>search ::

                                           <select name="cmbSearchType" class="selectbox">
                                           <option value="name"
                                           <?php if($cmbSearchType == "name" || $cmbSearchType == ""){
                                           echo("selected"); } ?>>Name</option>
                                           <option value="description"  <?php
                                           if($cmbSearchType == "description"){
                                           echo("selected"); } ?>>Description</option>
                                           </select> &nbsp;
                                           <input type="text" name="txtSearch" size="20" maxlength="50"
                                           value="<?php  echo(htmlentities($txtSearch)); ?>"
                                           onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                           class="textbox">&nbsp;</td><td width="5%" valign="middle"                                              class="listing126"><a href="javascript:clickSearch();">
                                           <img src='.././images/go.gif'  width="20" height="20" border='0'></a></td>
                                           </td>
                                           </tr>
                                           </table>


                        </td>
                        </tr>
                        </table>


                        <table width="100%"  border="0" cellpadding="5" cellspacing="1" class="text">
                        <tr class="blacksub">
                        <td width="5%" valign="top" >#</td>
                        <td width="30%" >Name</td>
                        <td width="45%">Description</td>
                        <td width="10%" >Edit</td>
                        <td width="10%">Delete</td>
                        </tr>

                                                <?php

                                                //loop and display the limited records being browsed
                                                $counter=$begin+1;

                                                while ($arr = mysql_fetch_array($rs)) {
                                                        echo "<tr  class=background  class='text'>
                                                        <td style='word-break:break-all;'
                                                        align='left'>&nbsp;".stripslashes($counter)."</td>";
                                                        echo "<td  align='left' style='word-break:break-all;'>
                                                        &nbsp;" . htmlentities(stripslashes($arr["vcat_name"])). "</td>";
                                                        echo "<td  align='left' style='word-break:break-all;'>".htmlentities(stripslashes($arr["vcat_desc"]))." </td>";
                                                        echo "<td >&nbsp;<a href='editcategory.php?catid=". stripslashes($arr["ngcat_id"]) ."' class=subtext>Edit</a></td>";
                                                        echo "<td >&nbsp;<a href=javascript:deleteCategory('". stripslashes($arr["ngcat_id"]) ."') class=subtext>Delete</a></td>";
                                                        echo "</tr>";
                                                        $counter++;
                                                }
                                        ?>

                        <tr class=background>
                        <td colspan="7" align="center" height="30">
                        <?php echo($navigate[2]); ?>&nbsp;
                        </td>
                        </tr>
                                                 <tr class=background>
                        <td colspan="7" align="left">
                                                   <a href="editcategory.php" class=toplinks>Add New Category</a>
                        </td>
                        </tr>
                        </table>

                                </td>
                </tr>
                </table>

</td>
</tr>
</table>


</form>



<?php
include "includes/adminfooter.php";
?>