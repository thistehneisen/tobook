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
//12,1,simple,5,1
//id,status,type,template,user
//../../../showsitepreview.php?id=12&status=1&type=simple&template=5&user=1&"
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
if($_GET["status"]=="0"){
       $qry=" select sp.ntempsp_id,sm.ntempsite_id,sm.nuser_id from tbl_tempsite_pages sp 
	   left join tbl_tempsite_mast sm on sp.ntempsite_id=sm.ntempsite_id where sp.ntempsite_id='".addslashes($_GET["id"])."' 
	   and sm.nuser_id='".$_SESSION["session_userid"]."' and sm.ntemplate_id='".$_GET["template"]."'";
	   
	   if(mysql_num_rows(mysql_query($qry))<=0){
		 echo "<script>alert('Site doesnot exist!!!');window.close();location.href='sitemanager.php';</script>";
		 exit; 
		}
       $_SESSION['session_currenttempsiteid'] = $_GET["id"];
}else{
        $qry="select sp.nsp_id,sm.nsite_id,sm.nuser_id from tbl_site_pages 
        sp left join tbl_site_mast sm on sp.nsite_id=sm.nsite_id where sp.nsite_id='".addslashes($_GET["id"])."' 
		and sm.nuser_id='".$_SESSION["session_userid"]."' and sm.ntemplate_id='".$_GET["template"]."'";
		if(mysql_num_rows(mysql_query($qry))<=0){
		 echo "<script>alert('Site doesnot exist!!!');window.close();location.href='sitemanager.php';</script>";
		 exit; 
		}
        $_SESSION["session_siteid"] = $_GET["id"];
}
$_SESSION['session_currenttemplateid']="";
$_SESSION['session_currenttemplateid']=$_GET["template"];
$tmpsiteid=$_SESSION['session_currenttempsiteid'];
$templateid=$_SESSION['session_currenttemplateid'];
$userid=$_SESSION["session_userid"];
$siteid=$_SESSION["session_siteid"];
if( $_GET["status"]=="1"){

}else{

}
         if( $_GET["status"]=="1"){
		   $qry=" select * from tbl_site_pages where nsite_id='$siteid' order by nsp_id limit 0,1";
		   $rs=mysql_query($qry);
		   $row=mysql_fetch_array($rs);
		   if($row['vtype']=="simple"){
		     $defaultpage="home.htm";
		   }else{
		      $defaultpage=$row['vpage_name'];
		   }
		    SaveSitePreview($userid,$templateid,$tmpsiteid,$siteid,"Yes",".","preview");
		    $location="./workarea/sites/$siteid/$defaultpage";
			if(is_file($location)){
				header("location:$location");
				exit;
		    }else{
			   header("location:defaultpage.php");
			   exit; 
			} 
           
		 }else{
		   $qry=" select * from tbl_tempsite_pages where ntempsite_id='$tmpsiteid' order by ntempsp_id limit 0,1";
		   //echo $qry;
		   $rs=mysql_query($qry);
		   $row=mysql_fetch_array($rs);
		   if($row['vtype']=="simple"){
		     $defaultpage="home.htm";
		   }else{
		      $defaultpage=$row['vpage_name'];
		   }
		 
		 
		    SaveSitePreview($userid,$templateid,$tmpsiteid,$siteid,"no",".","preview");
		    $location="./workarea/tempsites/$tmpsiteid/$defaultpage";
			//echo "loc==".$location;
			if(is_file($location)){
				header("location:$location");
				exit;
		    }else{
			   header("location:defaultpage.php");
			   exit; 
			}		
		 
		 }	
/*}*/		  

?>
