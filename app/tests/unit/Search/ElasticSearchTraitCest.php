<?php namespace Test\Unit\Search;

use Test\Unit\Search\Stub\Model;
use UnitTester;
use Mockery as m;
use App;

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
        $i->assertEquals(Model::getSearchIndexName(), 'models');
    }

    public function testGetSearchIndexType(UnitTester $i)
    {
        $model = new Model();
        $i->assertEquals(Model::getSearchIndexType(), 'model');
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

        // I don't want to actually hit ES, so I provide it a mock
        App::instance('App\Search\ProviderInterface', $mock);

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

    public function testDefaultTransformSearchResult(UnitTester $i)
    {
        $result = Model::transformSearchResult(true);
        $i->assertEquals($result, true);
    }

    public function testSearch(UnitTester $i)
    {
        $mock = m::mock('\App\Search\ProviderInterface')
            ->shouldReceive('search')
            ->once()
            ->andReturn([
                'hits' => [
                    'hits' => [],
                    'total' => 0
                ]
            ])
            ->getMock();

        App::instance('App\Search\ProviderInterface', $mock);
        $result = Model::search('foo');
        $i->assertTrue($result instanceof \Illuminate\Pagination\Paginator);
        $i->assertEquals($result->getTotal(), 0);
        $i->assertEquals($result->getItems(), []);
    }
}
