<?php namespace App\Core\Models;

class DisabledModule extends \Eloquent
{
    public $fillable = ['module'];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
