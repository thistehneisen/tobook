<?php namespace Test\Unit\FlashDeal;

use UnitTester, Carbon\Carbon, DB;
use Test\Helpers\Fixture;
use App\Cart\Cart;
use App\Cart\CartDetail;
use App\FlashDeal\Stats\FlashDeal;

/**
 * @group fd
 */
class FlashDealStatsCest
{
    protected $user;

    public function _before()
    {
        $this->user = Fixture::user();
        $this->seed();
    }

    public function testGetTotalSoldByDays(UnitTester $i)
    {
        $from = new Carbon('2014-12-05');
        $to = $from->copy()->addDays(20);

        $result = with(new FlashDeal())->getTotalSoldByDays($from, $to);
        $i->assertTrue($result->isEmpty() === false);

        $first = $result[0];
        $i->assertEquals($first->date, '2014-12-05');
        $i->assertEquals($first->total, '5');
        $i->assertEquals($first->revenue, '85');

        $second = $result[1];
        $i->assertEquals($second->date, '2014-12-15');
        $i->assertEquals($second->total, '4');
        $i->assertEquals($second->revenue, '40');
    }

    public function testGetTotalSoldByBusinesses(UnitTester $i)
    {
        $from = new Carbon('2014-12-05');
        $to = $from->copy()->addDays(20);

        $result = with(new FlashDeal())->getTotalSoldByBusinesses($from, $to);
        $i->assertTrue($result->isEmpty() === false);

        $first = $result[0];
        $i->assertEquals($first->revenue, '125');
        $i->assertEquals($first->total, '9');
        $i->assertEquals($first->user_id, $this->user->id);
        $i->assertTrue($first->business instanceof \App\Core\Models\Business, 'instanceof Business');
    }

    protected function seed()
    {
        $cart1 = Cart::make(
            ['status' => Cart::STATUS_COMPLETED],
            $this->user
        );
        $cart2 = Cart::make(
            ['status' => Cart::STATUS_COMPLETED],
            $this->user
        );

        $table = with(new CartDetail())->getTable();
        DB::table($table)->insert([
            [
                'model_type' => 'App\FlashDeal\Models\FlashDealDate',
                'model_id'   => 999,
                'quantity'   => 2,
                'price'      => 20,
                'cart_id'    => $cart1->id,
                'created_at' => '2014-12-05'
            ],
            [
                'model_type' => 'App\FlashDeal\Models\FlashDealDate',
                'model_id'   => 998,
                'quantity'   => 3,
                'price'      => 15,
                'cart_id'    => $cart1->id,
                'created_at' => '2014-12-05'
            ],
            [
                'model_type' => 'App\FlashDeal\Models\FlashDealDate',
                'model_id'   => 997,
                'quantity'   => 4,
                'price'      => 10,
                'cart_id'    => $cart2->id,
                'created_at' => '2014-12-15'
            ],
            [
                'model_type' => 'App\FlashDeal\Models\FlashDealDate',
                'model_id'   => 996,
                'quantity'   => 5,
                'price'      => 5,
                'cart_id'    => $cart1->id,
                'created_at' => '2014-12-30'
            ],
        ]);
    }
}
