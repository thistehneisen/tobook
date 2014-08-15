<?php 
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2007 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: programmer<programmer@armia.com>                            |
// |                                                                      |
// +----------------------------------------------------------------------+

$glb_dbhost_1="localhost";
$glb_dbuser_1="root";
$glb_dbpass_1="status";
$glb_dbname_1="dbswhereswanda";
 
/*
$glb_dbhost_1 = "localhost";
$glb_dbuser_1 = "root";
$glb_dbpass_1 = "status";
$glb_dbname_1 = "supportdesk3";
*/

$conapi = mysql_connect($glb_dbhost_1,$glb_dbuser_1,$glb_dbpass_1) or die(mysql_error());
mysql_select_db($glb_dbname_1,$conapi) or die(mysql_error());

function userAdd($loginnameapi, $passwordapi, $firstnameapi, $emailapi){

         global $conapi;

         if($loginnameapi != "" && $passwordapi != "" && $firstnameapi != "" && $emailapi != ""){

            $sqlapi = "Select * from tbl_user_mast where vuser_login = '".addslashes($loginnameapi)."'";
            $resultapi = mysql_query($sqlapi,$conapi);

            if(mysql_num_rows($resultapi) == 0){
                  $sqlapi  = " INSERT INTO tbl_user_mast(`nuser_id`, `vuser_login`, `vuser_password`, `vuser_name`, `vuser_email`, `duser_join`, `vuser_style`, `naff_id`, `vdel_status`) ";
                  $sqlapi .= " VALUES('', '".addslashes($loginnameapi)."', '".md5(addslashes($passwordapi))."', '".addslashes($firstnameapi)."', '".addslashes($emailapi)."', now(), 'site.css', '0', '0')";
                  $resultapi = mysql_query($sqlapi,$conapi);
            }

         }

}
?>