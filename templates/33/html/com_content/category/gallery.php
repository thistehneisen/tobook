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

$document = JFactory::getDocument();

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers');
JHtml::_('behavior.caption');

function special_chars_replace($string){
  $result = preg_replace("/[&%$#@'*]/", "", $string);
  return $result;
}

if ($this->params->get('user_hover')){
  $hover_active = 'hover_true';
  switch ($this->params->get('hover_style')) {
    case 'style1':
      $item_hover_style = "style1";
      break;
        case 'style1':
      $item_hover_style = "style1";
      break;
        case 'style2':
      $item_hover_style = "style2";
      break;
        case 'style3':
      $item_hover_style = "style3";
      break;
        case 'style4':
      $item_hover_style = "style4";
      break;
        case 'style5':
      $item_hover_style = "style5";
      break;
        case 'style6':
      $item_hover_style = "style6";
      break;
        case 'style7':
      $item_hover_style = "style7";
      break;
    
    default:
      $item_hover_style = "style1";
      break;
  }
  $document->addStyleSheet(JURI::base().'/templates/'. $template->template .'/css/hover_styles/'.$item_hover_style.'.css');
} else {
  $item_hover_style = "";
  $hover_active = "hover_false";
}

?>

<div class="note"></div>


<div class="page-gallery page-gallery__<?php echo $this->pageclass_sfx;?>">

  <?php if ($this->params->get('show_page_heading', 1)) : ?>
  <div class="page_header">
    <?php echo wrap_with_tag(wrap_with_span($this->escape($this->params->get('page_heading'))), $template->params->get('categoryPageHeading')); ?>
  </div>
  <?php endif; ?>

  <?php if ($this->params->get('show_category_title', 1) or $this->params->get('page_subheading')) : ?>
    <div class="category_title">
      <h2> <?php echo $this->escape($this->params->get('page_subheading')); ?>
        <?php if ($this->params->get('show_category_title')) : ?>
        <span class="subheading-category"><?php echo $this->category->title;?></span>
        <?php endif; ?>
      </h2>
    </div>
  <?php endif; ?>

  <?php if ($this->params->get('show_description', 1) || $this->params->def('show_description_image', 1)) : ?>
  <div class="category_desc">
    <?php if ($this->params->get('show_description_image') && $this->category->getParams()->get('image')) : ?>
      <img src="<?php echo $this->category->getParams()->get('image'); ?>"/>
    <?php endif; ?>
    <?php if ($this->params->get('show_description') && $this->category->description) : ?>
      <?php echo JHtml::_('content.prepare', $this->category->description, '', 'com_content.category'); ?>
    <?php endif; ?>
    <div class="clr"></div>
  </div>
  <?php endif; ?>

  <!-- Filter -->
  <?php 
    $galleryCategories = array();
    foreach ($this->intro_items as $key => &$item){
        $categoryTitle = $item->category_title;
        $galleryCategories[] = $categoryTitle;
    }; 

    $galleryCategories = array_unique($galleryCategories);
  ?>

  <?php if((!empty($this->lead_items) || (!empty($this->intro_items))) &&  $this->params->get('show_filter')): ?>
        <div class="filters">
          <b><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_BY_CATEGORY'); ?>:</b>

          <ul id="filters" class="unstyled">
            <li><a href="#" data-filter="*" class="selected"><?php echo JText::_('TPL_COM_CONTENT_GALLERY_FILTER_SHOW_ALL'); ?></a></li>
            <?php foreach ($galleryCategories as $key => $value) : ?>
              <li><a class="" href="#"data-filter=".<?php echo special_chars_replace(strtolower(str_replace(" ","_",$value))); ?>"><?php echo $value; ?></a></li>
            <?php endforeach; ?>
          </ul>
          
          <div class="clearfix"></div>
        </div>
  <?php endif; ?>

  <?php $leadingcount = 0; ?>
  <?php if (!empty($this->lead_items)) : ?>
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
    ?>
    <?php endforeach; ?>
  </div><!-- end items-leading -->
  <div class="clearfix"></div>
  <?php endif; ?>


  <?php
    $introcount = (count($this->intro_items));
    $counter = 0;
  ?>

  <?php if (!empty($this->intro_items)) : ?>
    <?php $row = $counter / $this->columns; ?>

    <div class="row-fluid">
      <ul id="isotopeContainer" class="gallery items-row cols-<?php echo (int) $this->columns;?> <?php echo $hover_active; ?>">
        <?php foreach ($this->intro_items as $key => &$item) : ?>
        <?php
          $rowcount = (((int) $key) % (int) $this->columns) + 1;
      
          if ($rowcount == 1) : ?>    
          <?php endif; ?>
      
            <li class="gallery-item <?php echo $item_hover_style; ?> <?php echo special_chars_replace(strtolower(str_replace(" ","_",$item->category_title))); ?>">
                <?php
                $this->item = &$item;
                echo $this->loadTemplate('item');
              ?>
              <?php $counter++; ?>
              <div class="clearfix"></div>
            </li><!-- end span -->
            <?php if (($rowcount == $this->columns) or ($counter == $introcount)): ?>     
          
            <?php endif; ?>
      
        <?php endforeach; ?>
      </ul>
    </div><!-- end row -->
  <?php endif; ?>
  
  <?php if (!empty($this->link_items)) : ?>
  <div class="items-more">
  <?php echo $this->loadTemplate('links'); ?>
  </div>
  <?php endif; ?>


  <?php if (!empty($this->children[$this->category->id])&& $this->maxLevel != 0) : ?>
    <div class="category_children">
      <div class="row">
        <!-- <h3> <?php /*echo JTEXT::_('JGLOBAL_SUBCATEGORIES');*/ ?> </h3> -->
        <?php echo $this->loadTemplate('children'); ?>
      </div>
    </div>
  <?php endif; ?>

  <?php if (($this->params->def('show_pagination', 1) == 1  || ($this->params->get('show_pagination') == 2)) && ($this->pagination->get('pages.total') > 1)) : ?>
  
  <div class="pagination">
    <?php  if ($this->params->def('show_pagination_results', 1)) : ?>
    <p class="counter"> <?php echo $this->pagination->getPagesCounter(); ?> </p>
    <?php endif; ?>
    <?php echo $this->pagination->getPagesLinks(); ?> </div>
  <?php  endif; ?>
