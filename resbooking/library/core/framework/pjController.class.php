<?php
require_once FRAMEWORK_PATH . 'pjObject.class.php';
/**
 * Main controller
 *
 * @package ebc
 * @subpackage ebc.core.framework
 */
class pjController extends pjObject
{
/**
 * Hold results from controller logic, and use it in views
 *
 * @access public
 * @var array
 * @example $this->tpl['arr'] = array('foo', 'bar');
 */
	var $tpl;
/**
 * Hold path to JS files, use it in views/layouts
 *
 * @access public
 * @var array
 * @example $this->js[] = array('file' => 'admin.js', 'path' => JS_PATH);
 */
	var $js = array();
/**
 * Hold path to CSS files, use it in views/layouts
 *
 * @access public
 * @var array
 * @example $this->css[] = array('file' => 'admin.css', 'path' => CSS_PATH);
 */
	var $css = array();
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
	var $default_language = 'language';
/**
 * Hold the name of session variable which store product label
 *
 * @access public
 * @var string
 * @example $_SESSION[$this->default_product] = 'test';
 */
	var $default_product = 'product';
/**
 * A hash used to 'salt' passwords
 *
 * @access public
 * @var string
 * @example sha1($_POST['password'] . $this->salt)
 */
	var $salt = '1y$g*ac:V4';
/**
 * Hold name of current layout
 *
 * @access public
 * @var string
 */
	var $layout = 'default';
/**
 * Set this to boolean 'true' if expect AJAX method, default is 'false'.
 *
 * @var bool
 */
	var $isAjax = false;
/**
 * In demo mode some operations are not permitted.
 *
 * @access public
 * @var bool
 */
	var $isDemo = false;
/**
 * Constructor
 */
	function pjController()
	{
		
	}
/**
 * This function is executed before every action in the controller. Its a handy place to check for an active session or inspect user permissions.
 *
 * @access public
 */
	function beforeFilter()
	{
		
	}
/**
 * Called after controller action logic, but before the view is rendered.
 *
 * @access public
 */
	function beforeRender()
	{
		
	}
/**
 * Called after every controller action.
 *
 * @access public
 */
	function afterFilter()
	{
		
	}
/**
 * Called after an action has been rendered.
 *
 * @access public
 */
	function afterRender()
	{
		
	}
/**
 * Default action
 *
 * @access public
 */
	function index()
	{
		
	}
/**
 * Check if method is AJAX
 *
 * @access public
 * @return bool
 */
	function isAjax()
    {
    	return $this->isAjax;
    }
/**
 * Get current layout
 *
 * @access public
 * @return string
 */
    function getLayout()
    {
    	return $this->layout;
    }
/**
 * Get session language
 *
 * @access public
 * @return string
 */
	function getLanguage()
    {
    	return isset($_SESSION[$this->default_product]) &&
    		isset($_SESSION[$this->default_product][$this->default_language]) &&
    		!empty($_SESSION[$this->default_product][$this->default_language]) ?
    			$_SESSION[$this->default_product][$this->default_language] :
    			'en';
    }
/**
 * Get user ID
 *
 * @access public
 * @return int|false
 */
	function getUserId()
    {
    	return isset($_SESSION[$this->default_user]) && array_key_exists('id', $_SESSION[$this->default_user]) ? $_SESSION[$this->default_user]['id'] : false;
    }
/**
 * Get user's role ID
 *
 * @access public
 * @return int|false
 */
    function getRoleId()
    {
    	return isset($_SESSION[$this->default_user]) && array_key_exists('role_id', $_SESSION[$this->default_user]) ? $_SESSION[$this->default_user]['role_id'] : false;
    }
/**
 * Check user if is loged in
 *
 * @access public
 * @return bool
 */
	function isLoged()
    {
        if (isset($_SESSION[$this->default_user]) && count($_SESSION[$this->default_user]) > 0)
        {
            return true;
	    }
	    return false;
    }
/**
 * Check user against 'Admin' role
 *
 * @access public
 * @return bool
 */
	function isAdmin()
    {
   		return $this->getRoleId() == 1;
    }
/**
 * Check for demo mode
 *
 * @access public
 * @return bool
 */
	function isDemo()
    {
   		return $this->isDemo;
    }
/**
 * Get file extension
 *
 * @param string $str File name
 * @access public
 * @static
 * @return string
 */
	function getFileExtension($str)
    {
    	$arrSegments = explode('.', $str); // may contain multiple dots
        $strExtension = $arrSegments[count($arrSegments) - 1];
        $strExtension = strtolower($strExtension);
        return $strExtension;
    }
/**
 * Check if request is send via AJAX
 *
 * @access public
 * @static
 * @return bool
 */
    function isXHR()
    {
		return @$_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest';
    }
/**
 * Download a file
 *
 * @param string $data
 * @param string $name
 * @param string $mimetype
 * @param int $filesize
 * @access public
 * @static
 * @return binary
 */
	function download($data, $name, $mimetype='', $filesize=false)
	{
	    // File size not set?
	    if ($filesize == false || !is_numeric($filesize))
	    {
	        $filesize = strlen($data);
	    }
	
	    // Mimetype not set?
	    if (empty($mimetype))
	    {
	        $mimetype = 'application/octet-stream';
	        //$mimetype = 'application/force-download';
	    }
		
	    // Start sending headers
	    header("Pragma: public"); // required
	    header("Expires: 0"); // no cache
	    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
	    header("Cache-Control: private",false); // required for certain browsers
	    header("Content-Transfer-Encoding: binary");
	    header("Content-Type: " . $mimetype);
	    header("Content-Length: " . $filesize);
	    header("Content-Disposition: attachment; filename=\"" . $name . "\";" );
	
		// download
		echo $data;
		//die();
	}
/**
 * Generate random string. Suitable for password generation.
 *
 * @param int $n
 * @param string $chars
 * @access public
 * @static
 * @return string
 */
	function getRandomPassword($n = 6, $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890')
	{
		srand((double)microtime()*1000000);
		$m = strlen($chars);
		$randPassword = "";
		while($n--)
		{
			$randPassword .= substr($chars,rand()%$m,1);
		}
		return $randPassword;
	}
	
	function setDefaultProduct($str)
	{
		$this->default_product = $str;
		return $this;
	}
}
?>