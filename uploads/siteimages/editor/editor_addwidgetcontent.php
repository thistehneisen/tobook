<?php 

//function to generate the widget content to the html
//include "../includes/session.php";
//include "../includes/config.php";


/*
	 * function to add html widget content to the page
*/
function addWidgetContent($content) {
    $retHtml = '<div class="widgethtmlbox">'.$content.'</div>';
    return $retHtml;
}



//function to load the slider widget contents
function addWidgetSlider($panelPos,$currentPage) {
    $type='simple';
    $panelPosRow = explode("_",$panelPos);
    if($panelPos != '' && $currentPage != '') {
        if($type == 'simple') {
            $silderContent = '';
            $widgetSliderPath = BASE_URL.'widgets/slider/simple/';
            $sliderFiles = ' <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>';
            $sliderFiles .= '<script src="'.$widgetSliderPath.'js/bjqs-1.3.min.js"></script>';
            $sliderFiles .= '<link rel="stylesheet" href="'.$widgetSliderPath.'demo.css">';

            $sliderSettings = $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['settings'];

            $slideHeight = $sliderSettings['height'];
            $slideWidth = $sliderSettings['width'];
            $slideDelay = $sliderSettings['delay'];
            $jsCode = "<script>
        		jQuery(document).ready(function($) {
                            $('#banner-fade".$panelPosRow[2]."').bjqs({
                                height: ".$slideHeight.",width: ".$slideWidth.",responsive  : true, animspeed   : ".$slideDelay."
                            });
                        });
                        </script> ";
            $silderContent 	= '<div id="banner-fade'.$panelPosRow[2].'" style="float:left;width:'.$slideWidth.'px;" ><ul class="bjqs">';
            $galleryImage 	= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['images'];
            if($galleryImage) {
                foreach($galleryImage as $key=>$images) {
                    $imgUrl = $images['image'];
                    $silderContent .= ' <li><img src="'.$imgUrl.'"> </li>';
                }
            }
            $silderContent .= '</ul> </div>';
            $htmlContent 	= '<div id="'.$panelPos.'" class="widgetimageslider">'.$sliderFiles.$silderContent.$jsCode.'</div><div class="clear"></div><br/>';
        }
    }
    return $htmlContent;
}


// function to show the slider in the editor 
function showSlider($panelPos,$currentPage) {
    $type='simple';
    if($panelPos != '' && $currentPage != '') {
        if($type == 'simple') {
            $silderContent = '';
            $widgetSliderPath 	= BASE_URL.'widgets/slider/simple/';
            $sliderFiles       .= '<link rel="stylesheet" href="'.$widgetSliderPath.'demo.css">';
            $sliderSettings 	= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['settings'];
            $slideHeight 		= $sliderSettings['height'];
            $slideWidth 		= $sliderSettings['width'];
            $slideDelay 		= $sliderSettings['delay'];
            $silderContent 		= '<div id="banner-fade" style="float:left;width:'.$slideWidth.'px;" > ';
            $galleryImage 		= $_SESSION['siteDetails'][$currentPage]['apps'][$panelPos]['images'];
            if($galleryImage) {
                foreach($galleryImage as $key=>$images) {
                    $silderContent .= '  <img src="'.$images['image'].'"    width="'.$slideWidth.'"  >  ';
                    break;
                }
            }

            $silderContent     .= '  </div>';
            $htmlContent 		= '<div class="widgetimageslider">'.$sliderFiles.$silderContent.'</div><div class="clear"></div>';
        }
    }
    return $htmlContent;
}


