<?php namespace App\Appointment\Models;

class ExtraService extends \App\Core\Models\Base
{
    protected $table = 'as_extra_services';

    public $fillable = ['name', 'description', 'price', 'length', 'is_hidden'];

    protected $rulesets = [
        'saving' => [
            'name'   => 'required',
            'price'  => 'required',
            'length' => 'required'
        ]
    ];

    //--------------------------------------------------------------------------
    // ATTRIBUTES
    //--------------------------------------------------------------------------
    public function getNameWithLengthAttribute()
    {
        return $this->attributes['name'] . sprintf(' (%s)', $this->attributes['length']);
    }

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }
}
