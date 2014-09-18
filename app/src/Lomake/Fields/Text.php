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
        $params = [
            $this->name,
            $this->values ?: $this->default,
            $this->options
        ];

        return call_user_func_array('Form::text', $params);
    }
}
