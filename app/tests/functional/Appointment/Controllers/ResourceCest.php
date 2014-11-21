<?php namespace Test\Appointment\Controllers;

use App\Appointment\Models\Resource;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Core\Models\User;
use \FunctionalTester;
use Config;

/**
 * @group as
 */
class ResourceCest
{
    use \Test\Traits\Models;

    /**
     * @var Service
     */
    private $service = null;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        Resource::boot();

        $this->_createUser();
        $this->_createEmployee();

        $categories = $this->_createCategoryServiceAndExtra(1, 1, 0);
        $this->service = $categories[0]->services()->first();

        $I->amLoggedAs($this->user);
    }

    public function testIndex(FunctionalTester $I)
    {
        $resource = $this->_createResource($this->service);

        $I->amOnRoute('as.services.resources.index');
        $I->canSeeResponseCodeIs(200);
        $I->seeNumberOfElements('.item-row', 1);

        $rowSelector = '#row-' . $resource->id;
        $I->seeElement($rowSelector);
        $I->see($resource->name, $rowSelector . ' > *');
        $I->see($resource->description, $rowSelector . ' > *');
        $I->see($resource->quantity, $rowSelector . ' > *');
        $I->seeElement($rowSelector . '-edit');
        $I->seeElement($rowSelector . '-delete');
    }

    public function testPagination(FunctionalTester $I)
    {
        $resources = [];
        for ($i = 0; $i < 50; $i++) {
            $resources[$i] = $this->_createResource($this->service);
        }

        $I->amOnRoute('as.services.resources.index');
        $I->seeNumberOfElements('.item-row', min(count($resources), Config::get('view.perPage')));

        foreach ([5, 10, 20, 50] as $perPage) {
            $I->click('#per-page-' . $perPage);
            $I->seeCurrentRouteIs('as.services.resources.index', ['perPage' => $perPage]);
            $I->seeNumberOfElements('.item-row', $perPage);

            $page = 1;
            $resourcesCopy = $resources;
            do {
                if ($page > 1) {
                    $I->amOnRoute('as.services.resources.index', ['perPage' => $perPage, 'page' => $page]);
                }

                for ($i = 0; $i < $perPage; $i++) {
                    $resource = array_shift($resourcesCopy);
                    $I->seeElement('#row-' . $resource->id);
                }

                $page++;
            } while ($page <= count($resources) / $perPage);
        }
    }

    public function testAddSuccess(FunctionalTester $I)
    {
        $name = 'Resource ' . time();
        $description = $name . ' description';
        $quantity = 10;

        $I->amOnRoute('as.services.resources.upsert');
        $I->fillField('name', $name);
        $I->fillField('description', $description);
        $I->fillField('quantity', $quantity);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.resources.index');
        $I->seeNumberOfElements('.item-row', 1);

        $resources = Resource::ofCurrentUser()->get();
        $I->assertEquals(1, count($resources), 'resources');
        foreach ($resources as $resource) {
            $I->assertEquals($name, $resource->name, 'name');
            $I->assertEquals($description, $resource->description, 'description');
            $I->assertEquals($quantity, $resource->quantity, 'quantity');
        }
    }

    public function testAddWithoutName(FunctionalTester $I)
    {
        $name = 'Resource ' . time();
        $description = $name . ' description';
        $quantity = 20;

        $I->amOnRoute('as.services.resources.upsert');
        $I->fillField('description', $description);
        $I->fillField('quantity', $quantity);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.resources.upsert');
        $I->seeSessionHasErrors();

        $resources = Resource::ofCurrentUser()->get();
        $I->assertEquals(0, count($resources), 'resources');
    }

    public function testDelete(FunctionalTester $I)
    {
        $resource = $this->_createResource($this->service);
        $I->assertNotEmpty($resource, 'resource has been created');

        $I->amOnRoute('as.services.resources.delete', ['id' => $resource->id]);
        $I->seeCurrentRouteIs('as.services.resources.index');

        $resource = ServiceCategory::find($resource->id);
        $I->assertEmpty($resource, 'resource has been deleted');
    }

    public function testBulkDelete(FunctionalTester $I)
    {
        $resource1 = $this->_createResource($this->service);
        $I->assertNotEmpty($resource1, 'resource 1 has been created');
        $resource2 = $this->_createResource($this->service);
        $I->assertNotEmpty($resource2, 'resource 2 has been created');

        $I->sendAjaxPostRequest(route('as.services.categories.bulk'), [
            'action' => 'destroy',
            'ids' => [$resource1->id, $resource2->id]
        ]);

        $resource1 = ServiceCategory::find($resource1->id);
        $I->assertEmpty($resource1, 'resource 1 has been deleted');
        $resource2 = ServiceCategory::find($resource2->id);
        $I->assertEmpty($resource2, 'resource 2 has been deleted');
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
