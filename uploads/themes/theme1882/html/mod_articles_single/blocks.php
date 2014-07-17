<?php
	defined('_JEXEC') or die;
?>

<div class="mod-article-single mod-article-single__<?php echo $moduleclass_sfx; ?>">
	<div class="item__module">

		<!-- Intro Image -->
		<?php if ($params->get('show_intro_image')) : ?>
			<?php  if (isset($item_images->image_intro) and !empty($item_images->image_intro)) : ?>
			<?php $imgfloat = (empty($item_images->float_intro)) ? $params->get('float_intro') : $item_images->float_intro; ?>
			<div class="item_img img-intro img-intro__<?php echo htmlspecialchars($imgfloat); ?>">
			<?php if ((($params->get('item_title') && $params->get('link_titles')) || $params->get('readmore')) && isset($item->link)) : ?>
			<a href="<?php echo $item->link;?>">
			<?php endif; ?>
			<img
				<?php if ($item_images->image_intro_caption):
					echo 'class="caption"'.' title="' .htmlspecialchars($item_images->image_intro_caption) .'"';
				endif; ?>
				src="<?php echo htmlspecialchars($item_images->image_intro); ?>" alt="<?php echo htmlspecialchars($item_images->image_intro_alt); ?>"/>
				<?php if ((($params->get('item_title') && $params->get('link_titles')) || $params->get('readmore')) && isset($item->link)) : ?>
				</a>
				<?php endif; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<!-- Item Title -->
		<?php if ($params->get('item_title')) : ?>
			<<?php echo $item_heading; ?> class="item-title">
				<?php if ($params->get('link_titles') && isset($item->link)) : ?>
					<a href="<?php echo $item->link;?>">
						<?php echo $item->title;?></a>
				<?php else : ?>
					<?php echo $item->title; ?>
				<?php endif; ?>
			</<?php echo $item_heading; ?>>
		<?php endif; ?>
		<?php echo $item->afterDisplayTitle; ?>
		<?php echo $item->beforeDisplayContent; ?>

		<!-- Publish Date -->
		<?php if ($params->get('published_on')) : ?>
			<span class="item_published">
				<i class="icon-calendar"></i><?php echo JText::sprintf(JHtml::_('date', $item->publish_up, JText::_('DATE_FORMAT_LC3'))); ?>
			</span>
		<?php endif; ?>

		<!-- Intro Text -->
		<div class="item_introtext">
			<?php echo $item->introtext; ?>
		</div>	
			<?php 
				if (isset($item->link) && $params->get('readmore')) :	

            if($view == "form"){
              if(isset($item->attribs['alternative_readmore'])){
                $readMoreText = $item->attribs['alternative_readmore'];
              } else {
                $readMoreText = JText::_('TPL_COM_CONTENT_LEARN_MORE');
              }
            } else {
              if ($item->params->get('alternative_readmore')){
                $readMoreText = $item->params->get('alternative_readmore');
              } else {
                $readMoreText = JText::_('TPL_COM_CONTENT_LEARN_MORE');
              }
            }
					echo '<a class="link" href="'.$item->link.'"><span>'. $readMoreText .'</span></a>';
				endif; ?>
	</div>

  <?php if($params->get('mod_button') == 1): ?>   
    <div class="mod-newsflash-adv_custom-link">
      <?php 
        $menuLink = $menu->getItem($params->get('custom_link_menu'));

          switch ($params->get('custom_link_route')) 
          {
            case 0:
              $link_url = $params->get('custom_link_url');
              break;
            case 1:
              $link_url = JRoute::_($menuLink->link.'&Itemid='.$menuLink->id);
              break;            
            default:
              $link_url = "#";
              break;
          }
          echo '<a href="'. $link_url .'">'. $params->get('custom_link_title') .'</a>';
      ?>
    </div>
  <?php endif; ?>

</div>