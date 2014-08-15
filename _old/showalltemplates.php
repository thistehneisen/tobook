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
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$begin=($_GET["begin"] != ""?$_GET["begin"]:$_POST["begin"]);
$num=($_GET["num"] != ""?$_GET["num"]:$_POST["num"]);
$numBegin=($_GET["numBegin"] != ""?$_GET["numBegin"]:$_POST["numBegin"]);
if($begin == ""){
        $begin=0;
        $num=1;
        $numBegin=1;
}
$buildtype=$_SESSION['session_buildtype'];
$url="";
$build_prefix="";
if($buildtype == "advanced") {
        $url = "./code/editor.php?actiontype=init&type=new&";
		$build_prefix="A";
}
else {
        $url = "./getsitedetail.php?";
		$build_prefix="S";
}
if($_GET['type']=="T"){
      $selectedtemplateid=$_GET['templateid'];
	  /*categoryid
	    $categoryid=addslashes($_GET['catid']);
		if($categoryid !=""){
		 $_SESSION['session_categoryid']=$categoryid;
		}else{
		 $categoryid= $_SESSION['session_categoryid'];
		} */
      if($_SESSION['session_currenttempsiteid']==""){
                                $qry = "insert into tbl_tempsite_mast(ntempsite_id,vsite_name,nuser_id,ddate) values('','" . $_SESSION['session_sitename'] . "','" . $_SESSION["session_userid"] . "',now())";
                                 mysql_query($qry);
                                $_SESSION['session_currenttempsiteid'] = mysql_insert_id();
                                if (!is_dir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'])) {
                                    mkdir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'],0777);
									chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'],0777);

                                    mkdir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/images",0777);
									chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/images",0777);

                                                mkdir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/flash",0777);
												chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/flash",0777);

                                    $fp = fopen("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/resource.txt", "w");
                                    chmod("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'] . "/resource.txt", 0777);
                                                //copydirr("./".$_SESSION["session_template_dir"]."/".$selectedtemplateid."/watermarkimages","./workarea/tempsites/".$_SESSION['session_currenttempsiteid']."/images",0777,false);
                                                //copy("./".$_SESSION["session_template_dir"]."/".$selectedtemplateid."/style.css","./workarea/sites/".$_SESSION['session_currenttempsiteid']."/style.css");
                                }

                                if (!is_dir("./usergallery/" . $_SESSION["session_userid"])) {
                                    mkdir("./usergallery/" . $_SESSION["session_userid"],0777);
									chmod("./usergallery/" . $_SESSION["session_userid"],0777);
                                    mkdir("./usergallery/" . $_SESSION["session_userid"] . "/images",0777);
									chmod("./usergallery/" . $_SESSION["session_userid"] . "/images",0777);
                                    mkdir("./usergallery/" . $_SESSION["session_userid"] . "/flash",0777);
									chmod("./usergallery/" . $_SESSION["session_userid"] . "/flash",0777);			

                                }
                                if (!is_dir("./sitepages/tempsites/" . $_SESSION['session_currenttempsiteid'])) {
                                    mkdir("./sitepages/tempsites/" . $_SESSION['session_currenttempsiteid'],0777);
									chmod("./sitepages/tempsites/" . $_SESSION['session_currenttempsiteid'],0777);

                                }
       }
                //$_SESSION['session_currenttemplateid']=$selectedtemplateid;
                $_SESSION['session_cleared']="no";
				$_SESSION['session_backurl']="showtemplates.php?catid=".$_SESSION['session_categoryid'];
                 $location=$url."templateid=$selectedtemplateid&";
                 header("location:$location");
                exit;
}
$sql=" select ntemplate_mast,vthumpnail,vtype,ncat_id from  tbl_template_mast where vtype='".addslashes($buildtype)."'";
$sql .="  order by ddate desc ";
$session_back = "showalltemplates.php?begin=" . $begin . "&num=" . $num . "&numBegin=" . $numBegin . "&cmbSearchType=" . $cmbSearchType . "&txtSearch=" . $txtSearch;
$_SESSION['gtemplatebackurl']=$session_back;
// get the total amount of rows returned
$totalrows = mysql_num_rows(mysql_query($sql));
/*
Call the function:
I've used the global $_GET array as an example for people
running php with register_globals turned 'off' :)
*/
$navigate = pageBrowser($totalrows, 12, 12, "&cmbSearchType=$cmbSearchType&txtSearch=$txtSearch", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);
//echo $sql;



//$rs = mysql_query($sql);
include "includes/userheader.php";
?>
<script>
function showpreview(prtype,id,type){
            var leftPos = (screen.availWidth-500) / 2;
		    var topPos = (screen.availHeight-400) / 2 ;
			winurl="templatepriview_loged.php?prtype="+prtype+"&id="+id+"&type="+type+"&";
		    //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
		    insertFormWin = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos); 

			 
   }
