<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 5                                                      |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 2                 |
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+


include "../includes/session.php";

/*
	page to check the order of the panels
*/
$currentPage 		= $_SESSION['siteDetails']['currentpage'];
if($_GET['templateid'] != '' && $_GET['themeid'] != '') {

    // iterating the panels
    $panelArray	= array();
    foreach ($_GET as $position => $item) {
        if($position){
            $arrPos = explode('_',$position);
            if(isset($arrPos) && isset($arrPos[1])){
                if(is_numeric($arrPos[1])) {
                    if($arrPos[0] == 'column') { // check whether its a column or not

                        $panelsArr 	= explode(',',$item);
                        foreach($panelsArr as $panels) {

                            $panel = explode('_',$panels);

                            if($panel[0] == 'item') {	// our existing panels
                                $panelArray[$arrPos[1]][] = $panel[1];
                            }
                            elseif($panel[0] == 'exterbox') { // external boxes
                                $panelArray[$arrPos[1]][] = $panels;
                            }
                        }

                    }
                }
            }
        }
    }

    //echo 'Panel Array : <pre>';print_r($panelArray);echo '</pre>';
    $_SESSION['siteDetails'][$currentPage]['panelpositions'] = $panelArray;

}

?>