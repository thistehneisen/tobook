<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_news
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
<div class="mod-newsflash-adv mod-newsflash-adv__<?php echo $moduleclass_sfx; ?>">
  <div class="isotope" id="mod-newsflash-adv__masonry">
    <?php for ($i = 0, $n = count($list); $i < $n; $i ++) :
      $item = $list[$i]; 
      ?>

      <div class="item item_num<?php echo $i; ?> item__module isotope-item">
          <?php require JModuleHelper::getLayoutPath('mod_articles_news_adv', '_item'); ?>
      </div>
    <?php endfor; ?>
  </div> 

    <div class="mod-newsflash-adv_custom-link">
    <?php 
      $menuLink = $menu->getItem($params->get('custom_link_menu'));

      if($params->get('mod_button') == 1)
      {
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
      }
    ?>
  </div> 
</div>

  <script type="text/javascript">
    jQuery(document).ready(function() {
    (function($){ 
     $(window).load(function(){

      var $cols = <?php echo $columns; ?>;
      $container = $('#mod-newsflash-adv__masonry');

      $item = $('.isotope-item')
      $item.outerWidth(Math.floor($container.width() / $cols));

      $container.isotope({
        resizable: true
      });

      if($container.width() <= '480'){
        $item.outerWidth($container.width());
        $item.addClass('straightDown');
        $container.isotope({
          layoutMode: 'straightDown'
        });
      } else {
        $item.removeClass('straightDown');
        $container.isotope({
          layoutMode: 'masonry'
        });
      }

      $(window).resize(function(){
        $item.outerWidth(Math.floor($container.width() / $cols));
        if($container.width() <= '480'){
          $item.outerWidth($container.width());
          $item.addClass('straightDown');
          $container.isotope({
            layoutMode: 'straightDown'
          });
        } else {
          $item.outerWidth(Math.floor($container.width() / $cols));
          $item.removeClass('straightDown');
          $container.isotope({
            layoutMode: 'masonry'
          });
        }
      });

    });
  })(jQuery);
  }); 
  </script>