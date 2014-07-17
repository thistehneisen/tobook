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


        </head>
        <body>


                    

                    <?php echo $content_for_layout; ?>
             
        <?php echo $this->element('sql_dump'); ?>
    </body>
</html>
