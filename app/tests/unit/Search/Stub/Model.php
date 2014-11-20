<?php namespace Test\Unit\Search\Stub;

use Eloquent;
use App\Search\ElasticSearchTrait;

class Model extends Eloquent
{
    use ElasticSearchTrait;
}
