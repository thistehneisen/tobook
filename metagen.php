<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
$curTab = 'dashboard';
//include files
include "includes/session.php";
include "includes/config.php";

//sent back if back button is clicked
if($_GET["goback"]=="meta"){
   header("Location:metagen.php");
   exit;

}elseif($_GET["goback"]=="promo"){
   header("Location:promotionmanager.php");
   exit;
}


//handle post back of signup

$act=$_GET["act"];
$message="";

if($act=="post"){

  //get values for meta generation from user		 	
  $description=$_POST["description"];
  $keywords=$_POST["keywords"];
  $robot=$_POST["robot"];
  $robot=$_POST["robot"];
  $refresh=$_POST["refresh"];
  $copyright=$_POST["copyright"];
  $author=$_POST["author"];
  $generator=$_POST["generator"];
  $language=$_POST["language"];
  $revisit=$_POST["revisit"];

//append values to generate the meta tag
$tag="";
$example="<font class=redtext>&lt;HTML&gt;<br>&lt;HEAD&gt;<br></font>";

$tag.="&LT;META NAME=\"description\" CONTENT=\"".$description."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"description\" CONTENT=\"".$description."\"&GT;\n";

$tag.="&LT;META NAME=\"keywords\" CONTENT=\"".$keywords."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"keywords\" CONTENT=\"".$keywords."\"&GT;\n";

if($robot=="ok"){

$tag.="&LT;META NAME=\"robot\" CONTENT=\"".$_POST["robotans"]."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"robot\" CONTENT=\"".$_POST["robotans"]."\"&GT;\n";

}
if($refresh=="ok"){

$tag.="&LT;META NAME=\"refresh\" CONTENT=\"".$_POST["refreshans"]."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"refresh\" CONTENT=\"".$_POST["refreshans"]."\"&GT;\n";

}
if($copyright=="ok"){

$tag.="&LT;META NAME=\"copyright\" CONTENT=\"".$_POST["copyrightans"]."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"copyright\" CONTENT=\"".$_POST["copyrightans"]."\"&GT;\n";

}
if($author=="ok"){

$tag.="&LT;META NAME=\"author\" CONTENT=\"".$_POST["authorans"]."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"author\" CONTENT=\"".$_POST["authorans"]."\"&GT;\n";

}
if($generator=="ok"){

$tag.="&LT;META NAME=\"generator\" CONTENT=\"".$_POST["generatorans"]."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"generator\" CONTENT=\"".$_POST["generatorans"]."\"&GT;\n";

}
if($language=="ok"){
$tag.="&LT;META HTTP-EQUIV=\"Content-Language\" CONTENT=\"".$_POST["languageans"]."\"&GT;\n";
$example.="<BR>&LT;META HTTP-EQUIV=\"Content-Language\" CONTENT=\"".$_POST["languageans"]."\"&GT;\n";

}
if($revisit=="ok"){

$tag.="&LT;META NAME=\"revisit-after\" CONTENT=\"".$_POST["revisitans"]."\"&GT;\n";
$example.="<BR>&LT;META NAME=\"revisit-after\" CONTENT=\"".$_POST["revisitans"]."\"&GT;\n";

}

  $example.="<BR><BR><font class=redtext>&lt;/HEAD&gt;</font>";
}

include "includes/userheader.php";
?>

<FORM ACTION="metagen.php?act=post" name=metaForm METHOD=post>
<?php
$linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                    FOOTER_PROMOTION_MANAGER =>'promotionmanager.php',
                    PROMOTION_MANAGER_METATAG_GEN_TITLE =>'metagen.php');
echo getBreadCrumb($linkArray);
?>
<h2><?php echo PROMOTION_MANAGER_METATAG_GEN_TITLE; ?></h2>

<div class="comm_div">
<div class="comm_text">
<p class="txt09"><?php echo META_DESCRIPTION1; ?>&lt;<?php echo META_DESCRIPTION2; ?>&gt; <?php echo META_DESCRIPTION3; ?> &lt;<?php echo META_DESCRIPTION4; ?>&gt; <?php echo META_DESCRIPTION5; ?> </p> </div>
</div>

