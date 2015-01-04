<?php namespace App\Core\Models;

class CommissionLog extends \AppModel
{
    const ACTION_ADD = 'add';
    const ACTION_SUBTRACT = 'subtract';

    public $fillable = [
        'action',
        'amount',
        'note',
    ];

    public $rulesets = [
        'saving' => [
            'action' => 'required',
            'amount' => 'required|numeric',
        ]
    ];

    //--------------------------------------------------------------------------
    // RELATIONSHIPS
    //--------------------------------------------------------------------------
    public function user()
    {
        return $this->belongsTo('App\Core\Models\User');
    }

    //--------------------------------------------------------------------------
    // CUSTOM METHODS
    //--------------------------------------------------------------------------
    public static function calculatePaid(User $user)
    {
        return static::where('user_id', $user->id)
            ->where('action', static::ACTION_ADD)
            ->sum('amount');
    }

    public static function calculateRefunded(User $user)
    {
        return static::where('user_id', $user->id)
            ->where('action', static::ACTION_SUBTRACT)
            ->sum('amount');
    }
}
