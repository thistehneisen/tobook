<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

// +----------------------------------------------------------------------+

// | PHP version 4/5                                                      |

// +----------------------------------------------------------------------+

// | Copyright (c) 2005-2006 ARMIA INC                                    |

// +----------------------------------------------------------------------+

// | This source file is a part of easycreate sitebuilder                 |

// +----------------------------------------------------------------------+

// | Authors: mahesh<mahesh.s@armia.com>              		              |

// |          									                          |

// +----------------------------------------------------------------------+

?>

<?php

//include files

include "includes/session.php";

include "includes/config.php";

?>

<html>

<head>

<TITLE><?php echo getSettingsValue('site_name') ?>The do it yourself online website builder</TITLE>

<META name="description" content="<?php echo getSettingsValue('site_name') ?> will let you build your own websites online using our large collection of graphically intensive templates and template editors.Build a web site in six easy steps.">

<META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">

<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">

<link href=style/site.css TYPE="text/css" REL=STYLESHEET>

</head>

<body topmargin="0">

<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">

  <tr>

    <td width="1" align="center" valign="top" bgcolor="#134B66"><img src="images/spacer.jpg" width="1" height="1"></td>

    <td width="520" align="center"><table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0">

      <tr>

        <td align="center"><table width="800" border="0" cellpadding="0" cellspacing="0">

            <tr>

              <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td><table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">

                        <tr>

                          <td width="37%" align="left"><img src="images/corner.gif" width="23" height="26"></td>

                          <td width="60%" align="right">&nbsp; </td>

                          <td width="3%" align="right"><img src="images/cornervflip.gif" width="23" height="26"></td>

                        </tr>

                      </table>

                        <!-- <img src="images/paenlheader.gif" width="800" height="56"> --></td>

                  </tr>

                </table>

				<table width="800" border="0" cellpadding="0" cellspacing="0">

					<tr>

						<td style="background: url(./images/sbinnerheader.gif);BACKGROUND-REPEAT: no-repeat;" width="800" height="56" border="0" align="left" valign="top"><a href="index.php"><img src ="<?php echo($_SESSION["session_logourl"]); ?>" border=0></a></td>

						

					</tr>

				</table>

                <table width="100%"  border="0" cellpadding="0" cellspacing="0" class="text">

                  <tr>

                    <td width="26%" align="left"><img src="images/cornervflipleft.gif" width="23" height="26"></td>

                    <td width="54%" align="center">&nbsp;</td>

                    <td width="20%" align="right"><img src="images/cornerflip.gif" width="23" height="26"></td>

                  </tr>

                </table>

                <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td align="center" valign="top">

							<table width="80%"  border="0">

							  <!--- images prview heree-->

							</table>



					</td>

       			 </tr>

       		 </table>

                <table width="100%"  border="0" cellspacing="0" cellpadding="0">

                  <tr>

                    <td align="left" background="images/footerbarbg.gif"><img src="images/footerbarbg.gif" width="10" height="10"></td>

                  </tr>

                </table>

               

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
    <td align="center">Powered by <a href="http://www.iscripts.com/easycreate" class="copyright" target="_blank" >iScripts EasyCreate</a> . A premium product from <a href="http://www.iscripts.com" class="copyright" target="_blank" >iScripts.com</a></td>
  </tr>
</table>
</body>

</html>