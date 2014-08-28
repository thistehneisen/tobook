<?php namespace App\Lomake\Fields;

class Radio extends Base
{
    protected $options = [
        'values'  => '1',
        'default' => '1',
        'options' => []
    ];

    public function render()
    {
        $params = $this->pick('name', 'values', 'default', 'options');
        return call_user_func_array('Form::radio', $params);
    }
}
