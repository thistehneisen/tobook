<?php namespace App\Restaurant\Models;

class Menu extends \App\Core\Models\Base
{
    protected $table = 'rb_menus';
    public $fillable = ['name', 'type'];

    public function user()
    {
        return $this->belongsTo('App\Core\Model\User');
    }
}
