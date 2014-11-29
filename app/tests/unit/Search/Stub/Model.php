<?php namespace Test\Unit\Search\Stub;

class Model extends \Eloquent implements \App\Search\SearchableInterface
{
    use \App\Search\ElasticSearchTrait;
    use \Illuminate\Database\Eloquent\SoftDeletingTrait;

    public $table = 'prefix_models';

    protected $fillable = ['foo', 'bar'];

    public static function search($keyword, array $options = [])
    {
        return static::serviceSearch($keyword, $options);
    }
}
