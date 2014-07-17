<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
 $templateid=addslashes($_GET['templateid']);
?>
<html>
<head>
	<title></title>
	<SCRIPT>
	<?php
	 $template="var templateid=\"$templateid\";\n";
	 echo $template;
	?>
	ver=parseInt(navigator.appVersion)
	ie4=(ver>3  && navigator.appName!="Netscape")?1:0
	ns4=(ver>3  && navigator.appName=="Netscape")?1:0
	if(ns4){
	  location.href="./resources/Mozilla/mz.php?templateid="+templateid+"&";
	}
	</SCRIPT>
</head>
<body>

</body>
</html>