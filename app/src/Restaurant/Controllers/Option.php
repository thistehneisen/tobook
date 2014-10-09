<?php namespace App\Restaurant\Controllers;

use Config, Input, Redirect;
use App\Core\Controllers\Base;
use App\Lomake\FieldFactory;
use App\Restaurant\Models\Option as OptionModel;

class Option extends Base
{
    protected $viewPath = 'modules.rb.options';

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
        $userOptions = $this->user->rb_options;

        // Get default settings of this page to generate form for user to edit
        $fields = [];
        $sections = [];
        $options = Config::get('restaurant.options.'.$page);
        foreach ($options as $section => $controls) {
            $allControls = [];

            foreach ($controls as $name => $params) {
                $params['name'] = $name;
                if (isset($userOptions[$name])) {
                    $params['default'] = $userOptions[$name];
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
        $userOptions = $this->user->rb_options;
        foreach ($input as $field => $value) {
            $default = $userOptions->get($field);
            // It's very long time ago since I use non-strict comparison, but
            // it's acceptable here, since some options are just '1' or true.
            if ($value != $default) {
                $dirty[$field] = $value;
            }
        }

        OptionModel::upsert($this->user, $dirty);

        return Redirect::back()->with(
            'messages',
            $this->successMessageBag(trans('rb.options.updated'))
        );
    }
}
