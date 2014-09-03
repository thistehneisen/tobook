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
        return \View::make('varaa-lomake::fields.checkbox', $params)->render();
    }
}
