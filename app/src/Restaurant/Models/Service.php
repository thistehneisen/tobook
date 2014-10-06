<?php namespace App\Restaurant\Models;

class Service extends \App\Core\Models\Base
{
    protected $table = 'rb_services';
    public $fillable = ['name', 'start_at', 'end_at', 'length', 'price'];

    public function user()
    {
        return $this->belongsTo('App\Core\Model\User');
    }
}
