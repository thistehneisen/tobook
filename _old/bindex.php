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
$curTab = 'home';

//include files
include "includes/session.php";
include "includes/config.php";
include "includes/homeheader.php";
if($_GET["affid"] != "") {
    $_SESSION["session_naffid"] = $_GET["affid"];
}
unset($_SESSION['siteDetails']);
/*
 * To read the logo details from database
 */


 




/*
$sql_logo = "Select vname,vvalue from tbl_lookup where vname IN('Logourl') Order by vname ASC";
$resultLogo=mysql_query($sql_logo) or die(mysql_error());
$rowLogo = mysql_fetch_array($resultLogo);
$_SESSION["session_logourl"] = $rowLogo["Logourl"];
*/
//get 6 images of latest added templates for display
 $qry="select distinct(temp_id),template.*,themes.theme_image_thumb,themes.theme_id 
        from tbl_template_mast as template 
        left join  tbl_template_themes AS themes ON themes.temp_id=template.ntemplate_mast 
        WHERE ntemplate_type='N' GROUP BY template.ntemplate_mast 
        order by ddate desc limit  0,6";
 
 
 //$qry="select distinct(themes.temp_id),template.*,themes.theme_image_thumb from tbl_template_mast as template 
        //left join  (select distinct(temp_id),distinct(theme_image_thumb) from tbl_template_themes) themes  ON themes.temp_id=template.ntemplate_mast 
        //order by ddate desc limit  0,6";
$result=mysql_query($qry);
$numRows=mysql_num_rows($result);

//        $pic1="images/temp1.jpg";
//        $pic2="images/temp2.jpg";
//        $pic3="images/temp3.jpg";
//        $pic4="images/temp4.jpg";
//        $pic5="images/temp5.jpg";
//        $pic6="images/temp6.jpg";
//        $holder='$pic';
        $counter=1;

        while($row=mysql_fetch_array($result)){
            //echo"<pre>";print_r($row);echo"</pre>";
              switch ($counter){
			  case 1:
                 // $pic1= "showtemplateimage.php?tmpid=".$row['ntemplate_mast']."&type=thumb&imgname=".$row['theme_image_thumb'];
			  	$pic1= "templates/".$row["ntemplate_mast"]."/".$row['theme_image_thumb'];
				//$pic1link= "<a class=subtext style=\"text-decoration:none;\" href='javascript:void(0)' onclick=\"showpreview('index','".stripslashes($row["ntemplate_mast"])."','".$row["vtype"]."');\">";
                              $pic1link="<a class='newwadd_templates modal' name='600' id='viewtemplate.php?prtype=index&tid=".$row['theme_id']."&id=".$row['ntemplate_mast']."&type=redirect&'  href='javascript:void(0);' title='Click to get preview'>";
			  	break;

			  case 2:
                //$pic2= "showtemplateimage.php?tmpid=".$row['ntemplate_mast']."&type=thumb&imgname=".$row['theme_image_thumb'];
			  	 $pic2= "templates/".$row["ntemplate_mast"]."/".$row['theme_image_thumb'];
				//$pic2link= "<a class=subtext style=\"text-decoration:none;\"  href='javascript:void(0)' onclick=\"showpreview('index','".stripslashes($row["ntemplate_mast"])."','".$row["vtype"]."');\">";
                              $pic2link="<a class='newwadd_templates modal' name='600' id='viewtemplate.php?prtype=index&tid=".$row['theme_id']."&id=".$row['ntemplate_mast']."&type=redirect&'  href='javascript:void(0);' title='Click to get preview'>";
			  	break;

			  case 3:
                 //$pic3= "showtemplateimage.php?tmpid=".$row['ntemplate_mast']."&type=thumb&imgname=".$row['theme_image_thumb'];
			  	 $pic3= "templates/".$row["ntemplate_mast"]."/".$row['theme_image_thumb'];
				//$pic3link= "<a class=subtext style=\"text-decoration:none;\"  href='javascript:void(0)' onclick=\"showpreview('index','".stripslashes($row["ntemplate_mast"])."','".$row["vtype"]."');\">";
                              $pic3link="<a class='newwadd_templates modal' name='600' id='viewtemplate.php?prtype=index&tid=".$row['theme_id']."&id=".$row['ntemplate_mast']."&type=redirect&'  href='javascript:void(0);' title='Click to get preview'>";
			  	break;

			  case 4:
                $pic4= "showtemplateimage.php?tmpid=".$row['ntemplate_mast']."&type=thumb&imgname=".$row['theme_image_thumb'];
			  	$pic4= "templates/".$row["ntemplate_mast"]."/".$row['theme_image_thumb'];
				//$pic4link= "<a class=subtext style=\"text-decoration:none;\"  href='javascript:void(0)' onclick=\"showpreview('index','".stripslashes($row["ntemplate_mast"])."','".$row["vtype"]."');\">";
                              $pic4link="<a class='newwadd_templates modal' name='600' id='viewtemplate.php?prtype=index&tid=".$row['theme_id']."&id=".$row['ntemplate_mast']."&type=redirect&'  href='javascript:void(0);' title='Click to get preview'>";
			  	break;

			  case 5:
               // $pic5= "showtemplateimage.php?tmpid=".$row['ntemplate_mast']."&type=thumb&imgname=".$row['theme_image_thumb'];
			  	$pic5= "templates/".$row["ntemplate_mast"]."/".$row['theme_image_thumb'];
				//$pic5link= "<a class=subtext style=\"text-decoration:none;\"  href='javascript:void(0)' onclick=\"showpreview('index','".stripslashes($row["ntemplate_mast"])."','".$row["vtype"]."');\">";
                              $pic5link="<a class='newwadd_templates modal' name='600' id='viewtemplate.php?prtype=index&tid=".$row['theme_id']."&id=".$row['ntemplate_mast']."&type=redirect&'  href='javascript:void(0);' title='Click to get preview'>";
			  	break;

			  case 6:
              //  $pic6= "showtemplateimage.php?tmpid=".$row['ntemplate_mast']."&type=thumb&imgname=".$row['theme_image_thumb'];
			  	$pic6= "templates/".$row["ntemplate_mast"]."/".$row['theme_image_thumb'];
				//$pic6link= "<a class=subtext style=\"text-decoration:none;\"  href='javascript:void(0)' onclick=\"showpreview('index','".stripslashes($row["ntemplate_mast"])."','".$row["vtype"]."');\">";
                              $pic6link="<a class='newwadd_templates modal' name='600' id='viewtemplate.php?prtype=index&tid=".$row['theme_id']."&id=".$row['ntemplate_mast']."&type=redirect&'  href='javascript:void(0);' title='Click to get preview'>";
			  	break;
			  }
			  $counter++;
        }

