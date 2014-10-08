<?php namespace App\Restaurant\Models;

class Table extends \App\Core\Models\Base
{
    protected $table = 'rb_tables';
    public $fillable = ['name', 'seats', 'minimum'];
    protected $rulesets = [
        'saving' => [
            'name'      => 'required',
            'seats'     => 'required|numeric',
            'minimum'   => 'required|numeric',
        ],
    ];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function groups()
    {
        return $this->belongsToMany('App\Restaurant\Models\Group', 'rb_group_table');
    }
}
