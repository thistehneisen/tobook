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
/**
 * Used to invoke the Dispatcher
 *
 * @package framework
 * @since 1.0.0
 */
class pjObserver
{
/**
 * @var object
 * @access private
 */
	private $controller;

    public function __construct() {
        spl_autoload_register(function($name){
            $controllerFile = PJ_APP_PATH . 'controllers/' . $name . '.controller.php';

            $modelFile = PJ_APP_PATH . 'models/' . $name  . '.model.php';
            $modelFileWithModel = PJ_APP_PATH . 'models/' . str_replace('Model', '.model', $name) . '.php';

            $componentFile = PJ_FRAMEWORK_PATH . 'components/' . $name . '.component.php';
            $controllerComponent = PJ_APP_PATH . 'controllers/components/' .$name . '.component.php';

            $classFile = PJ_FRAMEWORK_PATH . $name . '.class.php';

            if (realpath($controllerFile)) {
                require_once($controllerFile);
            } else if (realpath($modelFile)) {
                require_once($modelFile);
            } else if(realpath($modelFileWithModel)){
                require_once($modelFileWithModel);
            } else if(realpath($classFile)){
                require_once($classFile);
            } else if(realpath($componentFile)) {
                require_once($componentFile);
            } else if (realpath($controllerComponent)){
                require_once($controllerComponent);
            } else if (realpath($name . '.php')) {
                require_once($name . '.php');
            }
        });
        // spl_autoload_register(array($this,'autoLoadController'), true, true);
        // spl_autoload_register(array($this,'autoLoadModel'), true, true);
        // spl_autoload_register(array($this,'autoLoadComponent'), true, true);
    }

    public static function autoLoadController($name){
        $controllerFile = PJ_APP_PATH . 'controllers/' . $name . '.controller.php';
        if (realpath($controllerFile)) {
            require_once($controllerFile);
        }
    }

    public static function autoLoadModel($name){
        $modelFile = PJ_APP_PATH . 'models/' . $name  . '.model.php';
        $modelFileWithModel = PJ_APP_PATH . 'models/' . str_replace('Model', '.model', $name) . '.php';
         if (realpath($modelFile)) {
            require_once($modelFile);
        } else if(realpath($modelFileWithModel)){
            require_once($modelFileWithModel);
        }
    }

    public function autoLoadComponent($name){
        $componentFile = PJ_FRAMEWORK_PATH . 'components/' . $name . '.component.php';
        $controllerComponent = PJ_APP_PATH . 'controllers/components/' .$name . '.component.php';
        if(realpath($controllerComponent)){
            require_once($controllerComponent);
        } else if(realpath($componentFile)) {
            require_once($componentFile);
        } 
    }

/**
 * The Factory pattern allows for the instantiation of objects at runtime.
 *
 * @param array Array with parameters passed to class constructor.
 * @access public
 * @static
 * @return self Instance of a `pjObserver`
 */
	public static function factory($attr=array())
	{
		return new pjObserver($attr);
	}
/**
 * Initialize
 *
 * @access public
 * @return void
 */
	public function init()
	{
		require_once dirname(__FILE__) . '/pjObject.class.php';
		require_once dirname(__FILE__) . '/pjDispatcher.class.php';
		
		if (isset($GLOBALS['CONFIG']['plugins']))
		{
			pjObject::import('Plugin', $GLOBALS['CONFIG']['plugins']);
		}
		$Dispatcher = new pjDispatcher();
		$Dispatcher->dispatch($_GET, array());
		$this->controller = $Dispatcher->getController();
	}
/**
 * Gets the controller object
 *
 * @access public
 * @return object Instance of a requested controller
 */
	public function getController()
	{
		return $this->controller;
	}
}
?>
