<?php
/**
 * Upload component
 *
 * @package erp
 * @subpackage erp.app.controllers.components
 */
class pjUpload
{
	var $file;
	var $error;

	function pjUpload()
	{
		
	}

	function load($file)
	{
		if (is_array($file) && array_key_exists('tmp_name', $file) && !empty($file['tmp_name']) && is_uploaded_file($file['tmp_name']))
		{
			$this->file = $file;
			return true;
		}
		$this->error = "The file is empty or wasn't uploaded via HTTP POST";
		return false;
	}
	
	function save($destination)
	{
		if (!move_uploaded_file($this->file['tmp_name'], $destination))
		{
			$this->error = $this->file['name'] . " is not a valid upload file or cannot be moved for some reason.";
			return false;
		}
		return true;
	}
	
	function getError()
	{
		return $this->error;
	}
	
	function getExtension()
    {
    	$arr = explode('.', $this->file['name']);
        $ext = strtolower($arr[count($arr) - 1]);
        return $ext;
    }
    
    function getSize()
    {
    	return getimagesize($this->file['tmp_name']);
    }
    
    function getFile($key)
    {
    	return $this->file[$key];
    }
}
?>