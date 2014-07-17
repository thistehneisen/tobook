<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>	        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
if(($_SESSION["session_loginname"] == "") && basename($_SERVER["REQUEST_URI"]) != "login.php") {
    header("location:login.php");
    exit;
}
/*
if($_SESSION["session_loginname"] != "" && $_SERVER['HTTP_REFERER'] == "" && basename($_SERVER["REQUEST_URI"]) != "usermain.php" && basename($_SERVER['REQUEST_URI']) != "publishpage.php"  && basename($_SERVER['REQUEST_URI']) != "sitemanager.php"){
	header("location:usermain.php");
	exit;	
}
*/
//include "applicationheader.php";
?>
<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of easycreate sitebuilder                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
    include_once "function.php";
    if((strpos($_SERVER['REQUEST_URI'], "admin") != '') || strpos($_SERVER['REQUEST_URI'], "code") != '' || strpos($_SERVER['REQUEST_URI'], "simpleIEeditor") != '')
    include_once "../includes/config.php";
    else
    include_once "includes/config.php";
    
    $theme = getSettingsValue('theme');
    $_SESSION["session_style"] = $theme;

    $logo = getSettingsValue('Logourl');
 ?>
 
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<TITLE><?php echo($_SESSION["session_lookupsitename"]); ?> - <?php echo HEADER_SITE_TITLE; ?></TITLE>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<META name="description" content="<?php echo($_SESSION["session_lookupsitename"]); ?> will let you build your own websites online using our large collection of graphically intensive templates and template editors.Build a web site in six easy steps.">
<META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<link href="favicon.ico" type="image/x-icon" rel="icon"> 
<link href="favicon.ico" type="image/x-icon" rel="shortcut icon">
</head>
<body>
<style>
.editorloader{ position:absolute; top:45%; left:45%;z-index:9999;}
.editorloader p{font-size:20px;}
.editorloader img{margin-top:10px;}
</style>
<div class="editorloader"  >
<p align="center"> <?php echo EDITOR_LOADING;?><br>
<img src="images/loader.gif"></p></div>
    <div class="wrps_main">
	
	 <style type="text/css">
   
 
