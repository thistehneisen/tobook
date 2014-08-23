<?php namespace App\Core\Models;

use Confide;
use Watson\Validating\ValidatingTrait;

class Base extends \Eloquent
{
    use ValidatingTrait;

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    public function scopeOfCurrentUser($query)
    {
        return $this->scopeOfUser($query, Confide::user()->id);
    }

    public function scopeOfUser($query, $userId)
    {
        if (method_exists($this, 'user')) {
            return $query->where('user_id', $userId);
        } elseif (method_exists($this, 'users')) {
            return $query->whereHas('users', function($q) use ($userId) {
                return $q->where('user_id', $userId);
            });
        }


        return $query;
    }
}
