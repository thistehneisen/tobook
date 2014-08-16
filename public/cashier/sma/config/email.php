<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once realpath(BASEPATH.'../../../includes/configsettings.php');

$config['protocol'] = 'smtp';
$config['smtp_host'] = SMTP_HOST;
$config['smtp_user'] = SMTP_USERNAME;
$config['smtp_pass'] = SMTP_PASSWORD;
$config['smtp_port'] = SMTP_PORT;
$config['smtp_timeout'] = 5;