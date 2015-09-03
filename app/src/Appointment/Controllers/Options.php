<?php namespace App\Appointment\Controllers;

use App, View, Confide, Redirect, Input, Config, NAT, Closure;
use Util, Entrust, Session;
use App\Lomake\FieldFactory;
use App\Appointment\Models\Option;
use App\Appointment\Models\Discount;
use App\Appointment\Models\DiscountLastMinute;

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
                // Don't display option with attribute admin_only to non-admin users
                if (isset($params['admin_only']) && $params['admin_only']) {
                    if (!$this->user->isAdmin && (Session::get('stealthMode') === null)) {
                        continue;
                    }
                }
                if (isset($userOptions[$name])) {
                    $params['default'] = $userOptions[$name];
                }
                if (!empty($params['values'])) {
                    if ($params['values'] instanceof Closure) {
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
        $errors = [];
        foreach ($input as $field => $value) {
            $default = $userOptions->get($field);
            // It's very long time ago since I use non-strict comparison, but
            // it's acceptable here, since some options are just '1' or true.
            if ($value != $default) {
                $dirty[$field] = $value;
            }

            if ($field === 'style_external_css') {
                if (trim($value) === '') {
                    continue;
                }
                $filetype = Util::getRemoteFileType($value);
                if ($filetype !== 'text/css') {
                    $dirty[$field] = $default;
                    $errors[]['msg'] = trans('as.options.invalid_' . $field);
                }
            }
        }
        Option::upsert($this->user, $dirty);

        $redirect = Redirect::back();

        if (!empty($errors)) {
            $redirect->withErrors($errors);
        } else {
            $redirect->with('messages', $this->successMessageBag(trans('as.options.updated')));
        }

        return $redirect;
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

        //Prevent user input crazy data.
        $invalidData = [];
        foreach ($new as $weekday) {
            foreach ($weekday as $key => $value) {
                $dateTimeObj1 = \DateTime::createFromFormat('d.m.Y H:i:s', '1.1.1970 ' . $value);
                $dateTimeObj2 = \DateTime::createFromFormat('d.m.Y H:i', '1.1.1970 ' . $value);
                if ($dateTimeObj1 === false && $dateTimeObj2 === false) {
                    $invalidData[] = $value;
                }
            }
        }

        if (!empty($invalidData)) {
            $errors = $this->errorMessageBag([trans('as.options.invalid_data')]);

            return Redirect::back()->withInput()->withErrors($errors, 'top');
        }

        if ($old !== $new) {
            // We need to check if there's already a record in database
            $option = $this->user->asOptions()
                ->where('key', 'working_time')
                ->first();

            if ($option === null) {
                $option = new Option();
            }

            $option->fill([
                'key'   => 'working_time',
                'value' => Input::get('working_time')
            ]);

            $this->user->asOptions()->save($option);

            // Rebuild the NAT calendar of this user
            NAT::enqueueToRebuild($this->user);
        }

        return Redirect::back()->with(
            'messages',
            $this->successMessageBag(trans('as.options.updated'))
        );
    }

    /**
     * Show the discount form (last minute discount, day period discount)
     *
     * @param $page
     * @return View
     */
    public function discount($page = 'last-minute')
    {
        $data = [
            'page' => $page,
            'user' => $this->user
        ];

        $model = ($page === 'discount')
            ? 'App\Appointment\Models\Discount'
            : 'App\Appointment\Models\DiscountLastMinute';

        $data['user'] = $this->user;

        $model::createFormData($data);

        return $this->render($page, $data);
    }

    public function discountUpsert($page = 'last-minute')
    {
        $model = ($page === 'discount')
            ? 'App\Appointment\Models\Discount'
            : 'App\Appointment\Models\DiscountLastMinute';

        $obj          = new $model;
        $data         = Input::all();
        $data['user'] = $this->user;

        $obj->upsert($data);

        return Redirect::back();
    }
}
