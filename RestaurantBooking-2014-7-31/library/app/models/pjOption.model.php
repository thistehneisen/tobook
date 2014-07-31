<?php
if (!defined("ROOT_PATH"))
{
	header("HTTP/1.1 403 Forbidden");
	exit;
}
require_once MODELS_PATH . 'pjApp.model.php';
class pjOptionModel extends pjAppModel
{
/**
 * The name of table's primary key. If PK is over 2 or more columns set this to boolean null
 *
 * @var string
 * @access public
 */
	var $primaryKey = 'id';
/**
 * The name of table associate with current model
 *
 * @var string
 * @access protected
 */
	var $table = 'restaurant_booking_options';
/**
 * Table schema
 *
 * @var array
 * @access protected
 */
	var $schema = array(
		array('name' => 'key', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'value', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'description', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'label', 'type' => 'text', 'default' => ':NULL'),
		array('name' => 'type', 'type' => 'enum', 'default' => 'string'),
		array('name' => 'order', 'type' => 'int', 'default' => ':NULL'),
		array('name' => 'style', 'type' => 'varchar', 'default' => ':NULL'),
		array('name' => 'is_visible', 'type' => 'tinyint', 'default' => 1)
	);
/**
 * (non-PHPdoc)
 * @see core/framework/Model::get()
 * @param string $key
 * @access public
 * @return array
 */
	function get($key)
	{
		$arr = array();
		$r = mysql_query(sprintf("SELECT * FROM `%s` WHERE `key` = '%s' LIMIT 1",
			$this->getTable(), Object::escapeString($key)));
		if (@mysql_num_rows($r) == 1)
		{
			$row = mysql_fetch_assoc($r);
			$f = $this->showColumns($this->getTable());
			for($j = 0; $j < count($f); $j++)
			{
				$arr[$f[$j]['field']] = $row[$f[$j]['field']];
			}
		}
		return $arr;
	}
/**
 * Get array of key => values. Raw version
 *
 * @access public
 * @return array
 */
	function getAllPairs()
	{
		$arr = array();
		$r = mysql_query(sprintf("SELECT * FROM `%s` WHERE 1", $this->getTable()));
		if (@mysql_num_rows($r) > 0)
		{
			while ($row = mysql_fetch_assoc($r))
			{
				$arr[$row['key']] = $row['value'];
			}
		}
		return $arr;
	}
/**
 * Get array of key => values. Clear special values
 *
 * @access public
 * @return array
 */
	function getPairs()
	{
		$arr = array();
		$r = mysql_query(sprintf("SELECT * FROM `%s` WHERE 1", $this->getTable()));
		if (@mysql_num_rows($r) > 0)
		{
			while ($row = mysql_fetch_assoc($r))
			{
				switch ($row['type'])
				{
					case 'enum':
					case 'bool':
						list(, $arr[$row['key']]) = explode("::", $row['value']);
						break;
					default:
						$arr[$row['key']] = $row['value'];
						break;
				}
			}
		}
		return $arr;
	}

