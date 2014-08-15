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
require_once( JPATH_ROOT . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_komento' . DIRECTORY_SEPARATOR . 'bootstrap.php' );

JHtml::addIncludePath(JPATH_COMPONENT . '/helpers');
$params = $this->params;
?>

<div id="page-archive_items">
	<?php foreach ($this->items as $i => $item) : ?>
		<?php $info = $item->params->get('info_block_position', 0); ?>
		<div class="row<?php echo $i % 2; ?>">
			<div class="item">
				<div class="item_header">
					<?php echo '<'. $template->params->get('categoryItemHeading') .' class="item_title">'; ?>
						<?php if ($params->get('link_titles')) : ?>
							<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug)); ?>"> <?php echo wrap_with_span($this->escape($item->title)); ?></a>
						<?php else: ?>
							<?php echo wrap_with_span($this->escape($item->title)); ?>
						<?php endif; ?>
					<?php echo '</'. $template->params->get('categoryItemHeading') .'>'; ?>
				</div>

	<?php if ($params->get('show_tags', 1) && !empty($item->tags)) : ?>
		<?php $item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>

		<?php echo $item->tagLayout->render($item->tags->itemTags); ?>
	<?php endif; ?>
			<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date')
				|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category')); ?>
			<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
				<div class="item_info">
					<dl class="item_info_dl">

					<!-- <dt class="article-info-term">
						<?php /*echo JText::_('COM_CONTENT_ARTICLE_INFO');*/ ?>
					</dt> -->

					<?php if ($params->get('show_author') && !empty($item->author )) : ?>
						<dd>
							<div class="item_createdby">
							<?php $author = $item->author; ?>
							<?php $author = ($item->created_by_alias ? $item->created_by_alias : $author); ?>
								<?php if (!empty($item->contactid ) && $params->get('link_author') == true) : ?>
									<?php echo JText::sprintf(
									'TPL_BY',
									JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id='.$item->contactid), $author)
									); ?>
								<?php else :?>
									<?php echo JText::sprintf('TPL_BY', $author); ?>
								<?php endif; ?>
							</div>
						</dd>
					<?php endif; ?>

					<?php if ($params->get('show_parent_category') && !empty($item->parent_slug)) : ?>
						<dd>
							<div class="item_parent-category-name">
								<?php	$title = $this->escape($item->parent_title);
								$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($item->parent_slug)).'">' . $title . '</a>'; ?>
								<?php if ($params->get('link_parent_category') && !empty($item->parent_slug)) : ?>
									<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
								<?php else : ?>
									<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
								<?php endif; ?>
							</div>
						</dd>
					<?php endif; ?>
					<?php if ($params->get('show_category')) : ?>
						<dd>
							<div class="item_category-name">
								<?php $title = $this->escape($item->category_title);
								$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug)).'">' . $title . '</a>'; ?>
								<?php if ($params->get('link_category') && $item->catslug) : ?>
									<?php echo JText::sprintf('TPL_IN', $url); ?>
								<?php else : ?>
									<?php echo JText::sprintf('TPL_IN', $title); ?>
								<?php endif; ?>
							</div>
						</dd>
					<?php endif; ?>

					<?php if ($params->get('show_publish_date')) : ?>
						<dd>
							<div class="item_published">
								<?php echo JText::sprintf('TPL_ON', JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
							</div>
						</dd>
					<?php endif; ?>

					<?php if ($info == 0): ?>
						<?php if ($params->get('show_modify_date')) : ?>
							<dd>
								<div class="item_modified">
									<?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
								</div>
							</dd>
						<?php endif; ?>
						<?php if ($params->get('show_create_date')) : ?>
							<dd>
								<div class="item_create">
									<?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC3'))); ?>
								</div>
							</dd>
						<?php endif; ?>

						<?php if ($params->get('show_hits')) : ?>
							<dd>
								<div class="item_hits">
									<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $item->hits); ?>
								</div>
							</dd>
						<?php endif; ?>

						<?php if (Komento::loadApplication( 'com_content')) : ?>
							<dd class="komento">
								<?php echo Komento::commentify( 'com_content', $item) ?>
							</dd>
						<?php endif; ?>

					<?php endif; ?>
					</dl>
				</div>
			<?php endif; ?>

			
