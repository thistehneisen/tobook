<?php namespace App\Appointment\Models;

use Confide, DB;
use App\Core\Models\User;

class Consumer extends \App\Consumers\Models\Consumer
{
    protected $rulesets = [
        'saving' => [
            'email'         => 'email',
            'first_name'    => 'required',
            'last_name'     => 'required',
            'phone'         => 'required|numeric'
        ]
    ];
}
