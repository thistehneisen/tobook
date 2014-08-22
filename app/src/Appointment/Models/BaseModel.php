<?php namespace App\Appointment\Models;

use Eloquent;
use Watson\Validating\ValidatingTrait;

class BaseModel extends Eloquent
{
    use ValidatingTrait;
}
