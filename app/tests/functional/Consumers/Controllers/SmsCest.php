<?php namespace Test\Functional\Consumers\Controllers;

use App\Consumers\Models\Sms;
use FunctionalTester;
use Test\Traits\Models;

/**
 * @group co
 */
class SmsCest
{
    use Models;

    protected $groups = [];

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        $this->groups[0] = $this->_createConsumerGroup($this->user, 2);
        $this->groups[1] = $this->_createConsumerGroup($this->user, 3);

        $consumer = $this->_createConsumer($this->user);
        $this->groups[0]->consumers()->attach($consumer->id);
        $this->groups[1]->consumers()->attach($consumer->id);

        $I->amLoggedAs($this->user);
    }

    // TODO: crud tests

    public function testHistorySentToConsumer(FunctionalTester $I)
    {
        $sms = $this->_createSms($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        Sms::sendConsumers($sms, [$consumer->id]);

        $I->amOnRoute('consumer-hub.sms.history');
        $I->seeNumberOfElements('.item-row', 1);
        $I->see($sms->title);
        $I->see($consumer->phone);
    }

    public function testHistorySentToGroup(FunctionalTester $I)
    {
        $sms = $this->_createSms($this->user);
        $group = $this->groups[1];
        $consumersCount = $group->consumers()->count();
        $I->assertEquals(4, $consumersCount);

        Sms::sendConsumers($sms, [$group->id]);

        $I->amOnRoute('consumer-hub.sms.history');
        $I->seeNumberOfElements('.item-row', $consumersCount);
        $I->see($sms->title);
        $I->see($group->name);

        foreach ($group->consumers as $consumer) {
            $I->see($consumer->phone);
        }
    }

    public function testHistoryByCampaign(FunctionalTester $I)
    {
        $smsAll = [];
        $smsAll[] = $this->_createSms($this->user);
        $smsAll[] = $this->_createSms($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        foreach ($smsAll as $sms) {
            Sms::sendConsumers($sms, [$consumer->id]);
        }

        $I->amOnRoute('consumer-hub.sms.history');
        $I->seeNumberOfElements('.item-row', count($smsAll));
        foreach ($smsAll as $sms) {
            $I->see($sms->title);
        }
        $I->see($consumer->phone);

        foreach ($smsAll as $sms) {
            $I->amOnRoute('consumer-hub.sms.history', ['sms_id' => $sms->id]);
            $I->seeNumberOfElements('.item-row', 1);
            $I->see($sms->title);
            $I->see($consumer->phone);
        }
    }
}
