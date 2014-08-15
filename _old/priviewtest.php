<?php
include "includes/config.php";
include "includes/function.php";
function showtplpriview($templateid, $templatetype, $currentloc)
{
    $qry = " select * from tbl_template_mast where ntemplate_mast='$templateid'";
    $rs = mysql_query($qry);
    $row = mysql_fetch_array($rs);
    require($currentloc . '/smarty/lib/Smarty.class.php');
    remove_dir($currentloc . '/smarty/templates_c');
    @mkdir($currentloc . "/smarty/templates_c", 0777);
    @chmod($currentloc . "/smarty/templates_c", 0777);
    $smarty = new Smarty();
    $smarty->template_dir = $currentloc . "/".$_SESSION["session_template_dir"]."/" . $templateid;
    $smarty->compile_dir = $currentloc . "/smarty/templates_c";
    $smarty->cache_dir = $currentloc . "/smarty/cache";
    $smarty->config_dir = $currentloc . "/smarty/configs";
    if ($templatetype == "index") {
        $smarty->assign('vlogoband', "tp_logoimage.jpg");
        $smarty->assign('vcompanyband', "tp_company.jpg");
        $smarty->assign('captionband', "tp_caption.jpg");
        $smarty->assign('vsite_links', $row['vlinks']);
        $smarty->assign('vsite_editable', $row['veditable']);
        $html = $smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
    } else {
        $smarty->clear_cache('index.tpl');
        $smarty->assign('vinnserlogoband', "tp_logoimage.jpg");
        $smarty->assign('vinnercompanyband', "tp_innercompany.jpg");
        $smarty->assign('innercaptionband', "tp_innercaption.jpg");
        $smarty->assign('vsite_links', $row['vsub_links']);
        $smarty->assign('vsubsite_editable', $row['vsub_editable']);
        $html = $smarty->fetch('subpage.tpl', $cache_id = null, $compile_id = null, false);
    } 
    $fp = fopen($currentloc . "/templates/$templateid/priview.htm", "w");
    fputs($fp, $html);
    fclose($fp);
} 

showtplpriview("2", "indesdsx", ".");

?>
<a href="./templates/2/priview.htm" TARGET="new">click</a>