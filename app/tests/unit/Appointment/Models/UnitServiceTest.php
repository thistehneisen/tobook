<?php
use App\Core\Models\User;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
class UnitServiceTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
    }

    protected function tearDown()
    {
    }


    public function testServiceLength(){
        $user = new User([
                'username' => 'admin',
                'email'    => 'admin@varaa.com'
            ]);

        $user->saveOrFailed();

        $category = ServiceCategory([
                'name' => 'Category 1',
                'description' => 'Category Desc'
            ]);

        $category->user()->associate($user);
        $category->saveOrFailed();

        $service = new Service([
                'name' => 'Service Test',
                'description' => 'Service Desc',
                'price' => 20,
                'before' => 15,
                'during' => 15,
                'after' => 15,
                'is_active'=> true
            ]);
        $service->user()->associate($user);
        $service->category()->associate($category);

        $service->saveOrFailed();
        $this->assertTrue(true);
    }

    // tests
    public function testMe()
    {
    }

}
