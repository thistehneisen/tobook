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
        //Not sure if it is a good idea, but ConfideUser need to use app config
        // $app = require_once './bootstrap/start.php';
        // $app->run();

        $this->_modelsReset();
        $this->categories = $this->_createCategoryServiceAndExtra();
    }
}
