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

    public function testGetSearchIndexType(UnitTester $i)
    {
        $model = new Model();
        $i->assertEquals($model->getSearchIndexType(), 'model');
    }

    public function testGetSearchDocument(UnitTester $i)
    {
        $attr = [
            'foo' => 'Hello World',
            'bar' => true
        ];
        $model = new Model($attr);
        $doc = $model->getSearchDocument();

        $i->assertEquals($attr['foo'], $doc['foo']);
        $i->assertEquals($attr['bar'], $doc['bar']);
    }
}
