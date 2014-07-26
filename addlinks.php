<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.0                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>        		              |
// |          									                          |
// +----------------------------------------------------------------------+
?>
<?php
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";
$tmpsiteid = $_SESSION['session_currenttempsiteid'];
$templateid = $_SESSION['session_currenttemplateid'];
$userid = $_SESSION["session_userid"];
// redirect if tempsites deleted
if (!is_dir("./workarea/tempsites/" . $_SESSION['session_currenttempsiteid'])) {
    header("location:sitemanager.php");
    exit;
}
/*
 * urltype is set to 'back' when we click back button from anyotherpages.
 * same is set if sitelinks exist or its not a home page
 */
if ($_SESSION['session_sitelinks'] != "" and $_SESSION['session_sitelinks'] != "Home") {
    $urltype = "back";
} else {
    $urltype = addslashes($_GET['urltype']);
}

function isValidLinkname($links) {
    if (trim($links) != "") {
        if (preg_match("[^0-9a-zA-Z+, ]", $links)) {
            return false;
        } else {
            return true;
        }
    } else {
        return false;
    }
}

/* get the deleted image entry in the resource file 
 * $filelocation->location of the page to be delete
 * $resourcelocation->location of the resource file
 * $var_deletedimages->will contain the deleted images seperated bt '|'
 */

function getDeletedImages($filelocation, $resourcelocation) {
    $var_deletedimages = "";
    $filecontent = file_get_contents($filelocation);
    $fp = fopen($resourcelocation, "r");
    $imagearray = array();
    while ($str = fgets($fp)) {
        $searchstring = substr(basename($str), 0, -1);
        array_push($imagearray, $searchstring);
    }
    $imagearray = array_unique($imagearray);
    foreach ($imagearray as $key => $value) {
        $i = 0;
        $cnt = substr_count($filecontent, $value);
        for ($i = 0; $i < $cnt; $i++) {
            $var_deletedimages .= $value . "|";
        }
    }

    return $var_deletedimages;
}

function SaveResource($resource_location, $var_deletedimages, $var_newimages) {
    if (is_file($resource_location)) {
        $content_arr = file($resource_location);
        if (strlen($var_deletedimages) > 0) { // IF Delete images > 0
            $deleted_arr = explode('|', $var_deletedimages);
            $deleted_count = count($deleted_arr);

            for ($i = 0; $i < $deleted_count; $i++) { // FOR LOOP - I
                $temp_string = $deleted_arr[$i];
                switch (substr($temp_string, 0, 2)) {
                    case "ug":
                        $temp_string = "usergallery/" . $_SESSION["session_userid"] . "/images/" . $temp_string;
                        break;
                    case "sg":
                        $temp_string = "systemgallery/" . $temp_string;
                        break;
                    case "sl":
                        $temp_string = "samplelogos/" . $temp_string;
                        break;
                    default:
                        continue;
                }
                $temp_count = 0;
                foreach ($content_arr as $lines) { // FOR LOOP - II
                    if (strcmp($temp_string, rtrim($lines, "\n")) == 0) {
                        $content_arr[$temp_count] = "";
                        break;
                    }
                    $temp_count++;
                } // END FOR LOOP - II	
            } // END FOR LOOP - I
        } // END IF Delete images > 0
    }
    $fwriter = fopen($resource_location, "w");
    if (count($content_arr) > 0) {
        foreach ($content_arr as $lines) { // FOR LOOP
            if ($lines != "") {
                fputs($fwriter, $lines);
            }
        }
    }
    if (strlen($var_newimages) > 0) { // IF New images > 0
        $new_arr = explode('|', $var_newimages);
        $new_count = count($new_arr);
        for ($i = 0; $i < $new_count; $i++) { // FOR LOOP - I
            $temp_string = $new_arr[$i];
            switch (substr($temp_string, 0, 2)) {
                case "ug":
                    $temp_string = "usergallery/" . $_SESSION["session_userid"] . "/images/" . $temp_string . "\n";
                    break;
                case "sg":
                    $temp_string = "systemgallery/" . $temp_string . "\n";
                    break;
                case "sl":
                    $temp_string = "samplelogos/" . $temp_string . "\n";
                    break;
                default:
                    continue;
            }
            fputs($fwriter, $temp_string);
        }
    } // END IF New images > 0
    fclose($fwriter);
}

/* Save the data into tbl_tempsite_mast,and form the html files using smarty
 * $userid->userid
 * $tmpsiteid->the temporary siteid in which user is working
 * $tmpsiteid->template id
 * $tmpsiteid->page created by user separated by coma
 * $sitecolor->site color
 * $logo-> logo image
 * $sublogo->sub logo image
 * $caption-> caption
 * $subcaption->sub caption
 * $company->Company
 * $subcompany->sub company
 * $title->title of the site
 * $metadesc->meta description of the site
 * $metakey->meta key of the site
 */