#sticker {
    position:fixed;
	width:100%;
	z-index:9999;
    top:0px;
}
    </style>
	 
	 <div id="sticker">
	
        <!-- editor_new hdr starts-->
        <div class="editor_hdrnew"  >
			<div class="editor_hdrnew_leftcol">
                            <a href="index.php"><img height="38" border="0" src="<?php echo $logo;?>"></a><span><?php echo EDITOR;?></span>
			</div>
			
			<div class="editor_hdrnew_rightcol"> 
				<div class="hdr_rightcolbx"> 
					<a href="#" class="editorhdr_widget_btn" id="jqwidgetshow"><span><?php echo EDITOR_WIDGETS;?></span></a>
				</div> 
				<div class="hdr_rightcolbx"> 
					<a title="Site Preview" href="editor_sitepreview.php" class="editorhdr_preview_btn sitepreview"><span><?php echo EDITOR_PREVIEW;?></span></a>
				</div>  
				<div class="hdr_rightcolbx">

				<a href="userhelp/editor/index.html" class="iframe" title="Editor Help"><?php echo EDITOR_HELP;?></a> | 
                                    <?php $pagescount = count($_SESSION['siteDetails']['pages']);
                                        
                                        if($pagescount== 0 && $_SESSION['cnt']!=2){
                                           
                                           echo "<script>";
                                           echo "window.location.reload();";
                                           echo "</script>";  
                                           $_SESSION['cnt'] = 2;                                      
                                        }
                                            ?>

                                    <?php echo EDITOR_PAGES;?> 
                                    <span id="jqsitepagelist">
                                    <select name="slct_pages" class="select_stylenew1 jQPages" >
                                        <?php foreach($_SESSION['siteDetails']['pages'] as $pages){ ?>
                                            <option value="<?php echo $pages['alias'] ?>" <?php echo ($_SESSION['siteDetails']['currentpage']==$pages['alias'])?'selected':'';?> ><?php echo ucwords($pages['title']);?></option>
                                        <?php } ?>
                                        <option value="addpage" id="jQAddPage"><?php echo EDITOR_ADDEDIT;?></option>
                                        <!--option value="addpage">Add New Page</option-->
                                    </select>
                                    </span> 
				</div>
 
				<div class="clear"></div>
			</div>
			
			<div class="clear"></div>
		</div>
        <!--  editor_new hdr ends -->
		
		 <!--  editor_widgetdropdown starts  ---------------------------------------------- -->
		 <div class="widget_dropdown" id="jqwidgetbox" style="display: none;"> 
		 	<a href="#" class="widget_closebtn" id="jqclosewidgetbox"></a>
		 	<div class="widget_dd_arrow"></div>
			
		 	<div class="dragndrop_widget_wrapper">
		 	
		 	<?php $appid = time();?>
		 	<!-- 
		 	<div class="socialshare_divs ssdiv_width1"><div class="column" id="externalapp_guestbook" data-attr="exterbox_guestbook_<?php echo $appid;?>">
					<div class="dragbox" id="exterbox_guestbook_<?php //echo $appid;?>" >
						<h4 id="headexterbox_guestbook_<?php //echo $appid;?>"><a href="#" class="guestbook">Guest Book</a></h4>
					</div>
			</div></div>
		 	 -->
		 <?php
                 $checkForm = getAppSettingsValue('dynamic_form');
                 if($checkForm == 1) {
                 ?>
		 	
                    <div class="socialshare_divs ssdiv_width7">
                        <div class="column" id="externalapp_dynamicform" data-attr="exterbox_dynamicform_<?php echo $appid;?>">
                            <div class="dragbox" id="exterbox_dynamicform_<?php echo $appid;?>" >
                                <h4 id="headexterbox_dynamicform_<?php echo $appid;?>"><a href="#" class="dynamicform"><?php echo EDITOR_FORM;?></a> </h4>
                            </div>
                        </div>
                    </div>
                        
              <?php
                 }
		 	    $checkAdsense = getAppSettingsValue('google_adsense');
		 	 if($checkAdsense == 1) {
		 	 ?>
                        <div class="socialshare_divs ssdiv_width2">
                            <div class="column" id="externalapp_googleadsense" data-attr="exterbox_googleadsense_<?php echo $appid;?>">
                                <div class="dragbox" id="exterbox_googleadsense_<?php echo $appid;?>" >
                                    <h4 id="headexterbox_googleadsense_<?php echo $appid;?>"><a href="#" class="adsense"><?php echo EDITOR_ADSENSE;?></a> </h4>
                                </div>
                            </div>
                        </div>
                 <?php }
                  $checkSlider = getAppSettingsValue('slider');
		 	 if($checkSlider == 1) {
                 
                 ?>
		 	<div class="socialshare_divs ssdiv_width3">
                            <div class="column" id="externalapp_slider" data-attr="exterbox_slider_<?php echo $appid;?>">
                                <div class="dragbox" id="exterbox_slider_<?php echo $appid;?>" >
                                    <h4 id="headexterbox_slider_<?php echo $appid;?>"><a href="#" class="image"><?php echo EDITOR_SLIDER;?></a></h4>
                                </div>
                            </div>
                        </div>
                        <?php }
                        
                         $checkContact = getAppSettingsValue('contact_form');
		 	 if($checkContact == 1) {     
                        ?>
                        
			<div class="socialshare_divs ssdiv_width4">
                            <div class="column" id="externalapp_form" data-attr="exterbox_form_<?php echo $appid;?>">
                                <div class="dragbox" id="exterbox_form_<?php echo $appid;?>" >
                                    <h4 id="headexterbox_form_<?php echo $appid;?>"><a href="#" class="formicon"><?php echo EDITOR_CONTACT;?></a> </h4>
                                </div>
                            </div>
                        </div>
                        <?php } 
                          $checkNavMenu = getAppSettingsValue('navigation_menu');
		 	 if($checkNavMenu == 1) {    
                        ?>
			<div class="socialshare_divs ssdiv_width5">
                            <div class="column" id="externalapp_navmenu" data-attr="exterbox_navmenu_<?php echo $appid;?>">
                                <div class="dragbox" id="exterbox_navmenu_<?php echo $appid;?>" >
                                    <h4 id="headexterbox_navmenu_<?php echo $appid;?>"><a href="#" class="menu"><?php echo EDITOR_MENU;?></a>  </h4>
                                </div>
                            </div>
                        </div>
                        <?php }
                         $checkShares = getAppSettingsValue('social_shares');
		 	 if($checkShares == 1) {    
                        ?>
			<div class="socialshare_divs ssdiv_width6">
                            <div class="column" id="externalapp_socialshare" data-attr="exterbox_socialshare_<?php echo $appid;?>">
                                <div class="dragbox" id="exterbox_socialshare_<?php echo $appid;?>" >
                                    <h4 id="headexterbox_socialshare_<?php echo $appid;?>"><a href="#" class="s_share"><?php echo EDITOR_SOCIALSHARE;?></a></h4>
                                </div>
                            </div>
                        </div>
                        <?php }
 			$checkHtmlWidget = getAppSettingsValue('html_widget');
		 	 if($checkHtmlWidget == 1) {    
                        
                        ?>
			<div class="socialshare_divs ssdiv_width7">
                            <div class="column" id="externalapp_htmlbox" data-attr="exterbox_htmlbox_<?php echo $appid;?>">
                                <div class="dragbox" id="exterbox_htmlbox_<?php echo $appid;?>" >
                                    <h4 id="headexterbox_htmlbox_<?php echo $appid;?>"><a href="#" class="html"><?php echo EDITOR_HTML;?></a></h4>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
		 	
			<!--<a href="#" class="s_share">Social share</a>
			
				<a href="#" class="menu">Menu</a>
				<a href="#" class="image">Image</a>
				<a href="#" class="slideshow">Slide show</a>
				<a href="#" class="html">HTML</a>   -->	
				
				
				<p class="widget_title"><?php echo EDITOR_DRAG;?></p>
				
			</div>
			
		 <div class="clear"></div>
		 </div>
		 </div>
		 <!-- editor_widgetdropdown ends -------------------------------------------------- -->
		

        <div class="cntarea_dvs" style="padding-top:47px;">
<!-- external panel for editor  -->


            <div class="cnt_innerdvs">
