<?php namespace Test\Unit\Search;

use Test\Unit\Search\Stub\Model;
use UnitTester;
use Mockery as m;

/**
 * @group search
 */
class ElasticSearchTraitCest
{
    public function _after()
    {
        m::close();
    }

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

    public function testGetSearchDocumentId(UnitTester $i)
    {
        $model = new Model();
        $model->id = 999;

        $i->assertEquals($model->getSearchDocumentId(), 999);
    }

    public function testUpdateSearchIndex(UnitTester $i)
    {
        $mock = m::mock('\App\Search\ProviderInterface')
            ->shouldReceive('index')->once()
            ->getMock();

        $model = new Model();
        $model->updateSearchIndex($mock);
    }

    public function testNotUpdateSearchIndexIfNotSearchable(UnitTester $i)
    {
        $mock = m::mock('\App\Search\ProviderInterface')
            ->shouldReceive('index')->never()
            ->getMock();

        $model = new Model();
        $model->isSearchable = false;
        $model->updateSearchIndex($mock);
    }
}
