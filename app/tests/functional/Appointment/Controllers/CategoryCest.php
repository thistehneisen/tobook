<?php namespace Test\Appointment\Controllers;

use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
use App\Appointment\Models\ServiceTime;
use App\Appointment\Models\ExtraService;
use App\Appointment\Models\Booking;
use App\Appointment\Models\Employee;
use App\Core\Models\User;
use \FunctionalTester;
use Config;

/**
 * @group as
 */
class CategoryCest
{
    use \Appointment\Traits\Models;
    use \Appointment\Traits\Booking;

    private $categories = [];

    public function _before(FunctionalTester $I)
    {
        $this->_modelsReset();
        $this->_createUser();
        $this->_createEmployee();
        $this->categories = $this->_createCategoryServiceAndExtra(1, 0, 0, $this->employees[0]);

        $I->amLoggedAs($this->user);
    }

    public function testIndex(FunctionalTester $I)
    {
        $category = $this->categories[0];

        $I->amOnRoute('as.services.categories.index');
        $I->canSeeResponseCodeIs(200);
        $I->seeNumberOfElements('.item-row', 1);

        $rowSelector = '#row-' . $category->id;
        $I->seeElement($rowSelector);
        $I->see($category->name, $rowSelector . ' > *');
        $I->see($category->description, $rowSelector . ' > *');
        $I->see(trans('common.yes'), $rowSelector . ' > *');
        $I->seeElement($rowSelector . '-edit');
        $I->seeElement($rowSelector . '-delete');
    }

    public function testIsShowFrontFalse(FunctionalTester $I)
    {
        $category = $this->categories[0];
        $category->is_show_front = 0;
        $category->saveOrFail();

        $I->amOnRoute('as.services.categories.index');
        $I->seeNumberOfElements('.item-row', 1);

        $rowSelector = '#row-' . $category->id;
        $I->seeElement($rowSelector);
        $I->see(trans('common.no'), $rowSelector . ' > *');
    }

    public function testPagination(FunctionalTester $I)
    {
        $this->categories = array_merge($this->categories, $this->_createCategoryServiceAndExtra(49, 0, 0, $this->employees[0]));

        $I->amOnRoute('as.services.categories.index');
        $I->seeNumberOfElements('.item-row', min(count($this->categories), Config::get('view.perPage')));

        foreach ([5, 10, 20, 50] as $perPage) {
            $I->click('#per-page-' . $perPage);
            $I->seeCurrentRouteIs('as.services.categories.index', ['perPage' => $perPage]);
            $I->seeNumberOfElements('.item-row', $perPage);

            $page = 1;
            $categories = $this->categories;
            do {
                if ($page > 1) {
                    $I->amOnRoute('as.services.categories.index', ['perPage' => $perPage, 'page' => $page]);
                }

                for ($i = 0; $i < $perPage; $i++) {
                    $category = array_shift($categories);
                    $I->seeElement('#row-' . $category->id);
                }

                $page++;
            } while ($page <= count($this->categories) / $perPage);
        }
    }

    public function testAddIsShowFrontTrue(FunctionalTester $I)
    {
        $name = 'Category ' . time();
        $description = $name . ' description';

        $I->amOnRoute('as.services.categories.upsert');
        $I->fillField('name', $name);
        $I->fillField('description', $description);

        // 1 should be checked by default
        // $I->selectOption('is_show_front', 1);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.categories.index');
        $I->seeNumberOfElements('.item-row', 2);

        $categories = ServiceCategory::ofCurrentUser()->where('id', '<>', $this->categories[0]->id)->get();
        $I->assertEquals(1, count($categories), 'categories');
        foreach ($categories as $category) {
            $I->assertEquals($name, $category->name, 'name');
            $I->assertEquals($description, $category->description, 'description');
            $I->assertEquals(1, $category->is_show_front, 'is_show_front');
        }
    }

    public function testAddIsShowFrontFalse(FunctionalTester $I)
    {
        $name = 'Category ' . time();
        $description = $name . ' description';

        $I->amOnRoute('as.services.categories.upsert');
        $I->fillField('name', $name);
        $I->fillField('description', $description);
        $I->selectOption('is_show_front', 0);
        $I->click('#btn-save');

        $I->seeCurrentRouteIs('as.services.categories.index');
        $I->seeNumberOfElements('.item-row', 2);

        $categories = ServiceCategory::ofCurrentUser()->where('id', '<>', $this->categories[0]->id)->get();
        $I->assertEquals(1, count($categories), 'categories');
        foreach ($categories as $category) {
            $I->assertEquals($name, $category->name, 'name');
            $I->assertEquals($description, $category->description, 'description');
            $I->assertEquals(0, $category->is_show_front, 'is_show_front');
        }
    }

