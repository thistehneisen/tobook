<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, Response;
use App\Appointment\Models\ExtraService;

class ExtraServices extends AsBase
{
    public function extras()
    {
        $perPage = (int) Input::get('perPage', Config::get('view.perPage'));
        $extras = ExtraService::ofCurrentUser()->paginate($perPage);

        return View::make('modules.as.services.extras', [
            'extras' => $extras
        ]);
    }
}
