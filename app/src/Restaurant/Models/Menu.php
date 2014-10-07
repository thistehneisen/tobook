<?php namespace App\Restaurant\Models;

class Menu extends \App\Core\Models\Base
{
    protected $table = 'rb_menus';
    public $fillable = ['name', 'type'];
    protected $rulesets = [
        'saving'    => [
            'name'  => 'required',
            'type'  => 'required',
        ],
    ];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
