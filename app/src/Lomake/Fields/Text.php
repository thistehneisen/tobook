<?php namespace App\Lomake\Fields;

class Text extends Base
{
    protected $opt = [
        'options' => ['class' => 'form-control']
    ];

    /**
     * @{@inheritdoc}
     */
    public function render()
    {
        return call_user_func_array('Form::text', $this->getParams());
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
            $this->values ?: $this->default,
            $this->options
        ];
    }
}
