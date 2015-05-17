<?php namespace App\Core\Controllers\Ajax;

use App;
use App\Appointment\Controllers\Embed\Layout;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\Service;
use App\Core\Models\Business;
use App\Core\Models\User;
use DB;
use Input;
use Request;
use View;

class Search extends Base
{
    use Layout;

    public function __construct()
    {
        $this->beforeFilter('ajax', [
            'except' => ['showBusiness']
        ]);
    }

    /**
     * Prepare services data for typeahead
     *
     * @return JSON
     */
    public function getServices()
    {
        $categories = MasterCategory::getAll()->map(function ($category) {
            return array_merge([$category->name],
                $category->treatments->lists('name'));
        })->flatten()->toArray();
        $businesses = Business::where('name', '!=', '')->lists('name');

        return array_merge($categories, $businesses);
    }

    /**
     * Prepare location data for typeadhead
     *
     * @return JSON
     */
    public function getLocations()
    {
        $locations = DB::table(with(new Business())->getTable())
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
        $user = User::with('business')->findOrFail($id);
        $coupons = $user
            ->coupons()
            ->with('service')
            ->active()
            ->get();

        $flashDeals = $user
            ->flashDeals()
            ->with('flashDeal', 'flashDeal.service')
            ->active()
            ->get();

        $layout = $this->handleIndex($user->hash, $user,'layout-3');

        // Data to be passed to view
        $data = array_merge([
            'business'   => $user->business,
            'coupons'    => $coupons,
            'flashDeals' => $flashDeals
        ], $layout);

        if (Request::ajax()) {
            return View::make('front.el.business', $data);
        }

        Input::merge(array('l' => '3', 'hash' => $user->hash));

        $data = [
            'business'       => $user->business,
            'lat'            => $user->business->lat,
            'lng'            => $user->business->lng,
            'businessesJson' => json_encode([$user->business]),
        ];

        $data = array_merge($data, $this->getNextTimeSlotData());

        $viewData = array_merge($data, $layout);

        return View::make('front.business', $viewData);
    }

    public function getNextTimeSlotData()
    {
        $serviceId  = Input::get('service_id', 0);
        $employeeId = Input::get('employee_id', 0);
        $hour       = Input::get('hour');
        $minute     = Input::get('minute');

        $categoryId = null;
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
