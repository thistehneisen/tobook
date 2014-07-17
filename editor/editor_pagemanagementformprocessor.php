<?php
// +----------------------------------------------------------------------+
// | Authors: jinson<jinson.m@armia.com>      		              |
// | Page to add external navigational menu elements to session						                          |
// +----------------------------------------------------------------------+

include "../includes/session.php";
include_once "../includes/config.php";
//include "../includes/globalfunctions.php";
include "../includes/sitefunctions.php";

$doc = new DOMDocument();

$templateid  = $_SESSION['siteDetails']['templateid'];
$type        = $_GET['type'];

if($type == 1) { // adding new page

    $pageTitle		= trim($_POST['txtaddpagename']);
    if($siteLanguageOption =='english'){
        $pageLinkVal        = getAlias($pageTitle,"_");
        $pageAlias          = getAlias($pageTitle);
    }else{
        $pageLinkVal  = $pageAlias      = getPageLink();
    }


    $error_flag = 0;
    
    if($pageTitle==""){
        $message = "Enter page name";
        $error_flag = 1;
    }else{ //echopre($_POST); echopre($pageAlias);
        $pageArray  = $_SESSION['siteDetails']['pages'];
        foreach($pageArray as $pageVal){ 
            if($pageAlias == $pageVal['alias'] && $_POST['txtpageid'] == ''){ 
                $message = "Page name aleady exists";
                $error_flag = 1;
            }
        }
    }
    
    
    $pageType		= $_POST['pagetype'];
    if($pageType == 'guestbook')
    	$pageLink		= $pageLinkVal.'.php'; 
    else
    	$pageLink		= $pageLinkVal.'.html'; 

    if($error_flag==0){
        // validation
        if($pageTitle != '' && $pageLink != '' ) {

            $arrNewPage 			= array();
            $arrNewPage['title'] 		= $pageTitle;
            $arrNewPage['link'] 		= $pageLink;
            $arrNewPage['pagetype']             = $pageType;

            if($_POST['txtpageaction'] == 'edit' && $_POST['txtpageid'] != '') {
                $pageid = $_POST['txtpageid'];
                $arrNewPage['alias'] = $_SESSION['siteDetails']['pages'][$pageid]['alias'] ;
                $_SESSION['siteDetails']['pages'][$pageid] = $arrNewPage;

                // Update New page data to session
                updateNewPageToSession($templateid,$pageType,$pageAlias);
        
                echo "editsuccess~".$pageTitle;
            }
            else {
                $arrNewPage['alias'] = $pageAlias;
                
                //$arrNewPage['alias'] = getAlias($pageTitle);
                
                // adding new menu item
                $rowNo = time();
                $_SESSION['siteDetails']['pages'][$rowNo] = $arrNewPage; 
                //$_SESSION['siteDetails']['currentpage'] = $pageLinkVal;
                //$_SESSION['siteDetails']['currentpage'] = $pageAlias;

                // Update New page data to session
                updateNewPageToSession($templateid,$pageType,$pageAlias);

                echo "success~".$pageTitle;
            }
        }
        
    }else{
        echo "failure~".$message;
    }

}
else if($type == 2) {
  	$page 		= $_GET['page']; 
   	$pageName 	= $_SESSION['siteDetails']['pages'][$page]['alias'];
   	unset($_SESSION['siteDetails']['pages'][$page]);
   	unset($_SESSION['siteDetails'][$page]);
	echo "Successfully deleted the page";
    exit();
}	

?>