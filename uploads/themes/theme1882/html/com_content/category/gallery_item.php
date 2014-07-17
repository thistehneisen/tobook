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

// Create a shortcut for params.
$params = &$this->item->params;
$images = json_decode($this->item->images);
$canEdit = $this->item->params->get('access-edit');
JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
$info = $this->item->params->get('info_block_position', 0);
JHtml::_('behavior.tooltip');
JHtml::_('behavior.framework');
?>

<!-- Icons -->
  <?php if ($params->get('show_print_icon') || $params->get('show_email_icon') || $canEdit) : ?>
  <div class="item_icons btn-group pull-right"> <a class="btn dropdown-toggle" data-toggle="dropdown" href="#"> <i class="fa fa-cog"></i> <span class="caret"></span> </a>
    <ul class="dropdown-menu">
      <?php if ($params->get('show_print_icon')) : ?>
        <li class="print-icon"> <?php echo JHtml::_('icon.print_popup', $this->item, $params); ?> </li>
      <?php endif; ?>
      <?php if ($params->get('show_email_icon')) : ?>
        <li class="email-icon"> <?php echo JHtml::_('icon.email', $this->item, $params); ?> </li>
      <?php endif; ?>
      <?php if ($canEdit) : ?>
        <li class="edit-icon"> <?php echo JHtml::_('icon.edit', $this->item, $params); ?> </li>
      <?php endif; ?>
    </ul>
  </div>
  <div class="clearfix"></div>
  <?php endif; ?>


<?php if ($this->params->get('user_hover')): ?>
  <div class="view">
<?php endif; ?>

