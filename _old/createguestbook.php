<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

if (get_magic_quotes_gpc()) {
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
}


//get post/get values for paging
if (isset($_GET["siteid"]) and $_GET["siteid"] != "") {
    $siteid = $_GET["siteid"];
} else if (isset($_POST["siteid"]) and $_POST["siteid"] != "") {
    $siteid = $_POST["siteid"];
}
if (isset($_GET["sitetype"]) and $_GET["sitetype"] != "") {
    $sitetype = $_GET["sitetype"];
} else if (isset($_POST["sitetype"]) and $_POST["sitetype"] != "") {
    $sitetype = $_POST["sitetype"];
}
if (isset($_GET["templatetype"]) and $_GET["templatetype"] != "") {
    $templatetype = $_GET["templatetype"];
} else if (isset($_POST["templatetype"]) and $_POST["templatetype"] != "") {
    $templatetype = $_POST["templatetype"];
}

if (isset($_GET["templateid"]) and $_GET["templateid"] != "") {
    $templateid = $_GET["templateid"];
} else if (isset($_POST["templateid"]) and $_POST["templateid"] != "") {
    $templateid = $_POST["templateid"];
}

						  	
                          if($sitetype == "completed"){//populate the variables for usage depending on whether the site is completed or not
                                  $sitemaster = "tbl_site_mast";
                                  $sitepagetable = "tbl_site_pages";
                                  $siteidfield = "nsite_id";
                                  $sitefoldername  = "./sites/".$siteid;
                                  $sitepagesfile = "";
                                  $ptype = "edit";
                          }else{
                                  $sitemaster = "tbl_tempsite_mast";
                                  $sitepagetable = "tbl_tempsite_pages";
                                  $siteidfield = "ntempsite_id";
                                  $sitefoldername  = "./workarea/tempsites/".$siteid;
                                  $sitepagesfoldername =  "./sitepages/tempsites/".$siteid;
                                  $ptype = "new";
                          }

