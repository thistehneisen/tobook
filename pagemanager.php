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
//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$userid=$_SESSION["session_userid"];
/* 'cmbSitename' will be combination of siteid,templateid,type,status separated by '|'
*   siteid -> the id of the site.it may be either id in tbl_tempsite_mast or is in tbl_site_mast
*   type ->simple or advanced
*   status- > published or not
*/  
if($_POST['cmbSitename'] !=""){
 $cmbSitename=addslashes($_POST['cmbSitename']);
 $cmbSitenamearray=explode("|",$cmbSitename);
 $siteid=$cmbSitenamearray[0];
 $sitetemplate=$cmbSitenamearray[1];
 $sitetype=$cmbSitenamearray[2];
 $sitestatus=$cmbSitenamearray[3];
}
if($_POST['btnback']=="Back"){
   header("location:pagemanager.php");
   exit;
}
include "includes/userheader.php";
?>
<script>
function changesitename(){
 
  document.frmPageManager.submit();
}
function showHomePagepreview(id){
            var leftPos = (screen.availWidth-500) / 2;
		    var topPos = (screen.availHeight-400) / 2 ;
			winurl="showhomepagepriview.php?id="+id+"&";
		    //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
		    showpriview = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos); 

           
			 
}
function showSubPagepreview(id){
            var leftPos = (screen.availWidth-500) / 2;
		    var topPos = (screen.availHeight-400) / 2 ;
			winurl="showsubpagepriview.php?id="+id+"&";
		    //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
		    showpriview = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos); 

           
			 
}
</script>
<h1 class="main_heading">Page Manager</h1>
<table width="100%"  border="0">
  
  <tr>
   
    <td colspan=3 align=center>
	  
	  
	  
		<div class="common_box"><br>
		<p align=center class=maintext>Select a site whose pages you would like to add/delete.</p>	  
	   <table>
	      <tr>
		     <td>
			       <table>
				       <?php
					   $sql = "select IFNULL(ts.ntempsite_id,si.nsite_id) as 'nsite_id',IFNULL(ts.ntemplate_id,si.ntemplate_id) as 'ntemplate_id',
								IFNULL(ts.vsite_name,si.vsite_name) as 'vsite_name',IFNULL(Date_Format(ts.ddate,'%m/%d/%Y'),
								Date_Format(si.ddate,'%m/%d/%Y')) as 'ddate',IF( ISNULL(ts.ntempsite_id), 'Completed', 'Under Construction' ) as 'status',
								IFNULL(ts.vtype,si.vtype) as 'vtype'
								from dummy d left join tbl_tempsite_mast ts ON(d.num=0 AND ts.nuser_id='" . $_SESSION["session_userid"] . "' " . $qryopt1 . ")
								left join tbl_site_mast si on(d.num=1 AND si.nuser_id='" . $_SESSION["session_userid"] . "' AND si.ndel_status='0' " . $qryopt2 . ")
								 where ts.ntemplate_id IS NOT NULL OR si.nsite_id IS NOT NULL ";

					   $rs = mysql_query($sql);
					   ?>
					  <form name=frmPageManager method=post>
				      <tr>
					    <td class=maintext>Site name</td>
						 <td>
						   <select name=cmbSitename id=cmbSitename class=selectbox onchange="if(this.value != 0){ changesitename(); }">
						   <option value="0">Select site</option>
						      <?php while ($arr = mysql_fetch_array($rs)) { 
							     $siteid_type_templateid=$arr["nsite_id"]."|".$arr["ntemplate_id"]."|".$arr["vtype"]."|".$arr["status"];
							  ?>
							     <option value="<?php echo $siteid_type_templateid; ?>" <?php if(strcasecmp($cmbSitename,$siteid_type_templateid)==0) echo "selected" ; ?>><?php echo stripslashes($arr["vsite_name"]); ?></option>
							  <?php } ?>
						   </select>
						 </td>
					  </tr>
					  </form> 
				   </table>
				   
			 </td>
		  </tr>
		  <tr>
		  <td class=maintext>
		  <?php 
		  /* when user select a site.
		     the POST varibale 'cmbSitename' will be combination of siteid,templateid,type,status separated by '|'
			*   siteid -> the id of the site.it may be either id in tbl_tempsite_mast or is in tbl_site_mast
			*   type ->simple or advanced
			*   status- > published or not
			*/
		 
		  if($_POST['cmbSitename'] !=""){
		         /* user can add page(s) to his/her site only after add his/her site details
				 * 
				 */ 
		         if($sitetype=="simple"){
						 if(strcasecmp($sitestatus,"Completed")==0){
						    $qry="select * from tbl_site_mast where vtype='simple' and nsite_id='".$siteid."' and nuser_id='".$_SESSION["session_userid"]."'";
					     }else{
						    $qry="select * from tbl_tempsite_mast where vtype='simple' and ntempsite_id='".$siteid."' and nuser_id='".$_SESSION["session_userid"]."'";
						 }		
						    $rs=mysql_query($qry);
							if(mysql_num_rows($rs)>0){
								   $row=mysql_fetch_array($rs);
								   if($row['vlogo'] !=""){
								        require("./pagemanager_inc.php");
								   }else{
								       echo "<br><br>You should upload your logo image before adding a web page";
								   }
							}	
						 	 
		           
				 }else if($sitetype=="advanced"){
				    require("./pagemanageradv_inc.php"); 
				 }else{
				   echo "invalid site name";
				   
				 }  
			   } 
		   ?>
		  </td>
		    
		  </tr>
	   </table>
	   <br>&nbsp;
</div>	
<br>
<div align="left"><a href="usermain.php" class=subtext ><img src="./images/back.gif" border="0" width="54px" height="15px"></a>  </div>
	</td>
  
</table>
<?php
include "includes/userfooter.php";
?>