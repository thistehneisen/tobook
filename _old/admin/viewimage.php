<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: jimmy<jimmy.jos@armia.com>	        		              |
// +----------------------------------------------------------------------+
//called from step-IV of template addition
//Helps to view all images inside a given location
//It reads the $_GET["loc"] and displays the images.
  include "../includes/session.php";
  include "../includes/config.php";
  include "../includes/function.php";
  $loc = $_GET["loc"];
?>
<html>
<head>
	<title>:: <?php echo($_SESSION["session_lookupsitename"]); ?> - View images ::</title>
	<link href="../style/site.css" rel="stylesheet" type="text/css">
	<SCRIPT>
	window.name="targ";
	
	 function closeGallery(){
	 		returnValue = new Object();
			returnValue.url = ""; 
	        window.close();
	  }
	  function savetblpro(){
			document.frmImageUpload.submit();
	  }
	
	  function selectthisimags(id){
	  	 return;
	  }	
	</SCRIPT>
</head>
<body>
<table width="100%" cellpadding=2 align=center>
   <tr>
      <td>
	                  <FIELDSET>
					      <legend class=maintextbold>Images present in the images folder of the template</legend>
						    <table>
							<tr>
								<td align="center">&nbsp;
									
								</td>
								<td>&nbsp;
									
								</td>
							</tr>
							 <form  method="post"   name="frmImageUpload" target="targ">
							  <input type="hidden" name="type" id="type" value="<?php echo($var_type); ?>">
						     <?php
							   $loc .= "/";
							   $handle = opendir($loc);
							   while (false !== ($file = readdir($handle))) {
							   //echo $file;
							    if ($file != "." && $file != ".." && $file !="Thumbs.db" ) {
							 ?>
							     <tr>
								   <td>
								     <img id="<?php echo $file?>" src="<?php echo $loc . $file?>" width=100 height=100 onclick="selectthisimags(this.id)" border="1">
								   </td>
								   <td>
								   		<?php
											echo($file);
										?>	
								   </td>
								 </tr>
							 <?php	  
								}
							   }	
							 ?>
						    
							      
							      
								 <input type=hidden name=selectedimage id=selectedimage value="<?php echo($url); ?>">
								  </form>
							  </table>
						  
						  
					  </FIELDSET>
	  </td>
	</tr>
</table>	  				  	  

</body>
</html>