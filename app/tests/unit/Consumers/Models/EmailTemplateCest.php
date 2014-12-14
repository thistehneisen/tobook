<?php namespace Test\Unit\Consumers\Models;

use App\Consumers\Models\EmailTemplate;
use App\Consumers\Models\History;
use UnitTester;

/**
 * @group co
 */
class EmailTemplateCest
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
        $campaign = $this->_createEmailTemplate($this->user);
        $consumer = $this->groups[0]->consumers()->first();

        $sent = EmailTemplate::sendConsumers($campaign, [$consumer->id]);
        $I->assertEquals(1, $sent, '$sent');
        $I->assertEquals([$consumer->id], $campaign->histories()->lists('consumer_id'), 'history');

        $sent = EmailTemplate::sendConsumers($campaign, [$consumer->id]);
        $I->assertEquals(0, $sent, '$sent again');
        $I->assertEquals([$consumer->id], $campaign->histories()->lists('consumer_id'), 'history');
    }

    public function testSendGroups(UnitTester $I)
    {
        $campaign = $this->_createEmailTemplate($this->user);
        $group = $this->groups[0];
        $consumersCount = $group->consumers()->count();
        $I->assertEquals(3, $consumersCount);

        list($sent, $total) = EmailTemplate::sendGroups($campaign, [$group->id]);
        $I->assertEquals($consumersCount, $sent, '$sent');
        $I->assertEquals($consumersCount, $total, '$total');
        //$I->assertEquals([$group->id], $campaign->histories()->lists('group_id'), 'history');

        $group = $this->groups[1];
        $consumersCount = $group->consumers()->count();
        $I->assertEquals(4, $consumersCount);

        list($sent, $total) = EmailTemplate::sendGroups($campaign, [$group->id]);
        $I->assertEquals($consumersCount - 1, $sent, '$sent again');
        $I->assertEquals($consumersCount, $total, '$total again');
        // $I->assertEquals($this->groups, $campaign->histories()->lists('group_id'), 'history');
    }
}
