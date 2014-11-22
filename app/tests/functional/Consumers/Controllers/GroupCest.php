<?php namespace Test\Consumers\Controllers;

use Test\Traits\Mail;
use Test\Traits\Models;
use Test\Traits\Sms;
use FunctionalTester;

/**
 * @group co
 */
class GroupCest
{
    use Models;
    use Mail;
    use Sms;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();

        $I->amLoggedAs($this->user);
    }

    // TODO: crud tests

    public function testBulkSendCampaign(FunctionalTester $I)
    {
        $groups = [];
        $groups[] = $this->_createConsumerGroup($this->user, 2);
        $groups[] = $this->_createConsumerGroup($this->user, 3);

        $anotherConsumer = $this->_createConsumer($this->user);
        $groups[0]->consumers()->attach($anotherConsumer->id);
        $groups[1]->consumers()->attach($anotherConsumer->id);

        $campaign = $this->_createCampaign($this->user);

        $toAddresses = [];
        $consumerIds = [];
        foreach ($groups as $group) {
            foreach ($group->consumers as $consumer) {
                $toAddresses[$consumer->email] = false;
                $consumerIds[] = $consumer->id;
            }
        }
        $consumerIds = array_unique($consumerIds);
        $I->assertEquals(6, count($consumerIds), '2 + 3 + another in both');

        $this->_mockMailSend(function (array $message) use ($I, $campaign, &$toAddresses) {
            $I->assertEquals($campaign->subject, $message['subject'], '$message["subject"]');

            $I->assertEquals(1, count($message['to']), 'count($message["to"])');

            $firstAddress = $message['toFirstAddress'];
            $I->assertTrue(isset($toAddresses[$firstAddress]), 'first address');
            unset($toAddresses[$firstAddress]);
        });

        $I->amOnRoute('consumer-hub.groups.index');
        $I->checkOption('#bulk-item-' . $groups[0]->id);
        $I->checkOption('#bulk-item-' . $groups[1]->id);
        $I->selectOption('action', 'send_campaign');
        $I->click('#btn-bulk');

        $I->selectOption('campaign_id', $campaign->id);
        $I->click('#btn-submit');

        $historiesCount = $campaign->histories()->count();
        $I->assertEquals(count($consumerIds), $historiesCount, '$historiesCount');

        foreach ($consumerIds as $consumerId) {
            $historyConsumer = $campaign->histories()->where('consumer_id', $consumerId)->first();
            $I->assertNotEmpty($historyConsumer, sprintf('history for consumer #%d found', $consumerId));
        }

        $I->assertEquals(0, count($toAddresses), 'count($toAddresses)');
    }

    public function testBulkSendSms(FunctionalTester $I)
    {
        $groups = [];
        $groups[] = $this->_createConsumerGroup($this->user, 2);
        $groups[] = $this->_createConsumerGroup($this->user, 3);

        $anotherConsumer = $this->_createConsumer($this->user);
        $groups[0]->consumers()->attach($anotherConsumer->id);
        $groups[1]->consumers()->attach($anotherConsumer->id);

        $sms = $this->_createSms($this->user);

        $phones = [];
        $consumerIds = [];
        foreach ($groups as $group) {
            foreach ($group->consumers as $consumer) {
                $phones[$consumer->phone] = false;
                $consumerIds[] = $consumer->id;
            }
        }
        $consumerIds = array_unique($consumerIds);
        $I->assertEquals(6, count($consumerIds), '2 + 3 + another in both');

        $this->_mockSmsSend(function (array $message) use ($I, $sms, &$phones) {
            $I->assertEquals($sms->content, $message['content'], '$message["content"]');

            $phoneNumber = $message['to'];
            $I->assertTrue(isset($phones[$phoneNumber]), 'phone number');
            unset($phones[$phoneNumber]);
        });

        $I->amOnRoute('consumer-hub.groups.index');
        $I->checkOption('#bulk-item-' . $groups[0]->id);
        $I->checkOption('#bulk-item-' . $groups[1]->id);
        $I->selectOption('action', 'send_sms');
        $I->click('#btn-bulk');

        $I->selectOption('sms_id', $sms->id);
        $I->click('#btn-submit');

        $historiesCount = $sms->histories()->count();
        $I->assertEquals(count($consumerIds), $historiesCount, '$historiesCount');

        foreach ($consumerIds as $consumerId) {
            $historyConsumer = $sms->histories()->where('consumer_id', $consumerId)->first();
            $I->assertNotEmpty($historyConsumer, sprintf('history for consumer #%d found', $consumerId));
        }

        $I->assertEquals(0, count($phones), 'count($phones)');
    }
}
