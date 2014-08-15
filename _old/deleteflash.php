<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                                      |
// |                                                                                                            |
// +----------------------------------------------------------------------+
?>
<?php
//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

$canbedeleted = true;
$resourcetext = "";

$sql = "SELECT nsite_id FROM tbl_site_mast WHERE nuser_id = '".addslashes($_SESSION["session_userid"])."' ";
$res = mysql_query($sql);
$siteimg = array();
if(mysql_num_rows($res)> 0){
        while($row = mysql_fetch_array($res)){
                $siteid = $row["nsite_id"];
                $actualfoldername  = "./sites/".$siteid;
                
                $resourcetext .= getResourceText($actualfoldername,"resource.txt" ) ;
                //echo "<br>Site: ".$siteid."<br>";
        }
}

$sql1 = "SELECT ntempsite_id FROM tbl_tempsite_mast WHERE nuser_id = '".addslashes($_SESSION["session_userid"])."' ";
$res1 = mysql_query($sql1);
//echo "<br>".$sql1."<br>";
if(mysql_num_rows($res1)> 0){
        while($row1 = mysql_fetch_array($res1)){
                $siteid = $row1["ntempsite_id"];
                $actualfoldername  = "./workarea/tempsites/".$siteid;
                $resourcetext .= getResourceText($actualfoldername,"resource.txt" ) ;
                //echo "<br>Temp Site: ".$siteid."<br>";
        }
}

//get the current page
$curpage=$_GET["page"];

//get the file name
$filename=$_GET["fname"];
$dir = "./usergallery/".$_SESSION["session_userid"]."/flash/";

$fullfile="$dir"."$filename";
$dir1 = "usergallery/".$_SESSION["session_userid"]."/flash/";
$fullfilename = $dir1.$filename;

//echo $resourcetext;
//echo "<br>---------------<br>";
$siteimg = array_values($siteimg);
$siteimg = array_unique($siteimg);

//print_r($siteimg);
//echo "<br>---------------<br>";
//
//echo "<br><br>Full File Name:   ".$fullfilename;
//echo "<br><br>File Name:   ".$filename;
if(in_array($filename,$siteimg)){
        $canbedeleted = false;
        //echo "<br><br>CanNOT be deleted since in database<br><br>";
}else if(strpos($resourcetext, $fullfilename) !== false){
        $canbedeleted = false;
        //echo "<br><br>CanNOT be deleted since in resource text<br><br>";
}else{
        $canbedeleted = true;
        //echo "<br><br>Can be deleted!!!!<br><br>";
}


if($canbedeleted){
        unlink($fullfile);
        header("location:manageflash.php?del=yes&curpage=$curpage");
}else{
        header("location:manageflash.php?del=no&curpage=$curpage");
}
exit;



?>