<!-- Image  -->
  <?php  if (isset($images->image_intro) and !empty($images->image_intro)) : ?>
   
    <?php 
    	$categoryImgFloat = $this->params->get('image_float');

    	if($categoryImgFloat == 'use_article'){
    		$imgfloat = (empty($images->float_intro)) ? $params->get('float_intro') : $images->float_intro;
    	} else {
    		$imgfloat = $categoryImgFloat;
    	}
     ?>
      <div class="item_img img-intro img-intro__<?php echo htmlspecialchars($imgfloat); ?>"> 

      	<?php  if (isset($images->image_fulltext) and !empty($images->image_fulltext)) : ?>
	        <?php if (!$params->get('user_hover')): ?>
            <a class="touchGalleryLink zoom galleryZoomIcon" href="<?php echo htmlspecialchars($images->image_fulltext); ?>">   
            <?php endif; ?>         
            <?php if (!$this->params->get('user_hover')): ?>
  	          <span class="zoom-bg"></span>
  	          <span class="zoom-icon"></span>
            <?php endif; ?>

	          <img
	          <?php if ($images->image_intro_caption):
	            echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
	          endif; ?>
	          src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
	        <?php if ($params->get('user_hover')): ?></a><?php endif; ?>
      	<?php else: ?>
      		<img
	          <?php if ($images->image_intro_caption):
	            echo 'class="caption"'.' title="' .htmlspecialchars($images->image_intro_caption) .'"';
	          endif; ?>
	          src="<?php echo htmlspecialchars($images->image_intro); ?>" alt="<?php echo htmlspecialchars($images->image_intro_alt); ?>"/>
        <?php endif; ?>

      </div>
    
  <?php endif; ?>

  <?php if ($this->params->get('user_hover')): ?>
	  <?php if ($params->get('hover_style') == 'style2'): ?>
	  	<div class="mask"></div>
	  	<div class="content">  		
	  <?php else: ?>
	  	<div class="mask">
	  <?php endif; ?>
    <div class="mask_wrap"><div class="mask_cont">
  <?php endif; ?>
  
  		<!--  title/author -->
  			<?php if ($params->get('show_title') || $this->item->state == 0 || ($params->get('show_author') && !empty($this->item->author ))) : ?>
  				<div class="item_header">
  				<?php if ($params->get('show_title')) : ?>
  					<?php echo '<'. $template->params->get('categoryItemHeading') .' class="item_title">'; ?>
  						<?php if ($params->get('link_titles') && $params->get('access-view')) : ?>
  							<a href="<?php echo JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid)); ?>"> <?php echo wrap_with_span($this->escape($this->item->title)); ?></a>
  						<?php else : ?>
  							<?php echo wrap_with_span($this->escape($this->item->title)); ?>
  						<?php endif; ?>
  					<?php echo '</'. $template->params->get('categoryItemHeading') .'>'; ?>
  				<?php endif; ?>
  
  				<?php if ($this->item->state == 0) : ?>
  					<span class="label label-warning"><?php echo JText::_('JUNPUBLISHED'); ?></span>
  				<?php endif; ?>
  
  				<?php if ($params->get('show_author') && !empty($this->item->author )) : ?>
  					<div class="item_createdby">
  					<?php $author = $this->item->author; ?>
  					<?php $author = ($this->item->created_by_alias ? $this->item->created_by_alias : $author); ?>
  					<?php if (!empty($this->item->contactid ) && $params->get('link_author') == true) : ?>
  						<?php
  						echo JText::sprintf(
  						'TPL_BY',
  						JHtml::_('link', JRoute::_('index.php?option=com_contact&view=contact&id=' . $this->item->contactid), $author)
  						); ?>
  					<?php else :?>
  						<?php echo JText::sprintf('TPL_BY', $author); ?>
  					<?php endif; ?>
  					</div>
  				<?php endif; ?>
  				</div>
  			<?php endif; ?>
    
  		<!-- info TOP -->
  			<?php // to do not that elegant would be nice to group the params ?>
  			<?php $useDefList = ($params->get('show_modify_date') || $params->get('show_publish_date')
  				|| $params->get('show_hits') || $params->get('show_category') || $params->get('show_parent_category')); ?>
  			<?php if ($useDefList && ($info == 0 || $info == 2)) : ?>
  				<div class="item_info muted">
  					<dl class="item_info_dl">
  
  					<!-- <dt class="article-info-term">
  						<?php /*echo JText::_('COM_CONTENT_ARTICLE_INFO');*/ ?>
  					</dt> -->
  
  					<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
  						<dd>
  							<div class="item_parent-category-name">
  								<?php $title = $this->escape($this->item->parent_title);
  								$url = '<a href="'.JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>';?>
  								<?php if ($params->get('link_parent_category') && !empty($this->item->parent_slug)) : ?>
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
  								<?php $title = $this->escape($this->item->category_title);
  								$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
  								<?php if ($params->get('link_category') && $this->item->catslug) : ?>
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
  								<?php echo JText::sprintf('TPL_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
  							</div>
  						</dd>
  					<?php endif; ?>
  
  					<?php if ($info == 0): ?>
  						<?php if ($params->get('show_modify_date')) : ?>
  							<dd>
  								<div class="item_modified">
  									 <?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
  								</div>
  							</dd>
  						<?php endif; ?>
  						<?php if ($params->get('show_create_date')) : ?>
  							<dd>
  								<div class="item_create">
  									 <?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->created, JText::_('DATE_FORMAT_LC3'))); ?>
  								</div>
  							</dd>
  						<?php endif; ?>
  
  						<?php if ($params->get('show_hits')) : ?>
  							<dd>
  								<div class="item_hits">
  									<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
  								</div>
  							</dd>
  						<?php endif; ?>
  					<?php endif; ?>
  					</dl>
  				</div>
  			<?php endif; ?>
  
  			<?php if (!$params->get('show_intro')) : ?>
  				<?php echo $this->item->event->afterDisplayTitle; ?>
  			<?php endif; ?>
  				<?php echo $this->item->event->beforeDisplayContent; ?>
  
  		<!-- Introtext -->
  			<div class="item_introtext">
          <?php 
          if($params->get('limt_introtext') > '0'){
            echo limit_words($this->item->introtext, $params->get('limt_introtext'));             
          } else {
            echo $this->item->introtext;
          }
          ?>
        </div>
  
  		<!-- info BOTTOM -->
  			<?php if ($useDefList && ($info == 1 || $info == 2)) : ?>
  				<div class="item_info muted">
  					<dl class="item_info_dl">
  
  				<!-- 	<dt class="article-info-term"><?php /*echo JText::_('COM_CONTENT_ARTICLE_INFO');*/ ?></dt> -->
  
  					<?php if ($info == 1): ?>
  						<?php if ($params->get('show_parent_category') && !empty($this->item->parent_slug)) : ?>
  							<dd>
  								<div class="item_parent-category-name">
  									<?php	$title = $this->escape($this->item->parent_title);
  									$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->parent_slug)) . '">' . $title . '</a>';?>
  									<?php if ($params->get('link_parent_category') && $this->item->parent_slug) : ?>
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
  									<?php 	$title = $this->escape($this->item->category_title);
  									$url = '<a href="' . JRoute::_(ContentHelperRoute::getCategoryRoute($this->item->catslug)) . '">' . $title . '</a>';?>
  									<?php if ($params->get('link_category') && $this->item->catslug) : ?>
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
  									 <?php echo JText::sprintf('COM_CONTENT_PUBLISHED_DATE_ON', JHtml::_('date', $this->item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
  								</div>
  							</dd>
  						<?php endif; ?>
  					<?php endif; ?>
  
  					<?php if ($params->get('show_create_date')) : ?>
  						<dd>
  							<div class="item_create"><?php echo JText::sprintf('COM_CONTENT_CREATED_DATE_ON', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
  							</div>
  						</dd>
  					<?php endif; ?>
  					<?php if ($params->get('show_modify_date')) : ?>
  						<dd>
  							<div class="item_modified"><?php echo JText::sprintf('COM_CONTENT_LAST_UPDATED', JHtml::_('date', $this->item->modified, JText::_('DATE_FORMAT_LC3'))); ?>
  							</div>
  						</dd>
  					<?php endif; ?>
  					<?php if ($params->get('show_hits')) : ?>
  						<dd>
  							<div class="item_hits">
  								<?php echo JText::sprintf('COM_CONTENT_ARTICLE_HITS', $this->item->hits); ?>
  							</div>
  						</dd>
  					<?php endif; ?>
  					</dl>
  				</div>
  			<?php endif; ?>

        <?php 
          if ($params->get('show_readmore') && $this->item->readmore) :
            if ($params->get('access-view')) :
              $link = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
            else :
              $menu = JFactory::getApplication()->getMenu();
              $active = $menu->getActive();
              $itemId = $active->id;
              $link1 = JRoute::_('index.php?option=com_users&view=login&Itemid=' . $itemId);
              $returnURL = JRoute::_(ContentHelperRoute::getArticleRoute($this->item->slug, $this->item->catid));
              $link = new JURI($link1);
              $link->setVar('return', base64_encode($returnURL));
            endif;
          endif;
        ?>

        <?php if ($this->params->get('user_hover')): ?>

          <div class="item_more">
            <a href="<?php echo htmlspecialchars($images->image_fulltext); ?>" class="galleryZoom"><i class="fa fa-search-plus"></i></a>
            <?php if ($params->get('show_readmore') && $this->item->readmore) : ?>
              <a href="<?php echo $link; ?>" class="hover_more"><i class="fa fa-sign-in"></i></a>
            <?php endif; ?>
          </div>

        <?php else: ?>  
    		<!-- More -->
    			<?php if ($params->get('show_readmore') && $this->item->readmore) : ?>
    				<a class="btn btn-info item_more" href="<?php echo $link; ?>"><span>
    				<?php if (!$params->get('access-view')) :
    					echo JText::_('COM_CONTENT_REGISTER_TO_READ_MORE');
    				elseif ($readmore = $this->item->alternative_readmore) :
    					echo $readmore;
    					if ($params->get('show_readmore_title', 0) != 0) :
    						echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
    					endif;
    				elseif ($params->get('show_readmore_title', 0) == 0) :
    					echo JText::sprintf('TPL_COM_CONTENT_READ_MORE_TITLE');
    				else :
    					echo JText::_('TPL_COM_CONTENT_READ_MORE');
    					echo JHtml::_('string.truncate', ($this->item->title), $params->get('readmore_limit'));
    				endif; ?>
    				</span></a>
    			<?php endif; ?>
        <?php endif; ?>

        <?php if ($params->get('show_tags', 1) && !empty($this->item->tags)) : ?>
          <?php $this->item->tagLayout = new JLayoutFile('joomla.content.tags'); ?>
  
          <?php echo $this->item->tagLayout->render($this->item->tags->itemTags); ?>
        <?php endif; ?>

      <?php if ($this->params->get('user_hover')): ?>
          		</div>
          	</div>
          </div>
        </div>
      <?php endif; ?>

<?php echo $this->item->event->afterDisplayContent; ?>
