<?php
/**
 *
 * PHP versions 4 and 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       cake
 * @subpackage    cake.cake.libs.view.templates.layouts
 * @since         CakePHP(tm) v 0.10.0.1076
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <?php echo $this->Html->charset(); ?>
        <title>
            <?php __('Newsletter Mailer'); __('Home',true); ?> - 
            <?php __($title_for_layout); ?>
        </title>
        <meta http-equiv="pragma" content="no-cache" />
        <meta http-equiv="expires" content="0" />
        <?php
            echo $this->Html->meta('icon');

            //echo $this->Html->css('cake.generic');

            echo $scripts_for_layout;
        ?>
            <link rel="stylesheet" href="<?php echo $this->Html->url("/"); ?>css/screen.css" type="text/css" media="screen, projection" />
            <link rel="stylesheet" href="<?php echo $this->Html->url("/"); ?>css/print.css" type="text/css" media="print" />
 
            <!--[if IE]>
            <link rel="stylesheet" href="<?php echo $this->Html->url("/"); ?>css/ie.css" type="text/css" media="screen, projection" />
            <![endif]-->
            <link rel="stylesheet" href="<?php echo $this->Html->url("/"); ?>css/style.css" type="text/css" media="screen, projection" />
            <link rel="stylesheet" href="<?php echo $this->Html->url("/"); ?>assets/stylesheets/formalize.css" />
                <!--[if IE]><script language="javascript" type="text/javascript" src="<?php echo $this->Html->url("/"); ?>js/excanvas.min.js"></script><![endif]-->

            <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.3/jquery.min.js" type="text/javascript">
            </script>
            <script src="<?php echo $this->Html->url("/"); ?>assets/javascripts/jquery.formalize.js" type="text/javascript">
            </script>
            <script type="text/javascript" src="<?php echo $this->Html->url("/"); ?>js/jquery.countdown.min.js"></script>
            <script type="text/javascript" src="<?php echo $this->Html->url("/"); ?>js/jquery.tipTip.minified.js"></script>


            <script  type="text/javascript" src="<?php echo $this->Html->url("/"); ?>js/jquery.flot.js"></script>

            <script type="text/javascript" src="<?php echo $this->Html->url("/"); ?>fancybox/jquery.fancybox-1.3.2.js"></script>
            <link rel="stylesheet" type="text/css" href="<?php echo $this->Html->url("/"); ?>fancybox/jquery.fancybox-1.3.2.css" media="screen" />
            <script type="text/javascript">
                $(document).ready(function() {
                    $(".modal").fancybox({
                        'width'				: '75%',
                        'height'			: '75%',
                        'autoScale'     	: false,
                        'transitionIn'		: 'none',
                        'transitionOut'		: 'none',
                        'type'				: 'iframe'
                    });
                    $("input").tipTip({defaultPosition:"right",delay:200});
                    $("*").tipTip({defaultPosition:"top",delay:200});
                });
            </script>

        </head>
        <body>
            <div class="container" style="margin-top:22px;">
                <div class="span-5 first">
                    <img src="<?php echo $this->Html->url("/"); ?>logo.png?12" alt="logo" />
                </div><?php if ($this->params['controller'] != "images") { ?>
                    <div class="span-18 ">
                        <ul id="menu">
                            <li>
                                <a href="<?php echo $this->Html->url("/"); ?>" <?php if ($this->params['controller'] == "home") { ?>class="sel"<?php } ?>>
                            <?php echo $this->Html->image("icons/dashboard.png", array("alt" => __('Dashboard', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Dashboard'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url("/mails"); ?>" <?php if ($this->params['controller'] == "recipients" || $this->params['controller'] == "mails" || $this->params['controller'] == "templates" || $this->params['controller'] == "campaigns") { ?>class="sel"<?php } ?>>
                            <?php echo $this->Html->image("icons/mail-send.png", array("alt" => __('Newsletter', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Newsletters'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url("/subscribers"); ?>" <?php if ($this->params['controller'] == "categories" || $this->params['controller'] == "subscribers" || $this->params['controller'] == "importtasks") { ?>class="sel"<?php } ?>>
                            <?php echo $this->Html->image("icons/xfn-colleague.png", array("alt" => __('Subscribers', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Subscribers'); ?>
                        </a>
                    </li>
                    <li>
                        <a href="<?php echo $this->Html->url("/configurations"); ?>" <?php if ($this->params['controller'] == "forms" || $this->params['controller'] == "configurations"|| $this->params['controller'] == "settings" || $this->params['controller'] == "users") { ?>class="sel"<?php } ?>>
                            <?php echo $this->Html->image("icons/wrench-screwdriver.png", array("alt" => __('Configurations', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Settings'); ?>
                        </a>
                    </li>
                </ul>
            </div>
            <div class="span-1 last">
                <div class="logintab" style="top: -14px; left: -283px;width:300px; position:relative;">
                    <?php __('Hello'); ?> <b><?php echo $session->read('Auth.User.username'); ?></b>
                    <?php echo $html->link(__('Logout', true), array('controller' => 'users', 'action' => 'logout')); ?>
                        </div>
                    </div><?php } else {
                    ?>
                            <div class="span-19 last">&nbsp;</div>
            <?php } ?>
                    </div>
                    <div class="container header" style="">
                        <div class="span-24">
                            <div style="padding: 3px;" class="topmenu">
                    <?php if ($this->params['controller'] == "settings" ||$this->params['controller'] == "configurations" || $this->params['controller'] == "forms" || $this->params['controller'] == "users") {
                    ?>
                            <a href="<?php echo $this->Html->url("/configurations"); ?>">
                        <?php echo $this->Html->image("icons/server-network.png", array("alt" => __('SMTP', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Mail Server Configurations'); ?>
                        </a>
                         <?php 
                             if ($this->Session->read('Auth.User.level') == "0") { ?>
                        <a href="<?php echo $this->Html->url("/users"); ?>">
                        <?php echo $this->Html->image("icons/users.png", array("alt" => __('Users', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Users'); ?>
                        </a>
                                <?php 
                                }
                                ?>
                        <a href="<?php echo $this->Html->url("/forms"); ?>">
                        <?php echo $this->Html->image("icons/application-form.png", array("alt" => __('Form', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Subscription Forms'); ?>
                        </a>
                                <?php 
                             if ($this->Session->read('Auth.User.level') == "0") { ?>
                               <a href="<?php echo $this->Html->url("/settings"); ?>">
                        <?php echo $this->Html->image("icons/wrench-screwdriver.png", array("alt" => __('Settings', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Application Settings'); ?>
                        </a>
                        <?php 
                                }
                                ?>
                        
                        <?php } ?>


                    <?php if ($this->params['controller'] == "categories" || $this->params['controller'] == "subscribers" || $this->params['controller'] == "importtasks") {
                    ?>
                            <a href="<?php echo $this->Html->url("/subscribers"); ?>">
                        <?php echo $this->Html->image("icons/xfn-colleague.png", array("alt" => __('Subscribers', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Subscribers'); ?>
                        </a>

                        <a href="<?php echo $this->Html->url("/categories"); ?>">
                        <?php echo $this->Html->image("icons/address-book.png", array("alt" => __('Categories', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Categories'); ?>
                        </a>


                        <a href="<?php echo $this->Html->url("/subscribers/import"); ?>">
                        <?php echo $this->Html->image("icons/database-import.png", array("alt" => __('Subscribers', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Import Subscribers'); ?>
                        </a>
                        <a href="<?php echo $this->Html->url("/subscribers/export"); ?>">
                        <?php echo $this->Html->image("icons/database-export.png", array("alt" => __('Subscribers', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Export Subscribers'); ?>
                        </a>
                        <a href="<?php echo $this->Html->url("/importtasks"); ?>">
                        <?php echo $this->Html->image("icons/databases-relation.png", array("alt" => __('Update from other DB', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Update from other DB'); ?>
                        </a>
                                <a href="<?php echo $this->Html->url("/subscribers/cleanup"); ?>">
                        <?php echo $this->Html->image("icons/eraser.png", array("alt" => __('Clean Up Subscribers', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Clean Up Subscribers'); ?>
                        </a>
                    <?php } ?>

                    <?php if ($this->params['controller'] == "recipients" || $this->params['controller'] == "mails"||  $this->params['controller'] == "campaigns" || $this->params['controller'] == "templates") {
                    ?>

                            <a href="<?php echo $this->Html->url("/mails"); ?>">
                        <?php echo $this->Html->image("icons/mails-stack.png", array("alt" => __('Newsletter', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Newsletters'); ?>
                        </a>

                        <a href="<?php echo $this->Html->url("/campaigns"); ?>">
                        <?php echo $this->Html->image("icons/chart-up.png", array("alt" => __('Campaign', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Campaigns'); ?>
                        </a>


                        <a href="<?php echo $this->Html->url("/templates"); ?>">
                        <?php echo $this->Html->image("icons/blog-blue.png", array("alt" => __('Template', true), "style" => "margin-bottom: -3px;")); ?> <?php __('Templates'); ?>
                        </a>
                           
                        
                        <?php } ?>


                </div>
            </div>
        </div>
        <div class="container foot" style="background:#FFFFFF">
            <div class="span-24  ">
                <div style="padding: 10px;">
                    <?php echo $this->Session->flash(); ?>

                    <?php echo $content_for_layout; ?>

                    </div>
                    <span class="version">Newsletter Mailer v1.395 (July 2013)</span>
                </div>
            </div>

        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
