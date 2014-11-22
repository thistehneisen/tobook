<?php namespace App\Core\Models;

use Confide, App;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use App\Search\SearchableInterface;
use App\Search\ElasticSearchTrait;

class Base extends \Eloquent implements SearchableInterface
{
    use ValidatingTrait;
    use SoftDeletingTrait;
    use ElasticSearchTrait;

    /**
     * @{@inheritdoc}
     */
    public static function boot()
    {
        parent::boot();

        static::saved(function ($model) {
            // Send data of this model to ES for indexing
            $model->updateSearchIndex();
        });
    }

    //--------------------------------------------------------------------------
    // SCOPES
    //--------------------------------------------------------------------------
    /**
     * Return records that belong to the current logged-in user
     *
     * @param Illuminate\Database\Query\Builder $query
     *
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfCurrentUser($query)
    {
        $userId = 0;
        $user = Confide::user();
        if (!empty($user)) {
            $userId = $user->id;
        }

        return $this->scopeOfUser($query, $userId);
    }

    /**
     * Return records that belong to the provided user
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param App\Core\Models\User|int          $userId
     *
     * @return Illuminate\Database\Query\Builder
     */
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

            return $query->whereHas('users', function ($query) use ($userId, $table) {
                return $query->where($table.'.user_id', $userId);
            });
        }

        return $query;
    }
}
