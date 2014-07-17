<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

JHtml::_('behavior.keepalive');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
include_once(JPATH_BASE.'/templates/'. $template->template .'/includes/functions.php');
?>
<div class="page-remind page-remind__<?php echo $this->pageclass_sfx?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page_header">
		<?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('categoryPageHeading')); ?>
	</div>
	<?php endif; ?>

	<form id="user-registration" action="<?php echo JRoute::_('index.php?option=com_users&task=remind.remind'); ?>" method="post" class="form-validate">
		<fieldset>
			<?php foreach ($this->form->getFieldsets() as $fieldset): ?>
			<p><?php echo JText::_($fieldset->label); ?></p>

				<?php foreach ($this->form->getFieldset($fieldset->name) as $name => $field): ?>
						<?php echo $field->label; ?>
						<?php echo $field->input; ?>
					<?php endforeach; ?>
			<?php endforeach; ?>
		</fieldset>
			<button type="submit" class="btn btn-primary validate"><?php echo JText::_('JSUBMIT'); ?></button>
			<?php echo JHtml::_('form.token'); ?>
	</form>
</div>
