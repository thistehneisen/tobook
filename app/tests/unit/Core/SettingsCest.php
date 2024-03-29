<?php namespace Core;

use App\Core\Models\Setting;
use Illuminate\Support\Collection;
use Mockery as m;
use Settings;
use UnitTester;

/**
 * @group core
 */
class SettingsCest
{
    public function _before()
    {
        $m = m::mock('App\Core\Models\Setting[all]');
        $m->shouldReceive('all')->andReturn(new Collection([
            new Setting(['key' => 'foo', 'value' => true]),
            new Setting(['key' => 'bar', 'value' => 1]),
            new Setting(['key' => 'social_foo', 'value' => 99]),
            new Setting(['key' => 'social_bar', 'value' => 88]),
        ]));

        \App::instance('App\Core\Models\Setting', $m);
    }

    public function testGetExisting(UnitTester $i)
    {
        $i->assertEquals(Settings::get('foo'), true);
        $i->assertEquals(Settings::get('bar'), 1);
    }

    public function testGetNotExisting(UnitTester $i)
    {
        $i->assertEquals(Settings::get('nonexisting'), null);
        $i->assertEquals(Settings::get('non', 'existing'), 'existing');
    }

    public function testGetGroup(UnitTester $i)
    {
        $i->assertEquals(Settings::group('social'), [
            'foo' => 99,
            'bar' => 88,
        ]);

        $i->assertEquals(
            Settings::group('nonexisting', ['love' => 'is all around']),
            ['love' => 'is all around']
        );
    }
}
