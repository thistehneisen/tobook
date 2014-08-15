<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com> 		       		              |
// +----------------------------------------------------------------------+
//include files
$curTab = 'template_manager';
include "../includes/session.php";
include "../includes/config.php";
include "../includes/function.php";
include "includes/pclzip.lib.php";
include "./includes/adminheader.php";

ini_set('upload_max_filesize', '10M');
ini_set('post_max_size', '10M');

$error 		= false;
$message 	= "";
$rootDirectory=getSettingsValue('rootserver');

if($_POST["postback"] == "step1") {
    
    if(is_uploaded_file($_FILES["tempzip"]["tmp_name"]) &&  getFileExtension($_FILES["tempzip"]["name"]) == "zip") {
        $archive = new PclZip($_FILES["tempzip"]["tmp_name"]);
        $tempZipName = getFileName($_FILES["tempzip"]["name"]);

        if($_POST['txttemplatetype']=='J') {
            $themDir = '../uploads/themes/'.$tempZipName;
            //$themDir = '../uploads/themes';
        }
        else {
            $themDir = '../uploads/themes';
        }

        if ($archive->extract(PCLZIP_OPT_PATH,$themDir) == 0) {
            //die("Error : ".$archive->errorInfo(true));
            // echo "There is some issue with the uploaded file. Please try again.";
            $message =  '<font color="red">'.MSG_FILE_ISSUE.'</font>';
        }
        // successfully uploaded the template.

        $tempUploadPath = '../uploads/themes/'.$tempZipName;
        $templatePath	= '../'. $_SESSION["session_template_dir"] .'/'.$tempZipName;

        $templateSettingsXML = $tempUploadPath.'/templateDetails.xml';

        if($_POST['txttemplatetype']=='J') {
            // move the template to actual folder
            rcopy($tempUploadPath, $templatePath);
            $it = new RecursiveDirectoryIterator($templatePath);
            foreach(new RecursiveIteratorIterator($it) as $file) {

                if(strpos($file , "templateDetails.xml")) {
                    $folder=str_replace("templateDetails.xml","",$file);
                    copydirr($folder, $templatePath);



                }
            }
            // code to insert the themplate to table
            $tempCategory = $_POST['cmbCategory'];
            $tempName = $_POST['txttemplatename'];
            $tempType   =  $_POST['txttemplatetype'];
            $sql =  "insert into tbl_template_mast(ncat_id,temp_name,vthumpnail,vtype,ddate,ntemplate_type)
                            Values('" . $tempCategory . "','".$tempName."',
                            'thumpnail.jpg',
                            'advanced',
                            now(),'J')";
            mysql_query($sql) or die(mysql_error());
            $var_insert_id = mysql_insert_id();
            include "joomlatotpl.php";
            $Html2Tpl	= joomlaToTemplate($templatePath.'/index.php',$templatePath.'/templateDetails.xml',$var_insert_id,$templatePath);
            //print_r($Html2Tpl);exit;
            if($Html2Tpl['result'] == 'success') {
                // add the themes to database
                $sql =  "insert into  tbl_template_themes(temp_id,theme_name,theme_style,theme_image_thumb,theme_image_home,theme_image_sub,theme_status)
                                                        values(".$var_insert_id.",'".$themeDet['name']."','".$themeDet['style']."','template_thumbnail.png','template_thumbnail.png','template_thumbnail.png',1)";
                mysql_query($sql) or die(mysql_error());
//                foreach($xml->themes as $tempThemes){
//                        foreach($tempThemes as $themes){
//                                $themeDet = (array)$themes;
//                                $sql =  "insert into  tbl_template_themes(temp_id,theme_name,theme_style,theme_image_thumb,theme_image_home,theme_image_sub,theme_status) 
//                                                        values(".$var_insert_id.",'".$themeDet['name']."','".$themeDet['style']."','".$themeDet['thumbnail']."','".$themeDet['homepageimage']."','".$themeDet['subpageimage']."',1)";
//                                mysql_query($sql) or die(mysql_error());	
//
//                        }
//                }	
                // theme adding ends
                //-------------Copy Joomla styles to the corresponding folder----------
                $sourceCssPath = '../style/joomla/system.css';
                $destinationCssPath = $templatePath.'/css/system.css';
                copy($sourceCssPath, $destinationCssPath);
                $sourceCssPath = '../style/joomla/general.css';
                $destinationCssPath = $templatePath.'/css/general.css';
                copy($sourceCssPath, $destinationCssPath);

                @rename($templatePath,"../" . $_SESSION["session_template_dir"] . "/" . $var_insert_id);

                $message = "<font color='green'>".MSG_TEMP_UPLOAD_SUCC."</font>";
            }
            else {
                $message = $Html2Tpl['message'];
            }
        }
        else {

            //echo $templateSettingsXML;
            $xml = @simplexml_load_file($templateSettingsXML);
            //echopre($xml);
            $tempRet = templateVaildation($xml,$tempUploadPath);
            //echopre1($tempRet);
            if($tempRet['status'] == 'success') {	// template validation success
                // $message =  "validation success";

                // move the template to actual folder
                rcopy($tempUploadPath, $templatePath);
                $panels = $xml->panels;

                // code to insert the themplate to table
                $tempCategory = $_POST['cmbCategory'];

                $tempName = $_POST['txttemplatename'];
                $tempType   =  $_POST['txttemplatetype'];



                $sql =  "insert into tbl_template_mast(ncat_id,temp_name,vthumpnail,vtype,ddate)
                                    Values('" . $tempCategory . "','".$tempName."',
                                    'thumpnail.jpg',
                                    'advanced',
                                    now())";
                mysql_query($sql) or die(mysql_error());
                $var_insert_id = mysql_insert_id();
                // template insertion ends

                $pages = $xml->files;
                $pages = (array)$pages;
                include "htmltotpl.php";
                $Html2Tpl	= convertHtmlToTemplate($templatePath,$var_insert_id,"",$panels,$pages);

                // echopre($Html2Tpl);
                if($Html2Tpl['result'] == 'success') {


                    // add the themes to database
                    foreach($xml->themes as $tempThemes) {
                        foreach($tempThemes as $themes) {
                            $themeDet = (array)$themes;

                            $defaultVal = 0;
                            if($themeDet['currenttheme'] == 'true')  $defaultVal = 1;

                            $sql =  "insert into  tbl_template_themes(temp_id,theme_name,theme_style,theme_color,theme_image_thumb,theme_image_home,theme_image_sub,theme_status,theme_default)
                                                            values(".$var_insert_id.",'".$themeDet['name']."','".$themeDet['style']."','".$themeDet['themecolor']."','".$themeDet['thumbnail']."','".$themeDet['homepageimage']."','".$themeDet['subpageimage']."',1,".$defaultVal. ")";
                            mysql_query($sql) or die(mysql_error());

                        }
                    }
                    // theme adding ends



                    @rename($templatePath,"../" . $_SESSION["session_template_dir"] . "/" . $var_insert_id);
                    $message = "<font color='green'>".MSG_TEMP_UPLOAD_SUCC."</font>";
                }
                else
                    $message = $Html2Tpl;
            }
            else
                $message =  $tempRet['message'];
        }

    }
    else {
        $message =  '<font color="red">'.MSG_INVALID_TEMP.' </font>';
    }
}

