<?php namespace Test\Unit\Consumers\Models;

use App\Consumers\Models\Sms;
use UnitTester;

/**
 * @group co
 */
class SmsCest
{
    use \Test\Traits\Models;

    protected $groups = [];

    public function _before(UnitTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        $this->groups[0] = $this->_createConsumerGroup($this->user, 2);
        $this->groups[1] = $this->_createConsumerGroup($this->user, 3);

        $consumer = $this->_createConsumer($this->user);
        $this->groups[0]->consumers()->attach($consumer->id);
        $this->groups[1]->consumers()->attach($consumer->id);
    }

    public function testSendConsumers(UnitTester $I)
    {
        $sms = $this->_createSms($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        $sent = Sms::sendConsumers($sms, [$consumer->id]);
        $I->assertEquals(1, $sent, '$sent');

        // send to the same consumer again
        $sent = Sms::sendConsumers($sms, [$consumer->id]);
        $I->assertEquals(0, $sent, '$sent again');
    }

    public function testSendGroups(UnitTester $I)
    {
        $sms = $this->_createSms($this->user);
        $group = $this->groups[0];
        $consumersCount = $group->consumers()->count();
        $I->assertEquals(3, $consumersCount);

        list($sent, $total) = Sms::sendGroups($sms, [$group->id]);
        $I->assertEquals($consumersCount, $sent, '$sent');
        $I->assertEquals($consumersCount, $total, '$total');

        $group = $this->groups[1];
        $consumersCount = $group->consumers()->count();
        $I->assertEquals(4, $consumersCount);

        list($sent, $total) = Sms::sendGroups($sms, [$group->id]);
        $I->assertEquals($consumersCount - 1, $sent, '$sent again');
        $I->assertEquals($consumersCount, $total, '$total again');
    }
}
