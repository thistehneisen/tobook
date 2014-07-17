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
include "includes/subpageheader.php";

$cmsData = getCmsData('terms'); 
$termscontent   = $cmsData['section_content'];
$termsTitle     = $cmsData['section_title'];
?>
<h2><?php echo $termsTitle; ?></h2>
<table><tr>
        <td align=center>
            <table width=95% border=0 cellpadding="6" cellspacing="2">
                <tr>
                    <td align=center class=maintext>
                        <?php
                        echo $termscontent;
                        ?>
                    </td>
                </tr>
                <tr>
                    <td align=center>&nbsp;</td>
                </tr>
            </table>
        </td>
    </tr></table>

<?php
include "includes/userfooter.php";
?>