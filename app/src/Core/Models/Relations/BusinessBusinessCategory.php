<?php namespace App\Core\Models\Relations;

use App\Core\Models\Business;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class BusinessBusinessCategory extends BelongsToMany
{
    public function __construct(Builder $query, Model $parent, $table, $foreignKey, $otherKey, $relationName = null)
    {
        parent::__construct($query, new ParentProxy($parent), $table, $foreignKey, $otherKey, $relationName);
    }
}

class ParentProxy extends Model
{
    protected $delegate;

    public function getKey() {
        return $this->delegate->user_id;
    }

    function __construct(Business $delegate) {
        $this->delegate = $delegate;
    }

    function __get($name) {
        return $this->delegate->$name;
    }

    function __call($name, $args) {
        return call_user_func_array(array($this->delegate, $name), $args);
    }
}
