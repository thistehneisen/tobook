<?php namespace App\Controllers\Admin;

use Settings;

class Base extends \App\Controllers\Base
{
    protected function render($path, $data = [])
    {
        return \View::make('admin.'.$path, $data);
    }
}
