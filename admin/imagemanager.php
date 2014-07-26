<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: johnson<johnson@armia.com>        		                  |
// |          									                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include "../includes/config.php";
include "includes/adminheader.php";
include_once "../includes/function.php";

$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
$txtSearch=($_GET["txtSearch"] != ""?trim($_GET["txtSearch"]):trim($_POST["txtSearch"]));
$cmbSearchType=($_GET["cmbSearchType"] != ""?$_GET["cmbSearchType"]:$_POST["cmbSearchType"]);

$systemgallerydir = "../systemgallery/";

if($_POST["btnUpload"] == "Upload"){
	$ddlCategory = $_POST["ddlCategory"];
	$userimage = $_FILES['fileUserImage'];
    /*$userimagename = $_FILES['fileUserImage']['name'];*/
	$tmpname=$_FILES['fileUserImage']['tmp_name'];
	$imagename=$_FILES['fileUserImage']['name'];
    $userimagetype = $_FILES['fileUserImage']['type'];
	$imagewidth_height_type_array=explode(":",ImageType($_FILES['fileUserImage']['tmp_name']));
	$imagetype=$imagewidth_height_type_array[0];
	$userimagename=time().".".$imagetype;//forming the image name
	$imageprefix = "sg_";
	$userimagedest = $systemgallerydir . $imageprefix . $userimagename;//prefixing sg_ to image name
	$dbimagename = $imageprefix .$userimagename;//tobe inserted in the database
	$dbimageurl = "systemgallery/".$imageprefix .$userimagename;//tobe inserted in the database
	if (!is_readable($systemgallerydir) || !is_writable($systemgallerydir) || !is_executable($systemgallerydir)) {
        $error = true;
        $message .= " * Please change the permission of 'systemgallery' folder to 777 <br>";
    }
	
	if ($userimagename != "") {
        if (!isValidWebImageType($userimagetype,$imagename,$tmpname)) {
            $message .= " * Invalid image or image not uploaded! Please upload a valid image (jpg/gif/png)" . "<br>";
            $error = true;
        } else {
            if (file_exists($userimagedest)) {
                $message .= " * Image with the same name exists! Please rename the image and upload! " . "<br>";
                $error = true;
            } 
        } 
    }else{
		if($userimagename == ""){
			$message .= " * Image is required! Please upload an image!" . "<br>";
       		$error = true;
		}
	}
	
	if($message !=""){
		$message = "<br>Please correct the following errors to continue!<br>".$message;
	}else{//no errors, so upload image
		if($userimagename != ""){
			move_uploaded_file($_FILES['fileUserImage']['tmp_name'], $userimagedest);
			chmod($userimagedest,0777);
		}
		$sql = "INSERT INTO tbl_gallery(vimg_name,vimg_url,ngcat_id)VALUES('".addslashes($dbimagename)."','".addslashes($dbimageurl)."','".addslashes($ddlCategory)."')";
		mysql_query($sql);
		$message = "Image uploaded successfully!";
	}
	
}
if($_POST["postback"] == "Delete Selected"){
	$chkImages = $_POST["chkImages"];
	if(isNotNull($chkImages)){
		$chkImages = implode(",",$chkImages );
	}else{
		$message1= "No images were selected to delete!";
	}
	if($message1 !=""){
		$message1 = "<br>Please correct the following errors to continue!<br>".$message1;
	}else{//no errors, so delete selected image(s)
		$sitesdir = "../sites";
		$wareadir = "../workarea";
		$resourcetext =  getResourceText($sitesdir,"resource.txt");//search all the resource.txt in 'sites' directory and collect the contents to $resourcetext
		$resourcetext .=  getResourceText($wareadir,"resource.txt");//search all the resource.txt in 'workarea' directory and collect the contents to $resourcetext
		$deletable = "";
		$allnotdeleted = false;
		$notdeleted = array();
		$sql1 = "SELECT nimg_id,vimg_url FROM tbl_gallery WHERE nimg_id IN (".addslashes($chkImages).")";
		$res = mysql_query($sql1);
		if(mysql_num_rows($res)!=0){
			while($rw = mysql_fetch_array($res)){
				$file = $rw["vimg_url"];
				if(strpos($resourcetext, $file) === false){//check whether the file is in $resourcetext
					@unlink("../".$file);
					$deletable .= $rw["nimg_id"]. ",";
				}else{
					$allnotdeleted = true;
					$filename = substr($file,14);//14 - length of 'systemgallery/'
					array_push($notdeleted,$filename );
				}
			}
		}
		$deletable = substr($deletable,0,-1);
		$sql = "DELETE FROM  tbl_gallery WHERE nimg_id IN (".addslashes($deletable).")";
		mysql_query($sql);
		if($allnotdeleted){
			$notdeleted = implode(",",$notdeleted);//list of images not deleted
			//print_r($notdeleted);
			$message1 = "The following image(s) in use could not be deleted!<br>";
			$message1 .= $notdeleted."<br>";
		}else{
			$message1 = "Selected image(s) deleted successfully!";
		}
		
	}
}

