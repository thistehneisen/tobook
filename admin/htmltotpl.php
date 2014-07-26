<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 1.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
include_once "../includes/function.php";
// include "includes/adminheader.php";
/* To check any errors in html file
*  $templateid ->location to the newly created template
*  $message ->message
*  on success the function will return otherwise function will return false and error will be set in message
*/
function HtmlToTplCheckforErros($templateid,$relativepathtoroot,&$message) {
    $htmlfile="$templateid/index.htm";
    if(!is_file($htmlfile)) {
        $message="index.htm file doesnot exist";
        return false;
    }
    $htmlcontents=file_get_contents($htmlfile);
    /* check <head> and </head> in html file*/
    $headposition=strpos($htmlcontents,"<head>");
    if(!$headposition) {
        $message="Unable to locate &lthead> in your html file";
        return false;
    }
    $backslashheadposition=strpos($htmlcontents,"</head>");
    if(!$backslashheadposition) {
        $message="Unable to locate &lt/head> in your html file";
        return false;
    }

    /* check for logoimage,company image,caption image */
    $logoposition=strpos($htmlcontents,"tp_logoimage.jpg");
    if(!$logoposition) {
        $message="Unable to locate tp_logoimage.jpg in your html file";
        return false;
    }
    $compposition=strpos($htmlcontents,"tp_company.jpg");
    if(!$compposition) {
        $message="Unable to locate tp_company.jpg in your html file";
        return false;
    }
    $captionposition=strpos($htmlcontents,"tp_caption.jpg");
    if(!$captionposition) {
        $message="Unable to locate tp_caption.jpg in your html file";
        return false;
    }
    /* check for link area*/
    $startlinkposition=strpos($htmlcontents,"start linkarea");
    if(!$startlinkposition) {
        $message="Unable to locate 'start linkarea' in your html file";
        return false;
    }
    $endlinkposition=strpos($htmlcontents,"end linkarea");
    if(!$endlinkposition) {
        $message="Unable to locate 'end linkarea' in your html file";
        return false;
    }
    //check for editable area
    $starteditableareaposition=strpos($htmlcontents,"start editablearea");
    if(!$starteditableareaposition) {
        $message="Unable to locate 'start editablearea' in your html file";
        return false;
    }
    $endeditableareaposition=strpos($htmlcontents,"end editablearea");
    if(!$endeditableareaposition) {
        $message="Unable to locate 'end editablearea' in your html file";
        return false;
    }
    /* the above checking for sub.htm file*/
    $htmlfile="$templateid/sub.htm";
    if(!is_file($htmlfile)) {
        $message="sub.htm file doesnot exist";
        return false;
    }
    $htmlcontents=file_get_contents($htmlfile);
    $headposition=strpos($htmlcontents,"<head>");
    if(!$headposition) {
        $message="Unable to locate &lthead> in your html file";
        return false;
    }
    $backslashheadposition=strpos($htmlcontents,"</head>");
    if(!$backslashheadposition) {
        $message="Unable to locate &lt/head> in your html file";
        return false;
    }
    $logoposition=strpos($htmlcontents,"tp_innerlogoimage.jpg");
    if(!$logoposition) {
        $message="Unable to locate tp_innerlogoimage.jpg in your html file";
        return false;
    }
    $compposition=strpos($htmlcontents,"tp_innercompany.jpg");
    if(!$compposition) {
        $message="Unable to locate tp_innercompany.jpg in your html file";
        return false;
    }
    $captionposition=strpos($htmlcontents,"tp_innercaption.jpg");
    if(!$captionposition) {
        $message="Unable to locate tp_innercaption.jpg in your html file";
        return false;
    }
    $startlinkposition=strpos($htmlcontents,"start linkarea");
    if(!$startlinkposition) {
        $message="Unable to locate 'start linkarea' in your html file";
        return false;
    }
    $endlinkposition=strpos($htmlcontents,"end linkarea");
    if(!$endlinkposition) {
        $message="Unable to locate 'end linkarea' in your html file";
        return false;
    }
    //check for editable area
    $starteditableareaposition=strpos($htmlcontents,"start editablearea");
    if(!$starteditableareaposition) {
        $message="Unable to locate 'start editablearea' in your html file";
        return false;
    }
    $endeditableareaposition=strpos($htmlcontents,"end editablearea");
    if(!$endeditableareaposition) {
        $message="Unable to locate 'end editablearea' in your html file";
        return false;
    }
    return true;



}
/* convert html files index.htm,sub.htm to tpl files
*  $templateid ->template location(ie location to index.htm nd sub.htm)
*  $originaltemplateid -> the template id in table template master
*  The function will create index.tpl,subpage.tpl
*/ 
function HtmlToTpl($templateid,$originaltemplateid,$relativepathtoroot,$panels = array()) {

    /***************************************** convert index.htm**************************************************/
    $htmlfile="$templateid/index.htm";
    if(!is_file($htmlfile)) {
        $errormessage="index.htm file doesnot exist";
        return $errormessage;
    }


    $htmlcontents=file_get_contents($htmlfile);

    $htmlPageContents = $htmlcontents;
    $headposition=strpos($htmlcontents,"<head>");
    if(!$headposition) {
        $errormessage="Unable to locate &lthead> in your html file";
        return $errormessage;
    }
    $backslashheadposition=strpos($htmlcontents,"</head>");
    if(!$backslashheadposition) {
        $errormessage="Unable to locate &lt/head> in your html file";
        return $errormessage;
    }
    /* fetch the <head> elements upto </head>(excluding </head>) to $headelements*/
    $headelements=substr($htmlcontents, $headposition,$backslashheadposition-$headposition+7);
    $newheadcontent="<head><META NAME=\"generator\" CONTENT=\"easycreate.com Site Builder\">";
    $newheadcontent .="<meta name=\"copyright\" content=\"Copyright (C) www.easycreate.com,www.armia.com. All rights reserved.\"><meta name=\"keywords\" content=\"{\$vsite_metakey}\">";
    $newheadcontent .="<meta name=\"description\" content=\"{\$vsite_metadesc}\">";
    $newheadcontent .="<title>{\$vsite_title}</title>";
    $newheadcontent .="<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
    $newheadcontent .="<style>{\$vsitecolor};</style></head>";
    /* replace the <head>..... elements  $newheadcontent*/
    $htmlcontents=str_replace($headelements,$newheadcontent,$htmlcontents );

    /*replace the logoimage,companyimage,captionimage with appropriate tpl varibales*/
    $logoposition=strpos($htmlcontents,"tp_logoimage.jpg");
    if(!$logoposition) {
        $errormessage="Unable to locate tp_logoimage.jpg in your html file";
        return $errormessage;
    }
    $htmlcontents=str_replace("tp_logoimage.jpg","{\$vlogoband} ",$htmlcontents );
    //check for company
    $compposition=strpos($htmlcontents,"tp_company.jpg");
    if(!$compposition) {
        $errormessage="Unable to locate tp_company.jpg in your html file";
        return $errormessage;
    }
    $htmlcontents=str_replace("tp_company.jpg","{\$vcompanyband} ",$htmlcontents );
    //check for caption
    $captionposition=strpos($htmlcontents,"tp_caption.jpg");
    if(!$captionposition) {
        $errormessage="Unable to locate tp_caption.jpg in your html file";
        return $errormessage;
    }
    $htmlcontents=str_replace("tp_caption.jpg","{\$captionband} ",$htmlcontents );
    //check for links

    $startlinkposition=strpos($htmlcontents,"start linkarea");
    if(!$startlinkposition) {
        $errormessage="Unable to locate 'start linkarea' in your html file";
        return $errormessage;
    }
    $endlinkposition=strpos($htmlcontents,"end linkarea");
    if(!$endlinkposition) {
        $errormessage="Unable to locate 'end linkarea' in your html file";
        return $errormessage;
    }
    /* fetch the link within linkarea
							*   eg
							*      <!-- start linkarea --> about us / contact / service / faq / terms / sitemap <!-- end linkarea -->
							*      $linkelements ->start linkarea --> about us / contact / service / faq / terms / sitemap <!--
							*      $linkelemntswithouthtmlcomment->about us / contact / service / faq / terms / sitemap
    */
    $linkelements=substr($htmlcontents, $startlinkposition,$endlinkposition-$startlinkposition);

    $linkelemntswithouthtmlcomment=substr($linkelements, strpos($linkelements,"-->")+3,strpos($linkelements,"<!--")-strpos($linkelements,"-->")-3);
    $newlinkcontent="{\$vsite_links}";
    /* replace the linkarea with tpl variable*/
    $htmlcontents=str_replace($linkelemntswithouthtmlcomment,$newlinkcontent,$htmlcontents );
    //check for editable area
    $starteditableareaposition=strpos($htmlcontents,"start editablearea");
    if(!$starteditableareaposition) {
        $errormessage="Unable to locate 'start editablearea' in your html file";
        return $errormessage;
    }
    $endeditableareaposition=strpos($htmlcontents,"end editablearea");
    if(!$endeditableareaposition) {
        $errormessage="Unable to locate 'end editablearea' in your html file";
        return $errormessage;
    }
    /* fetch the editable area from html file.*/
    $editableelements=substr($htmlcontents, $starteditableareaposition,$endeditableareaposition-$starteditableareaposition);
    $editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
    $neweditcontent="{\$vsite_editable}";
    $htmlcontents=str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlcontents );

    $vlinks=addslashes($linkelemntswithouthtmlcomment);
    $veditable=addslashes($editableelemntswithouthtmlcomment);
    $indexhtmlcontents=$htmlcontents;

    /******************************************* convert sub.htm*****************************************************/
    $htmlfile="$templateid/sub.htm";
    if(!is_file($htmlfile)) {
        $errormessage="sub.htm file doesnot exist";
        return $errormessage;
    }
    $htmlcontents=file_get_contents($htmlfile);
    $headposition=strpos($htmlcontents,"<head>");
    if(!$headposition) {
        $errormessage="Unable to locate &lthead> in your html file";
        return $errormessage;
    }
    $backslashheadposition=strpos($htmlcontents,"</head>");
    if(!$backslashheadposition) {
        $errormessage="Unable to locate &lt/head> in your html file";
        return $errormessage;
    }
    /* fetch the <head> elements upto </head>(excluding </head>) to $headelements*/
    $headelements=substr($htmlcontents, $headposition,$backslashheadposition-$headposition+7);
    $newheadcontent="<head><META NAME=\"generator\" CONTENT=\"easycreate.com Site Builder\">";
    $newheadcontent .="<meta name=\"copyright\" content=\"Copyright (C) www.easycreate.com,www.armia.com. All rights reserved.\"><meta name=\"keywords\" content=\"{\$vsite_metakey}\">";
    $newheadcontent .="<meta name=\"description\" content=\"{\$vsite_metadesc}\">";
    $newheadcontent .="<title>{\$vsite_title}</title>";
    $newheadcontent .="<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
    $newheadcontent .="<style>{\$vsitecolor};</style></head>";
    /* replace the <head>..... elements  $newheadcontent*/
    $htmlcontents=str_replace($headelements,$newheadcontent,$htmlcontents );

    //check for logo
    $logoposition=strpos($htmlcontents,"tp_innerlogoimage.jpg");
    if(!$logoposition) {
        $errormessage="Unable to locate tp_innerlogoimage.jpg in your html file";
        return $errormessage;
    }
    $htmlcontents=str_replace("tp_innerlogoimage.jpg","{\$vinnserlogoband} ",$htmlcontents );
    //check for company
    $compposition=strpos($htmlcontents,"tp_innercompany.jpg");
    if(!$compposition) {
        $errormessage="Unable to locate tp_innercompany.jpg in your html file";
        return $errormessage;
    }
    $htmlcontents=str_replace("tp_innercompany.jpg","{\$vinnercompanyband} ",$htmlcontents );
    //check for caption
    $captionposition=strpos($htmlcontents,"tp_innercaption.jpg");
    if(!$captionposition) {
        $errormessage="Unable to locate tp_innercaption.jpg in your html file";
        return $errormessage;
    }
    $htmlcontents=str_replace("tp_innercaption.jpg","{\$innercaptionband} ",$htmlcontents );
    //check for links

    $startlinkposition=strpos($htmlcontents,"start linkarea");
    if(!$startlinkposition) {
        $errormessage="Unable to locate 'start linkarea' in your html file";
        return $errormessage;
    }
    $endlinkposition=strpos($htmlcontents,"end linkarea");
    if(!$endlinkposition) {
        $errormessage="Unable to locate 'end linkarea' in your html file";
        return $errormessage;
    }
    /* fetch the links within linkarea
							*   eg
							*      <!-- start linkarea --> about us / contact / service / faq / terms / sitemap <!-- end linkarea -->
							*      $linkelements ->start linkarea --> about us / contact / service / faq / terms / sitemap <!--
							*      $linkelemntswithouthtmlcomment->about us / contact / service / faq / terms / sitemap
    */
    $linkelements=substr($htmlcontents, $startlinkposition,$endlinkposition-$startlinkposition);
    $linkelemntswithouthtmlcomment=substr($linkelements, strpos($linkelements,"-->")+3,strpos($linkelements,"<!--")-strpos($linkelements,"-->")-3);

    $newlinkcontent="{\$vsite_links}";
    /* replace the linkarea with tpl variable*/
    $htmlcontents=str_replace($linkelemntswithouthtmlcomment,$newlinkcontent,$htmlcontents );


    //check for editable area
    $starteditableareaposition=strpos($htmlcontents,"start editablearea");
    if(!$starteditableareaposition) {
        $errormessage="Unable to locate 'start editablearea' in your html file";
        return $errormessage;
    }
    $endeditableareaposition=strpos($htmlcontents,"end editablearea");
    if(!$endeditableareaposition) {
        $errormessage="Unable to locate 'end editablearea' in your html file";
        return $errormessage;
    }
    /* fetch the editable area from html file.*/
    $editableelements=substr($htmlcontents, $starteditableareaposition,$endeditableareaposition-$starteditableareaposition);
    $editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
    $neweditcontent="{\$vsubsite_editable}";
    $htmlcontents=str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlcontents );
    $subpagehtmlcontents=$htmlcontents;

    /* write indexhtmlcontents that we are already created to index.tpl
							 *        subpagehtmlcontents to subpage.tpl
    */
    $fp=fopen("$templateid/index.tpl","w");
    fputs($fp,$indexhtmlcontents);
    fclose($fp);

    $fp=fopen("$templateid/subpage.tpl","w");
    fputs($fp,$subpagehtmlcontents);
    fclose($fp);

    $vsublinks=addslashes($linkelemntswithouthtmlcomment);
    $vsubeditable=addslashes($editableelemntswithouthtmlcomment);

    /* update the template master */
    $updateqrystr=" vlinks='".$vlinks."',veditable='".$veditable."',vsub_links='".$vsublinks."',vsub_editable='".$vsubeditable."'";
    $qry="update tbl_template_mast set ".$updateqrystr." where ntemplate_mast='".$originaltemplateid."'";
    mysql_query($qry);
    $fp=fopen("$templateid/qry.tpl","w");
    fputs($fp,$qry);
    fclose($fp);







    /* New validation section starts here */

    /*
							$htmlfile		= "$templateid/index.htm";
							$newTemplate 	= "$templateid/index_temp.htm";
							if (!copy($htmlfile, $newTemplate)) {
							    echo "failed to copy $file...\n";
							}
    */
    // code to check all the panels are exist in the template file
    $tempPanels 	= (array)$panels;

    $tempMessage 	= array();
    foreach($tempPanels as $panel) {
        $starteditableareaposition=strpos($htmlPageContents,"start ".$panel);
        if(!$starteditableareaposition)
            $tempMessage .="Unable to locate 'start ".$panel."' in your html file";

        $endeditableareaposition=strpos($htmlPageContents,"end ".$panel);
        if(!$endeditableareaposition)
            $tempMessage .="Unable to locate 'end ".$panel."' in your html file";
    }


    // code to create new template and db operations
    if(empty($tempMessage)) {


        /*
								 * create new template and insert into database
        */
        foreach($tempPanels as $panel) {
            $startEditPosition	= strpos($htmlPageContents,"start ".$panel);
            $endEditPosition 	= strpos($htmlPageContents,"end ".$panel);



            /* fetch the editable area from html file.*/
            $editableelements	=substr($htmlPageContents, $startEditPosition,$endEditPosition-$startEditPosition);
            $editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
            $neweditcontent		= "{\$editable".$panel."}";
            $htmlPageContents	= str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlPageContents );


            $sql =  "insert into  tbl_template_panel(temp_id,panel_type,panel_html)
									values('".$templateid."','" . $panel. "',
									'".addslashes($editableelemntswithouthtmlcomment)."')";
            mysql_query($sql) or die(mysql_error());



        }
        // create new template with replace values
        $fp=fopen("$templateid/index_temp.htm","w");
        fputs($fp,$htmlPageContents);
        fclose($fp);

        echo "validation success";
    }
    echopre($tempMessage);

    /* New valiation section ends here */











    return true;

}














