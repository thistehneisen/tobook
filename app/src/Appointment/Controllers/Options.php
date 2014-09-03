<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Lomake\Fields\Factory as FieldFactory;
use App\Appointment\Models\Option;

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

        // Get default settings of this page to generate form for user to edit
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

    /**
     * Receive user settings and update changes in database
     * Ignore updating if selected value is not different from default settings
     *
     * @param string $page
     *
     * @return Redirect
     */
    public function update($page = null)
    {
        $input = Input::all();
        unset($input['_token']);

        $dirty = [];
        foreach ($input as $field => $value) {
            $default = $this->user->as_options->get($field);
            // It's very long time ago since I use non-strict comparison, but
            // it's acceptable here, since some options are just '1' or true.
            if ($value != $default) {
                $dirty[$field] = $value;
            }
        }

        Option::upsert($this->user, $dirty);

        return Redirect::back()->with('messages', $this->successMessageBag('Updated'));
    }
}