/*
	 * function to create an image slideshow
*/
function createSlideShow($widgetid,$attributes='') {

    $type='simple';
    $widgetIdRow = explode("_",$widgetid);
    //$currentPage = $_SESSION['siteDetails']['currentpage'];
    $currentPage = "index";

    /*
		$galleryImage = $_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$widgetid]['images'];
		if(sizeof($galleryImage) > 0){
			foreach($galleryImage as $key=>$images) {
				$slideShowHtml .= '<br>'.$imgUrl = $images['image'];
 			}
		}
    */

    if($type == 'simple') {
        $silderContent = '';
        $widgetSliderPath = BASE_URL.'widgets/slider/simple/';
        $sliderFiles = ' <script src="http://code.jquery.com/jquery-1.7.1.min.js"></script>';
        $sliderFiles .= '<script src="'.$widgetSliderPath.'js/bjqs-1.3.js"></script>';
        $sliderFiles .= '<link rel="stylesheet" href="'.$widgetSliderPath.'demo.css">';



        $sliderSettings = $_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$widgetid]['settings'];

        $slideHeight = $sliderSettings['height'];
        $slideWidth = $sliderSettings['width'];
        $slideDelay = $sliderSettings['delay'];
        $jsCode = "   <script >
        		jQuery(document).ready(function($) {
          		$('#banner-fade".$widgetIdRow[1]."').bjqs({
		            height: ".$slideHeight.",width: ".$slideWidth.",responsive  : true, animspeed   : ".$slideDelay." 
		        });
        	});
      		</script> ";
        $silderContent 	= '<div id="banner-fade'.$widgetIdRow[1].'" style="float:left;width:'.$slideWidth.'px;" ><ul class="bjqs">';
        $galleryImage = $_SESSION['siteDetails'][$currentPage]['datatypes']['slider'][$widgetid]['images'];

        if($galleryImage) {
            foreach($galleryImage as $key=>$images) {
                $imgUrl = $images['image'];
                $silderContent .= ' <li><img src="'.$imgUrl.'"     > </li>';
            }
        }
        $silderContent .= '</ul> </div>';
        $htmlContent 	= '<div id="'.$widgetid.'" class="widgetimageslider" style="width:100%">'.$sliderFiles.$silderContent.$jsCode.'</div><div class="clear"></div>';
    }


    //$slideShowHtml .= 'Slide Show'.$widgetid;
    return $htmlContent;

}

function addGoogleAd($ad_slot, $ad_width , $ad_height , $ad_comment , $ad_client) {
    // Default Values are mentioned on function prototype
    // These default values will be used only when you pass no parameters
    global $total_ads;
    if($total_ads == 3) return;
    $total_ads++;
    $code = "\n<script type=\"text/javascript\"><!--\n";
    $code .= "\tgoogle_ad_client = \"$ad_client\";\n";
    $code .= "\t/* $ad_comment */\n";
    $code .= "\tgoogle_ad_slot = \"$ad_slot\";\n";
    $code .= "\tgoogle_ad_width = $ad_width;\n";
    $code .= "\tgoogle_ad_height = $ad_height;\n";
    $code .= "\t//-->\n";
    $code .= "</script>\n";
    $code .= "<script type=\"text/javascript\"\n";
    $code .= "\tsrc=\"http://pagead2.googlesyndication.com/pagead/show_ads.js\">\n";
    $code .= "</script>\n";
    $startTag = "<div class=\"google_adsense_div\">";
    $endTag = "<div class=\"clear\"></div></div><div class=\"clear\"></div>";
    return $startTag.$code.$endTag;
}

/*
 * function to change the default style with the selected theme
*/
function changeSiteStyle($templateid,$templateThemeId,$theData) {

    // find active theme of the template
    $actTheme = mysql_query('select theme_style from tbl_template_themes where temp_id='.$templateid.' and theme_default=1') or die(mysql_error());
    if(mysql_num_rows($actTheme) > 0) {
        $rowActTheme 	= mysql_fetch_assoc($actTheme);
        $defTheme 		= $rowActTheme['theme_style'];
    }
    $themeResult = mysql_query('select theme_style from tbl_template_themes where theme_id='.$templateThemeId) or die(mysql_error());
    if(mysql_num_rows($themeResult) > 0) {
        $rowTheme 		= mysql_fetch_assoc($themeResult);
        $themeCss 		= $rowTheme['theme_style'];
        $themeStyleUrl 	= $_SESSION["session_template_dir"] . "/" . $templateid.'/'.$themeCss;
        $theData 		= str_replace($defTheme,$themeStyleUrl,$theData);
    }
    return $theData;
}



/*
 * function to add script for guest book content listing
*/
function showGuestBookContents() {
    /*
	$test = <<<END
<p> <?php 
echo 'text'; 
?> </p>
END;


ob_start();
eval("?>$test");
$result = ob_get_clean();
	
	return $result;
    */
}


