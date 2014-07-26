<?php
include "./includes/session.php";
include "./includes/config.php";
include "./includes/function.php";
$templateid=$_GET['id'];
$qry="select t.ntemplate_mast,t.vtype from tbl_template_mast t 
         where t.ntemplate_mast='".addslashes($templateid)."'";
$rs=mysql_query($qry);
$row=mysql_fetch_array($rs);

?>
<html>
<head>
	<title></title>
	<link href=<?php echo $currentloc?>style/<?php echo $_SESSION["session_style"];?> TYPE="text/css" REL=STYLESHEET>
	
	</script>
</head>
<body>
    <table width=800 align=center>
	     
	     
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		    <td>
			   <FIELDSET>
			   <legend class=maintextbold>Home page template</legend>
		       <table width="100%" border=0 align=center>
			      <tr><td>&nbsp;</td></tr>
			      <tr>
						  <?php
						    $pichome= "showtemplateimage.php?type=home&tmpid=".$templateid."&";
						  ?> 
						  <td align=center><img src="<?php echo $pichome;?>"></td>
						</tr>
				  <tr><td>&nbsp;</td></tr>		
			   </table>
			   </FIELDSET>
			</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		<tr>
		  <td>&nbsp;</td>
		</tr>
		
		
	</table>
</body>
</html>
<?php
 //
?>