<?php namespace App\Core\Controllers\Ajax;

use App\Core\Models\Business;
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