<?php $images = json_decode($item->images); ?>

<!-- Intro image -->
	<?php if (isset($images->image_intro) && !empty($images->image_intro)) : ?>
		<?php $imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro; ?>
		<div class="item_img img-intro img-intro__<?php echo htmlspecialchars($imgfloat); ?>">
		<?php if ($params->get('link_titles')) : ?>
		<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug)); ?>">
		<?php endif; ?>
			<img
			<?php if ($images->image_intro_caption):
				echo 'class="caption"'.' title="' . htmlspecialchars($images->image_intro_caption) . '"';
			endif; ?>
			src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
			<?php if ($params->get('link_titles')) : ?>
		</a>
		<?php endif; ?>
		</div>
	<?php endif; ?>	

			<?php if ($params->get('show_intro')) :?>
				<div class="intro"> <?php echo JHtml::_('string.truncate', $item->introtext, $params->get('introtext_limit')); ?> </div>
			<?php endif; ?>

			<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
				<div class="item_info muted">
					<dl class="item_info_dl">

					<!-- <dt class="article-info-term"><?php /*echo JText::_('COM_CONTENT_ARTICLE_INFO');*/ ?></dt> -->

					<?php if ($info == 1): ?>
						<?php if ($params->get('show_parent_category') && !empty($item->parent_slug)) : ?>
							<dd>
								<div class="item_parent-category-name">
									<?php	$title = $this->escape($item->parent_title);
									$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($item->parent_slug)) . '">' . $title . '</a>';?>
								<?php if ($params->get('link_parent_category') && $item->parent_slug) : ?>
									<?php echo JText::sprintf('COM_CONTENT_PARENT', $url); ?>
								<?php else : ?>
									<?php echo JText::sprintf('COM_CONTENT_PARENT', $title); ?>
								<?php endif; ?>
								</div>
							</dd>
						<?php endif; ?>
						<?php if ($params->get('show_category')) : ?>
							<dd>
								<div class="item_category-name">
									<?php 	$title = $this->escape($item->category_title);
									$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($item->catslug)) . '">' . $title . '</a>'; ?>
									<?php if ($params->get('link_category') && $item->catslug) : ?>
										<?php echo JText::sprintf('TPL_IN', $url); ?>
									<?php else : ?>
										<?php echo JText::sprintf('TPL_IN', $title); ?>
									<?php endif; ?>
								</div>
							</dd>
						<?php endif; ?>
						<?php if ($params->get('show_publish_date')) : ?>
							<dd>
								<div class="item_published">
									<?php echo JText::sprintf('TPL_ON', JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
								</div>
							</dd>
						<?php endif; ?>
					<?php endif; ?>

					<?php if ($params->get('show_create_date')) : ?>
						<dd>
							<div class="create"><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
							</div>
						</dd>
					<?php endif; ?>
					<?php if ($params->get('show_modify_date')) : ?>
						<dd>
							<div class="item_modified"><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
							</div>
						</dd>
					<?php endif; ?>
					<?php if ($params->get('show_hits')) : ?>
						<dd>
							<div class="item_hits">
								<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $item->hits); ?>
							</div>
						</dd>
					<?php endif; ?>

				<?php if (Komento::loadApplication( 'com_content')) : ?>
					<dd class="komento">
						<?php echo Komento::commentify( 'com_content', $item) ?>
					</dd>
				<?php endif; ?>

				</dl>
			</div>
			<?php endif; ?>
		</div>
	</div>
	<?php endforeach; ?>
</div>
<div class="pagination">
	<p class="counter"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
	<?php echo $this->pagination->getPagesLinks(); ?>
</div>