<br>


        <!--start HELPPANEL CODE -->
        
        <script language="javascript">

        function goback(page){
         if(page=="metagen"){

                   document.metaForm.action="metagen.php?goback=meta";

         }else if(page=="promotionmanager"){

                   document.metaForm.action="metagen.php?goback=promo";


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
        
 
        <!--end  HELPPANEL CODE -->










<?php if($act!="post"){?>
<script>

function validate(){

    if(document.metaForm.description.value==""){
      alert("<?php echo VAL_SITE_DESC;?>");
      document.metaForm.description.focus();
      return false;

    }else if(document.metaForm.keywords.value==""){
      alert("<?php echo VAL_SITE_KEYWORDS;?>");
      document.metaForm.keywords.focus();
      return false;

    }else{

    document.metaForm.submit();

    }


}

</script>

<div class="form-pnl">
    
   
    <ul>
		<h4 class="hding_10"><?php echo META_INFORMATION; ?></h4>
		<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="customize-tbl">
		  <tr>
			<td valign="top" align="left" width="250">
			<label class="txt11"><?php echo SITE_DESCRIPTION; ?> <sup>*</sup></label>
			</td>
			<td valign="top" align="left">
			  <p>
			    <input type="text" size="40"  class=textbox  name="description" VALUE="" style="width:638px; ">
			    </p>
			  <p><span class="txt09"><?php echo SITE_DESCRIPTION_EG; ?></span>
			        </p></td>
		  </tr>
		  <tr>
			<td valign="top" align="left">
			<label class="txt11"><?php echo SITE_KEYWORDS; ?> <sup>*</sup></label>
			</td>
			<td valign="top" align="left">
			  <p>
			    <textarea  class=textbox  rows=5 name=keywords style="width:638px; "></textarea>
			    </p>
			  <p><span class="txt09"><?php echo SITE_KEYWORDS_EG; ?></span>
			        </p></td>
		  </tr>
		  <tr>
			<td valign="top" align="left">&nbsp;</td>
			<td valign="top" align="left">&nbsp;</td>
		  </tr>
		
		</table>

		<h4 class="hding_10"><?php echo ADDITIONAL_INFO; ?>&nbsp;<?php echo OPTIONAL; ?></h4>
		
		<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="customize-tbl">
		<tr>
			<td valign="top" align="left" width="250">
			<label class="txt11"><INPUT TYPE=checkbox NAME=robot VALUE=ok align=left ><?php echo ROBOTS_OPTION; ?></label>
		  </td>
			<td valign="top" align="left">
			  <p>
			    <SELECT NAME=robotans>
                    <OPTION VALUE="index,follow"><?php echo ROBOTS_OPTION1; ?>
                    <OPTION VALUE="noindex,nofollow"><?php echo ROBOTS_OPTION2; ?>
                    <OPTION VALUE="index,nofollow"><?php echo ROBOTS_OPTION3; ?>
                    <OPTION VALUE="noindex,follow"><?php echo ROBOTS_OPTION4; ?>
                </SELECT>
			    </p>
			  <p><span class="txt09"><?php echo ROBOTS_DESCRIPTION; ?>
			      </span>
			        </p></td>
		  </tr>
 
 		  <tr>
			<td valign="top" align="left" width="200">
			<label class="txt11"><INPUT TYPE=checkbox NAME=refresh VALUE=ok ><?php echo REFRESH_RATE; ?> </label>
			</td>
			<td valign="top" align="left">
			  <p>
			    <INPUT TYPE=text  class=textbox NAME=refreshans SIZE=3 VALUE="" style="width:380px;">
			    </p>
			  <p><span class="txt09"><?php echo REFRESH_RATE_DESCRIPTION; ?></span>
			        </p></td>
		  </tr>
		  <tr>
			<td valign="top" align="left" width="200">
			<label class="txt11"><INPUT TYPE=checkbox NAME=copyright VALUE=ok ><?php echo COPYRIGHT; ?> </label>
			</td>
			<td valign="top" align="left">
			  <p>
			    <INPUT TYPE=text class=textbox  NAME=copyrightans VALUE="" SIZE=35 style="width:380px;">
			  </p>
			  <p>			      <span class="txt09"><?php echo COPYRIGHT_DESCIPTION; ?></span>
			        </p></td>
		  </tr>

		  <tr>
			<td valign="top" align="left" width="200">
			<label class="txt11"><INPUT TYPE=checkbox NAME=author VALUE=ok ><?php echo AUTHOR; ?> </label>
			</td>
			<td valign="top" align="left">
			 <INPUT TYPE=text class=textbox  NAME=authorans SIZE=35 VALUE="" style="width:380px;">
			<p><span class="txt09"><?php echo AUTHOR_DESCRIPTION; ?></span>
			</td>
		  </tr>
            
		   <tr>
			<td valign="top" align="left" width="200">
			<label class="txt11"><INPUT TYPE=checkbox NAME=generator VALUE=ok ><?php echo GENERATOR; ?></label>
			</td>
			<td valign="top" align="left">
			  <p>
			    <INPUT TYPE=text class=textbox  NAME=copyrightans VALUE="" SIZE=35 style="width:380px;">
			  </p>
			  <p>			      <span class="txt09"><?php echo GENERATOR_DESCRIPTION; ?></span>
			        </p></td>
		  </tr>
                
		   <tr>
			<td valign="top" align="left" width="200">
			<label class="txt11"> <INPUT TYPE=checkbox NAME=language VALUE=ok ><?php echo LANGUAGE; ?></label>
			</td>
			<td valign="top" align="left">
			  <p>
			    <select name=languageans class=selectbox>
                    <option value=BG><?php echo BULGARIAN; ?></option>
                    <option value=CS><?php echo CZECH; ?></option>
                    <option value=DA><?php echo DANISH; ?></option>
                    <option value=DE><?php echo GERMAN; ?></option>
                    <option value=EL><?php echo GREEK; ?></option>
                    <option SELECTED value=EN><?php echo ENGLISH; ?></option>
                    <option value=EN-GB><?php echo ENGLISH_UK; ?></option>
                    <option value=EN-US><?php echo ENGLISH_US; ?></option>
                    <option value=ES><?php echo SPANISH; ?></option>
                    <option value=ES-ES><?php echo SPANISH_SPAIN; ?></option>
                    <option value=FI><?php echo FINNISH; ?></option>
                    <option value=HR><?php echo CROATIAN; ?></option>
                    <option value=IT><?php echo ITALIAN; ?></option>
                    <option value=FR><?php echo FRENCH; ?></option>
                    <option value=FR-CA><?php echo FRENCH_QUEBEC; ?></option>
                    <option value=FR-FR><?php echo FRENCH_FRANCE; ?></option>
                    <option value=JA><?php echo JAPANESE; ?></option>
                    <option value=KO><?php echo KOREAN; ?></option>
                    <option value=NL><?php echo DUTCH; ?></option>
                    <option value=NO><?php echo NORWEGIAN; ?></option>
                    <option value=PL><?php echo POLISH; ?></option>
                    <option value=RU><?php echo RUSSIAN; ?></option>
                    <option value=SV><?php echo SWEDISH; ?></option>
                    <option value=ZH><?php echo CHINESE; ?></option>
                </select>
			  </p>
			  <p>			      <span class="txt09"><?php echo LANG_DESCRIPTION; ?></span>
			        </p></td>
		  </tr>
		  
		   <tr>
			<td valign="top" align="left" width="200">
			<label class="txt11"> <INPUT TYPE=checkbox NAME=revisit VALUE=ok ><?php echo SEARCHENGINE_REVISITRATE; ?><br>&nbsp; &nbsp;   &nbsp;<?php echo IN_DAYS; ?></label>
			</td>
			<td valign="top" align="left">
			  <p>
			    <INPUT class=textbox TYPE=text NAME=revisitans SIZE=3 VALUE="" style="width:380px;">
			    </p>
			  <p><span class="txt09"><?php echo REVISIT_DESCRIPTION; ?></span>
			        </p></td>
		  </tr>
		   <tr>
			<td valign="top" align="left" width="200">
			<label class="txt11"> </label>
			</td>
			<td valign="top" align="left">
			<br>
			<span class="btn-container">
                
                
                <INPUT TYPE=button class="btn01 right" onClick=validate() VALUE="Generate Meta Tags">
				<input type="button" name="btnBack" value="Back" class="btn02 right" style="margin-right:10px;" onClick="goback('promotionmanager');" >
            </span>
			<span class="txt09"></span>
			</td>
		  </tr>
		   
		
		</table>
       
    </ul>
</div>
<!--<table border=0 width=100%><tr>
<td align=center class=maintext>

<br>

<fieldset><legend class=subtext><b>Meta Information</b></legend>
<br><table width=100% border=0><tr><td class=maintext width=30% valign=top>
Site Description<font color="red"><sup>*</sup></font>:
</td>
<td class=maintext align=left>
<input type="text" size="40"  class=textbox  name="description" VALUE=""><br>
A little, plain language description of the site/page. It is used by search engines to describe your document. Particularly important if your document has very little text. (E.g.: Yoursite.com is a place to sell roses.)
</td>
</tr>

<tr>
<td class=maintext valign=top>
Site Keywords<font color="red"><sup>*</sup></font>: <br>(Seperated by commas):
</td>
<td class=maintext align=left>
<textarea  class=textbox style="width:200px;" rows=5 name=keywords></textarea><br>
The keywords is useful as a way to reinforce the terms you think your site page is important for. (E.g.: roses, red roses, yellow roses, buy roses, purchase roses.)
<br>&nbsp;
</td>
</tr>
</table>
</fieldset>


<fieldset><legend class=subtext><b>Additional Information&nbsp;[Optional]</b></legend>
<br>

<table>

<tr><td valign=top>
<INPUT TYPE=checkbox NAME=robot VALUE=ok align=left >
</td><td  align=left class=maintext valign=top>Robots Option
<SELECT NAME=robotans>
<OPTION VALUE="index,follow">Index this page and follow all links
<OPTION VALUE="noindex,nofollow">Don't index this page and don't follow any links
<OPTION VALUE="index,nofollow">Index this page, but don't follow any links
<OPTION VALUE="noindex,follow">Don't index this page, but follow links
</SELECT><br>
(The Robots META tag is a tag to tell a robot if it is ok to index this page or not. It also is used to invite a spider to walk down through all your pages.)
</td></tr>

<tr><td align=left valign=top>
<INPUT TYPE=checkbox NAME=refresh VALUE=ok >
</td><td  align=left class=maintext valign=top >
Refresh rate in seconds
<INPUT TYPE=text  class=textbox NAME=refreshans SIZE=3 VALUE=""><br>
(Specifies a delay in seconds before the browser automatically reloads the document.)
</td></tr>

<tr><td align=left valign=top>
<INPUT TYPE=checkbox NAME=copyright VALUE=ok >
</td><td  align=left class=maintext>
Copyright line:
<INPUT TYPE=text class=textbox  NAME=copyrightans VALUE="" SIZE=35><br>
(It tells the unqualified copyright statement for the site.)
</td></tr>

<tr><td align=left valign=top>
<INPUT TYPE=checkbox NAME=author VALUE=ok >
</td><td  align=left class=maintext valign=top>
Author:
<INPUT TYPE=text class=textbox  NAME=authorans SIZE=35 VALUE=""><br>(It represents the unqualified author's name who created the site. )
</td></tr>

<tr><td align=left valign=top>
<INPUT TYPE=checkbox NAME=generator VALUE=ok >
</td><td  align=left class=maintext>
Generator: <INPUT TYPE=text class=textbox  NAME=generatorans SIZE=35 VALUE=""><br>
(It tells the name and version number of a publishing tool used to create the page)
</td></tr>

<tr><td align=left valign=top>
<INPUT TYPE=checkbox NAME=language VALUE=ok >
</td><td  align=left class=maintext>
Language:

<select name=languageans class=selectbox>
<option value=BG>Bulgarian</option><option value=CS>Czech</option>
<option value=DA>Danish</option><option value=DE>German</option>
<option value=EL>Greek</option><option SELECTED value=EN>English</option>
<option value=EN-GB>English (UK)</option><option value=EN-US>English (US)</option>
<option value=ES>Spanish</option><option value=ES-ES>Spanish (Spain)</option>
<option value=FI>Finnish</option><option value=HR>Croatian</option>
<option value=IT>Italian</option><option value=FR>French</option>
<option value=FR-CA>French (Quebec)</option>
<option value=FR-FR>French (France)</option>
<option value=JA>Japanese</option><option value=KO>Korean</option>
<option value=NL>Dutch</option><option value=NO>Norwegian</option>
<option value=PL>Polish</option><option value=RU>Russian</option>
<option value=SV>Swedish</option><option value=ZH>Chinese</option>
</select><br>
(This tag is to indicate to search engines the languages supported by your site.
)












</td></tr>

<tr><td  align=left valign=top>
<INPUT TYPE=checkbox NAME=revisit VALUE=ok >
</td><td  align=left class=maintext>
Search engines revisit rate in days
<INPUT class=textbox TYPE=text NAME=revisitans SIZE=3 VALUE=""><br>
(The revisit meta tag is used by search engines as a means to indicate how often a web page should be revisited for re-indexing)
</td></tr>

<tr><td colspan=2 width=100%>&nbsp;

 
</td></tr></table>

</fieldset><br>
<INPUT TYPE=button class=button onClick=validate() VALUE="Generate Meta Tags">&nbsp;&nbsp;<input type="button" class="button"
value="Back"     onClick="goback('promotionmanager');">



</td>
</tr></table>-->

<?php
}else{
?>

<br><br><br>
<table width=100%>
<tr><td align=center class=maintext><?php echo PASTE;?>
<br>
<?php echo $example;?>

</td></tr>
<tr><td align=center>
<textarea cols=50 rows=20><?php echo $tag; ?></textarea>
</td><tr>
<tr><td align=center> <input type="button" class="button"
value="Generate More Meta Tags" onClick="goback('metagen');">&nbsp;&nbsp;<input type="button" class="button"
value="Back"  onClick="goback('promotionmanager');"></td></tr>
</table>
<br><br><br>&nbsp;

<?php
}
?>
</FORM>
<?php
include "includes/userfooter.php";
?>