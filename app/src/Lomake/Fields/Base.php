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
