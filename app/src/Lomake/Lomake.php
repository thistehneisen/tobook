<?php namespace App\Lomake;

use App, View;
use App\Lomake\FieldFactory;

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

        throw new \InvalidArgumentException("This form contains no controls named `$name` ");
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
            'form'       => ['class' => 'form-horizontal well', 'role' => 'form', 'enctype' => 'multipart/form-data'],
            'template'   => 'varaa-lomake::form',
            'fields'     => [],
            'noRender'   => false,
            'langPrefix' => ''
        ], $opt);

        if (!isset($opt['route'])) {
            throw new \InvalidArgumentException('Route name must be passed as an option in second argument');
        }
        // Attach the route to generate URL
        $opt['form']['route'] = $opt['route'];

        // Generate fields
        $fields = [];
        foreach ($instance->fillable as $name) {
            $field['name']       = $name;
            $field['type']       = $this->guessInputType($name);
            $field['required']   = $this->isRequired($instance, $name);
            $field['model']      = $instance;
            $field['langPrefix'] = $opt['langPrefix'];

            $fields[$name] = FieldFactory::create($field);
        }

        // If user wants to overwrite generated fields
        foreach ($opt['fields'] as $name => $field) {
            $field['name']       = $name;
            $field['model']      = $instance;
            $field['langPrefix'] = $opt['langPrefix'];

            $fields[$name] = FieldFactory::create($field);
        }

        // Update the fields list
        $this->fields = $fields;

        // If we don't want to render this form, but get the instance instead
        // This could be useful for partially rendering in order view
        if ($opt['noRender'] === true) {
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
