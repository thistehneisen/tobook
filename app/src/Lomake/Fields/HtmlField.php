<?php namespace App\Lomake\Fields;

class HtmlField extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        return call_user_func_array('Form::textarea', $this->getParams());
    }
}
