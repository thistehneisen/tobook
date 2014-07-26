<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;

//parameters
$footer_menu = '<jdoc:include type="modules" name="footer-menu" style="" />';
$footer_menu_on = $this->countModules('footer-menu');;
$footer_info = $this->params->get('footer_info');


//display footer element if only one element of footer is in use
if(!$footer_menu_on && $footer_info !=''){
$footer_element = $footer_info;
}
elseif($footer_menu_on && $footer_info ==''){
$footer_element = $footer_menu;
}

?>
<footer id="footer">
<div class="container"> 
<div class="clear separator"></div>
<div class="grid_12"><div class="block"><?php echo $footer_info; ?>
<br>
Designed by <a href="http://www.olwebdesign.com/" title="Visit olwebdesign.com!" target="blank">olwebdesign.com</a>
</div></div>
<div class="grid_12"><div class="block"><?php echo $footer_menu; ?></div></div>		

<div class="clear"></div>
</div>	
</footer>