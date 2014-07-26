<?php
/**
 * Requests collector.
 *
 *  This file collects requests if:
 *	- no mod_rewrite is avilable or .htaccess files are not supported
 *  - requires App.baseUrl to be uncommented in app/config/core.php
 *	- app/webroot is not set as a document root.
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
 * @since         CakePHP(tm) v 0.2.9
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
die("Please check if you have uploaded the .htaccess file. This file is mostly hidden if you are using the mac or linux operating system. <a href=\"http://ynhwebdev.tumblr.com/post/19959026138/fix-500-internal-server-error\">Please follow these instructions</a>");
