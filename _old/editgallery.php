<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +----------------------------------------------------------------------+

// | PHP version 4/5                                                      |

// +----------------------------------------------------------------------+

// | Copyright (c) 2005-2006 ARMIA INC                                    |

// +----------------------------------------------------------------------+

// | This source file is a part of easycreate sitebuilder                 |

// +----------------------------------------------------------------------+

// | Authors: sudheesh<sudheesh@armia.com>      		              |

// |          									                          |

// +----------------------------------------------------------------------+

include "includes/session.php";

include "includes/config.php";

include "includes/function.php";

if($_POST['btnbacktoimagegallery']=="Back to System gallery"){

    header("location:".$_SESSION["ourgallery_backurl"]);

	exit;

 }else if($_POST['btnbacktoimagegallery1']=="Back to Image gallery"){

     header("location:gallerymanager.php");

  exit;

 

 }

include "includes/userheader.php";



$userid=$_SESSION["session_userid"];

?>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">

<tr>

<td  valign="top" align=center>

                <table width="100%"  border="0" cellspacing="0" cellpadding="0">

				<tr>

				    

                	<td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<img src="images/Image-Editor.gif" ></td>

            	</tr>

				<tr>

				   <td>&nbsp;</td>

				</tr>

                <tr>

                <td >

				<!-- Main section starts here-->

				         <table width="100%" border=0 align=center>

						        <tr>

								   <td>

								    <?php

									               $Spaceallotedfull="0";

													 if(!validateSizePerUser($_SESSION["session_userid"],$size_per_user,$allowed_space)) {

													       $edittype=$_GET['edittype'];

													       $errorinlink = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete 

														   unused images or any/all of the sites created by you to proceed further.<br>&nbsp;";     

														   $Spaceallotedfull="1";

														   

													?>	  

													    <form name=frmSizeexceeds method=post> 

														  <table>

																<tr>

																   <td class=errormessage><?php echo $errorinlink; ?></td>

																</tr>

																	<?php if($edittype=="systemgallery"){ ?>

																				 <tr>

																				    <td>

																					   <input type="submit" name=btnbacktoimagegallery  class=button value="Back to System gallery" >

																					</td>

																				 </tr>

																	 

																	<?php }else{?>

													

																			 <tr>

																			    <td>

																				   <input  type="submit" class=button name=btnbacktoimagegallery1 value="Back to Image gallery">

																				</td>

																			 </tr>

																<?php } ?>

													     </table>

									 </form>

													<?php 	   

													 

													}else{

													    require("./galleryedit_inc.php");

													} 

													

													?>

								   </td>

								</tr>

							   

						 </table>

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

// user footer canot include here because loadJavaScript function calls  just before body tag



//include "includes/userfooter.php";

?>

		</td>

        </tr>

        </table>

                <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td align="left" background="images/footerbarbg.gif"><img src="images/footerbarbg.gif" width="10" height="10"></td>

                  </tr>

                </table>

                <!-- ///start of footer links....................................................    -->

				<?php

				include("./includes/userbottomlinks.php");

				?>

				<!-- ///end of footer links....................................................    -->

		                <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td bgcolor="#134B66">&nbsp;</td>

                  </tr>

                </table></td>

            </tr>

        </table></td>

      </tr>

    </table></td>

    <td width="1" align="center" valign="top" bgcolor="#134B66"><img src="images/spacer.jpg" width="1" height="1"></td>

  </tr>

</table>

<table width="800" border="0" align="center" cellpadding="0" cellspacing="0" class="text">
  <tr>
    <td align="center"class="copyright">Powered by <a href="http://www.iscripts.com/easycreate" class="copyright" target="_blank" >iScripts EasyCreate</a> . A premium product from <a href="http://www.iscripts.com" class="copyright" target="_blank" >iScripts.com</a></td>
  </tr>
</table>
<?php 

/* $Invalidimage -> will be set to TRUE for invalid image in galleryedit_inc.php.

*  otherwise $ci->loadJavaScript(); would be called before </body> tag

*/ 		

if($Invalidimage !="TRUE" and $Spaceallotedfull=="0"){

  $ci->loadJavaScript();

}

?>

</body>

</html> 