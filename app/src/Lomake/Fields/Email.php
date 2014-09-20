<?php namespace App\Lomake\Fields;

class Email extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        return call_user_func_array('Form::input', $this->getParams());
    }

    /**
     * Get params to pass to generating function
     *
     * @return array
     */
    protected function getParams()
    {
        return [
            'email',
            $this->name,
            $this->values ?: $this->default,
            $this->options
        ];
    }
}
