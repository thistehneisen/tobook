<?php
// This is global bootstrap for autoloading
require_once realpath(__DIR__.'/../../vendor/autoload.php');

\Codeception\Util\Autoload::registerSuffix('Group', __DIR__.DIRECTORY_SEPARATOR.'_groups');