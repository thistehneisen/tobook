<?php
require_once MODELS_PATH . 'App.model.php';
/**
 * Option model
 *
 * @package tsbc
 * @subpackage tsbc.app.models
 */
class OptionModel extends AppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to boolean null
 *
 * @var string
 * @access public
 */
	var $primaryKey = null;
/**
 * The name of table associate with current model
 *
 * @var string
 * @access protected
 */
	var $table = 'ts_options';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'calendar_id', 'type' => 'int', 'default' => ':NULL'),
        array('name' => 'owner_id', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'key', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'tab_id', 'type' => 'tinyint', 'default' => ':NULL'),
		array('name' => 'value', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'description', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'label', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'varchar', 'default' => 'string'),
		array('name' => 'order', 'type' => 'int', 'default' => ':NULL')
	);
/**
 * (non-PHPdoc)
 * @see core/framework/Model::get()
 * @param int $calendar_id
 * @param string $key
 * @access public
 * @return array
 */
	function get($calendar_id, $key = null)
	{
		$arr = array();
		$sql_key = $this->escapeString($key);
		$sql_calendar_id = intval($calendar_id);
		$owner_id = (int) $_SESSION['owner_id'];
		$r = mysql_query("SELECT * FROM `".$this->getTable()."` WHERE `calendar_id` = '$sql_calendar_id' AND `key` = '$sql_key' AND `owner_id` = {$owner_id} LIMIT 1");
		if (mysql_num_rows($r) == 1)
		{
			$row = mysql_fetch_object($r);
			$f = $this->showColumns($this->getTable());
			for($j = 0; $j < count($f); $j++)
			{
				$arr[$f[$j]['field']] = $row->$f[$j]['field'];
			}
		}
		return $arr;
	}
/**
 * Get array of key => values. Raw version
 *
 * @param int $calendar_id
 * @access public
 * @return array
 */
	function getAllPairs($calendar_id)
	{
		$arr = array();
		$owner_id = (int) $_SESSION['owner_id'];
		$r = mysql_query("SELECT * FROM `".$this->getTable()."` WHERE `owner_id` = {$owner_id} AND `calendar_id` = '".intval($calendar_id)."'");
		if (mysql_num_rows($r) > 0)
		{
			while ($row = mysql_fetch_object($r))
			{
				$arr[$row->key] = $row->value;
			}
		}
		return $arr;
	}
/**
 * Get array of key => values. Clear special values
 *
 * @param int $calendar_id
 * @access public
 * @return array
 */
	function getPairs($calendar_id)
	{
		$arr = array();
		$owner_id = (int) $_SESSION['owner_id'];
		$r = mysql_query("SELECT * FROM `".$this->getTable()."` WHERE `owner_id` = {$owner_id} AND `calendar_id` = '".intval($calendar_id)."'");
		if (mysql_num_rows($r) > 0)
		{
			while ($row = mysql_fetch_object($r))
			{
				switch ($row->type)
				{
					case 'enum':
						list(, $arr[$row->key]) = explode("::", $row->value);
						break;
					default:
						$arr[$row->key] = $row->value;
						break;
				}
			}
		}
		return $arr;
	}
}
?>
