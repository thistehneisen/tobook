<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;

//top modules columns
$main_content =	'<jdoc:include type="component" />';
$side_left = $this->countModules('left');
$side_right = $this->countModules('right');

//defaine style for main content "block" div
$main_content_block_style = 'style="padding-bottom:0;"';

//columns layout
$main_content_three_columns_layout = $this->params->get('main_content_three_columns_layout');
$main_content_two_columns_layout = $this->params->get('main_content_two_columns_layout');
?>

<section id="content">
<div class="container">	

<?php 

//two sidebars are in use
if ($side_left && $side_right)  { ?>

<div class="grid_6 left"><div class="block"><jdoc:include type="modules" name="left" style="block" /></div></div>
<div class="grid_12"><div class="block" <?php echo $main_content_block_style; ?>><?php echo $main_content; ?></div></div>
<div class="grid_6 right"><div class="block"><jdoc:include type="modules" name="right" style="block" /></div></div>

<?php } 

//two sidebars are in use
elseif ($side_left || $side_right) {
   

//check wich sidebar is in use and show it
if($side_left && !$side_right) {				
$sidebar = 'left';
$sidebar_class = 'left';
}
elseif(!$side_left && $side_right) {
$sidebar = 'right';
$sidebar_class = 'right';
}

{ ?>

<div class="grid_18"><div class="block" <?php echo $main_content_block_style; ?>><?php echo $main_content; ?></div></div>
<div class="grid_6 <?php echo $sidebar_class; ?>"><div class="block"><jdoc:include type="modules" name="<?php echo $sidebar ?>" style="block" /></div></div>

   
<?php }

?>

<?php }
else { ?>

<div class="grid_24"><div class="block" <?php echo $main_content_block_style; ?>><?php echo $main_content; ?></div></div>

<?php
}		
?>

<div class="clear"></div>
</div>	
</section>