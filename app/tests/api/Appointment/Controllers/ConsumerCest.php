<?php
namespace Appointment\Schedule;

use \ApiTester;
use App\Appointment\Models\Consumer;
use Appointment\Traits\Booking;
use Appointment\Traits\Models;
use Carbon\Carbon;

class ConsumerCest
{
    use Booking;
    use Models;

    protected $servicesEndpoint = '/api/v1.0/as/consumers';
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

        $I->sendGET($this->servicesEndpoint);
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

}
