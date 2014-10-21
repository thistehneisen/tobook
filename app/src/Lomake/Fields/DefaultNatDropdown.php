<?php namespace App\Lomake\Fields;

use Carbon\Carbon;
use App\Appointment\Models\Service;
use App\Appointment\Models\ServiceCategory;
/**
 * Label of options in dropdown will show current date time in format
 */
class DefaultNatDropdown extends Dropdown
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->getParams();
        // Print the current date as label
        $params['values'] = $this->makeServices();

        return call_user_func_array('Form::select', $params);
    }

    /**
     * Print current date as values in dropdown list
     *
     * @param array $arr
     *
     * @return array
     */
    protected function makeServices()
    {
        $services = [];
        $services[-1] = trans('common.select');

        $categories =  ServiceCategory::OfCurrentUser()
        ->orderBy('order')
        ->with(['services' => function ($query) {
            return $query->where('is_active', true);
        }])->where('is_show_front', true)->get();

        foreach ($categories as $category) {
            foreach ($category->services as $service) {
                $services[$category->name][$service->id] = $service->name;
            }
        }
        return $services;
    }
}
