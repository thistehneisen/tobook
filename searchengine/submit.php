<?php
	/***************************************************************************
					    submit.php
		            ---------------------
		    Author		:	Arun Vijayan. C
			Date        :	Sun Aug 24 2003
		    email		:	ifetchmaster@hotmail.com
		
		   Desc : SubmitForce v.1.0 url submitter file
		
	 ***************************************************************************/
		
	/***************************************************************************
	 *                                         				                                
	 *   This program is free software; you can redistribute it and/or modify  	
	 *   it under the terms of the GNU General Public License as published by  
	 *   the Free Software Foundation, provided that you keep this text intact.
	 *
	 ***************************************************************************/

include "_config.php";

$selected_engines=$_POST['selected_engines'];
$url=$_POST['url'];
$email=$_POST['email'];
include "SubmitForce.class.php";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE> submitting URL <?=$title_suffix;?></TITLE>
<LINK REL="stylesheet" type="text/css" href="<?php echo $style_sheet_href;?>">
</HEAD>
<BODY bgcolor="#D3DDDD">
<p>
<script type="text/javascript"><!--
google_ad_client = "pub-7866720674639050";
google_ad_width = 728;
google_ad_height = 90;
google_ad_format = "728x90_as";
google_ad_channel ="";
google_ad_type = "text";
google_color_border = "A8DDA0";
google_color_bg = "EBFFED";
google_color_link = "0000CC";
google_color_url = "008000";
google_color_text = "6F6F6F";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</p>
<?

$addit = new SubmitForce;							// create object
$addit->set_mode($SF_mode);							// operating mode ONLINE or DEBUG
$addit->queue_engines($engine_file);				// Load the engine data file
$addit->init($url,$email,$user,$selected_engines);	// initialize variables
$addit->submit_page();								// submit


?>
<hr size=1 noshade>
<TABLE width=200><TR>
	<TD width=200>
		<IMG SRC="submitforce.gif" WIDTH="200" HEIGHT="40" BORDER=0 ALT="Powered by SubmitForce search engine submitter">
	</TD>
</TR>
</TABLE>
<A HREF="http://www.civiconarch.com/senselabs/web_site_tools.php">&laquo;back</A>



<a target="_top" href="http://t.extreme-dm.com/?login=senselab">
<img src="http://t1.extreme-dm.com/i.gif" height=3
border=0 width=3 alt=""></a><script language="javascript1.2"><!--
EXs=screen;EXw=EXs.width;navigator.appName!="Netscape"?
EXb=EXs.colorDepth:EXb=EXs.pixelDepth;//-->
</script><script language="javascript"><!--
var ref = '';
EXd=document;EXw?"":EXw="na";EXb?"":EXb="na";

ref = EXd.referrer;
EXd.write("<img src=\"http://t0.extreme-dm.com",
"/c.g?tag=senselab&j=y&srw="+EXw+"&srb="+EXb+"&",
"l="+escape(ref)+"\" height=1 width=1>");//-->
</script><noscript><img height=1 width=1 alt=""
src="http://t0.extreme-dm.com/c.g?tag=senselab&j=n"></noscript>



</BODY>
</HTML>