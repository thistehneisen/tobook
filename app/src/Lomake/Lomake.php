<?php namespace App\Lomake;

use App, View;
use App\Lomake\Fields\Factory;

class Lomake
{
    /**
     * The list of all fields in this form
     *
     * @var array
     */
    protected $fields = [];

    /**
     * $fields getter
     *
     * @return array
     */
    public function getFields()
    {
        return $this->fields;
    }

    /**
     * Magically return a field in field list if the name is matched
     * Otherwise, raise an error
     *
     * @param string $name
     *
     * @return App\Lomake\Fields\FieldInterface
     */
    public function __get($name)
    {
        if (isset($this->fields[$name])) {
            return $this->fields[$name];
        }

        $trace = debug_backtrace();
        trigger_error(
            'Cannot find field: ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            E_USER_NOTICE
        );

        return null;
    }

    /**
     * Make the form based on passed model
     *
     * @param Illuminate\Database\Eloquent\Model|string $model name of the class
     *                                                         or an instance
     * @param array                                     $opt
     *
     * @return View
     */
    public function make($model, $opt = [])
    {
        if (is_string($model)) {
            $model = App::make($model);
        }

        // Now we have an object
        $instance = $model;

        // Merge default options with values from user
        $opt = array_merge([
            'form'     => ['class' => 'form-horizontal well', 'role' => 'form', 'enctype' => 'multipart/form-data'],
            'template' => 'varaa-lomake::form',
            'fields'   => [],
            'raw'      => false
        ], $opt);

        if (!isset($opt['route'])) {
            throw new \InvalidArgumentException('Route name must be passed as an option in second argument');
        }
        // Attach the route to generate URL
        $opt['form']['route'] = $opt['route'];

        $fields = [];
        foreach ($instance->fillable as $name) {
            // Try to guess the type of this field
            $field['type'] = $this->guessInputType($name);

            // Firstly we're trying to translate field name
            // But if it's not available, use the raw name instead
            $field['label'] = $opt['trans'].'.'.$name;

            // If this is required
            $field['required'] = $this->isRequired($instance, $name);
            $field['default']  = '';
            $field['name']     = $name;
            $field['instance'] = $instance;

            $fields[$name] = Factory::create($field);
        }

        // User is able to overwrite guessing fields, for example, to create
        // a dropdown list
        // Usage:
        // $opt['fields'] = [
        //  'gender' => [
        //      'label' => 'Gender',
        //      'type' => 'dropdown',
        //      'values' => ['m' => 'Male', 'f' => 'Female']
        //  ]
        // ];
        foreach ($opt['fields'] as $name => $field) {
            $field['name']     = $name;
            $field['instance'] = $instance;

            // Automatically generate label
            if (empty($field['label'])) {
                $field['label'] = $opt['trans'].'.'.$name;
            }
            $fields[$name] = Factory::create($field);
        }

        // Update the fields list
        $this->fields = $fields;

        // If we don't want to render this form, but get the instance instead
        // This could be useful for partially rendering in order view
        if ($opt['raw'] === true) {
            return $this;
        }

        return View::make($opt['template'], [
            'fields' => $fields,
            'opt'    => $opt,
            'item'   => $instance
        ]);
    }

    /**
     * Check if this field is require
     *
     * @param Illuminate\Database\Eloquent\Model $instance
     * @param string                             $name
     *
     * @return boolean
     */
    protected function isRequired($instance, $name)
    {
        $rule = '';
        $rules = $instance->getRules();
        if (!empty($rules) && isset($rules[$name])) {
            $rule = $rules[$name];
        } else {
            // Not all rulesets have 'saving'
            // @todo: Think about an universal check supporting state
            // for all cases
            $rulesets = $instance->getRulesets();
            $rules = $rulesets['saving'];
            $rule = isset($rules[$name]) ? $rules[$name] : '';
        }

        return str_contains($rule, 'required');
    }

    /**
     * Guess the input type for this field based on the name
     *
     * @param string $name
     *
     * @return string
     */
    protected function guessInputType($name)
    {
        if (starts_with($name, 'password')) {
            return 'password';
        }

        if (starts_with($name, 'is_')) {
            return 'radio';
        }

        return 'text';
    }
}
