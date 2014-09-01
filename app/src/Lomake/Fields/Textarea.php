<?php namespace App\Lomake\Fields;

class Textarea extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        $params = $this->pick('name', 'values', 'options');

        return call_user_func_array('Form::textarea', $params);
    }
}
