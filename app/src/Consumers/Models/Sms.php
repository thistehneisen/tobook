<?php
namespace App\Consumers\Models;

use Mail;

class Sms extends \App\Core\Models\Base
{
    public $fillable = [
        'title',
        'content',
    ];

    protected $table = 'mt_sms';

    protected $rulesets = ['saving' => [
        'title' => 'required',
        'content' => 'required',
    ]];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    public function histories()
    {
        return $this->hasMany('App\Consumers\Models\History');
    }

}
