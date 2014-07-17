<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_content
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
include_once(JPATH_BASE.'/templates/'. $template->template .'/includes/functions.php');

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('behavior.caption');
?>
<div class="page-category page-category__<?php echo $this->pageclass_sfx;?>">

	<?php if ($this->params->get('show_page_heading', 1)) : ?>
	<div class="page_header">
		<?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('categoryPageHeading')); ?>
	</div>
	<?php endif;

	if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
		<div class="category_title">
			<h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
				<?php if ($this->params->get('show_category_title')) : ?>
				<span class="subheading-category"><?php echo $this->category->title;?></span>
				<?php endif; ?>
			</h2>
		</div>
	<?php endif;

	if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
	<div class="category_desc">
		<?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
			<img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
		<?php endif;
		if ($this->params->get('show_description') && $this->category->description) :
			echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category');
		endif; ?>
		<div class="clr"></div>
	</div>
	<?php endif;

	$leadingcount = 0;
	if (!empty($this->lead_items)) : ?>
	<div class="items-leading">
		<?php foreach ($this->lead_items as &$item) : ?>
		<div class="leading-<?php echo $leadingcount; ?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
			<?php
				$this->item = &$item;
				echo $this->loadTemplate('item');
			?>
		</div>
		<div class="clearfix"></div>
		<?php
			$leadingcount++;
		endforeach; ?>
	</div><!-- end items-leading -->
	<div class="clearfix"></div>
	<?php endif;

	$introcount = (count($this->intro_items));
	$counter = 0;
	if (!empty($this->intro_items)) : ?>
	<?php foreach ($this->intro_items as $key => &$item) : ?>
	<?php
		$rowcount = (((int) $key) % (int) $this->columns) + 1;
		$row = $counter / $this->columns;

		if ($rowcount == 1) : ?>
		<div class="items-row cols-<?php echo (int) $this->columns;?> <?php echo 'row-'.$row; ?> row-fluid">
		<?php endif; ?>
			<div class="span<?php echo round((12 / $this->columns));?>">
				<div class="item column-<?php echo $rowcount;?><?php echo $item->state == 0 ? ' system-unpublished' : null; ?>">
					<?php
					$this->item = &$item;
					echo $this->loadTemplate('item');
				?>
				</div><!-- end item -->
				<?php $counter++; ?>
			</div><!-- end spann -->
			<?php if (($rowcount == $this->columns) or ($counter == $introcount)): ?>			
		</div><!-- end row -->
			<?php endif; ?>
	<?php endforeach; ?>
	<?php endif; ?>
	
	<?php if (!empty($this->link_items)) : ?>
	<div class="items-more">
	<?php echo $this->loadTemplate('links'); ?>
	</div>
	<?php endif; ?>


	<?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
	<div class="category_children">
		<h3> <?php echo JTEXT::_('JGLOBAL_SUBCATEGORIES'); ?> </h3>
		<?php echo $this->loadTemplate('children'); ?> </div>
	<?php endif; ?>
	<?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
	
	<div class="pagination">
		<?php  if ($this->params->def('show_pagination_results', 1)) : ?>
		<p class="counter"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
		<?php endif; ?>
		<?php echo $this->pagination->getPagesLinks(); ?> </div>
	<?php  endif; ?>
</div>
