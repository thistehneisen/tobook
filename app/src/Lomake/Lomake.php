<?php namespace App\Lomake;

use App, View, Form;

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
     * The list of required assets for all Lomake forms
     *
     * @var array
     */
    protected static $requiredAssets = [];

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
            'form'       => ['class' => 'form-horizontal well', 'role' => 'form', 'enctype' => 'multipart/form-data', 'id' => 'lomake-form'],
            'template'   => 'varaa-lomake::form',
            'fields'     => [],
            'overwrite'  => false,
            'langPrefix' => '',
        ], $opt);

        if (!isset($opt['route'])) {
            throw new \InvalidArgumentException('Route name must be passed as an option in second argument');
        }
        // Attach the route to generate URL
        $opt['form']['route'] = $opt['route'];

        // Generate fields
        $fieldsData = [];
        $fields = [];
        if ($opt['overwrite'] === false) {
            foreach ($instance->fillable as $name) {
                $fieldData               = [];
                $fieldData['name']       = $name;
                $fieldData['type']       = $this->guessInputType($name);
                $fieldData['required']   = $this->isRequired($instance, $name);
                $fieldData['model']      = $instance;
                $fieldData['langPrefix'] = $opt['langPrefix'];

                $fieldsData[$name]       = $fieldData;
            }
        }

        // If user wants to overwrite fields data
        foreach ($opt['fields'] as $name => $fieldData) {
            if (!isset($fieldData['name'])) {
                $fieldData['name'] = $name;
            }
            if (!isset($fieldData['type'])) {
                $fieldData['type'] = $this->guessInputType($fieldData['name']);
            }
            if (!isset($fieldData['required'])) {
                $fieldData['required'] = $this->isRequired($instance, $fieldData['name']);
            }
            if (!isset($fieldData['model'])) {
                $fieldData['model'] = $instance;
            }
            if (!isset($fieldData['langPrefix'])) {
                $fieldData['langPrefix'] = $opt['langPrefix'];
            }

            foreach ($fieldData as $fieldDataKey => $fieldDataValue) {
                $fieldsData[$name][$fieldDataKey] = $fieldDataValue;
            }

            $isHidden = isset($fieldsData[$name]['hidden'])
                ? (bool) $fieldsData[$name]['hidden']
                : false;

            if (empty($fieldsData[$name]['type']) || $isHidden) {
                // allow caller to disable fields from being rendered
                unset($fieldsData[$name]);
            }
        }

        // create fields from data
        foreach ($fieldsData as $name => $fieldData) {
            $fields[$name] = FieldFactory::create($fieldData);
        }

        $instance = new self();
        // Update the fields list
        $instance->fields = $fields;
        $instance->opt = $opt;
        $instance->model = $instance;

        return $instance;
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
     * @param array $options
     *
     * @return string
     */
    public function open(array $options = array())
    {
        $options = array_merge($this->opt['form'], $options);

        return Form::open($options);
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
     * Return HTML markup for <head /> if needed
     *
     * @return string
     */
    public function renderHead()
    {
        $html = '';

        foreach (self::$requiredAssets as $asset) {
            switch ($asset['type']) {
                case 'js':
                    if (!empty($asset['src'])) {
                        $html .= sprintf('<script type="text/javascript" src="%s"></script>', asset($asset['src']));
                    }
                    break;
            }
        }

        return $html;
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

    /**
     * Add a requires javascript asset for the form.
     *
     * @param string $src
     */
    public static function addRequiredJs($src)
    {
        $asset = ['type' => 'js', 'src' => $src];

        self::$requiredAssets[md5(serialize($asset))] = $asset;
    }
}
