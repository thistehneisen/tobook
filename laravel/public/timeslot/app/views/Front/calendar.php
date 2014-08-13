<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.Front
 */
echo $tpl['calendar']->getMonthView($_GET['month'], $_GET['year']);
if ($controller->option_arr['color_legend'] == 'Yes')
{
	echo $tpl['calendar']->getLegend($TS_LANG);
}
?>