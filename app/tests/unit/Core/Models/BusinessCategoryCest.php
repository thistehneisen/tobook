<?php namespace Test\Unit\Core\Models;

use App\Core\Models\BusinessCategory;
use UnitTester;

/**
 * @group core
 */
class BusinessCategoryCest
{
    protected $item;

    public function _before()
    {
        $this->item = new BusinessCategory([
            'name' => 'this_is_the_name',
            'keywords' => 'key, words, word as a key'
        ]);
    }

    public function testGetNiceOriginalName(UnitTester $i)
    {
        $i->assertEquals($this->item->nice_original_name, 'this is the name');
    }

    public function testGetIcon(UnitTester $i)
    {
        $i->assertEquals($this->item->icon, '');

        $this->item->name = 'home';
        $i->assertEquals($this->item->icon, 'fa-home');
    }
}
