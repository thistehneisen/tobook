<?php namespace App\Consumers\Controllers;

use App\Core\Controllers\Base;
use App\Appointment\Traits\Crud;

class Consumer extends Base
{
    use Crud;
    protected $modelClass = 'App\Consumers\Models\Consumer';
    protected $langPrefix = 'co';
}
