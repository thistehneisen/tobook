<?php namespace App\Core\Controllers\Admin;

class Base extends \App\Core\Controllers\Base
{
    protected function render($path, $data = [])
    {
        return \View::make('admin.'.$path, $data);
    }
}
