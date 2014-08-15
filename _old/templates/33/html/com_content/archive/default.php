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

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
JHtml::_('behavior.caption');
?>
<div class="page-archive page-archive__<?php echo $this->pageclass_sfx;?>">

<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page_header">
	<?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('blogPageHeading')); ?>
</div>

<?php endif; ?>
			<?php if ($this->params->get('filter_field') != 'hide') : ?>
<form id="adminForm" action="<?php echo JRoute::_('index.php')?>" method="post" class="form-inline page-archive_filters">
	<fieldset class="filters">
		<div class="filter-search">
			<label class="filter-search-lbl" for="filter-search"><?php echo JText::_('COM_CONTENT_'.$this->params->get('filter_field').'_FILTER_LABEL').'&#160;'; ?></label>
			<input type="text" name="filter-search" id="filter-search" value="<?php echo $this->escape($this->filter); ?>" class="inputbox span2" onchange="document.getElementById('adminForm').submit();" />
			

			<?php echo $this->form->monthField; ?>
			<?php echo $this->form->yearField; ?>
			<?php echo $this->form->limitField; ?>

			<div class="clearfix"></div>

			<button type="submit" class="btn btn-primary"><?php echo JText::_('JGLOBAL_FILTER_BUTTON'); ?></button>
		</div>
		
		<input type="hidden" name="view" value="archive" />
		<input type="hidden" name="option" value="com_content" />
		<input type="hidden" name="limitstart" value="0" />
	</fieldset>

</form>
<?php endif; ?>
	<?php echo $this->loadTemplate('items'); ?>
</div>