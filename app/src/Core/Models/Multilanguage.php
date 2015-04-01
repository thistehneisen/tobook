<?php namespace App\Core\Models;

use App\Core\Models\User;

class Multilanguage extends Base
{
    public $fillable = [
        'context',
        'lang',
        'key',
        'value'
    ];

    public $rulesets = [
        'saving' => [
            'context' => 'required',
            'lang' => 'required',
            'key' => 'required',
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

}
