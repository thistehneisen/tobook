<?php namespace App\Restaurant\Models;

class Group extends \App\Core\Models\Base
{
    protected $table = 'rb_groups';
    public $fillable = ['name', 'description'];
    protected $rulesets = [
        'saving'    => [
            'name'  => 'required',
        ],
    ];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
