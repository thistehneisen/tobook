<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
/* this page conatins two filedset.top fieldset conatins all the gallery category
* when a user click on any of the category 'selectedcatname' and 'selectedcatid' will be passed to this page as POST hidden variable
* now the second fieldset will displays all the images under the selected category two in one row
* 
* 
*/ 

$gallerrycatid=addslashes($_POST['selectedcatid']);
$gallerycatname=addslashes(urldecode($_POST['selectedcatname']));
if($gallerrycatid==""){
	      $numbegin=$_GET['numBegin'];
		  $start=$_GET['start'];
		  $begin=$_GET['begin'];
		  $num=$_GET['num']; 
	      $gallerrycatid=addslashes($_GET['gcatid']);
	      $gallerycatname=addslashes(urldecode($_GET['gcatname'])); 
}
$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
include "includes/userheader.php";
?>
<script>
function showimagesincategory(catname,catid){
	     document.getElementById("selectedcatname").value=catname;
	     document.getElementById("selectedcatid").value=catid;
	     document.frmselectcategory.submit();
}	
</script>
<table width="80%"  border="0">
  <tr>
    <td width="12%">&nbsp;</td>
    <td width="76%" align="center" class="bigTitleText">
	<img src="images/manageourgallery.gif" >
            	
	</td>
    <td width="12%">&nbsp;</td>
  </tr>
  <tr>
    <td colspan=3 align=center>
	                      
	                    <table width="100%" cellpadding=2 cellpadding=2 align=center>
													   <tr>
													      <td>
	              
															                <FIELDSET>
																			  
															                   <legend class=maintextbold>Select category</legend>
															                   <form  method="post"   name="frmselectcategory" action="manageourgallery.php">
																					 
																					 <?php
																					  $qry="select * from tbl_gallery_category order by vcat_name ";
																					  $rs=mysql_query($qry);
																					 ?>
																					
																					 <table width="60%"  border=0 align=center>
																					 <input type=hidden id="selectedcatname" name="selectedcatname">
																					 <input type=hidden id="selectedcatid" name="selectedcatid">
																				     <?php
																						   if(mysql_num_rows($rs)>0){
																						        $i=0;
																						        while($row=mysql_fetch_array($rs)){
																								/* display two images in one row*/
																								if($i==0) {
																									echo "<tr>";
																								}
																								if($i==2) {
																								     $i=0;
																								 	 echo "</tr><tr>";
																								}
																								$catname=urlencode($row['vcat_name']);
																								
																					 ?>
																					              
																								   <td align=left><img src="./images/bullet.gif">
																								     <a class=anchor href="#" onclick="showimagesincategory('<?php echo $catname; ?>','<?php echo $row["ngcat_id"]; ?>')"><?php echo stripslashes(htmlentities($row['vcat_name'])); ?></a>
																								    <!-- <a class=anchor href="./ourgallerynew.php?catname=<?php echo $catname; ?>&catid=<?php echo $row['ngcat_id']; ?>"><?php echo $row['vcat_name']; ?></a>-->
																								   </td>
																								   
																								    
																						     
																					             
																					 <?php	
																						        $i++;
																						   		}
																								if($i==1){
																								  echo "<td colspan='2'>&nbsp;</td></tr>";
																								}else if($i==2){
																								  echo "</tr>";
																								}
																								
																						   }else{
																					?>
																					      <tr><td class=maintext>No categories found</td></tr>
																					<?php	     	
																						   }
																					 ?>
																					   
																				 </table>
																				 </form>
																				 </FIELDSET>
	  
																  </td>
															   
															   </tr>
  
  <?php
    /* if user click on any of the gallery category $gallerrycatid will be set to selected categoryid*/
    if($gallerrycatid>0){
	
  ?>
															      <tr>
															          <td>
																                  <FIELDSET>
																				      <legend class=maintextbold>Images in <?php echo stripslashes(htmlentities($gallerycatname)); ?></legend>
																					  <form  method="post"   name="frmImageUpload" target="targ">
																					      <table border=0 width="100%" align=center>
																					       <?php
																						       /* clear the 'currentimage' before entering to image editor*/
																						       $_SESSION['currentimage']="";
																						       $sql=" select * from tbl_gallery where ngcat_id='".$gallerrycatid."'";
																							   $gallerycatname=urlencode(stripslashes($gallerycatname));
																							   $totalrows = mysql_num_rows(mysql_query($sql));
																							   $navigate = pageBrowser($totalrows, 10, 10, "&gcatname=$gallerycatname&gcatid=$gallerrycatid&",$numbegin,$start,$begin, $num);
																							   $session_back="manageourgallery.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&gcatname=" . $gallerycatname . "&gcatid=" .$gallerrycatid;
																							   $_SESSION["ourgallery_backurl"] = $session_back;
																						       $sql = $sql . $navigate[0];
																							   $rs = mysql_query($sql);
																							   if(mysql_num_rows($rs)>0){
																							        $i=0;
																							        while($row=mysql_fetch_array($rs)){
																											if($i==0) {
																												echo "<tr>";
																											}
																											if($i==2) {
																											     $i=0;
																											 	 echo "</tr><tr>";
																											}
																										$catname=urlencode($row['vcat_name']);
																										/* $row['vimg_url'] will contain full path.fetch the name of the file*/
																										$file=basename($row['vimg_url']);
																									  
																						 ?>          
																						              
																									  <td>
																									     <FIELDSET>
																									      <TABLE>
																										     <tr>
																											   <td><img id="<?php echo $file; ?>" src="./systemgallery/<?php echo htmlentities($file); ?>" width=100 height=100</td>
																											 </tr>
																											 <tr>
																											   <td>
																											   <?php
																											     $filename=basename($row['vimg_url']);
																											   ?>
																											   <a class=anchor href="./editgallery.php?edittype=systemgallery&fname=<?php echo urlencode($file); ?>&">Edit</a>
																											   
																											   
																											   </td>
																											 </tr>
																										  </TABLE>
																										  </fieldset>
																							              
																							         </td>
																									   
																									    
																							     
																						             
																						 <?php	
																							        $i++;
																							   		}
																									if($i==1){
																									  echo "<td colspan='2'>&nbsp;</td></tr>";
																									}else if($i==2){
																									  echo "</tr>";
																									}
																									
																							   }else{
																						?>
																						       <!--<tr><td class=maintext colspan=2 align=center>No Images found</td></tr>-->
																						<?php	     	
																							   }
																						 ?>
																					    
																						   <tr >
								<td colspan="6" align="center" height="30">
									<?php echo($navigate[2]);
?>&nbsp;
								</td>
							</tr>
																						      
																							 <input type=hidden name=selectedimage id=selectedimage value="<?php echo($url); ?>">
																							  </form>
																						  </table>
																					  
																					  
																				  </FIELDSET>
																  </td>
																</tr>
  <?php	
	}
  ?>
  
           </table>	
	
	</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td align="center" valign="top"></td>
    <td>&nbsp;</td>
  </tr>
</table>
<?php
include "includes/userfooter.php";
?>