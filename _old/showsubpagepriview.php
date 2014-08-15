<?php
include "./includes/session.php";
include "./includes/config.php";
include "./includes/function.php";
$templateid=$_GET['id'];
$qry="select t.ntemplate_mast,t.vtype,c.ncat_id,c.vcat_name,c.vcat_desc from tbl_template_mast t 
        left join tbl_template_category c on t.ncat_id=c.ncat_id where t.ntemplate_mast='".addslashes($templateid)."'";
$rs=mysql_query($qry);
$row=mysql_fetch_array($rs);

?>
<html>
<head>
	<title></title>
	<link href=<?php echo $currentloc; ?>style/<?php echo $_SESSION["session_style"];?> TYPE="text/css" REL=STYLESHEET>
	
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
			   <legend class=maintextbold>Sub page template</legend>
		       <table width="100%" border=0 align=center>
			      <tr><td>&nbsp;</td></tr> 
			      <tr>
						  <?php
						    $picsub= "showtemplateimage.php?type=sub&tmpid=".$templateid."&";
						  ?> 
						  <td align=center><img src="<?php echo $picsub;?>"</td>
						</tr>
				  <tr><td>&nbsp;</td></tr>		
			   </table>
			   </FIELDSET>
			</td>
		</tr>
		
	</table>
</body>
</html>
<?php
 //
?>