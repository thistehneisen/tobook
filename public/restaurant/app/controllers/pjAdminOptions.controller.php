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
				
				$this->js [] = array (
						'file' => 'jquery.ui.position.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/'
				);
				
				$this->js [] = array (
						'file' => 'jquery.ui.dialog.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/' 
				);
				$this->js [] = array (
						'file' => 'jabb-0.4.1.js',
						'path' => JS_PATH 
				);
				
				$this->css [] = array (
						'file' => 'jquery.ui.dialog.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/' 
				);
			
				$this->js [] = array (
						'file' => 'jquery.ui.button.min.js',
						'path' => LIBS_PATH . 'jquery/ui/js/'
				);
				$this->css [] = array (
						'file' => 'jquery.ui.button.css',
						'path' => LIBS_PATH . 'jquery/ui/css/smoothness/'
				);
					
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
	
	Public function getposts() {
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
			pjObject::import ( 'Model', 'pjPosts' );
			$pjPostsModel = new pjPostsModel ();
			
			$opts = array();
			if ( isset($_GET['search']) ) {
				$search = $_GET['search'];
				$opts['t1.post_title'] = array( "'%$search%'", 'LIKE', 'null');	
			}
			
			$diff = array('post', 'page');
			
			$this->tpl ['posts_arr'] = $pjPostsModel->getAll ( array_merge($opts, array (
							'col_name' => 't1.post_title',
							'direction' => 'asc',
							't1.post_type' => array( "('".join("','", $diff)."')", 'IN', 'null')
					)) ); 
		}
		
	}
	
	public function pjActionInsertContent() {
		$this->isAjax = true;
		
		if ($this->isXHR ()) {
			
			pjObject::import ( 'Model', 'pjPosts' );
			$pjPostsModel = new pjPostsModel ();
			
			if (isset($_POST['id'])) {
				
				$post = $pjPostsModel->getAll ( array (
						't1.ID' => $_POST['id']
				) );
				
				if ( count($post) > 0 ) {
					$post[0]['post_content'] = $post[0]['post_content'] . '<iframe src="' . INSTALL_URL . 'preview.php?rbpf=' . PREFIX .'" width="100%" height="800px"></iframe>';
				}
				//var_dump($post[0]);
				$pjPostsModel->update($post[0]);
			}
			
		}
		
		exit();
	}
}
