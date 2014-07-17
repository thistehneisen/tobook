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
$curTab = 'dashboard';

// include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

if (isset($_GET["page"]) and $_GET["page"] != "") {
    $page = $_GET["page"];
} else if (isset($_POST["page"]) and $_POST["page"] != "") {
    $page = $_POST["page"];
}
//========================================================
$begin = ($_GET["begin"] != "" ? $_GET["begin"] : $_POST["begin"]);
$num = ($_GET["num"] != "" ? $_GET["num"] : $_POST["num"]);
$numBegin = ($_GET["numBegin"] != "" ? $_GET["numBegin"] : $_POST["numBegin"]);
$txtSearch = ($_GET["txtSearch"] != "" ? trim($_GET["txtSearch"]) : trim($_POST["txtSearch"]));
$cmbSearchType = ($_GET["cmbSearchType"] != "" ? $_GET["cmbSearchType"] : $_POST["cmbSearchType"]);
$title		= isset($_REQUEST['title'])	?	trim($_REQUEST['title'])	:	"";
$order		= isset($_REQUEST['order'])	?	trim($_REQUEST['order'])	:	"";

$image='<img src="images/bgar.gif"></img>';
if($order=='DESC') {

    $image='<img src="images/descar.gif"></img>';
}

else if($order=='ASC') {

    $image='<img src="images/ascrar.gif" ></img>';

}
if ($begin == "") {
    $begin = 0;
    $num = 1;
    $numBegin = 1;
}

if ($page == "feedback") {
    $editpage = "feedback.php";
} else if ($page == "customform") {
    $editpage = "createcustom.php";
} else if ($page == "guestbook") {
    $editpage = "createguestbook.php";
}
if ($_POST["postback"] == "Back") {
    header("Location:integrationmanager.php");
    exit;
}

include "includes/userheader.php";
?>
<script language="JavaScript" type="text/JavaScript">
    function clickSearch()
    {
        document.frmSites.postback.value="S";
        document.frmSites.action="selectsite.php";
        document.frmSites.submit();
    }
    function clickBack()
    {
        document.frmSites.postback.value="Back";
        document.frmSites.action="selectsite.php";
        document.frmSites.submit();
    }

</script>

<form name="frmSites" method="post" action="">
  <input name="postback" type="hidden" id="postback">
  <input name="id" type="hidden" id="id">
  <input name="page" type="hidden" id="page" value="<?php echo $page ?>">
  <?php 
// ========================================================
$qryopt1 = "";
$qryopt2 = "";



if ($txtSearch != "") {
    if ($cmbSearchType == "site name") {
        $qryopt1 .= "  AND ts.vsite_name like '" . urldecode(addslashes($txtSearch)) . "%'";
        $qryopt2 .= "  AND si.vsite_name like '" . urldecode(addslashes($txtSearch)) . "%'";
    } elseif ($cmbSearchType == "date") {
        $qryopt1 .= "  AND ts.ddate like '" . urldecode(addslashes($txtSearch)) . "%'";
        $qryopt2 .= "  AND si.ddate like '" . urldecode(addslashes($txtSearch)) . "%'";
    }
}
// $sql="SELECT nsite_id, nuser_id, vsite_name,Date_Format(ddate,'%m/%d/%Y') as ddate FROM tbl_site_mast  WHERE nuser_id='".$_SESSION["session_userid"]."' And vdelstatus !='1'" . $qryopt . "  order by ddate DESC   ";
$sql = "select IFNULL(ts.ntempsite_id,si.nsite_id) as 'nsite_id',IFNULL(ts.ntemplate_id,si.ntemplate_id) as 'ntemplate_id',
IFNULL(ts.vsite_name,si.vsite_name) as 'vsite_name',IFNULL(Date_Format(ts.ddate,'%m/%d/%Y'),
Date_Format(si.ddate,'%m/%d/%Y')) as 'ddate',IF( ISNULL(ts.ntempsite_id), 'Completed', 'Under Construction' ) as 'status',
IFNULL(ts.vtype,si.vtype) as 'vtype'
from dummy d left join tbl_tempsite_mast ts ON(d.num=0 AND ts.nuser_id='" . $_SESSION["session_userid"] . "' " . $qryopt1 . ")
left join tbl_site_mast si on(d.num=1 AND si.nuser_id='" . $_SESSION["session_userid"] . "' AND si.ndel_status='0' " . $qryopt2 . ")
 where ts.ntemplate_id IS NOT NULL OR si.nsite_id IS NOT NULL ";
