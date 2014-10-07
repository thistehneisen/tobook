<?php namespace App\Restaurant\Models;

class Service extends \App\Core\Models\Base
{
    protected $table = 'rb_services';
    public $fillable = ['name', 'start_at', 'end_at', 'length', 'price'];
    protected $rulesets = [
        'saving'    => [
            'name'      => 'required',
            'start_at'  => 'required',
            'end_at'    => 'required',
            'length'    => 'required|numeric',
            'price'     => 'required|numeric',
        ],
    ];

    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
