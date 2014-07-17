<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;

//general parameters
$logo_type = $this->params->get('logo_type');
$logo_image = $this->params->get('logo_image');
$logo_image_width = $this->params->get('logo_image_width');
$logo_image_height = $this->params->get('logo_image_height');

//style parameters
$color_custom_style	= $this->params->get('color_custom_style');
$color_text	= $this->params->get('color_text');
$color_links = $this->params->get('color_links');
$color_links_hover = $this->params->get('color_links_hover');
$custom_css	= $this->params->get('custom_css');
$body_bg_color = $this->params->get('body_bg_color');
$body_bgim_color = $this->params->get('body_bgim_color');
$bgim_color = $this->params->get('bgim_color');

//page container
$page_width = $this->params->get('page_width');
$page_align = $this->params->get('page_align');


/*	Layout parameters */
if($page_align =='left'){
$align_value = 'float:left;margin:0;';
}
elseif($page_align =='right'){
$align_value = 'float:right;margin:0;';
}
else {
$align_value = 'margin:0 auto;';	
}

//page container
$doc->addStyleDeclaration('.container{width:'.$page_width.';'.$align_value.'}'); 

/*	Logo */
if($logo_type =='text'){
$doc->addStyleDeclaration('#logo-text{margin: 0px;}'); 
}
elseif($logo_type =='image'){
$doc->addStyleDeclaration('#logo-image a{background:url('.$base_url.'/'.$logo_image.') no-repeat left top;width:'.$logo_image_width.';height:'.$logo_image_height.';margin: 0px;}');
} 

if($body_bgim_color !='') {
$doc->addStyleDeclaration('body{ background-image:url('.$body_bgim_color.');}');
}

if($bgim_color =='show'){
$doc->addStyleDeclaration('body{ background-image:url('.$base_url.'/templates/ol_anteez/images/bg.jpg);}');
}

/*	Custom css */
if($custom_css != ''){
$doc->addStyleDeclaration($custom_css);
}