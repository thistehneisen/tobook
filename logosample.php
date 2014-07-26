<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>                                |
// |                                                                      |
// +----------------------------------------------------------------------+

/* By using this page user can select the logo sample provided by us.
*  on selecting the image the image will be copied from samlelogos to workarea location
*  the selected image will return to parent window.
* 
*/ 
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

$userid = $_SESSION["session_userid"];
$urllocation = EDITOR_USER_IMAGES;
$siteId = ($_SESSION['siteDetails']['siteId'])?$_SESSION['siteDetails']['siteId']:'0';

if ($_POST['selectedimage'] != "") {
    $filename = $_POST['selectedimage'];
    //$imagewidth_height_type_array = explode(":", ImageType($filename));
    //$imagetype = $imagewidth_height_type_array[0];
    $assignedname = $filename;
    //set the 'session variable 'logoname' to $assignedname.parent window will use this varible
    $_SESSION['logoname'] = $assignedname;
    $_SESSION['siteDetails']['siteInfo']['logoName'] = BASE_URL.'uploads/siteimages/'.$assignedname;
    copy("./samplelogos/" . $filename, $urllocation . $assignedname);
    chmod($urllocation . $assignedname, 0777);
    //for published site watermarking is not required
    
    //echo $urllocation.$assignedname;
    echo "<script>opener.insertourlogo('" . $urllocation . $assignedname . "','".$siteId."');window.close();</script>";
} 
$sql = "select * from tbl_logo ";
$rs = mysql_query($sql);
include "./includes/applicationheader.php";
?>
<script>
    function selectthisimags(id){
        document.getElementById("selectedimage").value=id;
        document.frmInsertLogo.submit();
    }
</script>
</head>
<body>
    <table width="82%"  border="0" cellspacing="0" cellpadding="0" align=center>
        <tr>
            <td  valign="top" align=center>
                <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                    <tr>
                        <td>
                            <!-- Main section starts here-->
                            <form name=frmInsertLogo method=post>
                                <table width="80%" border=0>
                                    <?php
                                    if(mysql_num_rows($rs)>0) {
                                        $i=0;
                                        while($row=mysql_fetch_array($rs)) {
                                            if($i==0) {
                                                echo "<tr>";
                                            }
                                            if($i==4) {
                                                $i=0;
                                                echo "</tr><tr>";
                                            }

                                            ?>

                                    <td width="50%" align=center>
                                        <img id="<?php echo $row['vlogo_url']; ?>" src="samplelogos/<?php echo $row['vlogo_url']; ?>" onClick="selectthisimags(this.id)">
                                    </td>




                                            <?php
                                            $i++;
                                        }
                                        if($i<=3) {
                                            echo "<td >&nbsp;</td></tr>";
                                        }else if($i==4) {
                                            echo "</tr>";
                                        }

                                    }else {
                                        ?>
                                    <tr><td class=maintext>No Logos found</td></tr>
                                        <?php
                                    }
                                    ?>
                                    <input type=hidden name=selectedimage id=selectedimage value="">
                                    <tr><td colspan="2" align=center>&nbsp;</td></tr>
                                    <tr><td colspan="4" align=center><input type="button" class=button name=btnclose value="Close" onClick="window.close();"> </td></tr>
                                </table>
                            </form>
                            <!-- Main section ends here-->
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>

</body>
<html>