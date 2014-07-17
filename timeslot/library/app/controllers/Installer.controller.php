<?php
require_once FRAMEWORK_PATH . 'Controller.class.php';
/**
 * Installer controller
 *
 * @package tsbc
 * @subpackage tsbc.app.controllers
 */
class Installer extends Controller
{
/**
 * Hold layout
 *
 * @access public
 * @var string
 */
	var $layout = 'install';
/**
 * Constructor
 */
	function Installer()
	{

	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::beforeFilter()
 */
	function beforeFilter()
	{
		$this->js[] = array('file' => 'jquery-1.5.2.min.js', 'path' => LIBS_PATH . 'jquery/');
		$this->css[] = array('file' => 'install.css', 'path' => CSS_PATH);
	}
/**
 * Import SQL dump file, and create admin account
 *
 * @param string $file
 * @param string $admin_username
 * @param string $admin_password
 * @param string $prefix
 * @access public
 * @return void
 */
	function importSQL($file, $admin_username, $admin_password, $prefix)
	{
		$string = file_get_contents($file);
		$string = preg_replace(
			array('/INSERT\s+INTO\s+`/', '/DROP\s+TABLE\s+IF\s+EXISTS\s+`/', '/CREATE\s+TABLE\s+IF\s+NOT\s+EXISTS\s+`/', '/DROP\s+TABLE\s+`/', '/CREATE\s+TABLE\s+`/'), 
			array('INSERT INTO `'.$prefix, 'DROP TABLE IF EXISTS `'.$prefix, 'CREATE TABLE IF NOT EXISTS `'.$prefix, 'DROP TABLE `'.$prefix, 'CREATE TABLE `'.$prefix), 
			$string);
		
		$arr = preg_split('/;(\s+)?\n/', $string);
		foreach ($arr as $v)
		{
			$v = trim($v);
			if (!empty($v))
			{
				mysql_query($v) or die(mysql_error());
			}
		}
		require('app/config/config.inc.php');
		Object::import('Model', 'User');
		$UserModel = new UserModel();
		$UserModel->prefix = $prefix;
		
		$data['username'] = $admin_username;
		$data['role_id'] = 1;
		$data['session_id'] = md5(uniqid(rand(), true));
		$data['password'] = $admin_password;
		$UserModel->save($data);
	}
/**
 * (non-PHPdoc)
 * @see core/framework/Controller::index()
 */
	function index()
	{
		Util::redirect($_SERVER['PHP_SELF'] . "?controller=Installer&action=step1&install=1");
	}
/**
 * Step 0 from installation
 *
 * @access public
 * @return void
 */
	function step0()
	{
		
	}
/**
 * Step 1 from installation
 *
 * @access public
 * @return void
 */
	function step1()
	{
		if ($this->isDemo())
		{
			$this->tpl['status'] = 7;
			return;
		}
		
		$filename = 'app/config/config.inc.php';
		
		$err = 0;
		$err_arr = array();
		if (!is_writable($filename))
		{
		    $err = 1;
		    $err_arr[] = array('file', $filename, 'You need to set write permissions (chmod 777) to options file located at');
		}

		$folders = array();
		foreach ($folders as $dir)
		{
			if (!is_writable($dir))
			{
				$err = 1;
				$err_arr[] = array('folder', $dir, 'You need to set write permissions (chmod 777) to directory located at');
			}
		}
		
		if ($err == 0)
		{
			$this->tpl['status'] = 'ok';
		} else {
			$this->tpl['status'] = $err;
			$this->tpl['err_arr'] = $err_arr;
		}
			
		$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
		$this->js[] = array('file' => 'installer.js', 'path' => JS_PATH);
	}
/**
 * Step 2 from installation
 *
 * @access public
 * @return void
 */
	function step2()
	{
		if ($this->isDemo())
		{
			$this->tpl['status'] = 7;
			return;
		}
		
		if (isset($_POST['step1']))
		{
			$_SESSION['Installer'] = $_POST;
			
			$err = 0;
			$link = @mysql_connect($_POST['hostname'], $_POST['username'], $_POST['password']);
			if (!$link)
			{
			    $err = 2;
			} else {
				$db_selected = mysql_select_db($_POST['database'], $link);
				if (!$db_selected)
				{
				    $err = 3;
				}
			}
			
			if ($err == 0)
			{
				$this->tpl['status'] = 'ok';
			} else {
				$this->tpl['status'] = $err;
			}
			$this->js[] = array('file' => 'jquery.validate.min.js', 'path' => LIBS_PATH . 'jquery/plugins/validate/js/');
			$this->js[] = array('file' => 'installer.js', 'path' => JS_PATH);
		}
	}
/**
 * Step 3 from installation
 *
 * @access public
 * @return void
 */
	function step3()
	{
		if (isset($_POST['step2']))
		{
			@set_time_limit(240); //seconds
			
			$this->tpl['status'] = true;
			$filename = 'app/config/config.inc.php';
			$string = file_get_contents('app/config/config.sample.php');
			if ($string === FALSE)
			{
				exit;
			}
			
			$doc_root = $_SERVER['DOCUMENT_ROOT'];
			$doc_root = str_replace('\\', '/', $doc_root);

			$cwd = getcwd();
			$cwd = str_replace('\\', '/', $cwd);

			$folder = str_replace($doc_root, '', $cwd);
			$folder = preg_replace('/^\//', '', $folder, 1) . '/';

			$string = str_replace('[hostname]', $_POST['hostname'], $string);
			$string = str_replace('[username]', $_POST['username'], $string);
			$string = str_replace('[password]', $_POST['password'], $string);
			$string = str_replace('[database]', $_POST['database'], $string);
			$string = str_replace('[prefix]', $_POST['prefix'], $string);
			$string = str_replace('[folder]', $folder, $string);
			$string = str_replace('[install_folder]', '/' . $folder, $string);
			$string = str_replace('[install_path]', $cwd . '/', $string);
			$string = str_replace('[install_url]', 'http://' . $_SERVER['SERVER_NAME'] . '/' . $folder, $string);

			if (is_writable($filename))
			{
			    if (!$handle = fopen($filename, 'wb'))
			    {
					exit;
			    }
			    
			    if (fwrite($handle, $string) === FALSE)
			    {
					exit;
			    }

			    fclose($handle);
			} else {
				exit;
			}
			
			$link = mysql_connect($_POST['hostname'], $_POST['username'], $_POST['password']);
			if (!$link)
			{
			    exit;
			}
			mysql_query("SET NAMES 'utf8'", $link);
			$db_selected = mysql_select_db($_POST['database'], $link);
			if (!$db_selected)
			{
			    exit;
			}
		
			$this->importSQL('app/config/database.sql', $_POST['admin_username'], $_POST['admin_password'], $_POST['prefix']);
			
			Util::redirect($_SERVER['PHP_SELF'] . "?controller=AdminCalendars&action=view&cid=1");
		}
	}
}
?>