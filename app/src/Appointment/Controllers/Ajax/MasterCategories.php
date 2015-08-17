<?php namespace App\Appointment\Controllers\Ajax;

use App, View, Redirect, Response, Request, Input, Config, Session, Event;
use App\Appointment\Models\MasterCategory;
use App\Appointment\Models\TreatmentType;

class MasterCategories extends \App\Core\Controllers\Ajax\Base
{

    /**
     *  Handle ajax request to return treatment types by certain master category
     *
     *  @return json
     **/
    public function getTreatmentTypes()
    {
        $masterCategoryId = (int) Input::get('master_category_id');
        $masterCategory = MasterCategory::find($masterCategoryId);
        $treatmentTypes = $masterCategory->treatments()->get();
        $data = [];

        $data[-1] = [
            'id'   => -1,
            'name' => sprintf('-- %s --', trans('common.select'))
        ];

        foreach ($treatmentTypes as $treatmentType) {
            $data[$treatmentType->id] = [
                'id'    => $treatmentType->id,
                'name'  => $treatmentType->name,
            ];
        }

        return $this->json(array_values($data));
    }
}
