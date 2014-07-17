<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2012-2013 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 2                 |
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
	include "includes/session.php";
	include "includes/config.php";
	include "includes/function.php";
	include "includes/userheader.php";
 	if(isset($_SESSION['siteDetails'])) {
 		
 		// echopre($_SESSION['siteDetails']);
		$templateid			= $_SESSION['siteDetails']['templateid'];
		$templateThemeId 	= $_SESSION['siteDetails']['themeid'];
		$templatePath 		= $_SESSION["session_template_dir"] . "/" . $templateid;
		 
		// code to get the template details
		$templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/index_temp.htm';
		$fh 			= fopen($templateFile, 'r');
		$theData 		= fread($fh, filesize($templateFile));
		fclose($fh);
		 
		//echopre($theData);
		
		//echopre($_SESSION['siteDetails']['homepage']['panelpositions']);
	 //	echo "<hr>";
		foreach($_SESSION['siteDetails']['homepage']['panelpositions'] as $key=>$positions){
			//echo $key.'<br>';
			
			// get the panel details
			$panelDet = mysql_query('select panel_type from tbl_template_panel where panel_id='.$key) or die(mysql_error());
			if(mysql_num_rows($panelDet) > 0) {
				$rowPanel 		= mysql_fetch_assoc($panelDet);
				//echo $panelType 		= $rowPanel['panel_type'];
				$replacer 	= '{$editable'.$rowPanel['panel_type'].'}';
				//echopre($positions);
				$editedHtml = '';
				foreach($positions as $panelPos){
					//echo '='.$panelPos;
					$editedHtml .= $_SESSION['siteDetails']['homepage']['panels'][$panelPos];
					
				}
				$theData 	= str_replace($replacer,$editedHtml,$theData);
			//	echo "<br>";
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
	
	
	// change the image path of the template
 	$templateSettingsXML 	=  $_SESSION["session_template_dir"] .'\\'.$templateid.'\templateDetails.xml';
	$xml 					= simplexml_load_file($templateSettingsXML);
	foreach($xml->images->filename as $tempImages){
		$fielPath 	= $templatePath."/".$tempImages[0];		
    	$theData 	= str_replace($tempImages[0],$fielPath,$theData);
    }
		



?>
 
 
 
 
 

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align=center>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left">
								  <div class="stage_selector">
				  <span>1</span>&nbsp;&nbsp;Generating Site
				  </div>
									<div class="bc01">
										 
										<div class="clear"></div>
									</div>
								  
								  </td>
                                </tr>
								<tr>
									<td>
									
									
									
                                        
                                        <?php 
										echo $theData;?>
                                        </td>
								</tr>
								
                               
                <tr>
                <td >

                               
                </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                                <tr><td>&nbsp;</td></tr>
                </table>

				 
</td>
</tr>
</table>

 
<?php 


 	}
 	else 	
 		echo "Error";
 	?>

<?php
include "includes/userfooter.php";
?>