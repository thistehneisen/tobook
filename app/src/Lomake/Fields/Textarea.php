<?php namespace App\Lomake\Fields;

class Textarea extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        return call_user_func_array('Form::textarea', $this->getParams());
    }
}