$categorylist = makeCategoryList();
?>
<script language="JavaScript" type="text/JavaScript">
function clickSearch()
{
        document.frmImages.postback.value="S";
        document.frmImages.action="imagemanager.php";
        document.frmImages.submit();
}

function isAnyOneSelected(){
	var frm = document.frmImages;
	for(i=0;i<frm.elements.length;i++){
		if(frm.elements[i].name == "chkImages[]"){
			if(frm.elements[i].checked){
				return true;
			}
		}
	}
	return false;	
}
function checkInput(){
	var frm = document.frmImages;
	if(frm.ddlCategory.value.length == 0){
		alert("Please select a category!");
		return false;
	}else if(frm.fileUserImage.value.length == 0){
		alert("Please select an image to upload!");
		return false;
	}
	
	return true;
}
function confirmDelete(){
	var frm = document.frmImages;
	if(!isAnyOneSelected()){
		alert("Please select the image(s) you want to delete!");
		return false;
	}else{
		if(confirm("Are you sure you want to delete the selected image(s)?")){
			frm.postback.value = "Delete Selected";
			frm.submit();
		}else{
			return false;
		}
	}
	
}

</script>

<form name="frmImages" method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>"  enctype="multipart/form-data">
<input name="postback" type="hidden" id="postback">


<?php



//========================================================



$qryopt="";

//$txtSearch=$_POST["txtSearch"];
//$cmbSearchType=$_POST["cmbSearchType"];

if($txtSearch != ""){

        if($cmbSearchType == "image name"){
                $qryopt .= "  AND g.vimg_name like '" . urldecode(addslashes($txtSearch)) . "%'";
        }elseif($cmbSearchType == "category"){
                $qryopt .= "  AND c.vcat_name like '" . urldecode(addslashes($txtSearch)) . "%'";
        }
}

$sql="SELECT g.nimg_id,g.vimg_name,g.vimg_url,c.vcat_name
FROM tbl_gallery g,tbl_gallery_category c
WHERE g.ngcat_id=c.ngcat_id " . $qryopt . "  order by c.vcat_name ";

//echo $sql;

$session_back="imagemanager.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
$gbackurl = $session_back;
//get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));

