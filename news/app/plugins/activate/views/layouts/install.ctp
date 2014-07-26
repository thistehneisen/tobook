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
            <?php echo $title_for_layout; ?>
        </title>
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
                    $("input").tipTip({defaultPosition:"right"});
                    $("*").tipTip({defaultPosition:"top"});
                });
            </script>

        </head>
        <body>

            <div style=" font-size: 1.2em;    line-height: 1.6em;margin:3px;">
                <div  style="width:490px; text-align: left;"  class="login centerblock">

                    <div class="lbox1">
                    <?php echo $this->Session->flash(); ?>

                    <?php echo $content_for_layout; ?>
                </div>  </div>
        </div>
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
