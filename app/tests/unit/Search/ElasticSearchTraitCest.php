<?php namespace Test\Unit\Search;

use Test\Unit\Search\Stub\Model;
use UnitTester;

/**
 * @group search
 */
class ElasticSearchTraitCest
{
    public function testGetSearchIndexName(UnitTester $i)
    {
        $model = new Model();
        $i->assertEquals($model->getSearchIndexName(), 'models');
    }
}
