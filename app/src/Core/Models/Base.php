<?php namespace App\Core\Models;

use Confide, App, Log, Input, Config;
use Watson\Validating\ValidatingTrait;
use Illuminate\Database\Eloquent\SoftDeletingTrait;
use App\Search\SearchableInterface;
use App\Search\ElasticSearchTrait;

class Base extends \Eloquent implements SearchableInterface
{
    use ValidatingTrait;
    use SoftDeletingTrait;
    use ElasticSearchTrait;

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

    //--------------------------------------------------------------------------
    // SearchableInterface
    //--------------------------------------------------------------------------

    /**
     * Search data with provided keyword
     *
     * @param string $keyword
     *
     * @return Illuminate\Pagination\Paginator
     */
    public static function search($keyword, array $options = [])
    {
        // First, try to search with search service
        try {
            return static::serviceSearch($keyword, $options);
        } catch (\Exception $ex) {
            // Silently failed baby
            Log::error('Failed to search using service: '.$ex->getMessage());
        }

        //----------------------------------------------------------------------
        // Fallback to traditional search ._.
        //----------------------------------------------------------------------

        // Get fillable fields of this model
        $model = new static();
        $fillable = $model->getFillable();

        // Add ID to be candicate for searching
        $fillable[] = 'id';
        $query = static::where(function ($q) use ($fillable, $keyword) {
            foreach ($fillable as $field) {
                $q = $q->orWhere($field, 'LIKE', '%'.$keyword.'%');
            }

            return $q;
        });

        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));

        return $query->paginate($perPage);
    }

    /**
     * @{@inheritdoc}
     */
    public static function transformSearchResult($results)
    {
        $data = [];
        foreach ($results as $result) {
            $item = static::find($result['_id']);
            if ($item !== null) {
                $data[] = $item;
            }
        }

        return $data;
    }
}