?>
<script language="javascript" src="../js/jquery.js"></script>
<script>
    $(document).ready(function(){
        $('.jtemplatetype').click(function(){
            var templatetype    =   $(this).attr('value');
            if(templatetype =='J'){
                $('#jdisplaysamepledownload').hide();
            }
            else{
                $('#jdisplaysamepledownload').show();
            }

        })
    });
    <!--
    var id=0;
<?php
if($_SESSION["session_steps"] >= 1) {
    echo("id=1;");
}
?>
    function clickClear() {
        var frmStep1 = document.frmStep1;
        frmStep1.postback.value="clearold";
        frmStep1.action="templadv1.php";
        frmStep1.method="post";
        frmStep1.submit();
    }
    function clickNext() {
        var frmStep1 = document.frmStep1;
        if(frmStep1.txttemplatename.value==""){
            alert("<?php echo VALMSG_TEMP_NAME;?>");
            frmStep1.txttemplatename.focus();
            return;
        }
        var fileImage = frmStep1.tempzip;
        if(fileImage.value.length <= 0 && id == 0) {
            alert("<?php echo MSG_SELECT_ZIP;?>");
            return;
        }
        frmStep1.postback.value="step1";
        frmStep1.action="addtemplate.php";
        frmStep1.method="post";
        frmStep1.submit();
    }
    -->
