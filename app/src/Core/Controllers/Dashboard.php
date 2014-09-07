<?php namespace App\Core\Controllers;

use View, Validator, Input, Redirect, Config, Session, URL;
use User;
use Confide;
use App\Core\Models\Module;

class Dashboard extends Base
{
    public function index()
    {
        $services = [
            //'site'        => '',
            //'gallery'     => '',
            //'profile'     => route('user.profile'),
            //'promotion'   => '',
            'cashier'     => route('cashier.index'),
            'restaurant'  => route('restaurant.index'),
            'timeslot'    => route('timeslot.index'),
            'appointment' => route('appointment.index'),
            'loyalty'     => route('loyalty.index'),
            //'martketing'  => route('marketing.index'),
        ];

        // Get all modules availables in the system
        $modules = Module::all();
        $now = \Carbon\Carbon::now();
        $activeModules = Confide::user()
            ->getActiveModules()
            ->lists('name');

        // Remove inactive modules
        if (Config::get('varaa.dashboard_hide_inactive')) {
            $modules = $modules->filter(function($module) use ($activeModules) {
                return in_array($module->name, $activeModules);
            });
        }

        return View::make('dashboard.index', [
            'user'          => Confide::user(),
            'modules'       => $modules,
            'activeModules' => $activeModules
        ]);
    }
}
