<?php
defined('_JEXEC') or die;
include_once ('includes/functions.php');
include_once ('includes/includes.php');
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
  
  <?php

    $doc->addStyleSheet('templates/'.$this->template.'/css/bootstrap.css'); 
    $doc->addStyleSheet('templates/'.$this->template.'/css/default.css');
    $doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
    $doc->addStyleSheet('templates/'.$this->template.'/css/touch.gallery.css');
    $doc->addStyleSheet('templates/'.$this->template.'/css/komento.css');  
    $doc->addStyleSheet('templates/'.$this->template.'/css/magnific-popup.css'); 
    $doc->addStyleSheet('templates/'.$this->template.'/css/responsive.css');   
    $doc->addStyleSheet('//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css'); 
    $doc->addStyleSheet('//fonts.googleapis.com/css?family=Open+Sans:400italic,400,300|Vidaloka'); 

    if($themeLayout == 1){
      $doc->addStyleSheet('templates/'.$this->template.'/css/layout.css');
    }
  ?>
  <jdoc:include type="head" />

  <!--[if IE 8]>
    <link rel="stylesheet" href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template; ?>/css/ie.css" />
  <![endif]-->
  
</head>

<body class="all <?php echo $option . " view-" . $view . " task-" . $task . " itemid-" . $itemid . " body__" . $pageClass;?>">

  <!-- Body -->
  <div id="wrapper">
    <div class="wrapper-inner">

	<div class="bg_top">

    <!-- Top -->
    <?php if ($this->countModules('top')): ?>
      <div id="top-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <div id="top" class="<?php echo $rowClass; ?>">
              <jdoc:include type="modules" name="top" style="themeHtml5" />
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Header -->
      <div id="header-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <header>
              <div class="<?php echo $rowClass; ?>">
                  <!-- Logo -->
                  <div id="logo" class="span<?php echo $this->params->get('logoBlockWidth'); ?>">
                    <a href="<?php echo $this->baseurl; ?>">
                      <img src="<?php echo $logo;?>" alt="<?php echo $sitename; ?>" />
                    </a>
                  </div>
                  <jdoc:include type="modules" name="header" style="themeHtml5" />
              </div>
            </header>
          </div>
        </div>
      </div>

    <!-- Navigation -->
    <?php if ($this->countModules('navigation')): ?>
      <div id="navigation-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <div class="<?php echo $rowClass; ?>">
              <nav>
                <jdoc:include type="modules" name="navigation" style="themeHtml5" />
              </nav>
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Showcase -->
    <?php if ($this->countModules('showcase') && $hideByView == false): ?>
      <div id="showcase-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <div class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="showcase" style="themeHtml5" />
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

	</div>

    <!-- Feature -->
    <?php if ($this->countModules('feature') && $hideByView == false): ?>
      <div id="feature-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <div class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="feature" style="themeHtml5" />
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
	
    <div class="bg_cont">

    <!-- Maintop -->
    <?php if ($this->countModules('maintop') && $hideByView == false && $layout !== 'edit'): ?>
      <div id="maintop-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <div id="maintop" class="<?php echo $rowClass; ?>">
              <jdoc:include type="modules" name="maintop" style="themeHtml5" />
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Main Content row -->
    <div id="content-row">
      <div class="row-container">
        <div class="<?php echo $containerClass; ?>">
          <div class="content-inner <?php echo $rowClass; ?>">
        
            <!-- Left sidebar -->
            <?php if ($this->countModules('aside-left') && ($hideByOption) == false && $view !== 'form' && $layout !== 'edit'): ?>
              <div id="aside-left" class="span<?php echo $asideLeftWidth; ?>">
                <aside>
                  <jdoc:include type="modules" name="aside-left" style="html5nosize" />
                </aside>
              </div>
            <?php endif; ?>
        
            <div id="component" class="span<?php echo $mainContentWidth; ?>">
              <!-- Breadcrumbs -->
              <?php if ($this->countModules('breadcrumbs')): ?>
                <div id="breadcrumbs-row">
                  <div id="breadcrumbs">
                    <jdoc:include type="modules" name="breadcrumbs" style="html5nosize" />
                  </div>
                </div>
              <?php endif; ?>
        
              <!-- Content-top -->
              <?php if ($this->countModules('content-top') && $hideByView == false): ?>
                <div id="content-top-row" class="<?php echo $rowClass; ?>">
                  <div id="content-top">
                    <jdoc:include type="modules" name="content-top" style="themeHtml5" />
                  </div>
                </div>
              <?php endif; ?>
        
                <jdoc:include type="message" />
                <jdoc:include type="component" />
        
              <!-- Content-bottom -->
              <?php if ($this->countModules('content-bottom') && $hideByView == false): ?>
                <div id="content-bottom-row" class="<?php echo $rowClass; ?>">
                  <div id="content-bottom">
                    <jdoc:include type="modules" name="content-bottom" style="themeHtml5" />
                  </div>
                </div>
              <?php endif; ?>
            </div>
        
            <!-- Right sidebar -->
            <?php if ($this->countModules('aside-right') && ($hideByOption) == false && $view !== 'form' && $layout !== 'edit'): ?>
              <div id="aside-right" class="span<?php echo $asideRightWidth; ?>">
                <aside>
                  <jdoc:include type="modules" name="aside-right" style="html5nosize" />
                </aside>
              </div>
            <?php endif; ?>
          </div>
        </div>
      </div>
    </div>

    <!-- Mainbottom -->
    <?php if ($this->countModules('mainbottom') && $hideByView == false): ?>
      <div id="mainbottom-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <div id="mainbottom" class="<?php echo $rowClass; ?>">
              <jdoc:include type="modules" name="mainbottom" style="themeHtml5" />
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>

    <!-- Bottom -->
    <?php if ($this->countModules('bottom') && $hideByView == false): ?>
      <div id="bottom-row">
        <div class="row-container">
          <div class="<?php echo $containerClass; ?>">
            <div id="bottom" class="<?php echo $rowClass; ?>">
              <jdoc:include type="modules" name="bottom" style="themeHtml5" />
            </div>
          </div>
        </div>
      </div>
    <?php endif; ?>
    
    </div>
    
    </div>
  </div>

  <div id="footer-wrapper">
    <div class="footer-wrapper-inner">
      <!-- Footer -->
      <?php if ($this->countModules('footer')): ?>
        <div id="footer-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div id="footer" class="<?php echo $rowClass; ?>">
                <jdoc:include type="modules" name="footer" style="themeHtml5" />
              </div>
            </div>
          </div>
        </div>
      <?php endif; ?>
      
      <!-- Copyright -->
        <div id="copyright-row">
          <div class="row-container">
            <div class="<?php echo $containerClass; ?>">
              <div class="<?php echo $rowClass; ?>">
                <!-- Footer Logo -->
                <div id="copyright" class="span<?php echo $this->params->get('footerWidth'); ?>">
                  <?php if($this->params->get('footerLogo') == 1) : ?>
                  <a class="footer_logo" href="<?php echo $this->baseurl; ?>">
                    <img src="<?php echo $footerLogo;?>" alt="<?php echo $sitename; ?>" />
                  </a>
  					      <?php else: ?>
                  <?php echo '<span class="siteName">'.$sitename.'</span>'; ?>
  					      <?php endif; ?>
					        <?php if($this->params->get('footerCopy') == 1) echo '<span class="copy">&copy;</span>'; ?>
					        <?php if($this->params->get('footerYear') == 1) echo '<span class="year">'.date('Y').'</span>'; ?>
                  <?php if($this->params->get('privacyLink') == 1) :?>
                  <a class="privacy_link" href="<?php echo $privacy_link_url; ?>"><?php echo $this->params->get('privacy_link_title'); ?></a>
  					      <?php endif; ?>
                  <?php if($this->params->get('termsLink') == 1) :?>
                  <a class="terms_link" href="<?php echo $terms_link_url; ?>"><?php echo $this->params->get('terms_link_title'); ?></a>
  					      <?php endif; ?>
                </div>
                <jdoc:include type="modules" name="copyright" style="themeHtml5" />
                <?php if($this->params->get('todesktop')): ?>
                <div class="span12">
                  <div id="to-desktop">
                    <a href="#"><span class="to_desktop"><?php echo $this->params->get('todesktop_text') ?></span><span class="to_mobile"><?php echo $this->params->get('tomobile_text') ?></span></a>
                  </div>
                </div>
  <?php endif; ?>
                More <a  rel='nofollow' href='http://www.templatemonster.com/category/spa-accessories-joomla-templates/' target='_blank'>Spa Accessories Joomla Templates at TemplateMonster.com</a>
              </div>
            </div>
          </div>
        </div>
    </div>
  </div>
  <?php if($this->params->get('totop')): ?>
    <div id="back-top">
      <a href="#"><span></span><?php echo $this->params->get('totop_text') ?></a>
    </div>
  <?php endif; ?>


  <?php if ($this->countModules('modal')): ?>
    <jdoc:include type="modules" name="modal" style="modal" />
  <?php endif; ?>

  <jdoc:include type="modules" name="debug" style="none"/>

  <?php
    $doc->addScript($this->baseurl."/media/jui/js/jquery.min.js");
    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/jquery.mobile.customized.min.js');
    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/jquery.easing.1.3.js');
    $doc->addScript($this->baseurl."/media/jui/js/bootstrap.min.js");

    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/jquery.isotope.min.js');
    /*$doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/touch.gallery.js');*/
    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/jquery.magnific-popup.js');
    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/ios-orientationchange-fix.js');
    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/desktop-mobile.js');
    if($this->params->get('blackandwhite')): 
    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/jquery.BlackAndWhite.min.js');
    $doc->addScriptDeclaration("jQuery(window).load(function(){
      jQuery('.item_img a').BlackAndWhite({
        invertHoverEffect: ".$this->params->get('invertHoverEffect').",
        intensity: 1,
        responsive: true,
        speed: {
            fadeIn: ".$this->params->get('fadeIn').",
            fadeOut: ".$this->params->get('fadeOut')." 
        }
    });
    });");
    endif;
    $doc->addScript($this->baseurl.'/templates/'.$this->template.'/js/scripts.js');
  ?>

</body>
</html>