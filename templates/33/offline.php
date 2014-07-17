<?php
/**
 * @package     Joomla.Site
 * @subpackage  Template.system
 *
 * @copyright   Copyright (C) 2005 - 2012 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
include ('includes/includes.php');

?>
 
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" dir="<?php echo $this->direction; ?>">
<head>
  <jdoc:include type="head" />

  <?php
    $doc->addStyleSheet('templates/'.$this->template.'/css/bootstrap.css');  
    $doc->addStyleSheet('templates/'.$this->template.'/css/default.css');
    $doc->addStyleSheet('templates/'.$this->template.'/css/template.css');
  ?>

  <script src="<?php echo $this->baseurl; ?>/media/jui/js/jquery.min.js"></script>
  <script src="<?php echo $this->baseurl; ?>/media/jui/js/bootstrap.min.js"></script>
  <script src="<?php echo $this->baseurl .'/templates/'.$this->template.'/js/scripts.js'?>"></script>
  <script type="text/javascript">jQuery.noConflict();</script>

</head>


<body>

<div class="container">
  <div class="row">
    <span class="span5 offset3">
        <jdoc:include type="message" />

        <div class="well">      
          <?php if ($app->getCfg('offline_image')) : ?>
          <img src="<?php echo $app->getCfg('offline_image'); ?>" alt="<?php echo htmlspecialchars($app->getCfg('sitename')); ?>" />
          <?php endif; ?>
          <h1>
            <?php echo htmlspecialchars($app->getCfg('sitename')); ?>
          </h1>

          <?php if ($app->getCfg('display_offline_message', 1) == 1 && str_replace(' ', '', $app->getCfg('offline_message')) != ''): ?>
            <p>
              <?php echo $app->getCfg('offline_message'); ?>
            </p>
          <?php elseif ($app->getCfg('display_offline_message', 1) == 2 && str_replace(' ', '', JText::_('JOFFLINE_MESSAGE')) != ''): ?>
            <p>
              <?php echo JText::_('JOFFLINE_MESSAGE'); ?>
            </p>
          <?php  endif; ?>


          <form action="<?php echo JRoute::_('index.php', true); ?>" method="post" id="form-login">
          <fieldset class="input">
            <p id="form-login-username">
              <label for="username"><?php echo JText::_('JGLOBAL_USERNAME') ?></label>
              <input name="username" id="username" type="text" class="inputbox" alt="<?php echo JText::_('JGLOBAL_USERNAME') ?>" size="18" />
            </p>
            <p id="form-login-password">
              <label for="passwd"><?php echo JText::_('JGLOBAL_PASSWORD') ?></label>
              <input type="password" name="password" class="inputbox" size="18" alt="<?php echo JText::_('JGLOBAL_PASSWORD') ?>" id="passwd" />
            </p>
            <p id="form-login-remember">
              <label class="checkbox" for="remember"><?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>
                <input type="checkbox" name="remember" class="inputbox" value="yes" alt="<?php echo JText::_('JGLOBAL_REMEMBER_ME') ?>" id="remember" />
              </label>
            </p>
            <input type="submit" name="Submit" class="button btn" value="<?php echo JText::_('JLOGIN') ?>" />
            <input type="hidden" name="option" value="com_users" />
            <input type="hidden" name="task" value="user.login" />
            <input type="hidden" name="return" value="<?php echo base64_encode(JURI::base()) ?>" />
            <?php echo JHtml::_('form.token'); ?>
          </fieldset>
          </form>
        </div>
    </span>
  </div>
</div>


</body>
</html>
