<?php namespace App\Lomake\Fields;

class Textarea extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = [
            $this->name,
            $this->values ?: $this->default,
            $this->options
        ];

        return call_user_func_array('Form::textarea', $params);
    }
}
