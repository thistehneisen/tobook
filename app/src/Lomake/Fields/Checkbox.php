<?php namespace App\Lomake\Fields;

class Checkbox extends Base
{
    protected $options = [
        'values'  => '1',
        'default' => '1',
        'options' => []
    ];

    public function render()
    {
        $params = $this->pick('name', 'values', 'default', 'options');
        return call_user_func_array('Form::checkbox', $params);
    }
}