function SavesiteData($userid, $tmpsiteid, $templateid, $links, $pagetype, $sitecolor, $logo, $sublogo, $caption, $subcaption, $company, $subcompany, $title, $metadesc, $metakey) {
    /* title,meta with addslashes */
    $titleoriginal = $title;
    $metadescoriginal = $metadesc;
    $metakeyoriginal = $metakey;
    /* title,meta for pages */
    $metadesc = htmlentities(stripslashes($metadesc));
    $metakey = htmlentities(stripslashes($metakey));
    $title = htmlentities(stripslashes($title));
    /* copy the style sheet . */
    copy("./" . $_SESSION["session_template_dir"] . "/" . $templateid . "/style.css", "./workarea/tempsites/" . $tmpsiteid . "/style.css");
    chmod("./workarea/tempsites/" . $tmpsiteid . "/style.css", 0755);
    /* fetch linkseprator,linktype (ie vertical or horizontal),editable content,subpage editable contect */
    $qry = " select * from tbl_template_mast where ntemplate_mast='" . $_SESSION['session_currenttemplateid'] . "'";
    $rs = mysql_query($qry);
    $row = mysql_fetch_array($rs);
    $templateseparator = $row['vlink_separator'];
    $linktype = $row['vlink_type'];
    if ($linktype == "vertical") {
        $linkseparator = "<br>";
    } else {
        $linkseparator = "";
    }
    $subtemplatesepartor = $row['vsublink_separator'];
    $sublinktype = $row['vsublink_type'];
    if ($sublinktype == "vertical") {
        $sublinkseparator = "<br>";
    } else {
        $sublinkseparator = "";
    }
    $editobaletext = $row['veditable'];
    $subeditobaletext = $row['vsub_editable'];
    // call smarty libraries
    require('./smarty/lib/Smarty.class.php');
    /* remove the files in template_c directory.to avoid conflict with another users site */
    remove_dir("./smarty/templates_c");
    @mkdir("./smarty/templates_c", 0777);
    @chmod("./smarty/templates_c", 0777);
    /* user select the site color set the style for site color */
    if ($sitecolor != "") {
        $siteclrvalue = ".variable { background-color:" . $sitecolor . "; }";
    } else {
        $siteclrvalue = "";
    }

    $smarty = new Smarty();
    $smarty->template_dir = "./" . $_SESSION["session_template_dir"] . "/" . $templateid;
    $smarty->compile_dir = './smarty/templates_c';
    $smarty->cache_dir = './smarty/cache';
    $smarty->config_dir = './smarty/configs';
    // insert page into tbl_tempsite_pages
    $qrystartvalues = "";
    $qrystart = "insert into tbl_tempsite_pages(ntempsp_id,ntempsite_id,vpage_name,vpage_title,vpage_type,vtype) values";

    if ($links == "") {
        $sitelinks .= "<a class=anchor1 href=\"./home.htm\">" . Home . "</a>";
        // clear the cache
        $smarty->clear_cache('index.tpl');
        $smarty->assign('vsite_metadesc', $metadesc);
        $smarty->assign('vsite_metakey', $metakey);
        $smarty->assign('vsite_title', $title);
        $smarty->assign('vsitecolor', $siteclrvalue);
        $smarty->assign('vlogoband', $logo);
        $smarty->assign('vcompanyband', $company);
        $smarty->assign('captionband', $caption);
        $smarty->assign('vsite_links', $sitelinks);
        $html = $smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
        if (!is_file("./sitepages/tempsites/$tmpsiteid/home.htm")) {
            $fp = fopen("./workarea/tempsites/" . $tmpsiteid . "/home.htm", "w");
            fputs($fp, $html);
            fclose($fp);
            $fp = fopen("./sitepages/tempsites/$tmpsiteid/home.htm", "w");
            fputs($fp, $editobaletext);
            fclose($fp);
        }

        $qrystartvalues .= "('','" . $tmpsiteid . "','Home','" . $titleoriginal . "','homepage','simple'),";
    } else {
        /* build the array using links(that are separated by coma) */
        $linkvaluesarray = explode(",", $links);
        $pagetypearray = explode(",", $pagetype);
        foreach ($linkvaluesarray as $key => $value) {
            $value1 = str_replace(" ", "", $value);
            $value1 = strtolower($value1);
            $pagename = $value1 . ".htm";
            /* link type is vertical linkseparator(<br>) and templateseparator (fetch from table) is appended infront of page(not for first link)
             *  link type is vertical templateseparator (fetch from table) is appended infront of page(not for first link)
             * 
             */
            if ($linktype == "vertical") {
                // seprator first
                if ($sitelinks == "")
                    $sitelinks .= $templateseparator . "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>";
                else
                    $sitelinks .= $linkseparator . $templateseparator . "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>";
            } else {
                if ($sitelinks == "")
                    $sitelinks .= "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>";
                else
                    $sitelinks .= $templateseparator . "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>" . $linkseparator;
            }
            if ($sublinktype == "vertical") {
                if ($subsitelinks == "")
                    $subsitelinks .= $templateseparator . "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>";
                else
                    $subsitelinks .= $sublinkseparator . $subtemplatesepartor . "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>";
            } else {
                if ($subsitelinks == "")
                    $subsitelinks .= "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>";
                else
                    $subsitelinks .= $subtemplatesepartor . "<a class=anchor1 href=\"./$pagename\">" . $value . "</a>" . $sublinkseparator;
            }
        }
        /* traverse the linkarray and build html files .html files are stored in work area location.
         * editable contents are stored in sitepages/tempsites/tempsiteid.if the page created for first time 
         * editable content will be default that are fetched from tbl_template_mast
         * Add the page information to tbl_tempsite_pages
         */

        foreach ($linkvaluesarray as $key => $value) {
            $value1 = str_replace(" ", "", $value);
            $value1 = strtolower($value1);
            $pagename = $value1 . ".htm";

            if (strcasecmp($value, "home") == 0 or ($pagetypearray[$key] == "homepage")) {
                // clear the cache
                $smarty->clear_cache('index.tpl');
                $smarty->assign('vsite_metadesc', $metadesc);
                $smarty->assign('vsite_metakey', $metakey);
                $smarty->assign('vsite_title', $title);
                $smarty->assign('vsitecolor', $siteclrvalue);

                $smarty->assign('vlogoband', $logo);
                $smarty->assign('vcompanyband', $company);
                $smarty->assign('captionband', $caption);
                $smarty->assign('vsite_links', $sitelinks);
                $html = $smarty->fetch('index.tpl', $cache_id = null, $compile_id = null, false);
                if (!is_file("./sitepages/tempsites/$tmpsiteid/$pagename")) {
                    $fp = fopen("./workarea/tempsites/" . $tmpsiteid . "/$pagename", "w");
                    fputs($fp, $html);
                    fclose($fp);
                    $fp = fopen("./sitepages/tempsites/$tmpsiteid/$pagename", "w");
                    fputs($fp, $editobaletext);
                    fclose($fp);
                }
                $qrystartvalues .= "('','" . $tmpsiteid . "','$value','" . $titleoriginal . "','homepage','simple'),";
            } else {
                $smarty->clear_cache('subpage.tpl');
                $smarty->assign('vsite_metadesc', $metadesc);
                $smarty->assign('vsite_metakey', $metakey);
                $smarty->assign('vsite_title', $title);
                $smarty->assign('vsitecolor', $siteclrvalue);
                $smarty->assign('vinnserlogoband', $sublogo);
                $smarty->assign('vinnercompanyband', $subcompany);
                $smarty->assign('innercaptionband', $subcaption);
                $smarty->assign('vsite_links', $subsitelinks);

                $html = $smarty->fetch('subpage.tpl', $cache_id = null, $compile_id = null, false);
                if (!is_file("./sitepages/tempsites/$tmpsiteid/$pagename")) {
                    $fp = fopen("./workarea/tempsites/" . $tmpsiteid . "/$pagename", "w");
                    fputs($fp, $html);
                    fclose($fp);
                    $fp = fopen("./sitepages/tempsites/$tmpsiteid/$pagename", "w");
                    fputs($fp, $subeditobaletext);
                    fclose($fp);
                }
                $qrystartvalues .= "('','" . $tmpsiteid . "','$value','" . $titleoriginal . "','subpage','simple'),";
            }
        }
    }

    $qrystartvalues = substr($qrystartvalues, 0, -1);
    // Update table tempsite
    if (get_magic_quotes_gpc() == 1) {
        $sitelinks = $sitelinks;
        $subsitelinks = $subsitelinks;
    } else {
        $sitelinks = addslashes($sitelinks);
        $subsitelinks = addslashes($subsitelinks);
    }
    /* update the tbl_tempsite_mast with sitelinks,subsitelinks etc */
    $qry = "update tbl_tempsite_mast set vtitle='" . $titleoriginal . "',vmeta_description='" . $metadescoriginal . "',vmeta_key='" . $metakeyoriginal . "',vlogo='" . $logo . "',";
    $qry .= "vcompany='" . $company . "',vcaption='" . $caption . "',vlinks='" . $sitelinks . "',vcolor='" . $sitecolor . "',vemail='" . $_SESSION['session_sitemeemail'] . "',ddate=now(),vdelstatus='0'";
    $qry .= ",vsub_logo='" . $sublogo . "',vsub_caption='" . $subcaption . "',vsub_company='" . $subcompany . "',vsub_sitelinks='" . $subsitelinks . "',vtype='simple'";
    $qry .= " where ntempsite_id='" . $tmpsiteid . "'";
    // echo $qry;
    // exit;
    mysql_query($qry) or die(mysql_error());

    $pageqry = $qrystart . $qrystartvalues;
    // delete all the page content
    $qry = "delete from tbl_tempsite_pages where ntempsite_id='" . $tmpsiteid . "'";
    mysql_query($qry);
    // insert into tbl_tempsite_pages
    mysql_query($pageqry);
}