</div>

  <script type="text/javascript">
    jQuery(document).ready(function() {
    (function($){ 
     $(window).load(function(){

      var $cols = <?php echo $this->columns; ?>;
      var $container = $('#isotopeContainer');

      $item = $('.gallery-item')
      $item.outerWidth(Math.floor($container.width() / $cols));

      $container.isotope({
        animationEngine: 'best-available',
        animationOptions: {
            queue: false,
            duration: 800
          },
          containerClass : 'isotope',
          containerStyle: {
            position: 'relative',
            overflow: 'hidden'
          },
          hiddenClass : 'isotope-hidden',
          itemClass : 'isotope-item',
          resizable: true,
          resizesContainer : true,
          transformsEnabled: !$.browser.opera // disable transforms in Opera
      });

      if($container.width() <= '767'){
        $item.outerWidth($container.width());
        $item.addClass('straightDown');
        $container.isotope({
          layoutMode: 'straightDown'
        });
      } else {
        $item.removeClass('straightDown');
        $container.isotope({
          layoutMode: 'fitRows'
        });
      }

      $(window).resize(function(){
        $item.outerWidth(Math.floor($container.width() / $cols));
        if($container.width() <= '767'){
          $item.outerWidth($container.width());
          $item.addClass('straightDown');
          $container.isotope({
            layoutMode: 'straightDown'
          });
        } else {
          $item.outerWidth(Math.floor($container.width() / $cols));
          $item.removeClass('straightDown');
          $container.isotope({
            layoutMode: 'fitRows'
          });
        }
      });
    });
  })(jQuery);
  }); 
  </script>

  <?php if($this->params->get('show_filter')): ?>

  <script type="text/javascript">
    jQuery(document).ready(function() {
    (function($){ 
     $(window).load(function(){

      var $container = $('#isotopeContainer');

      // filter items when filter link is clicked
      $('#filters a').click(function(){
        var selector = $(this).attr('data-filter');
        $container.isotope({ filter: selector });
        return false;
      });

      var $optionSets = $('#filters li'),
          $optionLinks = $optionSets.find('a');

          $optionLinks.click(function(){
              var $this = $(this);
              // don't proceed if already selected
              if ( $this.hasClass('selected') ) {
                return false;
              }
              var $optionSet = $this.parents('#filters');
              $optionSet.find('.selected').removeClass('selected');
              $this.addClass('selected');
        
              // make option object dynamically, i.e. { filter: '.my-filter-class' }
              var options = {},
                  key = $optionSet.attr('data-option-key'),
                  value = $this.attr('data-option-value');
              // parse 'false' as false boolean
              value = value === 'false' ? false : value;
              options[ key ] = value;
              if ( key === 'layoutMode' && typeof changeLayoutMode === 'function' ) {
                // changes in layout modes need extra logic
                changeLayoutMode( $this, options )
              } else {
                // otherwise, apply new options
                $container.isotope( options );
              }
              
              return false;
          });
     });
  })(jQuery);
  }); 
  </script>

  <?php endif; ?>