/*
	 * function to show the content for the guest book widget
*/
function showGuestBookWidgetContent() {

    $guestBookSample = '<div style="float:left; width:700px;">Current GuestBook Entries <br> Posted by test(test@test.com) on 2013-03-18.<br><br> Its a test guest comment<br><br>';
    $guestBookSample .='<form action="guestbook.php?act=post" method="post" name="gbForm" id="gbForm" ><fieldset>
<table width="100%" border="0"><tbody><tr align="center"><td colspan="3"><br><strong><font size="2" face="verdana">Add your guestbook entry</font> </strong><br>&nbsp;</td>
</tr><tr><td width="100%" align="center" colspan="3"><font size="1" face="verdana" color="red">Thank you. Your Guest book entry added</font></td>
</tr><tr><td width="45%" align="right"><font size="2" face="verdana">Your Name</font></td><td width="3%">&nbsp;</td>
<td width="52%" valign="top" align="left"><input type="text" id="name" name="name"></td></tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr>
<td align="right"><font size="2" face="verdana">Your Email Address</font></td><td>&nbsp;</td><td valign="top" align="left"><input type="text" id="email" name="email"></td>
</tr><tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr><tr><td align="right"><font size="2" face="verdana">Guest Book Matter</font></td>
<td>&nbsp;</td><td valign="top" align="left">            <textarea id="matter" name="matter"></textarea></td></tr><tr><td>&nbsp;</td>
<td>&nbsp;</td><td>&nbsp;</td></tr><tr align="center"><td colspan="3"><input type="button" class="popup_orngbtn" onclick="validate();" value="Sign Guest Book"></td>
</tr></tbody></table></fieldset></form></div>';
    return $guestBookSample;

}