if ($_POST["postback"] == "continue") {
    if (!validateSizePerUser($_SESSION["session_userid"], $size_per_user, $allowed_space)) {
        $errorinlink = "The space taken by you has exceeded the allowed limit.<br>(Space taken by you: " . human_read($size_per_user) . " <br>Allowed space: " . human_read($allowed_space) . ")<br>Delete 
		   unused images or any/all of the sites created by you to proceed further.<br>&nbsp;<br>";
    } else {
        $errorinlink = "";
        /* page name a passed as POST varibale seprated by coma */
        $linkvalues = substr(addslashes(trim($_POST['linkname'])), 0, -1);
        $linktypevalues = substr(addslashes(trim($_POST['linktype'])), 0, -1);
        $homepageexist = "0";
        $linksvalueexceeds = "0";
        $linkarr = explode(",", $linkvalues);
        /* check home page exist and any of the page length exceeds 20 character limit */
        foreach ($linkarr as $key => $value) {
            if (strcasecmp($value, "Home") == "0") {
                $homepageexist = "1";
            }
            if (strlen($value) > 20) {
                $linksvalueexceeds = "1";
            }
        }
        if (!isValidLinkname($linkvalues)) {
            $errorinlink = "Invalid link values.use only alphabets,digits and space.";
        } else if ($homepageexist == "0" or $linksvalueexceeds == "1") {
            $errorinlink = "Invalid link values.either home page does not exist or page length exceeds 20 character.";
        } else {

            $_SESSION['session_sitelinks'] = substr(addslashes($_POST['linkname']), 0, -1);
            $_SESSION['session_sitelinkstype'] = substr(addslashes($_POST['linktype']), 0, -1);
            if ($_SESSION['session_oldsitelinks'] != "") {
                /* delete the removed page,and its image entry in reource.txt */
                $oldlinksarray = explode(",", $_SESSION['session_oldsitelinks']);
                $newlinksarray = explode(",", $_SESSION['session_sitelinks']);
                $differencelinks = array_diff($oldlinksarray, $newlinksarray);
                foreach ($differencelinks as $key => $value) {
                    $value1 = str_replace(" ", "", $value);
                    $value1 = strtolower($value1);
                    $pagename = $value1 . ".htm";
                    if (is_file("./workarea/tempsites/" . $tmpsiteid . "/$pagename"))
                        unlink("./workarea/tempsites/" . $tmpsiteid . "/$pagename");
                    if (is_file("./sitepages/tempsites/$tmpsiteid/$pagename")) {
                        $resource_location = "./workarea/tempsites/$tmpsiteid/resource.txt";
                        $deltedimages = getDeletedImages("./sitepages/tempsites/$tmpsiteid/$pagename", "./workarea/tempsites/" . $tmpsiteid . "/resource.txt");
                        SaveResource($resource_location, $deltedimages, "");
                        unlink("./sitepages/tempsites/$tmpsiteid/$pagename");
                    }
                }
            }

            $_SESSION['session_oldsitelinks'] = $_SESSION['session_sitelinks'];
            $linkvalues = addslashes(trim($_SESSION['session_sitelinks']));
            SavesiteData($userid, $tmpsiteid, $templateid, $linkvalues, $linktypevalues, $_SESSION['session_sitecolor'], $_SESSION['session_logobandname'], $_SESSION['session_innerlogobandname'], $_SESSION['session_captionbandname'], $_SESSION['session_innercaptionbandname'], $_SESSION['session_companybandname'], $_SESSION['session_innercompanybandname'], $_SESSION['session_sitetitle'], $_SESSION['session_sitemetadesc'], $_SESSION['session_sitemetakey']);
            header("location:./simpleIEeditor/editor.php");
            exit;
        }
    }
} else if ($_POST["btnback"] == "Back") {
    $errorinlink = "";
    $linkvalues = substr(addslashes(trim($_POST['linkname'])), 0, -1);
    if (!isValidLinkname($linkvalues)) {
        $errorinlink = "Invalid link values.use only alphabets,digits and space";
    } else {
        $_SESSION['session_sitelinks'] = substr(addslashes($_POST['linkname']), 0, -1);
        $_SESSION['session_oldsitelinks'] = $_SESSION['session_sitelinks'];
        $location = "./" . $_SESSION['session_backurl'];
        header("location:$location");
        exit;
    }
}
include "includes/userheader.php";

