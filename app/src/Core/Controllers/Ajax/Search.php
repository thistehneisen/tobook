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
        $data = [];

        $categories = MasterCategory::getAll();

        foreach ($categories as $category) {
            $data[] = ['type' => 'category', 'name' => $category->name, 'url' => $category->url];

            foreach ($category->treatments as $treament) {
                $data[] = ['type' => 'category', 'name' => $treament->name, 'url' => $treament->url];

                //Append keyword<->treatment to typehead json
                foreach ($treament->keywords as $keyword) {
                    $data[] = ['type' => 'category', 'name' => $keyword->keyword, 'url' => $treament->url];
                }
            }

            foreach ($category->keywords as $keyword) {
                $data[] = ['type' => 'category', 'name' => $keyword->keyword, 'url' => $category->url];
            }
        }

        $businesses = Business::notHidden()
            ->whereHas('user', function ($query) {
                $query->whereNull('deleted_at');
            })
            ->where('name', '!=', '')
            ->get();
        foreach ($businesses as $business) {
            $data[] = ['type' => 'business', 'name' => $business->name, 'url' => $business->business_url];
        }

        return $data;
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

        $layout = $this->handleIndex($user->hash, $user, 'layout-3');

        // Data to be passed to view
        $data = array_merge([
            'business'   => $user->business,
            'coupons'    => $coupons,
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
        if (!empty($serviceId) && !empty($employeeId)
            && !empty($hour) && !empty($minute)) {
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
