<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php

$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/userheader.php";

if($_SESSION['session_lookupsitename'] == '')
	$_SESSION['session_lookupsitename'] = getSettingsValue('site_name');
?>

<h2 class="headingstyle_3"><?php echo TOP_LINKS_DASHBOARD; ?></h2>

<!--div class="wlcme_user">
<p><?php //echo DASHBOARD_WELCOME; ?> <span><?php //echo $_SESSION['session_loginname'];?></span></p>
</div-->

<?php if($_GET['action'] == 'newuser'){
  
	?>
<div class="nwusrwelcome">
	<div class="nwusrwelcometitle"><?php echo WELCOME ;?> <?php echo $_SESSION['session_loginname'];?>,</div>
	<div class="nwusrwelcomemessage"><?php echo WELCOME_TO ;?> <?php echo $_SESSION['session_lookupsitename']; ?>.
	<?php echo WELCOME_NOTE ;?>
	 </div>
	
	</div>
<?php } ?>

<div class="cpanel_container">
	<div class="managing_options">
			<div class="manage_box">
				<div class="manage_box_hdr">
                                    <a href="sitemanager.php"><h3><?php echo SITE_MANAGER ;?></h3></a>
				</div>
                            <a href="sitemanager.php"><img border="0" src="images/sitemanager.jpg"></a>
				<div class="clear"></div>
			</div>
			<div class="manage_box">
				<div class="manage_box_hdr">
                                    <a href="gallerymanager.php"><h3><?php echo GALLERY_MANAGER ;?>	</h3></a>
				</div>
                            <a href="gallerymanager.php"><img border="0" align="absmiddle" src="images/gallerymanager.jpg"></a>
				
			</div>
			<div class="manage_box">
				<div class="manage_box_hdr">
                                    <a href="profilemanager.php"><h3><?php echo PROFILE_MANAGER ;?></h3></a>
				</div>
                            <a href="profilemanager.php"><img border="0" src="images/profilemanager.jpg"></a>
				
			</div>
			<div class="manage_box no_margin">
				<div class="manage_box_hdr">
                                    <a href="promotionmanager.php"><h3><?php echo PROMOTION_MANAGER ;?></h3></a>
				</div>
                            <a href="promotionmanager.php"><img border="0" src="images/promotionmanager.jpg"></a>
			</div>
		<div class="clear"></div>
	</div>	
	
	<?php 
	if ($begin == "") {
    $begin = 0;
    $num = 1;
    $numBegin = 1;
}
	// $sql="SELECT nsite_id, nuser_id, vsite_name,Date_Format(ddate,'%m/%d/%Y') as ddate FROM tbl_site_mast  WHERE nuser_id='".$_SESSION["session_userid"]."' And vdelstatus !='1'" . $qryopt . "  order by ddate DESC   ";
$sql = "SELECT nsite_id,ncat_id,ntemplate_id,ntheme_id,vsite_name,Date_Format(ddate,'%m/%d/%Y') as ddate, CASE is_published WHEN 1 THEN 'Published' WHEN 0 THEN 'Draft' END  as 'status',vtype
        FROM tbl_site_mast WHERE nsite_id IS NOT NULL AND ndel_status='0' AND nuser_id='" . $_SESSION["session_userid"] . "' " . $qryopt1 . " ORDER BY ddate DESC ";
$session_back = "sitemanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
$gbackurl = $session_back;
// get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$pageCount = 5;
$navigate = pageBrowser($totalrows, 5, $pageCount, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);
	?>
	
	
<script language="JavaScript" type="text/JavaScript">
 
function clickEdit(siteid)
{
        document.frmSites.postback.value="E";
        document.frmSites.action="edit_site_intermediate.php?action=editsite&siteid=" + siteid;
        //document.frmSites.action="editor.php?actiontype=editsite&siteid=" + siteid;
        document.frmSites.method="post";
        document.frmSites.submit();
}

function clickDelete(siteid,sitename){ 
    if(confirm("<?php echo VAL_DELETE;?>")) {
        var frmId = document.frmSites;
        frmId.postback.value="D";
        frmId.siteId.value=siteid;
        frmId.siteName.value=sitename;
        frmId.action="sitemanager.php";
        frmId.method="post";
        frmId.submit();
    }
}
 