/*
	 * function to generate the guest book adding code
*/
function generateGuestBookContentAddCode($pageLink='',$pagetype='preview') {
    $jsCode = ' <script>
                            function checkMail(email){
                                  var str1=email;
                                  var arr=str1.split(\'@\');
                                  var eFlag=true;
                                  if(arr.length != 2){
                                      eFlag = false;
                                  }
                                  else if(arr[0].length <= 0 || arr[0].indexOf(\' \') != -1 || arr[0].indexOf("\'") != -1 || arr[0].indexOf(\'"\') != -1 || arr[1].indexOf(\'.\') == -1){
                                      eFlag = false;
                                  }
                                  else{
                                      var dot=arr[1].split(\'.\');
                                      if(dot.length < 2){
                                          eFlag = false;
                                      }
                                      else{
                                          if(dot[0].length <= 0 || dot[0].indexOf(\' \') != -1 || dot[0].indexOf(\'"\') != -1 || dot[0].indexOf("\'") != -1){
                                              eFlag = false;
                                          }

                                          for(i=1;i < dot.length;i++){
                                              if(dot[i].length <= 0 || dot[i].indexOf(\' \') != -1 || dot[i].indexOf(\'"\') != -1 || dot[i].indexOf("\'") != -1 || dot[i].length > 4){
                                                  eFlag = false;
                                              }
                                          }
                                      }
                                  }
                                  return eFlag;
                          }

                          function validate(){

                                if(document.gbForm.name.value=="" ){
                                    alert("Please enter your name");
                                    document.gbForm.name.focus();
                                }else if (document.gbForm.email.value==""){
                                    alert("Please enter your email");
                                    document.gbForm.email.focus();
                                }else if(checkMail(document.gbForm.email.value)==false){
                                    alert(\'Invalid mail format\');
                                    document.gbForm.email.focus();
                                    return false;
                                }else if (document.gbForm.matter.value==""){
                                    alert("Please enter your matter");
                                    document.gbForm.matter.focus();
                                }else{
                                    document.gbForm.submit();
                                }

                            }
                          </script>';

    $gbcontent=' <?php
                         $displaycontents = "";
                         $messageClass = "";
                          $filename = \'gb.txt\';
                          // make sure the file exists and is writable first.
                          if (is_writable($filename)) {
                              if (!$handle = fopen($filename, \'a+\')) {
                                   $message.= "Cannot open file ($filename)";
                                   $messageClass = "red";
                                   exit;
                              }
                              If($_GET["act"]=="post"){
                                      $message= "";
                                      $content = addslashes($_POST["name"])."`|^".$_POST["email"]."`|^".$_POST["matter"]."`|^".date("Y-m-d")."~`|\n";
                                      if (fwrite($handle, $content) === FALSE) {
                                          $message.= "Cannot write to file ($filename)";
                                          $messageClass = "red";
                                          exit;
                                      }
                                      $message.= "Thank you. Your Guest book entry added";
                                      $messageClass = "green";
                                      fseek($handle, 0);
                              }
                              //read file content to make display
                                  $displaycontents.=\'<table align="center" width="70%"><tr><td align="center"><font face="verdana" size="2"><b>Current GuestBook Entries<br>&nbsp;</b></font></td></tr>\';
                                  if(filesize($filename) != 0){
                                      $readcontents = @fread($handle, filesize($filename));
                                      $entryarray=explode("~`|\n",$readcontents);
                                      $entryCount = count($entryarray)-1;
                                      for($i=0; $i &lt; $entryCount; $i++){
                                          $valuearray=explode("`|^",$entryarray[$i]);
                                          $displaycontents.=\'<tr><td align="left" bgcolor="#dddddd"><font face="verdana" size="2">Posted &nbsp;by &nbsp;\'.stripslashes($valuearray[0]).\'( \'.$valuearray[1].\' ) &nbsp;on&nbsp; \'.$valuearray[3].\'</font></td></tr>\';
                                          $displaycontents.=\'<tr><td align="left" valign="top"><font face="verdana" size="2"><br>\'.$valuearray[2].\'</font></td></tr>\';
                                          $displaycontents.=\'<tr><td align="left" valign="top">&nbsp;</td></tr>\';
                                      }
                                  }else{
                                    $displaycontents.=\'<tr><td align="center" valign="top"><font face="verdana" size="2">Sorry! Guest book is empty.</font></td></tr>\';
                                  }
                                  $displaycontents.=\'</table>\';
                                  fclose($handle);

                          } else {
                              $message.= "The file $filename is not writable.Please provide write permission to it";
                              $messageClass = "red";
                          }?>
                         <div class="guestbookmessages">
                          <?php
                          echo $displaycontents;
                          ?></div>';



    $gbHtml = '<div class="guestbookform"> <table width="100%"  border="0" align="center">
                            <tr>
                              <td>&nbsp;</td>
                            </tr>
                            <tr>
                              <td align="center"><form name="gbForm" id="gbForm" method="post" action="'.$pageLink.'?act=post">
                                <fieldset style="width:400px;">
                                    <table width="100%"  border="0">
                                  <tr align="center">
                                    <td colspan="3"><br>
                                      <strong><font face=verdana size=2>Add your guestbook entry</font> </strong><br>&nbsp;</td>
                                    </tr>
                                                    <tr>
                                    <td width="100%" align="center" colspan="3"><font face="verdana" size="1" color="<?php echo $messageClass;?>" ><?php echo $message; ?></font></td>
                                  </tr>
                                  <tr>
                                    <td width="45%" align="right"><font face=verdana size=2>Your Name <span style="color:red">*</span></font></td>
                                    <td width="3%">&nbsp;</td>
                                    <td width="52%" align="left" valign="top"><input name="name" type="text" id="name"></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right"><font face=verdana size=2>Your Email Address <span style="color:red">*</span></font></td>
                                    <td>&nbsp;</td>
                                    <td align="left" valign="top"><input name="email" type="text" id="email"></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr>
                                    <td align="right"><font face=verdana size=2>Guest Book Matter <span style="color:red">*</span></font></td>
                                    <td>&nbsp;</td>
                                    <td align="left" valign="top">
                                    <textarea name="matter" id="matter"></textarea></td>
                                  </tr>
                                  <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                  </tr>
                                  <tr align="center">
                                    <td colspan="3"><input type="button" class="popup_orngbtn" value="Sign Guest Book" onclick=validate();></td>
                                    </tr>
                                </table>
                                    </fieldset>
                              </form></td>
                            </tr>
                          </table> </div>';

//ob_start();
    /*
eval("?>$gbcontent");
    */
//$result = ob_get_clean();
    if($pagetype =='publish')
        $result = $gbcontent.$gbHtml.$jsCode;
    else
        $result = $gbHtml;

    $result = '<div class="guestbook">'.$result.'</div>';

    return $result;
    //return $gbcontent;
}

function addCommonSocialShareContent($commonSocialShareId,$socialLinkDetails) {
    
    $socialShareDiv = '<div id="'.$commonSocialShareId.'" class="socialshare" data-type="socialshare">';
    $i=0;
    foreach($socialLinkDetails as $key=>$links) {
        $keyVal = $i++;
        $ssId = $commonSocialShareId.'_'.$keyVal;
        if(strpos($links['link'], 'http')==false)
            $link ='http://'.$links['link'];
        else
            $link = $links['link'];

        $socialShareDiv .= '<a href="'.$link.'" target="_blank" linkmode="external"><img id="'.$ssId.'" class="editableshare" src="'.$links['image'].'" data-param="'.$commonSocialShareId.'" data-edit="true"></a>';
    }
    $socialShareDiv .= '</div>';
    return $socialShareDiv;
}



?>