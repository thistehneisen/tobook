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
 	
	$templateid			= 172;
	$templateThemeId 	= 4;
	$templatePath 		= $_SESSION["session_template_dir"] . "/" . $templateid;
	
	// code to get the template details
	$templateFile 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/index_temp.htm';
	$fh 			= fopen($templateFile, 'r');
	$theData 		= fread($fh, filesize($templateFile));
	fclose($fh);
	
	//echopre($_SESSION['siteDetails']);
	
	// select the panels of the template
	$sql 	= "Select * from tbl_template_panel where temp_id=".$templateid." ORDER BY panel_id ASC";
	$result = mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result) > 0) {
		while($row = mysql_fetch_assoc($result)) {			
			
			if($_SESSION['siteDetails']['homepage']['panels'][$row['panel_id']] == '')
				$_SESSION['siteDetails']['homepage']['panels'][$row['panel_id']] = $row['panel_html']; 
			 
				$panelContent = $_SESSION['siteDetails']['homepage']['panels'][$row['panel_id']];
				
				$editLink 	= '<span style="text-align:right;float:right;"> [<a name="600" id="edittemplate.php?panelid='.$row['panel_id'].'&templateid='.$templateid.'" class="modal" href="javascript:void(0);" title="Click to edit the tempplate" >edit</a>]</span>';	
				$replacer 	= '{$editable'.$row['panel_type'].'}';
			
				$editContent = '<div class="column" id="column_'.$row['panel_id'].'">
				<div class="dragbox" id="item_'.$row['panel_id'].'" >
				<h4><span class="configure" >'.$editLink.'</span>'.$row['panel_type'].':'.$row['panel_id'].'</h4>
				<div class="dragbox-content" id="editpanel_'.$row['panel_id'].'" >'.$panelContent.'</div></div> 
				</div>';
				$theData 	= str_replace($replacer,$editContent,$theData);
			 
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
<script language="javascript" src="js/jquery.js"></script>
<script language="javascript" src="js/modal.popup.js"></script>


<script type="text/javascript" src="js/jquery-1.3.2.js" ></script>
<script type="text/javascript" src="js/jquery-ui-1.7.2.custom.min.js" ></script>
<link rel="stylesheet" type="text/css" href="style/dragdropstyles.css" />
<script type="text/javascript" >
$(function(){
	$('.dragbox')
	.each(function(){
		$(this).hover(function(){
			$(this).find('h4').addClass('collapse');
		}, function(){
			$(this).find('h4').removeClass('collapse');
		})
	 
		.click(function(){
			$(this).siblings('.dragbox-content').toggle();
		})
		.end()
		.find('.configure').css('visibility', 'hidden');
	});
	$('.column').sortable({
		connectWith: '.column',
		handle: 'h4',
		cursor: 'move',
		placeholder: 'placeholder',
		forcePlaceholderSize: true,
		opacity: 0.4,
		stop: function(event, ui){
			$(ui.item).find('h4').click();
			var sortorder='';
			 
			$('.column').each(function(){
				var itemorder=$(this).sortable('toArray');
				var columnId=$(this).attr('id');
				sortorder+=columnId+'='+itemorder.toString()+'&';
			});

			sortorder+= 'templateid=<?php echo $templateid;?>&themeid=<?php echo $templateThemeId; ?>';
			 

			
			//  var order = $('#test-list').sortable('serialize');
		  		$("#info").load("process-sortable.php?"+sortorder);
		  		
			//alert('SortOrder: '+sortorder);
			/*Pass sortorder variable to server using ajax to save state*/
		}
	})
	.disableSelection();
});
</script>
 
	 <script language="javascript" src="js/validations.js"></script>
 
<div id="info">Waiting for update</div>

<table width="100%"  border="0" cellspacing="0" cellpadding="0">
<tr>
<td  valign="top" align=center>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                <tr>
                                  <td align="left">
								  <div class="stage_selector">
				  <span>1</span>&nbsp;&nbsp;Edit Template
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

				<a href="sitebuilder.php">Generate Site</a>
</td>
</tr>
</table>



<div class="column" id="newitembox" >
		<div class="dragbox" id="box_1" >
			<h4>FB Share</h4>
			<div class="dragbox-content" >
				  FB Share Link Here
			</div>
		</div>
		<div class="dragbox" id="box_2" >
			<h4>Tweet Link</h4>
			<div class="dragbox-content" >
				 Tweet Link Here 
			</div>
		</div>
	</div>

























 


































































<?php
include "includes/userfooter.php";
?>