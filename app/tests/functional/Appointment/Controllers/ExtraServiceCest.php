<?php namespace Test\Appointment\Controllers;

use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use \FunctionalTester;
use Config;

/**
 * @group as
 */
class ExtraServiceCest
{
    use \Appointment\Traits\Models;

    /**
     * @var Service
     */
    private $service = null;

    /**
     * @var ExtraService
     */
    private $extraService = null;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        ExtraService::boot();

        $this->_createUser();
        $this->_createEmployee();

        $categories = $this->_createCategoryServiceAndExtra(1, 1, 1);
        $this->service = $categories[0]->services()->first();
        $this->extraService = $this->service->extraServices()->first();

        $I->amLoggedAs($this->user);
    }

    public function testIndex(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.extras.index');
        $I->canSeeResponseCodeIs(200);
        $I->seeNumberOfElements('.item-row', 1);

        $rowSelector = '#row-' . $this->extraService->id;
        $I->seeElement($rowSelector);
        $I->see($this->extraService->name, $rowSelector . ' > *');
        $I->see($this->extraService->description, $rowSelector . ' > *');
        $I->see($this->extraService->price, $rowSelector . ' > *');
        $I->see($this->extraService->length, $rowSelector . ' > *');
        $I->seeElement($rowSelector . '-edit');
        $I->seeElement($rowSelector . '-delete');
    }

    public function testPagination(FunctionalTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra(1, 1, 49);
        $extraServices = [$this->extraService];
        foreach ($categories[0]->services()->first()->extraServices as $extraService) {
            $extraServices[] = $extraService;
        }

        $I->amOnRoute('as.services.extras.index');
        $I->seeNumberOfElements('.item-row', min(count($extraServices), Config::get('view.perPage')));

        foreach ([5, 10, 20, 50] as $perPage) {
            $I->click('#per-page-' . $perPage);
            $I->seeCurrentRouteIs('as.services.extras.index', ['perPage' => $perPage]);
            $I->seeNumberOfElements('.item-row', $perPage);

            $page = 1;
            $extraServicesCopy = $extraServices;
            do {
                if ($page > 1) {
                    $I->amOnRoute('as.services.extras.index', ['perPage' => $perPage, 'page' => $page]);
                }

                for ($i = 0; $i < $perPage; $i++) {
                    $extraService = array_shift($extraServicesCopy);
                    $I->seeElement('#row-' . $extraService->id);
                }

                $page++;
            } while ($page <= count($extraServices) / $perPage);
        }
    }

    public function testAddSuccess(FunctionalTester $I)
    {
        $name = 'Resource ' . time();
        $description = $name . ' description';
        $price = 50;
        $length = 60;

        $I->amOnRoute('as.services.extras.upsert');
        $I->fillField('name', $name);
        $I->fillField('description', $description);
        $I->fillField('price', $price);
        $I->fillField('length', $length);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.extras.index');
        $I->seeNumberOfElements('.item-row', 2);

        $extraServices = ExtraService::ofCurrentUser()->where('id', '<>', $this->extraService->id)->get();
        $I->assertEquals(1, count($extraServices), 'extra services');
        foreach ($extraServices as $extraService) {
            $I->assertEquals($name, $extraService->name, 'name');
            $I->assertEquals($description, $extraService->description, 'description');
            $I->assertEquals($price, $extraService->price, 'price');
            $I->assertEquals($length, $extraService->length, 'length');
        }
    }

    public function testAddWithoutName(FunctionalTester $I)
    {
        $name = 'Resource ' . time();
        $description = $name . ' description';
        $price = 50;
        $length = 60;

        $I->amOnRoute('as.services.extras.upsert');
        $I->fillField('description', $description);
        $I->fillField('price', $price);
        $I->fillField('length', $length);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.extras.upsert');
        $I->seeSessionHasErrors(['name']);

        $extraServices = ExtraService::ofCurrentUser()->where('id', '<>', $this->extraService->id)->get();
        $I->assertEquals(0, count($extraServices), 'extra services');
    }

    public function testAddWithoutPrice(FunctionalTester $I)
    {
        $name = 'Resource ' . time();
        $description = $name . ' description';
        $length = 60;

        $I->amOnRoute('as.services.extras.upsert');
        $I->fillField('name', $name);
        $I->fillField('description', $description);
        $I->fillField('length', $length);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.extras.upsert');
        $I->seeSessionHasErrors(['price']);

        $extraServices = ExtraService::ofCurrentUser()->where('id', '<>', $this->extraService->id)->get();
        $I->assertEquals(0, count($extraServices), 'extra services');
    }

    public function testAddWithoutLength(FunctionalTester $I)
    {
        $name = 'Resource ' . time();
        $description = $name . ' description';
        $price = 50;

        $I->amOnRoute('as.services.extras.upsert');
        $I->fillField('name', $name);
        $I->fillField('description', $description);
        $I->fillField('price', $price);
        $I->fillField('length', '');
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.extras.upsert');
        $I->seeSessionHasErrors(['price']);

        $extraServices = ExtraService::ofCurrentUser()->where('id', '<>', $this->extraService->id)->get();
        $I->assertEquals(0, count($extraServices), 'extra services');
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.extras.delete', ['id' => $this->extraService->id]);
        $I->seeCurrentRouteIs('as.services.extras.index');

        $extraService = ServiceCategory::find($this->extraService->id);
        $I->assertEmpty($extraService, 'extra service has been deleted');
    }

    public function testBulkDelete(FunctionalTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra(1, 1, 2);
        $service = $categories[0]->services()->first();
        $extraService1 = $service->extraServices()->first();
        $I->assertNotEmpty($extraService1, 'extra service 1 has been created');
        $extraService2 = $service->extraServices()->where('id', '<>', $extraService1->id)->first();
        $I->assertNotEmpty($extraService2, 'extra service 2 has been created');

        $I->sendAjaxPostRequest(route('as.services.extras.bulk'), [
            'action' => 'destroy',
            'ids' => [$extraService1->id, $extraService2->id]
        ]);

        $extraService1 = ExtraService::find($extraService1->id);
        $I->assertEmpty($extraService1, 'extra service 1 has been deleted');
        $extraService2 = ExtraService::find($extraService2->id);
        $I->assertEmpty($extraService2, 'extra service 2 has been deleted');
    }

    /**
     * @param Service $service
     * @param User $user
     *
     * @return Resource
     */
    private function _createResource(Service $service, User $user = null)
    {
        static $i = 0;

        $resource = new Resource([
            'name' => 'Resource ' . (++$i),
            'description' => 'A resource',
            'quantity' => 10,
        ]);
        $resource->user()->associate($user ? $user : $this->user);
        $resource->save();
        $resource->services()->attach($service->id);

        return $resource;
    }
}
