<?php namespace App\Core\Session;

use SessionHandlerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionBagInterface;
use Symfony\Component\HttpFoundation\Session\Storage\MetadataBag;

class Store extends \Illuminate\Session\Store {

	/**
	 * {@inheritdoc}
	 */
	public function has($name)
	{
		if (is_tobook() && $name === 'stealthMode'){
			if (isset($_SESSION) && !empty($_SESSION['stealthMode'])) {
            	return true;
			}
        }
		return ! is_null($this->get($name));
	}

	/**
	 * {@inheritdoc}
	 */
	public function get($name, $default = null)
	{
		if (is_tobook() && $name === 'stealthMode'){
			if (isset($_SESSION) && !empty($_SESSION['stealthMode'])) {
            	$default = $_SESSION['stealthMode'];
			}
        }
		return array_get($this->attributes, $name, $default);
	}

}
