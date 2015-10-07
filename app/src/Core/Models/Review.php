<?php namespace App\Core\Models;


class Review extends Base
{
    protected $table = 'reviews';

    public $fillable = [
        'name',
        'comment',
        'environment',
        'service',
        'price_ratio'
    ];

    public $rulesets = [
        'saving' => [
            'environment' => 'required',
            'service'     => 'required',
            'price_ratio' => 'required',
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
