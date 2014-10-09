<?php namespace Test\Unit\Payment;

use App\Payment\Models\Transaction;
use UnitTester;

class TransactionCest
{
    public function testGetCurrencySymbol(UnitTester $t)
    {
        $item = new Transaction([
            'amount' => 999
        ]);
        $t->assertEquals($item->currency_symbol, '&euro;');

        $item = new Transaction([
            'amount' => 999,
            'currency' => 'USD'
        ]);
        $t->assertEquals($item->currency_symbol, '$');

        $item = new Transaction([
            'amount' => 999,
            'currency' => 'VND'
        ]);
        $t->assertEquals($item->currency_symbol, 'VND');
    }

    public function testGetFormattedAmount(UnitTester $t)
    {
        $item = new Transaction([
            'amount' => 999
        ]);
        $t->assertEquals($item->formatted_amount, '999.00&euro;');

        $item = new Transaction([
            'amount' => 999,
            'currency' => 'VND'
        ]);
        $t->assertEquals($item->formatted_amount, '999.00VND');
    }
}
