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
include "includes/sitefunctions.php";

//echopre1($_SESSION['siteDetails']);


if(isset($_SESSION['siteDetails'])) { 

    $currentPage = $_SESSION['siteDetails']['currentpage'];
   
    // hack to update the external app positions
    foreach($_SESSION['siteDetails'][$currentPage]['panelpositions'] as $key=>$panelPositions) {
        if($key == 'exterbox') {
            //$panelPositions = sort($panelPositions);
            //echopre($panelPositions);
            $exterItems = array();
            foreach($panelPositions as $positions) {
                if(is_numeric($positions)) {
                    $addPosition = $positions;
                    $exterItems[] = $positions;
                }
                else {
                    $exterItems[] = $positions;
                }
            }
            $_SESSION['siteDetails'][$currentPage]['panelpositions'][$addPosition] = $exterItems;
        }
    }
    // hack ends

    $templateid		= $_SESSION['siteDetails']['templateid'];
    $templateThemeId 	= $_SESSION['siteDetails']['themeid'];
    $templatePath 	= $_SESSION["session_template_dir"] . "/" . $templateid;

    // code to get the template details
    if($currentPage == 'homepage')
        $templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/index_temp.htm';
    else
        $templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/subpage_temp.htm';

    $fh 		= fopen($templateFile, 'r');
    $theData 		= fread($fh, filesize($templateFile));
    fclose($fh);

    foreach($_SESSION['siteDetails'][$currentPage]['panelpositions'] as $key=>$positions) { 

        // get the panel details
        if(is_numeric($key)) {
            $panelDet = mysql_query('select panel_type from tbl_template_panel where panel_id='.$key) or die(mysql_error());
            if(mysql_num_rows($panelDet) > 0) {
                $rowPanel 		= mysql_fetch_assoc($panelDet);
                $replacer 	= '{$editable'.$rowPanel['panel_type'].'}';
                $editedHtml = '';
                foreach($positions as $panelPos) {
                    $panelBox = explode('_',$panelPos);
                    //echopre($panelBox);
                    if($panelBox[0] == 'exterbox') {

                        //TODO: load the external box content

                        //$editedHtml .=  "external box : ".$panelPos.'<br>';

                        if($panelBox[1] == 'navmenu') {		// for navigational menu params
                            $appDetails = $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos];
                            if(sizeof($appDetails) > 0) {
                                $editedHtml .= '<ul>';
                                foreach($appDetails as $key=>$details) {
                                    $editedHtml .= '<li><a href="'.$details['link'].'" target="'.$details['opentype'].'">'.  $details['title'].'</a></li>';
                                }
                                $editedHtml .= '</ul>';
                            }
                        }
                        else if($panelBox[1] == 'socialshare') {
                            $appDetails = $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos];
                            if(sizeof($appDetails) > 0) {
                                $socialShare = '';
                                foreach($appDetails as $key=>$details) {
                                    //echo $key.'='.$details.'<br>';
                                    if($key == 'twitlink')
                                        $socialShare .= '<a href="'.$details.'">Twitter</a>';
                                    else if($key == 'fppage')
                                        $socialShare .= '<a href="'.$details.'">Facebook</a>';
                                    else if($key == 'linkedinlink')
                                        $socialShare .= '<a href="'.$details.'">LinkedIn</a>';

                                }
                            }
                            $editedHtml .=  $socialShare;
                            //	echopre($panelPos);

                        }
                        else if($panelBox[1] == 'twitlink') {
                            $editedHtml .=  "external box : ".$panelPos.'<br>';
                        }

                    }
                    else {

                        //$panelData 		= $_SESSION['siteDetails'][$currentPage]['panels'][$panelPos];
                        // ------- code to replace the unwanted items-------
                        $replaceQuery 	= ".//a[@class='jqeditormenusettings']";
                        //$panelData 		= removeItemsUsingDOM($replaceQuery,$panelData);
                        // --------- replace code ends ------------
                        // $editedHtml .= $panelData;
                        $editedHtml .= removeItemsUsingDOM($replaceQuery,$_SESSION['siteDetails'][$currentPage]['panels'][$panelPos]);

                    }
                }

                $theData 	= str_replace($replacer,$editedHtml,$theData);
            }
        }
    }


    // add the theme style to the template
    $themeResult = mysql_query('select theme_style from tbl_template_themes where theme_id='.$templateThemeId) or die(mysql_error());
    if(mysql_num_rows($themeResult) > 0) {
        $rowTheme 		= mysql_fetch_assoc($themeResult);
        $themeCss 		= $rowTheme['theme_style'];
        $themeStyleUrl 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/'.$themeCss;
        $theData 		= str_replace($themeCss,$themeStyleUrl,$theData);
    }
    ?>

<script language="javascript" src="js/jquery.js"></script>
<script>
    $(function () {
        $("a").click(function() {
            var pagelink =$(this).attr('href');
            if(pagelink != '')
                $("#sitepreviewcontent").load("editor_sitepreviewloader.php?page="+pagelink);
            return false;
        });
    });
</script>


<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td>
            <div id="sitepreviewcontent"> <?php echo $theData;?></div>
        </td>
    </tr>
</table>

    <?php
}
else
    echo "Error";


?>
