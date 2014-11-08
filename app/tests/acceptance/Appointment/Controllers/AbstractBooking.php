<?php namespace Test\Acceptance\Appointment\Controllers;

use Appointment\Traits\Booking;
use Appointment\Traits\Models;

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
