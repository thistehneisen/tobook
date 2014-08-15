<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php

$curTab = 'dashboard';

include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
//if($_GET['cat']=="simple"){
//          $_SESSION['session_buildtype']="simple";
//}else{
//          $_SESSION['session_buildtype']="advanced";
//}
$sql="select * from tbl_template_category  order by vcat_name ";
$totalrows = mysql_num_rows(mysql_query($sql));
$navigate = pageBrowser($totalrows, 10, 6, "", $_GET[numBegin], $_GET[start], $_GET[begin], $_GET[num]);
// execute the new query with the appended SQL bit returned by the function
$sql = $sql . $navigate[0];
$rs = mysql_query($sql);
$rs = mysql_query($sql);
include "includes/userheader.php";
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align=center>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align="left">
                        <!--div class="stage_selector">
                            <span>1</span>&nbsp;&nbsp;<?php echo SM_SELECT_CATEGORY; ?>
                        </div-->
                        <?php $linkArray = array( TOP_LINKS_DASHBOARD =>'usermain.php',
                                                  FOOTER_SITE_MANAGER =>'sitemanager.php',
                                                  SM_SELECT_CATEGORY  =>'showcategories.php');

                             echo getBreadCrumb($linkArray);?>
                        <!--h2 class="lft"><span class="step-cnt">1</span><?php echo SM_SELECT_CATEGORY_THEME; ?>.</h2-->
                        <!--<a href="showalltemplates.php" class="ryt grey-btn01"><?php echo SM_SHOW_ALL_TEMPLATES; ?></a>-->
                        <!--a href="showtemplates.php?catid=E_ALL" class="ryt grey-btn01"><?php echo SM_SHOW_ALL_TEMPLATES; ?></a-->
                    </td>
                </tr>
				<tr>
					<td><h2 class="lft"><span class="step-cnt">1</span><?php echo SM_SELECT_CATEGORY_THEME; ?>.</h2></td>
					<td><a href="showtemplates.php?catid=all" class="ryt grey-btn01"><?php echo SM_SHOW_ALL_TEMPLATES; ?></a></td>
				</tr>
                <tr>
                    <td align=center>&nbsp;</td>
                </tr>
                <tr>
                    <td colspan="2">
                        <!-- Main section starts here-->
                        <table width="100%" border=0 align=center>
                            <tr><td class=subtext align=center colspan=4 width=100%><br></td></tr>
                            <tr>
                                 <td colspan="6">
                                        <div class="category-display">
                                        <?php
                                        if (mysql_num_rows($rs) > 0) {
                                            $i = 0;
                                            while ($row = mysql_fetch_array($rs)) {
                                                if ($i == 0) {
                                                    echo "<tr>";
                                                }
                                                if ($i == 2) {
                                                    $i = 0;
                                                    echo "</tr><tr>";
                                                }
                                                ?>

                                                    <div class="category-box">
                                                        <a href="./showtemplates.php?catid=<?php echo $row['ncat_id']; ?>"><img src="./<?php echo $row['vcat_thumpnail']; ?>" alt="<?php echo $row['vcat_name']; ?>" title="<?php echo $row['vcat_desc']; ?>" border=0></a>
                                                        <!--<img src="themes/Coastal-Green/business-ico.png">-->
                                                        <p><?php echo $row['vcat_name']; ?></p>
                                                    </div>
                                                 <?php
                                                $i++;
                                            }?>
                                               
                                <?php            
                                if ($i == 1) {
                                    echo "<td colspan='2'>&nbsp;</td></tr>";
                                } else if ($i == 2) {
                                    echo "</tr>";
                                }
                            } else {
                                ?>
                                <tr><td><?php echo SM_NO_CATEGORY; ?></td></tr>
                                <?php
                            }
                            ?>
                                </div>
                            </td>  
                            </tr>
                            <tr>
                                <td colspan="6" valign="bottom"	class="category">
									<div class="admin-table-btm">
										<div class="total-list lft">
										<?php echo SM_LISTING." ". $navigate[1]." ". SM_OF." ". $totalrows. " " . SM_RESULTS; ?>
										</div>
										<div class="list-pagin ryt">
											<?php echo($navigate[2]);
											?>&nbsp;
										</div>
										<div class="clear"></div>
									</div>
                                </td>
                            </tr>
                            <!--<tr><td class=subtext align=center colspan=4 width=100%><br></td></tr>
                            <tr>
                                <td colspan="6">
                                    <div class="category-display">
                                        <div class="category-box">
                                            <img src="themes/Coastal-Green/business-ico.png">
                                            <p>Business and Services</p>
                                        </div>
                                        <div class="category-box">
                                            <img src="themes/Coastal-Green/edu-ico.png">
                                            <p>Education, University etc</p>
                                        </div>
                                        <div class="category-box">
                                            <img src="themes/Coastal-Green/family-ico.png">
                                            <p>Families and Friends</p>
                                        </div>
                                        <div class="category-box">
                                            <img src="themes/Coastal-Green/simple-theme-ico.png">
                                            <p>Families and Friends</p>
                                        </div>
                                        <div class="category-box">
                                            <img src="themes/Coastal-Green/simple-theme-ico.png">
                                            <p>Families and Friends</p>
                                        </div>
                                        <div class="category-box">
                                            <img src="themes/Coastal-Green/simple-theme-ico.png">
                                            <p>Families and Friends</p>
                                        </div>
                                        <div class="clear"></div>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td width="30%" valign="bottom"	class="category"></td>
                                <td colspan="5" align="right">
                                    <div class="pagination ryt">
                                        <ul>
                                            <li><img src="themes/Coastal-Green/prev.png"></li>
                                            <li>Prev</li>
                                            <li><a href="#">1 </a>| </li>
                                            <li><a href="#" class="active">2 </a>| </li>
                                            <li>Next</li>
                                            <li><img src="themes/Coastal-Green/next.png"></li>
                                        </ul>
                                    </div>
                                </td>
                            </tr>-->
                        </table>
                        <!-- Main section ends here-->
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

<?php
include "includes/userfooter.php";
?>