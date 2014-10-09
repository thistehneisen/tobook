<?php namespace App\Payment\Models;

class Transaction extends \AppModel
{
    public $fillable = [
        'amount',
        'currency',
        'paygate',
        'status',
        'message',
        'code',
        'reference',
    ];

    public $rulesets = [
        'saving' => [
            'amount' => 'required|numeric'
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function cart()
    {
        return $this->belongsTo('App\Core\Models\Cart');
    }
}
