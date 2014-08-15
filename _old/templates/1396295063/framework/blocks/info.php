<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;

// modules columns
$info1 = $this->countModules('info1');
$info2 = $this->countModules('info2');
$info3 = $this->countModules('info3');
$info4 = $this->countModules('info4');

//columns layout
$info_four_columns_layout = $this->params->get('info_four_columns_layout');
$info_three_columns_layout = $this->params->get('info_three_columns_layout');
$info_two_columns_layout = $this->params->get('info_two_columns_layout');

//four columns grid

$grid_4col_1 = 'grid_6';
$grid_4col_2 = 'grid_6';
$grid_4col_3 = 'grid_6';
$grid_4col_4 = 'grid_6';

//three columns grid

$grid_3col_1 = 'grid_8';
$grid_3col_2 = 'grid_8';
$grid_3col_3 = 'grid_8';

//two columns grid

$grid_2col_1 = 'grid_12';
$grid_2col_2 = 'grid_12';

if ($info1 || $info2 || $info3 || $info4 ){
?>

<section id="info">
<div class="container">
<div class="clear separator"></div>

<?php /*four columns modules*/ 
if ($info1 && $info2 && $info3 && $info4) { ?>		
<div class="<?php echo $grid_4col_1; ?>"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>
<div class="<?php echo $grid_4col_2; ?>"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>
<div class="<?php echo $grid_4col_3; ?>"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>
<div class="<?php echo $grid_4col_4; ?>"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>				

<?php /*three columns modules*/ 
}
elseif (!$info1 && $info2 && $info3 && $info4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>		
<?php 
}
elseif ($info1 && !$info2 && $info3 && $info4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>		
<?php 
}
elseif ($info1 && $info2 && !$info3 && $info4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>		
<?php
}
elseif ($info1 && $info2 && $info3 && !$info4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>	

<?php /*two columns modules*/
}
elseif (!$info1 && !$info2 && $info3 && $info4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>			
<?php
}
elseif (!$info1 && $info2 && !$info3 && $info4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>		
<?php 
}
elseif (!$info1 && $info2 && $info3 && !$info4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>		
<?php 
}
elseif ($info1 && !$info2 && $info3 && !$info4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>		
<?php 
}
elseif ($info1 && $info2 && !$info3 && !$info4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>		
<?php 
} elseif ($info1 && !$info2 && !$info3 && $info4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>

<?php /*one column modules*/
}
elseif ($info1 && !$info2 && !$info3 && !$info4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="info1" style="block" /></div></div>		
<?php 
}
elseif (!$info1 && $info2 && !$info3 && !$info4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="info2" style="block" /></div></div>		
<?php 
}
elseif (!$info1 && !$info2 && $info3 && !$info4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="info3" style="block" /></div></div>			
<?php 
}
elseif (!$info1 && !$info2 && !$info3 && $info4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="info4" style="block" /></div></div>

<?php } ?>

<div class="clear"></div>
</div>	
</section>

<?php } ?>