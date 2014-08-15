<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: girish<girish@armia.com>                                  |
// |                                                                                                      // |
// +----------------------------------------------------------------------+
?>
<?php
error_reporting(0);
include "../includes/session.php";
include "../includes/config.php"; 
include "../includes/function.php"; 
include "includes/admin_functions.php"; 

//Change password feature for cloud
if(isset($_GET['changPassword']))
{
	if($_POST['newPassword'] <> $_POST['confPassword'])
	{
		echo "Password Mismatch"; exit;
	}
	if($_POST['newPassword']=='' || $_POST['confPassword']=='')
	{
		echo "Password Cannot Be Blank"; exit;
	}
	$xml = simplexml_load_file(dirname(__FILE__).'/configxml.xml');
	if($xml->secretkey==$_GET['changPassword'])
	{
		$sql=" SELECT vvalue FROM tbl_lookup WHERE vname = 'admin_pass' ";
		$result=mysql_query($sql) or die(mysql_error());
		$obj = mysql_fetch_object($result);
		if(mysql_num_rows($result) > 0 )
		{
			if($obj->vvalue == md5($_POST['oldPassword'])) {
				$sqlUpdate = "UPDATE tbl_lookup SET vvalue = '".  md5($_POST['newPassword'])."'  WHERE vname = '".mysql_real_escape_string('admin_pass')."' " ;

				$result=mysql_query($sqlUpdate) or die(mysql_error());
				if($result)
				{
					echo "Password Successfully Changed"; exit;
				}else {
					echo  "Old Password not matching"; exit;
				}
			}

		} else {
			echo  "Old Password not matching"; exit;
		}

	} else {
		echo "Authorization Failed"; exit;
	}
	exit;
}
//

?>