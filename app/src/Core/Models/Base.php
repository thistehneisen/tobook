<?php namespace App\Core\Models;

use Confide;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

class Base extends \Eloquent
{
    use ValidatingTrait;
    use SoftDeletingTrait;

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    public function scopeOfCurrentUser($query)
    {
        return $this->scopeOfUser($query, Confide::user()->id);
    }

    public function scopeOfUser($query, $userId)
    {
        $table = $this->getTable();
        if (method_exists($this, 'user')) {
            return $query->where($table . '.' . 'user_id', $userId);
        } elseif (method_exists($this, 'users')) {
            return $query->whereHas('users', function($q) use ($userId) {
                return $q->where($table . '.' . 'user_id', $userId);
            });
        }


        return $query;
    }
}
