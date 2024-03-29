<?php namespace App\Core\Controllers\Ajax;

use App;
use App\Appointment\Controllers\Embed\Layout;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\Service;
use App\Core\Models\Business;
use App\Core\Models\User;
use App\Core\Models\Review;
use DB;
use Input;
use Request, Settings;
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
        $data = Business::getSearchableServices();
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
        $user = User::with('business')->where('id', '=', $id)->whereNull('deleted_at')->firstOrFail();

        $layout = $this->handleIndex($user->hash, $user, 'layout-3');


        $review = Review::where('user_id', '=', $id)->where('status', '=', Review::STATUS_APPROVED)
            ->select(DB::raw("AVG(environment) as avg_env"), 
                DB::raw("AVG(service) as avg_service"),
                DB::raw("AVG(price_ratio) as avg_price_ratio"),
                DB::raw("AVG(avg_rating) as avg_total"))->first();
            
        // Data to be passed to view
        $data = array_merge([
            'business'   => $user->business,
            'review' => $review
        ], $layout);

        if (Request::ajax()) {
            $view = sprintf('front.el.%s.business', Settings::get('default_layout'));
            if (is_tobook()){
                $view = sprintf('front.el.%s.tobook-business', Settings::get('default_layout'));
            }
            $data['ajax'] = true;
            return View::make($view, $data);
        }

        Input::merge(array('l' => '3', 'hash' => $user->hash));

        $data = [
            'business'       => $user->business,
            'review'         => $review,
            'lat'            => $user->business->lat,
            'lng'            => $user->business->lng,
            'businessesJson' => json_encode([$user->business])
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