function selecttemplate(tempid){
  document.getElementById("selectedtemplate").value=tempid;
  document.getElementById("postback").value="T";

 // document.frmSelectTemplate.submit();

}
 function selecttemplate_1(tid){
            document.frmSelectTemplate.action="showtemplates.php?type=T&templateid="+tid; 
   			document.frmSelectTemplate.submit();
		    //location.href="./showtemplates.php?type=T&templateid="+tid;
   
   }
</script>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align=center>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align=center><img src="./images/cstep1.gif" border="0"><br>&nbsp;<br>
								  <table width="40%">
								  	<tr>
										<td>
											<fieldset>
												<table>
													<tr>
														<td class="maintext">
															<b>Select a template for your site from the given list.</b>
														</td>
													</tr>
												</table>
											</fieldset>
										</td>
									</tr>
								  </table>
								  </td>
                                </tr>
                                <tr>
                                  <td align=center>&nbsp;</td>
                                </tr>
                <tr>
                <td >

                                <!-- Main section starts here-->
                                <form name="frmSelectTemplate" METHOD="post" action="">
                                <input type=hidden name=selectedtemplate id=selectedtemplate>
                                <input type=hidden name=postback id=postback>
                                <table width="100%" border="0" cellspacing="10" align="center">
                                         <?php
                                                                   if(mysql_num_rows($rs)>0){
                                                                        $i=0;
                                                                        while($row=mysql_fetch_array($rs)){
                                                                                if($i==0) {
                                                                                        echo "<tr>";
                                                                                }
                                                                                if($i==3) {
                                                                                     $i=0;
                                                                                          echo "</tr>";
                                                                                }
																			$prvlink= "<a class=subtext  style=\"text-decoration:none;\" href='javascript:void(0)' onclick=\"showpreview('index','".stripslashes($row["ntemplate_mast"])."','".$row["vtype"]."');\">";

                                                         ?>

                                                                                   <td width="33%" align=center class="temp_list_box">
																				         <?php
																						    $picthumb= "showtemplateimage.php?tmpid=".$row['ntemplate_mast']."&";
																						  ?> 

                                                                                         <a style=\"text-decoration:none;\" href="./showtemplates.php?type=T&templateid=<?php echo $row['ntemplate_mast']; ?>&categoryid=<?php echo $row['ncat_id']; ?>">
																						 <img src="<?php echo $picthumb;?>"  border="0">
																						 </a>
                                                                                   		<br><?php echo("Template ID: <b>" . $build_prefix . $row['ntemplate_mast'] . "</b>"); ?>
																						&nbsp;[<?php echo $prvlink; ?>&nbsp;<b>Select/View</b>&nbsp;</a>]
																				   </td>




                                                         <?php
                                                                        $i++;
                                                                                   }
                                                                                if($i==1){
                                                                                  echo "<td >&nbsp;</td></tr>";
                                                                                }else if($i==3){
                                                                                  echo "</tr>";
                                                                                }

                                                                   }else{
                                                        ?>
                                                               
                                                        <?php
                                                                   }
                                                         ?>
														 <tr class=background><td colspan="4" align="center" height="30">
														    <?php echo($navigate[2]); ?>&nbsp;
	                        							</td>
                        								</tr>
                               <tr><td class=subtext align="left" colspan=2 width=100%><a href="showcategories.php?cat=<?php echo $buildtype;?>" class="subtext"><img src="./images/back.gif" border="0" width="54px" height="15px"></a></td></tr>
                                </table>
                                </form>
                                <!-- Main section ends here-->
                </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td></tr>
                </table>
</td>
</tr>
</table>

<?php
include "includes/userfooter.php";
?>