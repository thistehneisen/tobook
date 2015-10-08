<?php namespace App\Core\Models;


class Review extends Base
{
    protected $table = 'reviews';

    const STATUS_INIT   = 'init';
    const STATUS_APPROVED   = 'approved';
    const STATUS_REJECTED = 'rejected';

    public $fillable = [
        'name',
        'comment',
        'environment',
        'service',
        'price_ratio',
        'avg_rating',
        'status',
    ];

    public $rulesets = [
        'saving' => [
            'environment' => 'required',
            'service'     => 'required',
            'price_ratio' => 'required',
        ]
    ];

    public function setAvgRating()
    {
        $this->avg_rating = ($this->environment + $this->price_ratio + $this->service) / 3;
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
