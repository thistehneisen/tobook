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

//sent back if backbuton is clicked
if($_GET["goback"]=="true"){

   header("Location:profilemanager.php");
   exit;
}

//get current style being used

$sql="select vuser_style from tbl_user_mast  where nuser_id='".$_SESSION["session_userid"]."'";
$result=mysql_query($sql);
$row=mysql_fetch_array($result);
$current_style=$row["vuser_style"];

//get post back

$act=$_GET["act"];

if($act=="preview"){

//set new style
$_SESSION["session_style"]=$_GET["style"];
header("location:editstyle.php");

}elseif($act=="save"){

//set new style
$_SESSION["session_style"]=$_GET["style"];

//update database with new style value
$sql="update tbl_user_mast set vuser_style='".$_GET["style"]."' where nuser_id='".$_SESSION["session_userid"]."'";

mysql_query($sql,$con);
header("location:editstyle.php");

}


include "includes/userheader.php";


?>



<h1 class="main_heading">Site Appearance</h1>
<form action="" method=post name=styleForm>

<table width="40%"  border="0" align="center">
  <tr><td colspan=2 width=100%>&nbsp;</td></tr>
    <tr><td colspan=2 width=100%>&nbsp;</td></tr>
      <tr><td colspan=2 width=100%>&nbsp;</td></tr>
  <tr>
  <td width=50% align=center class=maintext>Choose a color scheme</td>
  <td width=50% align=left >
   <select name=vuser_style id=vuser_style>

    <?php
    $option="";
if ($handle = opendir('style')) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            $option.="<option value='$file' >".substr($file,0,strlen($file)-4)."</option>";
        }
    }
    closedir($handle);
}
echo $option;
?>
  </select>
  </td></tr>
    <tr><td colspan=2 width=100%>&nbsp;</td></tr>
      <tr><td colspan=2 width=100%>&nbsp;</td></tr>
        <tr><td colspan=2 width=100%>
        <input type=button value="Save"  onClick="save()" class=button >&nbsp;
        <input type=button value="Preview" onClick="preview()" class=button >&nbsp;
         <input type=button value="Back"    onClick="goback();"   class=button >
        </td></tr>
</table>
<br>&nbsp;

<br><br>&nbsp;
</form>
<script>
var currentstyle="";
currentstyle="<?php echo $current_style;?>";

 function save(){
         var style= document.styleForm.vuser_style.value;
         document.styleForm.action="editstyle.php?act=save&style="+style;
         document.styleForm.submit();
 }

 function preview(){
        var style= document.styleForm.vuser_style.value;
        document.styleForm.action="editstyle.php?act=preview&style="+style;
        document.styleForm.submit();
 }


function goback(){

         document.styleForm.action="editstyle.php?goback=true";
         document.styleForm.submit();

}

 document.styleForm.vuser_style.value=currentstyle;

 </script>
<?php
include "includes/userfooter.php";
?>