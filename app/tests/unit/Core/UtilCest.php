<?php namespace Core;

use UnitTester;
use Util, Cache, Geocoder, Log;
use Mockery as m;

/**
 * @group core
 */
class UtilCest
{
    public function _after()
    {
        m::close();
    }

    public function testGeocoderWithEmptyLocation(UnitTester $i)
    {
        try {
            $pair = Util::geocoder('');
        } catch (\Exception $ex) {
            $i->assertTrue($ex instanceof \InvalidArgumentException, 'instanceof InvalidArgumentException');
        }
    }

    public function testGeocoderWithCachedResult(UnitTester $i)
    {
        $location =  'Middle-earth';
        $key = 'geocoder.'.md5($location);
        Cache::shouldReceive('get')
            ->with($key)
            ->once()
            ->andReturn([0, 1])
            ->shouldReceive('flush')
            ->andReturn(true);

        $pair = Util::geocoder($location);
        $i->assertEquals($pair, [0, 1]);
    }

    public function testGeocoderShouldCallGeocoder(UnitTester $i)
    {
        $location =  'Middle-earth';
        $key = 'geocoder.'.md5($location);

        Cache::shouldReceive('get')
            ->with($key)
            ->once()
            ->andReturn(false)
            ->shouldReceive('flush')
            ->andReturn(true)
            ->shouldReceive('forever')
            ->andReturn(true);

        $result = m::mock('Geocoder\Result\ResultInterface');
        $result->shouldReceive('getLatitude')->andReturn(0);
        $result->shouldReceive('getLongitude')->andReturn(1);

        Geocoder::shouldReceive('geocode')
            ->once()
            ->andReturn($result);

        $pair = Util::geocoder($location);
        $i->assertEquals($pair, [0, 1]);
    }

    public function testGeocoderShouldLogException(UnitTester $i)
    {
        $location =  'Middle-earth';
        $key = 'geocoder.'.md5($location);

        Cache::shouldReceive('get')
            ->with($key)
            ->once()
            ->andReturn(false)
            ->shouldReceive('flush')
            ->andReturn(true)
            ->shouldReceive('forever')
            ->andReturn(true);

        Log::shouldReceive('warning')
            ->once();

        Geocoder::shouldReceive('geocode')
            ->once()
            ->andThrow(new \Exception('Foo message'));

        try {
            $pair = Util::geocoder($location);
        } catch (\Exception $ex) {
            $i->assertEquals('Foo message', $ex->getMessage());
        }
    }
}
