<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>                                  |
// |                                                                      |
// +----------------------------------------------------------------------+
?>
<?php
$curTab = 'dashboard';

//include files
include "includes/session.php";
include "includes/config.php";


//sent back if back button is clicked
if($_GET["goback"]=="meta"){
   header("Location:metaanalyser.php");
   exit;

}elseif($_GET["goback"]=="promo"){
   header("Location:promotionmanager.php");
   exit;
}


//handle post back of meta analyser

$act=$_GET["act"];
$message="";

if($act=="post"){

		//get the metatag information from site
		if($tags = @get_meta_tags($_POST["domain"])){

        //split the informaton
		$generator = $tags["generator"];
        $language = $tags["language"];
        $description = $tags["description"];
        $keywords = $tags["keywords"];
        $robot = $tags["robot"];
        $author = $tags["author"];
        $refresh = $tags["refresh"];
        $copyright = $tags["copyright"];
        $revisit = $tags["revisit-after"];

        //get the total metatag
		$total="<META NAME='description' CONTENT='$description'><META NAME='keywords' CONTENT='$keywords'><META NAME='robot' CONTENT='$robot'><META NAME='refresh' CONTENT='$refresh'><META NAME='copyright' CONTENT='$copyright'><META NAME='author' CONTENT='$author'><META NAME='generator' CONTENT='$generator'><META HTTP-EQUIV='Content-Language' CONTENT='$language'><META NAME='revisit-after' CONTENT='$revisit'>";
        
		//replace newline chars to prevent javascript string breaks.
		$total = preg_replace('/\\n/','',$total);
        $total = preg_replace('/\\r/','',$total);






        }else{

        $message=VALTAG_SITE_MSG;

        }




}

include "includes/userheader.php";


?>
<FORM ACTION="metaanalyser.php?act=post" name=metaForm METHOD=post>
<script>

function validate(){

         if(document.metaForm.domain.value==""){

                  alert("<?php echo VALTAG_DOMAIN;?>");
                  document.metaForm.domain.focus();

         }else{

                  document.metaForm.submit();

         }

}

</script>

<?php
$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_PROMOTION_MANAGER =>'promotionmanager.php',
                    PROMOTION_MANAGER_METATAG_ANALYZER_TITLE =>'metaanalyser.php');
echo getBreadCrumb($linkArray);
?>
<h2><?php echo PROMOTION_MANAGER_METATAG_ANALYZER_TITLE; ?></h2>

<div class="comm_div">
<div class="comm_text">
<p class="txt09">
<?php echo META_ANALYSER_PAGEDESCRIPTION; ?>
</p>
</div>
</div>

      <!--start HELPPANEL CODE -->
        
        <script language="javascript">
                function goback(page){
         if(page=="metaanalyser"){

                   document.metaForm.action="metaanalyser.php?goback=meta";

         }else if(page=="promotionmanager"){

                   document.metaForm.action="metaanalyser.php?goback=promo";


         }
         document.metaForm.submit();

         }

        function genHex(){
                colors = new Array(14);
                colors[0]="0";
                colors[1]="1";
                colors[2]="2";
                colors[3]="3";
                colors[4]="4";
                colors[5]="5";
                colors[5]="6";
                colors[6]="7";
                colors[7]="8";
                colors[8]="9";
                colors[9]="a";
                colors[10]="b";
                colors[11]="c";
                colors[12]="d";
                colors[13]="e";
                colors[14]="f";
                digit = new Array(5);
                color="";
                for (i=0;i<6;i++){
                digit[i]=colors[Math.round(Math.random()*14)];
                color = color+digit[i];
                }
                document.getElementById('lady').style.backgroundColor=color;
}
</script>
        </td>
    </tr>
    
    </table>
        </fieldset>
        <!--end  HELPPANEL CODE -->

