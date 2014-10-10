<?php namespace Test\Unit\Payment;

use App\Payment\Models\Transaction;
use UnitTester;

class TransactionCest
{
    protected $item;

    public function _before(UnitTester $t)
    {
        $this->item = new Transaction(['amount' => 999]);
    }

    public function getCurrencySymbol(UnitTester $t)
    {
        $t->assertEquals($this->item->currency_symbol, '&euro;');

        $this->item->currency = 'USD';
        $t->assertEquals($this->item->currency_symbol, '$');

        $this->item->currency = 'VND';
        $t->assertEquals($this->item->currency_symbol, 'VND');
    }

    public function getAmount(UnitTester $t)
    {
        $t->assertEquals($this->item->amount, 999.00);
        $this->item->amount = '99.95555555';
        $t->assertEquals($this->item->amount, 99.96);
    }

    public function getFormattedAmount(UnitTester $t)
    {
        $t->assertEquals($this->item->formatted_amount, '999.00&euro;');

        $this->item->currency = 'VND';
        $t->assertEquals($this->item->formatted_amount, '999.00VND');
    }

    public function getCurrency(UnitTester $t)
    {
        $t->assertEquals($this->item->currency, 'EUR');
    }
}
