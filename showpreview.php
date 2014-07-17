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
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$tmpsiteid=$_SESSION['session_currenttempsiteid'];
$templateid=$_SESSION['session_currenttemplateid'];
$userid=$_SESSION["session_userid"];
if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE); 
} 

function showPreview($userid,$tmpsiteid,$templateid,$links,$pagetype,$sitecolor,$logo,$innerlogo,$caption,$innercaption,$company,$innercompany,$title,$metadesc,$metakey){
                $title=htmlentities(stripslashes($title));
				$metadesc=htmlentities(stripslashes($metadesc));
				$metakey=htmlentities(stripslashes($metakey));
	   copy("./".$_SESSION["session_template_dir"]."/".$templateid."/style.css","./workarea/tempsites/".$tmpsiteid."/style.css");
       chmod("./workarea/tempsites/".$tmpsiteid."/style.css",0755);
	  
	   /*set the link values*/
	      //find the link seprator
		   $qry=" select * from tbl_template_mast where ntemplate_mast='".$_SESSION['session_currenttemplateid']."'";
           $rs=mysql_query($qry);
           $row=mysql_fetch_array($rs);
           $templateseparator=$row['vlink_separator'];
		   $linktype=$row['vlink_type'];
		   if($linktype=="vertical"){
		      $linkseparator="<br>";
		    
		   }else{
		     $linkseparator="";
		   }
		   $subtemplatesepartor=$row['vsublink_separator'];
		   $sublinktype=$row['vsublink_type'];
		   if($sublinktype=="vertical"){
		      $sublinkseparator="<br>";
		    
		   }else{
		     $sublinkseparator="";
		   }
		   $editobaletext=$row['veditable'];
		   $editobaletextold=$row['veditable'];
		   $subeditobaletext=$row['vsub_editable'];
		   $subeditobaletextold=$row['vsub_editable'];
		    require('./smarty/lib/Smarty.class.php');
			remove_dir("./smarty/templates_c");
			@mkdir("./smarty/templates_c",0777);
			@chmod("./smarty/templates_c",0777);
 
			if($sitecolor !=""){
			    $siteclrvalue=".variable { background-color:".$sitecolor."; }";
			}else{
			    $siteclrvalue=".default{ background-color:".$sitecolor."; }";
			}
			$smarty = new Smarty();
			$smarty->clear_compiled_tpl();
			$smarty->template_dir = "./".$_SESSION["session_template_dir"]."/".$templateid;
			$smarty->compile_dir = './smarty/templates_c';
			$smarty->cache_dir = './smarty/cache';
			$smarty->config_dir = './smarty/configs';
		    //clear the cache
		
		
	       if($links==""){
			  
				       if(is_file("./sitepages/tempsites/$tmpsiteid/home.htm")){
					     $editobaletext=file_get_contents("./sitepages/tempsites/$tmpsiteid/home.htm");
					   }else{
						   $editobaletext=$editobaletextold;
					   }
					   $defaultpage="home.htm";
				    $smarty->clear_cache('index.tpl');
			        $sitelinks .="<a class=anchor1 href=\"./home.htm\">".Home."</a>";
				    $smarty->assign('vsite_metadesc', $metadesc);
					$smarty->assign('vsite_metakey', $metakey);
				    $smarty->assign('vsite_title', $title);
				    $smarty->assign('vsitecolor', $siteclrvalue);
				    $smarty->assign('vlogoband', $logo);
				    $smarty->assign('vcompanyband', $company);
				    $smarty->assign('captionband', $caption);
				    $smarty->assign('vsite_links', $sitelinks);
					$smarty->assign('vsite_editable', $editobaletext);
				    $html=$smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
				
					$fp=fopen("./workarea/tempsites/".$tmpsiteid."/home.htm","w");
			        fputs($fp,$html);
			        fclose($fp);
					chmod("./workarea/tempsites/".$tmpsiteid."/home.htm",0777);
			    		
		   }else{
		   
		     $linkvaluesarray=explode(",",$links);
			 $pagetypearray=explode(",",$pagetype);
			 foreach($linkvaluesarray as $key=>$value){
			    $value1=str_replace(" ","" , $value);
				$value1=strtolower($value1);
				
			  	$pagename=$value1.".htm";
			                 	if($linktype=="vertical"){
								  //seprator first
										  if($sitelinks=="")
										   	$sitelinks .=$templateseparator."<a class=anchor1 href=\"./$pagename\">".$value."</a>";
										  else
										    $sitelinks .=$linkseparator.$templateseparator."<a class=anchor1 href=\"./$pagename\">".$value."</a>";	
								}else{
								     if($sitelinks=="")
									            $sitelinks .="<a class=anchor1 href=\"./$pagename\">".$value."</a>";
									 else
								   				$sitelinks .=$templateseparator."<a class=anchor1 href=\"./$pagename\">".$value."</a>".$linkseparator;
								}
								if($sublinktype=="vertical"){
								   if($subsitelinks=="")
								   		$subsitelinks .=$templateseparator."<a class=anchor1 href=\"./$pagename\">".$value."</a>";
								   else
								        $subsitelinks .=$sublinkseparator.$subtemplatesepartor."<a class=anchor1 href=\"./$pagename\">".$value."</a>";		
								}else{
								    if($subsitelinks=="")
								    	$subsitelinks .="<a class=anchor1 href=\"./$pagename\">".$value."</a>";
									else
									   $subsitelinks .=$subtemplatesepartor."<a class=anchor1 href=\"./$pagename\">".$value."</a>".$sublinkseparator;
								}
				//$sitelinks .="<a class=anchor1 href=\"./$pagename\">".$value."</a>".$templateseparator.$linkseparator;
				//$subsitelinks .=$subtemplatesepartor."<a class=anchor1 href=\"./$pagename\">".$value."</a>".$sublinkseparator;
			 } 	
			 $sitelinks=substr($sitelinks,0,-1);
			 $subsitelinks=substr($subsitelinks,0,-1);
		     foreach($linkvaluesarray as $key=>$value){
			  
				
			    $value1=str_replace(" ","" , $value);
				$value1=strtolower($value1);
			  	$pagename=$value1.".htm";
				if($key==0){
				  $defaultpage=$pagename;
				}
				//echo "<br>pagename==$value and srcmp=".strcasecmp ($value,"home");
				 
			  				 if(strcasecmp($value,"home")==0 or ($pagetypearray[$key]=="homepage") ){
							             if(is_file("./sitepages/tempsites/$tmpsiteid/$pagename")){
										     $editobaletext=file_get_contents("./sitepages/tempsites/$tmpsiteid/$pagename");
										  }else{
										    $editobaletext=$editobaletextold;
										  }
										 
							                $smarty->clear_cache('index.tpl');
							                
										    $smarty->assign('vsite_metadesc', $metadesc);
											$smarty->assign('vsite_metakey', $metakey);
										    $smarty->assign('vsite_title', $title);
										    $smarty->assign('vsitecolor', $siteclrvalue);
										    $smarty->assign('vlogoband', $logo);
										    $smarty->assign('vcompanyband', $company);
										    $smarty->assign('captionband', $caption);
										    $smarty->assign('vsite_links', $sitelinks);
											$smarty->assign('vsite_editable', $editobaletext);
										    $html=$smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
											
												$fp=fopen("./workarea/tempsites/".$tmpsiteid."/$pagename","w");
										        fputs($fp,$html);
										        fclose($fp);
												chmod("./workarea/tempsites/".$tmpsiteid."/$pagename",0777);
												
											
							 }else{
							         //subpage link
									        if(is_file("./sitepages/tempsites/$tmpsiteid/$pagename")){
											     $subeditobaletext=file_get_contents("./sitepages/tempsites/$tmpsiteid/$pagename");
												 //$subeditobaletext="hello";
											 }else{
											     $subeditobaletext=$subeditobaletextold;
											 }
											
									          $smarty->clear_cache('subpage.tpl');
									        
										    $smarty->assign('vsite_metadesc', $metadesc);
											$smarty->assign('vsite_metakey', $metakey);
										    $smarty->assign('vsite_title', $title);
										    $smarty->assign('vsitecolor', $siteclrvalue);
										    $smarty->assign('vinnserlogoband', $innerlogo);
										    $smarty->assign('vinnercompanyband', $innercompany);
										    $smarty->assign('innercaptionband', $innercaption);
										    $smarty->assign('vsite_links', $subsitelinks);
											$smarty->assign('vsubsite_editable', $subeditobaletext);
										    $html=$smarty->fetch('subpage.tpl', $cache_id = null, $compile_id = null, false);
											
												$fp=fopen("./workarea/tempsites/".$tmpsiteid."/$pagename","w");
										        fputs($fp,$html);
										        fclose($fp);
												chmod("./workarea/tempsites/".$tmpsiteid."/$pagename",0777);
												
							 
							 }
				
			 }
			 
		   }
		
	   $location="./workarea/tempsites/".$tmpsiteid."/$defaultpage";
	   //echo $location;
	 // echo file_get_contents($location);
       header("location:$location");
       exit;
	   
}
function isValidLinkname($links)
{

    if (trim($links) !="" ) {
          if ( eregi ( "[^0-9a-zA-Z+, ]", $links ) ) {
                         return false;
             }else{
                    return true;
         }
    }else{
                return false;
    }

}
$linkvalues=substr(addslashes(trim($_GET['linkvalues'])),0,-1);
$linktypevalues=substr(addslashes(trim($_GET['linktypevalues'])),0,-1);
if(!isValidLinkname($linkvalues)){
  echo "Invalid link values.use only alphabets,digits and space";
  exit;
}

showPreview($userid,$tmpsiteid,$templateid,$linkvalues,$linktypevalues,$_SESSION['session_sitecolor'],$_SESSION['session_logobandname'],$_SESSION['session_innerlogobandname'],$_SESSION['session_captionbandname'],$_SESSION['session_innercaptionbandname'],$_SESSION['session_companybandname'],$_SESSION['session_innercompanybandname'],$_SESSION['session_sitetitle'],$_SESSION['session_sitemetadesc'],$_SESSION['session_sitemetakey']);

?>
