<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                      |
// |                                                                                                            |
// +----------------------------------------------------------------------+

include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

$canbedeleted = true;
$resourcetext = "";

$sql = "SELECT nsite_id,vlogo,vcompany,vcaption,vsub_logo,vsub_caption,vsub_company FROM tbl_site_mast WHERE nuser_id = '".addslashes($_SESSION["session_userid"])."' ";
$res = mysql_query($sql);
$siteimg = array();
if(mysql_num_rows($res)> 0){
        while($row = mysql_fetch_array($res)){
                $siteid = $row["nsite_id"];
                $actualfoldername  = "./sites/".$siteid;
                if($row["vlogo"]!=""){
                        array_push($siteimg,$row["vlogo"] );
                }
                if($row["vcompany"]!=""){
                        array_push($siteimg,$row["vcompany"] );
                }
                if($row["vcaption"]!=""){
                        array_push($siteimg,$row["vcaption"] );
                }
                if($row["vsub_logo"]!=""){
                        array_push($siteimg,$row["vsub_logo"] );
                }
                if($row["vsub_caption"]!=""){
                        array_push($siteimg,$row["vsub_caption"] );
                }
                if($row["vsub_company"]!=""){
                        array_push($siteimg,$row["vsub_company"] );
                }
                $resourcetext .= getResourceText($actualfoldername,"resource.txt" ) ;
                //echo "<br>Site: ".$siteid."<br>";
        }
}

$sql1 = "SELECT ntempsite_id,vlogo,vcompany,vcaption,vsub_logo,vsub_caption,vsub_company FROM tbl_tempsite_mast WHERE nuser_id = '".addslashes($_SESSION["session_userid"])."' ";
$res1 = mysql_query($sql1);

if(mysql_num_rows($res1)> 0){
        while($row1 = mysql_fetch_array($res1)){
                $siteid = $row1["ntempsite_id"];
                $actualfoldername  = "./workarea/tempsites/".$siteid;

                if($row1["vlogo"]!=""){
                        array_push($siteimg,$row1["vlogo"] );
                }
                if($row1["vcompany"]!=""){
                        array_push($siteimg,$row1["vcompany"] );
                }
                if($row1["vcaption"]!=""){
                        array_push($siteimg,$row1["vcaption"] );
                }
                if($row1["vsub_logo"]!=""){
                        array_push($siteimg,$row1["vsub_logo"] );
                }
                if($row1["vsub_caption"]!=""){
                        array_push($siteimg,$row1["vsub_caption"] );
                }
                if($row1["vsub_company"]!=""){
                        array_push($siteimg,$row1["vsub_company"] );
                }

                $resourcetext .= getResourceText($actualfoldername,"resource.txt" ) ;
        }
}

//get the current page
$curpage=$_GET["page"];

//get the file name
$filename=$_GET["fname"];
$dir = "./usergallery/".$_SESSION["session_userid"]."/images/";

$fullfile="$dir"."$filename";
$dir1 = "usergallery/".$_SESSION["session_userid"]."/images/";
$fullfilename = $dir1.$filename;

$siteimg = array_values($siteimg);
$siteimg = array_unique($siteimg);

if(in_array($filename,$siteimg)){
        $canbedeleted = false;
}else if(strpos($resourcetext, $fullfilename) !== false){
        $canbedeleted = false;
}else{
        $canbedeleted = true;
}

if($canbedeleted){
        unlink($fullfile);
        header("location:gallerymanager.php?del=yes&curpage=$curpage");
}else{
        header("location:gallerymanager.php?del=no&curpage=$curpage");
}
exit;



?>