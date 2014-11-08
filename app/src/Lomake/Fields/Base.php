<?php namespace App\Lomake\Fields;

use App\Lomake\FieldInterface;

abstract class Base implements FieldInterface
{
    /**
     * A list of options used to render the field.
     * Should have those keys:
     *     name: (required) string
     *         The name of this field
     *     values: (optional) string|array
     *         Values will be used to generate field, for examples, dropdown, checkbox
     *     default: (optional) mixed
     *         Default value of this field
     *     options: (optional) array
     *         Other options that could be passed to generate field
     *     label: (optional)
     *         Should be language key for translatable, or field name will be used
     *     model: (optional)
     *         Instance of model to be populate to the form
     *     langPrefix: (optional) string
     *         Language prefix that is used to translate label of this field
     *
     *
     * @var array
     */
    protected $opt = [];

    public $name;
    public $values;
    public $default;
    public $options;
    public $label;
    public $model;
    public $langPrefix;

    /**
     * Constructor
     */
    public function __construct($opt)
    {
        $this->init($opt);
    }

    /**
     * Populate properties with data from given options
     *
     * @param array $opt
     *
     * @return void
     */
    protected function init($opt)
    {
        // Merge with default options
        $this->opt = array_merge_recursive($this->opt, $opt);
        $attrs = [
            'name',
            'values',
            'default',
            'options',
            'label',
            'model',
            'langPrefix',
        ];

        foreach ($attrs as $attr) {
            if (array_key_exists($attr, $this->opt)) {
                $this->$attr = $this->opt[$attr];
            }
        }

        // Apply filter to all property
        foreach ($attrs as $attr) {
            $methodName = 'process'.studly_case($attr);
            if (method_exists($this, $methodName)) {
                $this->$methodName();
            }
        }
    }

    /**
     * If we has a model instance, try to get value from it and set to default
     *
     * @return void
     */
    protected function processDefault()
    {
        if ($this->model !== null && empty($this->default)) {
            $name = $this->name;
            $this->default = $this->model->$name;
        }
    }

    /**
     * Automatically generate label if required
     *
     * @return void
     */
    protected function processLabel()
    {
        $label = $this->label !== null
            ? $this->label
            : $this->langPrefix.'.'.$this->name;

        $label = trans($label);
        if (isset($this->opt['required']) && $this->opt['required']) {
            $label .= '*';
        }

        $this->label = $label;
    }

    /**
     * Render the field to HTML
     *
     * @return string
     */
    abstract public function render();

    /**
     * @{@inheritdoc}
     */
    public function __toString()
    {
        return $this->render();
    }
}