if($title!=''){
   $sql    .=" ORDER BY ".$title."  ".$order;
}

$session_back = "selectsite.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . urlencode($txtSearch) . "&page=$page";
$_SESSION["gbackurl"] = $session_back;


// get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
  Call the function:

  I've used the global $_GET array as an example for people
  running php with register_globals turned 'off' :)
 */

$navigate = pageBrowser($totalrows, 10, 10, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch&page=$page&title=$title&order=$order", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);

$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_INTEGRATION_MANAGER =>'integrationmanager.php',
                    INTEGRATION_MANAGER_CREATED_SITE_TITLE =>'selectsite.php?page='.$page);
echo getBreadCrumb($linkArray);
?>
  <h2><?php echo INTEGRATION_MANAGER_CREATED_SITE_TITLE; ?></h2>
  <table  width="100%"  border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td colspan="2" >
	
	<div class="admin-search-pnl">
	<label class="text">Search </label>
	
	<select name="cmbSearchType" class="selectbox select10">
<option value="site name"
<?php if ($cmbSearchType == "site name" || $cmbSearchType == "") {
	echo("selected");
} ?>>Site Name  &nbsp;  &nbsp;</option>
</select>

 &nbsp;
 <input type="text" name="txtSearch" size="20" maxlength="50"  class="textbox txt10" 
                                                           value="<?php echo(htmlentities($txtSearch)); ?>"
                                                           onKeyPress="if(window.event.keyCode == '13'){ return false; }"
                                                           >
	
	<a class="btn05" href="javascript:clickSearch();">Search</a>
	<div class="clear"></div>
	</div>
	
	
	
      <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="admin-table-list">
        <tr class="blacksub">
          <th width="50%" valign="middle" ><a href="selectsite.php?title=vsite_name&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'?>"><?php echo $image ?>Site Name</a></th>
          <th width="19%" valign="middle"><a href="selectsite.php?title=status&order=<?php echo $order == 'ASC' ? 'DESC' : 'ASC'?>"><?php echo $image ?>Status</a></th>
          <th width="31%" valign="middle">Create
            <?php
                                        if ($page == "feedback") {
                                            $form = "/Edit Feedback Form";
                                        } else if ($page == "customform") {
                                            $form = "Customized Form";
                                        } else if ($page == "guestbook") {
                                            $form = "Guest Book";
                                        }
                                        echo $form;
                                        ?>
          </th>
        </tr>
        <?php
// loop and display the limited records being browsed
                                $counter = 1;
                                while ($arr = mysql_fetch_array($rs)) {
                                    if ($arr["status"] == "Completed") {
                                        $type = "completed";
                                    } else {
                                        $type = "temp";
                                    }
                                    $templatetype = $arr["vtype"];
                                    $templateid = $arr["ntemplate_id"];
                                    echo "<tr   class=background class='text'>
                                                        <td align='left' style='word-break:break-all;'
                                                        > &nbsp;" . stripslashes($arr["vsite_name"]) . "</td>";

                                    echo "<td  style='word-break:break-all;'>" . $arr["status"] . " </td>";
                                    if ($templatetype == "simple") {
                                        echo "<td align='center' >&nbsp;<a href=$editpage?sitetype=" . $type . "&templatetype=" . $templatetype . "&templateid=" . $templateid . "&siteid=" . $arr["nsite_id"] . ">Continue</a></td>";
                                    } else {
                                        echo "<td align='center' > - NA - (Advanvced Template)</td>";
                                    }

                                    echo "</tr>";
                                    $counter++;
                                }
                                ?>
        
      </table>
</form>
</td>

</tr>



<tr class="admin-table-btm">
  <td align="left">
  <?php echo("Listing $navigate[1] of $totalrows results."); ?>  &nbsp; &nbsp; <?php echo($navigate[2]);
                                        ?>&nbsp; 
  </td>
  <td align="right">
  <input type="button" class="btn02" value="Back to Integration Manager" name="btnBack" onclick="javascript:clickBack();"></td>
</tr>

</table>
<?php

include "includes/userfooter.php";

?>
