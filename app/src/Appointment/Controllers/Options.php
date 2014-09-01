<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Lomake\Fields\Factory as FieldFactory;

class Options extends AsBase
{
    protected $viewPath = 'modules.as.options';

    /**
     * Show the form of options
     *
     * @param string $page
     *
     * @return View
     */
    public function index($page = null)
    {
        if ($page === null) {
            $page = 'general';
        }

        // Get options
        $fields = [];
        $sections = [];
        $options = Config::get('appointment.options.'.$page);
        foreach ($options as $section => $controls) {
            $allControls = [];

            foreach ($controls as $name => $params) {
                $params['name'] = $name;
                $allControls[] = FieldFactory::create($params);
            }

            $sections[] = $section;
            $fields[$section] = $allControls;
        }

        return $this->render('page', [
            'page'     => $page,
            'fields'   => $fields,
            'sections' => $sections
        ]);
    }
}
