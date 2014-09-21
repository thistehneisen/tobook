<?php namespace App\Lomake;

use App, View, Form;
use App\Lomake\FieldFactory;

class Lomake
{
    /**
     * Options passed from user to Lomake
     *
     * @var array
     */
    protected $opt;

    /**
     * The instance of model
     *
     * @var Illuminate\Database\Eloquent\Model
     */
    protected $model;

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

        if (property_exists($this, $name)) {
            return $this->$name;
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
            'overwrite'  => false,
            'langPrefix' => ''
        ], $opt);

        if (!isset($opt['route'])) {
            throw new \InvalidArgumentException('Route name must be passed as an option in second argument');
        }
        // Attach the route to generate URL
        $opt['form']['route'] = $opt['route'];

        // Generate fields
        $fields = [];
        if ($opt['overwrite'] === false) {
            foreach ($instance->fillable as $name) {
                $field['name']       = $name;
                $field['type']       = $this->guessInputType($name);
                $field['required']   = $this->isRequired($instance, $name);
                $field['model']      = $instance;
                $field['langPrefix'] = $opt['langPrefix'];

                $fields[$name] = FieldFactory::create($field);
            }
        }

        // If user wants to overwrite generated fields
        foreach ($opt['fields'] as $name => $field) {
            $field['name']       = $name;
            $field['model']      = $instance;
            $field['langPrefix'] = $opt['langPrefix'];
            $field['required']   = $this->isRequired($instance, $name);

            $fields[$name] = FieldFactory::create($field);
        }

        // Update the fields list
        $this->fields = $fields;
        $this->opt = $opt;
        $this->model = $instance;

        return $this;
    }

    /**
     * Render the form based on provided options
     *
     * @return View
     */
    public function render()
    {
        return View::make($this->opt['template'], [
            'fields' => $this->fields,
            'opt'    => $this->opt,
            'item'   => $this->model
        ]);
    }

    /**
     * @{@inheritdoc}
     */
    public function __toString()
    {
        return (string) $this->render();
    }

    /**
     * Return Form::open of this form
     *
     * @return string
     */
    public function open()
    {
        return Form::open($this->opt['form']);
    }

    /**
     * Close the form
     *
     * @return string
     */
    public function close()
    {
        return Form::close();
    }

    /**
     * Check if this field is require
     *
     * @param Illuminate\Database\Eloquent\Model $instance
     * @param string                             $name
     *
     * @return boolean
     */
    protected function isRequired($instance, $name, $set = 'saving')
    {

        $rule = '';
        $rules = $instance->getRules();
        if (!empty($rules) && isset($rules[$name])) {
            $rule = $rules[$name];
        } else {
            // Check if this model makes use of Validating trait
            if (!method_exists($instance, 'getRulesets')) {
                // If not, consider no required
                return false;
            }

            $rulesets = $instance->getRulesets();
            $rules = $rulesets[$set];
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
