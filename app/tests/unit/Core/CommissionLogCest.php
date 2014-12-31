<?php namespace Test\Unit\Core;

use App\Core\Models\User;
use App\Core\Models\CommissionLog;
use UnitTester;
use Mockery as m;

/**
 * @group core
 */
class CommissionLogCest
{
    protected $user;

    public function _before()
    {
        $data = [
            ['action' => 'add', 'amount' => 8.00],
            ['action' => 'subtract', 'amount' => 20.00],
            ['action' => 'add', 'amount' => 17.00],
        ];

        $this->user = User::find(70);
        foreach ($data as $input) {
            $item = new CommissionLog($input);
            $item->user()->associate($this->user);
            $item->save();
        }
    }

    public function _after()
    {
        m::close();
    }

    public function testCalculatePaidCommission(UnitTester $i)
    {
        $result = CommissionLog::calculatePaid($this->user);
        $i->assertEquals($result, 25.00);
    }

    public function testCalculateRefundedCommission(UnitTester $i)
    {
        $result = CommissionLog::calculateRefunded($this->user);
        $i->assertEquals($result, 20.00);
    }
}