?>
<script>
<!--
function showHomePagepreview(id){
        //alert(id)
        var leftPos = (screen.availWidth-500) / 2;
        var topPos = (screen.availHeight-400) / 2 ;
        winurl="showhomepagepriview.php?id="+id+"&";
        //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
        showpriview = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos); 
        
        
        
    }
    function showSubPagepreview(id){
        var leftPos = (screen.availWidth-500) / 2;
        var topPos = (screen.availHeight-400) / 2 ;
        winurl="showsubpagepriview.php?id="+id+"&";
        //winurl="showpreview.php?linkvalues="+linkvalues+"&linktypevalues="+linktypevalues+"&";
        showpriview = window.open(winurl,'','width=' + screen.availWidth + ',height=' + screen.availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos); 
        
        
        
    }
    function validchars(strstring, allowedchar, strl)
    {
        var ch; 
        // strstring=trim(strstring);
        if (strstring . length > strl) {
            return false;
        } 
        
        for (i = 0; i < strstring . length;i++) {
            ch = strstring . charAt(i);
            for (j = 0; j < allowedchar . length; j++)
                if (ch == allowedchar . charAt(j))
                    break;
            
            if (j == allowedchar . length) {
                return false;
                break;
            } 
        } 
        return true;
    } 
    /* 
          innerhtmlarray is multi dimensioanl array  
          the array contains the follwoing values
          innerhtmlarray[][0]="<table><tr><td>pagename</td><td>Move up</td><td>Move down</td><td>Remove</td></tr></table>"
                  innerhtmlarray[][1]=pagename
                  innerhtmlarray][2]=homepage or subpage;  
     */
    
    var innerhtmlarray = new Array(); 
    // declare array
    for(var arraylimit = 0;arraylimit < 100;arraylimit++) {
        innerhtmlarray[arraylimit] = new Array();
    } 
    
    /* count-> from whcih line  this function calls (staring from 0).for the fist line this function
     willbe disabled  .ie from second line count willbe 1
         pagename->Name of the page
         currcount->The number of page added by the user
     */
    
    function moveup(count, pagename)
    {
        var linkhtml = "";
        var currcount = document . getElementById("linkcount") . value;
        countminus1 = parseInt(count)-1;
        /* temporaraly store current pagename,pagetype and  upone pagename,pagetype*/
        tmp1pagename = innerhtmlarray[count][1];
        tmp1pagetype = innerhtmlarray[count][2];
        
        tmp2pagename = innerhtmlarray[countminus1][1];
        tmp2pagetype = innerhtmlarray[countminus1][2];
        /*swap the values in innerhtmlarray*/
        innerhtmlarray[countminus1][1] = tmp1pagename;
        innerhtmlarray[countminus1][2] = tmp1pagetype;
        innerhtmlarray[count][1] = tmp2pagename;
        innerhtmlarray[count][2] = tmp2pagetype;
        /* rebuild the innerhtml array 
                    var1->pagename
                        var2->Moveup link.for the first line it will be disabled
                        var3->Move Down.for the last line it wil be disabled.
                        var3->Remove.for the home page line it will be disabled
        
        
         */
        for(var i = 0;i < currcount;i++) {
            var oldpagename = innerhtmlarray[i][1];
            var oldpagetype = innerhtmlarray[i][2];
            if (oldpagename == "###deleted####")
                continue;
            var1 = "<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' class=maintext align=left>" + oldpagename + "</td>";
            if (i == 0) {
                var2 = "<td width='40%' align=left class=maintext>Move Up";
            } else {
                var2 = "<td width='40%' align=left class=maintext><a class=anchor   href=\"javascript:moveup('" + i + "','" + oldpagename + "')\">Move Up</a>";
            } 
            if (i == parseInt(currcount)-1) {
                var3 = "Move Down";
            } else {
                var3 = "<a class=anchor   href=\"javascript:movedown('" + i + "','" + oldpagename + "')\">Move Down</a>";
            } 
            if (oldpagename == "Home") {
                var4 = "Remove</td><td align=left class=maintext>" + oldpagetype + "</td></tr></table>";
            } else {
                var4 = "<a class=anchor   href=\"javascript:remove('" + i + "','" + oldpagename + "')\">Remove</a></td><td align=left class=maintext>" + oldpagetype + "</td></tr></table>";
            } 
            innerhtmlarray[i][0] = var1 + var2 + "&nbsp;&nbsp;&nbsp;" + var3 + "&nbsp;&nbsp;&nbsp;" + var4;;
            innerhtmlarray[i][1] = oldpagename;
            innerhtmlarray[i][2] = oldpagetype;
            linkhtml = linkhtml + innerhtmlarray[i][0];
        } 
        document . getElementById("addedpages") . innerHTML = linkhtml;
    } 
    /* count-> from whcih line  this function calls (staring from 0).for the fist line this function
     willbe disabled  .ie from second line count willbe 1
         pagename->Name of the page
         currcount->The number of page added by the user
     */
    function movedown(count, pagename)
    {
        var linkhtml = "";
        var currcount = document . getElementById("linkcount") . value;
        currcountwithdeletd = currcount;
        countplus1 = parseInt(count) + 1;
        countminus1 = parseInt(count)-1;
        tmp1pagename = innerhtmlarray[count][1];
        tmp1pagetype = innerhtmlarray[count][2];
        
        tmp2pagename = innerhtmlarray[countplus1][1];
        tmp2pagetype = innerhtmlarray[countplus1][2];
        
        innerhtmlarray[count][1] = tmp2pagename;
        innerhtmlarray[count][2] = tmp2pagetype;
        
        innerhtmlarray[countplus1][1] = tmp1pagename;
        innerhtmlarray[countplus1][2] = tmp1pagetype;
        /* rebuild the innerhtml array 
                    var1->pagename
                        var2->Moveup link.for the first line it will be disabled
                        var3->Move Down.for the last line it wil be disabled.
                        var3->Remove.for the home page line it will be disabled
         */
        for(var i = 0;i < currcount;i++) {
            var oldpagename = innerhtmlarray[i][1];
            var oldpagetype = innerhtmlarray[i][2];
            if (oldpagename == "###deleted####") {
                // currcountwithdeletd=currcountwithdeletd -1;
                continue;
            } 
            var1 = "<table width='100%'><tr bgcolor=#CCCCCC ><td width='30%' class=maintext align=left>" + oldpagename + "</td>";
            if (i == 0) {
                var2 = "<td width='40%' class=maintext align=left>Move Up";
            } else {
                var2 = "<td width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('" + i + "','" + oldpagename + "')\">Move Up</a>";
            } 
            if (i == parseInt(currcount)-1) {
                var3 = "Move Down";
            } else {
                var3 = "<a class=anchor   href=\"javascript:movedown('" + i + "','" + oldpagename + "')\">Move Down</a>";
            } 
            if (oldpagename == "Home") {
                var4 = "Remove</td><td class=maintext align=left>" + oldpagetype + "</td></tr></table>";
            } else {
                var4 = "<a class=anchor   href=\"javascript:remove('" + i + "','" + oldpagename + "')\">Remove</a></td><td align=left class=maintext>" + oldpagetype + "</td></tr></table>";
            } 
            
            innerhtmlarray[i][0] = var1 + var2 + "&nbsp;&nbsp;&nbsp;" + var3 + "&nbsp;&nbsp;&nbsp;" + var4;
            innerhtmlarray[i][1] = oldpagename;
            innerhtmlarray[i][2] = oldpagetype;
            
            linkhtml = linkhtml + innerhtmlarray[i][0];
        } 
        document . getElementById("addedpages") . innerHTML = linkhtml;
    } 
    /* count-> from whcih line  this function calls (staring from 0).for the fist line this function
     willbe disabled  .ie from second line count willbe 1
         pagename->Name of the page
         currcount->The number of page added by the user
     */
    function remove(count, pagename)
    {
        var linkhtml = "";
        var currcount = document . getElementById("linkcount") . value;
        var newcount = parseInt(count) + 1;
        currcount = parseInt(currcount)-1;
        document . getElementById("linkcount") . value = currcount;
        /*  delete the  the line from which  this function calls.
                     delete operation done by replace the deleted line with next line ,next line with line after the next line and so on
        
         */
        for(var j = count;j < currcount;j++) {
            innerhtmlarray[j][1] = innerhtmlarray[newcount][1];
            innerhtmlarray[j][2] = innerhtmlarray[newcount][2];
            innerhtmlarray[j][0] = innerhtmlarray[newcount][0];
            newcount = newcount + 1;
        } 
        /* rebuild the innerhtml array .current count decreased by 1 in the previous step
                    var1->pagename
                        var2->Moveup link.for the first line it will be disabled
                        var3->Move Down.for the last line it wil be disabled.
                        var3->Remove.for the home page line it will be disabled
         */
        for(var i = 0;i < currcount;i++) {
            var oldpagename = innerhtmlarray[i][1];
            var oldpagetype = innerhtmlarray[i][2];
            if (oldpagename == "###deleted####") {
                currcountwithdeletd = currcountwithdeletd -1;
                continue;
            } 
            var1 = "<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' class=maintext align=left>" + oldpagename + "</td>";
            if (i == 0) {
                var2 = "<td width='40%' class=maintext align=left>Move Up";
            } else {
                var2 = "<td width='40%' class=maintext align=left><a class=anchor   class='MoveUp' href=\"javascript:moveup('" + i + "','" + oldpagename + "')\">Move Up</a>";
            } 
            
            if (i == parseInt(currcount)-1) {
                var3 = "Move Down";
            } else {
                var3 = "<a class=anchor   href=\"javascript:movedown('" + i + "','" + oldpagename + "')\">Move Down</a>";
            } 
            if (oldpagename == "Home") {
                var4 = "Remove</td><td align=left class=maintext>" + oldpagetype + "</td></tr></table>";
            } else {
                var4 = "<a class=anchor   href=\"javascript:remove('" + i + "','" + oldpagename + "')\">Remove</a></td><td align=left class=maintext>" + oldpagetype + "</td></tr></table>";
            } 
            innerhtmlarray[i][0] = var1 + var2 + "&nbsp;&nbsp;&nbsp;" + var3 + "&nbsp;&nbsp;&nbsp;" + var4;
            innerhtmlarray[i][1] = oldpagename;
            innerhtmlarray[i][2] = oldpagetype;
            
            linkhtml = linkhtml + innerhtmlarray[i][0];
        } 
        document . getElementById("addedpages") . innerHTML = linkhtml;
    } 
    
    function clickcontinue(id)
    {
        /*
                    linkvalues->all the page added by the user seperated by coma,and if user checked
                        website with only one page linkvalues will be set to 'Home,'
                        linktypevalues->whether the page is homepage or subpage
        
         */
        
        var linkvalues = "";
        var linktypevalues = "";
        if (document . getElementById("site") . checked) {
            linkvalues = "Home,";
            linktypevalues = "homepage,";
        } else {
            var currcount = document . getElementById("linkcount") . value;
            for(var i = 0;i < currcount;i++) {
                linkvalues = linkvalues + innerhtmlarray[i][1] + ",";
                linktypevalues = linktypevalues + innerhtmlarray[i][2] + ",";
            } 
        } 
        document . getElementById("linkname") . value = linkvalues;
        document . getElementById("linktype") . value = linktypevalues;
        document . getElementById("postback") . value = "continue";
        document . frmAddlinks . submit();
    } 
    function clickSave(id)
    {
        var linkvalues = "";
        var linktypevalues = "";
        var currcount = document . getElementById("linkcount") . value;
        for(var i = 0;i < currcount;i++) {
            linkvalues = linkvalues + innerhtmlarray[i][1] + ",";
            linktypevalues = linktypevalues + innerhtmlarray[i][2] + ",";
        } 
        
        document . getElementById("linkname") . value = linkvalues;
        document . getElementById("linktype") . value = linktypevalues;
        document . getElementById("postback") . value = "Save";
        document . frmAddlinks . submit();
    } 
    function showpreview(id)
    {
        /*
                    linkvalues->all the page added by the user seperated by coma,and if user checked
                        website with only one page linkvalues will be set to 'Home,'
                        linktypevalues->whether the page is homepage or subpage
        
         */
        var linkvalues = "";
        var linktypevalues = "";
        if (document . getElementById("site") . checked) {
            linkvalues = "Home,";
            linktypevalues = "homepage,";
        } else {
            var currcount = document . getElementById("linkcount") . value;
            for(var i = 0;i < currcount;i++) {
                linkvalues = linkvalues + innerhtmlarray[i][1] + ",";;
                linktypevalues = linktypevalues + innerhtmlarray[i][2] + ",";
            } 
        } 
        var leftPos = (screen . availWidth-500) / 2;
        var topPos = (screen . availHeight-400) / 2 ;
        winurl = "showpreview.php?linkvalues=" + linkvalues + "&linktypevalues=" + linktypevalues + "&";
        insertFormWin = window . open(winurl, '', 'width=' + screen . availWidth + ',height=' + screen . availHeight + ',scrollbars=yes,resizable=yes,titlebar=0,top=' + topPos + ',left=' + leftPos);
    } 
    /*
        type->'0' means its called from this page(ie by click add page button).'1' means automatic calls(when the page loaded)
        pagetype->type fo the page(ie homepage or subpage)
    
     */
    function addpage(type, pagetype)
    {
        var linkhtml = "";
        var currcount = document . getElementById("linkcount") . value;
        var pagename = document . getElementById("pagename") . value;
        var allowedchar = "abcdefghijklmnopqrstuvwxyz ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        if (pagename . length == 0 || pagename . substr(0, 1) == " ") {
            alert("Please enter page name");
            return false;
        } 
        if (! validchars(pagename, allowedchar, "20")) {
            alert("Please enter valid page name.use only alphabets,digits and space");
            return false;
        } 
        /* check for dupliacte page name,feedback,guestbook.
          when this function calls on page loading feedback,guestbook checking avoided*/
        for(var i = 0;i < currcount;i++) {
            pagenameU = pagename . toUpperCase();
            innerhtmlarrayU = innerhtmlarray[i][1] . toUpperCase();
            if (pagenameU == innerhtmlarrayU) {
                alert("duplicate page name");
                return false;
            } 
            if ((pagenameU == "FEEDBACK" || pagenameU == "GUESTBOOK") && type == "0") {
                
                alert("You cannot add " + pagename + " page here.Please use Integration Manager to add " + pagename + " page");
                
                return false;
            } 
        } 
        
        if (pagetype == "") {
            if (document . getElementById("ptype") . checked) {
                pagetype = "homepage";
            } else {
                pagetype = "subpage";
            } 
        } 
        /* rebuild the innerhtml array .current count decreased by 1 in the previous step
                    var1->pagename
                        var2->Moveup link.for the first line it will be disabled
                        var3->Move Down.for the last line it wil be disabled.
                        var3->Remove.for the home page line it will be disabled
         */
        for(var i = 0;i < currcount;i++) {
            var oldpagename = innerhtmlarray[i][1];
            var oldpagetype = innerhtmlarray[i][2];
            
            var1 = "<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>" + oldpagename + "</td>";
            if (i == 0) {
                var2 = "<td width='40%' class=maintext align=left>Move Up";
            } else {
                var2 = "<td  width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('" + i + "','" + oldpagename + "')\">Move Up</a>";
            } 
            var3 = "<a class=anchor   href=\"javascript:movedown('" + i + "','" + oldpagename + "')\">Move Down</a>";
            if (oldpagename == "Home") {
                var4 = "Remove</td><td class=maintext align=left>" + oldpagetype + "</td></tr></table>";
            } else {
                var4 = "<a class=anchor   href=\"javascript:remove('" + i + "','" + oldpagename + "')\">Remove</a></td><td align=left class=maintext>" + oldpagetype + "</td></tr></table>";
            } 
            innerhtmlarray[i][0] = var1 + var2 + "&nbsp;&nbsp;&nbsp;" + var3 + "&nbsp;&nbsp;&nbsp;" + var4;
            innerhtmlarray[i][1] = oldpagename;
            innerhtmlarray[i][2] = oldpagetype; 
            // alert(innerhtmlarray[i][0]);
            linkhtml = linkhtml + innerhtmlarray[i][0];
        } 
        
        if (pagename == "Home" && currcount == 0) {
            // alert(pagetype);
            var1 = "<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>" + pagename + "</td>";
            var2 = "<td width='40%' class=maintext align=left>Move Up";
            var3 = "Move Down";
            var4 = "Remove</td><td class=maintext align=left>" + pagetype + "</td></tr></table>";
        } else if (pagename == "Home") {
            var1 = "<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>" + pagename + "</td>";
            var2 = "<td width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('" + currcount + "','" + pagename + "')\">Move Up</a>";
            var3 = "Move Down";
            var4 = "Remove</td><td class=maintext align=left>" + pagetype + "</td></tr></table>";
        } else {
            var1 = "<table width='100%'><tr bgcolor=#CCCCCC><td width='30%' align=left class=maintext>" + pagename + "</td>";
            var2 = "<td width='40%' class=maintext align=left><a class=anchor   href=\"javascript:moveup('" + currcount + "','" + pagename + "')\">Move Up</a>";
            var3 = "Move Down";
            var4 = "<a class=anchor   href=\"javascript:remove('" + currcount + "','" + pagename + "')\">Remove</a></td><td class=maintext align=left>" + pagetype + "</td></tr></table>";
        } 
        
        innerhtmlarray[currcount][0] = var1 + var2 + "&nbsp;&nbsp;&nbsp;" + var3 + "&nbsp;&nbsp;&nbsp;" + var4;
        innerhtmlarray[currcount][1] = pagename;
        innerhtmlarray[currcount][2] = pagetype;
        currcount = parseInt(currcount) + 1;
        document . getElementById("linkcount") . value = currcount;
        
        linkhtml = linkhtml + innerhtmlarray[i][0];
        
        document . getElementById("addedpages") . innerHTML = linkhtml;
        document . getElementById("pagename") . value = "";
    } 
    
    function loaddefault()
    {
        var pagename = "Home";
        var1 = "<table width='100%' border=0><tr bgcolor=#CCCCCC><td  class='maintext' width='30%' align=left>" + pagename + "</td>";
        var2 = "<td width='40%' class='maintext'  align=left>Move Up";
        var3 = "Move Down ";
        var4 = "Remove</td><td class=maintext align=left>homepage</td></tr></table>";
        innerhtmlarray[0][0] = var1 + var2 + "&nbsp;&nbsp;&nbsp;" + var3 + "&nbsp;&nbsp;&nbsp;" + var4;
        innerhtmlarray[0][1] = pagename;
        innerhtmlarray[0][2] = "homepage";
        document . getElementById("addedpages") . innerHTML = innerhtmlarray[0][0];;
        document . getElementById("linkcount") . value = "1";
    } 
    function clicksimple()
    {
        document . getElementById("multiplepages") . style . display = "none";
    } 
    function clickmultiple()
    {
        document . getElementById("multiplepages") . style . display = "";
    } 
    function clickback(id)
    {
        var linkvalues = "";
        if (document . getElementById("site") . checked) {
            linkvalues = "Home,";
        } else {
            var currcount = document . getElementById("linkcount") . value;
            for(var i = 0;i < currcount;i++) {
                linkvalues = linkvalues + innerhtmlarray[i][1] + ",";;
            } 
        } 
        document . getElementById("linkname") . value = linkvalues;
    } 
    -->
