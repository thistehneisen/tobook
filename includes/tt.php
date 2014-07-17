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

function SavesiteData($userid,$tmpsiteid,$templateid,$links,$sitecolor,$logo,$sublogo,$caption,$subcaption,$company,$subcompany,$title,$metadesc,$metakey){
       //copy the template images
       copydirr("./templates/".$templateid."/images","./workarea/tempsites/".$tmpsiteid."/images",0777,false);
       //copy userfiles
       copydirr("./usergallery/".$userid."/images","workarea/tempsites/".$tmpsiteid."/images",0777,false);
	   //template css
	   //copy("./templates/".$templateid."/style.css","./workarea/tempsites/".$tmpsiteid."/style.css");
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
		   $subeditobaletext=$row['vsub_editable'];
		    //call smarty libraries
		    require('./smarty/lib/Smarty.class.php');
			remove_dir("./smarty/templates_c");
			$siteclrvalue=".variable { background-color:".$sitecolor."; }";
			$smarty = new Smarty();
			$smarty->template_dir = "./templates/".$templateid;
			$smarty->compile_dir = './smarty/templates_c';
			$smarty->cache_dir = './smarty/cache';
			$smarty->config_dir = './smarty/configs';
		   //insert page into tbl_tempsite_pages
			$qrystartvalues="";
			$qrystart="insert into tbl_tempsite_pages(ntempsp_id,ntempsite_id,vpage_name,vpage_title,vpage_type,vtype) values";
		   
	       if($links==""){
					    $sitelinks .="<a href=\"./home.htm\">".home."</a>";
						 //clear the cache
					    $smarty->clear_cache('index.tpl');
						$smarty->assign('vsite_metadesc', $metadesc);
						$smarty->assign('vsite_metakey', $metakey);
					    $smarty->assign('vsite_title', $title);
					    $smarty->assign('vsitecolor', $siteclrvalue);
					    $smarty->assign('vlogoband', $logo);
					    $smarty->assign('vcompanyband', $company);
					    $smarty->assign('captionband', $caption);
					    $smarty->assign('vsite_links', $sitelinks);
					    $html=$smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
						$fp=fopen("./workarea/tempsites/".$tmpsiteid."/home.htm","w");
				        fputs($fp,$html);
				        fclose($fp);
						$fp=fopen("./sitepages/tempsites/$tmpsiteid/home.htm","w");
						fputs($fp,$editobaletext);
				        fclose($fp);
						
						$qrystartvalues .="('','".$tmpsiteid."','home','".$title."','htm','".$_SESSION['session_buildtype']."'),";
		   }else{
					     $linkvaluesarray=explode(",",$links);
					     foreach($linkvaluesarray as $key=>$value){
						  	$pagename=$value.".htm";
						    $sitelinks .="<a href=\"./$pagename\">".$value."</a>".$templateseparator.$linkseparator;
							$subsitelinks .=$subtemplatesepartor."<a href=\"./$pagename\">".$value."</a>".$sublinkseparator;
						 }
						 
						 $sitelinks=substr($sitelinks,0,-1);
						 //$subsitelinks=substr($subsitelinks,0,-1);
						 foreach($linkvaluesarray as $key=>$value){
			  				 $pagename=$value.".htm";
			  				 if(strcmp($value,"home")==0){
							  
								//clear the cache
							    $smarty->clear_cache('index.tpl');
								$smarty->assign('vsite_metadesc', $metadesc);
								$smarty->assign('vsite_metakey', $metakey);
							    $smarty->assign('vsite_title', $title);
							    $smarty->assign('vsitecolor', $siteclrvalue);
							    
							    $smarty->assign('vlogoband', $logo);
							    $smarty->assign('vcompanyband', $company);
							    $smarty->assign('captionband', $caption);
							    $smarty->assign('vsite_links', $sitelinks);
							    $html=$smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
								$fp=fopen("./workarea/tempsites/".$tmpsiteid."/home.htm","w");
						        fputs($fp,$html);
						        fclose($fp);
								$fp=fopen("./sitepages/tempsites/$tmpsiteid/home.htm","w");
								fputs($fp,$editobaletext);
						        fclose($fp);
								$qrystartvalues .="('','".$tmpsiteid."','home','".$title."','htm','".$_SESSION['session_buildtype']."'),";
							 }else{
							    $smarty->clear_cache('subpage.tpl');
								$smarty->assign('vsite_metadesc', $metadesc);
								$smarty->assign('vsite_metakey', $metakey);
							    $smarty->assign('vsite_title', $title);
							    $smarty->assign('vsitecolor', $siteclrvalue);
								$smarty->assign('vinnserlogoband', $sublogo);
							    $smarty->assign('vinnercompanyband', $subcompany);
							    $smarty->assign('innercaptionband', $subcaption);
							    $smarty->assign('vsite_links', $subsitelinks);
								
							    $html=$smarty->fetch('subpage.tpl', $cache_id = null, $compile_id = null, false);
								$fp=fopen("./workarea/tempsites/".$tmpsiteid."/$pagename","w");
						        fputs($fp,$html);
						        fclose($fp);
								$fp=fopen("./sitepages/tempsites/$tmpsiteid/$pagename","w");
								fputs($fp,$subeditobaletext);
						        fclose($fp);
								$qrystartvalues .="('','".$tmpsiteid."','$pagename','".$title."','htm','".$_SESSION['session_buildtype']."'),"; 
							 
							 }
						 } 	 
		   }
	   
	  
	   
	    $qrystartvalues=substr($qrystartvalues,0,-1);
	   //Update table tempsite
	   
	    $qry="update tbl_tempsite_mast set vtitle='".$title."',vmeta_description='".$metadesc."',vmeta_key='".$metakey."',vlogo='".$logo."',";
		$qry .="vcompany='".$company."',vcaption='".$caption."',vlinks='".$sitelinks."',vcolor='".$sitecolor."',vemail='".$_SESSION['session_sitemeemail']."',ddate=now(),vdelstatus='0'";
		$qry .=",vsub_log='".$sublogo."',vsub_caption='".$subcaption."',vsub_company='".$subcompany."',vsub_sitelinks='".$subsitelinks."'";
        $qry .=" where ntempsite_id='".$tmpsiteid."'";
		mysql_query($qry);
		
		$pageqry=$qrystart.$qrystartvalues;
		//delete all the page content
		$qry="delete from tbl_tempsite_pages where ntempsite_id='".$tmpsiteid."'";
		mysql_query($qry);
		//insert into tbl_tempsite_pages
		mysql_query($pageqry);
	   
}

?>