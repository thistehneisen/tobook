<?php namespace App\Core\Controllers\Ajax;

use DB, Carbon\Carbon, Request, View, Input;
use Illuminate\Support\Collection;
use App\Core\Models\BusinessCategory;
use App\Core\Models\User;
use App\Appointment\Controllers\Embed\Layout;
use App\Appointment\Models\Service;

class Search extends Base
{
    use Layout;

    public function __construct()
    {
        $this->beforeFilter('ajax', ['except' => 'showBusiness']);
    }

    /**
     * Prepare services data for typeahead
     *
     * @return JSON
     */
    public function getServices()
    {
        $categories = BusinessCategory::getAll();
        $result = [];
        foreach ($categories as $cat) {
            $result[] = $cat->name;
            $result = array_merge($result, $cat->keywords);
            foreach ($cat->children as $subCat) {
                $result[] = $subCat->name;
                $result = array_merge($result, $subCat->keywords);
            }
        }

        return $this->json($result);
    }

    /**
     * Prepare location data for typeadhead
     *
     * @return JSON
     */
    public function getLocations()
    {
        $locations = DB::table(with(new User)->getTable())
            ->select('city AS name')
            ->where('city', '!=', '')
            ->distinct()
            ->get();

        return $this->json($locations);
    }

    /**
     * Show information of a business
     *
     * @param int $id
     *
     * @return View
     */
    public function showBusiness($id)
    {
        $business = User::findOrFail($id);
        $coupons = $business
            ->coupons()
            ->with('service')
            ->active()
            ->get();

        $flashDeals = $business
            ->flashDeals()
            ->with('flashDeal', 'flashDeal.service')
            ->active()
            ->get();

        $layout = $this->handleIndex($business->hash, $business,'layout-3');

        $data = [
            'business'   => $business,
            'coupons'    => $coupons,
            'flashDeals' => $flashDeals
        ];

        $viewData = array_merge($data, $layout);

        $view = $this->view('front.search.business', $viewData);

        if (Request::ajax()) {
            return $view;
        }

        Input::merge(array('l' => '3'));

        $nextSlots = $this->handleNextTimeSlot();

        $data = [
            'businesses' => new \Illuminate\Support\Collection([$business]),
            'single'     => $view,
            'lat'        => $business->lat,
            'lng'        => $business->lng,
            'now'        => Carbon::now()
        ];

        $data = array_merge($data, $nextSlots);

        $viewData = array_merge($data, $layout);

        return View::make('front.search.index', $viewData);
    }

    public function handleNextTimeSlot()
    {
        $serviceId  = Input::get('service_id', 0);
        $employeeId = Input::get('employee_id', 0);
        $hour       = Input::get('hour');
        $minute     = Input::get('minute');

        if(!empty($serviceId) && !empty($employeeId)
            && !empty($hour) && !empty($minute)){
            $service = Service::findOrFail($serviceId);
            $categoryId = $service->category->id;
        }

        $time = sprintf('%s:%s', $hour, $minute);

        return [
            'serviceId'  => $serviceId,
            'employeeId' => $employeeId,
            'categoryId' => $categoryId,
            'time'       => $time
        ];
    }
}