/*
Call the function:

I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/

$navigate = pageBrowser($totalrows,10,10,"&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch",$_GET[numBegin],$_GET[start],$_GET[begin],$_GET[num]);

//execute the new query with the appended SQL bit returned by the function
$sql = $sql.$navigate[0];
$rs = mysql_query($sql);
?>

<table width="82%"  border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td  valign="top" align=center>
			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
      			<tr>
                	<td align="center"><img src="../images/systemgalleryimages.gif" ></td>
            	</tr>
				<tr>
                	<td class="listingmain">
               			<table width="100%"  border="0" cellpadding="2" cellspacing="1" class="text">
                        	
                      		<tr class=background>
                          		<td width="100%">
									<table  border="0">
										<tr><td colspan="4" align="center" class=redtext><?php echo $message1;?></td></tr>
										<tr>
	                                    	<td width="3%" align="center" valign="top">&nbsp;</td>
	                                     	<td width="30%" valign="bottom"
	                                           class="category"><?php echo("Listing $navigate[1] of $totalrows results."); ?>
	                                       	</td>
                                       		<td width="67%" valign="top"
                                           		class="listing126" align=right><?php echo SEARCH;?> ::
	                                           <select name="cmbSearchType" class="selectbox">
	                                           <option value="image name"
	                                           <?php if($cmbSearchType == "image name" || $cmbSearchType == ""){
	                                           echo("selected"); } ?>>Image Name</option>
	                                           <option value="category"  <?php
	                                           if($cmbSearchType == "category" || $cmbSearchType == ""){
	                                           echo("selected"); } ?>>Category</option>
	                                           </select> &nbsp;
	                                           <input type="text" name="txtSearch" size="20" maxlength="50"
	                                           value="<?php echo(htmlentities($txtSearch)); ?>"
	                                           onKeyPress="if(window.event.keyCode == '13'){ return false; }"
	                                           class="textbox">&nbsp;
											</td>
											<td width="5%" valign="middle"                                               class="listing126"><a href="javascript:clickSearch();">
	                                           <img src='../images/go.gif'  width="20" height="20" border='0'></a>
											</td>
	                                       
                                		</tr>
                        			</table>
                        		</td>
                        	</tr>
                        </table>
                        <table width="100%"  border="0" cellpadding="5" cellspacing="1" class="maintext">
	                        <tr class="blacksub">
		                        <td width="10%" valign="top" >Select</td>
		                        <td width="40%" valign="top">Image</td>
		                        <td width="25%" >Name</td>
		                        <td width="25%" >Category</td>
	                        </tr>
                        
                   		<?php
                           	//loop and display the limited records being browsed
                           	$counter=1;
                 			while ($arr = mysql_fetch_array($rs)) {
								$chk = "<input type='checkbox' name='chkImages[]' class='textbox' value='".$arr["nimg_id"]."'> ";
								echo "<tr   class=background class='text'>
                           		<td style='word-break:break-all;'
                           		>&nbsp;".$chk."</td>";
                           		echo "<td   style='word-break:break-all;'>
                           			&nbsp;<br><img src='showgallery.php?imagename=../systemgallery/".urlencode(stripslashes($arr["vimg_name"]))."'><br>
                                                                                   &nbsp;</td>";
                           		echo "<td  style='word-break:break-all;'>
                           		&nbsp;" . htmlentities(stripslashes($arr["vimg_name"])). "</td>";
                           		echo "<td   style='word-break:break-all;'>".htmlentities(stripslashes($arr["vcat_name"]))." </td>";
                           		echo "</tr>";
                           		$counter++;
                           }
                        ?>

                        <tr class=background>
	                        <td colspan="4" align="center" height="30">
	                        	<?php echo($navigate[2]); ?>&nbsp;
	                        </td>
                        </tr>
						<tr class=background>
	                        <td colspan="4" align="center" height="30">
	                        	<input type="button" value="Delete Selected" name="btnDeleteSelected" class="button" ONCLICK="javascript:confirmDelete();">
	                        </td>
                        </tr>
                	</table>
				</td>
       		</tr>
			<tr><td>&nbsp;</td></tr>
			<tr class=background>
				<td align="center" >
					<fieldset>
						<legend class="maintext">Upload Image</legend>
						<table cellspacing="3" cellpadding="3" border=0 width="80%" class="maintext">
						<tr><td width="100%" align="center" colspan="2">&nbsp;</td></tr>
						<tr><td width="100%" align="left" colspan="2" class="redtext"><?php echo $message;?></td></tr>
							<tr><td width="100%" align="center" colspan="2">&nbsp;</td></tr>
							<tr><td width="20%" align="left" class=maintext>Category</td><td align="left"><?php echo makeDropDownList("ddlCategory", $categorylist, $ddlCategory, false, "textbox" , $properties, $behaviors)?></td></tr>
							<tr><td width="20%" align="left" class=maintext>Image</td><td align="left"><input type="file" name="fileUserImage" id="fileUserImage" class="textbox" onKeyPress="" ></td></tr>
							<tr><td width="100%" align="center" colspan="2"><input type="submit" value="Upload" name="btnUpload" class="button" onClick="return checkInput();" ></td></tr>
						</table>
					</fieldset>
				</td>
			</tr>
     	</table>						
		</td>
	</tr>
</table>									
</form>



<?php
include "includes/adminfooter.php";
?>