?>
<style type="text/css">
<!--
.style1 {color: #FFFFFF}
-->
</style>

<script>
 function showpreview(prtype,id,type){
            var leftPos = (screen.availWidth-500) / 2;
		    var topPos = (screen.availHeight-400) / 2 ;
			winurl="templatepriview.php?prtype="+prtype+"&id="+id+"&type="+type+"&";
		    //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
		    insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);


   }
   function selecttemplate_1(tid,ttype){

			document.frmindex.action="login.php?req=ts&tid="+tid+"&ttype="+ttype+"&";
   			document.frmindex.submit();
		    //location.href="./showtemplates.php?type=T&templateid="+tid;

   }
</script>
<script language="javascript" src="js/modal.popup.js"></script>
<script language="javascript" src="js/validations.js"></script>
<div class="hm_wrap">
<?php
  $cmsData = getCmsData('home'); 
 ?>
<div class="hm_lftdiv">
    <h1>
        <?php echo $cmsData['section_title']; ?>
    </h1>
        <?php echo $cmsData['section_content']; ?>
</div>

<div class="hm_rgtdiv">
    <?php
    if($numRows>0){?>
        <h2><?php echo HOME_NEW_ADITIONS;?></h2>
        
        <div class="preview_listing">
            <div class="previewlist">
              <?php 
              if($pic1!=''){
                echo $pic1link; ?><img border=0 src="<?php echo $pic1;?>" width="120" height="81"></a> 
                <?php echo $pic1link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a>
              <?php
              }?>      
            </div>
            <div class="previewlist">
               <?php 
              if($pic2!=''){
                echo $pic2link; ?><img border=0 src="<?php echo $pic2;?>" width="120" height="81"></a> 
                 <?php echo $pic2link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a>
              <?php
              }?>   
            </div>
            <div class="previewlist">
              <?php 
              if($pic3!=''){
                echo $pic3link; ?><img border=0 src="<?php echo $pic3;?>" width="120" height="81"></a> 
                 <?php echo $pic3link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a>
              <?php
              }?>  
            </div>
            <div class="clear"></div>
            
            <div class="previewlist">
              <?php 
              if($pic4!=''){
                echo $pic4link; ?><img border=0 src="<?php echo $pic4;?>" width="120" height="81"></a>
                 <?php echo $pic4link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a>
              <?php
              }?>      
            </div>
            <div class="previewlist">
               <?php 
              if($pic5!=''){
                echo $pic5link; ?><img border=0 src="<?php echo $pic5;?>" width="120" height="81"></a> 
                 <?php echo $pic5link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a>
              <?php
              }?>   
            </div>
            <div class="previewlist">
              <?php 
              if($pic6!=''){
                echo $pic6link; ?><img border=0 src="<?php echo $pic6;?>" width="120" height="81"></a> 
                 <?php echo $pic6link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a>
              <?php
              }?>  
            </div>
        </div>
        
        <!--<table width="100%" height="280"  border="0" class="tmlist_styles" cellpadding="0" cellspacing="0">
            <tr align="center">
             <td>
               
                    <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="text">
                        <?php
                        if($pic1!=''){?>
                            <tr>
                                <td align="center"></td>
                            </tr>
                            <tr>
                                <td align="center"><?php echo $pic1link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a></td>
                            </tr>
                             <?php
                            }?>
                      
                    </table>
                
               
               </td>
               <td>
               
                        <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="text">
                             <?php
                if($pic2!=''){?>
                            <tr>
                                <td align="center"><?php echo $pic2link; ?><img border=0 src="<?php echo $pic2;?>" width="120" height="81"></a><br></td>
                            </tr>
                            <tr>
                                <td align="center"><?php echo $pic2link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a></td>
                            </tr>
                               <?php
                }?>
                        </table>
                
                    </td>
                   <td>
                <?php if($pic3!=''){?>  
                   

                    <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="text">
                        <tr>
                            <td align="center"><?php echo $pic3link; ?><img border=0 src="<?php echo $pic3;?>" width="120" height="81"></a><br></td>
                        </tr>
                        <tr>
                            <td align="center"><?php echo $pic3link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a></td>
                        </tr>
                    </table>
                         <?php
                } ?> 
                </td>
                
            </tr>
            <tr align="center">
                
                <td>
                    <?php  if($pic4!=''){?> 
                    <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="text">
                        <tr>
                            <td align="center"><?php echo $pic4link; ?><img border=0 src="<?php echo $pic4;?>" width="120" height="81"></a></td>
                        </tr>
                        <tr>
                            <td align="center"><?php echo $pic4link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a></td>
                        </tr>
                    </table>
                     <?php
                }  ?>
                </td>
                
                    <td>
                     <?php
                if($pic5!=''){?> 
                        <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="text">
                            <tr>
                                <td align="center"><?php echo $pic5link; ?><img border=0 src="<?php echo $pic5;?>" width="120" height="81"></a></td>
                            </tr>
                            <tr>
                                <td align="center"><?php echo $pic5link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a></td>
                            </tr>
                        </table>
                        <?php
                }  ?>
                    </td>
                    <td>
                <?php    
                if($pic6!=''){?>   
                        

                        <table width="100%"  border="0" cellpadding="5" cellspacing="0" class="text">
                            <tr>
                                <td align="center"><?php echo $pic6link; ?><img border=0 src="<?php echo $pic6;?>" width="120" height="81"></a></td>
                            </tr>
                            <tr>
                                <td align="center"><?php echo $pic6link; ?>[&nbsp;<b><?php echo HOME_PREVIEW;?></b>&nbsp;]</a></td>
                            </tr>
                        </table>
                    <?php
                }?>            
                    </td>
            </tr>

        </table>-->
        <div class="clear"></div>
       <?php
    }?>
</div>

<div class="clear"></div>
</div>


<form name="frmindex" method=post action="">
<input type=hidden name="Templateselected" value="YES">

</form>

<?php
include "includes/userfooter.php";
 
?>