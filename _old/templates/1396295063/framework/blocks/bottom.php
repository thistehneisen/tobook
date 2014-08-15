<?php
/**
 * @package     Joomla.Site
 * @subpackage  ol_anteez Template
 */

defined('_JEXEC') or die;


//top modules columns
$bottom1 = $this->countModules('bottom1');
$bottom2 = $this->countModules('bottom2');
$bottom3 = $this->countModules('bottom3');
$bottom4 = $this->countModules('bottom4');

//columns layout
$bottom_four_columns_layout = $this->params->get('bottom_four_columns_layout');
$bottom_three_columns_layout = $this->params->get('bottom_three_columns_layout');
$bottom_two_columns_layout = $this->params->get('bottom_two_columns_layout');


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

if ($bottom1 || $bottom2 || $bottom3 || $bottom4 ){
?>


<section id="bottom">
	<div class="container">
		
		
		
				
		<?php /*four columns modules*/ 
		if ($bottom1 && $bottom2 && $bottom3 && $bottom4) { ?>		
			<div class="<?php echo $grid_4col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>
			<div class="<?php echo $grid_4col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>
			<div class="<?php echo $grid_4col_3; ?>"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>
			<div class="<?php echo $grid_4col_4; ?>"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>				
			



			
		<?php /*three columns modules*/ 
		}
		elseif (!$bottom1 && $bottom2 && $bottom3 && $bottom4) { ?>
			<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>
			<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>
			<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>		
		<?php 
		}
		elseif ($bottom1 && !$bottom2 && $bottom3 && $bottom4) { ?>
			<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>
			<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>
			<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>		
		<?php 
		}
		elseif ($bottom1 && $bottom2 && !$bottom3 && $bottom4) { ?>
			<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>
			<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>
			<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>		
		<?php
		}
		elseif ($bottom1 && $bottom2 && $bottom3 && !$bottom4) { ?>
			<div class="<?php echo $grid_3col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>
			<div class="<?php echo $grid_3col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>
			<div class="<?php echo $grid_3col_3; ?>"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>	
				
		
		
		
		
		
		<?php /*two columns modules*/
		}
		elseif (!$bottom1 && !$bottom2 && $bottom3 && $bottom4) { ?>
			<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>
			<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>			
		<?php
		}
		elseif (!$bottom1 && $bottom2 && !$bottom3 && $bottom4) { ?>
			<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>
			<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>		
		<?php 
		}
		elseif (!$bottom1 && $bottom2 && $bottom3 && !$bottom4) { ?>
			<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>
			<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>		
		<?php 
		}
		elseif ($bottom1 && !$bottom2 && $bottom3 && !$bottom4) { ?>
			<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>
			<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>		
		<?php 
		}
		elseif ($bottom1 && $bottom2 && !$bottom3 && !$bottom4) { ?>
			<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>
			<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>		
		<?php 
		} elseif ($bottom1 && !$bottom2 && !$bottom3 && $bottom4) { ?>
			<div class="<?php echo $grid_2col_1; ?>"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>
			<div class="<?php echo $grid_2col_2; ?>"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>
			
			
		
		
		
		
		
		<?php /*one column modules*/
		}
		elseif ($bottom1 && !$bottom2 && !$bottom3 && !$bottom4) { ?>
			<div class="grid_24"><div class="block"><jdoc:include type="modules" name="bottom1" style="block" /></div></div>		
		<?php 
		}
		elseif (!$bottom1 && $bottom2 && !$bottom3 && !$bottom4) { ?>
			<div class="grid_24"><div class="block"><jdoc:include type="modules" name="bottom2" style="block" /></div></div>		
		<?php 
		}
		elseif (!$bottom1 && !$bottom2 && $bottom3 && !$bottom4) { ?>
			<div class="grid_24"><div class="block"><jdoc:include type="modules" name="bottom3" style="block" /></div></div>			
		<?php 
		}
		elseif (!$bottom1 && !$bottom2 && !$bottom3 && $bottom4) { ?>
			<div class="grid_24"><div class="block"><jdoc:include type="modules" name="bottom4" style="block" /></div></div>
			
		<?php } ?>
		
		<div class="clear"></div>
	</div>	
</section>

<?php } ?>
