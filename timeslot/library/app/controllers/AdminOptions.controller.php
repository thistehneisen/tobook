<?php
require_once CONTROLLERS_PATH . 'Admin.controller.php';
/**
 * AdminOptions controller
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers
 */
class AdminOptions extends Admin
{
/**
 * (non-PHPdoc)
 * @see app/controllers/Admin::index()
 * @access public
 * @return void
 */
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				$opts = array();
				$opts['calendar_id'] = $this->getCalendarId();
				
				Object::import('Model', 'Option');
				$OptionModel = new OptionModel();
								
				$arr = $OptionModel->getAll(array_merge($opts, array('col_name' => 't1.group ASC, t1.order', 'direction' => 'asc')));
				
				$_arr = array();
				foreach ($arr as $i => $v)
				{
					if (!array_key_exists($v['group'], $_arr))
		            {
		                $_arr[$v['group']] = array();
		            }
		            $_arr[$v['group']][] = $v;
				}

				$this->tpl['arr'] = $_arr;
				
				$this->js[] = array('file' => 'colorpicker.js', 'path' => LIBS_PATH . 'jquery/plugins/colorpicker/js/');
				$this->css[] = array('file' => 'colorpicker.css', 'path' => LIBS_PATH . 'jquery/plugins/colorpicker/css/');
				$this->css[] = array('file' => 'layout.css', 'path' => LIBS_PATH . 'jquery/plugins/colorpicker/css/');
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'adminOptions.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Install
 *
 * @access public
 * @return void
 */
	function install()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				$this->js[] = array('file' => 'adminOptions.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Update
 *
 * @access public
 * @return void
 */
	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin() || $this->isOwner())
			{
				if (isset($_POST['options_update']))
				{
					if ($this->isDemo())
					{
						Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminOptions&action=index&err=7");
					}
					
					Object::import('Model', 'Option');
					$OptionModel = new OptionModel();
				
					foreach ($_POST as $key => $value)
					{
						if (preg_match('/value-(string|text|int|float|enum|color)-(.*)/', $key) === 1)
						{
							list(, $type, $k) = explode("-", $key);
							if (!empty($k))
							{
								$sql_value = $OptionModel->escape($value, null, $type);
								$k = $OptionModel->escape($k, null, 'string');
								mysql_query("UPDATE `".$OptionModel->getTable()."` SET `value` = '$sql_value' WHERE `calendar_id` = '".$this->getCalendarId()."' AND `key` = '$k' LIMIT 1") or die(mysql_error());
							}
						}
					}
					
					if (isset($_POST['username']) && isset($_POST['password']))
					{
						Object::import('Model', 'User');
						$UserModel = new UserModel();
						$data['username'] = $_POST['username'];
						$data['password'] = $_POST['password'];
						$data['id'] = $this->getUserId();
						if ($UserModel->update($data))
						{
							$_SESSION[$this->default_user]['password'] = $_POST['password'];
						}
					}
					
					Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminOptions&action=index&err=5&tab_id=" . $_POST['tab_id']);
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}