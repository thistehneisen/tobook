<?php namespace App\Lomake\Fields;

abstract class Base
{
    /**
     * A list of options used to render the field
     *
     * @var array
     */
    protected $options = [];

    public function __construct($options)
    {
        // Merge with default options
        $this->options = array_merge($this->options, $options);

        // Setup default value
        if (isset($this->options['instance'])) {
            $instance = $this->options['instance'];
            $name = $this->getName();
            $this->options['default'] = $instance->$name;
        }
    }

    /**
     * Return the name of this field
     *
     * @return string
     */
    public function getName()
    {
        return $this->options['name'];
    }

    /**
     * Return the label of this field
     *
     * @return string
     */
    public function getLabel()
    {
        $label = isset($this->options['label'])
            ? $this->options['label']
            : $this->getName();

        $label = trans($label);
        if (isset($this->options['required']) && $this->options['required']) {
            $label .= '*';
        }

        return $label;
    }

    /**
     * Pick elements from option array
     *
     * @return array
     */
    protected function pick()
    {
        $fields = func_get_args();
        $data = [];
        foreach ($fields as $field) {
            if (!isset($this->options[$field])) {
                throw new \InvalidArgumentException('Cannot find value to pick: '.$field);
            }

            $data[$field] = $this->options[$field];
        }

        return $data;
    }

    /**
     * Render the field to HTML
     *
     * @return string
     */
    abstract public function render();

    public function __toString()
    {
        return $this->render();
    }
}
