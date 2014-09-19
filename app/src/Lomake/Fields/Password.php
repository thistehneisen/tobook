<?php namespace App\Lomake\Fields;

class Password extends Text
{
    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        return call_user_func_array('Form::password', $this->getParams());
    }

    /**
     * Get params to pass to generating function
     *
     * @return array
     */
    protected function getParams()
    {
        return [
            $this->name,
            $this->options
        ];
    }
}
