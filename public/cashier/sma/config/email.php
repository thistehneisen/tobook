<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
require_once realpath(BASEPATH.'../../../Bridge.php');
$varaaEmail = Bridge::emailConfig();

$config['protocol'] = 'smtp';
$config['smtp_host'] = $varaaEmail['host'];
$config['smtp_port'] = $varaaEmail['port'];
$config['smtp_user'] = $varaaEmail['username'];
$config['smtp_pass'] = $varaaEmail['password'];
$config['smtp_timeout'] = 5;
