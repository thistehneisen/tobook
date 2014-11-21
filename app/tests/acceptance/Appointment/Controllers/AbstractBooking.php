<?php namespace Test\Acceptance\Appointment\Controllers;

use Test\Traits\Booking;
use Test\Traits\Models;

/**
 * @group as
 */
abstract class AbstractBooking
{
    use Models;
    use Booking;

    protected $categories = [];

    public function _before()
    {
        $this->_modelsReset();
        $this->categories = $this->_createCategoryServiceAndExtra();
    }
}
