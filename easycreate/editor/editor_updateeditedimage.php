<?php 
/*
 * file to update the image into the session value
 *  Authors: jinson<jinson.m@armia.com>
*/

include "../includes/session.php";

//$curPage = 'index';


$replaceid 	= $_POST['replaceid'];
$newimg 	= $_POST['newimg'];


$curPage = $_SESSION['siteDetails']['currentpage'] ;

if($replaceid != '' && $newimg != '') {
    $imgDet 		= explode('_',$replaceid);
    $panelId 		= $imgDet[1];


    $panelContent 	= $_SESSION['siteDetails'][$curPage]['panels'][$panelId];

    $doc 	= new DOMDocument();
    @$doc->loadHTML($panelContent);
    $tags 	= $doc->getElementsByTagName('img');

    foreach ($tags as $tag) {
        if($tag->getAttribute('id') == $replaceid) {
            $tag->setAttribute("src", $newimg);
            // To set logo in session
            if($tag->getAttribute('data-type') == 'logo'){
                $_SESSION['siteDetails']['siteInfo']['logoName'] = $newimg;
            }
        }
    }
    $panelContent = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
    $_SESSION['siteDetails'][$curPage]['panels'][$panelId] = $panelContent;


    echo "success";
}


?>