	function init()
	{
		$opts = array(
			array('key'=>'calendar_height','value'=>'240','description'=>'Height','label'=>NULL,'type'=>'int','order'=>'2'),
			array('key'=>'calendar_width','value'=>'320','description'=>'Width','label'=>NULL,'type'=>'int','order'=>'1'),
			array('key'=>'color_bg_empty_cells','value'=>'ffffff','description'=>'Empty cells','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_bg_legend','value'=>'FFFFFF','description'=>'Legend (Background color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_bg_month','value'=>'1c486a','description'=>'Month','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_bg_status_1','value'=>'67ae20','description'=>'Status 1 (Background color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_bg_status_2','value'=>'b50404','description'=>'Status 2 (Background color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_bg_status_3','value'=>'fa6c00','description'=>'Status 3 (Background color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_bg_weekday','value'=>'000000','description'=>'Weekdays','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_border_inner','value'=>'000000','description'=>'Inner border (Color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_border_legend','value'=>'FFFFFF','description'=>'Legend border (Color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_border_outer','value'=>'fa6c00','description'=>'Outer border (Color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_font_legend','value'=>'000000','description'=>'Legend (Font color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_font_month','value'=>'FFFFFF','description'=>'Month (Font color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_font_status_1','value'=>'FFFFFF','description'=>'Status 1 (Font color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_font_status_2','value'=>'FFFFFF','description'=>'Status 2 (Font color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_font_status_3','value'=>'000000','description'=>'Status 3 (Font color)','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'color_font_weekday','value'=>'FFFFFF','description'=>'Weekdays','label'=>NULL,'type'=>'color','order'=>NULL),
			array('key'=>'first_day','value'=>'1|2|3|4|5|6|0::1','description'=>'First day of week','label'=>'Monday|Tuesday|Wednesday|Thursday|Friday|Saturday|Sunday','type'=>'enum','order'=>NULL),
			array('key'=>'show_legend','value'=>'1|0::1','description'=>'Show legend','label'=>NULL,'type'=>'bool','order'=>NULL),
			array('key'=>'size_border_inner','value'=>'0|1|2|3|4|5|6|7|8|9::1','description'=>'Inner border (Size)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'size_border_legend','value'=>'0|1|2|3|4|5|6|7|8|9::1','description'=>'Legend border (Size)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'size_border_outer','value'=>'0|1|2|3|4|5|6|7|8|9::1','description'=>'Outer border (Size)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'size_font_days','value'=>'10|12|14|16|18|20|22|24|26|28|30::12','description'=>'Days (Font size)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'size_font_month','value'=>'10|12|14|16|18|20|22|24|26|28|30::20','description'=>'Month (Font size)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'size_font_weekday','value'=>'10|12|14|16|18|20|22|24|26|28|30::14','description'=>'Weekdays (Font size)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'status_1','value'=>'Available','description'=>'Status 1','label'=>NULL,'type'=>'string','order'=>NULL),
			array('key'=>'status_2','value'=>'Booked','description'=>'Status 2','label'=>NULL,'type'=>'string','order'=>NULL),
			array('key'=>'status_3','value'=>'Pending','description'=>'Status 3','label'=>NULL,'type'=>'string','order'=>NULL),
			array('key'=>'style_font_days','value'=>'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-weight: normal','description'=>' (Font style)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'style_font_family','value'=>'Arial|Arial Black|Book Antiqua|Century|Century Gothic|Comic Sans MS|Courier|Courier New|Impact|Lucida Console|Lucida Sans Unicode|Monotype Corsiva|Modern|Sans Serif|Serif|Small fonts|Symbol|Tahoma|Times New Roman|Verdana::Arial','description'=>'Font family (Font style)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'style_font_month','value'=>'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-weight: normal','description'=>' (Font style)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'style_font_weekday','value'=>'font-weight: normal|font-weight: bold|font-style: italic|text-decoration: underline|font-weight: bold; font-style: italic::font-weight: normal','description'=>' (Font style)','label'=>NULL,'type'=>'enum','order'=>NULL),
			array('key'=>'today_bold','value'=>'1|0::1','description'=>'Make today bold','label'=>NULL,'type'=>'bool','order'=>NULL)
		);
		$this->begin();
		foreach ($opts as $item)
		{
			$this->save($item);
		}
		$this->commit();
	}

	function copyOptions($dst_cid, $src_cid)
	{
		$sql = sprintf("INSERT INTO `%1\$s` (`calendar_id`, `key`, `value`, `description`, `label`, `type`, `order`)
			SELECT '%2\$u', `key`, `value`, `description`, `label`, `type`, `order`
			FROM `%1\$s`
			WHERE `calendar_id` = '%3\$u'",
			$this->getTable(), $dst_cid, $src_cid
		);
		$this->execute($sql);
	}
}
?>