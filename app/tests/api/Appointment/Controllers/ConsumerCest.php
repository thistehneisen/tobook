<?php
namespace Appointment\Schedule;

use \ApiTester;
use App\Appointment\Models\Consumer;
use Appointment\Traits\Booking;
use Appointment\Traits\Models;
use Carbon\Carbon;

/**
 * @group as
 */
class ConsumerCest
{
    use Booking;
    use Models;

    protected $consumersEndpoint = '/api/v1.0/as/consumers';
    protected $categories;

    public function _before(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();
        $this->_createEmployee();

        $this->categories = $this->_createCategoryServiceAndExtra(1, 1, 0, $this->employees[0]);

        // do not use amHttpAuthenticated because route filters are disabled by default
        // and amLoggedAs is just faster!
        $I->amLoggedAs($this->user);
    }

    public function testIndex(ApiTester $I)
    {
        $consumerCount = 3;

        for ($i = 0; $i < $consumerCount; $i++) {
            $this->_book($I, $this->user, $this->categories[0], sprintf('%02d:00', 8 + $i * 2));
        }

        $I->sendGET($this->consumersEndpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals($consumerCount, $pagination['total'], "\$pagination['total']");
        $I->assertEquals(1, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(1, $pagination['last_page'], "\$pagination['last_page']");

        $consumersData = $I->grabDataFromJsonResponse('data');
        $I->assertEquals($consumerCount, count($consumersData), 'count($consumers)');
        $consumers = Consumer::ofCurrentUser()->get();
        $I->assertEquals($consumerCount, count($consumers), 'count($consumers)');
        foreach ($consumers as $consumer) {
            $consumerDataFound = false;

            foreach ($consumersData as $consumerData) {
                if ($consumer->id == $consumerData['consumer_id']) {
                    $this->_assertConsumer($I, $consumer, $consumerData);
                    $consumerDataFound = true;
                }
            }

            $I->assertTrue($consumerDataFound, '$consumerDataFound');
        }
    }

    public function testStore(ApiTester $I)
    {
        $email = 'consumer'. time() . '@varaa.com';
        $firstName = 'First';
        $lastName = 'Last ' . time();
        $phone = time();

        $I->sendPOST($this->consumersEndpoint, [
            'email' => $email,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'phone' => $phone,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $consumerData = $I->grabDataFromJsonResponse('data');
        $I->assertEquals($email, $consumerData['consumer_email'], "\$consumerData['consumer_email']");
        $I->assertEquals($firstName, $consumerData['consumer_first_name'], "\$consumerData['consumer_first_name']");
        $I->assertEquals($lastName, $consumerData['consumer_last_name'], "\$consumerData['consumer_last_name']");
        $I->assertEquals($phone, $consumerData['consumer_phone'], "\$consumerData['consumer_phone']");

        $consumer = Consumer::ofCurrentUser()->findOrFail($consumerData['consumer_id']);
        $this->_assertConsumer($I, $consumer, $consumerData);
    }

    public function testShow(ApiTester $I)
    {
        $this->_book($I, $this->user, $this->categories[0]);

        $consumer = Consumer::ofCurrentUser()->first();

        $I->sendGET($this->consumersEndpoint . '/' . $consumer->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $consumerData = $I->grabDataFromJsonResponse('data');
        $this->_assertConsumer($I, $consumer, $consumerData);
    }

    public function testUpdate(ApiTester $I)
    {
        $this->_book($I, $this->user, $this->categories[0]);
        $consumer = Consumer::ofCurrentUser()->first();

        $firstName = 'First';
        $lastName = 'Last ' . time();
        $phone = time();

        $I->sendPUT($this->consumersEndpoint . '/' . $consumer->id, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'email' => $consumer->email,
            'phone' => $phone,
        ]);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $consumerData = $I->grabDataFromJsonResponse('data');
        $I->assertEquals($consumer->id, $consumerData['consumer_id'], "\$consumerData['consumer_id']");
        $I->assertEquals($firstName, $consumerData['consumer_first_name'], "\$consumerData['consumer_first_name']");
        $I->assertEquals($lastName, $consumerData['consumer_last_name'], "\$consumerData['consumer_last_name']");
        $I->assertEquals($phone, $consumerData['consumer_phone'], "\$consumerData['consumer_phone']");

        $consumer = Consumer::ofCurrentUser()->findOrFail($consumerData['consumer_id']);
        $this->_assertConsumer($I, $consumer, $consumerData);
    }

    public function testDestroy(ApiTester $I)
    {
        $this->_book($I, $this->user, $this->categories[0]);
        $consumer = Consumer::ofCurrentUser()->first();

        $I->sendDELETE($this->consumersEndpoint . '/' . $consumer->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $I->seeResponseContainsJson(['error' => false]);

        $consumer = Consumer::ofCurrentUser()->find($consumer->id);
        $I->assertEmpty($consumer, '$consumer');
    }

}
