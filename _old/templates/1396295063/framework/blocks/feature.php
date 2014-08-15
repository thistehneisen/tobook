<?php
/**
* @subpackage  ol_anteez Template
*/


defined('_JEXEC') or die;


//before-content modules columns
$breadcrumb = $this->countModules('breadcrumb');
$advert1 = $this->countModules('advert1');
$advert2 = $this->countModules('advert2');
$advert3 = $this->countModules('advert3');
$advert4 = $this->countModules('advert4');

//columns layout
$feature_four_columns_layout = $this->params->get('feature_four_columns_layout');
$feature_three_columns_layout = $this->params->get('feature_three_columns_layout');
$feature_two_columns_layout = $this->params->get('feature_two_columns_layout');

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

if ($advert1 || $advert2 || $advert3 || $advert4 ){
?>

<!-- start feature -->
<section id="feature">
<div class="container">
<?php if($breadcrumb){?>
<div class="<?php echo $breadcrumb_class; ?>">
<div class="block">
<jdoc:include type="modules" name="breadcrumb" style="none" />
</div>
</div>        
<?php } ?>
<?php /*four columns modules*/ 
if ($advert1 && $advert2 && $advert3 && $advert4) { ?>		
<div class="<?php echo $grid_4col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>
<div class="<?php echo $grid_4col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>
<div class="<?php echo $grid_4col_3; ?>"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>
<div class="<?php echo $grid_4col_4; ?>"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>				

<?php /*three columns modules*/ 
}
elseif (!$advert1 && $advert2 && $advert3 && $advert4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>		
<?php 
}
elseif ($advert1 && !$advert2 && $advert3 && $advert4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>		
<?php 
}
elseif ($advert1 && $advert2 && !$advert3 && $advert4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>		
<?php
}
elseif ($advert1 && $advert2 && $advert3 && !$advert4) { ?>
<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>
<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>
<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>	

<?php /*two columns modules*/
}
elseif (!$advert1 && !$advert2 && $advert3 && $advert4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>			
<?php
}
elseif (!$advert1 && $advert2 && !$advert3 && $advert4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>		
<?php 
}
elseif (!$advert1 && $advert2 && $advert3 && !$advert4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>		
<?php 
}
elseif ($advert1 && !$advert2 && $advert3 && !$advert4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>		
<?php 
}
elseif ($advert1 && $advert2 && !$advert3 && !$advert4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>		
<?php 
} elseif ($advert1 && !$advert2 && !$advert3 && $advert4) { ?>
<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>
<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>

<?php /*one column modules*/
}
elseif ($advert1 && !$advert2 && !$advert3 && !$advert4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="advert1" style="block" /></div></div>		
<?php 
}
elseif (!$advert1 && $advert2 && !$advert3 && !$advert4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="advert2" style="block" /></div></div>		
<?php 
}
elseif (!$advert1 && !$advert2 && $advert3 && !$advert4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="advert3" style="block" /></div></div>			
<?php 
}
elseif (!$advert1 && !$advert2 && !$advert3 && $advert4) { ?>
<div class="grid_24"><div class="block"><jdoc:include type="modules" name="advert4" style="block" /></div></div>

<?php } ?>

<div class="clear separator"></div>
</div>	
</section>

<?php } ?>