<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_search
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
include_once(JPATH_BASE.'/templates/'. $template->template .'/includes/functions.php');
?>

<div class="page page-search page-search__<?php echo $this->pageclass_sfx; ?>">

<?php if ($this->params->get('show_page_heading', 1)) : ?>
<div class="page_header">
    <?php if ($this->escape($this->params->get('page_heading'))) :?>
	<?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('categoryPageHeading')); ?>
    <?php else : ?>
    <?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_title'))), $template->params->get('categoryPageHeading')); ?>
    <?php endif; ?>
</div>
<?php endif; ?>


<?php echo $this->loadTemplate('form'); ?>
<?php if ($this->error == null && count($this->results) > 0) :
	echo $this->loadTemplate('results');
else :
	echo $this->loadTemplate('error');
endif; ?>
</div>
