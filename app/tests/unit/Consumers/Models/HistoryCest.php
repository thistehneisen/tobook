<?php namespace Test\Unit\Consumers\Models;

use App\Consumers\Models\History;
use UnitTester;

/**
 * @group co
 */
class HistoryCest
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

    public function testQuickSaveEmailTemplate(UnitTester $I)
    {
        $emailTemplate = $this->_createEmailTemplate($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        $history = History::quickSave($this->user, $emailTemplate, $consumer);
        $I->assertEquals($emailTemplate->id, $history->email->id, 'Email template');
    }

    public function testQuickSaveSmsTemplate(UnitTester $I)
    {
        $smsTemplate = $this->_createSms($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        $history = History::quickSave($this->user, $smsTemplate, $consumer);
        $I->assertEquals($smsTemplate->id, $history->sms->id, 'Sms template');
    }
}
