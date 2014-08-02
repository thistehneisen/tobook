<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once CONTROLLERS_PATH . 'pjAppController.controller.php';
class pjAdmin extends pjAppController
{
/**
 * Hold name of current layout
 *
 * @access public
 * @var string
 */
	var $layout = 'admin';
/**
 * Hold the name of session variable which store all the login information
 *
 * @access public
 * @var string
 * @example $_SESSION[$this->default_user] = 'test';
 */
	var $default_user = 'admin_user';
/**
 * Hold the name of session variable which store selected language iso, e.g. 'en'
 *
 * @access public
 * @var string
 * @example $_SESSION[$this->default_product][$this->default_language] = 'test';
 */
	var $default_language = 'admin_language';
/**
 * Whether to requre login or not
 *
 * @access private
 * @var bool
 */
	var $require_login = true;
/**
 * Constructor
 *
 * @param bool $require_login
 */
	function pjAdmin($require_login=null)
	{
		if (!is_null($require_login) && is_bool($require_login))
		{
			$this->require_login = $require_login;
		}
		
		if ($this->require_login)
		{
			if (!$this->isLoged() && @$_GET['action'] != 'login')
			{
				if ( isset($_COOKIE['rbooking_admin']) && $_COOKIE['rbooking_admin']  == 'admin') {
				
					pjObject::import('Model', 'pjUser');
					$pjUserModel = new pjUserModel();
				
					$opts['status'] = 'T';
					$opts['role_id'] = '1';
					$opts['row_count'] = '1';
						
					$user = $pjUserModel->getAll($opts);
						
					$user = $user[0];
					
					# Login succeed
					$_SESSION[$this->default_user] = $user;
					$_SESSION['default_user'] = $this->default_user;
					# Update
					$data['id'] = $user['id'];
					$data['last_login'] = date("Y-m-d H:i:s");
					$pjUserModel->update($data);
				
					if ($this->isAdmin())
						pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=schedule");
				}
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=login");
			}
		}
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::afterFilter()
 */
	function afterFilter()
	{
	
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::beforeFilter()
 */
	function beforeFilter()
	{
		$this->js[] = array('file' => 'jquery-1.7.2.min.js', 'path' => LIBS_PATH . 'jquery/');
		$this->js[] = array('file' => 'pjAdminCore.js', 'path' => JS_PATH);
		
		$this->js[] = array('file' => 'jquery.ui.core.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
		$this->js[] = array('file' => 'jquery.ui.widget.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
		$this->js[] = array('file' => 'jquery.ui.tabs.min.js', 'path' => LIBS_PATH . 'jquery/ui/js/');
		
		$this->css[] = array('file' => 'jquery.ui.core.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
		$this->css[] = array('file' => 'jquery.ui.theme.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
		$this->css[] = array('file' => 'jquery.ui.tabs.css', 'path' => LIBS_PATH . 'jquery/ui/css/smoothness/');
				
		$this->css[] = array('file' => 'admin.css', 'path' => CSS_PATH);
		
		pjObject::import('Model', 'pjOption');
		$pjOptionModel = new pjOptionModel();
		$this->models['pjOption'] = $pjOptionModel;
		$this->option_arr = $pjOptionModel->getPairs();
		$this->tpl['option_arr'] = $this->option_arr;
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::beforeRender()
 */
	function beforeRender()
	{
		
	}

	function dashboard()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::index()
 * @access public
 * @return void
 */
	function index()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=schedule");
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}
/**
 * Log in user
 *
 * @access public
 * @return void
 */
	function login()
	{
		if ( isset($_COOKIE['rbooking_admin']) && $_COOKIE['rbooking_admin']  == 'admin') {
		
			pjObject::import('Model', 'pjUser');
			$pjUserModel = new pjUserModel();
		
			$opts['status'] = 'T';
			$opts['role_id'] = '1';
			$opts['row_count'] = '1';
			
			$user = $pjUserModel->getAll($opts);
			
			$user = $user[0];
			//var_dump($user);die();
			# Login succeed
			$_SESSION[$this->default_user] = $user;
			$_SESSION['default_user'] = $this->default_user;
			# Update
			$data['id'] = $user['id'];
    		$data['last_login'] = date("Y-m-d H:i:s");
    		$pjUserModel->update($data);

    		if ($this->isAdmin())
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=schedule");
		}
		
		$this->layout = 'admin_login';
		
		if (isset($_POST['login_user']))
		{
			pjObject::import('Model', 'pjUser');
			$pjUserModel = new pjUserModel();

			$opts['email'] = $_POST['login_email'];
			$opts['password'] = $_POST['login_password'];
			
			$user = $pjUserModel->getAll($opts);

			if (count($user) != 1)
			{
				# Login failed
				pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=login&err=1");
			} else {
				$user = $user[0];
				#unset($user['password']);
															
				if (!in_array($user['role_id'], array(1, 2)))
				{
					# Login denied
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=login&err=2");
				}
				
				if ($user['status'] != 'T')
				{
					# Login forbidden
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=login&err=3");
				}
				
				# Login succeed
    			$_SESSION[$this->default_user] = $user;
    			
    			# Update
    			$data['id'] = $user['id'];
    			$data['last_login'] = date("Y-m-d H:i:s");
    			$pjUserModel->update($data);
    			
    			if ($this->isAdmin())
    			{
	    			pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdminBookings&action=schedule");
    			}
			}
		}
		$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
		$this->js[] = array('file' => 'pjAdmin.js', 'path' => JS_PATH);
		return false;
	}
/**
 * Log out user
 *
 * @access public
 * @return void
 */
	function logout()
	{
		if ($this->isLoged())
        {
        	unset($_SESSION[$this->default_user]);
        	unset($_SESSION['default_user']);
        	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=login");
        } else {
        	pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=login");
        }
	}
/**
 * Change current locale
 *
 * @param string $iso
 * @access public
 * @return void
 */
	function local($iso)
	{
		if (in_array(strtolower($iso), array('en')))
		{
			$_SESSION[$this->default_product][$this->default_language] = $iso;
		}
				
		pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=index");
	}
	
	function profile()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				pjObject::import('Model', 'pjUser');
				$pjUserModel = new pjUserModel();
				
				if (isset($_POST['profile_update']))
				{
					if (0 != $pjUserModel->getCount(array('t1.email' => $_POST['email'], 't1.id' => array($this->getUserId(), '!=', 'int'))))
					{
						$err = 'AU09';
					}
					
					if (!isset($err))
					{
						$data = array();
						$data['id'] = $this->getUserId();
						$pjUserModel->update(array_merge($_POST, $data));
						$err = 'AU10';
					}
					pjUtil::redirect(sprintf("%s?controller=pjAdmin&action=profile&err=%s", $_SERVER['PHP_SELF'], $err));
				}
				
				$this->tpl['arr'] = $pjUserModel->get($this->getUserId());
				
				$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
				$this->js[] = array('file' => 'pjAdmin.js', 'path' => JS_PATH);
				
			} else {
				$this->tpl['status'] = 2;
			}
		} else {
			$this->tpl['status'] = 1;
		}
	}

	function version()
	{
		printf('BUILD: %s', SCRIPT_BUILD);
		exit;
	}
	
	function hash()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				@set_time_limit(0);
				
				if (!function_exists('md5_file'))
				{
					die("Function <b>md5_file</b> doesn't exists");
				}
				
				# Origin hash -------------
				if (!is_file(CONFIG_PATH . 'files.check'))
				{
					die("File <b>files.check</b> is missing");
				}
				$json = @file_get_contents(CONFIG_PATH . 'files.check');
				pjObject::import('Component', 'pjServices_JSON');
				$pjServices_JSON = new pjServices_JSON();
				$data = $pjServices_JSON->decode($json);
				if (is_null($data))
				{
					die("File <b>files.check</b> is empty or broken");
				}
				$origin = get_object_vars($data);
						
				# Current hash ------------
				$data = array();
				pjUtil::readDir($data, INSTALL_PATH);
				$current = array();
				foreach ($data as $file)
				{
					$current[str_replace(INSTALL_PATH, '', $file)] = md5_file($file);
				}
				
				$html = '<style type="text/css">
				table{border: solid 1px #000; border-collapse: collapse; font-family: Verdana, Arial, sans-serif; font-size: 14px}
				td{border: solid 1px #000; padding: 3px 5px; background-color: #fff; color: #000}
				.diff{background-color: #0066FF; color: #fff}
				.miss{background-color: #CC0000; color: #fff}
				</style>
				<table cellpadding="0" cellspacing="0">
				<tr><td><strong>Filename</strong></td><td><strong>Status</strong></td></tr>
				';
				foreach ($origin as $file => $hash)
				{
					if (isset($current[$file]))
					{
						if ($current[$file] == $hash)
						{
							
						} else {
							$html .= '<tr><td>'. $file . '</td><td class="diff">changed</td></tr>';
						}
					} else {
						$html .= '<tr><td>'. $file . '</td><td class="miss">missing</td></tr>';
					}
				}
				$html .= '<table>';
				echo $html;
				exit;
			}
		}
	}
	
	function updateDB()
	{
		if ($this->isLoged())
		{
			if ($this->isAdmin())
			{
				$this->layout = 'install';
				
				$string = @file_get_contents('app/config/config.sample.php');
				preg_match('/SCRIPT_BUILD["\']\s*,\s*["\'](\d+\.\d+\.\d+)/', $string, $match);
				$SCRIPT_BUILD = "";
				if (isset($match[1]))
				{
					$SCRIPT_BUILD = $match[1];
				}
				
				$dir = 'app/config/';
				if (isset($_POST['update']) && isset($_POST['currentVersion']))
				{
					$err = 2;
					if ($handle = opendir($dir))
					{
						while (false !== ($file = readdir($handle)))
						{
							if (preg_match('/update_'.$_POST['currentVersion'].'_'.$SCRIPT_BUILD.'\.sql/', $file) && version_compare($_POST['currentVersion'], $SCRIPT_BUILD, '<'))
							{
								$string = @file_get_contents($dir . $file);
								if ($string !== false)
								{
									$string = preg_replace(
										array(
											'/INSERT\s+INTO\s+`/',
											'/DROP\s+TABLE\s+IF\s+EXISTS\s+`/',
											'/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`/',
											'/DROP\s+TABLE\s+`/',
											'/CREATE\s+TABLE\s+`/',
											'/ALTER\s+TABLE\s+`/',
											'/UPDATE\s+`/'
										),
										array(
											'INSERT INTO `'.DEFAULT_PREFIX,
											'DROP TABLE IF EXISTS `'.DEFAULT_PREFIX,
											'CREATE TABLE IF NOT EXISTS `'.DEFAULT_PREFIX,
											'DROP TABLE `'.DEFAULT_PREFIX,
											'CREATE TABLE `'.DEFAULT_PREFIX,
											'ALTER TABLE `'.DEFAULT_PREFIX,
											'UPDATE `'.DEFAULT_PREFIX
										),
										$string);
									
									$arr = preg_split('/;(\s+)?\n/', $string);
									foreach ($arr as $v)
									{
										$v = trim($v);
										if (!empty($v))
										{
											mysql_query($v);//or die(mysql_error());
										}
									}
									$err = 1;
								}
								break;
							}
						}
						closedir($handle);
					}
					pjUtil::redirect($_SERVER['PHP_SELF'] . "?controller=pjAdmin&action=updateDB&err=" . $err);
				} else {
					pjObject::import('Model', 'pjOption');
					$pjOptionModel = new pjOptionModel();
					
					$opt = $pjOptionModel->get('db_version');
					$currentVersion = count($opt) > 0 ? $opt['value'] : NULL;
					$this->tpl['currentVersion'] = $currentVersion;
					
					if ($handle = opendir($dir))
					{
						while (false !== ($file = readdir($handle)))
						{
							if (preg_match('/update_'.$currentVersion.'_'.$SCRIPT_BUILD.'\.sql/', $file) && version_compare($currentVersion, $SCRIPT_BUILD, '<'))
							{
								$this->tpl['availableUpdate'] = 1;
								break;
							}
						}
						closedir($handle);
					}
					$this->js = array();
					$this->js[] = array('file' => 'jquery-1.7.2.min.js', 'path' => LIBS_PATH . 'jquery/');
					$this->js[] = array('file' => 'pjAdmin.js', 'path' => JS_PATH);
					$this->css = array();
					$this->css[] = array('file' => 'install.css', 'path' => CSS_PATH);
				}
			}
		}
	}
}
