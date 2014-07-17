<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2005-2006 ARMIA INC                                    |
// +----------------------------------------------------------------------+
// | This source file is a part of iScripts EasyCreate 3.1                 |
// +----------------------------------------------------------------------+
// | Authors: sudheesh<sudheesh@armia.com>      		              |
// |          									                          |
// +----------------------------------------------------------------------+
include "includes/session.php";
include "includes/config.php";
include "includes/function.php";

function HtmlToTpl1($htmlfile,$htmlpagetype,$templateid){
  if($htmlpagetype=="index"){
  			$htmlcontents=file_get_contents($htmlfile);
  			$headposition=strpos($htmlcontents,"<head>");
		    if(!$headposition){
		       $errormessage="Unable to locate &lthead> in your html file";
		        return $errormessage; 
		    }
  		    $backslashheadposition=strpos($htmlcontents,"</head>");
			if(!$backslashheadposition){
			     $errormessage="Unable to locate &lt/head> in your html file";
			     return $errormessage; 
			}
			  //start from <head> end to </head>
		   $headelements=substr($htmlcontents, $headposition,$backslashheadposition-$headposition+7);
  		   $newheadcontent="<head><meta name=\"key words\" content=\"{\$vsite_metadesc}\">";
		   $newheadcontent .="<meta name=\"description\" content=\"{\$vsite_metakey}\">";
		   $newheadcontent .="<title>{\$vsite_title}</title>";
           $newheadcontent .="<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
           $newheadcontent .="<style>{\$vsitecolor};</style>";
           $htmlcontents=str_replace($headelements,$newheadcontent,$htmlcontents );
		   
		   //check for logo
		   $logoposition=strpos($htmlcontents,"tp_logoimage.jpg");
		   if(!$logoposition){
		     $errormessage="Unable to locate tp_logoimage.jpg in your html file";
			 return $errormessage; 
		   }
		   $htmlcontents=str_replace("tp_logoimage.jpg","{\$vlogoband} ",$htmlcontents );
		   //check for company
		   $compposition=strpos($htmlcontents,"tp_company.jpg");
		   if(!$compposition){
		     $errormessage="Unable to locate tp_company.jpg in your html file";
			 return $errormessage; 
		   }
		   $htmlcontents=str_replace("tp_company.jpg","{\$vcompanyband} ",$htmlcontents );
		   //check for caption
		   $captionposition=strpos($htmlcontents,"tp_caption.jpg");
		   if(!$captionposition){
		     $errormessage="Unable to locate tp_caption.jpg in your html file";
			 return $errormessage; 
		   }
		   $htmlcontents=str_replace("tp_caption.jpg","{\$captionband} ",$htmlcontents );
		   //check for links
		  
		   $startlinkposition=strpos($htmlcontents,"start linkarea");
		   if(!$startlinkposition){
		     $errormessage="Unable to locate 'start linkarea' in your html file";
			 return $errormessage; 
		   }
		   $endlinkposition=strpos($htmlcontents,"end linkarea");
		   if(!$endlinkposition){
		     $errormessage="Unable to locate 'end linkarea' in your html file";
			 return $errormessage; 
		   }
		    $linkelements=substr($htmlcontents, $startlinkposition,$endlinkposition-$startlinkposition);
			$linkelemntswithouthtmlcomment=substr($linkelements, strpos($linkelements,"-->")+3,strpos($linkelements,"<!--")-strpos($linkelements,"-->")-3);
			
			$newlinkcontent="{\$vsite_links}";
			$htmlcontents=str_replace($linkelemntswithouthtmlcomment,$newlinkcontent,$htmlcontents );
			//check for editable area
			$starteditableareaposition=strpos($htmlcontents,"start editablearea");
			if(!$starteditableareaposition){
		     $errormessage="Unable to locate 'start editablearea' in your html file";
			 return $errormessage; 
		    }
			$endeditableareaposition=strpos($htmlcontents,"end editablearea");
			if(!$endeditableareaposition){
		     $errormessage="Unable to locate 'end editablearea' in your html file";
			 return $errormessage; 
		    }
			$editableelements=substr($htmlcontents, $starteditableareaposition,$endeditableareaposition-$starteditableareaposition);
			$editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
			$neweditcontent="{\$vsite_editable}";
			$htmlcontents=str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlcontents );
			if(get_magic_quotes_gpc()==1){
			  $vlinks=$linkelemntswithouthtmlcomment;
			  $veditable=$editableelemntswithouthtmlcomment;
			}else{
			  $vlinks=addslashes($linkelemntswithouthtmlcomment);
			  $veditable=addslashes($editableelemntswithouthtmlcomment);
			}
			$fp=fopen("./".$_SESSION["session_template_dir"]."/$templateid/index.tpl","w");
			fputs($fp,$htmlcontents);
			fclose($fp); 
			$updateqrystr=" vlinks='".$vlinks."',veditable='".$veditable."'";
			//echo $editableelemntswithouthtmlcomment;
		   
  }else if($htmlpagetype=="sub"){
  			$htmlcontents=file_get_contents($htmlfile);
  			$headposition=strpos($htmlcontents,"<head>");
		    if(!$headposition){
		       $errormessage="Unable to locate &lthead> in your html file";
		        return $errormessage; 
		    }
  		    $backslashheadposition=strpos($htmlcontents,"</head>");
			if(!$backslashheadposition){
			     $errormessage="Unable to locate &lt/head> in your html file";
			     return $errormessage; 
			}
			  //start from <head> end to </head>
		   $headelements=substr($htmlcontents, $headposition,$backslashheadposition-$headposition+7);
  		   $newheadcontent="<head><meta name=\"key words\" content=\"{\$vsite_metadesc}\">";
		   $newheadcontent .="<meta name=\"description\" content=\"{\$vsite_metakey}\">";
		   $newheadcontent .="<title>{\$vsite_title}</title>";
           $newheadcontent .="<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
           $newheadcontent .="<style>{\$vsitecolor};</style>";
           $htmlcontents=str_replace($headelements,$newheadcontent,$htmlcontents );
		   
		   //check for logo
		   $logoposition=strpos($htmlcontents,"tp_innerlogoimage.jpg");
		   if(!$logoposition){
		     $errormessage="Unable to locate tp_innerlogoimage.jpg in your html file";
			 return $errormessage; 
		   }
		   $htmlcontents=str_replace("tp_innerlogoimage.jpg","{\$vinnserlogoband} ",$htmlcontents );
		   //check for company
		   $compposition=strpos($htmlcontents,"tp_innercompany.jpg");
		   if(!$compposition){
		     $errormessage="Unable to locate tp_innercompany.jpg in your html file";
			 return $errormessage; 
		   }
		   $htmlcontents=str_replace("tp_innercompany.jpg","{\$vinnercompanyband} ",$htmlcontents );
		   //check for caption
		   $captionposition=strpos($htmlcontents,"tp_innercaption.jpg");
		   if(!$captionposition){
		     $errormessage="Unable to locate tp_innercaption.jpg in your html file";
			 return $errormessage; 
		   }
		   $htmlcontents=str_replace("tp_innercaption.jpg","{\$innercaptionband} ",$htmlcontents );
		   //check for links
		  
		   $startlinkposition=strpos($htmlcontents,"start linkarea");
		   if(!$startlinkposition){
		     $errormessage="Unable to locate 'start linkarea' in your html file";
			 return $errormessage; 
		   }
		   $endlinkposition=strpos($htmlcontents,"end linkarea");
		   if(!$endlinkposition){
		     $errormessage="Unable to locate 'end linkarea' in your html file";
			 return $errormessage; 
		   }
		    $linkelements=substr($htmlcontents, $startlinkposition,$endlinkposition-$startlinkposition);
			$linkelemntswithouthtmlcomment=substr($linkelements, strpos($linkelements,"-->")+3,strpos($linkelements,"<!--")-strpos($linkelements,"-->")-3);
			
			$newlinkcontent="{\$vsite_links}";
			$htmlcontents=str_replace($linkelemntswithouthtmlcomment,$newlinkcontent,$htmlcontents );
			//check for editable area
			$starteditableareaposition=strpos($htmlcontents,"start editablearea");
			if(!$starteditableareaposition){
		     $errormessage="Unable to locate 'start editablearea' in your html file";
			 return $errormessage; 
		    }
			$endeditableareaposition=strpos($htmlcontents,"end editablearea");
			if(!$endeditableareaposition){
		     $errormessage="Unable to locate 'end editablearea' in your html file";
			 return $errormessage; 
		    }
			$editableelements=substr($htmlcontents, $starteditableareaposition,$endeditableareaposition-$starteditableareaposition);
			$editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
			$neweditcontent="{\$vsubsite_editable}";
			$htmlcontents=str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlcontents );
			//echo $editableelemntswithouthtmlcomment;
		
		   if(get_magic_quotes_gpc()==1){
			  $vlinks=$linkelemntswithouthtmlcomment;
			  $veditable=$editableelemntswithouthtmlcomment;
			}else{
			  $vlinks=addslashes($linkelemntswithouthtmlcomment);
			  $veditable=addslashes($editableelemntswithouthtmlcomment);
			}
			$fp=fopen("./".$_SESSION["session_template_dir"]."/$templateid/subpage.tpl","w");
			fputs($fp,$htmlcontents);
			fclose($fp);
			$updateqrystr=" vsub_links='".$vlinks."',vsub_editable='".$veditable."'";
  }
  
    $qry="update tbl_template_mast set ".$updateqrystr." where ntemplate_mast='".$templateid."'";
	//echo $qry;
	mysql_query($qry);
    return $htmlcontents;

}
function HtmlToTpl($templateid,$relativepathtoroot){
      /*craete index.tpl*/
				           $htmlfile=$relativepathtoroot.$_SESSION["session_template_dir"]."/$templateid/index.htm";
						   if(!is_file($htmlfile)){
						          $errormessage="index.htm file doesnot exist";
						        return $errormessage;
						   }
				  		   $htmlcontents=file_get_contents($htmlfile);
				  		   $headposition=strpos($htmlcontents,"<head>");
						   if(!$headposition){
						        $errormessage="Unable to locate &lthead> in your html file";
						        return $errormessage; 
						   }
				  		   $backslashheadposition=strpos($htmlcontents,"</head>");
						   if(!$backslashheadposition){
							     $errormessage="Unable to locate &lt/head> in your html file";
							     return $errormessage; 
						   }
						   
						  //start from <head> end to </head>
						   $headelements=substr($htmlcontents, $headposition,$backslashheadposition-$headposition+7);
				  		   $newheadcontent="<head><meta name=\"key words\" content=\"{\$vsite_metadesc}\">";
						   $newheadcontent .="<meta name=\"description\" content=\"{\$vsite_metakey}\">";
						   $newheadcontent .="<title>{\$vsite_title}</title>";
				           $newheadcontent .="<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
				           $newheadcontent .="<style>{\$vsitecolor};</style>";
				           $htmlcontents=str_replace($headelements,$newheadcontent,$htmlcontents );
						   
						   //check for logo
						   $logoposition=strpos($htmlcontents,"tp_logoimage.jpg");
						   if(!$logoposition){
						     $errormessage="Unable to locate tp_logoimage.jpg in your html file";
							 return $errormessage; 
						   }
						   $htmlcontents=str_replace("tp_logoimage.jpg","{\$vlogoband} ",$htmlcontents );
						   //check for company
						   $compposition=strpos($htmlcontents,"tp_company.jpg");
						   if(!$compposition){
						     $errormessage="Unable to locate tp_company.jpg in your html file";
							 return $errormessage; 
						   }
						   $htmlcontents=str_replace("tp_company.jpg","{\$vcompanyband} ",$htmlcontents );
						   //check for caption
						   $captionposition=strpos($htmlcontents,"tp_caption.jpg");
						   if(!$captionposition){
						     $errormessage="Unable to locate tp_caption.jpg in your html file";
							 return $errormessage; 
						   }
						   $htmlcontents=str_replace("tp_caption.jpg","{\$captionband} ",$htmlcontents );
						   //check for links
						  
						   $startlinkposition=strpos($htmlcontents,"start linkarea");
						   if(!$startlinkposition){
						     $errormessage="Unable to locate 'start linkarea' in your html file";
							 return $errormessage; 
						   }
						   $endlinkposition=strpos($htmlcontents,"end linkarea");
						   if(!$endlinkposition){
						     $errormessage="Unable to locate 'end linkarea' in your html file";
							 return $errormessage; 
						   }
						    $linkelements=substr($htmlcontents, $startlinkposition,$endlinkposition-$startlinkposition);
							$linkelemntswithouthtmlcomment=substr($linkelements, strpos($linkelements,"-->")+3,strpos($linkelements,"<!--")-strpos($linkelements,"-->")-3);
							
							$newlinkcontent="{\$vsite_links}";
							$htmlcontents=str_replace($linkelemntswithouthtmlcomment,$newlinkcontent,$htmlcontents );
							//check for editable area
							$starteditableareaposition=strpos($htmlcontents,"start editablearea");
							if(!$starteditableareaposition){
						     $errormessage="Unable to locate 'start editablearea' in your html file";
							 return $errormessage; 
						    }
							$endeditableareaposition=strpos($htmlcontents,"end editablearea");
							if(!$endeditableareaposition){
						     $errormessage="Unable to locate 'end editablearea' in your html file";
							 return $errormessage; 
						    }
							$editableelements=substr($htmlcontents, $starteditableareaposition,$endeditableareaposition-$starteditableareaposition);
							$editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
							$neweditcontent="{\$vsite_editable}";
							$htmlcontents=str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlcontents );
							if(get_magic_quotes_gpc()==1){
							  $vlinks=$linkelemntswithouthtmlcomment;
							  $veditable=$editableelemntswithouthtmlcomment;
							}else{
							  $vlinks=addslashes($linkelemntswithouthtmlcomment);
							  $veditable=addslashes($editableelemntswithouthtmlcomment);
							}
							
							$indexhtmlcontents=$htmlcontents;
							
			   
		   /*craete subpage.tpl*/
		   
			            $htmlfile=$relativepathtoroot.$_SESSION["session_template_dir"]."/$templateid/sub.htm";
						if(!is_file($htmlfile)){
						          $errormessage="sub.htm file doesnot exist";
						        return $errormessage;
						}
			  			$htmlcontents=file_get_contents($htmlfile);
			  			$headposition=strpos($htmlcontents,"<head>");
					    if(!$headposition){
					       $errormessage="Unable to locate &lthead> in your html file";
					        return $errormessage; 
					    }
			  		    $backslashheadposition=strpos($htmlcontents,"</head>");
						if(!$backslashheadposition){
						     $errormessage="Unable to locate &lt/head> in your html file";
						     return $errormessage; 
						}
						  //start from <head> end to </head>
					   $headelements=substr($htmlcontents, $headposition,$backslashheadposition-$headposition+7);
			  		   $newheadcontent="<head><meta name=\"key words\" content=\"{\$vsite_metadesc}\">";
					   $newheadcontent .="<meta name=\"description\" content=\"{\$vsite_metakey}\">";
					   $newheadcontent .="<title>{\$vsite_title}</title>";
			           $newheadcontent .="<link href=\"style.css\" rel=\"stylesheet\" type=\"text/css\">";
			           $newheadcontent .="<style>{\$vsitecolor};</style>";
			           $htmlcontents=str_replace($headelements,$newheadcontent,$htmlcontents );
					   
					   //check for logo
					   $logoposition=strpos($htmlcontents,"tp_innerlogoimage.jpg");
					   if(!$logoposition){
					     $errormessage="Unable to locate tp_innerlogoimage.jpg in your html file";
						 return $errormessage; 
					   }
					   $htmlcontents=str_replace("tp_innerlogoimage.jpg","{\$vinnserlogoband} ",$htmlcontents );
					   //check for company
					   $compposition=strpos($htmlcontents,"tp_innercompany.jpg");
					   if(!$compposition){
					     $errormessage="Unable to locate tp_innercompany.jpg in your html file";
						 return $errormessage; 
					   }
					   $htmlcontents=str_replace("tp_innercompany.jpg","{\$vinnercompanyband} ",$htmlcontents );
					   //check for caption
					   $captionposition=strpos($htmlcontents,"tp_innercaption.jpg");
					   if(!$captionposition){
					     $errormessage="Unable to locate tp_innercaption.jpg in your html file";
						 return $errormessage; 
					   }
					   $htmlcontents=str_replace("tp_innercaption.jpg","{\$innercaptionband} ",$htmlcontents );
					   //check for links
					  
					   $startlinkposition=strpos($htmlcontents,"start linkarea");
					   if(!$startlinkposition){
					     $errormessage="Unable to locate 'start linkarea' in your html file";
						 return $errormessage; 
					   }
					   $endlinkposition=strpos($htmlcontents,"end linkarea");
					   if(!$endlinkposition){
					     $errormessage="Unable to locate 'end linkarea' in your html file";
						 return $errormessage; 
					   }
					    $linkelements=substr($htmlcontents, $startlinkposition,$endlinkposition-$startlinkposition);
						$linkelemntswithouthtmlcomment=substr($linkelements, strpos($linkelements,"-->")+3,strpos($linkelements,"<!--")-strpos($linkelements,"-->")-3);
						
						$newlinkcontent="{\$vsite_links}";
						$htmlcontents=str_replace($linkelemntswithouthtmlcomment,$newlinkcontent,$htmlcontents );
						//check for editable area
						$starteditableareaposition=strpos($htmlcontents,"start editablearea");
						if(!$starteditableareaposition){
					     $errormessage="Unable to locate 'start editablearea' in your html file";
						 return $errormessage; 
					    }
						$endeditableareaposition=strpos($htmlcontents,"end editablearea");
						if(!$endeditableareaposition){
					     $errormessage="Unable to locate 'end editablearea' in your html file";
						 return $errormessage; 
					    }
						$editableelements=substr($htmlcontents, $starteditableareaposition,$endeditableareaposition-$starteditableareaposition);
						$editableelemntswithouthtmlcomment=substr($editableelements, strpos($editableelements,"-->")+3,strpos($editableelements,"<!--")-strpos($editableelements,"-->")-3);
						$neweditcontent="{\$vsubsite_editable}";
						$htmlcontents=str_replace($editableelemntswithouthtmlcomment,$neweditcontent,$htmlcontents );
						$subpagehtmlcontents=$htmlcontents;
						//echo $editableelemntswithouthtmlcomment;
						
			//create tpl files
		    $fp=fopen($relativepathtoroot.$_SESSION["session_template_dir"]."/$templateid/index.tpl","w");
			fputs($fp,$indexhtmlcontents);
			fclose($fp);
			
			$fp=fopen($relativepathtoroot.$_SESSION["session_template_dir"]."/$templateid/subpage.tpl","w");
			fputs($fp,$subpagehtmlcontents);
			fclose($fp);
			
			
		
		   if(get_magic_quotes_gpc()==1){
			  $vsublinks=$linkelemntswithouthtmlcomment;
			  $vsubeditable=$editableelemntswithouthtmlcomment;
			}else{
			  $vsublinks=addslashes($linkelemntswithouthtmlcomment);
			  $vsubeditable=addslashes($editableelemntswithouthtmlcomment);
			}
			
			
			
			$updateqrystr=" vlinks='".$vlinks."',veditable='".$veditable."',vsub_links='".$vsublinks."',vsub_editable='".$vsubeditable."'";
            $qry="update tbl_template_mast set ".$updateqrystr." where ntemplate_mast='".$templateid."'";
	        mysql_query($qry);
    return "success";

}
  //$Html2Tpl=HtmlToTpl("./templates/4/index.htm","index","4"); 
   //$Html2Tpl=HtmlToTpl("./templates/4/sub.htm","sub","4");
   $Html2Tpl=HtmlToTpl("4","./");
  //echo $Html2Tpl;
?>