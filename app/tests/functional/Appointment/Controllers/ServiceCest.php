<?php namespace Test\Appointment\Controllers;

use App\Appointment\Models\Service;
use App\Core\Models\User;
use \FunctionalTester;
use Config;

/**
 * @group as
 */
class ServiceCest
{
    use \Test\Traits\Models;

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();
        $categories = $this->_createCategoryServiceAndExtra(1, 1, 0);
        $this->service = $categories[0]->services()->first();

        $I->amLoggedAs($this->user);
    }

    public function testIndex(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.index');
        $I->canSeeResponseCodeIs(200);
        $I->seeNumberOfElements('.item-row', 1);

        $rowSelector = '#row-' . $this->service->id;
        $I->seeElement($rowSelector);
        $I->see($this->service->name, $rowSelector . ' > *');
        $I->see($this->service->employees()->first()->name, $rowSelector . ' > *');
        $I->see($this->service->price, $rowSelector . ' > *');
        $I->see($this->service->during, $rowSelector . ' > *');
        $I->see($this->service->length, $rowSelector . ' > *');
        $I->see($this->service->category->name, $rowSelector . ' > *');
        $I->see(trans('common.yes'), $rowSelector . ' > *');
        $I->seeElement($rowSelector . '-edit');
        $I->seeElement($rowSelector . '-delete');
    }

    public function testIndexInactive(FunctionalTester $I)
    {
        $this->service->is_active = 0;
        $this->service->saveOrFail();

        $I->amOnRoute('as.services.index');
        $I->seeNumberOfElements('.item-row', 1);

        $rowSelector = '#row-' . $this->service->id;
        $I->seeElement($rowSelector);
        $I->see(trans('common.no'), $rowSelector . ' > *');
    }

    public function testAdd(FunctionalTester $I)
    {
        $name = 'Service ' . time();
        $description = $name . ' description';
        $price = 50;
        $during = 30;
        $before = 15;
        $after = 15;
        $category = $this->service->category;
        $employee = $this->service->employees()->first();
        $plustime = 60;

        $I->amOnRoute('as.services.upsert');
        $I->fillField('name', $name);
        $I->fillField('description', $description);
        $I->fillField('price', $price);
        $I->fillField('during', $during);
        $I->fillField('before', $before);
        $I->fillField('after', $after);
        $I->selectOption('category_id', $category->id);
        $I->checkOption('#employee-' . $employee->id);
        $I->selectOption('plustimes[' . $employee->id . ']', $plustime);
        $I->click('#btn-save-service');

        $I->seeCurrentRouteIs('as.services.index');
        $I->seeNumberOfElements('.item-row', 2);

        $services = Service::ofCurrentUser()->where('id', '<>', $this->service->id)->get();
        $I->assertEquals(1, count($services), 'services');
        foreach ($services as $service) {
            $I->assertEquals($name, $service->name, 'name');
            $I->assertEquals($description, $service->description, 'description');
            $I->assertEquals($price, $service->price, 'price');
            $I->assertEquals($during, $service->during, 'during');
            $I->assertEquals($before, $service->before, 'before');
            $I->assertEquals($after, $service->after, 'after');
            $I->assertEquals($before + $during + $after, $service->length, 'length');

            $I->assertEquals(1, $service->is_active, 'is_active');

            $I->assertEquals($category->id, $service->category_id, 'category_id');
            $I->assertNotNull($service->employees()->find($employee->id), 'employee');
            $I->assertEquals($plustime, $employee->getPlustime($service->id), 'plustime');
        }
    }

    public function testEditLengthDuring(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);
        $I->seeInField('during', $this->service->during);

        $delta = 60;
        $I->fillField('during', $this->service->during + $delta);
        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals($this->service->during + $delta, $service->during, 'during');
        $I->assertEquals($this->service->length + $delta, $service->length, 'length');
    }

    public function testEditLengthBefore(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);
        $I->seeInField('before', $this->service->before);

        $delta = 60;
        $I->fillField('before', $this->service->before + $delta);
        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals($this->service->before + $delta, $service->before, 'before');
        $I->assertEquals($this->service->length + $delta, $service->length, 'length');
    }

    public function testEditLengthAfter(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);
        $I->seeInField('after', $this->service->after);

        $delta = 60;
        $I->fillField('after', $this->service->after + $delta);
        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals($this->service->after + $delta, $service->after, 'after');
        $I->assertEquals($this->service->length + $delta, $service->length, 'length');
    }

    public function testEditCategory(FunctionalTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra(1, 0, 0);

        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);
        $I->seeOptionIsSelected('category_id', $this->service->category->name);

        $I->selectOption('category_id', $categories[0]->id);
        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals($categories[0]->id, $service->category_id, 'category_id');
    }

    public function testEditIsActiveFalse(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);
        $I->seeOptionIsSelected('is_active', 1);

        $I->selectOption('is_active', 0);
        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals(0, $service->is_active, 'is_active');
    }

    public function testEditIsActiveTrue(FunctionalTester $I)
    {
        $this->service->is_active = 0;
        $this->service->saveOrFail();

        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);
        $I->seeOptionIsSelected('is_active', 0);

        $I->selectOption('is_active', 1);
        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals(1, $service->is_active, 'is_active');
    }

    public function testEditEmployeesRemove(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);

        $checkbox = '#employee-' . $this->service->employees()->first()->id;
        $I->seeCheckboxIsChecked($checkbox);

        $I->uncheckOption($checkbox);
        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals(0, $service->employees()->count(), 'employee count');
    }

    public function testEditEmployeesAdd(FunctionalTester $I)
    {
        $this->_createEmployee(2);

        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);

        $serviceEmployee = $this->service->employees()->first();
        foreach ($this->employees as $employee) {
            $checkbox = '#employee-' . $employee->id;

            if ($employee->id == $serviceEmployee->id) {
                $I->seeCheckboxIsChecked($checkbox);
            } else {
                $I->dontSeeCheckboxIsChecked($checkbox);
                $I->checkOption($checkbox);
            }
        }

        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals(2, $service->employees()->count(), 'employee count');
    }

    public function testEditEmployeesReplace(FunctionalTester $I)
    {
        $this->_createEmployee(2);

        $I->amOnRoute('as.services.upsert', ['id' => $this->service->id]);

        $serviceEmployee = $this->service->employees()->first();
        foreach ($this->employees as $employee) {
            $checkbox = '#employee-' . $employee->id;

            if ($employee->id == $serviceEmployee->id) {
                $I->seeCheckboxIsChecked($checkbox);
                $I->uncheckOption($checkbox);
            } else {
                $I->dontSeeCheckboxIsChecked($checkbox);
                $I->checkOption($checkbox);
            }
        }

        $I->click('#btn-save-service');

        $service = Service::find($this->service->id);
        $I->assertEquals(1, $service->employees()->count(), 'employee count');
        $newEmployee = $service->employees()->first();
        $I->assertNotEquals($serviceEmployee->id, $newEmployee->id, 'employee id');
    }

    public function testDelete(FunctionalTester $I)
    {
        $I->amOnRoute('as.services.delete', ['id' => $this->service->id]);
        $I->seeCurrentRouteIs('as.services.index');

        $service = Service::find($this->service->id);
        $I->assertEmpty($service, 'service has been deleted');
    }

    public function testBulkDelete(FunctionalTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra(1, 2, 0);
        $services = $categories[0]->services;

        $serviceIds = [];
        foreach ($services as $service) {
            $serviceIds[] = $service->id;
        }

        $I->sendAjaxPostRequest(route('as.services.bulk'), [
            'action' => 'destroy',
            'ids' => $serviceIds,
        ]);

        $service1 = Service::find($serviceIds[0]);
        $I->assertEmpty($service1, 'service 1 has been deleted');
        $service2 = Service::find($serviceIds[1]);
        $I->assertEmpty($service2, 'service 2 has been deleted');
    }
}
