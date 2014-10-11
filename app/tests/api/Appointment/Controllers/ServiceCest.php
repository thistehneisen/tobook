<?php
namespace Appointment\Schedule;

use \ApiTester;
use Appointment\Traits\Models;
use Carbon\Carbon;

class ServiceCest
{
    use Models;

    const CATEGORY_COUNT = 5;
    const SERVICE_COUNT = 10;
    const EXTRA_SERVICE_COUNT = 2;

    protected $servicesEndpoint = '/api/v1.0/as/services';
    protected $categoriesEndpoint = '/api/v1.0/as/service-categories';
    protected $categories = null;

    public function _before(ApiTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();
        $this->_createEmployee(2);

        $this->categories = $this->_createCategoryServiceAndExtra(self::CATEGORY_COUNT, self::SERVICE_COUNT, self::EXTRA_SERVICE_COUNT, $this->employees[0]);

        // attach second employee to services in the first category
        $category = $this->categories[0];
        foreach ($category->services as $service) {
            $service->employees()->attach($this->employees[1]);
        }

        // do not use amHttpAuthenticated because route filters are disabled by default
        // and amLoggedAs is just faster!
        $I->amLoggedAs($this->user);
    }

    public function testServices(ApiTester $I)
    {
        $I->sendGET($this->servicesEndpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $services = $I->grabDataFromJsonResponse('services');
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::CATEGORY_COUNT * self::SERVICE_COUNT, $pagination['total'], "\$pagination['total']");
        $I->assertEquals($pagination['per_page'], count($services), 'count($services)');
        $I->assertEquals(1, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(ceil(self::CATEGORY_COUNT * self::SERVICE_COUNT / count($services)), $pagination['last_page'], "\$pagination['last_page']");
    }

    public function testServicesPagination(ApiTester $I)
    {
        $I->sendGET($this->servicesEndpoint . '?per_page=1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $services = $I->grabDataFromJsonResponse('services');
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::CATEGORY_COUNT * self::SERVICE_COUNT, $pagination['total'], "\$pagination['total']");
        $I->assertEquals(1, $pagination['per_page'], "\$pagination['per_page']");
        $I->assertEquals($pagination['per_page'], count($services), 'count($services)');
        $I->assertEquals(1, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(self::CATEGORY_COUNT * self::SERVICE_COUNT, $pagination['last_page'], "\$pagination['last_page']");

        $I->sendGET($this->servicesEndpoint . '?per_page=1&page=' . self::SERVICE_COUNT);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $services = $I->grabDataFromJsonResponse('services');
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::CATEGORY_COUNT * self::SERVICE_COUNT, $pagination['total'], "\$pagination['total']");
        $I->assertEquals(1, $pagination['per_page'], "\$pagination['per_page']");
        $I->assertEquals($pagination['per_page'], count($services), 'count($services)');
        $I->assertEquals(self::SERVICE_COUNT, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(self::CATEGORY_COUNT * self::SERVICE_COUNT, $pagination['last_page'], "\$pagination['last_page']");
    }

    public function testServicesByCategory(ApiTester $I)
    {
        $category = $this->categories[0];

        $I->sendGET($this->servicesEndpoint . '?category_id=' . $category->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $services = $I->grabDataFromJsonResponse('services');
        foreach ($services as $service) {
            $I->assertEquals($category->id, $service['category_id'], "\$service['category_id']");
        }

        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::SERVICE_COUNT, $pagination['total'], "\$pagination['total']");
    }

    public function testServicesByEmployee(ApiTester $I)
    {
        $I->sendGET($this->servicesEndpoint . '?employee_id=' . $this->employees[0]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::CATEGORY_COUNT * self::SERVICE_COUNT, $pagination['total'], "\$pagination['total']");

        // second round with another employee, services from first category should be returned for this one
        $I->sendGET($this->servicesEndpoint . '?employee_id=' . $this->employees[1]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::SERVICE_COUNT, $pagination['total'], "\$pagination['total']");
    }

    public function testServicesByCategoryAndEmployee(ApiTester $I)
    {
        $category = $this->categories[0];

        // first category, second employee, all category services should be returned
        $I->sendGET($this->servicesEndpoint . '?category_id=' . $category->id . '&employee_id=' . $this->employees[1]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $services = $I->grabDataFromJsonResponse('services');
        foreach ($services as $service) {
            $I->assertEquals($category->id, $service['category_id'], "\$service['category_id']");
        }
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::SERVICE_COUNT, $pagination['total'], "\$pagination['total']");

        // second category, second employee, no services should be returned
        $I->sendGET($this->servicesEndpoint . '?category_id=' . $this->categories[1]->id . '&employee_id=' . $this->employees[1]->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(0, $pagination['total'], "\$pagination['total']");
    }

    public function testOneService(ApiTester $I)
    {
        $service = $this->categories[0]->services()->first();

        $I->sendGET($this->servicesEndpoint . '/' . $service->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $serviceData = $I->grabDataFromJsonResponse('service');
        $I->assertEquals('service', $serviceData['type'], "\$serviceData['type']");
        $I->assertEquals($service->name, $serviceData['service_name'], "\$serviceData['service_name']");
        $I->assertEquals($service->description, $serviceData['service_description'], "\$serviceData['service_description']");
        $I->assertEquals(1, $serviceData['service_is_active'], "\$serviceData['service_is_active']");

        $I->assertEquals(1, count($serviceData['service_times']), "count(\$serviceData['service_times'])");
        $I->assertEquals('default', $serviceData['service_times'][0]['service_time_id'], "\$serviceData['service_times'][0]['service_time_id']");
        $I->assertEquals($service->length, $serviceData['service_times'][0]['length'], "\$serviceData['service_times'][0]['length']");
        $I->assertEquals($service->price, $serviceData['service_times'][0]['price'], "\$serviceData['service_times'][0]['price']");

        $I->assertEquals($service->extraServices()->count(), count($serviceData['extra_services']), "count(\$serviceData['extra_services'])");
        foreach ($service->extraServices as $extraService) {
            $extraServiceDataFound = false;

            foreach ($serviceData['extra_services'] as $extraServiceData) {
                if ($extraServiceData['extra_service_id'] == $extraService->id) {
                    $I->assertEquals($extraService->name, $extraServiceData['name'], "\$extraServiceData['name']");
                    $I->assertEquals($extraService->description, $extraServiceData['description'], "\$extraServiceData['description']");
                    $I->assertEquals($extraService->length, $extraServiceData['length'], "\$extraServiceData['length']");
                    $I->assertEquals($extraService->price, $extraServiceData['price'], "\$extraServiceData['price']");

                    $extraServiceDataFound = true;
                }
            }

            $I->assertTrue($extraServiceDataFound, '$extraServiceDataFound');
        }
    }

    public function testCategories(ApiTester $I)
    {
        $I->sendGET($this->categoriesEndpoint);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $categories = $I->grabDataFromJsonResponse('categories');
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::CATEGORY_COUNT, $pagination['total'], "\$pagination['total']");
        $I->assertEquals(1, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(ceil(self::CATEGORY_COUNT / count($categories)), $pagination['last_page'], "\$pagination['last_page']");
    }

    public function testCategoriesPagination(ApiTester $I)
    {
        $I->sendGET($this->categoriesEndpoint . '?per_page=1');
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $categories = $I->grabDataFromJsonResponse('categories');
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::CATEGORY_COUNT, $pagination['total'], "\$pagination['total']");
        $I->assertEquals(1, $pagination['per_page'], "\$pagination['per_page']");
        $I->assertEquals($pagination['per_page'], count($categories), 'count($categories)');
        $I->assertEquals(1, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(self::CATEGORY_COUNT, $pagination['last_page'], "\$pagination['last_page']");

        $I->sendGET($this->categoriesEndpoint . '?per_page=1&page=' . self::CATEGORY_COUNT);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $categories = $I->grabDataFromJsonResponse('categories');
        $pagination = $I->grabDataFromJsonResponse('pagination');
        $I->assertEquals(self::CATEGORY_COUNT, $pagination['total'], "\$pagination['total']");
        $I->assertEquals(1, $pagination['per_page'], "\$pagination['per_page']");
        $I->assertEquals($pagination['per_page'], count($categories), 'count($categories)');
        $I->assertEquals(self::CATEGORY_COUNT, $pagination['page'], "\$pagination['page']");
        $I->assertEquals(self::CATEGORY_COUNT, $pagination['last_page'], "\$pagination['last_page']");
    }

    public function testOneCategory(ApiTester $I)
    {
        $category = $this->categories[0];

        $I->sendGET($this->categoriesEndpoint . '/' . $category->id);
        $I->seeResponseCodeIs(200);
        $I->seeResponseIsJson();

        $categoryData = $I->grabDataFromJsonResponse('category');
        $I->assertEquals('service_category', $categoryData['type'], "\$categoryData['type']");
        $I->assertEquals($category->name, $categoryData['category_name'], "\$categoryData['category_name']");
        $I->assertEquals($category->description, $categoryData['category_description'], "\$categoryData['category_description']");
    }
}
