<?php namespace App\Controllers\Admin;

class Base extends \App\Controllers\Base
{
    protected function render($path, $data = [])
    {
        return \View::make('admin.'.$path, $data);
    }
}