</script>
<?php
$linkArray = array( TEMP_MANAGER     =>'admin/templatemanager.php',
        ADD_NEW_TEMP  =>'admin/addtemplate.php');
?>

<div class="admin-pnl">

    <?php echo getBreadCrumb($linkArray); ?>
    <h2><?php echo ADD_NEW_TEMP;?></h2>



    <table  cellpadding="0" cellspacing="0" class="commntbl_style1"><tr>
            <td align=center>
                <form name="frmStep1" method="post" action="" enctype="multipart/form-data">
                    <table border=0 width="100%"  align="left">


                        <tr>
                            <td align=center colspan=3 class=maintext>&nbsp;<font color='red'><?php echo $message;?></font></td>
                        </tr>

                        <tr>
                            <td width="300" align=left class=maintext><?php echo SELECT_CATEGORY_TEMP;?> <font color=red><sup>*</sup></font></td>
                            <td width="6"></td>
                            <td width="369" align=left><input type="hidden" name="postback" id="postback" value="">
                                <select name="cmbCategory" class="selectbox2" >
                                    <?php

                                    $sql = "Select ncat_id,vcat_name from tbl_template_category";
                                    $result = mysql_query($sql) or die(mysql_error());
                                    if(mysql_num_rows($result) > 0) {
                                        while($row = mysql_fetch_array($result)) {
                                            echo("<option value=\"". $row["ncat_id"] . "\"" . (($_SESSION["session_advCatId"] == $row["ncat_id"])?"Selected":"") . ">" . $row["vcat_name"] . "</option>");
                                        }
                                    }
                                    ?>
                                </select>


                            </td>
                        </tr>

                        <tr>
                            <td align=left class=maintext><?php echo TEMP_NAME;?> <font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left>
                                <input type="text" name="txttemplatename" /></td>
                        </tr>

                        <tr>
                            <td align=left class=maintext><?php echo TEMP_TYPE;?> <font color=red><sup>*</sup></font></td>
                            <td></td>
                            <td align=left>
                                <input type="radio" name="txttemplatetype" class="jtemplatetype" value="J" /><?php echo JOOMLA;?> <input type="radio" name="txttemplatetype" class="jtemplatetype" value="N" checked /><?php echo NORMAL;?></td>
                        </tr>
                        <tr>
                            <td align=left class=maintext><?php echo ZIP_FILE_FOR_TEMP;?><font color=red><sup>*</sup></font><br /><br />
                                <a id="jdisplaysamepledownload" href="<?php echo $rootDirectory?>/uploads/easycreate_sample_template.zip"><?php echo DOWNLOAD_SAMPLE;?></a>  </td>
                            <td></td>
                            <td align=left><input type="file" name="tempzip" id="tempzip" onKeyPress=""  class=textbox  size="23"></td>
                        </tr>



                        <tr>
                            <td  colspan=3 align=center>
                                <?php
                                if($_SESSION["session_advTemplateDir"] != "") {
                                    echo("<img id='imgThumpnail' src=\"../images/spacer.jpg\">");
                                    $script_string = "<script>document.all(\"imgThumpnail\").src=\"" . $_SESSION["session_advTemplateDir"] . "/thumpnail.jpg\";</script>";
                                    $button_string = "<input type=\"button\" onClick=\"javascript:clickClear();\" value=\"Clear old data\"  class=\"editorbutton\" >";
                                }
                                ?></td>
                        </tr>
                        <tr>
                            <td  colspan=3 align=right class=""><input type="button" onClick="javascript:clickNext();" value="<?php echo BTN_ADD;?>"  class="button btn01" > &nbsp;<?php echo($button_string); ?></td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                        <tr>
                            <td colspan="3">&nbsp;</td>
                        </tr>
                    </table>

                </form>
            </td>
        </tr></table>
</div>
<?php
echo($script_string); 
?>
<?php

include "includes/adminfooter.php";
?>