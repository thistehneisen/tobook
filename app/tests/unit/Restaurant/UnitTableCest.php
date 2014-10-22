<?php namespace Restaurant\Models;

use \UnitTester;
use App\Restaurant\Models\Table;
use App\Core\Models\User;

class UnitTableCest
{
    public function testAddTable(UnitTester $I)
    {
        $table = new Table;
        $table->name = 'Table 1';
        $table->seats = 5;
        $table->minimum = 2;
        $table->user()->associate(User::find(70));
        $table->save();

        $I->assertEquals($table->name, 'Table 1');

        $I->seeRecord('rb_tables', [
            'name'      => 'Table 1',
            'seats'     => 5,
            'minimum'   => 2,
        ]);
    }

    public function testEditTable(UnitTester $I)
    {
        $table = Table::find(1);
        $table->name = 'Table Edit';
        $table->save();

        $I->assertEquals($table->name, 'Table Edit');

        $I->seeRecord('rb_tables', [
            'name'      => 'Table Edit',
        ]);
    }
}
