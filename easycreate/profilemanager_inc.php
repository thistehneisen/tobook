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
 $siteid=$cmbSitenamearray[0];
 $sitetemplate=$cmbSitenamearray[1];
 $sitetype=$cmbSitenamearray[2];
 $sitestatus=$cmbSitenamearray[3];
 
 $_SESSION['session_siteid']="";
 $_SESSION['session_currenttemplateid']="";
 $_SESSION['session_edittype']="";
 if(strcasecmp($sitestatus,"Completed")==0){
     $_SESSION['session_siteid']=$siteid;
	 $_SESSION['session_currenttemplateid']=$sitetemplate;
	 $_SESSION['session_edittype']="edit";
 }else{
    $_SESSION['session_currenttempsiteid']=$siteid;
	$_SESSION['session_currenttemplateid']=$sitetemplate;
    $_SESSION['session_edittype']="new"; 
 }
$tmpsiteid=$_SESSION['session_currenttempsiteid'];
$templateid=$_SESSION['session_currenttemplateid'];
$userid=$_SESSION["session_userid"];
$siteid=$_SESSION['session_siteid'];

 function isValidLinkname($links)
{

    if (trim($links) !="" ) {
          if ( preg_match ( "[^0-9a-zA-Z+, ]", $links ) ) {
                         return false;
             }else{
                    return true;
         }
    }else{
                return false;
    }

}
function getDeletedImages($filelocation,$resourcelocation){
   $var_deletedimages="";
   $filecontent=file_get_contents($filelocation);
   $fp=fopen($resourcelocation,"r");
   $imagearray=array();
   while($str=fgets($fp)){
  			$searchstring=substr(basename($str),0,-1);
  			array_push($imagearray, $searchstring);
  	}
	$imagearray=array_unique($imagearray);
	foreach($imagearray as $key=>$value){
	   $i=0;
	   $cnt=substr_count($filecontent,$value);
	   for($i=0;$i<$cnt;$i++){
	     $var_deletedimages .=$value."|";
	   }
	
	
	}

   return  $var_deletedimages;
  
}

