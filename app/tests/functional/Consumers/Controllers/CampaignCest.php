<?php namespace Test\Functional\Consumers\Controllers;

use App\Consumers\Models\Campaign;
use FunctionalTester;
use Test\Traits\Models;

/**
 * @group co
 */
class CampaignCest
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
        $campaign = $this->_createCampaign($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        Campaign::sendConsumers($campaign, [$consumer->id]);

        $I->amOnRoute('consumer-hub.campaigns.history');
        $I->seeNumberOfElements('.item-row', 1);
        $I->see($campaign->subject);
        $I->see($consumer->email);
    }

    public function testHistorySentToGroup(FunctionalTester $I)
    {
        $campaign = $this->_createCampaign($this->user);
        $group = $this->groups[1];
        $consumersCount = $group->consumers()->count();
        $I->assertEquals(4, $consumersCount);

        Campaign::sendConsumers($campaign, [$group->id]);

        $I->amOnRoute('consumer-hub.campaigns.history');
        $I->seeNumberOfElements('.item-row', $consumersCount);
        $I->see($campaign->subject);
        $I->see($group->name);

        foreach ($group->consumers as $consumer) {
            $I->see($consumer->email);
        }
    }

    public function testHistoryByCampaign(FunctionalTester $I)
    {
        $campaigns = [];
        $campaigns[] = $this->_createCampaign($this->user);
        $campaigns[] = $this->_createCampaign($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        foreach ($campaigns as $campaign) {
            Campaign::sendConsumers($campaign, [$consumer->id]);
        }

        $I->amOnRoute('consumer-hub.campaigns.history');
        $I->seeNumberOfElements('.item-row', count($campaigns));
        foreach ($campaigns as $campaign) {
            $I->see($campaign->subject);
        }
        $I->see($consumer->email);

        foreach ($campaigns as $campaign) {
            $I->amOnRoute('consumer-hub.campaigns.history', ['campaign_id' => $campaign->id]);
            $I->seeNumberOfElements('.item-row', 1);
            $I->see($campaign->subject);
            $I->see($consumer->email);
        }
    }
}
