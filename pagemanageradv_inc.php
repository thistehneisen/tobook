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
$siteid = $cmbSitenamearray[0];
$sitetemplate = $cmbSitenamearray[1];
$sitetype = $cmbSitenamearray[2];
$sitestatus = $cmbSitenamearray[3];
$_SESSION['session_siteid'] = "";
$_SESSION['session_currenttemplateid'] = "";
$_SESSION['session_edittype'] = "";
if (strcasecmp($sitestatus, "Completed") == 0) {
				$_SESSION['session_siteid'] = $siteid;
				$_SESSION['session_currenttemplateid'] = $sitetemplate;
				$_SESSION['session_edittype'] = "edit";
} else {
				$_SESSION['session_currenttempsiteid'] = $siteid;
				$_SESSION['session_currenttemplateid'] = $sitetemplate;
				$_SESSION['session_edittype'] = "new";
} 

$tmpsiteid = $_SESSION['session_currenttempsiteid'];
$templateid = $_SESSION['session_currenttemplateid'];
$userid = $_SESSION["session_userid"];
$siteid = $_SESSION['session_siteid'];

function isValidLinkname($links)
{
				if (trim($links) != "") {
								if (eregi ("[^0-9a-zA-Z+, ]", $links)) {
												return false;
								} else {
												return true;
								} 
				} else {
								return false;
				} 
} 
/*get the deleted image entry in the resource file 
* $filelocation->location of the page to be delete
* $resourcelocation->location of the resource file
* $var_deletedimages->will contain the deleted images seperated bt '|'
*/
function getDeletedImages($filelocation, $resourcelocation)
{
				$var_deletedimages = "";
				$filecontent = file_get_contents($filelocation);
				$fp = fopen($resourcelocation, "r");
				$imagearray = array();
				while ($str = fgets($fp)) {
								$searchstring = substr(basename($str), 0, -1);
								array_push($imagearray, $searchstring);
				} 
				$imagearray = array_unique($imagearray);
				foreach($imagearray as $key => $value) {
								$i = 0;
								$cnt = substr_count($filecontent, $value);
								for($i = 0;$i < $cnt;$i++) {
												$var_deletedimages .= $value . "|";
								} 
				} 

				return $var_deletedimages;
} 

function SaveResource($resource_location, $var_deletedimages, $var_newimages)
{
				if (is_file($resource_location)) {
								$content_arr = file($resource_location);
								if (strlen($var_deletedimages) > 0) { // IF Delete images > 0
												$deleted_arr = explode('|', $var_deletedimages);
												$deleted_count = count($deleted_arr);

												for($i = 0;$i < $deleted_count;$i++) { // FOR LOOP - I
																$temp_string = $deleted_arr[$i];
																switch (substr($temp_string, 0, 2)) {
																				case "ug":
																								$temp_string = "../usergallery/" . $_SESSION["session_userid"] . "/images/" . $temp_string;
																								break;
																				case "sg":
																								$temp_string = "../systemgallery/" . $temp_string;
																								break;
																				case "sl":
																								$temp_string = "../samplelogos/" . $temp_string;
																								break;
																				default:
																								continue;
																} 
																$temp_count = 0;
																foreach($content_arr as $lines) { // FOR LOOP - II
																				if (strcmp($temp_string, rtrim($lines, "\n")) == 0) {
																								$content_arr[$temp_count] = "";
																								break;
																				} 
																				$temp_count++;
																} // END FOR LOOP - II	
												} // END FOR LOOP - I
								} // END IF Delete images > 0
				} 
				$fwriter = fopen($resource_location, "w");
				if (count($content_arr) > 0) {
								foreach($content_arr as $lines) { // FOR LOOP
												if ($lines != "") {
																fputs($fwriter, $lines);
												} 
								} 
				} 
				if (strlen($var_newimages) > 0) { // IF New images > 0
								$new_arr = explode('|', $var_newimages);
								$new_count = count($new_arr);
								for($i = 0;$i < $new_count;$i++) { // FOR LOOP - I
												$temp_string = $new_arr[$i];
												switch (substr($temp_string, 0, 2)) {
																case "ug":
																				$temp_string = "../usergallery/" . $_SESSION["session_userid"] . "/images/" . $temp_string . "\n";
																				break;
																case "sg":
																				$temp_string = "../systemgallery/" . $temp_string . "\n";
																				break;
																case "sl":
																				$temp_string = "../samplelogos/" . $temp_string . "\n";
																				break;
																default:
																				continue;
												} 
												fputs($fwriter, $temp_string);
								} 
				} // END IF New images > 0
				fclose($fwriter);
} 
/* Function used to find the link that are already created for the given site
  *   $sitestatus->status of the site(ie completed or under construction).
  *   all the pages will be stored in  'session_sitelinks' seprated by 	','
  *   corresponding page type will be stored in 'session_sitelinkstype' seprated by ','
  */
