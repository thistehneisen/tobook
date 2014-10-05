<?php namespace App\Restaurant\Models;

class Group extends \App\Core\Models\Base
{
    protected $table = 'rb_group';
    public $fillable = ['name', 'description'];

    public function user()
    {
        return $this->belongsTo('App\Core\Model\User');
    }
}