</script> 
		  
<table width="100%"  border="0" cellspacing="0" cellpadding="0">
    <tr>
        <td  valign="top" align=center>
            <table width="100%"  border="0" cellspacing="0" cellpadding="0">
                <tr>
                    <td align=center>
                        <div class="stage_selector">
                            <span>3</span>&nbsp;&nbsp;Create Pages
                        </div>
                                      
                                      
                    </td>
                </tr>
                <tr>
                    <td align=center>&nbsp;</td>
                </tr>
                <tr>
                    <td class=errormessage><?php echo $errorinlink; ?></td>
                </tr>
                <tr>
                    <?php
                    $homepagepriviewlink = "<a style=\"text-decoration:none;\" class=subtext  href='javascript:void(0)' onclick=\"showHomePagepreview('" . $templateid . "');\">";
                    $subpagepriviewlink = "<a style=\"text-decoration:none;\" class=subtext  href='javascript:void(0)' onclick=\"showSubPagepreview('" . $templateid . "');\">";
                    ?>
                    <td >
                        <form name="frmAddlinks" method="POST" action="addlinks.php?urltype=1">
                            <input type=hidden id=linkcount name=linkcount value="0">
                            <input type=hidden id=linkname name=linkname >
                            <input type=hidden id=postback name=postback >
                            <input type=hidden id=linktype name=linktype >
                            <table width="100%" align=center border=0 cellpadding="0" cellspacing="0">
                                <tr>
                                    <td width="30%">&nbsp;</td>
                                    <td  align=left class=maintext>
                                        <input type="radio" name="site" id="site" value="simple" checked onclick="clicksimple();">I would like a simple one-page website&nbsp;&nbsp;[<?php echo $homepagepriviewlink; ?>&nbsp;<b>Preview </b>&nbsp;</a> ]

                                                        
                                    </td>
                                </tr>
                                <tr> 
                                    <td width="30%">&nbsp;</td>
                                        <td align=left class=maintext>
                                                       
                                        <input type="radio" name="site" id="site_multiple" value="multiple"  onclick="clickmultiple();">
                                        I would like a website with multiple web pages
                                                              
                                    </td>
                                </tr>
                                <tr><td colspan=2>&nbsp;</td></tr>
                                                     
                                    <tr>
                                                    
                                    <td colspan=2 align=center>

                                        <div id=multiplepages>
                                            <FIELDSET style="width:600px">
                                                <LEGEND class=maintextbigbold>Add new web page to your site </LEGEND>
                                                <table width="100%" align=center border=0>
                                                    <tr>
                                                        <td>&nbsp;</td>
                                                    </tr>
                                                    <tr>
                                                        <td align=right><input name=pagename id=pagename type=text size=40 maxlength="20" class=textbox></td>
                                                        <td align=left>&nbsp;<input class=button type=button name="addapage" value="Add web page"  onclick="addpage(0,'');"></td>
                                                    </tr>
                                                    <tr align=right>
                                                            <td class=maintext colspan=2 width="100%" align=center>
                                                                                              
                                                            <input type=radio name=ptype id=ptype value="homepage" checked>add a home page template&nbsp;&nbsp;[<?php echo $homepagepriviewlink; ?>&nbsp;<b>Preview</b>&nbsp; </a> ]
                                                            <input type=radio name=ptype id=ptype1 value="subpage">add a sub page template&nbsp;&nbsp;[<?php echo $subpagepriviewlink; ?>&nbsp;<b>Preview</b>&nbsp; </a>]
                                                        </td>
                                                    </tr>
                                                    <tr><td colspan=2>&nbsp;</td></tr>
                                                    <tr>
                                                        <td colspan=2 >
                                                            <table width="100%" border=0 cellpadding="0" cellspacing="0">
                                                                <tr><td class=maintextbigbold align=left colspan=4>Current pages in your site</td></tr>
                                                                <tr><td colspan=4>&nbsp;</td></tr>
                                                                    <tr>
                                                                                                              
                                                                    <td >
                                                                        <table border=0 width="100%"><tr><td width="30%" align=left class=maintextbigbold>Page</td><td width="40%" align=left class=maintextbigbold>Actions</td>
                                                                                <td align=left class=maintextbigbold>Template type</td>
                                                                            </tr></table>
                                                                    </td>
                                                                </tr>
                                                                    <tr>
                                                                                                              
                                                                        <td id=addedpages>
                                                                                                              
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </FIELDSET>
                                        </div>
                                                               
                                    </td>
                                </tr>
                                                    
                                                    
                                <tr>
                                    <td colspan=2 align=center><input type=submit name=btncontinue value="continue" class=button onclick="clickcontinue(this.id);">
                                        <input class=button type=button value="preview" onclick="showpreview(this.id);" >
                                        <input class=button type=submit name=btnback  value="Back" onclick="clickback(this.id);" >
                                    </td>
                                </tr>
                            </table>
                                             
                        </form>
                    </td>
                </tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td>&nbsp;</td></tr>
            </table>
        </td>
    </tr>
