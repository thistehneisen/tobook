<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: mahesh<mahesh.s@armia.com>              		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
//include files
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

//include "includes/subpageheader.php";
?>
<?php
if($_SESSION["session_style"]==""){
	$_SESSION["session_style"]="site.css";
}
//include "includes/applicationheader.php";
if(!isset($_SESSION["session_lookupsitename"]) || $_SESSION["session_lookupsitename"] == "") {
	$sql = "Select vname,vvalue from tbl_lookup where vname IN('site_name','admin_mail','Logourl','rootserver','secureserver','template_dir') Order by vname ASC";
	$result=mysql_query($sql) or die(mysql_error());
	if(mysql_num_rows($result) >0) {
		while($row = mysql_fetch_array($result)) {
			switch($row["vname"]) {
				case "site_name":
					$_SESSION["session_lookupsitename"] = $row["vvalue"];
					break;
				case "Logourl":
					$_SESSION["session_logourl"] = $row["vvalue"];
					break;
				case "admin_mail":
					$_SESSION["session_lookupadminemail"] = $row["vvalue"];
					break;
				case "rootserver":
					$_SESSION["session_rootserver"] = $row["vvalue"];
					break;
				case "secureserver":
					$_SESSION["session_secureserver"] = $row["vvalue"];
					break;
				case "template_dir":
					$_SESSION["session_template_dir"] = $row["vvalue"];
					break;
			}
		}
	}
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<TITLE><?php echo($_SESSION["session_lookupsitename"]); ?> - The do it yourself online website builder</TITLE>
<META name="description" content="<?php echo($_SESSION["session_lookupsitename"]); ?> will let you build your own websites online using our large collection of graphically intensive templates and template editors.Build a web site in six easy steps.">
<META name="keywords" content="online website builder,website building software,web design software,site creation tool,build a website,website builder,site builder,free web site builder,create a website,web building software,website building,build a website,create a website,web site templates,easy website creator, easy website builder,best website builder,website maker,free website maker">
<META HTTP-EQUIV="PRAGMA" CONTENT="NO-CACHE">
<link href=<?php echo $currentloc; ?>style/<?php echo $_SESSION["session_style"];?> type="text/css" rel="stylesheet">
<script language="javascript1.1" type="text/javascript">
history.forward();
</script>
<link href="favicon.ico" type="image/x-icon" rel="icon"> 
<link href="favicon.ico" type="image/x-icon" rel="shortcut icon">
</head>

<body  topmargin="0">
<table width="802" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#05B5FE">
<tr>
<td align="center">
	
	
		<table width="800" border="0" align="center" cellpadding="0" cellspacing="0">
      	<tr>
        <td>
		
		
				<table width="100%"  border="0" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
          		<tr>
            	<td>
			
			
						<table width="100%"  border="0" cellspacing="0" cellpadding="0">
              			<tr>
                		<td>
				
				
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    			<tr>
                      			<td class=headerclass  width="800" height="84" border="0" align=left valign=middle ><a href="index.php"><img src="xxx.gif" width=15 height=0 border=0><img src ="<?php echo($_SESSION["session_logourl"]); ?>" border=0></a></td>
                    			</tr>
                  				</table>
                    
								<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                      			<tr>
                        		<td>
						
						
										<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                            			<tr>
                              			<td><img src="images/headerrestredbar.gif" width="800" height="6"></td>
                            			</tr>
                          				</table>
                           
						    			<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                              			<tr>
                                		<td width="73%" align="right">
								
								
												<table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    			<tr>
                                      			<td width="67%">&nbsp;</td>
                                      			<td width="33%" align="left" class="toplinks">&nbsp;</td>
                                    			</tr>
                                				</table>
												
										</td>
                                		<td width="27%"><img src="images/headerrestredbarrgt.gif" width="220" height="43"></td>
                              			</tr>
                          				</table>
										
								</td>
                      			</tr>
                    			</table>
								
								
						</td>
              			</tr>
            			</table>
						
				</td>
          		</tr>
        		</table>
        
		
		  		<table width="100%"  border="0" cellspacing="0" cellpadding="0">
					<tr bgcolor="#FFFFFF"><td>&nbsp;</td></tr>
					<tr>
    	            	<td height="146" valign="top" bgcolor="#FFFFFF" align="center">
							<p align=center>
								<b>Invalid License Key.<br><br> Please contact <?php echo $_SESSION["session_lookupadminemail"];?></b>							
							</p>						
						</td>			
							<table width=100% cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
								<tr>
									<td align="center" valign="top">	
		                              <?php include "includes/footer.php"; ?>
		  