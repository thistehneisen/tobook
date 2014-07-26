<?php
/**
 * @package tsbc
 * @subpackage tsbc.app.views.AdminCalendars
 */
echo $tpl['calendar']->getMonthView($_GET['month'], $_GET['year']);
?>