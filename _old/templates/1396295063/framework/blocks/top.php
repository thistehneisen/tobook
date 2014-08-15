<?php
/**
* @subpackage  ol_anteez Template
*/

defined('_JEXEC') or die;

//top modules columns
$top1 = $this->countModules('top1');
$top2 = $this->countModules('top2');
$top3 = $this->countModules('top3');
$top4 = $this->countModules('top4');

//columns layout
$top_four_columns_layout = $this->params->get('top_four_columns_layout');
$top_three_columns_layout = $this->params->get('top_three_columns_layout');
$top_two_columns_layout = $this->params->get('top_two_columns_layout');

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

if ($top1 || $top2 || $top3 || $top4 ){
?>

<section id="top">
<div class="container">

<?php /*four columns modules*/ 
if ($top1 && $top2 && $top3 && $top4) { ?>		
<div class="<?php echo $grid_4col_1; ?>"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>
<div class="<?php echo $grid_4col_2; ?>"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>
<div class="<?php echo $grid_4col_3; ?>"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>
<div class="<?php echo $grid_4col_4; ?>"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>				

<?php /*three columns modules*/ 
}
elseif (!$top1 && $top2 && $top3 && $top4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>		
<?php 
}
elseif ($top1 && !$top2 && $top3 && $top4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>		
<?php 
}
elseif ($top1 && $top2 && !$top3 && $top4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>		
<?php
}
elseif ($top1 && $top2 && $top3 && !$top4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>	

<?php /*two columns modules*/
}
elseif (!$top1 && !$top2 && $top3 && $top4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>			
<?php
}
elseif (!$top1 && $top2 && !$top3 && $top4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>		
<?php 
}
elseif (!$top1 && $top2 && $top3 && !$top4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>		
<?php 
}
elseif ($top1 && !$top2 && $top3 && !$top4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>		
<?php 
}
elseif ($top1 && $top2 && !$top3 && !$top4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>		
<?php 
} elseif ($top1 && !$top2 && !$top3 && $top4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>

<?php /*one column modules*/
}
elseif ($top1 && !$top2 && !$top3 && !$top4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="top1" style="block" /></div></div>		
<?php 
}
elseif (!$top1 && $top2 && !$top3 && !$top4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="top2" style="block" /></div></div>		
<?php 
}
elseif (!$top1 && !$top2 && $top3 && !$top4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="top3" style="block" /></div></div>			
<?php 
}
elseif (!$top1 && !$top2 && !$top3 && $top4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="top4" style="block" /></div></div>

<?php } ?>

<div class="clear separator"></div>
</div>	
</section>

<?php } ?>