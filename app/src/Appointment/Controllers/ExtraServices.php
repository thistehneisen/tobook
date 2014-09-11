<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;

class ExtraServices extends AsBase
{
    use App\Appointment\Traits\Crud;

    protected $langPrefix = 'as.services.extras';
}