/* convert html files index.htm,sub.htm to tpl files
*  $templateid ->template location(ie location to index.htm nd sub.htm)
*  $originaltemplateid -> the template id in table template master
*  The function will create index.tpl,subpage.tpl
*  created by : jinson<jinson.m@armiasystems.com>
*/ 
function convertHtmlToTemplate($templateid,$originaltemplateid,$relativepathtoroot,$panels = array(),$pages) {

    /***************************************** convert index.htm**************************************************/

    foreach($pages['filedata'] AS $pageVal){
        if($pageVal->filename =='index.html'){
            $pageAliasIndex = $pageVal->filealias; 
        }
    }
     
    $htmlfile="$templateid/index.html"; 
    if(!is_file($htmlfile)) {
        $errormessage="index.html file doesnot exist";
        return $errormessage;
    }
    $htmlcontents=file_get_contents($htmlfile);

    $htmlPageContents = $htmlcontents;
    $headposition=strpos($htmlcontents,"<head>");
    if(!$headposition) {
        $errormessage="Unable to locate &lthead> in your html file";
        return $errormessage;
    }
    $backslashheadposition=strpos($htmlcontents,"</head>");
    if(!$backslashheadposition) {
        $errormessage="Unable to locate &lt/head> in your html file";
        return $errormessage;
    }

    //add to the page table
    //mysql_query("SET NAMES 'utf8'");
    $sqlAddPage =  "INSERT INTO tbl_template_pages(temp_id,panel_ref,page_name,page_alias)
                    VALUES(".$originaltemplateid.",1,'index.html','".$pageAliasIndex."')";
    mysql_query($sqlAddPage) or die(mysql_error());
    $newPageId = mysql_insert_id();

    /* New validation section starts here */


    // code to check all the panels are exist in the template file
    $tempPanelsArr 	= (array)$panels;
    //$tempPanels = $tempPanelsArr['position'];
    $tempPanels = $tempPanelsArr;
    $tempMessage 	= array();
    
    /*
    foreach($tempPanels as $panel){
            $starteditableareaposition=strpos($htmlPageContents,"start ".$panel);
            if(!$starteditableareaposition){
                    $tempMessage['message'][] ="Unable to locate 'start ".$panel."' in your html file";
                    $tempMessage['result'] = 'error';
            }
            $endeditableareaposition=strpos($htmlPageContents,"end ".$panel);
            if(!$endeditableareaposition){
                    $tempMessage['message'][] ="Unable to locate 'end ".$panel."' in your html file";
                    $tempMessage['result'] = 'error';
            }
    }
    */

    // code to create new template and db operations
    if(empty($tempMessage)) {
        /*
	* create new template and insert into database
        */
        foreach($tempPanels as $panel) {

            $startEditPosition	= strpos($htmlPageContents,"start ".$panel);
            $endEditPosition 	= strpos($htmlPageContents,"end ".$panel);

            /* fetch the editable area from html file.*/
            $editableelements	= substr($htmlPageContents, $startEditPosition,$endEditPosition-$startEditPosition);
            $editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
            $neweditcontent		= "{\$editable".$panel."}";
            $htmlPageContents	= str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlPageContents );

            if($editableelemntswithouthtmlcomment != '') {
                $sql =  "insert into  tbl_template_panel(temp_id,page_type,panel_type,panel_html,temp_page_id)
                         values('".$originaltemplateid."',1,'" . $panel. "',
                         '".addslashes($editableelemntswithouthtmlcomment)."',".$newPageId.")";
                mysql_query($sql) or die(mysql_error());
                $tempMessage['result'] = 'success';
            }
        }
        // create new template with replace values
        $fp=fopen("$templateid/index_temp.htm","w");
        fputs($fp,$htmlPageContents);
        fclose($fp);
    }
    // validation ends for index page

    // subpage iteration starts
    $i=2;
    $pageDetails = $pages['filedata'];

    foreach($pageDetails as $pageData) { 

        $page = $pageData->filename;
        if($page != 'index.html') {
            // validation starts for sub page
            $htmlfile="$templateid/".$page;
            if(!is_file($htmlfile)) {
                $errormessage="subpage.htm file doesnot exist";
                return $errormessage;
            }
            $htmlcontents=file_get_contents($htmlfile);
            $htmlPageContents = $htmlcontents;
            $headposition=strpos($htmlcontents,"<head>");
            if(!$headposition) {
                $errormessage="Unable to locate &lthead> in your html file";
                return $errormessage;
            }
            $backslashheadposition=strpos($htmlcontents,"</head>");
            if(!$backslashheadposition) {
                $errormessage="Unable to locate &lt/head> in your html file";
                return $errormessage;
            }

            //add to the page table
            $sqlAddPage =  "INSERT INTO tbl_template_pages(temp_id,panel_ref,page_name,page_alias)
                            VALUES(".$originaltemplateid.",".$i.",'".$page."','".$pageData->filealias."')";
            mysql_query($sqlAddPage) or die(mysql_error());
            $newPageId = mysql_insert_id();


            /*
	    * create new template and insert into database
            */
            foreach($tempPanels as $panel) {
                $startEditPosition	= strpos($htmlPageContents,"start ".$panel);
                $endEditPosition 	= strpos($htmlPageContents,"end ".$panel);
                /* fetch the editable area from html file.*/
                $editableelements	=substr($htmlPageContents, $startEditPosition,$endEditPosition-$startEditPosition);
                $editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
                $neweditcontent		= "{\$editable".$panel."}";
                $htmlPageContents	= str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlPageContents );

                if($editableelemntswithouthtmlcomment != '') {
                    $sql =  "insert into  tbl_template_panel(temp_id,page_type,panel_type,panel_html,temp_page_id)
									values('".$originaltemplateid."',".$i.",'" . $panel. "',
									'".addslashes($editableelemntswithouthtmlcomment)."',".$newPageId.")";
                    mysql_query($sql) or die(mysql_error());
                }
                $tempMessage['result'] = 'success';
            }
            // create new template with replace values
            $newFile = explode('.',$page);

            $fp=fopen($templateid."/".$newFile[0].'_temp.htm',"w");
            fputs($fp,$htmlPageContents);
            fclose($fp);
            $i++;
            // validation ends for sub page
        }
    }

    /* New valiation section ends here */

    return $tempMessage;

}


?>