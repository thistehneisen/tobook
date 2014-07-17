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
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$_SESSION['session_currenttemplateid']="1";
$_SESSION['session_sitecolor']="#678912";
$qry=" select * from tbl_template_mast where ntemplate_mast='".$_SESSION['session_currenttemplateid']."'";
$rs=mysql_query($qry);
$row=mysql_fetch_array($rs);
$templatecss=$row['vcss'];
$sitecolorcss=".variable { background-color:".$_SESSION['session_sitecolor']."; }";
$templatecss=$templatecss." ".$sitecolorcss;
//echo  $templatecss;


$inserqry ="insert into tbl_tempsite_mast(ntempsite_id,vsite_name,nuser_id,ntemplate_id,vtitle,vmeta_description,";
$inserqry .="vmeta_key,vlogo,vcompany,vcaption,vlinks,vcss,vcolor,vemail,ddate,vdelstatus)values('',";
$inserqry .="'".$_SESSION['session_sitename']."','".$userid."','".$_SESSION['session_currenttemplateid']."','".$_SESSION['session_sitetitle']."',";
$inserqry .="'".$_SESSION['session_sitemetadesc']."','".$_SESSION['session_sitemetakey']."','".$_SESSION['session_logobandname']."','".$_SESSION['session_companybandname']."',";
$inserqry .="'".$_SESSION['session_captionbandname']."','".$_SESSION['session_sitelinks']."','".addslashes($templatecss)."',";
$inserqry .="'".$_SESSION['session_sitecolor']."','".$_SESSION['session_sitemeemail']."',now(),'0')";

$buildhomepage  ="<html><head><title>".$_SESSION['session_sitetitle']."</title>";
$buildhomepage .="<link href=./style.css TYPE='text/css' REL=STYLESHEET>";
$buildhomepage .="<meta name='description' content=\"".$_SESSION['session_sitemetadesc']."\">";
$buildhomepage .="<meta name=\"keywords\" content=\"".$_SESSION['session_sitemetakey']."\"></head><body>";
$buildhomepage .=$row['vbefore_logo']."<img src='./images/".$_SESSION['session_logobandname']."'>".$row['vbefore_company'];
$buildhomepage .="<img src='./images/".$_SESSION['session_companybandname']."'>".$row['vbefore_caption'];
$buildhomepage .="<img src='./images/".$_SESSION['session_captionbandname']."'>".$row['vbefore_links'].$row['vlinks'].$row['vbefore_editable'].$row['veditable'].$row['vafter_editable'];
$buildhomepage .="</body></html>";
//echo $buildhomepage;
//copy the template images
    copydirr('./'.$_SESSION["session_template_dir"].'/1/images','workarea/tempsites/1/images',0777,false);
//copy userfiles
  copydirr('./usergallery/1/images','workarea/tempsites/1/images',0777,false);
  $fp=fopen("./workarea/tempsites/1/home.htm","w");
  fputs($fp,$buildhomepage);
  fclose($fp);
  chmod("./workarea/tempsites/1/home.htm",0777);
  //create site css
  
  $fp=fopen("./workarea/tempsites/1/style.css","w");
  fputs($fp,$templatecss);
  fclose($fp);
  chmod("./workarea/tempsites/1/style.css",0777);
?>
<html>
<head>
	<title></title>
</head>
<body>
  <a href="./workarea/tempsites/1/home.htm" target="new">Preview</a>
</body>
</html>