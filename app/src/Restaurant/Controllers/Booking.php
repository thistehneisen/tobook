<?php namespace App\Restaurant\Controllers;

use Input;
use App\Core\Controllers\Base;

class Booking extends Base
{
    use \CRUD;

    protected $crudOptions = [
        'langPrefix'    => 'rb.bookings',
        'modelClass'    => 'App\Restaurant\Models\Booking',
        'layout'        => 'modules.rb.layout',
        'indexFields'   => ['uuid', 'date', 'start_at', 'end_at', 'status', 'note'],
    ];
}
