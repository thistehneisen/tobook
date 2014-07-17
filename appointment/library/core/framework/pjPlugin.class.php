<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
/**
 * PHP Framework
 *
 * @copyright Copyright 2013, StivaSoft, Ltd. (http://stivasoft.com)
 * @link      http://www.phpjabbers.com/
 * @package   framework
 * @version   1.0.11
 */
require_once PJ_CONTROLLERS_PATH . 'pjAppController.controller.php';
/**
 * Plugin's ancestor class
 *
 * @package framework
 * @since 1.0.0
 */
class pjPlugin extends pjAppController
{
/**
 * Invoked after script install
 *
 * Returns array with indexes:
 * - `code` Any code that differ than 200 are treated as an error and will cause stop the process
 * - `info` Array with text describing the errors
 *
 * Example:
 * <code>
 * return array('code' => 200, 'info' => array());
 * //or
 * return array('code' => 101, 'info' => array('The folder need write permissions', 'File not found', '...'));
 * </code>
 *
 * @access public
 * @return array
 */
	public function pjActionAfterInstall()
	{
		
	}
/**
 * Invoked before script install
 *
 * Returns array with indexes:
 * - `code` Any code that differ than 200 are treated as an error and will cause stop the process
 * - `info` Array with text describing the errors
 *
 * Example:
 * <code>
 * return array('code' => 200, 'info' => array());
 * //or
 * return array('code' => 101, 'info' => array('The folder need write permissions', 'File not found', '...'));
 * </code>
 *
 * @access public
 * @return array
 */
	public function pjActionBeforeInstall()
	{
		
	}
}
?>