<?php
if($act=="post"){
?>
<br><font CLASS="redtext"><?php echo $message;?></font><br><br>

<h4 class="hding_10"><?php echo TAG_INFORMATION; ?></h4>
<table width="100%" border=0 cellpadding="0" cellspacing="0" class="customize-tbl2">

<tr><td width="39%" align=left class=maintext>
<?php echo TAG_SITE_DESCRIPTION; ?>
</td><td width="61%"  align=left class=maintext>
<input type="text" class=textbox size="40" name="description" VALUE="<?php echo htmlentities($description);?>">
</td></tr>



<tr>
<td  align=left class=maintext valign=top>
<?php echo TAG_SITE_KEYWORDS; ?> <br></td>
<td  align=left class=maintext>
  <p>
    <textarea cols=32 rows=5 name=keywords><?php echo htmlentities($keywords);?></textarea>
  </p>
  <p class="text12"><?php echo TAG_COMMA; ?> 
    
</p></td>
</tr>


<tr><td align=left class=maintext>
<?php echo TAG_ROBOT_OPTION; ?> 
</td><td  align=left class=maintext>
<INPUT TYPE=text  class=textbox NAME=robotans SIZE=35 VALUE="<?php echo htmlentities($robot);?>">
</td></tr>

<tr><td  align=left class=maintext>
<?php echo TAG_COPYRIGHT; ?> 
</td><td  align=left class=maintext>

<INPUT TYPE=text class=textbox  NAME=copyrightans VALUE="<?php echo htmlentities($copyright);?>" SIZE=35>
</td></tr>

<tr><td  align=left class=maintext>
<?php echo TAG_AUTHOR; ?> 
</td><td  align=left class=maintext>

<INPUT TYPE=text  class=textbox NAME=authorans SIZE=35 VALUE="<?php echo htmlentities($author);?>">
</td></tr>

<tr><td  align=left class=maintext>
<?php echo TAG_GENERATOR; ?>
</td><td  align=left class=maintext>
<INPUT TYPE=text  class=textbox NAME=generatorans SIZE=35 VALUE="<?php echo htmlentities($generator);?>">
</td></tr>

<tr><td  align=left class=maintext>
<?php echo TAG_LANGUAGE; ?>
</td><td  align=left class=maintext>

<INPUT TYPE=text class=textbox  NAME=languageans SIZE=35 VALUE="<?php echo htmlentities($language);?>">
</td></tr>

<tr><td   align=left class=maintext >
<?php echo TAG_SEARCH_REVISIT; ?>
</td><td  align=left class=maintext>
<INPUT TYPE=text class=textbox  NAME=revisitans SIZE=3 VALUE="<?php echo htmlentities($revisit);?>">
</td></tr>


<tr><td  align=left class=maintext>
<?php echo TAG_SEARCH_REFRESHRATE; ?>
</td><td  align=left class=maintext>
<INPUT TYPE=text  class=textbox NAME=refreshans SIZE=3 VALUE="<?php echo htmlentities($refresh);?>">
</td></tr>

<script>

function copyTag(){

   total="<?php echo $total;?>";
   if(total!=""){

      document.getElementById("copytag").style.display="inline";
      document.getElementById("total").value=total;
   }else{

      alert("<?php echo VALTAG_NO_TAGS;?>");   

   }
}

</script>
<tr><td colspan=2 id=copytag style="display:none;">

<textarea cols=30 rows=5 id="total" ></textarea>

</td></tr>
<tr></tr>
<tr>
	<td  align="center" >&nbsp;</td>
	<td  align="left">
<input type="button" class="btn02" value="Back"     onClick="goback('promotionmanager');">&nbsp;&nbsp;
<input type="button" class="btn01" value="Get more tags"     onClick="goback('metaanalyser');">&nbsp;&nbsp;
<input type="button" class="btn01" value="Copy Tags" onclick="copyTag()" >
</td>
</tr>
</table>





<?php
}else{
?>


<h4 class="hding_10"><?php echo TAG_DOMAIN_NAME; ?></h4>
<div class="form-pnl">
     <ul>
        <li>
            <label style="width:350px;"><?php echo TAG_ENTER_DOMAIN_NAME; ?> <sup>*</sup></label>
            <input type=text class=textbox  name=domain id=domain value="http://">
        </li>
        
        <li>
            <label>&nbsp;</label>
            <span class="btn-container">
                <input type="button" name="btnBack" value="Back" class="btn02"  onClick="goback('promotionmanager')" >
                <input type=button value="Get Tag" class=btn01 onClick=validate();>
                
               
            </span>
        </li>
    </ul>
</div>
<!--<table width=100%>
<tr><td align=center class=maintext>Please Enter a Valid Domain Name</td></tr>
<tr><td align=center>
<input type=text class=textbox  name=domain id=domain value="http://">
</td><tr>
<tr><td align=center><input type=button value="Get Tag" class=button onClick=validate();>&nbsp;&nbsp;<input type="button" class="button"
value="Back"     onClick="goback('promotionmanager');"></td></tr>
</table>-->



<?php
}
?>
</FORM>
<?php
include "includes/userfooter.php";
?>