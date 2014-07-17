<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAdmin.controller.php';
class pjAdminOptions extends pjAdmin
{
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				$opts = array();
				$opts['t1.is_visible'] = 1;
				$arr = $this->models['pjOption']->getAll(array_merge($opts, array('col_name' => 't1.group ASC, t1.order', 'direction' => 'asc')));
			
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
				
				$this->js[] = array('file' => 'pjAdminOptions.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}

	function install()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				$this->js[] = array('file' => 'pjAdminOptions.js', 'path' => JS_PATH);
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
	
	function update()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				if (isset($_POST['options_update']))
				{
					if ($this->isDemo())
					{
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=index&err=AA07");
					}

					$this->models['pjOption']->execute(sprintf("UPDATE `%s` SET `value` = '1|0::0' WHERE `type` = 'bool' AND `is_visible` = '1'", $this->models['pjOption']->getTable()));
					
					foreach ($_POST as $key => $value)
					{
						if (preg_match('/value-(string|text|int|float|enum|color|bool)-(.*)/', $key) === 1)
						{
							list(, $type, $k) = explode("-", $key);
							if (!empty($k))
							{
								$this->models['pjOption']->execute(sprintf("UPDATE `%s` SET `value` = '%s' WHERE `key` = '%s' LIMIT 1",
									$this->models['pjOption']->getTable(),
									$this->models['pjOption']->escape($value, null, $type),
									pjObject::escapeString($k)
								));
							}
						}
					}

					if (isset($_POST['email']) && isset($_POST['password']))
					{
						pjObject::import('Model', 'pjUser');
						$pjUserModel = new pjUserModel();
						$data['email'] = $_POST['email'];
						$data['password'] = $_POST['password'];
						$data['id'] = $this->getUserId();
						if ($pjUserModel->update($data))
						{
							$_SESSION[$this->default_user]['password'] = $_POST['password'];
						}
					}
					
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminOptions&action=index&err=AO01&tab_id=" . $_POST['tab_id']);
				}
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
}