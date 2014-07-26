<?php
/**
 * @package     Joomla.Site
 * @subpackage  com_users
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;


$app = JFactory::getApplication('site');
$template = $app->getTemplate(true);
include_once(JPATH_BASE.'/templates/'. $template->template .'/includes/functions.php');
?>
<div class="page-registration__complete page-registration__complete__<?php echo $this->pageclass_sfx;?>">
	<?php if ($this->params->get('show_page_heading')) : ?>
	<div class="page_header">
    <?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('categoryPageHeading')); ?>
  </div>
	<?php endif; ?>
</div>
