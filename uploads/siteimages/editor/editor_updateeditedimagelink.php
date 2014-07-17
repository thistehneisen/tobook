<?php 
/*
 * file to update the image into the session value
 *  Authors: jinson<jinson.m@armia.com>
 */

include "../includes/session.php";

exit();
/* 
 *  need to modify the code, because it redirect to the link of the image when page refresh
 */
//$curPage = 'index';

 
$replaceid 	= $_POST['replaceid'];
$imgLink 	= $_POST['imgLink'];
$curPage 	= $_SESSION['siteDetails']['currentpage'];

if($replaceid != '' && $imgLink != ''){

	
	$imgDet 		= explode('_',$replaceid);
	$panelId 		= $imgDet[1];
	$panelContent 	= $_SESSION['siteDetails'][$curPage]['panels'][$panelId];

	$doc 	= new DOMDocument();
	@$doc->loadHTML($panelContent);
	$tags 	= $doc->getElementsByTagName('img');
	
	$newLink = $doc->createElement('a');
	$newLink->setAttribute('href',$imgLink);
		 
	
	foreach ($tags as $tag) {
		if($tag->getAttribute('id') == $replaceid){
			 
			 //Clone our created div
    		$newLinkClone = $newLink->cloneNode();
   			//Replace image with this wrapper div
    		$tag->parentNode->replaceChild($newLinkClone,$tag);
    		//Append this image to wrapper div
    		$newLinkClone->appendChild($tag);
			
			
			//$tag->setAttribute("src", $newimg); 
		}
	}
	$panelContent = preg_replace('~<(?:!DOCTYPE|/?(?:html|body))[^>]*>\s*~i', '', $doc->saveHTML());
 	$_SESSION['siteDetails'][$curPage]['panels'][$panelId] = $panelContent; 		
	echo "success";
}


?>