if($_GET["act"]=="post"){



						  //guest book string for creating the guest book	
                          $gbcontent='<?php
                          $filename = \'gb.txt\';
                          // make sure the file exists and is writable first.
                          if (is_writable($filename)) {
                              if (!$handle = fopen($filename, \'a+\')) {
                                   $message.= "Cannot open file ($filename)";
                                   exit;
                              }
                                  If($_GET["act"]=="post"){
                                          $message= "";
                                          $content = addslashes($_POST["name"])."`|^".$_POST["email"]."`|^".$_POST["matter"]."`|^".date("Y-m-d")."~`|\n";
                                          if (fwrite($handle, $content) === FALSE) {
                                                  $message.= "Cannot write to file ($filename)";
                                                  exit;
                                          }

                                          $message.= "Thank you. Your Guest book entry added";
                                          fseek($handle, 0);

                                  }
                              //read file content to make display
                                  $displaycontents.="<table align=center width=70%><tr><td align=center><font face=verdana size=2><b>Current GuestBook Entries<br>&nbsp;</b></font></td></tr>";

                                  if(filesize($filename)>0){

                                                  $readcontents = @fread($handle, filesize($filename));
                                                  $entryarray=explode("~`|\n",$readcontents);


                                                  for($i=0;$i<count($entryarray)-1;$i++){

                                                          $valuearray=explode("`|^",$entryarray[$i]);
                                                          $displaycontents.="<tr><td align=left bgcolor=#dddddd><font face=verdana size=2>Posted &nbsp;by &nbsp;".stripslashes($valuearray[0])."( ".$valuearray[1]." ) &nbsp;on&nbsp; ".$valuearray[3]."</font></td></tr>";
                                                          $displaycontents.="<tr><td align=left valign=top><font face=verdana size=2><br>".$valuearray[2]."</font></td></tr>";
                                                          $displaycontents.="<tr><td align=left valign=top>&nbsp;</td></tr>";


                                                  }


                                  }else{

                                                  $displaycontents.="<tr><td align=center valign=top><font face=verdana size=2>Sorry! Guest book is empty.</font></td></tr>";

                                  }
                                  $displaycontents.="</table>";
                                  fclose($handle);


                          } else {

                              $message.= "The file $filename is not writable.Please provide write permission to it";

                          }




                          ?>
                          <script>
                          function checkMail(email)
                          {
                                  var str1=email;
                                  var arr=str1.split(\'@\');
                                  var eFlag=true;
                                  if(arr.length != 2)
                                  {
                                          eFlag = false;
                                  }
                                  else if(arr[0].length <= 0 || arr[0].indexOf(\' \') != -1 || arr[0].indexOf("\'") != -1 || arr[0].indexOf(\'"\') != -1 || arr[1].indexOf(\'.\') == -1)
                                  {
                                                  eFlag = false;
                                  }
                                  else
                                  {
                                          var dot=arr[1].split(\'.\');
                                          if(dot.length < 2)
                                          {
                                                  eFlag = false;
                                          }
                                          else
                                          {
                                                  if(dot[0].length <= 0 || dot[0].indexOf(\' \') != -1 || dot[0].indexOf(\'"\') != -1 || dot[0].indexOf("\'") != -1)
                                                  {
                                                          eFlag = false;
                                                  }

                                                  for(i=1;i < dot.length;i++)
                                                  {
                                                          if(dot[i].length <= 0 || dot[i].indexOf(\' \') != -1 || dot[i].indexOf(\'"\') != -1 || dot[i].indexOf("\'") != -1 || dot[i].length > 4)
                                                          {
                                                                  eFlag = false;
                                                          }
                                                  }
                                          }
                                  }
                                          return eFlag;
                          }
                          function validate(){

                                          if(document.gbForm.name.value=="" ){

                                                  alert("Please enter your name");
                                                  document.gbForm.name.focus();

                                          }else if (document.gbForm.email.value==""){

                                                  alert("Please enter your email");
                                                  document.gbForm.email.focus();

                                          }else if(checkMail(document.gbForm.email.value)==false){

                                               alert(\'Invalid mail format\');
                                               document.gbForm.email.focus();
                                               return false;

                                      }else if (document.gbForm.matter.value==""){

                                                  alert("Please enter your matter");
                                                  document.gbForm.matter.focus();

                                          }else{

                                                  document.gbForm.submit();
                                          }

                          }
                          </script>
                          <?php
                          echo $displaycontents;
                          ?>
                          <table width="100%"  border="0" align="center">
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center"><form name="gbForm" method="post" action="guestbook.php?act=post">
                                <fieldset style="width:400px;">
                                    <table width="100%"  border="0">
                                  <tr align="center">
                                    <td colspan="3"><br>
                                      <strong><font face=verdana size=2>Add your guestbook entry</font> </strong><br>&nbsp;</td>
                                    </tr>
                                                    <tr>
                                    <td width="100%" align="center" colspan=3><font face=verdana size=1 color=red><?php echo $message; ?></font></td>
                                  </tr>
                                  <tr>
                                    <td width="45%" align="right"><font face=verdana size=2>Your Name</font></td>
                                    <td width="3%">&nbsp;</td>
                                    <td width="52%" align="left" valign="top"><input name="name" type="text" id="name"></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right"><font face=verdana size=2>Your Email Address</font></td>
                                    <td>&nbsp;</td>
                                    <td align="left" valign="top"><input name="email" type="text" id="email"></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right"><font face=verdana size=2>Guest Book Matter</font></td>
                                    <td>&nbsp;</td>
                                    <td align="left" valign="top">            <textarea name="matter" id="matter"></textarea></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr align="center">
                                    <td colspan="3"><input type="button" value="Sign Guest Book" onclick=validate();></td>
                                    </tr>
                                </table>
                                    </fieldset>
                              </form></td>
                            </tr>
                          </table>';


										  $message="";
                                          $sql = "SELECT sm.vlinks, sm.vsub_sitelinks, tm.vlink_separator,tm.vsublink_separator,tm.vlink_type,tm.vsublink_type FROM ".$sitemaster." sm INNER JOIN tbl_template_mast tm ON sm.ntemplate_id = tm.ntemplate_mast ";
                                          $sql .= " WHERE sm.".$siteidfield."  = '".addslashes($siteid)."'";
															
                                          $res = mysql_query($sql);
                                          if(mysql_num_rows($res)!= 0){

                                                  $row = mysql_fetch_array($res);
                                                  $links = $row["vlinks"];
                                                  $sublinks = $row["vsub_sitelinks"];
                                                  $linkseparator = $row["vlink_separator"];
                                                  $sublinkseparator = $row["vsublink_separator"];
                                                  $linktype = $row["vlink_type"];
                                                  $sublinktype = $row["vsublink_type"];
                                                  $guestbooklink = "<a class=anchor1 href='./guestbook.php'>Guestbook</a>";
                                                  if($linktype == "horizontal"){
                                                          $newlink = $links .$linkseparator.$guestbooklink;
                                                  }else if($linktype == "vertical"){
                                                          $newlink = $links .$linkseparator.$guestbooklink."<br>";
                                                  }

                                                  if($sublinktype == "horizontal"){
                                                          $newsublink = $sublinks .$sublinkseparator.$guestbooklink;
                                                  }else if($sublinktype == "vertical"){
                                                          $newsublink = $sublinks .$sublinkseparator.$guestbooklink."<br>";
                                                  }

												  
                                                  if($links != ""){//if alredy pages are present

														  if($_SESSION["gbid"]!=$siteid){
																  $sql = "UPDATE ".$sitemaster." SET vlinks = '".addslashes($newlink)."', vsub_sitelinks='".addslashes($newsublink)."' WHERE ". $siteidfield." = '".addslashes($siteid)."' ";
																  mysql_query($sql);
																  $pagename = "Guestbook";
																  $filename = "guestbook.php";
																  $pagetitle = "Guestbook";
																  $pagetype = "guestbook";
																  $type = "simple";
		
		
																  $sql2 = "INSERT INTO ".$sitepagetable."(".$siteidfield.", vpage_name,vpage_title,vpage_type,vtype) VALUES ('".addslashes($siteid)."','".addslashes($pagename)."','".addslashes($pagetitle)."','".addslashes($pagetype)."','".addslashes($type)."') ";
																  mysql_query($sql2);
																  //echo "<br>".$sql2."<br>";
																  $newfilename = $sitefoldername."/".$filename;
																  if($sitepagesfoldername!=""){
																		  $newsitepagefilename = $sitepagesfoldername."/".$filename;
																		  $fp1 = fopen($newsitepagefilename,"w+");
																		  fwrite($fp1,$gbcontent);
																		  fclose($fp1);
                                                                                                                                                  @chmod($newsitepagefilename,$_SESSION['SERVER_PERMISSION']);
																		  $fp2 = fopen($sitepagesfoldername."/gb.txt","w+");
																		  fclose($fp2);
                                                                                                                                                  @chmod($sitepagesfoldername."/gb.txt",$_SESSION['SERVER_PERMISSION']);
																  }
																  //echo "<br>".$sql2;
																  //write data to guestbook.php file	
																  $fp = fopen($newfilename,"w+");
																  fwrite($fp,$gbcontent);
																  fclose($fp);
                                                                                                                                  @chmod($newfilename."/gb.txt",$_SESSION['SERVER_PERMISSION']);
																  //$link = "editsitepageoption.php?tempsiteid=".addslashes($siteid)."&type=".$ptype."&go=true&msg=creatednew";
																  $link = "editsitepageoption.php?tempsiteid=".addslashes($siteid)."&type=".$ptype."&go=true&msg=creatednew&page=guestbook&pagetype=guestbook&";
																  //header("Location:$link");
																  $_SESSION["gbid"]=$siteid;
														 
														  }
														//  $message ="Guestbook successfully added. Click here to <a href=javascript:showpreview('" . $siteid . "','1','" . $type . "','" . $templateid . "','".$_SESSION["session_userid"]."')>Preview</a> site.";
                                                                                                                   $message ="Guestbook successfully added. Click here to <a href=javascript:window.location='sitemanager.php'>go to sitemanager</a>";


                                                  }else{

                                                          $message .= "<br>Home Page not present! Please create home page![<a href='sitemanager.php'>Back</a>]";
                                                  }
                                          }

}

include "includes/userheader.php";
?>

<script>
function showpreview(id,status,type,template,user){
//alert(status);
	var leftPos = (screen.availWidth-500) / 2;
    var topPos = (screen.availHeight-400) / 2 ;
    winurl="showsitepreview.php?id=" + id +"&status="+status+"&type="+type+"&template="+template+"&user="+user;
  insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
          //   winname="sitepreview";
          // winurl="showsitepreview.php?id=" + id +"&status="+status+"&type="+type+"&template="+template+"&user="+user;
          //window.open(winurl,winname,'');
}
</script>

<table width="80%"  border="0">
<tr>
<td width="10%">&nbsp;</td>
<td width="80%" align="center" class="bigTitleText"><image src=images/controlpanel.gif></td>
<td width="10%">&nbsp;</td>
</tr>
<tr>
<td>&nbsp;</td>
<td align="center" valign="top">
<fieldset>
          <table width=100% border=0>
								
                                <?php
								if($message==""){
                                if($templatetype == "advanced"){
                                        $url = "code/editor.php?type=".$ptype."&actiontype=editsite&templateid=" .$templateid. "&tempsiteid=" .$siteid;
                                ?>
                                <tr><td align="left"  class=maintext>Please add your custom page by going to the <a href="<?php echo $url;?>">advanced editor</a>!</td></tr>
                                <tr><td align="left">&nbsp;</td></tr>
                                <tr><td align="left"><input type="button" name="btnBack"  value="Back" class="button" onClick="window.location.href='selectsite.php?page=guestbook';" ></td></tr>
                                <?php }else{?>
                                <tr><td align="left">&nbsp;</td></tr>
                                <?php
                                $guestbookpresent = false;
                                $arr = array();
                                $sql = "SELECT vpage_type FROM " .$sitepagetable ." WHERE  ".$siteidfield ." = '".addslashes($siteid)."' ";
                              
                                $res = mysql_query($sql);
                                if(mysql_num_rows($res) != 0){
                                        while($row = mysql_fetch_array($res))        {
                                                array_push($arr, $row["vpage_type"]);
                                        }
                                }

                                $arr  = array_unique(array_values($arr));
                                if(in_array("guestbook",$arr )){
                                        $guestbookpresent = true;
                                }

                                if($guestbookpresent ){

                                        if($sitetype == "completed"){
                                                $type = "edit";
                                        }else{
                                                $type = "new";
                                        }
                                        //$link = "editsitepageoption.php?tempsiteid=".addslashes($siteid)."&type=".$type."&go=true&";
										$link = "editsitepageoption.php?tempsiteid=".addslashes($siteid)."&type=".$type."&go=true&page=guestbook&pagetype=Guestbook&";
										
                                ?>
                                        <tr><td align="left" class=maintext>Guestbook Page already present. Please<?php echo "<a href=javascript:showpreview('" . $siteid . "','" . (($sitetype == "completed")?1:0) . "','simple','" . $templateid . "','".$_SESSION["session_userid"]."')>Preview</a>"; ?></td></tr>
                                        <tr><td align="left">&nbsp;</td></tr>
                                        <tr><td align="left"><input type="button" class="button" value="Back" name="btnBack" onclick="window.location.href='selectsite.php?page=guestbook'; "></td></tr>
                                <?php
                                }else{
									if(!validateSizePerUser($_SESSION["session_userid"],$size_per_user,$allowed_space)) {
           								$errorinlink = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete 
		   									unused images or any/all of the sites created by you to proceed further.<br>&nbsp;<br>";
										echo "<tr><td align='left' class='redtext'>".$errorinlink."</td></tr>";
										echo "<tr><td align='center' class='maintext'><a href='integrationmanager.php'>Back to Integration Manager</a></td></tr>";
									}else{
								?>
										<tr><td>
										
										<!-- guestbook----------------------------------------------------------- -->
										
										<form method=post action=<?php $_SERVER["PHP_SELF"]?>?act=post>
										
										        <table width="100%"  border="0">
										        <tr align="center" valign="middle">
										        <td width="100%" height="77" class=maintext align=left><p class=redtext><?php echo $message;?></p>Please click the following button to add a guest book to your site
										        <br><br>
										        NOTE: <br><ul align=left><li align=left>You should not modify the guest book page if you are not sure about it.Modification can possibly leave your guestbook non functional.<li>In order to make your guestbook work fine <b>your server should support PHP.</b></li>
										        <li>If you are publishing
										        your site as zip file, please provide write permission to "gb.txt" while uploading your site
										        for proper functioning of your guestbook.</li></ul></p>
										        <p align=center>
										        <input type=submit value="Add Guestbook" class=button>
										        <input type=hidden value=<?php echo $sitetype; ?> name=sitetype id=siteid>
										        <input type=hidden value=<?php echo $siteid; ?> name=siteid id=siteid>
												<input type=hidden value=<?php echo $templateid; ?> name=templateid id=templateid>
										        </p>
										        </td>
										        </tr>
										    	</table>
										
										</form>
										
										<!-- /guestbook----------------------------------------------------------- -->
										
										
										</td></tr>



                                <?php
									}
                                }
								
                                ?>
								
								

<?php
}
}else{

echo "<tr><td align=center class='redtext'><br><br><br><br><br>$message<br><br><br><br><br>&nbsp;</td></tr>";

}
?>
</table>
</fieldset>


<br>&nbsp;
</td>
<td>&nbsp;</td>
</tr>
</table>

<?php
include "includes/userfooter.php";
?>