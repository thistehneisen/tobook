<?php
/**
 * Main class
 *
 * @package tsbc
 * @subpackage tsbc.core.framework
 */
class Object
{
/**
 * Get the highest value of 'order' column increased by one
 *
 * @param string $table
 * @param array $conditions
 * @access public
 * @return int
 * @static
 */
	function _getNextOrder($table, $conditions=array())
	{
		$sql_conditions = "";
		if (count($conditions) > 0)
		{
			foreach ($conditions as $key => $value)
			{
				$sql_conditions .= " AND `$key` = '$value' ";
			}
		}
		$r     = mysql_query("SELECT MAX(`order`) AS `max` FROM `".$table."` WHERE 1=1 $sql_conditions ");
		$row   = mysql_fetch_object($r);
		return ($row->max == 'NULL') ? 1 : $row->max + 1;
	}
/**
 * Escapes special characters in a string for use in a SQL statement
 *
 * @param string $value Unescaped string
 * @access public
 * @return string
 * @static
 */
 	function escapeString($value)
    {
    	if (get_magic_quotes_gpc())
    	{
    		$value = stripslashes($value);
    	}
    	return function_exists('mysql_real_escape_string') ? mysql_real_escape_string($value) : mysql_escape_string($value);
    }
/**
 * Execute bulk 'INSERT' SQL statement
 *
 * @param string $table
 * @param string $foreignKey
 * @param string $associationKey
 * @param mixed $fk_value
 * @param array $data
 * @access public
 * @return bool
 * @static
 */
    function addLinked($table, $foreignKey, $associationKey, $fk_value, $data)
    {
		if (is_array($data))
		{
			$arr = array();
		    foreach($data as $v)
		    {
		    	if (!empty($v))
		    	{
			    	$arr[] = "('$fk_value', '$v')";
		    	}
		    }
		    if (count($arr) > 0)
			{
				$values = join(",", $arr);
		    	if (!mysql_query("INSERT INTO `$table`(`$foreignKey`, `$associationKey`) VALUES $values;"))
		    	{
		    		return false;
		    	}
		    	return true;
			}
		}
		return false;
    }
/**
 * Execute 'SELECT' SQL statement
 *
 * @param string $table
 * @param string $foreignKey
 * @param string $associationKey
 * @param mixed $fk_value
 * @access public
 * @return array
 * @static
 */
	function getLinked($table, $foreignKey, $associationKey, $fk_value)
	{
		$arr = array();
		$r = mysql_query("SELECT `$associationKey` FROM `$table` WHERE `$foreignKey` = '$fk_value'") or die(mysql_error());
		if(mysql_num_rows($r) > 0)
		{
			while($row = mysql_fetch_object($r))
			{
				$arr[] = $row->$associationKey;
			}
		}
		return $arr;
	}
/**
 * Execute 'DELETE' SQL statement
 *
 * @param string $table
 * @param string $foreignKey
 * @param mixed $value
 * @access public
 * @return void
 * @static
 */
    function deleteAllLinked($table, $foreignKey, $value)
    {
		//$value = intval($value);
		mysql_query("DELETE FROM `$table` WHERE `$foreignKey` = '$value'") or die(mysql_error());
    }
/**
 * Get column information from given table
 *
 * @param string $table
 * @param string $database
 * @access public
 * @return array
 * @static
 */
	function showColumns($table, $database = null)
    {
    	$arr = array();
    	
    	$database = (!is_null($database)) ? " FROM `$database`" : null;
		$r = mysql_query("SHOW COLUMNS FROM `".$table."` $database") or die(mysql_error());
		if (mysql_num_rows($r) > 0)
		{
			$i = 0;
			while ($row = mysql_fetch_object($r))
			{
				$arr[$i]['field']   = $row->Field;
				$arr[$i]['type']    = $row->Type;
				$arr[$i]['null']    = $row->Null;
				$arr[$i]['key']     = $row->Key;
				$arr[$i]['default'] = $row->Default;
				$arr[$i]['extra']   = $row->Extra;
				$i++;
			}
		}
    	return $arr;
    }
/**
 * Finds and require() classes based on $name and $type.
 *
 * @param string $type The type of Class. Possible values (case-insensitive) are: 'Model' and 'Component'.
 * @param array|string $name Name of the Class
 * @access public
 * @return void
 * @static
 */
	function import($type, $name)
	{
		$type = strtolower($type);
		if (!in_array($type, array('model', 'component')))
		{
			return false;
		}
		
		switch ($type)
		{
			case 'model':
				if (is_array($name))
				{
					foreach ($name as $n)
					{
						require_once MODELS_PATH . $n . '.model.php';
					}
				} else {
					require_once MODELS_PATH . $name . '.model.php';
				}
				break;
			case 'component':
				if (is_array($name))
				{
					foreach ($name as $n)
					{
						require_once COMPONENTS_PATH . $n . '.component.php';
					}
				} else {
					require_once COMPONENTS_PATH . $name . '.component.php';
				}
				break;
		}
		return;
	}
}
?>