    public function testEditName(FunctionalTester $I)
    {
        $category = $this->categories[0];

        $I->amOnRoute('as.services.categories.upsert', ['id' => $category->id]);
        $I->seeInField('name', $category->name);

        $newName = $category->name . ' Edited';
        $I->fillField('name', $newName);
        $I->click('#btn-save');

        $category = ServiceCategory::find($category->id);
        $I->assertEquals($newName, $category->name, 'name');
    }

    public function testEditDescription(FunctionalTester $I)
    {
        $category = $this->categories[0];

        $I->amOnRoute('as.services.categories.upsert', ['id' => $category->id]);
        $I->seeInField('description', $category->description);

        $newDescription = 'Description ' . time();
        $I->fillField('description', $newDescription);
        $I->click('#btn-save');

        $category = ServiceCategory::find($category->id);
        $I->assertEquals($newDescription, $category->description, 'description');
    }

    public function testEditIsShowFrontFalse(FunctionalTester $I)
    {
        $category = $this->categories[0];

        $I->amOnRoute('as.services.categories.upsert', ['id' => $category->id]);
        $I->seeOptionIsSelected('is_show_front', 1);

        $I->selectOption('is_show_front', 0);
        $I->click('#btn-save');

        $category = ServiceCategory::find($category->id);
        $I->assertEquals(0, $category->is_show_front, 'is_show_front');
    }

    public function testEditIsShowFrontTrue(FunctionalTester $I)
    {
        $category = $this->categories[0];
        $category->is_show_front = 0;
        $category->saveOrFail();

        $I->amOnRoute('as.services.categories.upsert', ['id' => $category->id]);
        $I->seeOptionIsSelected('is_show_front', 0);

        $I->selectOption('is_show_front', 1);
        $I->click('#btn-save');

        $category = ServiceCategory::find($category->id);
        $I->assertEquals(1, $category->is_show_front, 'is_show_front');
    }

    public function testDelete(FunctionalTester $I)
    {
        $category = $this->categories[0];

        $I->amOnRoute('as.services.categories.delete', ['id' => $category->id]);
        $I->seeCurrentRouteIs('as.services.categories.index');

        $category = ServiceCategory::find($category->id);
        $I->assertEmpty($category, 'category has been deleted');
    }

    /**
     * This test check if we can delete a category which has a service or not
     * If the category is deletable, the test is failed
     */
    public function testDeleteCategoryHasService(FunctionalTester $I)
    {
        $user = User::find(70);
        $this->initCategory();
        $category = ServiceCategory::find(189);//see initCategory function

        $I->amOnRoute('as.services.categories.delete', ['id' => $category->id]);
        $I->seeCurrentRouteIs('as.services.categories.index');
        $I->dontSee('div.alert-success');
        $I->seeElement('div.alert-danger');

        $category = ServiceCategory::find($category->id);
        $I->assertNotEmpty($category, 'category 1 has been found');
    }

    public function testBulkDelete(FunctionalTester $I)
    {
        $categories = $this->_createCategoryServiceAndExtra(2, 0, 0);

        $category1 = ServiceCategory::find($categories[0]->id);
        $I->assertNotEmpty($category1, 'category 1 has been found');
        $category2 = ServiceCategory::find($categories[1]->id);
        $I->assertNotEmpty($category2, 'category 2 has been found');

        $I->sendAjaxPostRequest(route('as.services.categories.bulk'), [
            'action' => 'destroy',
            'ids' => [$category1->id, $category2->id]
        ]);

        $category1 = ServiceCategory::find($categories[0]->id);
        $I->assertEmpty($category1, 'category 1 has been deleted');
        $category2 = ServiceCategory::find($categories[1]->id);
        $I->assertEmpty($category2, 'category 2 has been deleted');
    }

    /**
     * Create a category which has one service
     */
    private function initCategory()
    {
        $employee = new Employee([
            'name' => 'Employee',
            'email' => 'employee@varaa.com',
            'phone' => '1234567890',
            'is_active' => 1,
        ]);
        $employee->user()->associate($this->user);
        $employee->saveOrFail();

        ServiceCategory::where('id', 189)->forceDelete();
        $category = new ServiceCategory([
            'name' => 'Service Category',
            'is_show_front' => 1,
        ]);
        $category->id = 189;
        $category->user()->associate($this->user);
        $category->saveOrFail();

        $service = new Service([
            'name' => 'Klassinen hieronta',
            'length' => 45,
            'during' => 30,
            'after' => 15,
            'price' => 35,
            'is_active' => 1,
        ]);

        $service->user()->associate($this->user);
        $service->category()->associate($category);
        $service->saveOrFail();
        $service->employees()->attach($employee);
    }
}