</table>

<?php
include "includes/userfooter.php";
/* This code required when the user entered this page by clicking back button from  any other page
 *  when a user add a page for his/her site 'session_sitelinks' will contain the page added by them.if he/she would like
 *  one web page site 'session_sitelinks' will conatin 'Home'.
 *  assign 'session_oldsitelinks' to the 'session_sitelinks' .this will required for deleting a page when user remove a page
 *   build a javascript array that contains page name
 *   build a avascript array that contains page type
 * 
 */

if ($urltype == "back" and $_SESSION['session_sitelinks'] != "Home") {
    $_SESSION['session_oldsitelinks'] = $_SESSION['session_sitelinks'];
    $sitelinkarray = explode(",", $_SESSION['session_sitelinks']);
    $sitelinksforjs = "";
    foreach ($sitelinkarray as $key => $value) {
        $sitelinksforjs .= "linksarray[$key]=\"$value\" ;\n";
    }
    $sitelinksforjs .= "var numberoflinks=" . count($sitelinkarray) . " ;\n";
    $sitelinktypearray = explode(",", $_SESSION['session_sitelinkstype']);
    $sitelinkstypeforjs = "";
    foreach ($sitelinktypearray as $key => $value) {
        $sitelinkstypeforjs .= "linkstypearray[$key]=\"$value\" ;\n";
    }
    /* build the page block  from already created array by calling addpage()  by passing '1' as first parameter 
     * indicating the function is called automaticaly(ie not by click add a page button)
     */
    ?>

  <script>
        var linksarray=new Array();
        var linkstypearray=new Array();
        <?php echo $sitelinksforjs; ?>
        <?php echo $sitelinkstypeforjs; ?>
                 document.getElementById("linkcount").value="0";
                 document.getElementById("site_multiple").checked=true;
                 document.getElementById("multiplepages").style.display="";
                 for (z = 0;  z <numberoflinks;  z++){
                     document.getElementById("pagename").value=linksarray[z];
                     addpage(1,linkstypearray[z]);
                 }	 
                                           
   </script>
<?php
  
}else{
/* this means user either added a single web page option or not added a page
*/ 
?>
   <script>
           loaddefault();
           document.getElementById("multiplepages").style.display="none";
   </script>
<?php

}
?>