</script>
<form name="frmSites" method="post" action="">
<input name="postback" type="hidden" id="postback">
<input name="id" type="hidden" id="id">
<input type="hidden" name="siteId" id="siteId" value="">
<input type="hidden" name="siteName" id="siteName" value="">
	<div class="userdashboard_ursites">
		<h5><?php echo MY_SITES ;?></h5>
		<table width="100%"  border="0" cellspacing="1" cellpadding="0">
		  <tr>
			<th scope="col" align="left" valign="top"><?php echo DASHBOARD_SITE_NAME ;?></th>
			<th scope="col" align="left" valign="top"><?php echo DATE_CREATED ;?></th>
			<th scope="col" align="left" valign="top"><?php echo STATUS ;?></th>
			<th scope="col" align="left" valign="top" colspan="2"><?php echo OPERATIONS ;?></th>
			<th scope="col" align="left" valign="top"><?php echo PREVIEW ;?></th>
		  </tr>
		      <?php
                                // loop and display the limited records being browsed
                                if(mysql_num_rows($rs)>0){
                                $counter = 1;
                                while ($arr = mysql_fetch_array($rs)) {
                                
                               ?>
		  <tr>
			<td align="left" valign="top"><a href="javascript:clickEdit('<?php echo $arr["nsite_id"]?>');"><?php echo stripslashes($arr["vsite_name"]);?></a></td>
			<td align="left" valign="top"><?php echo stripslashes($arr["ddate"]) ;?></td>
			<td align="left" valign="top"><?php echo  $arr["status"];?> </td>
			<td align="left" valign="top"><a href="javascript:clickEdit('<?php echo $arr["nsite_id"];?>');"><?php echo SM_EDIT;?></a></td>
			<td align="left" valign="top"><a href="#" onClick="javascript:clickDelete('<?php echo $arr["nsite_id"];?>','<?php echo $arr["vsite_name"];?>');" style="text-decoration:none;"><?php echo SM_DELETE;?></a></td>
			<td align="left" valign="top"><a href='workarea/sites/<?php echo $arr[nsite_id];?>/index.html' target='_blank'><?php echo HOME_PREVIEW;?></a></td>
		  </tr>
		  <?php } }
			else 
				echo '<tr><td colspan="6">Sorry! No records Found.</td></tr>';
				
				
				if($totalrows > $pageCount){ 
					echo '<tr><td colspan="6"><span style="float:right"><a href="sitemanager.php">view all</a></span></td></tr>';
				}
		  ?>
		  
		</table>

	</div>
	</form>
	<!-- One row -
	<div class="cpanel_row">
			<!-- One item 
			<div class="item">
				<div class="image">
				
				</div>
				<div class="cnt">
				<h3><a href="sitemanager.php"><?php echo FOOTER_SITE_MANAGER; ?></a></h3>
				<p> <?php echo DASHBOARD_SITE_MANAGER_CONTENT; ?> </p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends 
			
			<!-- One item 
			<div class="item">
				<div class="image">
				<a href="pagemanager.php"><img src="images/icon_pagemanager.png"></a>
				</div>
				<div class="cnt">
				<h3><!--a href="pagemanager.php"--<a href="#"><?php echo FOOTER_PAGE_MANAGER; ?> </a></h3>
				<p><?php echo DASHBOARD_PAGE_MANAGER_CONTENT; ?> </p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends -->
			
			<!-- One item 
			<div class="item" style="margin-right:0; ">
				<div class="image">
				
				</div>
				<div class="cnt">
				<h3><a href="gallerymanager.php"><?php echo FOOTER_GALLERY_MANAGER; ?></a></h3>
				<p><?php echo DASHBOARD_GALLERY_MANAGER_CONTENT; ?></p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends 
			
			
	<div class="clear"></div>
	</div>
	<!-- One row ends 
	
	<!-- One row
	<div class="cpanel_row" style="border-bottom:none; ">
			<!-- One item 
			<div class="item">
				<div class="image">
                                    
				</div>
				<div class="cnt">
                                    <h3><a href="profilemanager.php"><?php echo FOOTER_PROFILE_MANAGER; ?></a></h3>
                                    <p><?php echo DASHBOARD_PROFILE_MANAGER_CONTENT; ?></p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends 
			
			<!-- One item 
			<div class="item">
				<div class="image">
                                    
				</div>
				<div class="cnt">
                                    <h3><a href="promotionmanager.php"><?php echo FOOTER_PROMOTION_MANAGER; ?></a></h3>
                                    <p><?php echo DASHBOARD_PROMOTION_MANAGER_CONTENT; ?></p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends 
			
			<!-- One item 
			<div class="item" style="margin-right:0; ">
				<div class="image">
                                    
				</div>
				<div class="cnt">
                                    <h3><a href="integrationmanager.php"><?php echo FOOTER_INTEGRATION_MANAGER; ?> </a></h3>
                                    <p><?php echo DASHBOARD_INTEGRATION_MANAGER_CONTENT; ?> </p>
				</div>
			<div class="clear"></div>
			</div>			
			<!-- One item ends 
			
			
	<div class="clear"></div>
	</div>
	 One row ends -->
	
<div class="clear"></div>
</div>
<!--end  HELPPANEL CODE -->

<?php
include "includes/userfooter.php";
?>