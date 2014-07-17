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
	<link href="<?php echo $currentloc?>style/<?php echo $_SESSION["session_style"];?>" TYPE="text/css" REL=STYLESHEET>
	<script>
	<?php
	$templatetype=$row['vtype'];
	$tmp="var tid=\"$templateid\";\n";
	$tmp .="var ttype=\"$templatetype\";\n";
	//$tmp .="var tid=\"$templateid\";\n";
	echo $tmp;
	?>
	function clickselect(){
	 opener.selecttemplate_1(tid,ttype);
	 window.close();
	}
	function clickclose(){
	 
	 window.close();
	}
	</script>
</head>
<body>
    <table width=800 align=center>
	     <tr>
			 <td align=center>
			 <a href="javascript:void(0)" onclick="clickselect()" class=anchor><img border=0 src="./images/select.gif"></a>&nbsp;&nbsp;
			 <a href="javascript:void(0)" onclick="clickclose()" class=anchor><img border=0 src="./images/close.gif"></a>
			 </td>
		 </tr> 
	     <tr>
		   <td>
		       <FIELDSET>
			   <legend class=maintextbold>Template description</legend>
		       <table width="60%" border=0 align=center>
			      <tr>
				    <td class=maintext width="30%">Template Category</td>
					<td class=maintext><?php echo $row['vcat_name']; ?></td>
				  </tr>
				  <tr>
				    <td class=maintext width="30%">Category description</td>
					<td class=maintext><?php echo $row['vcat_desc']; ?></td>
				  </tr>
				  <tr>
				    <td class=maintext width="30%">Template type</td>
					<td class=maintext><?php echo $row['vtype']; ?></td>
				  </tr>
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
		<tr>
		    <td>
			   <FIELDSET>
			   <legend class=maintextbold>Home page</legend>
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
		<tr>
		    <td>
			   <FIELDSET>
			   <legend class=maintextbold>Sub page</legend>
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
		<tr>
			 <td align=center>
			  <a href="javascript:void(0)" onclick="clickselect()" class=anchor><img border=0 src="./images/select.gif"></a>&nbsp;&nbsp;
			 <a href="javascript:void(0)" onclick="clickclose()" class=anchor><img border=0 src="./images/close.gif"></a>
			 </td>
		 </tr> 
	</table>
</body>
</html>
<?php
 //
?>