<?php
/**
 * @package     Joomla.Administrator
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */defined('_JEXEC') or die;/**
 * This is a file to add template specific chrome to module rendering.  To use it you would
 * set the style attribute for the given module(s) include in your template to use the style
 * for each given modChrome function.
 */
function modChrome_no($module, &$params, &$attribs)
{
	if ($module->content) {
		echo $module->content;
	}
}

function modChrome_dark($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>
<div class="dark">
<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3> 
<?php endif; ?>
<?php echo $module->content; ?>
<div style="clear:both;"></div>
</div>

<?php endif;
}

function modChrome_grey($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>
<div class="grey">
<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3> 
<?php endif; ?>
<?php echo $module->content; ?>
<div style="clear:both;"></div>
</div>

<?php endif;
}

function modChrome_light($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>
<div class="white">
<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3> 
<?php endif; ?>
<?php echo $module->content; ?>
<div style="clear:both;"></div>
</div>

<?php endif;
}

function modChrome_red($module, &$params, &$attribs)
{

if (!empty ($module->content)) : ?>

<div class="red">
<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3> 
<?php endif; ?>
<?php echo $module->content; ?>
<div style="clear:both;"></div>
</div>

<?php endif;
}

function modChrome_green($module, &$params, &$attribs)
{

if (!empty ($module->content)) : ?>

<div class="green">
<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3> 
<?php endif; ?>
<?php echo $module->content; ?>
<div style="clear:both;"></div>
</div>

<?php endif;
}

function modChrome_blue($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>

<div class="blue">
<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3> 
<?php endif; ?>
<?php echo $module->content; ?>
<div style="clear:both;"></div>
</div>

<?php endif;
}
function modChrome_orange($module, &$params, &$attribs)
{
if (!empty ($module->content)) : ?>
<div class="orange">
<?php if ($module->showtitle != 0) : ?>
<h3><?php echo $module->title; ?></h3> 
<?php endif; ?>
<?php echo $module->content; ?>
<div style="clear:both;"></div>
</div>

<?php endif;
}

function modChrome_block($module, &$params, &$attribs)
{
	if ($module->content) {
		echo "<div class=\"module-container " . htmlspecialchars($params->get('moduleclass_sfx')) . "\">";
		if ($module->showtitle)
		{
			echo "<h3 class=\"module-title\">" . $module->title . "</h3>";
		}		
		echo "<div class=\"module-content\">".$module->content."</div>";
		echo "</div>";
	}
}
?>
