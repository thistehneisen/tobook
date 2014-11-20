<?php namespace Test\Unit\Search\Stub;

use Eloquent;
use App\Search\ElasticSearchTrait;
use App\Search\SearchableInterface;

class Model extends Eloquent implements SearchableInterface
{
    use ElasticSearchTrait;

    protected $fillable = ['foo', 'bar'];
}
