<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// +----------------------------------------------------------------------+
?>
<?php
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/adminheader.php";
//id,status,type,template,user
$_SESSION['session_currenttempsiteid']="";
$_SESSION['session_currenttemplateid']="";
$_SESSION["session_userid"]="";
$_SESSION["session_siteid"]="";
if($_GET["status"]=="0"){

$_SESSION['session_currenttempsiteid'] = $_GET["id"];

}else{

$_SESSION["session_siteid"] = $_GET["id"];

}

$_SESSION["session_userid"]=$_GET["user"];
$_SESSION['session_currenttemplateid']=$_GET["template"];

$tmpsiteid=$_SESSION['session_currenttempsiteid'];
$templateid=$_SESSION['session_currenttemplateid'];
$userid=$_SESSION["session_userid"];
$siteid=$_SESSION["session_siteid"];

/*if($_SESSION['session_edittype']=="edit"){
            SaveSitePreview($userid,$templateid,$tmpsiteid,$siteid,"Yes",".","preview");
		    $location="./workarea/sites/$siteid/home.htm";
			header("location:$location");
			exit;
}else{*/
         if( $_GET["status"]=="1"){
		   $qry=" select * from tbl_site_pages where nsite_id='$siteid' order by nsp_id limit 0,1";
		   $rs=mysql_query($qry);
		   $row=mysql_fetch_array($rs);
		   if($row['vtype']=="simple"){
		     $defaultpage="home.htm";
		   }else{
		      $defaultpage=$row['vpage_name'];
		   }
		    SaveSitePreview($userid,$templateid,$tmpsiteid,$siteid,"Yes","..","preview");
			$location="../workarea/sites/$siteid/$defaultpage";
			if(is_file($location)){
				header("location:$location");
				exit;
		    }else{
			   header("location:../defaultpage.php");
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
		 
		 //echo "default==".$defaultpage;
		    SaveSitePreview($userid,$templateid,$tmpsiteid,$siteid,"no","..","preview");
		    $location="../workarea/tempsites/$tmpsiteid/$defaultpage";
			//echo "loc==".$location;
			if(is_file($location)){
				header("location:$location");
				exit;
		    }else{
			   header("location:../defaultpage.php");
			   exit; 
			}		
		 
		 }	
/*}*/		  

?>