function SaveResource($resource_location,$var_deletedimages,$var_newimages) {

			if(is_file($resource_location)) {
			$content_arr = file($resource_location);
			if(strlen($var_deletedimages) > 0) {	// IF Delete images > 0
				$deleted_arr = explode('|',$var_deletedimages);
				$deleted_count = count($deleted_arr);
				
				for($i=0;$i < $deleted_count;$i++) {	// FOR LOOP - I
						$temp_string=$deleted_arr[$i];
						switch(substr($temp_string,0,2)) {
							case "ug":
									$temp_string = "usergallery/".$_SESSION["session_userid"]."/images/". $temp_string;
									break;
							case "sg":
									$temp_string = "systemgallery/" . $temp_string;
									break;
							case "sl":
									$temp_string = "samplelogos/" . $temp_string;
									break;
							default:
									continue;											
						}
					$temp_count = 0;	
					foreach($content_arr as $lines) {	// FOR LOOP - II
						if(strcmp($temp_string,rtrim($lines,"\n")) == 0) {
							$content_arr[$temp_count] = "";
							break;
						}
						$temp_count++;
					}		// END FOR LOOP - II	
				}			// END FOR LOOP - I
			}		// END IF Delete images > 0
			}
			$fwriter=fopen($resource_location,"w");
			if(count($content_arr) > 0) {
				foreach($content_arr as $lines) {	// FOR LOOP 
					if($lines != "") {
						fputs($fwriter,$lines);
					}
				}
			}
			if(strlen($var_newimages) > 0) {	// IF New images > 0
				$new_arr = explode('|',$var_newimages);
				$new_count = count($new_arr);
				for($i=0;$i < $new_count;$i++) {	// FOR LOOP - I
					$temp_string=$new_arr[$i];
					switch(substr($temp_string,0,2)) {
						case "ug":
								$temp_string = "usergallery/".$_SESSION["session_userid"]."/images/" . $temp_string . "\n";
								break;
						case "sg":
								$temp_string = "systemgallery/" . $temp_string . "\n";
								break;
						case "sl":
								$temp_string = "samplelogos/" . $temp_string . "\n";
								break;
						default:
								continue;											
					}
					fputs($fwriter,$temp_string);
				}
			}	// END IF New images > 0
			fclose($fwriter);
}
 //find old links
 function findoldlinks($siteid,$templateid,$userid,$sitetype,$sitestatus){
        echo "sitestatus==".$sitestatus."--type=".$sitetype;
		           if(strcasecmp($sitestatus,"Completed" ) !=0){   //if  site completed
		                        if(strcasecmp($sitetype,"simple" )==0){
						            $qry="select * from tbl_tempsite_pages where ntempsite_id='".$siteid."' order by ntempsp_id";
									$rs1=mysql_query($qry);
									$editsitelinks="";
									while($row1=mysql_fetch_array($rs1)){
										  $editsitelinks .=$row1['vpage_name'].",";
									}
									$editsitelinks=substr($editsitelinks,0,-1 );
									$_SESSION['session_sitelinks']=$editsitelinks;
								}else{
								    $qry="select * from tbl_tempsite_pages where ntempsite_id='".$siteid."' order by ntempsp_id";
									$rs1=mysql_query($qry);
									$editsitelinks="";
									while($row1=mysql_fetch_array($rs1)){
										   $editsitelinks .=substr($row1['vpage_name'],0,-4).",";
									}
									$editsitelinks=substr($editsitelinks,0,-1 );
									$_SESSION['session_sitelinks']=$editsitelinks;
								}				 
				   }else{
				               if(strcasecmp($sitetype,"simple" )==0){
				   
				                    $qry="select * from tbl_site_pages where nsite_id='".$siteid."' order by nsp_id";
									
									$rs1=mysql_query($qry);
									$editsitelinks="";
									while($row1=mysql_fetch_array($rs1)){
										  $editsitelinks .=$row1['vpage_name'].",";
									}
									$editsitelinks=substr($editsitelinks,0,-1 );
									$_SESSION['session_sitelinks']=$editsitelinks;	
								}else{
								    $qry="select * from tbl_site_pages where nsite_id='".$siteid."' order by nsp_id";
									//echo $qry;
									$rs1=mysql_query($qry);
									$editsitelinks="";
									while($row1=mysql_fetch_array($rs1)){
								
										  $editsitelinks .=substr($row1['vpage_name'],0,-4).",";
									}
									$editsitelinks=substr($editsitelinks,0,-1 );
									$_SESSION['session_sitelinks']=$editsitelinks;	
								}			
				   }	
					
		
 
 }
 //save site links in simple sites
 function SaveSimpleLinks($userid,$tmpsiteid,$siteid,$templateid,$links){
        //save in tempsite location
		
		    require('./smarty/lib/Smarty.class.php');
			remove_dir("./smarty/templates_c");
			$siteclrvalue=".variable { background-color:".$sitecolor."; }";
			$smarty = new Smarty();
			$smarty->template_dir = "./".$_SESSION["session_template_dir"]."/".$templateid;
			$smarty->compile_dir = './smarty/templates_c';
			$smarty->cache_dir = './smarty/cache';
			$smarty->config_dir = './smarty/configs';
        if($tmpsiteid>0){
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
		
		
		
						   $linkvaluesarray=explode(",",$links);
						   $linkvaluesarray=explode(",",$links);
						   foreach($linkvaluesarray as $key=>$value){
							    $value1=str_replace(" ","" , $value);
							    $value1=strtolower($value1);
				  				$pagename=$value1.".htm";
							     $sitelinks .="<a class=anchor1 href=\"./$pagename\">".$value."</a>".$templateseparator.$linkseparator;
								$subsitelinks .=$subtemplatesepartor."<a class=anchor1 href=\"./$pagename\">".$value."</a>".$sublinkseparator;
						   }
						 
						   $sitelinks=substr($sitelinks,0,-1);
						   $qrystartvalues="";
						   $qrystart="insert into tbl_tempsite_pages(ntempsp_id,ntempsite_id,vpage_name,vpage_title,vpage_type,vtype) values";
						   foreach($linkvaluesarray as $key=>$value){
						                     $value1=str_replace(" ","" , $value);
										     $value1=strtolower($value1);
							  				 $pagename=$value1.".htm";
							  				
							  				 if(strcasecmp($value,"home")==0){
											  
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
												if(!is_file("./sitepages/tempsites/$tmpsiteid/home.htm")){
													$fp=fopen("./workarea/tempsites/".$tmpsiteid."/home.htm","w");
											        fputs($fp,$html);
											        fclose($fp);
													$fp=fopen("./sitepages/tempsites/$tmpsiteid/home.htm","w");
													fputs($fp,$editobaletext);
											        fclose($fp);
												}	
												$qrystartvalues .="('','".$tmpsiteid."','Home','".$title."','htm','".$_SESSION['session_buildtype']."'),";
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
												if(!is_file("./sitepages/tempsites/$tmpsiteid/$pagename")){
													$fp=fopen("./workarea/tempsites/".$tmpsiteid."/$pagename","w");
											        fputs($fp,$html);
											        fclose($fp);
													$fp=fopen("./sitepages/tempsites/$tmpsiteid/$pagename","w");
													fputs($fp,$subeditobaletext);
											        fclose($fp);
												}	
												if(strcasecmp($value,"feedback")==0){
												   $qrystartvalues .="('','".$tmpsiteid."','$value','".$title."','htm','feedback'),"; 
												}else{
													$qrystartvalues .="('','".$tmpsiteid."','$value','".$title."','htm','".$_SESSION['session_buildtype']."'),"; 
												}	
											 
											 }
						   }
						    /*if(get_magic_quotes_gpc()==0){
							     $sitelinks=$sitelinks;
							     $subsitelinks=$subsitelinks;
							 }else{
							     $sitelinks=addslashes($sitelinks);
							     $subsitelinks=addslashes($subsitelinks);
							 }*/
						    $qry="update tbl_tempsite_mast set ";
							$qry .="vlinks='".$sitelinks."'";
							$qry .=",vsub_sitelinks='".$subsitelinks."'";
					        $qry .=" where ntempsite_id='".$tmpsiteid."'";
							mysql_query($qry);
						   
						   
						    $qrystartvalues=substr($qrystartvalues,0,-1);
		                    $pageqry=$qrystart.$qrystartvalues;
							//delete all the page content
							$qry="delete from tbl_tempsite_pages where ntempsite_id='".$tmpsiteid."'";
							mysql_query($qry);
							//insert into tbl_tempsite_pages
							//echo "pagequery".$pageqry;
							mysql_query($pageqry);
	
		}else if($siteid>0){
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
		
		
		
						   $linkvaluesarray=explode(",",$links);
						   $linkvaluesarray=explode(",",$links);
						   foreach($linkvaluesarray as $key=>$value){
							    $value1=str_replace(" ","" , $value);
							    $value1=strtolower($value1);
				  				$pagename=$value1.".htm";
							     $sitelinks .="<a class=anchor1 href=\"./$pagename\">".$value."</a>".$templateseparator.$linkseparator;
								$subsitelinks .=$subtemplatesepartor."<a class=anchor1 href=\"./$pagename\">".$value."</a>".$sublinkseparator;
						   }
		                   $sitelinks=substr($sitelinks,0,-1);
						   $qrystartvalues="";
						   $qrystart="insert into tbl_site_pages(nsp_id,nsite_id,vpage_name,vpage_title,vpage_type,vtype) values";
						   foreach($linkvaluesarray as $key=>$value){
						                     $value1=str_replace(" ","" , $value);
										     $value1=strtolower($value1);
							  				 $pagename=$value1.".htm";
							  				
							  				 if(strcasecmp($value,"home")==0){
											  
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
												if(!is_file("./sites/$siteid/home.htm")){
													$fp=fopen("./workarea/sites/".$siteid."/home.htm","w");
											        fputs($fp,$html);
											        fclose($fp);
													$fp=fopen("./sites/$siteid/home.htm","w");
													fputs($fp,$editobaletext);
											        fclose($fp);
													
												}	
												$qrystartvalues .="('','".$siteid."','Home','".$title."','htm','".$_SESSION['session_buildtype']."'),";
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
												if(!is_file("./sites/$siteid/$pagename")){
													$fp=fopen("./workarea/sites/".$siteid."/$pagename","w");
											        fputs($fp,$html);
											        fclose($fp);
													//echo "pagename".$pagename."<br>".$subeditobaletext;
													$fp=fopen("./sites/$siteid/$pagename","w");
													fputs($fp,$subeditobaletext);
											        fclose($fp);
													
												}	
												if(strcasecmp($value,"feedback")==0){
												   $qrystartvalues .="('','".$siteid."','$value','".$title."','htm','feedback'),"; 
												}else{
												  $qrystartvalues .="('','".$siteid."','$value','".$title."','htm','".$_SESSION['session_buildtype']."'),"; 
												}
												
											 
											 }
						   }
						  /* if(get_magic_quotes_gpc()==0){
							   $sitelinks=$sitelinks;
							     $subsitelinks=$subsitelinks;
						   }else{
							     $sitelinks=addslashes($sitelinks);
							     $subsitelinks=addslashes($subsitelinks);
						   }*/
						    $qry="update tbl_site_mast set ";
							$qry .="vlinks='".$sitelinks."'";
							$qry .=",vsub_sitelinks='".$subsitelinks."'";
					        $qry .=" where nsite_id='".$siteid."'";
							mysql_query($qry);
						   
						   
						    $qrystartvalues=substr($qrystartvalues,0,-1);
		                    $pageqry=$qrystart.$qrystartvalues;
							//delete all the page content
							$qry="delete from tbl_site_pages where nsite_id='".$siteid."'";
							mysql_query($qry);
							//insert into tbl_tempsite_pages
							//echo "pagequery".$pageqry;
							mysql_query($pageqry);
		
		}


	   
}
 if ($_POST["postback"] == "Save") {
                    $errorinlink="";
					$linkvalues=substr(addslashes(trim($_POST['linkname'])),0,-1);
					$homepageexist="0";
				    $linkarr=explode(",",$linkvalues);
					foreach($linkarr as $key =>$value){
					     if(strcasecmp($value,"Home") =="0" ){
						   $homepageexist="1";
						  }
					 }
					if(!isValidLinkname($linkvalues)){
					  $errorinlink="Invalid link values.use only alphabets,digits and space.";
					
					}else if( $homepageexist=="0" or $linksvalueexceeds=="1"){
					    $errorinlink="Invalid link values.either home page does not exist or page length exceeds 20 character.";
					}else{
					      //delete the removed link
					               //delete
			                      $_SESSION['session_sitelinks'] =substr(addslashes($_POST['linkname']),0,-1);
					              if( $_SESSION['session_oldsitelinks'] !=""){
								 
								         $oldlinksarray=explode(",",$_SESSION['session_oldsitelinks']);
										 $newlinksarray=explode(",",$_SESSION['session_sitelinks']);
										 //echo count($oldlinksarray)."and=".count($newlinksarray);
										 $type="tempsite";
										// if(count($oldlinksarray)>count($newlinksarray)){
										        $differencelinks=array_diff($oldlinksarray, $newlinksarray);
												foreach($differencelinks as $key=>$value){
														   $value1=str_replace(" ","" , $value);
								                           $value1=strtolower($value1); 
														   $pagename=$value1.".htm";
														   if($_SESSION['session_edittype']=="edit"){
														           if(is_file("./workarea/sites/".$siteid."/$pagename"))
																   unlink("./workarea/sites/".$siteid."/$pagename");
														           if(is_file("./sites/".$siteid."/$pagename")){
																      $resource_location="./workarea/sites/$siteid/resource.txt";
																	  $deltedimages=getDeletedImages("./sites/$siteid/$pagename","./workarea/sites/".$siteid."/resource.txt");
																	  SaveResource($resource_location,$deltedimages,"");
																	  //update resource file in site
																	  @copy("./workarea/sites/".$siteid."/resource.txt","./sites/".$siteid."/resource.txt");
																	  unlink("./sites/".$siteid."/$pagename");
																   
																   }
																   
																   
														   }else{
														          if(is_file("./workarea/tempsites/".$tmpsiteid."/$pagename"))
																   unlink("./workarea/tempsites/".$tmpsiteid."/$pagename");
																   if(is_file("./sitepages/tempsites/$tmpsiteid/$pagename")){
																      $resource_location="./workarea/tempsites/$tmpsiteid/resource.txt";
																	  $deltedimages=getDeletedImages("./sitepages/tempsites/$tmpsiteid/$pagename","./workarea/tempsites/".$tmpsiteid."/resource.txt");
																	  SaveResource($resource_location,$deltedimages,"");
																	  unlink("./sitepages/tempsites/$tmpsiteid/$pagename");
																   
																   } 
																    
														   }
												 
												   
												}
										// }
								  }
								  $_SESSION['session_oldsitelinks']=$_SESSION['session_sitelinks'];
								  $linkvalues=addslashes(trim($_SESSION['session_sitelinks']));
								
								   if($_SESSION['session_edittype']=="edit"){
								     SaveSimpleLinks($userid,"","$siteid",$templateid,$linkvalues);
								   }else{
								  
								  
								     SaveSimpleLinks($userid,$tmpsiteid,"",$templateid,$linkvalues);
								   }
								  $message="Pages saved successfully!!!";
					
					}
 }else{
     if($_SESSION['session_edittype']=="edit"){
     findoldlinks($siteid,$templateid,$userid,$sitetype,$sitestatus) ;
}else{
     findoldlinks($tmpsiteid,$templateid,$userid,$sitetype,$sitestatus) ;
}
 }	 
 echo $_SESSION['session_sitelinks'];
