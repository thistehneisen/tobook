<?php namespace App\Core\Models;


class Review extends Base
{
    protected $table = 'reviews';

    const STATUS_INIT   = 'init';
    const STATUS_VALID   = 'valid';
    const STATUS_INVALID = 'invalid';

    public $fillable = [
        'name',
        'comment',
        'environment',
        'service',
        'price_ratio',
        'avg_rating',
        'status'
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
