<?php namespace App\Core\Models;

class BusinessCategory extends Base
{
    public $fillable = ['name'];

    public $rulesets = [
        'saving' => [
            'name' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function users()
    {
        return $this->belongsToMany('App\Core\Models\User');
    }
}