?>
<script>
<!--
function validchars(strstring,allowedchar,strl){

	            var ch;
				//strstring=trim(strstring);
				if(strstring.length>strl){
				   return false;
				}   
				
		        for (i = 1;  i < strstring.length;i++){
							ch = strstring.charAt(i);
							for (j = 0;  j < allowedchar.length;  j++)
							   if (ch == allowedchar.charAt(j))
								    break;
									
							   if (j == allowedchar.length){
							        return false;
									break;
							   }
                }
	       return true;
}
  var innerhtmlarray=new Array();
  //declare array
  for(var arraylimit=0;arraylimit<100;arraylimit++){
  innerhtmlarray[arraylimit]=new Array();
 }
  
  function moveup(count,pagename){
    	 var linkhtml="";
		 var currcount=document.getElementById("linkcount").value;
		 countminus1=parseInt(count)-1;
		 tmp1pagename=innerhtmlarray[count][1];
		 tmp2pagename=innerhtmlarray[countminus1][1];
		 innerhtmlarray[countminus1][1]=tmp1pagename;
		 innerhtmlarray[count][1]=tmp2pagename;
		for(var i=0;i<currcount;i++){
		   var oldpagename=innerhtmlarray[i][1];
		    if(oldpagename=="###deleted####")
		     continue;
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='40%' class=maintext align=left>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td align=left class=maintext>Move Up";
		   }else{
		     var2="<td align=left class=maintext><a class=anchor   href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   if(i==parseInt(currcount)-1){
		     var3="Move Down";
		   }else{
		     var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		   }
		    if(oldpagename=="Home"  ){
		   		var4="Remove</td></tr></table>";
		   }else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td></tr></table>"; 
		   }
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;;
		   innerhtmlarray[i][1]=oldpagename; 
		   
	       linkhtml= linkhtml+innerhtmlarray[i][0];
	    }
	    document.getElementById("addedpages").innerHTML=linkhtml;
  }
  function movedown(count,pagename){
    	 var linkhtml="";
		 var currcount=document.getElementById("linkcount").value;
		 currcountwithdeletd=currcount;
		 countplus1=parseInt(count)+1;
		 countminus1=parseInt(count)-1;
		 tmp1pagename=innerhtmlarray[count][1];
		 tmp2pagename=innerhtmlarray[countplus1][1];
		 innerhtmlarray[count][1]=tmp2pagename;
		 innerhtmlarray[countplus1][1]=tmp1pagename;
		for(var i=0;i<currcount;i++){
		   var oldpagename=innerhtmlarray[i][1];
		    if(oldpagename=="###deleted####"){
			  //currcountwithdeletd=currcountwithdeletd -1;
		      continue;
		   }
		   var1="<table width='100%'><tr bgcolor=#CCCCCC ><td width='40%' class=maintext align=left>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td class=maintext align=left>Move Up";
		   }else{
		     var2="<td class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   if(i==parseInt(currcount)-1){
		     var3="Move Down";
		   }else{
		     var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		   }
		    if(oldpagename=="Home"){
		   		var4="Remove</td></tr></table>";
		   }else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td></tr></table>"; 
		   }
		   
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[i][1]=oldpagename; 
		   
	       linkhtml= linkhtml+innerhtmlarray[i][0];
	    }
		document.getElementById("addedpages").innerHTML=linkhtml;
  }	
  function remove(count,pagename){
    	 var linkhtml="";
		 var currcount=document.getElementById("linkcount").value;
		 var newcount=parseInt(count)+1;
		 //reshufflearray
		 //alert(currcount);
		 
		 currcount=parseInt(currcount)-1;
		 document.getElementById("linkcount").value=currcount;
		 for(var j=count;j<currcount;j++){
		   innerhtmlarray[j][1]=innerhtmlarray[newcount][1];
		   innerhtmlarray[j][0]=innerhtmlarray[newcount][0];
		   newcount=newcount+1;
		   //alert(j);
		  //alert(innerhtmlarray[j][1]);
		 }
		 
		 //return;
		for(var i=0;i<currcount;i++){
		    
		   var oldpagename=innerhtmlarray[i][1];
		   if(oldpagename=="###deleted####"){
			  currcountwithdeletd=currcountwithdeletd -1;
		      continue;
		   }
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='40%' class=maintext align=left>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td class=maintext align=left>Move Up";
		   }else{
		     var2="<td class=maintext align=left><a class=anchor   class='MoveUp' href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   
		   if(i==parseInt(currcount)-1){
		     var3="Move Down";
		   }else{
		     var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		   }
		   if(oldpagename=="Home"){
		   	   		var4="Remove</td></tr></table>";
		   }else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td></tr></table>"; 
		   }
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[i][1]=oldpagename; 
		   
	       linkhtml= linkhtml+innerhtmlarray[i][0];
	    }
		document.getElementById("addedpages").innerHTML=linkhtml;
  }	
  function clickSave(id){
           var linkvalues="";
           var currcount=document.getElementById("linkcount").value;
				  	  for(var i=0;i<currcount;i++){
				           linkvalues=linkvalues+innerhtmlarray[i][1]+",";;
				     }
			
			 document.getElementById("linkname").value=linkvalues;
			 document.getElementById("postback").value="Save";
			 document.frmAddlinks.submit();
  }
  function showpreview(id){
             // var linkvalues=ocument.frmAddlinks.linkname.value;
			 var linkvalues="";
			 if(document.getElementById("site").checked){
			   linkvalues="Home,";
			 }else{
			         var currcount=document.getElementById("linkcount").value;
				  	  for(var i=0;i<currcount;i++){
				           linkvalues=linkvalues+innerhtmlarray[i][1]+",";;
				     }
			 }
			
			 winname="SiteBuilderpreview";
			 winurl="showpreview.php?linkvalues="+linkvalues+"&";
			 window.open(winurl,winname,'');
			 
  }
  function addpage(type){
    
	    var linkhtml="";
	    var currcount=document.getElementById("linkcount").value;
		     
		var pagename=document.getElementById("pagename").value;
		var allowedchar="abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		if (pagename.length == 0 ||pagename.substr(0,1) == " ") {
		   alert("Please enter link name");
		    return false;
		
		}
		if( ! validchars(pagename,allowedchar,"20")){
		 alert("Please enter valid charcters");
		 return false;
		}
		for(var i=0;i<currcount;i++){
		  pagenameU=pagename.toUpperCase();
		 innerhtmlarrayU=innerhtmlarray[i][1].toUpperCase();
		 //alert(innerhtmlarray[i][1].toUpperCase());
		if ( pagenameU == innerhtmlarrayU ){
		  //if(pagename==innerhtmlarray[i][1]){
		    alert("duplicate page name");
			return false;
		  }	
		  if ( pagenameU == "FEEDBACK" && type=="0"){
		  //if(pagename==innerhtmlarray[i][1]){
		    alert("You canot add feedback page here.please use Integration Manager to add feedback page"); 

  

			return false;
		  }	
		}
		
		for(var i=0;i<currcount;i++){
		
		   var oldpagename=innerhtmlarray[i][1];
		    
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='40%' align=left class=maintext>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td class=maintext align=left>Move Up";
		   }else{
		     var2="<td class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		    if(oldpagename=="Home"){
		   		var4="Remove</td></tr></table>";
		   }else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td></tr></table>"; 
		   }
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[i][1]=oldpagename; 
		   
	       linkhtml= linkhtml+innerhtmlarray[i][0];
	    }
		
		if(pagename=="Home" && currcount==0){
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='40%' align=left class=maintext>"+pagename+"</td>";
		   var2="<td class=maintext align=left>Move Up";
		   var3="Move Down";
		   var4="Remove</td></tr></table>"; 
		}else if(pagename=="Home"){
		    var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='40%' align=left class=maintext>"+pagename+"</td>";
		    var2="<td class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+currcount+"','"+pagename+"')\">Move Up</a>";
		    var3="Move Down";
		    var4="Remove</td></tr></table>"; 
		}else{
		    var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='40%' align=left class=maintext>"+pagename+"</td>";
		    var2="<td class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+currcount+"','"+pagename+"')\">Move Up</a>";
		    var3="Move Down";
		    var4="<a class=anchor   href=\"javascript:remove('"+currcount+"','"+pagename+"')\">Remove</a></td></tr></table>"; 
		}
		  var5="<td align=left class=maintextbigbold>Actions</td>";
		   innerhtmlarray[currcount][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+var5+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[currcount][1]=pagename;
		   currcount=parseInt(currcount)+1;
		   document.getElementById("linkcount").value=currcount;
		
	     linkhtml= linkhtml+innerhtmlarray[i][0];
	     document.getElementById("addedpages").innerHTML=linkhtml;
		 document.getElementById("pagename").value="";
  }
  
  function loaddefault(){
 
		 
		  var pagename="Home";
		  var1="<table width='100%' border=0><tr bgcolor=#CCCCCC><td  class='maintext' width='40%' align=left>"+pagename+"</td>";
		  var2="<td  class='maintext'  align=left>Move Up";
		  var3="Move Down ";
		  var4="Remove</td></tr></table>";
		  innerhtmlarray[0][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		  innerhtmlarray[0][1]=pagename;
		  document.getElementById("addedpages").innerHTML=innerhtmlarray[0][0];;
	      document.getElementById("linkcount").value="1";
  }
  function clicksimple(){
   document.getElementById("multiplepages").style.display="none";
  }
  function clickmultiple(){
   document.getElementById("multiplepages").style.display="";
  }
  function clickback(id){
           var linkvalues="";
           if(document.getElementById("site").checked){
			   linkvalues="Home,";
			 }else{
			         var currcount=document.getElementById("linkcount").value;
				  	  for(var i=0;i<currcount;i++){
				           linkvalues=linkvalues+innerhtmlarray[i][1]+",";;
				     }
			 }
			 //alert(linkvalues);
			 document.getElementById("linkname").value=linkvalues;
  }	
-->
</script> 

				  
					  
				     <table width="100%" align=center border=0 cellpadding="0" cellspacing="0">
					     
						 <tr><td colspan=2>&nbsp;</td></tr>
					    <tr>
				  			 <td class=errormessage><?php echo $errorinlink; ?></td>
						</tr>
						<?php if($errorinlink==""){ ?>
						<tr>
						   <td class=maintextbold><?php echo $message; ?></td>
						</tr>
						<?php } ?>
						
		                <tr>
						<tr>
						   
						   <td colspan=2 align=center>
						       <form name="frmAddlinks" method="POST" action="pagemanager.php?urltype=1">
						      <input type=hidden id=linkcount name=linkcount value="0">
							  <input type=hidden id=linkname name=linkname >
							  <input type=hidden id=postback name=postback >
							  <input type=hidden name="cmbSitename" value="<?php echo $cmbSitename; ?>">
							   <div id=multiplepages>
							   <FIELDSET style="width:600px">
							   <LEGEND class=maintextbigbold>Add new web page</LEGEND>
						       <table width="100%" align=center>
							                 <tr>
											   <td align=right><input name=pagename id=pagename type=text size=40 maxlength="20" class=textbox></td>
											   <td align=left>&nbsp;<input class=button type=button name="addapage" value="Add web page"  onclick="addpage(0);"></td>
											</tr>
											<tr><td colspan=2>&nbsp;</td></tr>
											<tr>
											   <td colspan=2 >
											      <table width="100%" border=0 cellpadding="0" cellspacing="0">
												      <tr><td class=maintextbigbold align=left colspan=4>Your web pages</td></tr>
													  <tr><td colspan=4>&nbsp;</td></tr>
													  <tr>
													  
													  <td colspan=2>
													     <table border=1 width="100%"><tr><td width="40%" align=left class=maintextbigbold>Page</td><td align=left class=maintextbigbold>Actions</td>
														 <td align=left class=maintextbigbold>Actions</td>
														 </tr></table>
													  </td>
													  </tr>
													  <tr>
													  
													  <td id=addedpages>
													     
													  </td>
													  
													  </tr>
												  </table>
											   </td>
											</tr>
							   </table>
							   </FIELDSET>
							   </div>
							   
						   </td>
						</tr>
					    
						
					    <tr>
						  <td colspan=2 align=center><input type=submit name=btnSave value="Save" class=button onclick="clickSave(this.id);">
						  <input class=button type=submit name=btnback  value="Back" onclick="clickback(this.id);" >
						  </td>
						</tr>
					 </table>
				            
				  </form>
                


<?php

if($_SESSION['session_sitelinks'] !="Home"){
	$_SESSION['session_oldsitelinks']=$_SESSION['session_sitelinks'];
	$sitelinkarray=explode(",",$_SESSION['session_sitelinks']);
	$sitelinksforjs="";
	foreach($sitelinkarray as $key=>$value){
	   $sitelinksforjs .="linksarray[$key]=\"$value\" ;\n";
	}
    $sitelinksforjs .="var numberoflinks=".count($sitelinkarray)." ;\n";


?>

  <script>
  
       var linksarray=new Array();
	   <?php echo $sitelinksforjs;?>
	   //loaddefault();
	   document.getElementById("linkcount").value="0";
	  // document.getElementById("site_multiple").checked=true;
	   document.getElementById("multiplepages").style.display="";
	   for (z = 0;  z <numberoflinks;  z++){
	   //alert(linksarray[z]);
	        document.getElementById("pagename").value=linksarray[z];
		  addpage(1);
	   }	 

	   
</script>
<?php
  //echo "sitelinks".$_SESSION['session_sitelinks'];
}else{
?>
<script>

	loaddefault();
	document.getElementById("multiplepages").style.display="";
</script>
<?php

}
?>