function findoldlinks($siteid, $templateid, $userid, $sitetype, $sitestatus)
{
				if (strcasecmp($sitestatus, "Completed") != 0) { // if  site completed
								if (strcasecmp($sitetype, "simple") == 0) {
												$qry = "select * from tbl_tempsite_pages where ntempsite_id='" . $siteid . "' order by ntempsp_id";
												$rs1 = mysql_query($qry);
												$editsitelinks = "";
												$editsitelinktype = "";
												while ($row1 = mysql_fetch_array($rs1)) {
																$editsitelinks .= $row1['vpage_name'] . ",";
																$editsitelinktype .= $row1['vpage_type'] . ",";
												} 
												$editsitelinks = substr($editsitelinks, 0, -1);
												$editsitelinktype = substr($editsitelinktype, 0, -1);
												$_SESSION['session_sitelinks'] = $editsitelinks;
												$_SESSION['session_sitelinkstype'] = $editsitelinktype;
								} else {
												$qry = "select * from tbl_tempsite_pages where ntempsite_id='" . $siteid . "' order by ntempsp_id";
												$rs1 = mysql_query($qry);
												$editsitelinks = "";
												$editsitelinktype = "";
												while ($row1 = mysql_fetch_array($rs1)) {
																$editsitelinks .= substr($row1['vpage_name'], 0, -4) . ",";
																$editsitelinktype .= $row1['vpage_type'] . ",";
												} 
												$editsitelinks = substr($editsitelinks, 0, -1);
												$editsitelinktype = substr($editsitelinktype, 0, -1);
												$_SESSION['session_sitelinks'] = $editsitelinks;
												$_SESSION['session_sitelinkstype'] = $editsitelinktype;
								} 
				} else {
								if (strcasecmp($sitetype, "simple") == 0) {
												$qry = "select * from tbl_site_pages where nsite_id='" . $siteid . "' order by nsp_id";

												$rs1 = mysql_query($qry);
												$editsitelinks = "";
												$editsitelinktype = "";
												while ($row1 = mysql_fetch_array($rs1)) {
																$editsitelinks .= $row1['vpage_name'] . ",";
																$editsitelinktype .= $row1['vpage_type'] . ",";
												} 
												$editsitelinks = substr($editsitelinks, 0, -1);
												$editsitelinktype = substr($editsitelinktype, 0, -1);
												$_SESSION['session_sitelinks'] = $editsitelinks;
												$_SESSION['session_sitelinkstype'] = $editsitelinktype;
								} else {
												$qry = "select * from tbl_site_pages where nsite_id='" . $siteid . "' order by nsp_id"; 
												// echo $qry;
												$rs1 = mysql_query($qry);
												$editsitelinks = "";
												$editsitelinktype = "";
												while ($row1 = mysql_fetch_array($rs1)) {
																$editsitelinks .= substr($row1['vpage_name'], 0, -4) . ",";
																$editsitelinktype .= $row1['vpage_type'] . ",";
												} 
												$editsitelinks = substr($editsitelinks, 0, -1);
												$editsitelinktype = substr($editsitelinktype, 0, -1); 
												// $_SESSION['session_sitelinks']=$editsitelinks;
												$_SESSION['session_sitelinks'] = $editsitelinks;
												$_SESSION['session_sitelinkstype'] = $editsitelinktype;
								} 
				} 
} 
/* Save the pages into table
   $links->added pages separated  by ','
   $pagetype->The type of the page (ie homepage or subpage) separated by ','
*/
function SaveAdvLinks($userid, $tmpsiteid, $siteid, $templateid, $links, $pagetype)
{
                //check ofr the site is published or not.for published site $tmpsiteid will be NULL*/
				if ($tmpsiteid > 0) {
								if (trim($links) != "") {
												$linkvaluesarray = explode(",", $links);
												$pagetypearray = explode(",", $pagetype);
												$qrystartvalues = "";
												$qrystart = "insert into tbl_tempsite_pages(ntempsp_id,ntempsite_id,vpage_name,vpage_title,vpage_type,vtype) values";
												/* loop through the page array to build*/
												foreach($linkvaluesarray as $key => $value) {
																$value1 = str_replace(" ", "" , $value);
																$value1 = strtolower($value1);
																if ($value1 == "guestbook") {
																				$pagename = "guestbook" . ".php";
																} else {
																				$pagename = $value1 . ".htm";
																} 
																/* page type is homepage ,copy the index.htm to workarea location/pagename*/
																if ($pagetypearray[$key] == "homepage") {
																				if (!is_file("./workarea/tempsites/" . $tmpsiteid . "/$pagename")) {
																								@copy("./" . $_SESSION["session_template_dir"] . "/$templateid/index.htm", "./workarea/tempsites/" . $tmpsiteid . "/$pagename");
																				} 
																				$qrystartvalues .= "('','" . $tmpsiteid . "','$pagename','" . $title . "','homepage','advanced'),";
																} else {
																      /* page type is subpage ,copy the sub.htm to workarea location/pagename*/
																				if (!is_file("./workarea/tempsites/" . $tmpsiteid . "/$pagename")) {
																								@copy("./" . $_SESSION["session_template_dir"] . "/$templateid/sub.htm", "./workarea/tempsites/" . $tmpsiteid . "/$pagename");
																				} 
																				if (strcasecmp($value, "feedback") == 0) {
																								$qrystartvalues .= "('','" . $tmpsiteid . "','$pagename','" . $title . "','feedback','feedback'),";
																				} else if (strcasecmp($value, "guestbook") == 0) {
																								$qrystartvalues .= "('','" . $tmpsiteid . "','$pagename','" . $title . "','guestbook','advanced'),";
																				} else {
																								$qrystartvalues .= "('','" . $tmpsiteid . "','$pagename','" . $title . "','subpage','advanced'),";
																				} 
																} 
												} 
												$qrystartvalues = substr($qrystartvalues, 0, -1);
												$pageqry = $qrystart . $qrystartvalues;
								} 
								// delete all the page content
								$qry = "delete from tbl_tempsite_pages where ntempsite_id='" . $tmpsiteid . "'";
								mysql_query($qry); 
								// insert into tbl_tempsite_pages
								// echo "pagequery".$pageqry;
								if ($pageqry != "")
												mysql_query($pageqry);
				} else if ($siteid > 0) {
								if (trim($links) != "") {
												$linkvaluesarray = explode(",", $links);
												$pagetypearray = explode(",", $pagetype);
												$qrystartvalues = "";
												$qrystart = "insert into tbl_site_pages(nsp_id,nsite_id,vpage_name,vpage_title,vpage_type,vtype) values";
												/* loop through the page array to build*/
												foreach($linkvaluesarray as $key => $value) {
																$value1 = str_replace(" ", "" , $value);
																$value1 = strtolower($value1);
																if ($value1 == "guestbook") {
																				$pagename = "guestbook" . ".php";
																} else {
																				$pagename = $value1 . ".htm";
																} 
																/* page type is homepage ,copy the index.htm to workarea location/pagename*/
																if ($pagetypearray[$key] == "homepage") {
																				if (!is_file("./sites/$siteid/$pagename")) {
																								@copy("./" . $_SESSION["session_template_dir"] . "/$templateid/index.htm", "./workarea/sites/" . $siteid . "/$pagename");
																								@copy("./" . $_SESSION["session_template_dir"] . "/$templateid/index.htm", "./sites/" . $siteid . "/$pagename");
																				} 
																				$qrystartvalues .= "('','" . $siteid . "','$pagename','" . $title . "','homepage','" . $_SESSION['session_buildtype'] . "'),";
																} else {
																     /* page type is subpage ,copy the sub.htm to workarea location/pagename*/        
																				if (!is_file("./sites/$siteid/$pagename")) {
																								@copy("./" . $_SESSION["session_template_dir"] . "/$templateid/sub.htm", "./workarea/sites/" . $siteid . "/$pagename");
																								@copy("./" . $_SESSION["session_template_dir"] . "/$templateid/sub.htm", "./sites/" . $siteid . "/$pagename");
																				} 
																				if (strcasecmp($value, "feedback") == 0) {
																								$qrystartvalues .= "('','" . $siteid . "','$pagename','" . $title . "','subpage','feedback'),";
																				} else if (strcasecmp($value, "guestbook") == 0) {
																								$qrystartvalues .= "('','" . $siteid . "','$pagename','" . $title . "','guestbook','advanced'),";
																				} else {
																								$qrystartvalues .= "('','" . $siteid . "','$pagename','" . $title . "','subpage','advanced'),";
																				} 
																} 
												} 
												$qrystartvalues = substr($qrystartvalues, 0, -1);
												$pageqry = $qrystart . $qrystartvalues;
								} 
								// delete all the page content
								$qry = "delete from tbl_site_pages where nsite_id='" . $siteid . "'";
								mysql_query($qry);
								if ($pageqry != "")
												mysql_query($pageqry);
				} 
} 
if ($_POST["postback"] == "Save") {
				$errorinlink = "";
				$linkvalues = substr(addslashes(trim($_POST['linkname'])), 0, -1); 
				$linktypevalues = substr(addslashes(trim($_POST['linktype'])), 0, -1);
				if (!isValidLinkname($linkvalues) and $linkvalues = "") {
								$errorinlink = "Invalid link values.use only alphabets,digits and space.";
				} else {
								$_SESSION['session_sitelinks'] = substr(addslashes($_POST['linkname']), 0, -1);
								$_SESSION['session_sitelinkstype'] = substr(addslashes($_POST['linktype']), 0, -1);
								if ($_SESSION['session_oldsitelinks'] != "") {
								                /* delete the removed page,and its image entry in reource.txt*/
												$oldlinksarray = explode(",", $_SESSION['session_oldsitelinks']);
												$newlinksarray = explode(",", $_SESSION['session_sitelinks']);
												$differencelinks = array_diff($oldlinksarray, $newlinksarray);
												foreach($differencelinks as $key => $value) {
																$value1 = str_replace(" ", "" , $value);
																$value1 = strtolower($value1);
																if ($value1 == "guestbook") {
																				$pagename = "guestbook" . ".php";
																} else {
																				$pagename = $value1 . ".htm";
																} 
																/* for published site 'session_edittype' will be edit
																*  delete the page in workarea location
																*  update resource.txt(ie remove remove entry for the deleted images)
																*  copy the updated resource to site location for published site
																*/ 
																  
																if ($_SESSION['session_edittype'] == "edit") {
																				if (is_file("./workarea/sites/" . $siteid . "/$pagename"))
																								unlink("./workarea/sites/" . $siteid . "/$pagename");
																				if (is_file("./sites/" . $siteid . "/$pagename")) {
																								$resource_location = "./workarea/sites/$siteid/resource.txt";
																								$deltedimages = getDeletedImages("./sites/$siteid/$pagename", "./workarea/sites/" . $siteid . "/resource.txt");
																								SaveResource($resource_location, $deltedimages, ""); 
																								@copy("./workarea/sites/" . $siteid . "/resource.txt", "./sites/" . $siteid . "/resource.txt");
																								unlink("./sites/" . $siteid . "/$pagename");
																				} 
																} else {
																				if (is_file("./workarea/tempsites/$tmpsiteid/$pagename")) {
																								$resource_location = "./workarea/tempsites/$tmpsiteid/resource.txt";
																								$deltedimages = getDeletedImages("./workarea/tempsites/$tmpsiteid/$pagename", "./workarea/tempsites/" . $tmpsiteid . "/resource.txt");
																								SaveResource($resource_location, $deltedimages, ""); 
																				} 

																				if (is_file("./workarea/tempsites/" . $tmpsiteid . "/$pagename"))
																								unlink("./workarea/tempsites/" . $tmpsiteid . "/$pagename");
																} 
												} 
								} 
								/* set the 'session_oldsitelinks' to added pages*/
								$_SESSION['session_oldsitelinks'] = $_SESSION['session_sitelinks'];
								$linkvalues = addslashes(trim($_SESSION['session_sitelinks']));
								$linktypevalues = addslashes(trim($_SESSION['session_sitelinkstype']));
								 /* for published site 'session_edittype' will be edit*/
								if ($_SESSION['session_edittype'] == "edit") {
												SaveAdvLinks($userid, "", "$siteid", $templateid, $linkvalues, $linktypevalues);
								} else {
												SaveAdvLinks($userid, $tmpsiteid, "", $templateid, $linkvalues, $linktypevalues);
								} 
								$message = "Pages saved successfully!!!";
				} 
} else {
                /* for published site 'session_edittype' will be edit*/
				if ($_SESSION['session_edittype'] == "edit") {
								findoldlinks($siteid, $templateid, $userid, $sitetype, $sitestatus) ;
				} else {
								findoldlinks($tmpsiteid, $templateid, $userid, $sitetype, $sitestatus) ;
				} 
} 
// echo $_SESSION['session_sitelinks'];
?>
<script>
/* the folllowing javascript functions are same in addlinks.php
    for more help about the following function refer addlinks.php
*/	
<!--
function validchars(strstring,allowedchar,strl){

	            var ch;
				//strstring=trim(strstring);
				if(strstring.length>strl){
				   return false;
				}   
				
		        for (i = 0;  i < strstring.length;i++){
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
		 tmp1pagetype=innerhtmlarray[count][2];
		 
		 tmp2pagename=innerhtmlarray[countminus1][1];
		 tmp2pagetype=innerhtmlarray[countminus1][2];
		 
		 innerhtmlarray[countminus1][1]=tmp1pagename;
		 innerhtmlarray[countminus1][2]=tmp1pagetype;
		 innerhtmlarray[count][1]=tmp2pagename;
		 innerhtmlarray[count][2]=tmp2pagetype;
		for(var i=0;i<currcount;i++){
		   var oldpagename=innerhtmlarray[i][1];
		   var oldpagetype=innerhtmlarray[i][2];
		    if(oldpagename=="###deleted####")
		     continue;
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' class=maintext align=left>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td width='40%' align=left class=maintext>Move Up";
		   }else{
		     var2="<td width='40%' align=left class=maintext><a class=anchor   href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   if(i==parseInt(currcount)-1){
		     var3="Move Down";
		   }else{
		     var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		   }
		   // if(oldpagename=="Home"  ){
		   		//var4="Remove</td><td align=left class=maintext>"+oldpagetype+"</td></tr></table>";
		   //}else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td><td align=left class=maintext>"+oldpagetype+"</td></tr></table>"; 
		   //}
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;;
		   innerhtmlarray[i][1]=oldpagename; 
		   innerhtmlarray[i][2]=oldpagetype; 
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
		 tmp1pagetype=innerhtmlarray[count][2];
		
		 tmp2pagename=innerhtmlarray[countplus1][1];
		 tmp2pagetype=innerhtmlarray[countplus1][2];
		 
		 innerhtmlarray[count][1]=tmp2pagename;
		 innerhtmlarray[count][2]=tmp2pagetype;
		 
		 innerhtmlarray[countplus1][1]=tmp1pagename;
		 innerhtmlarray[countplus1][2]=tmp1pagetype;
		for(var i=0;i<currcount;i++){
		   var oldpagename=innerhtmlarray[i][1];
		   var oldpagetype=innerhtmlarray[i][2];
		    if(oldpagename=="###deleted####"){
			  //currcountwithdeletd=currcountwithdeletd -1;
		      continue;
		   }
		   var1="<table width='100%'><tr bgcolor=#CCCCCC ><td width='30%' class=maintext align=left>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td width='40%' class=maintext align=left>Move Up";
		   }else{
		     var2="<td width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   if(i==parseInt(currcount)-1){
		     var3="Move Down";
		   }else{
		     var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		   }
		   // if(oldpagename=="Home"){
		   		//var4="Remove</td><td class=maintext align=left>"+oldpagetype+"</td></tr></table>";
		   //}else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td><td align=left class=maintext>"+oldpagetype+"</td></tr></table>"; 
		   //}
		   
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[i][1]=oldpagename; 
		    innerhtmlarray[i][2]=oldpagetype; 
		   
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
		   innerhtmlarray[j][2]=innerhtmlarray[newcount][2];
		   innerhtmlarray[j][0]=innerhtmlarray[newcount][0];
		   newcount=newcount+1;
		   //alert(j);
		  //alert(innerhtmlarray[j][1]);
		 }
		 
		 //return;
		for(var i=0;i<currcount;i++){
		    
		   var oldpagename=innerhtmlarray[i][1];
		   var oldpagetype=innerhtmlarray[i][2];
		   if(oldpagename=="###deleted####"){
			  currcountwithdeletd=currcountwithdeletd -1;
		      continue;
		   }
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' class=maintext align=left>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td width='40%' class=maintext align=left>Move Up";
		   }else{
		     var2="<td width='40%' class=maintext align=left><a class=anchor   class='MoveUp' href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   
		   if(i==parseInt(currcount)-1){
		     var3="Move Down";
		   }else{
		     var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		   }
		   //if(oldpagename=="Home"){
		   	   		//var4="Remove</td><td align=left class=maintext>"+oldpagetype+"</td></tr></table>";
		   //}else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td><td align=left class=maintext>"+oldpagetype+"</td></tr></table>"; 
		  // }
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[i][1]=oldpagename; 
		   innerhtmlarray[i][2]=oldpagetype; 
		   
	       linkhtml= linkhtml+innerhtmlarray[i][0];
	    }
		document.getElementById("addedpages").innerHTML=linkhtml;
  }	
  function clickSave(id){
           var linkvalues="";
		   var linktypevalues="";
           var currcount=document.getElementById("linkcount").value;
		   for(var i=0;i<currcount;i++){
				           linkvalues=linkvalues+innerhtmlarray[i][1]+",";
						   linktypevalues=linktypevalues+innerhtmlarray[i][2]+",";
			}
			
			 document.getElementById("linkname").value=linkvalues;
			 document.getElementById("linktype").value=linktypevalues;
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
  function addpage(type,pagetype){
      
	    
	    var linkhtml="";
	    var currcount=document.getElementById("linkcount").value;
		     
		var pagename=document.getElementById("pagename").value;
		var allowedchar="abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";	
		if (pagename.length == 0 ||pagename.substr(0,1) == " ") {
		   alert("Please enter page name");
		    return false;
		
		}
		if( ! validchars(pagename,allowedchar,"20")){
		  alert("Please enter valid page name.use only alphabets,digits and space");
		 return false;
		}
		for(var i=0;i<currcount;i++){
				   pagenameU=pagename.toUpperCase();
				   innerhtmlarrayU=innerhtmlarray[i][1].toUpperCase();
				  if ( pagenameU == innerhtmlarrayU ){
				    alert("duplicate page name");
					return false;
				  }if ( (pagenameU == "FEEDBACK" ||  pagenameU == "GUESTBOOK")&& type=="0"){
				  	    alert("You cannot add "+pagename+" page here.please use Integration Manager to add "+pagename+" page"); 
					    return false;
				  }	
		}
		if(pagetype ==""){
				if(document.getElementById("ptype").checked){
				  pagetype="homepage";
				}else{
				   pagetype="subpage";
				}
		}		
		
		for(var i=0;i<currcount;i++){
		
		   var oldpagename=innerhtmlarray[i][1];
		   var oldpagetype =innerhtmlarray[i][2];
		
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>"+oldpagename+"</td>";
		   if(i==0){
		     var2="<td width='40%' class=maintext align=left>Move Up";
		   }else{
		     var2="<td  width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+i+"','"+oldpagename+"')\">Move Up</a>";
		   }
		   var3="<a class=anchor   href=\"javascript:movedown('"+i+"','"+oldpagename+"')\">Move Down</a>";
		    //if(oldpagename=="Home"){
		   		//var4="Remove</td><td class=maintext align=left>"+oldpagetype+"</td></tr></table>";
		   //}else{
		        var4="<a class=anchor   href=\"javascript:remove('"+i+"','"+oldpagename+"')\">Remove</a></td><td align=left class=maintext>"+oldpagetype+"</td></tr></table>"; 
		   //}
		   innerhtmlarray[i][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[i][1]=oldpagename; 
		   innerhtmlarray[i][2]=oldpagetype;
		  // alert(innerhtmlarray[i][0]);
	       linkhtml= linkhtml+innerhtmlarray[i][0];
	    }
	
		/*
		if(pagename=="Home" && currcount==0){
		//alert(pagetype);
		   var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>"+pagename+"</td>";
		   var2="<td width='40%' class=maintext align=left>Move Up";
		   var3="Move Down";
		   var4="Remove</td><td class=maintext align=left>"+pagetype+"</td></tr></table>"; 
		}else if(pagename=="Home"){
		    var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>"+pagename+"</td>";
		    var2="<td width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+currcount+"','"+pagename+"')\">Move Up</a>";
		    var3="Move Down";
		    var4="Remove</td><td class=maintext align=left>"+pagetype+"</td></tr></table>"; 
		}else{*/
		    var1="<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>"+pagename+"</td>";
		    var2="<td width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('"+currcount+"','"+pagename+"')\">Move Up</a>";
		    var3="Move Down";
		    var4="<a class=anchor   href=\"javascript:remove('"+currcount+"','"+pagename+"')\">Remove</a></td><td class=maintext align=left>"+pagetype+"</td></tr></table>"; 
		//}
		  
		   innerhtmlarray[currcount][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		   innerhtmlarray[currcount][1]=pagename;
		   innerhtmlarray[currcount][2]=pagetype;
		   currcount=parseInt(currcount)+1;
		   document.getElementById("linkcount").value=currcount;
		
	     linkhtml= linkhtml+innerhtmlarray[i][0];
		 
	     document.getElementById("addedpages").innerHTML=linkhtml;
		 document.getElementById("pagename").value="";
  }
  
  function loaddefault(){
 
		 
		  var pagename="Home";
		  var1="<table width='100%' border=0><tr bgcolor=#CCCCCC><td  class='maintext' width='30%' align=left>"+pagename+"</td>";
		  var2="<td width='40%' class='maintext'  align=left>Move Up";
		  var3="Move Down ";
		  var4="Remove</td><td align=left class=maintext>homepage</td></tr></table>";
		  innerhtmlarray[0][0]=var1+var2+"&nbsp;&nbsp;&nbsp;"+var3+"&nbsp;&nbsp;&nbsp;"+var4;
		  innerhtmlarray[0][1]=pagename;
		  innerhtmlarray[0][2]="homepage";
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
							  <input type=hidden id=linktype name=linktype >
							  <input type=hidden id=postback name=postback >
							  <input type=hidden name="cmbSitename" value="<?php echo $cmbSitename; ?>">
							   <div id=multiplepages>
							   <FIELDSET style="width:600px">
							   <LEGEND class=maintextbigbold>Add new web page to your site</LEGEND>
						       <table width="100%" align=center border=0>
							            <tr>
											   <td>&nbsp;</td>
									    </tr> 
									
									
								        <?php
											 if(!validateSizePerUser($_SESSION["session_userid"],$size_per_user,$allowed_space)) {
										           $errorinlink = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete 
												   unused images or any/all of the sites created by you to proceed further.<br>&nbsp;<br>Please remove the site pages created by you.";
										?>		   
												   <tr><td class=errormessage><?php echo $errorinlink; ?></td></tr>
												 <tr>
												   <td align=right ><input name=pagename id=pagename type=hidden size=40 maxlength="20" class=textbox></td>
												   <td align=left>&nbsp;</td>
												</tr>
												   
										<?php		   
											 }else{
											  $homepagepriviewlink= "<a style=\"text-decoration:none;\" class=subtext  href='javascript:void(0)' onclick=\"showHomePagepreview('".$templateid."');\">";
									     $subpagepriviewlink= "<a style=\"text-decoration:none;\" class=subtext  href='javascript:void(0)' onclick=\"showSubPagepreview('".$templateid."');\">"; 
										
										?>
							                 
							                 <tr>
											   <td align=right>
											     <input name=pagename id=pagename type=text size=40 maxlength="20" class=textbox>
											     
											   </td>
											   <td align=left>&nbsp;<input class=button type=button name="addapage" value="Add web page"  onclick="addpage(0,'');"></td>
											</tr>
											<tr align=right>
											  <td class=maintext colspan=2 align=center>
											  <input type=radio name=ptype id=ptype value="homepage" checked>add a home page template&nbsp;&nbsp;[<?php echo $homepagepriviewlink; ?>&nbsp;<b>Preview</b>&nbsp;</a> ]
											   <input type=radio name=ptype id=ptype1 value="subpage">add a sub page template&nbsp;&nbsp;[<?php echo $subpagepriviewlink; ?>&nbsp;<b>Preview</b>&nbsp;</a>]
											  </td>
											</tr>
										<?php } ?>	
											<tr><td colspan=2>&nbsp;</td></tr>
											<tr>
											   <td colspan=2 id=pagesection>
											      <table width="100%" border=0 cellpadding="0" cellspacing="0">
												      <tr><td class=maintextbigbold align=left colspan=4>Current pages in your site</td></tr>
													  <tr><td colspan=4>&nbsp;</td></tr>
													  <tr>
													  
													  <td colspan=2>
													     <table border=0 width="100%"><tr><td width="30%" align=left class=maintextbigbold>Page</td><td width="40%" align=left class=maintextbigbold>Actions</td>
														 <td align=left class=maintextbigbold>Template type</td>
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
						  <input class=button type=submit name=btnback  value="Back"  >
						  </td>
						</tr>
					 </table>
				            
				  </form>
                


<?php
/* when a user add a page for his/her site 'session_sitelinks' will contain the page added by them.if he/she would like
*  one web page site 'session_sitelinks' will conatin 'Home'.
*  assign 'session_oldsitelinks' to the 'session_sitelinks' .this will required for deleting a page when user remove a page
*  build a javascript array that contains page name
*  build a avascript array that contains page type
* 
*/ 
if( $_SESSION['session_sitelinks'] !=""){
	$_SESSION['session_oldsitelinks']=$_SESSION['session_sitelinks'];
	
	$sitelinkarray=explode(",",$_SESSION['session_sitelinks']);
	$sitelinksforjs="";
	foreach($sitelinkarray as $key=>$value){
	   $sitelinksforjs .="linksarray[$key]=\"$value\" ;\n";
	}
    $sitelinksforjs .="var numberoflinks=".count($sitelinkarray)." ;\n";
 	$sitelinktypearray=explode(",",$_SESSION['session_sitelinkstype']);
	$sitelinkstypeforjs="";
	foreach($sitelinktypearray as $key=>$value){
	      $sitelinkstypeforjs .="linkstypearray[$key]=\"$value\" ;\n";
	}
    //$sitelinkstypeforjs .="var numberoflinks=".count($sitelinkarray)." ;\n";
	

?>

  <script>
  
       var linksarray=new Array();
	   var linkstypearray=new Array();
	   <?php echo $sitelinksforjs;?>
	   <?php echo $sitelinkstypeforjs;?>
	   //loaddefault();
	   document.getElementById("linkcount").value="0";
	  // document.getElementById("site_multiple").checked=true;
	   document.getElementById("multiplepages").style.display="";
	   
	   for (z = 0;  z <numberoflinks;  z++){
	   //alert(linksarray[z]);
	      document.getElementById("pagename").value=linksarray[z];
		  addpage(1,linkstypearray[z]);
	   }	 

	   
</script>
<?php
  //echo "sitelinks".$_SESSION['session_sitelinks'];
}else{
?>
<script>document.getElementById("addedpages").innerHTML="<br>No web page found.Please add web page to your site.";</script>
<?php
}
?>
