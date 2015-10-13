<?php namespace App\Lomake\Fields;

class Checkbox extends Base
{
    public function render()
    {
        if (empty($this->values)) {
            throw new \InvalidArgumentException('You must provide `values` to use Checkbox');
        }
        
        $params = [
            'name'    => $this->name,
            'values'  => $this->values,
            'default' => $this->default,
            'options' => $this->options,
            'model'   => $this->model
        ];

        return \View::make('varaa-lomake::fields.checkbox', $params)->render();
    }
}
