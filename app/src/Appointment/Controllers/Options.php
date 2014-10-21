<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config;
use App\Lomake\FieldFactory;
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

        // Get user options
        $userOptions = $this->user->as_options;

        // Get default settings of this page to generate form for user to edit
        $fields = [];
        $sections = [];
        $options = Config::get('appointment.options.'.$page);
        foreach ($options as $section => $controls) {
            $allControls = [];

            foreach ($controls as $name => $params) {
                $params['name'] = $name;
                if (isset($userOptions[$name])) {
                    $params['default'] = $userOptions[$name];
                }
                if(!empty($params['values'])) {
                    if($params['values'] instanceof \Closure) {
                        $params['values'] = $params['values']();
                    }
                }
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
        $userOptions = $this->user->as_options;
        foreach ($input as $field => $value) {
            $default = $userOptions->get($field);
            // It's very long time ago since I use non-strict comparison, but
            // it's acceptable here, since some options are just '1' or true.
            if ($value != $default) {
                $dirty[$field] = $value;
            }
        }

        Option::upsert($this->user, $dirty);

        return Redirect::back()->with(
            'messages',
            $this->successMessageBag(trans('as.options.updated'))
        );
    }

    /**
     * Show the form to update the working time of this shop owner
     *
     * @return View
     */
    public function workingTime()
    {
        $options = Config::get('appointment.options.working_time');
        if ($customOptions = $this->user->asOptions->get('working_time')) {
            $options = $customOptions;
        }

        return $this->render('working-time', [
            'options' => $options
        ]);
    }

    /**
     * Update working time
     *
     * @return Redirect
     */
    public function updateWorkingTime()
    {
        // Check if the current value was changed
        $new = Input::get('working_time');
        $old = $this->user->as_options->get('working_time');
        if ($old !== $new) {
            // We need to check if there's already a record in database
            $option = $this->user->asOptions()
                ->where('key', 'working_time')
                ->first();

            if ($option === null) {
                $option = new Option;
            }

            $option->fill([
                'key'   => 'working_time',
                'value' => Input::get('working_time')
            ]);

            $this->user->asOptions()->save($option);
        }

        return Redirect::back()->with(
            'messages',
            $this->successMessageBag(trans('as.options.updated'))
        );
    }
}
