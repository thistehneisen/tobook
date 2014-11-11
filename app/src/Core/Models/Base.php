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
        $userId = 0;
        $user = Confide::user();
        if (!empty($user)) {
            $userId = $user->id;
        }

        return $this->scopeOfUser($query, $userId);
    }

    public function scopeOfUser($query, $userId)
    {
        $table = $this->getTable();
        if ($userId instanceof User) {
            $userId = $userId->id;
        }

        if (method_exists($this, 'user')) {
            return $query->where($table.'.user_id', $userId);
        } elseif (method_exists($this, 'users')) {
            if ($this->users() instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany) {
                $table = $this->users()->getTable();
            }

            return $query->whereHas('users', function($query) use ($userId, $table) {
                return $query->where($table.'.user_id', $userId);
            });
        }

        return $query;
    }
}
